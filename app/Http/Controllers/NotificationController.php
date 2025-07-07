<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationSA;
use App\Models\NotificationSale;
use App\Models\NotificationAcc;
use App\Models\NotificationUserReadSA;
use App\Models\NotificationUserReadAcc;


class NotificationController extends Controller
{
    public function index()
    {
        $group = getUserGroup();
        $user = Auth::user();
        if ($group === 'admin') {
            $notifications = NotificationSA::with(['reads' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])->orderByDesc('created_at')->get();
        } elseif ($group === 'sale') {
            $notifications = NotificationSale::where('sale_id', $user->sale_id)->orderByDesc('created_at')->get();
        } elseif ($group === 'accounting') {
            $notifications = NotificationAcc::with(['reads' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])->orderByDesc('created_at')->get();
        } else {
            $notifications = collect();
        }
        return view('notifications.index', compact('notifications'));
    }

    public function fetchLatest()
    {
        $group = getUserGroup();
        $user = Auth::user();
        if ($group === 'admin') {
            $notifications = NotificationSA::with(['reads' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])->orderByDesc('created_at')->limit(20)->get();
        } elseif ($group === 'sale') {
            $notifications = NotificationSale::where('sale_id', $user->sale_id)->orderByDesc('created_at')->limit(20)->get();
        } elseif ($group === 'accounting') {
            $notifications = NotificationAcc::with(['reads' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])->orderByDesc('created_at')->limit(20)->get();
        } else {
            $notifications = collect();
        }
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $group = getUserGroup();
        $user = Auth::user();
        if ($group === 'admin') {
            $notification = NotificationSA::find($id);
            if ($notification) {
                \App\Models\NotificationUserReadSA::firstOrCreate([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id
                ], [
                    'read_at' => now()
                ]);
                return back();
            }
        } elseif ($group === 'sale') {
            $notification = NotificationSale::where('sale_id', $user->sale_id)->find($id);
            if ($notification) {
                $notification->is_read = true;
                $notification->save();
                return back();
            }
        } elseif ($group === 'accounting') {
            $notification = NotificationAcc::find($id);
            if ($notification) {
                \App\Models\NotificationUserReadAcc::firstOrCreate([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id
                ], [
                    'read_at' => now()
                ]);
                return back();
            }
        }
        return back();
    }

    public function markAllAsRead()
    {
        $group = getUserGroup();
        $user = Auth::user();
        if ($group === 'admin') {
            $notifications = NotificationSA::all();
            foreach ($notifications as $notification) {
                \App\Models\NotificationUserReadSA::firstOrCreate([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id
                ], [
                    'read_at' => now()
                ]);
            }
            return back();
        } elseif ($group === 'sale') {
            NotificationSale::where('sale_id', $user->sale_id)->update(['is_read' => true]);
            return back();
        } elseif ($group === 'accounting') {
            $notifications = NotificationAcc::all();
            foreach ($notifications as $notification) {
                \App\Models\NotificationUserReadAcc::firstOrCreate([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id
                ], [
                    'read_at' => now()
                ]);
            }
            return back();
        }
        return back();
    }

    public function goToNotification($id)
    {
        $group = getUserGroup();
        $user = Auth::user();
        $url = '#';
        if ($group === 'admin') {
            $notification = NotificationSA::find($id);
            if ($notification) {
                \App\Models\NotificationUserReadSA::firstOrCreate([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id
                ], [
                    'read_at' => now()
                ]);
                $url = $notification->url ?: '#';
            }
        } elseif ($group === 'sale') {
            $notification = NotificationSale::where('sale_id', $user->sale_id)->find($id);
            if ($notification) {
                $notification->is_read = true;
                $notification->save();
                $url = $notification->url ?: '#';
            }
        } elseif ($group === 'accounting') {
            $notification = NotificationAcc::find($id);
            if ($notification) {
                \App\Models\NotificationUserReadAcc::firstOrCreate([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id
                ], [
                    'read_at' => now()
                ]);
                $url = $notification->url ?: '#';
            }
        }
        return redirect($url);
    }
}
