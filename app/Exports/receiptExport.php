<?php

namespace App\Exports;

use App\Models\payments\invoiceModel;
use App\Models\payments\paymentModel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class receiptExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $paymentIdsArray;
    private $payments;
    private $num;

    public function __construct($paymentIdsArray)
    {
        $this->paymentIdsArray = $paymentIdsArray;
        //dd($this->quoteIdsArray); // ตรวจสอบค่า
    }

    public function collection()
    {
        //
        if (is_array($this->paymentIdsArray) && count($this->paymentIdsArray) > 0) {
            $query = paymentModel::whereIn('payment_id', $this->paymentIdsArray)->get();
            $this->payments = $query;
        } else {
            $this->payments = collect(); // กำหนดค่าเริ่มต้นเป็น empty collection
        }
        return $this->payments;
    }

    public function headings(): array
    {
        return array_merge([
            'ลำดับ',
            'Payment No.',
            'วันที่ออกใบเสร็จ',
            'เลขที่ใบเสนอราคา',
            'เลขที่อ้างอิงใบจองทัวร์',
            'รายละเอียดการชำระ',
            'จำนวนเงิน:บาท',
            'ประเภท',
            'สถานะการชำระเงิน',
        ]);
    }

    public function map($payments): array
    {
        if ($payments->payment_method === 'cash') {
            $payment_method = "วิธีการชำระเงิน : เงินสด\nวันที่ : " . date('d/m/Y : H:m', strtotime($payments->payment_in_date));
        } elseif ($payments->payment_method === 'transfer-money') {
            $payment_method = "วิธีการชำระเงิน : โอนเงิน\nวันที่ : " . date('d/m/Y : H:m', strtotime($payments->payment_in_date)) . "\nเช็คธนาคาร : " . ($payments->bank ? $payments->bank->bank_name : '');
        } elseif ($payments->payment_method === 'check') {
            $payment_method = "วิธีการชำระเงิน : เช็ค\nโอนเข้าบัญชี : " . ($payments->bank ? $payments->bank->bank_name : '') . "\nเลขที่เช็ค : " . ($payments->check_number ?? '') . "\nวันที่ : " . date('d/m/Y : H:m', strtotime($payments->payment_in_date));
        } elseif ($payments->payment_method === 'credit') {
            $payment_method = "วิธีการชำระเงิน : บัตรเครดิต\nเลขที่สลิป : " . ($item->payment_credit_slip_number ?? '') . "\nวันที่ : " .date('d/m/Y : H:m', strtotime($payments->payment_in_date));
        }

        if($payments->payment_status === 'success'){ $payment_status = 'สำเร็จ';}
        if($payments->payment_status === 'cancel'){ $payment_status = 'ยกเลิก';} 
        if($payments->payment_status === 'refund'){ $payment_status = 'คืนเงิน';}  
        if($payments->payment_status === 'wait'){ $payment_status = 'รอชำระเงิน';} 
        
        if($payments->payment_type === 'deposit'){ 
            $payment_type = 'ชำระมัดจำ';
        } else {
           $payment_type = 'ชำระเงินเต็มจำนวน' ;
        }
        
  


        return array_merge([
            ++$this->num,
            $payments->payment_number,
            date('d/m/Y',strtotime($payments->payment_in_date)),
            $payments->quote->quote_number,
            $payments->quote->quote_booking,
            $payment_method,
            number_format($payments->payment_total,2),
            $payment_type,
            $payment_status,

 

        ]);
    }

    public function columnWidths(): array
    {
      return [
        'B' => 20,
        'C' => 20,
        'D' => 20,
        'E' => 50,
        'F' => 25,
        'G' => 25,
        'H' => 25,
        'I' => 30,
      ];
    }




}
