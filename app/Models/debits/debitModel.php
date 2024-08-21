<?php

namespace App\Models\debits;

use App\Models\booking\bookingModel;
use App\Models\booking\countryModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\wholesale\wholesaleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class debitModel extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'debit_notes';
    protected $primaryKey = 'debit_note_id';
    protected $fillable = [
        'customer_id',
        'debit_note_number',
        'debit_note_date',
        'tour_id',
        'wholesale_id',
        'travel_type',
        'country_id',
        'debit_note_booking',
        'debit_note_sale',
        'debit_note_tour_code',
        'debit_note_status',
        'debit_note_discount',
        'debit_note_total',
        'debit_note_after_discount', //new
        'debit_note_price_excluding_vat', //new
        'created_by',
        'updated_by',
        'debit_note_vat_7',
        'debit_note_vat_3',
        'debit_note_grand_total',
        'debit_note_note',
        'vat_3_status',
        'vat_type',
        'payment_date',
        'payment_before_date',
        'payment_type',
        'deposit',
        'total_qty',
        'quote_number',
    ];

    // ความสัมพันธ์กับ BookingModel
    public function debit_noteBooking()
    {
        return $this->belongsTo(bookingModel::class, 'debit_note_booking', 'code');
    }

    // ความสัมพันธ์กับ CustomerModel
    public function debit_noteCustomer()
    {
        return $this->belongsTo(customerModel::class, 'customer_id', 'customer_id');
    }

    // ความสัมพันธ์กับ WholesaleModel
    public function debit_noteWholesale()
    {
        return $this->belongsTo(wholesaleModel::class, 'wholesale_id', 'id');
    }

    // Accessor เพื่อดึงข้อมูล country
    public function getdebit_noteCountriesAttribute()
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
