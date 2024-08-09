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
        'customer_id',
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
    ];
}
