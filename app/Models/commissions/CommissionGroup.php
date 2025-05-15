<?php

namespace App\Models\commissions;

use App\Models\sales\saleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionGroup extends Model
{
 protected $fillable = ['name','type'];

    // เปลี่ยนจาก User เป็น saleModel
    public function users()
    {
        return $this->belongsToMany(
            saleModel::class,            // Model ปลายทาง
            'commission_group_user',     // ชื่อ pivot table
            'commission_group_id',       // FK ของตารางนี้
            'id'                    // FK ของ saleModel (id)
        );
    }

    public function rules()
    {
        return $this->hasMany(CommissionRule::class);
    }
}
