<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('getUserGroup')) {
    function getUserGroup()
    {
        $user = Auth::user();
        if (!$user) return null;
        if ($user->hasRole(['Super Admin', 'admin'])) {
            return 'admin';
        } elseif ($user->hasRole('sale')) {
            return 'sale';
        } elseif ($user->hasRole('accounting')) {
            return 'accounting';
        }
        return null;
    }
}

if (!function_exists('routeNotificationModel')) {
    function routeNotificationModel()
    {
        $group = getUserGroup();
        if ($group === 'admin') {
            return \App\Models\NotificationSA::class;
        } elseif ($group === 'sale') {
            return \App\Models\NotificationSale::class;
        } elseif ($group === 'accounting') {
            return \App\Models\NotificationAcc::class;
        }
        return null;
    }
}
