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