<?php

namespace App\Exports;

use App\Models\invoices\taxinvoiceModel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class taxinvoiceExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $taxinvoiceIdsArray;
    private $taxinvoices;
    private $num;

    public function __construct($taxinvoiceIdsArray)
    {
        $this->taxinvoiceIdsArray = $taxinvoiceIdsArray;
        //dd($this->quoteIdsArray); // ตรวจสอบค่า
    }

    public function collection()
    {
        //
        if (is_array($this->taxinvoiceIdsArray) && count($this->taxinvoiceIdsArray) > 0) {
            $query = taxinvoiceModel::whereIn('taxinvoice_id', $this->taxinvoiceIdsArray)->get();
            $this->taxinvoices = $query;
        } else {
            $this->taxinvoices = collect(); // กำหนดค่าเริ่มต้นเป็น empty collection
        }
        return $this->taxinvoices;
    }

    public function headings(): array
    {
        return array_merge([
            'ลำดับ',
            'เลขที่ใบกำกับภาษี',
            'เลขที่ใบแจ้งหนี้',
            'วันที่ออกใบกำกับภาษี',
            'ชื่อลูกค้า',
            'Quotations',
            'จำนวนเงิน:บาท',
            'ภาษีหัก ณ ที่จ่าย:บาท',
            'สถานะ'
        ]);
    }

    public function map($taxinvoices): array
    {
        
       

        if($taxinvoices->taxinvoice_status === 'wait')
        {
            $invoice_status = 'รอดำเนินการ';

        }elseif($taxinvoices->taxinvoice_status === 'success')
        {
            $invoice_status = 'สำเร็จ';
        }else{
            $invoice_status = 'ยกเลิก';
        }


        return array_merge([
            ++$this->num,
            $taxinvoices->taxinvoice_number,
            $taxinvoices->invoice->invoice_number,
            date('d/m/Y',strtotime($taxinvoices->taxinvoice_date)),
            $taxinvoices->taxinvoiceCustomer->customer_name,
            $taxinvoices->invoice->quote->quote_number,
            number_format($taxinvoices->invoice->invoice_grand_total,2),
            number_format($taxinvoices->invoice->invoice_withholding_tax,2),
            $invoice_status
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
