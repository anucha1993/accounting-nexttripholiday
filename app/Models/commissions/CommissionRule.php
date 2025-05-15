<?php

namespace App\Models\commissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionRule extends Model
{
    use HasFactory;
     protected $fillable = ['commission_group_id','min_amount','max_amount','rate'];

    public function group()
    {
        return $this->belongsTo(CommissionGroup::class);
    }
}
