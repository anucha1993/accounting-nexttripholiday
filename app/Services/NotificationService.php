<?php

namespace App\Services;

use App\Models\NotificationSA;
use App\Models\NotificationSale;
use App\Models\NotificationAcc;
use Illuminate\Support\Carbon;

class NotificationService
{
    public function sendToSuperAdmin($message, $url, $relatedId, $relatedType)
    {
        return NotificationSA::create([
            'title' => $relatedType,
            'message' => $message,
            'type' => $relatedType,
            'notify_for' => 'notification_sa',
            'reference_id' => $relatedId,
            'url' => $url,
            'is_read' => false,
            'user_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function sendToSale($saleId, $message, $url, $relatedId, $relatedType)
    {
        return NotificationSale::create([
            'title' => $relatedType,
            'message' => $message,
            'type' => $relatedType,
            'notify_for' => 'notification_sale',
            'reference_id' => $relatedId,
            'url' => $url,
            'is_read' => false,
            'sale_id' => $saleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function sendToAccounting($message, $url, $relatedId, $relatedType)
    {
        return NotificationAcc::create([
            'title' => $relatedType,
            'message' => $message,
            'type' => $relatedType,
            'notify_for' => 'notification_acc',
            'reference_id' => $relatedId,
            'url' => $url,
            'is_read' => false,
            'user_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
