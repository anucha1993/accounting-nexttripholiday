<?php

namespace App\Models\creditnote;

use App\Models\customers\customerModel;
use App\Models\invoices\invoiceModel;
use App\Models\invoices\taxinvoiceModel;
use App\Models\quotations\quotationModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class creditNoteModel extends Model
{
    use HasFactory;
    protected $table = 'debit_note';
    protected $primaryKey = 'creditnote_id';
    protected $fillable = [
        'quote_id',
        'creditnote_number',// Auto run
        'creditnote_date', //
        'booking_number', //
        'wholesale_id', //
        'invoice_id', //
        'taxinvoice_id', //
        'creditnote_cause', //
        'creditnote_vat_exempted_amount',//
        'creditnote_pre_tax_amount',//
        'creditnote_discount',//
        'creditnote_pre_vat_amount',//
        'creditnote_vat',//
        'creditnote_include_vat',//
        'creditnote_grand_total',//
        'creditnote_withholding_tax',//
        'creditnote_withholding_tax_status',//
        'creditnote_total_new',//
        'creditnote_total_old',//
        'creditnote_difference',//
        'creditnote_note',//
        'creditnote_sale',//
        'creditnote_status', 
        'vat_type', 
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',

    ];


    public static function generateDebitNoteNumber()
    {
        $prefix = 'DBN';
        $yearMonth = now()->format('Ym');
        $lastDebitNote = self::where('creditnote_number', 'like', $prefix . $yearMonth . '-%')->latest()->first();

        if ($lastDebitNote) {
            $lastNumber = intval(substr($lastDebitNote->creditnote_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $yearMonth . '-' . $newNumber;
    }

    public function quote()
    {
        return $this->belongsTo(quotationModel::class, 'quote_id', 'quote_id');
    }
    
    public function customer()
    {
        return $this->quote->customer();
    }

    public function invoice()
    {
        return $this->belongsTo(invoiceModel::class, 'invoice_id', 'invoice_id');
    }

    

    public function taxinvoice()
    {
        return $this->belongsTo(taxinvoiceModel::class, 'taxinvoice_id', 'taxinvoice_id');
    }



}
