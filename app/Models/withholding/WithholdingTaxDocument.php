<?php

namespace App\Models\withholding;

use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithholdingTaxDocument extends Model
{
    use HasFactory;

    protected $table = 'withholding_tax_documents'; // ชื่อตารางในฐานข้อมูล

    protected $fillable = [
        'document_number',
        'ref_number',
        'customer_id',
        'document_date',
        'withholding_form',
        'total_amount',
        'total_withholding_tax',
        'total_payable',
    ];
    public static function generateDocumentNumber()
    {
        $latestDocument = self::latest('id')->first();

        if ($latestDocument) {
            $lastNumber = (int) substr($latestDocument->document_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return 'WT' . date('Y') . date('m') . '-' . $newNumber;
    }

    /**
     * ความสัมพันธ์กับตาราง customers
     */
    public function customer()
    {
        return $this->belongsTo(customerModel::class, 'customer_id');
    }

    public function items()
{
    return $this->hasMany(WithholdingTaxItem::class, 'document_id');
}



}
