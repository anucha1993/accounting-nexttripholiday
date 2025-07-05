<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSARead extends Model
{
    use HasFactory;

    protected $table = 'notification_sa_reads';

    protected $fillable = [
        'notification_id',
        'user_id',
        'read_at',
    ];

    public function notification()
    {
        return $this->belongsTo(NotificationSA::class, 'notification_id');
    }
}
