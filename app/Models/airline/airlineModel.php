<?php

namespace App\Models\airline;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class airlineModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'tb_travel_type';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'code1',
        'travel_name',
        'image',
        'status',
    ];
}
