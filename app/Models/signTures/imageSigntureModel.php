<?php

namespace App\Models\signTures;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class imageSigntureModel extends Model
{
    use HasFactory;
    protected $table = 'image_signature';
    protected $primaryKey = 'image_signature_id';
    protected $fillable = [
        'image_signature_name',
        'image_signature_path',
        'created_at',
        'updated_at',
    ];


}
