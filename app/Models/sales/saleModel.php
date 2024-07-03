<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class saleModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];
}
