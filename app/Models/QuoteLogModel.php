<?php

namespace App\Models;

use App\Models\quotations\quotationModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteLogModel extends Model
{
    use HasFactory;
    protected $table = 'quote_logs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'quote_id',
        'booking_email_status', 'booking_email_updated_at', 'booking_email_created_by',
        'invoice_status', 'invoice_updated_at', 'invoice_created_by',
        'slip_status', 'slip_updated_at', 'slip_created_by',
        'passport_status', 'passport_updated_at', 'passport_created_by',
        'appointment_status', 'appointment_updated_at', 'appointment_created_by', 'uploaded_files',
        'withholding_tax_status','withholding_tax_updated_at','withholding_tax_created_by','wholesale_tax_status',
        'wholesale_tax_status','wholesale_tax_updated_at','wholesale_tax_created_at',
        'quote_status','quote_updated_at','quote_created_by',
        'inv_status','inv_updated_at','inv_created_by',
        'depositslip_status','depositslip_updated_at','depositslip_created_by',
        'fullslip_status','fullslip_updated_at','fullslip_created_by','wholesale_skip_status',
        'customer_refund_status','customer_refund_updated_at','customer_refund_created_by',
        'wholesale_refund_status','wholesale_refund_updated_at','wholesale_refund_created_by'
    ];

    public function quote()
    {
        return $this->belongsTo(quotationModel::class);
    }

    public function updateStatus($field, $status, $createdBy)
    {
        $this->update([
            "{$field}_status" => $status,
            "{$field}_updated_at" => now(),
            "{$field}_created_by" => $createdBy,
        ]);
    }
}
