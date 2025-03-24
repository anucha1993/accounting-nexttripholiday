<?php

namespace App\Exports;

use App\Models\invoices\invoiceModel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class invoiceExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $invoiceIdsArray;
    private $invoices;
    private $num;

    public function __construct($invoiceIdsArray)
    {
        $this->invoiceIdsArray = $invoiceIdsArray;
        //dd($this->quoteIdsArray); // ตรวจสอบค่า
    }

    public function collection()
    {
        //
        if (is_array($this->invoiceIdsArray) && count($this->invoiceIdsArray) > 0) {
            $query = invoiceModel::whereIn('invoice_id', $this->invoiceIdsArray)->get();
            $this->invoices = $query;
        } else {
            $this->invoices = collect(); // กำหนดค่าเริ่มต้นเป็น empty collection
        }
        return $this->invoices;
    }

    public function headings(): array
    {
        return array_merge([
            'ลำดับ',
            'เลขที่ใบแจ้งหนี้',
            'เลขที่ใบเสนอราคา',
            'วันที่ออกใบแจ้งหนี้',
            'ชื่อลูกค้า',
            'Booking Code',
            'จำนวนเงิน:บาท',
            'ภาษีหัก ณ ที่จ่าย:บาท',
            'ผู้จัดทำ'
        ]);
    }

    public function map($invoices): array
    {
        return array_merge([
            ++$this->num,
            $invoices->invoice_number,
            $invoices->quote->quote_number,
            date('d/m/Y',strtotime($invoices->invoice_date)),
            $invoices->customer->customer_name,
            $invoices->invoice_booking,
            number_format($invoices->invoice_grand_total,2),
            number_format($invoices->invoice_withholding_tax,2),
            $invoices->created_by,
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
