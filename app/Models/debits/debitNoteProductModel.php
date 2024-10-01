<?php

namespace App\Models\debits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class debitNoteProductModel extends Model
{
    use HasFactory;
    protected $table = 'debit_note_product';
    protected $primaryKey = 'debit_note_product_id';
    protected $fillable = [
        'debit_id',
        'product_id',
        'product_name',
        'product_qty',
        'product_price',
        'product_sum',
        'expense_type',
        'vat'

    ];
}
