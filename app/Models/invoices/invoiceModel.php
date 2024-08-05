<?php

namespace App\Models\invoices;

use App\Models\booking\bookingModel;
use App\Models\booking\countryModel;
use App\Models\customers\customerModel;
use App\Models\wholesale\wholesaleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class invoiceModel extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'invoice_date',
        'tour_id',
        'wholesale_id',
        'travel_type',
        'country_id',
        'invoice_booking',
        'invoice_sale',
        'invoice_tour_code',
        'invoice_status',
        'invoice_discount',
        'invoice_total',
        'created_by',
        'updated_by',
        'invoice_vat_7',
        'invoice_vat_3',
        'invoice_grand_total',
        'invoice_note',

    ];

    // ความสัมพันธ์กับ BookingModel
    public function invoiceBooking()
    {
        return $this->belongsTo(bookingModel::class, 'invoice_booking', 'code');
    }

    // ความสัมพันธ์กับ CustomerModel
    public function invoiceCustomer()
    {
        return $this->belongsTo(customerModel::class, 'customer_id', 'customer_id');
    }

    // ความสัมพันธ์กับ WholesaleModel
    public function invoiceWholesale()
    {
        return $this->belongsTo(wholesaleModel::class, 'wholesale_id', 'id');
    }

    // Accessor เพื่อดึงข้อมูล country
    public function getInvoiceCountriesAttribute()
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
