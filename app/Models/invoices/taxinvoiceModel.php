<?php

namespace App\Models\invoices;

use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
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
            'invoice_id', // Foreign key on invoices table...
            'customer_id', // Foreign key on customers table...
            'invoice_id', // Local key on taxinvoices table...
            'customer_id'  // Local key on invoices table...
        );
    }


    
}
