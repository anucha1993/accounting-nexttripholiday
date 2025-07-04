<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'message',
        'related_type',
        'related_id',
        'status',
        'action_url',
        'data'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];
    
    /**
     * ความสัมพันธ์กับผู้ใช้ที่รับการแจ้งเตือน
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * ดึงข้อมูลโมเดลที่เกี่ยวข้อง (polymorphic)
     * 
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getRelatedModel()
    {
        if (!$this->related_type || !$this->related_id) {
            return null;
        }
        
        $modelClass = '\\App\\Models\\' . ucfirst($this->related_type);
        if (!class_exists($modelClass)) {
            return null;
        }
        
        return $modelClass::find($this->related_id);
    }
    
    /**
     * ตรวจสอบว่าการแจ้งเตือนนี้อ่านแล้วหรือยัง
     * 
     * @return bool
     */
    public function isRead(): bool
    {
        return $this->status === 'read';
    }
    
    /**
     * ตั้งค่าสถานะการแจ้งเตือนเป็น 'อ่านแล้ว'
     * 
     * @return bool
     */
    public function markAsRead(): bool
    {
        $this->status = 'read';
        return $this->save();
    }
    
    /**
     * คำนวณเวลาที่ผ่านมาแล้วในรูปแบบที่อ่านง่าย (เช่น "3 นาทีที่แล้ว")
     * 
     * @return string
     */
    public function getTimeAgoAttribute(): string
    {
        return Carbon::parse($this->created_at)->locale('th')->diffForHumans();
    }
}
