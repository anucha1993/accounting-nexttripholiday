<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationAccRead extends Model
{
    use HasFactory;

    protected $table = 'notification_acc_reads';

    protected $fillable = [
        'notification_id',
        'user_id',
        'read_at',
    ];

    public function notification()
    {
        return $this->belongsTo(NotificationAcc::class, 'notification_id');
    }
}
