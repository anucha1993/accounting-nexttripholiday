<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationAcc extends Model
{
    use HasFactory;

    protected $table = 'notification_acc';

    protected $fillable = [
        'title',
        'message',
        'type',
        'notify_for',
        'reference_id',
        'url',
    ];

    public function reads()
    {
        return $this->hasMany(\App\Models\NotificationUserReadAcc::class, 'notification_id');
    }
}
