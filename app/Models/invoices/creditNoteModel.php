<?php

namespace App\Models\invoices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class creditNoteModel extends Model
{
    use HasFactory;
    protected $table = 'credit_note';
    protected $primaryKey = 'credit_note_id';
    protected $fillable = [
        'invoice_number',
        'credit_note_number',
        'credit_note_date',
        'credit_note_status',
        'credit_note_discount',
        'credit_note_total',
        'created_by',
        'updated_by',
        'credit_note_vat_7',
        'credit_note_vat_3',
        'credit_note_grand_total',
        'credit_note_note',
        'vat_3_status',
        'vat_type',
        'payment_date',
        'credit_note_add_note',
        'grand_total_old',
        'total_sum_new',
        'difference_total',
        'payment_type',
        'payment_before_date',
        'deposit',
        'total_qty',
    ];
}
