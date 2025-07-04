<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    /**
     * @var NotificationService
     */
    protected $notificationService;
    
    /**
     * NotificationController constructor.
     *
     * @param NotificationService $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }
    
    /**
     * แสดงหน้ารายการแจ้งเตือนทั้งหมดของผู้ใช้
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * API สำหรับดึงข้อมูลการแจ้งเตือนล่าสุดของผู้ใช้
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentNotifications()
    {
        $user = Auth::user();
        \Illuminate\Support\Facades\Log::info("User requesting notifications: " . $user->name . " (ID: " . $user->id . ")");
        \Illuminate\Support\Facades\Log::info("User roles: " . implode(", ", $user->getRoleNames()->toArray()));
        
        $notifications = $this->notificationService->getRecentNotificationsForCurrentUser(20);
        \Illuminate\Support\Facades\Log::info("Found " . $notifications->count() . " notifications for user");
        
        $unreadCount = $this->notificationService->countUnreadNotificationsForCurrentUser();
        \Illuminate\Support\Facades\Log::info("Unread count: " . $unreadCount);
        
        // เพิ่มข้อมูล time_ago เข้าไปในแต่ละรายการ
        $notifications->transform(function($notification) {
            $notification->time_ago = $notification->getTimeAgoAttribute();
            return $notification;
        });
        
        return Response::json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'user_id' => $user->id,
            'user_roles' => $user->getRoleNames()
        ]);
    }
    
    /**
     * ทำเครื่องหมายว่าอ่านแล้วและเปลี่ยนเส้นทางไปยัง URL ที่เกี่ยวข้อง
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // ตรวจสอบว่าผู้ใช้ปัจจุบันเป็นเจ้าของการแจ้งเตือนนี้
        if ($notification->user_id !== Auth::id()) {
            if (request()->ajax()) {
                return Response::json([
                    'success' => false,
                    'message' => 'คุณไม่มีสิทธิ์เข้าถึงการแจ้งเตือนนี้'
                ], 403);
            }
            
            return redirect()->route('home')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงการแจ้งเตือนนี้');
        }
        
        $notification->markAsRead();
        $redirectUrl = $notification->action_url;
        
        if (request()->ajax()) {
            return Response::json([
                'success' => true,
                'redirectUrl' => $redirectUrl
            ]);
        }
        
        if ($redirectUrl) {
            return redirect()->to($redirectUrl);
        }
        
        return back()->with('success', 'ทำเครื่องหมายเป็นอ่านแล้ว');
    }
    
    /**
     * ทำเครื่องหมายว่าอ่านแล้วทั้งหมด
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $this->notificationService->markAllAsReadForCurrentUser();
        return back()->with('success', 'ทำเครื่องหมายเป็นอ่านแล้วทั้งหมด');
    }
    
    /**
     * API สำหรับทำเครื่องหมายว่าอ่านแล้วทั้งหมด
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiMarkAllAsRead()
    {
        $count = $this->notificationService->markAllAsReadForCurrentUser();
        return Response::json([
            'success' => true,
            'count' => $count
        ]);
    }
}
