<?php

namespace App\Models\payments;

use App\Models\bank\bankModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\quotations\quotationModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class paymentModel extends Model
{


    use HasFactory;
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
    'payment_cancel_note','payment_wholesale_date',
    'payment_bank_customer_number','payment_doc_type',
    'payment_doc_number', 'payment_type', 'payment_total', 'payment_method', 'payment_in_date', 'payment_bank_number', 'payment_date_time', 'payment_bank', 'payment_check_number', 'payment_check_date', 'payment_credit_slip_number', 'payment_file_path', 'payment_status', 'payment_number', 'created_by', 'payment_cancel_note', 'payment_cancel_file_path', 'payment_refund_total', 'payment_quote_id'];



    public function quote()
    {
        return $this->belongsTo(quotationModel::class, 'payment_quote_id', 'quote_id');
    }

    
    public function bank()
    {
        return $this->belongsTo(bankModel::class, 'payment_bank', 'bank_id');
    }



    public function paymentCustomer()
    {
        return $this->hasOneThrough(
            customerModel::class,
            quotationModel::class,
            'quote_id', // Foreign key on invoices table...
            'customer_id', // Foreign key on customers table...
            'payment_quote_id', // Local key on taxinvoices table...
            'customer_id'  // Local key on invoices table...
        );
    }


}
