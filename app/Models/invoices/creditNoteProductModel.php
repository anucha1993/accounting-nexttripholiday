<?php

namespace App\Models\invoices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class creditNoteProductModel extends Model
{
    use HasFactory;
    protected $table = 'credit_note_product';
    protected $primaryKey = 'credit_note_product_id';
    protected $fillable = [
        'credit_note_id',
        'product_id',
        'product_name',
        'credit_note_qty',
        'credit_note_price',
        'credit_note_sum',
        'expense_type',

    ];
}
