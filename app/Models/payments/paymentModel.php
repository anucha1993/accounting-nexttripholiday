<?php

namespace App\Models\payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentModel extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'payment_doc_type',
        'payment_doc_number',
        'payment_type',
        'payment_total',
        'payment_method',
        'payment_in_date',
        'payment_bank_number',
        'payment_date_time',
        'payment_bank',
        'payment_check_number',
        'payment_check_date',
        'payment_credit_slip_number',
        'payment_file_path',
        'created_by',
    ];
}