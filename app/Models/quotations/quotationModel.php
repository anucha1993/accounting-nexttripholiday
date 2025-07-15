<?php

namespace App\Models\quotations;

use App\Models\QuoteLogModel;
use App\Models\sales\saleModel;
use App\Models\booking\bookingModel;
use App\Models\booking\countryModel;
use App\Models\payments\paymentModel;
use App\Models\inputTax\inputTaxModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\wholesale\wholesaleModel;
use App\Http\Controllers\quotations\quoteLog;
use App\Models\airline\airlineModel;
use App\Models\creditnote\creditNoteModel;
use App\Models\debitnote\debitNoteModel;
use App\Models\invoices\invoiceModel;
use App\Models\payments\paymentWholesaleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class quotationModel extends Model
{
    use HasFactory;
    protected $table = 'quotation';
    protected $primaryKey = 'quote_id';
    protected $fillable = [
        'quote_number',
        'customer_id',
        'quote_tour_name',
        'quote_tour_name1', // NEW
        'quote_date_start',
        'quote_date_end',
        'quote_airline',
        'quote_country',
        'quote_wholesale',
        'quote_tour_number',
        'quote_tour_code',
        'quote_tour',
        'quote_date',
        'quote_booking',
        'quote_sale',
        'quote_numday',
        'quote_status',
        'quote_note',
        'vat_type',
        'payment_before_date',
        'payment_type',
        'deposit',
        'payment_date',
        'total_qty',
        'quote_pax_total',
        'wholesale_payment_status',
        'wholesale_payment_total',
        'payment',
        'quote_payment_type',
        'quote_payment_date',
        'quote_payment_date_full', // NEW
        'quote_payment_price',
        'quote_payment_extra', // NEW
        'quote_payment_total_full', //NEW
        'quote_payment_total', //NEW
        'quote_vat_exempted_amount',
        'quote_pre_tax_amount',
        'quote_discount',
        'quote_pre_vat_amount',
        'quote_vat',
        'quote_include_vat',
        'quote_grand_total',
        'quote_withholding_tax',
        'quote_withholding_tax_status',
        'quote_booking_create',
        'created_by',
        'updated_by',
        'quote_cancel_note',
        'quote_payment_status',
    ];

    // ความสัมพันธ์กับ CampaignSource (customer_campaign_source)
    public function campaignSource()
    {
        // This is a placeholder. Actual campaign source name will be joined in controller or accessed via $campaignSource array in the view.
        return null;
    }

    public function airline()
    {
        return $this->belongsTo(airlineModel::class, 'quote_airline', 'id');
    }

    public function paymentWholesaleLatest()
    {
        return $this->hasOne(paymentWholesaleModel::class, 'payment_wholesale_quote_id', 'quote_id')->latest('payment_wholesale_id'); // เพิ่ม latest() เพื่อดึงข้อมูลล่าสุด
    }

    public function customer()
    {
        return $this->belongsTo(customerModel::class, 'customer_id', 'customer_id');
    }

    public function InputTaxVat()
    {
        return $this->belongsTo(inputTaxModel::class, 'quote_id', 'input_tax_quote_id');
    }

    public function getTotalInputTaxVat()
    {
        // ตรวจสอบว่ามีแถวที่ input_tax_file ไม่เป็น NULL หรือไม่
        $hasFile = $this->InputTaxVat()->whereNotNull('input_tax_file')->exists();

        if ($hasFile) {
            // กรณีที่ input_tax_file ไม่เป็น NULL
            $total = $this->InputTaxVat()
                ->whereNotNull('input_tax_file')
                ->whereNotIn('input_tax_type', [1, 3])
                ->where('input_tax_status', 'success')
                ->sum(\DB::raw('COALESCE(input_tax_vat, 0) - COALESCE(input_tax_withholding, 0)'));
        } else {
            // กรณีที่ input_tax_file เป็น NULL
            $total = $this->InputTaxVat()
                ->whereNotIn('input_tax_type', [1, 3])
                ->whereNull('input_tax_file')
                ->sum('input_tax_withholding');
        }

        return $total ?? 0; // คืนค่า 0 หากไม่มีผลลัพธ์
    }

    public function getTotalInputTaxVatType()
    {
        // ตรวจสอบว่า input_tax_type = 3 หรือไม่
        $total = $this->InputTaxVat()
            ->whereIn('input_tax_type', [3, 1])
            ->sum('input_tax_grand_total');
        return $total ?? 0; // คืนค่า 0 หากไม่มีผลลัพธ์
    }

    public function calculateNetProfit()
    {
        $paymentCustomer = $this->GetDeposit(); // ลูกค้าชำระเงิน
        $paymentWhosale = $this->GetDepositWholesale(); // โอนเงินโฮลเซลล์
        $inputTaxTotal = $this->getTotalInputTaxVat(); // ภาษีซื้อ
        $withholdingTax = $this->InputTaxVat()->sum('input_tax_withholding'); // หักโฮลเซลล์
        $vatClaim = $inputTaxTotal - $this->InputTaxVat()->sum('input_tax_vat'); // VAT ที่เคลมได้

        // เงื่อนไขที่ 1: กำไรสุทธิ
        $profitCondition1 = $paymentCustomer - $paymentWhosale - $inputTaxTotal - $withholdingTax;

        // เงื่อนไขที่ 2: กำไรสุทธิจริงหลังเคลม VAT
        $profitCondition2 = $paymentCustomer - $paymentWhosale - ($vatClaim + $withholdingTax);

        return [
            'profitCondition1' => number_format($profitCondition1, 2),
            'profitCondition2' => number_format($profitCondition2, 2),
        ];
    }

    protected static function booted()
    {
        static::created(function ($quote) {
            // Create a QuoteLog row for the new quote
            \App\Models\QuoteLogModel::create([
                'quote_id' => $quote->quote_id,
                'booking_email_status' => 'ยังไม่ได้ส่ง',
                'invoice_status' => 'ยังไม่ได้',
                'slip_status' => 'ยังไม่ได้ส่ง',
                'passport_status' => 'ยังไม่ได้ส่ง',
                'appointment_status' => 'ยังไม่ได้ส่ง',
            ]);
        });
    }

    public function quoteLog()
    {
        // เปลี่ยนเป็น hasMany เพื่อรองรับหลายแถวใน quote_logs
        return $this->hasMany(\App\Models\QuoteLogModel::class, 'quote_id', 'quote_id');
    }

    // ความสัมพันธ์กับ BookingModel
    public function quoteBooking()
    {
        return $this->belongsTo(bookingModel::class, 'quote_booking', 'code');
    }

    public function Salename()
    {
        return $this->belongsTo(saleModel::class, 'quote_sale', 'id');
    }

    // ความสัมพันธ์กับ CustomerModel
    public function quoteCustomer()
    {
        return $this->belongsTo(customerModel::class, 'customer_id', 'customer_id');
    }

    // ความสัมพันธ์กับ invoiceModel
    public function quoteInvoice()
    {
        return $this->belongsTo(invoiceModel::class, 'quote_id', 'invoice_quote_id');
    }

    // ความสัมพันธ์กับ Payments
    public function quotePayment()
    {
        return $this->belongsTo(paymentModel::class, 'quote_id', 'payment_quote_id');
    }

    public function quotePayments()
    {
        return $this->hasMany(paymentModel::class, 'payment_quote_id', 'quote_id');
    }
    public function getRefundTotalAttribute()
    {
        return $this->quotePayments()->where('payment_type', 'refund')->whereNot('payment_status', 'cancel')->sum('payment_total');
    }
    public function getTotalAttribute()
    {
        //return $this->quotePayments()->whereNot('payment_type','refund')->whereNotNull('payment_file_path')->sum('payment_total');
        return $this->quotePayments()->whereNot('payment_status', 'cancel')->sum('payment_total');
    }

    // ความสัมพันธ์กับ Quote_log
    // public function quoteLogStatus()
    // {
    //     return $this->belongsTo(QuoteLogModel::class, 'quote_id', 'quote_id');
    // }
    // ความสัมพันธ์กับ WholesaleModel

    public function quoteWholesale()
    {
        return $this->belongsTo(wholesaleModel::class, 'quote_wholesale', 'id');
    }

    public function quoteCountry()
    {
        return $this->belongsTo(countryModel::class, 'quote_country', 'id');
    }

    // // Accessor เพื่อดึงข้อมูล country public function GetDeposit()
    public function payment()
    {
        return $this->hasOne(paymentModel::class, 'payment_quote_id', 'quote_id');
    }
    public function GetDeposit()
    {
        return $this->payment()->where('payment_status', '!=', 'cancel')->where('payment_type', '!=', 'refund')->get()->sum('payment_total');
    }
    public function Refund()
    {
        return $this->payment()->where('payment_status', '!=', 'cancel')->where('payment_type', '=', 'refund')->where('payment_file_path', '!=', null)->get()->sum('payment_total');
    }

    // // Accessor เพื่อดึงข้อมูล country public function GetDeposit()
    public function paymentWholesale()
    {
        return $this->hasMany(paymentWholesaleModel::class, 'payment_wholesale_quote_id', 'quote_id');
    }

    public function GetDepositWholesale()
    {
        return $this->paymentWholesale()
            ->where('payment_wholesale_file_name', '!=', '')
            ->get()
            ->sum(function ($paymentWholesale) {
                return $paymentWholesale->payment_wholesale_total;
            });
    }

    public function GetDepositWholesaleRefund()
    {
        return $this->paymentWholesale()
            ->where('payment_wholesale_refund_status', '=', 'success')
            ->get()
            ->sum(function ($paymentWholesale) {
                return $paymentWholesale->payment_wholesale_refund_total;
            });
    }

    public function getWholesalePaidNet()
    {
        return $this->GetDepositWholesale() - $this->GetDepositWholesaleRefund();
    }

    public function GrossProfit()
    {
        $quoteGrandTotal = $this->quote_grand_total; // ยอดใบเสนอราคา
        $cost = $this->GetDepositWholesale(); // ต้นทุนโดยรวมที่ได้จากฟังก์ชัน GetDepositWholesale

        $grossProfit = $quoteGrandTotal - $cost;

        return $grossProfit;
    }
    public function quoteLogStatus()
    {
        return $this->hasOne(inputTaxModel::class, 'input_tax_quote_id', 'quote_id');
    }

    public function inputtax()
    {
        return $this->hasOne(inputTaxModel::class, 'input_tax_quote_id', 'quote_id');
    }

    public function checkfileInputtax()
    {
        return $this->hasOne(inputTaxModel::class, 'input_tax_quote_id', 'quote_id')->where('input_tax_type', 0)->where('input_tax_status', 'success');
    }
    public function inputtaxTotal()
    {
        return $this->inputtax()
            ->where('input_tax_status', 'success')
            ->get()
            ->sum(function ($inputtax) {
                return $inputtax->input_tax_withholding;
            });
    }

    // Accessor: คำนวณยอดหักภาษี ณ ที่จ่าย (Withholding Tax Amount) เช่นเดียวกับ invoiceModel
    public function getWithholdingTaxAmountAttribute()
    {
        // ถ้าไม่มี invoice ที่เกี่ยวข้อง จะคืนค่า 0
        $invoice = $this->invoiceVat()->first();
        if (!$invoice) {
            return 0;
        }
        // ใช้ is_null เพื่อตรวจสอบว่า invoice_image เป็น NULL หรือไม่
        if (is_null($invoice->invoice_image)) {
            return is_numeric($invoice->invoice_withholding_tax) ? $invoice->invoice_withholding_tax + $invoice->invoice_vat : 0;
        }
        return $invoice->invoice_vat;
    }
    //ยอดรวมต้นทุนรวม
     public function getTotalCostAll()
    {
        $getTotalOtherCost = $this->getTotalOtherCost();
        $getWholesalePaidNet = $this->getWholesalePaidNet();
        return $getTotalOtherCost + $getWholesalePaidNet;
    }
    //ยอดรวมต้นทุนรวมอื่นๆ
    public function getTotalOtherCost()
    {
        // คำนวณต้นทุนอื่นๆ ตามตัวอย่างใน Blade
        $withholdingTaxAmount = $this->withholding_tax_amount; // accessor
        $getTotalInputTaxVat = $this->getTotalInputTaxVat();
        $hasInputTaxFile = $this->InputTaxVat()->whereNotNull('input_tax_file')->exists();
        if ($hasInputTaxFile) {
            return $withholdingTaxAmount - $getTotalInputTaxVat;
        } else {
            return $withholdingTaxAmount + $getTotalInputTaxVat;
        }
    }

    public function inputtaxTotalWholesale()
    {
        return $this->inputtax()
            ->whereIn('input_tax_type', [2, 4, 5, 6, 7])
            ->get()
            ->sum(function ($inputtax) {
                return $inputtax->input_tax_grand_total;
            });
    }

    public function invoiceVat()
    {
        return $this->hasOne(invoiceModel::class, 'invoice_quote_id', 'quote_id');
    }

    public function invoicetaxTotal()
    {
        $invoice = $this->invoiceVat()->first(); // ใช้ first() แทน get() เนื่องจากเป็นความสัมพันธ์ HasOne

        if ($invoice) {
            $inputTaxTotal = 0;
            $inputTaxTotal += $invoice->invoice_withholding_tax;
            $inputTaxTotal += $invoice->invoice_vat;

            return $inputTaxTotal;
        }

        return 0; // กรณีไม่มีข้อมูลใน invoiceVat
    }

    // debitNote
    public function debitNote()
    {
        return $this->belongsTo(debitNoteModel::class, 'quote_id', 'quote_id');
    }

    public function creditNote()
    {
        return $this->belongsTo(creditNoteModel::class, 'quote_id', 'quote_id');
    }
    // Accessor สำหรับสถานะการชำระของลูกค้า (plain text)
    public function getCustomerPaymentStatusAttribute()
    {
        return trim(strip_tags(getQuoteStatusPayment($this)));
    }

    // ฟังก์ชันคำนวณกำไรสุทธิ (Net Profit)
    public function getNetProfit()
    {
        $paymentCustomer = $this->quote_grand_total; // ลูกค้าชำระเงิน
        $totalCostAll = $this->getTotalCostAll(); // ต้นทุนรวมทั้งหมด (ต้นทุนอื่นๆ + โอนโฮลเซลล์สุทธิ)
        return $paymentCustomer - $totalCostAll;
    }
    // ฟังก์ชันคำนวณกำไรสุทธิต่อคน (Net Profit Per Pax)
    public function getNetProfitPerPax()
    {
        $pax = $this->quote_pax_total;
        if (!$pax || $pax == 0) {
            return 0;
        }
        $netProfit = $this->getNetProfit();
        return $netProfit / $pax;
    }

    public function getCommissionQt()
{
    $profit = $this->getNetProfit(); // หรือสูตรที่ต้องการ
    $saleId = $this->quote_sale;
    $people = $this->quote_pax_total;
    $result = calculateCommission($profit, $saleId, 'qt', $people);
    return $result['calculated'] ?? 0;
}
}
