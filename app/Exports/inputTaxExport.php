<?php

namespace App\Exports;

use App\Models\inputTax\inputTaxModel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class inputTaxExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $inputTaxdsArray;
    private $inputtaxs;
    private $num;

    public function __construct($inputTaxdsArray)
    {
        $this->inputTaxdsArray = $inputTaxdsArray;
        //dd($this->quoteIdsArray); // ตรวจสอบค่า
    }

    public function collection()
    {
        //
        if (is_array($this->inputTaxdsArray) && count($this->inputTaxdsArray) > 0) {
            $query = inputTaxModel::whereIn('input_tax_id', $this->inputTaxdsArray)->get();
            $this->inputtaxs = $query;
        } else {
            $this->inputtaxs = collect(); // กำหนดค่าเริ่มต้นเป็น empty collection
        }
        return $this->inputtaxs;
    }

    public function headings(): array
    {
        return array_merge([
            'ลำดับ',
            'วันที่',
            'เลขที่เอกสาร',
            'เอกสารอ้างอิง',
            'ชื่อผู้จำหน่าย',
            'เลขที่ผู้เสียภาษี',
            'มูลค่า',
            'ภาษีมูลค่าเพิ่ม',
            'สถานะ',
        ]);
    }

    public function map($inputtaxs): array
    {

        return array_merge([
            ++$this->num,
            date('d/m/Y',strtotime($inputtaxs->input_tax_date_tax)),
            $inputtaxs->input_tax_number_tax,
            $inputtaxs->invoice->taxinvoice->taxinvoice_number ?? 'ไม่มีข้อมูล',
            $inputtaxs->quote->quoteWholesale->wholesale_name_th,
            $inputtaxs->quote->quoteWholesale->textid,
            number_format($inputtaxs->input_tax_service_total,2),
            number_format($inputtaxs->input_tax_vat,2),
            $inputtaxs->input_tax_file ? 'ได้รับเอกสารแล้ว' : 'ยังไม่ได้รับเอกสาร'


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
