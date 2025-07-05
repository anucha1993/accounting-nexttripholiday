<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUserRead extends Model
{
    use HasFactory;

    protected $table = 'notification_user_reads';

    protected $fillable = [
        'user_id',
        'notification_id',
        'group',
        'read_at',
    ];
}
