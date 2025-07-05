<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUserReadSA extends Model
{
    use HasFactory;

    protected $table = 'notification_user_reads_sa';

    protected $fillable = [
        'user_id',
        'notification_id',
        'read_at',
    ];
}
