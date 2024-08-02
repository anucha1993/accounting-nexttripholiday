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
    ];

    // join booking Mysql 2 
    public function invoiceBooking()
    {
        return $this->belongsTo(bookingModel::class, 'invoice_booking', 'code');
    }
    // join customer
    public function invoiceCustomer()
    {
        return $this->belongsTo(customerModel::class, 'customer_id', 'customer_id');
    }
     // join country
     public function invoiceCountry()
     {
         return $this->belongsTo(countryModel::class, 'country_id', 'id');
     }
      // join wholesale
      public function invoiceWholesale()
      {
          return $this->belongsTo(wholesaleModel::class, 'wholesale_id', 'id');
      }
 


}
