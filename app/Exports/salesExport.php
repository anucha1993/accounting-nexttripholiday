<?php

namespace App\Exports;

use App\Models\invoices\taxinvoiceModel;
use App\Models\commissions\CommissionRule;
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
        // รับทั้ง array, string, หรือ collection
        $ids = $this->taxinvoiceIdsArray;
        if (is_string($ids)) {
            $ids = trim($ids);
            if (str_starts_with($ids, '[') && str_ends_with($ids, ']')) {
                $ids = json_decode($ids, true);
            } else {
                $ids = explode(',', $ids);
            }
        }
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $ids = array_filter($ids, function($v) { return !empty($v) && is_numeric($v); });
        if (count($ids) > 0) {
            $query = taxinvoiceModel::with([
                'invoice.quote',
                'invoice.customer',
                'invoice.quote.quoteWholesale',
                'invoice.quote.quoteCountry',
                'invoice.quote.Salename',
            ])->whereIn('taxinvoice_id', $ids)->get();
            $this->taxinvoices = $query;
        } else {
            $this->taxinvoices = collect();
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
        // เตรียมข้อมูลเหมือนในตาราง blade
        $quote = $taxinvoices->invoice->quote;
        $customer = $taxinvoices->invoice->customer;
        $wholesale = $quote->quoteWholesale;
        $country = $quote->quoteCountry;
        $sale = $quote->Salename;

        $withholdingTaxAmount = $taxinvoices->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
        $getTotalInputTaxVat = $quote?->getTotalInputTaxVat() ?? 0;
        $hasInputTaxFile = $quote->InputTaxVat()->whereNotNull('input_tax_file')->exists();
        $paymentInputtaxTotal = $hasInputTaxFile ? $withholdingTaxAmount - $getTotalInputTaxVat : $withholdingTaxAmount + $getTotalInputTaxVat;

        $serviceAmount = $quote->quote_grand_total;
        $discountAmount = $quote->quote_discount;
        $netAmount = $serviceAmount - $discountAmount;
        $wholesalePayment = $quote->GetDepositWholesale() - $quote->GetDepositWholesaleRefund();
        $totalCost = $wholesalePayment + $paymentInputtaxTotal;
        $profit = $netAmount - $totalCost;
        $people = $quote->quote_pax_total;
        $profitPerPerson = $people > 0 ? $profit / $people : 0;

        // ใช้ฟังก์ชัน calculateCommission จาก Helper เพื่อให้ตรงกับตารางหน้าเว็บ
        $saleId = $sale->id ?? null;
        $commissionQt = $saleId ? calculateCommission($profitPerPerson, $saleId, 'qt', $people) : ['amount'=>0,'calculated'=>0,'type'=>'','percent'=>0];
        $commissionTotal = $saleId ? calculateCommission($profit, $saleId, 'total', $people) : ['amount'=>0,'calculated'=>0,'type'=>'','percent'=>0];

        // แสดงผลลัพธ์แบบเดียวกับตาราง blade
        $step = str_starts_with($commissionQt['type'] ?? '', 'step') ? number_format($commissionQt['amount'], 2).'฿/คน' : '';
        $percent = str_starts_with($commissionQt['type'] ?? '', 'percent') ? number_format($commissionQt['percent'], 2).'%' : '';

        return [
            $quote->quote_number ?? 'ใบเสนอราคาถูกลบ',
            date('d/m/Y', strtotime($quote->quote_date_start)) . ' - ' . date('d/m/Y', strtotime($quote->quote_date_end)),
            $wholesale->code ?? '',
            $customer->customer_name ?? '',
            $country->iso2 ?? '',
            $quote->quote_tour_name ?? $quote->quote_tour_name1 ?? '',
            $sale->name ?? '',
            $people,
            number_format($serviceAmount - $discountAmount, 2),
            number_format($discountAmount, 2),
            number_format($netAmount, 2),
            number_format($wholesalePayment, 2),
            number_format($paymentInputtaxTotal, 2),
            number_format($totalCost, 2),
            number_format($profit, 2),
            number_format($profitPerPerson, 2),
            number_format($commissionQt['calculated'], 2),
            $step,
            $percent,
        ];
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
