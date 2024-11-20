<?php

namespace App\Models\withholding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithholdingTaxItem extends Model
{
    use HasFactory;

    protected $table = 'withholding_tax_items';

    protected $fillable = [
        'document_id',
        'income_type',
        'tax_rate',
        'amount',
        'withholding_tax',
    ];

    public function document()
    {
        return $this->belongsTo(WithholdingTaxDocument::class, 'document_id');
    }
}
