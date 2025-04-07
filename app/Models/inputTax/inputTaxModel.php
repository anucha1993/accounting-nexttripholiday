<?php

namespace App\Models\inputTax;

use App\Models\invoices\invoiceModel;
use App\Models\invoices\taxinvoiceModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\quotations\quotationModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class inputTaxModel extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'input_tax';
    protected $primaryKey = 'input_tax_id';
    protected $fillable = [
        'input_tax_type',
        'input_tax_ref',
        'input_tax_withholding',
        'input_tax_vat',
        'input_tax_grand_total',
        'input_tax_file',
        'input_tax_quote_id',
        'input_tax_quote_number',
        'input_tax_cancel',
        'input_tax_status',
        'input_tax_service_total',
        'input_tax_wholesale',
        'input_tax_date',
        'created_by',
        'upated_by',
        'input_tax_withholding_status',
        'input_tax_wholesale_type',
        'input_tax_date_doc',
        'input_tax_number_tax',
        'input_tax_date_tax',
        'taxinvoice_number',
        
    ];


    public function quote()
    {
        return $this->belongsTo(quotationModel::class, 'input_tax_quote_id', 'quote_id');
    }

    public function invoice()
    {
        return $this->hasOneThrough(
            invoiceModel::class,
            quotationModel::class,
            'quote_id', // Foreign key on quotations table
            'invoice_quote_id', // Foreign key on invoices table
            'input_tax_quote_id', // Local key on input_tax table
            'quote_id' // Local key on quotations table
        );
    }

    
    
 
   
}
