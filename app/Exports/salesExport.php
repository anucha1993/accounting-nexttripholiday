<?php

namespace App\Exports;

use App\Models\invoices\taxinvoiceModel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class salesExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
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
        return array_merge(['Quotes', 'ช่วงเวลาเดินทาง', 'โฮลเซลล์', 'ชื่อลูกค้า', 'ประเทศ', 'แพคเกจทัวร์ที่ซื้อ', 'เซลล์ผู้ขาย', 'PAX', 'ค่าบริการ', 'ส่วนลด', 'ยอดรวมสุทธิ', 'ยอดชำระโฮลเซลล์', 'ต้นทุนอื่นๆ', 'กำไร', 'กำไรเฉลี่ย:คน', 'คอมมิชชั่นทั้งสิ้น', 'ค่าคอมมิชชั่น/Step', 'ค่าคอมมิชชั่น/%']);
    }
    private $totalMath = 0;
    private $totalPeople = 0;
    private $totalSales = 0;
    private $InputtaxTotal = 0;
    private $WhosaleTotal = 0;
    private $granTotal = 0;
    private $discountTotal = 0;
    private $serviceTotal = 0;
    private $paxTotal = 0;
    public function map($taxinvoices): array
    {
        $withholdingTaxAmount = $taxinvoices->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
        $getTotalInputTaxVat = $taxinvoices->invoice->quote?->getTotalInputTaxVat() ?? 0;
        $hasInputTaxFile = $taxinvoices->invoice->quote->InputTaxVat()->whereNotNull('input_tax_file')->exists();

        if ($hasInputTaxFile) {
            // กรณี input_tax_file !== NULL
            $paymentInputtaxTotal = $withholdingTaxAmount - $getTotalInputTaxVat;
        } else {
            // กรณี input_tax_file === NULL
            $paymentInputtaxTotal = $withholdingTaxAmount + $getTotalInputTaxVat;
        }

        $profit = $taxinvoices->invoice->quote->GetDepositWholesale() - ($taxinvoices->invoice->quote->GetDepositWholesaleRefund() - $paymentInputtaxTotal);

        $totalSale = $taxinvoices->invoice->quote->quote_grand_total - $profit;
        $this->totalSales += $totalSale;
        $this->WhosaleTotal += $profit;
        $this->granTotal += $taxinvoices->invoice->quote->quote_grand_total;
        $this->discountTotal += $taxinvoices->invoice->quote->quote_discount;
        $this->serviceTotal += $taxinvoices->invoice->quote->quote_grand_total + $taxinvoices->invoice->quote->quote_discount;
        $this->paxTotal += $taxinvoices->invoice->quote->quote_pax_total;

        $commission = $totalSale - $paymentInputtaxTotal;
        // 1) กำไรรวม (total profit) — ใช้เป็นฐานคูณ %
        $people = $taxinvoices->invoice->quote->quote_pax_total;
        $totalProfit = $totalSale - $paymentInputtaxTotal;
        // 2) กำไร “ต่อคน” (profit per person) — ใช้จับช่วง Step
        $profitPerPerson = $people > 0 ? $totalProfit / $people : 0;

        $this->totalPeople += $profitPerPerson;
        $this->InputtaxTotal += $paymentInputtaxTotal;

        /// คำนวนค่าคมมิซชั่น
        $matches = \App\Models\CommissionRule::matchBoth($profitPerPerson, $totalProfit);

        $this->totalMath += $matches['step']['commission'] * $taxinvoices->invoice->quote->quote_pax_total;

        $step = '';
        $percent = '';

        if($matches['step']['rule'])
        {
            $matches['step']['rule']->name;
            $step = number_format($matches['step']['commission'], 2).'฿/คน';
        }
        if($matches['percent']['rule'])
        {
            $matches['percent']['rule']->value.'%';
            $percent = number_format($matches['percent']['commission'], 2).'฿/คน';
        }

        return array_merge([$taxinvoices->invoice->quote->quote_number ? $taxinvoices->invoice->quote->quote_number : 'ใบเสนอราคาถูกลบ', 
        date('d/m/Y', strtotime($taxinvoices->invoice->quote->quote_date_start)) . ' - ' . date('d/m/Y', strtotime($taxinvoices->invoice->quote->quote_date_start)),
         $taxinvoices->invoice->quote->quoteWholesale->code, $taxinvoices->invoice->customer->customer_name, $taxinvoices->invoice->quote->quoteCountry->iso2,
          $taxinvoices->invoice->quote->quote_tour_name ? $taxinvoices->invoice->quote->quote_tour_name : $taxinvoices->invoice->quote->quote_tour_name1, $taxinvoices->invoice->quote->Salename->name, $taxinvoices->invoice->quote->quote_pax_total, $taxinvoices->invoice->quote->quote_grand_total + $taxinvoices->invoice->quote->quote_discount, 
          number_format($taxinvoices->invoice->quote->quote_discount, 2), 
          number_format($taxinvoices->invoice->quote->quote_grand_total, 2),
          number_format($profit, 2),
          number_format($paymentInputtaxTotal, 2),
          number_format($matches['step']['commission']*$taxinvoices->invoice->quote->quote_pax_total , 2),
          number_format($totalSale, 2),
          number_format($commission, 2),
          $step,
          $percent,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
            'C' => 20,
            'D' => 50,
            'E' => 20,
            'F' => 100,
            'G' => 50,
            'H' => 10,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'M' => 20,
            'N' => 20,
            'O' => 20,
            'P' => 20,
            'Q' => 20,
            'R' => 20,
            'S' => 20,
        ];
    }
}
