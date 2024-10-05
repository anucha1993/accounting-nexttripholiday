<?php

namespace App\Models\credits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class creditModel extends Model
{
    use HasFactory;
    protected $table = 'credit_note';
    protected $primaryKey = 'credit_id';
    protected $fillable = [
       'credit_date',
       'credit_number',
       'credit_invoice',
       'credit_taxinvoice',
       'customer_id',
       'vat_type',
       'credit_withholding_tax_status',
       'credit_original_invoice_value',
       'credit_correct_value',
       'credit_difference',
       'credit_vat_exempted_amount',
       'credit_pre_tax_amount',
       'credit_discount',
       'credit_pre_vat_amount',
       'credit_vat',
       'credit_include_vat',
       'credit_grand_total',
       'credit_status',
       'credit_note',
       'credit_cause',
       'payment',
       'created_by',
       'updated_by',

    ];
}
