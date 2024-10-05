<?php

namespace App\Models\debits;

use App\Models\booking\bookingModel;
use App\Models\booking\countryModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\wholesale\wholesaleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class debitModel extends Model
{
    
    use HasFactory;
    protected $table = 'debit_note';
    protected $primaryKey = 'debit_id';
    protected $fillable = [
        'debit_date',
       'debit_number',
       'debit_invoice',
       'debit_taxinvoice',
       'customer_id',
       'vat_type',
       'debit_withholding_tax_status',
       'debit_original_invoice_value',
       'debit_correct_value',
       'debit_difference',
       'debit_vat_exempted_amount',
       'debit_pre_tax_amount',
       'debit_discount',
       'debit_pre_vat_amount',
       'debit_vat',
       'debit_include_vat',
       'debit_grand_total',
       'debit_status',
       'debit_note',
       'debit_cause',
       'payment',
       'created_by',
       'updated_by',

    ];



}
