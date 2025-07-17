<?php

namespace App\Models\mumday;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class numDayModel extends Model
{

    use HasFactory;
    protected $table = 'num_days';
    protected $primaryKey = 'num_day_id';
    protected $fillable = [
        'num_day_total',
        'num_day_name',
    ];
}
