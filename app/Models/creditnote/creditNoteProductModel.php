<?php

namespace App\Models\debits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class creditNoteProductModel extends Model
{
    use HasFactory;
    protected $table = 'credit_note_product';
    protected $primaryKey = 'credit_note_product_id';
    protected $fillable = [
        'creditnote_id',
        'product_id',
        'product_name',
        'product_qty',
        'product_price',
        'product_sum',
        'expense_type',
        'vat_status',
        'withholding_tax'
    ];
}
