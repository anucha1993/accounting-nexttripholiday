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
    private $commissionMode;
    private $saleId;
    private $wholsaleId;
    private $countryId;
    private $status;
    private $columnName;
    private $keyword;
    private $dateStart;
    private $dateEnd;
    private $campaignSourceId;

    public function __construct($taxinvoiceIdsArray, $commissionMode = null, $saleId = null, $wholsaleId = null, $countryId = null, $status = null, $columnName = null, $keyword = null, $dateStart = null, $dateEnd = null, $campaignSourceId = null)
    {
        $this->taxinvoiceIdsArray = $taxinvoiceIdsArray;
        $this->commissionMode = $commissionMode;
        $this->saleId = $saleId;
        $this->wholsaleId = $wholsaleId;
        $this->countryId = $countryId;
        $this->status = $status;
        $this->columnName = $columnName;
        $this->keyword = $keyword;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->campaignSourceId = $campaignSourceId;
    }

    public function collection()
    {
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
        $query = taxinvoiceModel::with([
            'invoice.quote',
            'invoice.customer',
            'invoice.quote.quoteWholesale',
            'invoice.quote.quoteCountry',
            'invoice.quote.Salename',
        ])->whereIn('taxinvoice_id', $ids);

        // Apply filters
        if ($this->commissionMode && $this->commissionMode !== 'all') {
            // ไม่ filter ที่นี่ แต่จะใช้ใน map
        }
        if ($this->saleId) {
            $query->whereHas('invoice.quote.Salename', function($q) {
                $q->where('id', $this->saleId);
            });
        }
        if ($this->wholsaleId) {
            $query->whereHas('invoice.quote.quoteWholesale', function($q) {
                $q->where('id', $this->wholsaleId);
            });
        }
        if ($this->countryId) {
            $query->whereHas('invoice.quote.quoteCountry', function($q) {
                $q->where('id', $this->countryId);
            });
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->columnName && $this->keyword) {
            // ตัวอย่าง: filter เฉพาะ column ที่เลือก
            $col = $this->columnName;
            $kw = $this->keyword;
            if ($col !== 'all') {
                $query->whereHas('invoice.quote', function($q) use ($col, $kw) {
                    $q->where($col, 'like', "%$kw%");
                });
            } else {
                // filter หลาย field
                $query->whereHas('invoice.quote', function($q) use ($kw) {
                    $q->where('quote_number', 'like', "%$kw%")
                        ->orWhere('taxinvoice_number', 'like', "%$kw%")
                        ->orWhere('invoice_number', 'like', "%$kw%")
                        ->orWhere('invoice_booking', 'like', "%$kw%")
                        ->orWhere('customer_name', 'like', "%$kw%")
                        ->orWhere('customer_texid', 'like', "%$kw%")
                        ;
                });
            }
        }
        if ($this->dateStart && $this->dateEnd) {
            $query->whereHas('invoice.quote', function($q) {
                $q->whereBetween('quote_date_start', [$this->dateStart, $this->dateEnd]);
            });
        }
        if ($this->campaignSourceId) {
            $query->whereHas('invoice.customer', function($q) {
                $q->where('customer_campaign_source', $this->campaignSourceId);
            });
        }
        $this->taxinvoices = $query->get();
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
        $people = $quote->quote_pax_total;

        // เลือกสูตรคำนวณตาม commission_mode
        $commissionMode = $this->commissionMode ?? 'qt';
        if ($commissionMode === 'total') {
            $profit = $netAmount - $totalCost;
            $saleId = $sale->id ?? null;
            $commission = $saleId ? calculateCommission($profit, $saleId, 'total', $people) : ['amount'=>0,'calculated'=>0,'type'=>'','percent'=>0];
            $profitPerPerson = $people > 0 ? $profit / $people : 0;
            $step = str_starts_with($commission['type'] ?? '', 'step') ? number_format($commission['amount'], 2).'฿' : '';
            $percent = str_starts_with($commission['type'] ?? '', 'percent') ? number_format($commission['percent'], 2).'%' : '';
            return [
                $quote->quote_number ?? 'ใบเสนอราคาถูกลบ',
                date('d/m/Y', strtotime($quote->quote_date_start)) . ' - ' . date('d/m/Y', strtotime($quote->quote_date_end)),
                $wholesale->code ?? '',
                $customer->customer_name ?? '',
                $country->iso2 ?? '',
                $quote->quote_tour_name ?? $quote->quote_tour_name1 ?? '',
                $sale->name ?? '',
                $people,
                number_format($serviceAmount, 2),
                number_format($discountAmount, 2),
                number_format($netAmount, 2),
                number_format($wholesalePayment, 2),
                number_format($paymentInputtaxTotal, 2),
                number_format($totalCost, 2),
                number_format($profit, 2),
                number_format($profitPerPerson, 2),
                number_format($commission['calculated'], 2),
                $step,
                $percent,
            ];
        } else { // qt หรืออื่นๆ
            $profit = $netAmount - $totalCost;
            $profitPerPerson = $people > 0 ? $profit / $people : 0;
            $saleId = $sale->id ?? null;
            $commission = $saleId ? calculateCommission($profitPerPerson, $saleId, 'qt', $people) : ['amount'=>0,'calculated'=>0,'type'=>'','percent'=>0];
            $step = str_starts_with($commission['type'] ?? '', 'step') ? number_format($commission['amount'], 2).'฿/คน' : '';
            $percent = str_starts_with($commission['type'] ?? '', 'percent') ? number_format($commission['percent'], 2).'%' : '';
            return [
                $quote->quote_number ?? 'ใบเสนอราคาถูกลบ',
                date('d/m/Y', strtotime($quote->quote_date_start)) . ' - ' . date('d/m/Y', strtotime($quote->quote_date_end)),
                $wholesale->code ?? '',
                $customer->customer_name ?? '',
                $country->iso2 ?? '',
                $quote->quote_tour_name ?? $quote->quote_tour_name1 ?? '',
                $sale->name ?? '',
                $people,
                number_format($serviceAmount, 2),
                number_format($discountAmount, 2),
                number_format($netAmount, 2),
                number_format($wholesalePayment, 2),
                number_format($paymentInputtaxTotal, 2),
                number_format($totalCost, 2),
                number_format($profit, 2),
                number_format($profitPerPerson, 2),
                number_format($commission['calculated'], 2),
                $step,
                $percent,
            ];
        }
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
