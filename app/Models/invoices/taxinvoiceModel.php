<?php

namespace App\Models\invoices;

use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
use App\Models\quotations\quotationModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class taxinvoiceModel extends Model
{
    use HasFactory;
    protected $table = 'taxinvoices';
    protected $primaryKey = 'taxinvoice_id';
    protected $fillable = [
        'taxinvoice_number',
        'taxinvoice_date',
        'invoice_id',
        'invoice_number',
        'taxinvoice_note',
        'created_by',
        'updated_by',
        'taxinvoice_status',
        'taxinvoice_cancel_note',
    ];
    
    public function invoice()
    {
        return $this->belongsTo(invoiceModel::class, 'invoice_id');
    }

    public function taxinvoiceCustomer()
    {
        return $this->hasOneThrough(
            customerModel::class,
            invoiceModel::class,
            'invoice_id', 
            'customer_id', 
            'invoice_id', 
            'customer_id'  
        );
    }


    


    
}
