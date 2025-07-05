<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSale extends Model
{
    use HasFactory;

    protected $table = 'notification_sale';

    protected $fillable = [
        'title',
        'message',
        'type',
        'notify_for',
        'reference_id',
        'is_read',
        'sale_id',
        'url',
    ];
}
