<?php

namespace App\Models\invoices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoicePorductsModel extends Model
{
    use HasFactory;

    protected $table = 'invoice_product';
    protected $primaryKey = 'invoice_product_id';
    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_name',
        'product_qty',
        'product_price',
        'product_sum',
        'expense_type',
        'vat_status',
        'withholding_tax',
    ];
}
