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
    ];

    public function InputTaxVat()
    {
        return $this->belongsTo(inputTaxModel::class, 'quote_id', 'input_tax_quote_id');
    }

    public function getTotalInputTaxVat()
    {
        $query = $this->InputTaxVat(); // ดึง Query Builder จากความสัมพันธ์ InputTaxVat
    
        if ($query->whereNotNull('input_tax_file')->exists()) {
            // กรณีที่ input_tax_file ไม่เป็น NULL
            $total = $query->whereNotNull('input_tax_file')
                           ->selectRaw('SUM(input_tax_vat - input_tax_withholding) as total')
                           ->value('total');
        } else {
            // กรณีที่ input_tax_file เป็น NULL
            $total = $query->whereNull('input_tax_file')
                           ->selectRaw('SUM(input_tax_vat + input_tax_withholding) as total')
                           ->value('total');
        }
    
        // กรณีที่ผลรวมเป็น null ให้คืนค่า 0
        return $total ?? 0;
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
        return $this->hasOne(QuoteLogModel::class);
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
        return $this->payment()
            ->where('payment_status', '!=', 'cancel')
            ->get()
            ->sum(function ($payment) {
                return $payment->payment_total - $payment->payment_refund_total;
            });
    }

    // // Accessor เพื่อดึงข้อมูล country public function GetDeposit()
    public function paymentWholesale()
    {
        return $this->hasOne(paymentWholesaleModel::class, 'payment_wholesale_quote_id', 'quote_id');
    }

    public function GetDepositWholesale()
    {
        return $this->paymentWholesale()
            ->get()
            ->sum(function ($paymentWholesale) {
                return $paymentWholesale->payment_wholesale_total - $paymentWholesale->payment_wholesale_refund_total;
            });
    }

    public function GrossProfit()
    {
        $quoteGrandTotal = $this->quote_grand_total; // ยอดใบเสนอราคา
        $cost = $this->GetDepositWholesale(); // ต้นทุนโดยรวมที่ได้จากฟังก์ชัน GetDepositWholesale

        $grossProfit = $quoteGrandTotal - $cost;

        return $grossProfit;
    }

    public function inputtax()
    {
        return $this->hasOne(inputTaxModel::class, 'input_tax_quote_id', 'quote_id');
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

    public function inputtaxTotalWholesale()
    {
        return $this->inputtax()
            ->where('input_tax_type', 2)
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





    // public function getquoteCountriesAttribute()
    // {
    //     // แปลงค่า country_id จาก JSON string เป็น array
    //     $countryIds = json_decode($this->attributes['country_id'], true);

    //     // ตรวจสอบว่า countryIds ไม่เป็น null หรือว่างเปล่า
    //     if (is_array($countryIds) && count($countryIds) > 0) {
    //         // ดึงข้อมูลจาก CountryModel ตาม country_ids ที่ได้มา
    //         return countryModel::whereIn('id', $countryIds)->get();
    //     }

    //     return collect(); // คืนค่า collection ว่างเปล่าถ้าไม่มี country_ids
    // }

}
