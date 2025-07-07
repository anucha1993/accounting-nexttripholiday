<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSA extends Model
{
    use HasFactory;

    protected $table = 'notification_sa';

    protected $fillable = [
        'title',
        'message',
        'type',
        'notify_for',
        'reference_id',
        'url',
        'sale_id',
    ];

    public function reads()
    {
        return $this->hasMany(\App\Models\NotificationUserReadSA::class, 'notification_id');
    }
}
