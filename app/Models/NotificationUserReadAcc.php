<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUserReadAcc extends Model
{
    use HasFactory;

    protected $table = 'notification_user_reads_acc';

    protected $fillable = [
        'user_id',
        'notification_id',
        'read_at',
    ];
}
