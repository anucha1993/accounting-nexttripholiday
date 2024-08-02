<?php

namespace App\Models\booking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class countryModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'tb_country';
    protected $primaryKey = 'id';
    protected $fillable = [
        'country_name_th',
        'country_name_end',
    ];
}
