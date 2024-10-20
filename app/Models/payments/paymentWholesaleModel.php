<?php

namespace App\Models\payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentWholesaleModel extends Model
{
    use HasFactory;
    protected $table = 'payment_wholesale';
    protected $primaryKey = 'payment_wholesale_id';
    protected $fillable = [
        'payment_wholesale_doc',
        'payment_wholesale_total',
        'payment_wholesale_type',
        'payment_wholesale_file_name',
        'payment_wholesale_file_path',
        'payment_wholesale_number',
        'payment_wholesale_doc_type',
        
        'created_by',
        'updated_by',
    ];
}
