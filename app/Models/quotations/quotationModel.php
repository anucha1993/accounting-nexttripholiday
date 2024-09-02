<?php

namespace App\Models\quotations;

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
        'customer_id',
        'quote_number',
        'quote_date',
        'tour_id',
        'wholesale_id',
        'travel_type',
        'country_id',
        'quote_booking',
        'quote_sale',
        'quote_tour_code',
        'quote_status',
        'quote_discount',
        'quote_total',
        'created_by',
        'updated_by',
        'quote_vat_7',
        'quote_vat_3',
        'quote_grand_total',
        'quote_note',
        'vat_3_status',
        'vat_type',
        'payment_date',
        'payment_before_date',
        'payment_type',
        'deposit',
        'total_qty',
        'wholesale_payment_status',
        'wholesale_payment_total',
        'payment',
    ];

    
    // ความสัมพันธ์กับ BookingModel
    public function quoteBooking()
    {
        return $this->belongsTo(bookingModel::class, 'quote_booking', 'code');
    }

    // ความสัมพันธ์กับ CustomerModel
    public function quoteCustomer()
    {
        return $this->belongsTo(customerModel::class, 'customer_id', 'customer_id');
    }

    // ความสัมพันธ์กับ WholesaleModel
    public function quoteWholesale()
    {
        return $this->belongsTo(wholesaleModel::class, 'wholesale_id', 'id');
    }

    // Accessor เพื่อดึงข้อมูล country
    public function getquoteCountriesAttribute()
    {
        // แปลงค่า country_id จาก JSON string เป็น array
        $countryIds = json_decode($this->attributes['country_id'], true);

        // ตรวจสอบว่า countryIds ไม่เป็น null หรือว่างเปล่า
        if (is_array($countryIds) && count($countryIds) > 0) {
            // ดึงข้อมูลจาก CountryModel ตาม country_ids ที่ได้มา
            return countryModel::whereIn('id', $countryIds)->get();
        }

        return collect(); // คืนค่า collection ว่างเปล่าถ้าไม่มี country_ids
    }
    
}
