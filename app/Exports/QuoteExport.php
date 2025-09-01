<?php

namespace App\Exports;

use App\Models\quotations\quotationModel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class QuoteExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithCustomStartCell, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $quoteIdsArray;
    private $quotations;
    private $num = 0;
    private $totalPax = 0;
    private $totalGrandTotal = 0;
    private $totalWholesaleBalance = 0;

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
            'วันที่ใบจองทัวร์', 
            'เลขที่ใบจองทัวร์', 
            'โปรแกรมทัวร์',
            'วันที่เดินทาง', 
            'ชื่อลูกค้า', 
            'Pax', 
            'ประเทศ', 
            'สายการบิน', 
            'โฮลเซลล์', 
            'การชำระของลูกค้า', 
            'ยอดใบแจ้งหนี้',
            'การชำระโฮลเซลล์', 
            'ค้างชำระโฮลเซล', 
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

    $textGetQuoteStatusPayment = strip_tags(getQuoteStatusPayment($quotations));
    $this->totalPax += $quotations->quote_pax_total;
    $this->totalGrandTotal += $quotations->quote_grand_total;
    $this->totalWholesaleBalance += ($quotations->inputtaxTotalWholesale() - $quotations->getWholesalePaidNet());
      
    
        return array_merge([
            ++$this->num, 
            $quotations->quote_number,
            date('d/m/Y',strtotime($quotations->created_at)),
            $quotations->quote_booking,
            $quotations->quote_tour_name ? $quotations->quote_tour_name : $quotations->quote_tour_name1,
            date('d/m/Y', strtotime($quotations->quote_date_start)) . '-' . date('d/m/Y', strtotime($quotations->quote_date_end)),
            $quotations->quotecustomer->customer_name,
            number_format($quotations->quote_pax_total,2),
            $quotations->quoteCountry->country_name_th,
            $quotations->airline->code,
            $quotations->quoteWholesale->code,
            $textGetQuoteStatusPayment,
            number_format($quotations->quote_grand_total, 2),
            number_format(($quotations->inputtaxTotalWholesale() - $quotations->getWholesalePaidNet()), 2),
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

    public function startCell(): string
{
    return 'A1';
}

public function registerEvents(): array
{
    return [
        AfterSheet::class => function(AfterSheet $event) {
            $lastRow = $event->sheet->getHighestRow();
            $footerRow = $lastRow + 1;
            
            // Add totals row
            $event->sheet->setCellValue('G'.$footerRow, 'รวม');
            $event->sheet->setCellValue('H'.$footerRow, $this->totalPax);
            $event->sheet->setCellValue('M'.$footerRow, number_format($this->totalGrandTotal, 2));
            $event->sheet->setCellValue('N'.$footerRow, number_format($this->totalWholesaleBalance, 2));
            
            // Style the footer row
            $event->sheet->getStyle('G'.$footerRow.':N'.$footerRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
        },
    ];
}

}
