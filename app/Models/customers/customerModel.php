<?php

namespace App\Models\customers;

use App\Models\campaignModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class customerModel extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_name',
        'customer_number',
        'customer_email',
        'customer_texid',
        'customer_tel',
        'customer_fax',
        'customer_date',
        'customer_address',
        'customer_social_id',
        'customer_campaign_source',
    ];

     public function campaign_source()
    {
        return $this->hasOne(campaignModel::class, 'campaign_source_id', 'customer_campaign_source');
    }

}
