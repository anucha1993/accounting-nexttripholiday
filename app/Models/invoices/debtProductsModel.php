<?php

namespace App\Models\invoices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class debtProductsModel extends Model
{
    use HasFactory;
    protected $table = 'debt_product';
    protected $primaryKey = 'debt_product_id';
    protected $fillable = [
        'debt_id',
        'product_id',
        'product_name',
        'debt_qty',
        'debt_price',
        'debt_sum',
        'expense_type',

    ];
}
