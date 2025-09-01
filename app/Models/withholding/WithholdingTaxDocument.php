<?php

namespace App\Models\withholding;

use App\Models\customers\customerModel;
use App\Models\quotations\quotationModel;
use App\Models\wholesale\wholesaleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithholdingTaxDocument extends Model
{
    use HasFactory;

    protected $table = 'withholding_tax_documents'; // ชื่อตารางในฐานข้อมูล

    protected $fillable = [
        'quote_id',
        'document_number',
        'ref_number',
        'customer_id',
        'document_date',
        'withholding_form',
        'total_amount',
        'total_withholding_tax',
        'total_payable',
        'withholding_branch',
        'withholding_note',
        'image_signture_id',
        'book_no',
        'document_no',
        'wholesale_id',
        'ref_input_tax',
        'document_doc_date',
    ];

    public function GetDepositWithholdingTotal()
    {
         return $this->total_withholding_tax;
    }

public static function generateDocumentNumber()
{
    $prefix = 'WT'.date('Y').date('m').'-';

    // หา record ล่าสุดที่ "ขึ้นต้นด้วย prefix เดือนปัจจุบัน"
    $latest = self::where('document_number','like',$prefix.'%')
        ->orderBy('id','desc')
        ->first();

    if ($latest) {
        $lastNumber = (int) substr($latest->document_number, -4);
        $next = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    } else {
        // ถ้าเดือนนี้ยังไม่มี record → เริ่มที่ 0001
        $next = '0001';
    }

    return $prefix.$next;
}





    
   public static function generateDocumentNumberNo()
{
    $latestDocument = self::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', date('m'))
        ->latest('id')
        ->first();

    if ($latestDocument) {
        $lastNumber = (int) substr($latestDocument->document_no, -4);
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '0001'; // ถ้าเดือนนี้ยังไม่มีเอกสาร
    }

    return $newNumber;
}



    /**
     * ความสัมพันธ์กับตาราง customers
     */
    public function customer()
    {
        return $this->belongsTo(customerModel::class, 'customer_id');
    }
    public function quote()
    {
        return $this->belongsTo(quotationModel::class, 'quote_id');
    }

    public function wholesale()
    {
        return $this->belongsTo(wholesaleModel::class, 'wholesale_id', 'id');
    }

    public function items()
{
    return $this->hasMany(WithholdingTaxItem::class, 'document_id');
}



}
