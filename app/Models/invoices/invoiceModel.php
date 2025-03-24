<?php

namespace App\Models\invoices;

use App\Models\booking\bookingModel;
use App\Models\booking\countryModel;
use App\Models\customers\customerModel;
use App\Models\quotations\quotationModel;
use App\Models\sales\saleModel;
use App\Models\wholesale\wholesaleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class invoiceModel extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    protected $fillable = [
          'invoice_id',
          'invoice_number',
          'invoice_date',
          'invoice_quote_id',
          'invoice_quote_number',
          'invoice_booking',
          'invoice_sale',
          'customer_id',
          'invoice_vat_exempted_amount',
          'invoice_pre_tax_amount',
          'invoice_discount',
          'invoice_pre_vat_amount',
          'invoice_vat',
          'invoice_include_vat',
          'invoice_grand_total',
          'invoice_withholding_tax',
          'invoice_withholding_tax_status',
          'invoice_status',
          'invoice_note',
          'invoice_vat_type',
          'deposit',
          'created_by',
          'updated_by',
          'invoice_cancel_note',
          'invoice_image'
    ];

    public function quote()
    {
        return $this->belongsTo(quotationModel::class, 'invoice_quote_id', 'quote_id');
    }
    public function customer()
    {
        return $this->belongsTo(customerModel::class, 'customer_id', 'customer_id');
    }
   

   
    // public function getWithholdingTaxAmountAttribute()
    // {
    //     // ใช้ is_null เพื่อตรวจสอบว่า invoice_image เป็น NULL หรือไม่
    //     if (!is_null($this->invoice_image)) {
    //         return is_numeric($this->invoice_withholding_tax) ? $this->invoice_withholding_tax : 0;
    //     }
    //     return 0;
    // }

    public function getWithholdingTaxAmountAttribute()
    {
        // ใช้ is_null เพื่อตรวจสอบว่า invoice_image เป็น NULL หรือไม่
        if (is_null($this->invoice_image)) {
            return is_numeric($this->invoice_withholding_tax) ? $this->invoice_withholding_tax+$this->invoice_vat : 0;
        }
        return $this->invoice_vat;
    }

    
    
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
