<?php

namespace App\Models\commissions;

use App\Models\sales\saleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class commissionGroupModel extends Model
{
    use HasFactory;
    protected $table = 'commission_groups';
    protected $primaryKey = 'id';
    protected $casts = [
        'sale_ids' => 'array',
    ];
    protected $fillable = ['name','sale_ids','step'];


  public function commissionLists()
{
    return $this->hasMany(commissionListModel::class, 'commission_group_id', 'id');
}
   public function sales()
{
    return saleModel::whereIn('id', $this->sale_ids ?? [])->get();
}
    
}
