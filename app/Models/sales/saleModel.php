<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class saleModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name','email','phone','status'
    ];

    // Global Scope: กรองเฉพาะ sale ที่ active (ใช้สำหรับ dropdown)
    protected static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('status', 'active');
        });
    }
    
    /**
     * ดึงข้อมูล Sale ทั้งหมด รวมถึงที่ถูกปิด (inactive)
     * ใช้สำหรับแสดงข้อมูลเก่าที่มี sale ถูกปิดแล้ว
     */
    public static function withInactive()
    {
        return static::withoutGlobalScope('active');
    }
    
    /**
     * ดึงข้อมูล Sale เฉพาะที่ active
     * ใช้สำหรับ dropdown
     */
    public static function activeOnly()
    {
        return static::where('status', 'active');
    }
}
