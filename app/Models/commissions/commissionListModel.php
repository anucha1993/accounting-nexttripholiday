<?php

namespace App\Models\commissions;

use App\Models\sales\saleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commissionListModel extends Model
{
    use HasFactory;
    protected $table = 'commission_lists';
    protected $primaryKey = 'id';
    protected $fillable = ['commission_group_id','min_amount','max_amount','commission_calculate'];


}
