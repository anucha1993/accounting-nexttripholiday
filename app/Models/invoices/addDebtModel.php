<?php

namespace App\Models\invoices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class addDebtModel extends Model
{
    use HasFactory;
    protected $table = 'add_debt';
    protected $primaryKey = 'debt_id';
    protected $fillable = [
        'invoice_number',
        'debt_number',
        'debt_date',
        'debt_status',
        'debt_discount',
        'debt_total',
        'created_by',
        'updated_by',
        'debt_vat_7',
        'debt_vat_3',
        'debt_grand_total',
        'debt_note',
        'vat_3_status',
        'vat_type',
        'payment_date',
        'debt_add_note',
        'grand_total_old',
        'total_sum_new',
        'difference_total',
        'payment_type',
        'payment_before_date',
        'deposit',
        'total_qty',
    ];
}