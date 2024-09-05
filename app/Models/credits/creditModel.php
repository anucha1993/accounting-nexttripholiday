<?php

namespace App\Models\credits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class creditModel extends Model
{
    use HasFactory;
    protected $table = 'credit_note';
    protected $primaryKey = 'credit_note_id';
    protected $fillable = [
       'credit_note_number',
       'credit_note_date',
       'credit_note_note',
       'invoice_number',
       'credit_note_cause',
       'vat_3_total',
       'vat_3_status',
       'vat_7_total',
       'total',
       'discount',
       'after_discount',
       'grand_total',
       'grand_total_new',
       'invoice_grand_total',
       'difference',
       'price_excluding_vat',
       'credit_note_status',
       'vat_type',
       'payment',
       'created_by',
       'updated_by',

    ];
}
