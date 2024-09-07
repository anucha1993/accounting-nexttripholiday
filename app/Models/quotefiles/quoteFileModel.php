<?php

namespace App\Models\quotefiles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quoteFileModel extends Model
{

    protected $table = 'quote_file';
    protected $primaryKey = 'quote_file_id';
    protected $fillable = [
        'quote_number',
        'quote_file_name',
        'quote_file_path',
    ];
}
