<?php

namespace App\Models\wholesale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wholesaleModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'tb_wholesale';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'wholesale_name_th',
        'wholesale_name_en',
        'tel',
        'contact_person',
        'status',
    ];
}
