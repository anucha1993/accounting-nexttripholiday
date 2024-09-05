<?php

namespace App\Models\bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bankCompanyModel extends Model
{
    protected $table = 'bank_company';
    protected $primaryKey = 'bank_company_id';
    protected $fillable = [
        'bank_company_name',
        'bank_company_number',
        'bank_company_account_name',
        'bank_company_status',
    ];
}
