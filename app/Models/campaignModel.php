<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class campaignModel extends Model
{
    use HasFactory;
    protected $table = 'campaign_source';
    protected $primaryKey = 'campaign_source_id';
    protected $fillable = [
        'campaign_source_name',
    ];
    
}
