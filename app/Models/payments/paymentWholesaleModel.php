<?php

namespace App\Models\payments;

use App\Models\quotations\quotationModel;
use App\Models\wholesale\wholesaleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentWholesaleModel extends Model
{
    use HasFactory;
    protected $table = 'payment_wholesale';
    protected $primaryKey = 'payment_wholesale_id';
    protected $fillable = [
        'payment_wholesale_quote_id',
        'payment_wholesale_doc',
        'payment_wholesale_total',
        'payment_wholesale_type',
        'payment_wholesale_file_name',
        'payment_wholesale_file_path',
        'payment_wholesale_number',
        'payment_wholesale_doc_type',
        'payment_wholesale_refund_type',
        'payment_wholesale_refund_total',
        'payment_wholesale_refund_file_path',
        'payment_wholesale_refund_file_path1',
        'payment_wholesale_refund_file_path2',
        'payment_wholesale_refund_file_name',
        'payment_wholesale_refund_file_name1',
        'payment_wholesale_refund_file_name2',
        'payment_wholesale_refund_note',
        'payment_wholesale_refund_status',
        'payment_wholesale_date',
        'created_by',
        'updated_by',
    ];
       public function quote()
    {
        return $this->belongsTo(quotationModel::class, 'payment_wholesale_quote_id', 'quote_id');
    }
}
