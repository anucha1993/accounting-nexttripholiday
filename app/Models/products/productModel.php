<?php

namespace App\Models\products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productModel extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_name',
        'product_roles',
        'product_type',
        'product_price',
        'product_pax',
    ];
}
