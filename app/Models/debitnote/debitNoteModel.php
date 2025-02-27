<?php

namespace App\Models\debitnote;

use App\Models\invoices\invoiceModel;
use App\Models\invoices\taxinvoiceModel;
use App\Models\quotations\quotationModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class debitNoteModel extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'debit_note';
    protected $primaryKey = 'debitnote_id';
    protected $fillable = [
        'quote_id',
        'debitnote_number',// Auto run
        'debitnote_date', //
        'booking_number', //
        'wholesale_id', //
        'invoice_id', //
        'taxinvoice_id', //
        'debitnote_cause', //
        'debitnote_vat_exempted_amount',//
        'debitnote_pre_tax_amount',//
        'debitnote_discount',//
        'debitnote_pre_vat_amount',//
        'debitnote_vat',//
        'debitnote_include_vat',//
        'debitnote_grand_total',//
        'debitnote_withholding_tax',//
        'debitnote_withholding_tax_status',//
        'debitnote_total_new',//
        'debitnote_total_old',//
        'debitnote_difference',//
        'debitnote_note',//
        'debitnote_sale',//
        'debitnote_status', 
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
        $lastDebitNote = self::where('debitnote_number', 'like', $prefix . $yearMonth . '-%')->latest()->first();

        if ($lastDebitNote) {
            $lastNumber = intval(substr($lastDebitNote->debitnote_number, -4));
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
    public function invoice()
    {
        return $this->belongsTo(invoiceModel::class, 'invoice_id', 'invoice_id');
    }

    public function taxinvoice()
    {
        return $this->belongsTo(taxinvoiceModel::class, 'taxinvoice_id', 'taxinvoice_id');
    }



}
