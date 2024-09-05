<?php

namespace App\Models\bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bankModel extends Model
{
    protected $table = 'bank';
    protected $primaryKey = 'bank_id';
    protected $fillable = [
        'bank_name',
        'bank_status',
    ];
}
