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
    protected $primaryKey = 'debit_note_id';
    protected $fillable = [
       'debit_note_number',
       'debit_note_date',
       'debit_note_note',
       'invoice_number',
       'debit_note_cause',
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
       'debit_note_status',
       'vat_type',
       'payment',
       'created_by',
       'updated_by',

    ];



}
