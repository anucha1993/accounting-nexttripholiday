<?php

namespace App\Models\quotations;

use App\Models\sales\saleModel;
use App\Models\booking\bookingModel;
use App\Models\booking\countryModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\wholesale\wholesaleModel;
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
       'quote_date_start',
       'quote_date_end',
       'quote_airline',
       'quote_country',
       'quote_wholesale',
       'quote_tour_number',
       'quote_tour_code',
       'quote_tour',
       'quote_date',
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
       'wholesale_payment_status',
       'wholesale_payment_total',
       'payment',
       'quote_payment_type',
       'quote_payment_date',
       'quote_payment_price',
       'quote_payment_total',
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
    ];

    
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
    

    // // Accessor เพื่อดึงข้อมูล country
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
