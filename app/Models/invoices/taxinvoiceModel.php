<?php

namespace App\Models\invoices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}
