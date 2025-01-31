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
        'payment_wholesale_quote_id',
        'payment_wholesale_doc',
        'payment_wholesale_total',
        'payment_wholesale_type',
        'payment_wholesale_file_name',
        'payment_wholesale_file_path',
        'payment_wholesale_number',
        'payment_wholesale_doc_type',
        'payment_wholesale_refund_type',
        'payment_wholesale_refund_total',
        'payment_wholesale_refund_file_path',
        'payment_wholesale_refund_file_path1',
        'payment_wholesale_refund_file_path2',
        'payment_wholesale_refund_file_name',
        'payment_wholesale_refund_file_name1',
        'payment_wholesale_refund_file_name2',
        'payment_wholesale_refund_note',
        'created_by',
        'updated_by',
    ];
}
