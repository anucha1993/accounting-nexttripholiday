<?php

namespace App\Exports;

use App\Models\invoices\taxinvoiceModel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class saleTaxExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
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
            'วันเดือนปี',
            'เลขที่ใบแจ้งหนี้',
            'เลขที่ใบกำกับภาษี',
            'ชื่อลูกค้า',
            'เลขผู้เสียกับภาษี',
            'มูลค่าสินค้า/บริการ',
            'ภาษีมูลค่าเพิ่ม',
            'สถานะ',
        ]);
    }

    public function map($taxinvoices): array
    {
        return array_merge([
            ++$this->num,
            date('d/m/Y',strtotime($taxinvoices->taxinvoice_date)),
            $taxinvoices->invoice_number,
            $taxinvoices->taxinvoice_number,
            $taxinvoices->invoice->customer->customer_name,
            ' '.$taxinvoices->invoice->customer?->customer_texid ?? '0000000000000',
            number_format($taxinvoices->invoice->invoice_pre_vat_amount,2),
            number_format($taxinvoices->invoice->invoice_vat,2),
            $taxinvoices->taxinvoice_status === 'success' ? 'สำเร็จ' : 'ยกเลิก'

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
