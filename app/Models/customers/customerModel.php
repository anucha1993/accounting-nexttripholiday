<?php

namespace App\Models\customers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customerModel extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_name',
        'customer_number',
        'customer_email',
        'customer_texid',
        'customer_tel',
        'customer_fax',
        'customer_date',
        'customer_address',
    ];
}
