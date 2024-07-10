<?php

namespace App\Models\invoices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoiceModel extends Model
{
    use HasFactory;
    protected $table = 'invoice';
    protected $primaryKey = 'invoice_id';
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'invoice_date',
        'invoice_booking',
        'invoice_sale',
        'invoice_tour_code',
        'invoice_status',
        'invoice_discount',
        'invoice_total',
        'created_by',
        'updated_by',
    ];

}
