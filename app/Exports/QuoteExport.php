<?php

namespace App\Exports;

use App\Models\quotations\quotationModel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class QuoteExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $quoteIdsArray;
    private $quotations;
    private $num = 0;

    public function __construct($quoteIdsArray)
    {
        $this->quoteIdsArray = $quoteIdsArray;
        //dd($this->quoteIdsArray); // ตรวจสอบค่า
    }

    public function collection()
    {
        if (is_array($this->quoteIdsArray) && count($this->quoteIdsArray) > 0) {
            $query = quotationModel::whereIn('quote_id', $this->quoteIdsArray)->get();
            $this->quotations = $query;
        } else {
            $this->quotations = collect(); // กำหนดค่าเริ่มต้นเป็น empty collection
        }
        return $this->quotations;
    }

    public function headings(): array
    {
        return array_merge([
            'ลำดับ', 
            'ใบเสนอราคา',
            'เลขที่ใบจองทัวร์', 
            'โปรแกรมทัวร์', 
            'วันที่ใบจองทัวร์',
            'วันที่เดินทาง', 
            'ชื่อลูกค้า', 
            'Pax', 
            'ประเทศ', 
            'สายการบิน', 
            'โฮลเซลล์', 
            'การชำระของลูกค้า', 
            'ยอดใบแจ้งหนี้',
            'การชำระโฮลเซลล์', 
            'ผู้ขาย'
        ]);
    }
    public function map($quotations): array
{
    $latestPayment = $quotations->paymentWholesale()->latest('payment_wholesale_id')->first();

    $paymentwhosale = 'รอชำระเงิน'; // กำหนดค่าเริ่มต้น
    
    if ($latestPayment) {
        if ($latestPayment->payment_wholesale_type === null) {
            $paymentwhosale = 'รอชำระเงิน';
        } elseif ($latestPayment->payment_wholesale_type === 'deposit') {
            $paymentwhosale = 'รอชำระเงินเต็มจำนวน';
        } elseif ($latestPayment->payment_wholesale_type === 'full') {
            $paymentwhosale = 'ชำระเงินแล้ว';
        }
    }
      
    
        return array_merge([
            ++$this->num, 
            $quotations->quote_number,
            date('d/m/Y',strtotime($quotations->created_at)),
            $quotations->quote_booking,
            $quotations->quote_tour_name ? $quotations->quote_tour_name : $quotations->quote_tour_name1,
            date('d/m/Y', strtotime($quotations->quote_date_start)) . '-' . date('d/m/Y', strtotime($quotations->quote_date_end)),
            $quotations->quotecustomer->customer_name,
            $quotations->quote_pax_total,
            $quotations->quoteCountry->country_name_th,
            $quotations->airline->code,
            $quotations->quoteWholesale->code,
            getQuoteStatusPaymentReport($quotations),
            number_format($quotations->quote_grand_total),
            $paymentwhosale,
            $quotations->Salename->name
        ]);
    }

    public function columnWidths(): array
    {
      return [
       'A' => 10,
       'B' => 15,
       'C' => 15,
       'D' => 20,
       'G' => 20,
       'E' => 75,
       'F' => 25,
       'L' => 20,
       'N' => 20,
       'M' => 20,
       'O' => 25,
      ];
    }
}
