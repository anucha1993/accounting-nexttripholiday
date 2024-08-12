<?php

namespace App\Models\quotations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quoteProductModel extends Model
{
    use HasFactory;
    protected $table = 'quote_product';
    protected $primaryKey = 'quote_product_id';
    protected $fillable = [
        'quote_id',
        'product_id',
        'product_name',
        'product_qty',
        'product_price',
        'product_sum',
        'expense_type',
    ];
}
