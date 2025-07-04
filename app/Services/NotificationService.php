<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class NotificationService
{
    /**
     * สร้างการแจ้งเตือนสำหรับผู้ใช้
     *
     * @param int $userId ID ของผู้ใช้ที่จะรับการแจ้งเตือน
     * @param string $message ข้อความแจ้งเตือน
     * @param string|null $relatedType ประเภทของอ็อบเจกต์ที่เกี่ยวข้อง (เช่น quotation, booking)
     * @param int|null $relatedId ID ของอ็อบเจกต์ที่เกี่ยวข้อง
     * @param string|null $actionUrl URL สำหรับลิงก์ไปยังหน้าที่เกี่ยวข้อง
     * @param array|null $data ข้อมูลเพิ่มเติม
     * @return Notification
     */
    public function createForUser(
        int $userId,
        string $message,
        string $relatedType = null,
        int $relatedId = null,
        string $actionUrl = null,
        array $data = null
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'message' => $message,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'action_url' => $actionUrl,
            'data' => $data,
            'status' => 'unread'
        ]);
    }

    /**
     * สร้างการแจ้งเตือนสำหรับผู้ใช้หลายคน
     *
     * @param array $userIds รายการ ID ของผู้ใช้ที่จะรับการแจ้งเตือน
     * @param string $message ข้อความแจ้งเตือน
     * @param string|null $relatedType ประเภทของอ็อบเจกต์ที่เกี่ยวข้อง
     * @param int|null $relatedId ID ของอ็อบเจกต์ที่เกี่ยวข้อง
     * @param string|null $actionUrl URL สำหรับลิงก์ไปยังหน้าที่เกี่ยวข้อง
     * @param array|null $data ข้อมูลเพิ่มเติม
     * @return Collection ของ Notification objects
     */
    public function createForUsers(
        array $userIds,
        string $message,
        string $relatedType = null,
        int $relatedId = null,
        string $actionUrl = null,
        array $data = null
    ): Collection {
        $notifications = collect();
        
        foreach ($userIds as $userId) {
            $notifications->push(
                $this->createForUser($userId, $message, $relatedType, $relatedId, $actionUrl, $data)
            );
        }
        
        return $notifications;
    }
    
    /**
     * สร้างการแจ้งเตือนสำหรับกลุ่มผู้ใช้ที่มีบทบาทที่กำหนด
     *
     * @param string|array $roles บทบาทหรือรายการบทบาท (เช่น 'admin', ['accounting', 'manager'])
     * @param string $message ข้อความแจ้งเตือน
     * @param string|null $relatedType ประเภทของอ็อบเจกต์ที่เกี่ยวข้อง
     * @param int|null $relatedId ID ของอ็อบเจกต์ที่เกี่ยวข้อง
     * @param string|null $actionUrl URL สำหรับลิงก์ไปยังหน้าที่เกี่ยวข้อง
     * @param array|null $data ข้อมูลเพิ่มเติม
     * @return Collection ของ Notification objects
     */
    public function createForRoles(
        $roles,
        string $message,
        string $relatedType = null,
        int $relatedId = null,
        string $actionUrl = null,
        array $data = null
    ): Collection {
        // แปลงเป็น array หากเป็น string เดียว
        $roles = is_array($roles) ? $roles : [$roles];
        
        // ดึงผู้ใช้ที่มีบทบาทที่กำหนด
        $users = User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->get();
        
        $userIds = $users->pluck('id')->toArray();
        
        return $this->createForUsers($userIds, $message, $relatedType, $relatedId, $actionUrl, $data);
    }
    
    /**
     * สร้างการแจ้งเตือนสำหรับผู้ขาย (sale) ที่รับผิดชอบ booking ที่กำหนด
     *
     * @param int $bookingId ID ของ booking
     * @param string $message ข้อความแจ้งเตือน
     * @param string|null $relatedType ประเภทของอ็อบเจกต์ที่เกี่ยวข้อง
     * @param int|null $relatedId ID ของอ็อบเจกต์ที่เกี่ยวข้อง
     * @param string|null $actionUrl URL สำหรับลิงก์ไปยังหน้าที่เกี่ยวข้อง
     * @param array|null $data ข้อมูลเพิ่มเติม
     * @return Notification|null
     */
    public function createForBookingSalesPerson(
        int $bookingId,
        string $message,
        string $relatedType = null,
        int $relatedId = null,
        string $actionUrl = null,
        array $data = null
    ): ?Notification {
        // ค้นหา Booking และดึง user_id ของผู้ขายที่รับผิดชอบ
        $booking = DB::table('bookings')->find($bookingId);
        
        if (!$booking || !isset($booking->user_id)) {
            Log::warning("ไม่พบข้อมูล booking หรือข้อมูล user_id สำหรับ booking ID: {$bookingId}");
            return null;
        }
        
        return $this->createForUser(
            $booking->user_id,
            $message,
            $relatedType ?? 'booking',
            $relatedId ?? $bookingId,
            $actionUrl,
            $data
        );
    }
    
    /**
     * แจ้งเตือนกรณีขอคืนเงินลูกค้า (แจ้งบัญชี)
     *
     * @param int $refundId ID ของคำขอคืนเงิน
     * @param string $customerName ชื่อลูกค้า
     * @param float $refundAmount จำนวนเงินที่ขอคืน
     * @param string $actionUrl URL ไปยังหน้ารายละเอียดการขอคืนเงิน
     * @return Collection
     */
    public function notifyCustomerRefundRequest(
        int $refundId,
        string $customerName,
        float $refundAmount,
        string $actionUrl
    ): Collection {
        $message = "มีคำขอคืนเงินลูกค้า: {$customerName} จำนวน " . number_format($refundAmount, 2) . " บาท";
        
        $data = [
            'refund_id' => $refundId,
            'customer_name' => $customerName,
            'refund_amount' => $refundAmount
        ];
        
        return $this->createForRoles('accounting', $message, 'refund', $refundId, $actionUrl, $data);
    }
    
    /**
     * แจ้งเตือนกรณีบัญชีแนบสลิปคืนเงินให้ลูกค้า (แจ้ง sale)
     *
     * @param int $refundId ID ของคำขอคืนเงิน
     * @param int $bookingId ID ของการจอง
     * @param string $customerName ชื่อลูกค้า
     * @param float $refundAmount จำนวนเงินที่คืน
     * @param string $actionUrl URL ไปยังหน้ารายละเอียดการคืนเงิน
     * @return Notification|null
     */
    public function notifyCustomerRefundCompleted(
        int $refundId,
        int $bookingId,
        string $customerName,
        float $refundAmount,
        string $actionUrl
    ): ?Notification {
        $message = "เงินคืนลูกค้า: {$customerName} จำนวน " . number_format($refundAmount, 2) . " บาท ได้โอนเรียบร้อยแล้ว";
        
        $data = [
            'refund_id' => $refundId,
            'customer_name' => $customerName,
            'refund_amount' => $refundAmount
        ];
        
        return $this->createForBookingSalesPerson(
            $bookingId,
            $message,
            'refund',
            $refundId,
            $actionUrl,
            $data
        );
    }
    
    /**
     * แจ้งเตือนกรณีขอคืนเงินจากโฮลเซลล์ (แจ้งบัญชี)
     *
     * @param int $refundId ID ของคำขอคืนเงิน
     * @param string $wholesaleName ชื่อโฮลเซลล์
     * @param float $refundAmount จำนวนเงินที่ขอคืน
     * @param string $actionUrl URL ไปยังหน้ารายละเอียดการขอคืนเงิน
     * @return Collection
     */
    public function notifyWholesaleRefundRequest(
        int $refundId,
        string $wholesaleName,
        float $refundAmount,
        string $actionUrl
    ): Collection {
        $message = "มีคำขอคืนเงินจากโฮลเซลล์: {$wholesaleName} จำนวน " . number_format($refundAmount, 2) . " บาท";
        
        $data = [
            'refund_id' => $refundId,
            'wholesale_name' => $wholesaleName,
            'refund_amount' => $refundAmount
        ];
        
        return $this->createForRoles('accounting', $message, 'wholesale_refund', $refundId, $actionUrl, $data);
    }
    
    /**
     * แจ้งเตือนกรณีแนบสลิปคืนเงินจากโฮลเซลล์ (แจ้ง sale)
     *
     * @param int $refundId ID ของคำขอคืนเงิน
     * @param int $bookingId ID ของการจอง
     * @param string $wholesaleName ชื่อโฮลเซลล์
     * @param float $refundAmount จำนวนเงินที่คืน
     * @param string $actionUrl URL ไปยังหน้ารายละเอียดการคืนเงิน
     * @return Notification|null
     */
    public function notifyWholesaleRefundCompleted(
        int $refundId,
        int $bookingId,
        string $wholesaleName,
        float $refundAmount,
        string $actionUrl
    ): ?Notification {
        $message = "เงินคืนจากโฮลเซลล์: {$wholesaleName} จำนวน " . number_format($refundAmount, 2) . " บาท ได้รับเรียบร้อยแล้ว";
        
        $data = [
            'refund_id' => $refundId,
            'wholesale_name' => $wholesaleName,
            'refund_amount' => $refundAmount
        ];
        
        return $this->createForBookingSalesPerson(
            $bookingId,
            $message,
            'wholesale_refund',
            $refundId,
            $actionUrl,
            $data
        );
    }
    
    /**
     * แจ้งเตือนกรณีแนบสลิปเงินมัดจำหรือยอดเต็มใน Payment Wholesale (แจ้ง sale)
     *
     * @param int $paymentId ID ของการชำระเงิน
     * @param int $bookingId ID ของการจอง
     * @param string $wholesaleName ชื่อโฮลเซลล์
     * @param float $paymentAmount จำนวนเงินที่ชำระ
     * @param string $paymentType ประเภทการชำระ (เช่น "มัดจำ" หรือ "ยอดเต็ม")
     * @param string $actionUrl URL ไปยังหน้ารายละเอียดการชำระเงิน
     * @return Notification|null
     */
    public function notifyWholesalePaymentReceived(
        int $paymentId,
        int $bookingId,
        string $wholesaleName,
        float $paymentAmount,
        string $paymentType,
        string $actionUrl
    ): ?Notification {
        $message = "ได้รับชำระเงิน{$paymentType}จากโฮลเซลล์: {$wholesaleName} จำนวน " . number_format($paymentAmount, 2) . " บาท";
        
        $data = [
            'payment_id' => $paymentId,
            'wholesale_name' => $wholesaleName,
            'payment_amount' => $paymentAmount,
            'payment_type' => $paymentType
        ];
        
        return $this->createForBookingSalesPerson(
            $bookingId,
            $message,
            'wholesale_payment',
            $paymentId,
            $actionUrl,
            $data
        );
    }
    
    /**
     * แจ้งเตือนกรณีวันเดินทางน้อยกว่า 15 วัน และยังไม่ส่งพาสปอร์ต (แจ้ง sale)
     *
     * @param int $bookingId ID ของการจอง
     * @param string $customerName ชื่อลูกค้า
     * @param string $tourName ชื่อทัวร์
     * @param string $departureDate วันที่เดินทาง
     * @param string $actionUrl URL ไปยังหน้ารายละเอียดการจอง
     * @return Notification|null
     */
    public function notifyPassportSubmissionReminder(
        int $bookingId,
        string $customerName,
        string $tourName,
        string $departureDate,
        string $actionUrl
    ): ?Notification {
        $message = "กรุณาส่งพาสปอร์ตลูกค้า {$customerName} สำหรับทัวร์ {$tourName} เดินทางวันที่ {$departureDate} (เหลือเวลาน้อยกว่า 15 วัน)";
        
        $data = [
            'customer_name' => $customerName,
            'tour_name' => $tourName,
            'departure_date' => $departureDate
        ];
        
        return $this->createForBookingSalesPerson(
            $bookingId,
            $message,
            'booking',
            $bookingId,
            $actionUrl,
            $data
        );
    }
    
    /**
     * แจ้งเตือนกรณีวันเดินทางน้อยกว่า 3 วัน และยังไม่ส่งใบนัดหมาย (แจ้ง sale)
     *
     * @param int $bookingId ID ของการจอง
     * @param string $customerName ชื่อลูกค้า
     * @param string $tourName ชื่อทัวร์
     * @param string $departureDate วันที่เดินทาง
     * @param string $actionUrl URL ไปยังหน้ารายละเอียดการจอง
     * @return Notification|null
     */
    public function notifyAppointmentLetterReminder(
        int $bookingId,
        string $customerName,
        string $tourName,
        string $departureDate,
        string $actionUrl
    ): ?Notification {
        $message = "กรุณาส่งใบนัดหมายให้ลูกค้า {$customerName} สำหรับทัวร์ {$tourName} เดินทางวันที่ {$departureDate} (เหลือเวลาน้อยกว่า 3 วัน)";
        
        $data = [
            'customer_name' => $customerName,
            'tour_name' => $tourName,
            'departure_date' => $departureDate
        ];
        
        return $this->createForBookingSalesPerson(
            $bookingId,
            $message,
            'booking',
            $bookingId,
            $actionUrl,
            $data
        );
    }
    
    /**
     * ดึงการแจ้งเตือนล่าสุดของผู้ใช้ปัจจุบัน
     *
     * @param int $limit จำนวนการแจ้งเตือนที่ต้องการดึง
     * @return Collection
     */
    public function getRecentNotificationsForCurrentUser(int $limit = 20): Collection
    {
        if (!Auth::check()) {
            return collect();
        }
        
        $userId = Auth::id();
        
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * นับจำนวนการแจ้งเตือนที่ยังไม่ได้อ่านของผู้ใช้ปัจจุบัน
     *
     * @return int
     */
    public function countUnreadNotificationsForCurrentUser(): int
    {
        if (!Auth::check()) {
            return 0;
        }
        
        $userId = Auth::id();
        
        return Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->count();
    }
    
    /**
     * อ่านการแจ้งเตือน (เปลี่ยนสถานะเป็น 'read')
     *
     * @param int $notificationId ID ของการแจ้งเตือน
     * @return bool
     */
    public function markAsRead(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);
        
        if (!$notification) {
            return false;
        }
        
        // ตรวจสอบว่าผู้ใช้ปัจจุบันเป็นเจ้าของการแจ้งเตือนนี้หรือไม่
        if ($notification->user_id !== Auth::id()) {
            return false;
        }
        
        return $notification->markAsRead();
    }
    
    /**
     * อ่านการแจ้งเตือนทั้งหมดของผู้ใช้ปัจจุบัน (เปลี่ยนสถานะเป็น 'read')
     *
     * @return int จำนวนการแจ้งเตือนที่ถูกอัพเดท
     */
    public function markAllAsReadForCurrentUser(): int
    {
        if (!Auth::check()) {
            return 0;
        }
        
        $userId = Auth::id();
        
        return Notification::where('user_id', $userId)
            ->where('status', 'unread')
            ->update(['status' => 'read']);
    }
    
    /**
     * ตรวจสอบการแจ้งเตือนสำหรับการเดินทางที่ใกล้จะถึง (เพื่อใช้กับ Command หรือ Job)
     *
     * @return void
     */
    public function checkUpcomingTravelNotifications(): void
    {
        try {
            // ตรวจสอบว่ามีตาราง bookings หรือไม่
            if (Schema::hasTable('bookings')) {
                $this->checkPassportSubmissionDeadline();
                $this->checkAppointmentLetterDeadline();
            } else {
                Log::info('ไม่มีตาราง bookings ในระบบ');
            }
            
            // ตรวจสอบการอัพเดทใบเสนอราคา
            if (Schema::hasTable('quotations')) {
                $this->checkUpdatedQuotations();
            }
            
            // เพิ่มเติมการตรวจสอบอื่นๆ ตามที่ต้องการได้ที่นี่
            // เช่น การตรวจสอบสถานะการชำระเงิน, การตรวจสอบใบเสนอราคาที่ใกล้หมดอายุ, ฯลฯ
            
            Log::info('ตรวจสอบการแจ้งเตือนเสร็จสิ้น');
        } catch (\Exception $e) {
            Log::error('เกิดข้อผิดพลาดในการตรวจสอบการแจ้งเตือน: ' . $e->getMessage());
        }
    }
    
    /**
     * ตรวจสอบกรณีวันเดินทางน้อยกว่า 15 วัน และยังไม่ส่งพาสปอร์ต
     *
     * @return void
     */
    private function checkPassportSubmissionDeadline(): void
    {
        try {
            // ดัดแปลงตามโครงสร้างฐานข้อมูลจริงของคุณ
            $upcomingBookings = DB::table('quotations')
                ->whereDate('departure_date', '>', Carbon::now())
                ->whereDate('departure_date', '<=', Carbon::now()->addDays(15))
                ->where('status', 'confirmed')  // สมมติว่ามีฟิลด์ status เป็น confirmed แล้วถือว่าชำระเงินแล้ว
                ->whereNull('passport_sent_date')  // สมมติว่าถ้าเป็น null แสดงว่ายังไม่ส่งพาสปอร์ต
                ->get();
            
            if ($upcomingBookings->isEmpty()) {
                Log::info('ไม่พบรายการที่ต้องแจ้งเตือนเรื่องส่งพาสปอร์ต');
                return;
            }
            
            foreach ($upcomingBookings as $booking) {
                $bookingId = $booking->id ?? 0;
                $customerName = $booking->customer_name ?? 'ไม่ระบุชื่อลูกค้า';
                $tourName = $booking->tour_name ?? 'ไม่ระบุทัวร์';
                $departureDate = isset($booking->departure_date) 
                    ? Carbon::parse($booking->departure_date)->format('d/m/Y')
                    : 'ไม่ระบุวันที่';
                
                // ปรับเปลี่ยนตาม route จริงของคุณ
                $actionUrl = route('quotes.show', $bookingId);
                
                Log::info("แจ้งเตือนส่งพาสปอร์ตสำหรับ: {$customerName}, ทัวร์: {$tourName}, เดินทางวันที่: {$departureDate}");
                
                // ถ้ามี sales_id ให้ส่งแจ้งเตือนไปยัง sales คนนั้น
                if (isset($booking->user_id) && $booking->user_id > 0) {
                    $this->notifyPassportSubmissionReminder(
                        $bookingId,
                        $customerName,
                        $tourName,
                        $departureDate,
                        $actionUrl
                    );
                } else {
                    // ถ้าไม่มี sales_id ส่งแจ้งเตือนไปยังผู้ดูแลระบบ
                    $this->createForRoles(
                        'admin',
                        "กรุณาตรวจสอบการส่งพาสปอร์ตลูกค้า {$customerName} สำหรับทัวร์ {$tourName} เดินทางวันที่ {$departureDate} (เหลือเวลาน้อยกว่า 15 วัน)",
                        'quotation',
                        $bookingId,
                        $actionUrl
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('เกิดข้อผิดพลาดในการตรวจสอบการส่งพาสปอร์ต: ' . $e->getMessage());
        }
    }
    
    /**
     * ตรวจสอบกรณีวันเดินทางน้อยกว่า 3 วัน และยังไม่ส่งใบนัดหมาย
     *
     * @return void
     */
    private function checkAppointmentLetterDeadline(): void
    {
        try {
            // ดัดแปลงตามโครงสร้างฐานข้อมูลจริงของคุณ
            $upcomingBookings = DB::table('quotations')
                ->whereDate('departure_date', '>', Carbon::now())
                ->whereDate('departure_date', '<=', Carbon::now()->addDays(3))
                ->where('status', 'confirmed')  // สมมติว่ามีฟิลด์ status เป็น confirmed แล้วถือว่าชำระเงินแล้ว
                ->whereNull('appointment_letter_sent_date')  // สมมติว่าถ้าเป็น null แสดงว่ายังไม่ส่งใบนัดหมาย
                ->get();
            
            if ($upcomingBookings->isEmpty()) {
                Log::info('ไม่พบรายการที่ต้องแจ้งเตือนเรื่องส่งใบนัดหมาย');
                return;
            }
            
            foreach ($upcomingBookings as $booking) {
                $bookingId = $booking->id ?? 0;
                $customerName = $booking->customer_name ?? 'ไม่ระบุชื่อลูกค้า';
                $tourName = $booking->tour_name ?? 'ไม่ระบุทัวร์';
                $departureDate = isset($booking->departure_date)
                    ? Carbon::parse($booking->departure_date)->format('d/m/Y')
                    : 'ไม่ระบุวันที่';
                
                // ปรับเปลี่ยนตาม route จริงของคุณ
                $actionUrl = route('quotes.show', $bookingId);
                
                Log::info("แจ้งเตือนส่งใบนัดหมายสำหรับ: {$customerName}, ทัวร์: {$tourName}, เดินทางวันที่: {$departureDate}");
                
                // ถ้ามี sales_id ให้ส่งแจ้งเตือนไปยัง sales คนนั้น
                if (isset($booking->user_id) && $booking->user_id > 0) {
                    $this->notifyAppointmentLetterReminder(
                        $bookingId,
                        $customerName,
                        $tourName,
                        $departureDate,
                        $actionUrl
                    );
                } else {
                    // ถ้าไม่มี sales_id ส่งแจ้งเตือนไปยังผู้ดูแลระบบ
                    $this->createForRoles(
                        'admin',
                        "กรุณาตรวจสอบการส่งใบนัดหมายลูกค้า {$customerName} สำหรับทัวร์ {$tourName} เดินทางวันที่ {$departureDate} (เหลือเวลาน้อยกว่า 3 วัน)",
                        'quotation',
                        $bookingId,
                        $actionUrl
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('เกิดข้อผิดพลาดในการตรวจสอบการส่งใบนัดหมาย: ' . $e->getMessage());
        }
    }
    
    /**
     * ตรวจสอบกรณีใบเสนอราคาได้รับการอัพเดท
     *
     * @return void
     */
    private function checkUpdatedQuotations(): void
    {
        try {
            // ตรวจสอบใบเสนอราคาที่มีการอัพเดทในช่วง 24 ชั่วโมงที่ผ่านมา
            // สามารถปรับเงื่อนไขตามที่ต้องการ เช่น อัพเดทสถานะ หรือเงื่อนไขอื่นๆ
            $updatedQuotations = DB::table('quotations')
                ->where('updated_at', '>=', Carbon::now()->subHours(24))
                ->where('updated_at', '!=', DB::raw('created_at')) // เป็นการอัพเดท ไม่ใช่สร้างใหม่
                ->get();
            
            if ($updatedQuotations->isEmpty()) {
                Log::info('ไม่พบใบเสนอราคาที่มีการอัพเดทในช่วง 24 ชั่วโมงที่ผ่านมา');
                return;
            }
            
            // ค้นหาผู้ใช้ที่ควรได้รับการแจ้งเตือน (เช่น มีบทบาทเป็น admin, accounting)
            $notifyRoles = ['admin', 'accounting', 'sales', 'Super Admin']; // ปรับตามบทบาทที่มีในระบบของคุณ
            $users = User::whereHas('roles', function ($query) use ($notifyRoles) {
                $query->whereIn('name', $notifyRoles);
            })->get();
            
            if ($users->isEmpty()) {
                Log::info('ไม่พบผู้ใช้ที่ควรได้รับการแจ้งเตือนเกี่ยวกับใบเสนอราคาที่มีการอัพเดท');
                return;
            }
            
            foreach ($updatedQuotations as $quotation) {
                $quotationId = $quotation->quote_id ?? ($quotation->id ?? 0);
                $quotationNumber = $quotation->quote_number ?? 'ไม่ระบุ';
                
                // สร้างข้อความแจ้งเตือน
                $message = "ใบเสนอราคาเลขที่ {$quotationNumber} มีการอัพเดท";
                
                // URL สำหรับดูรายละเอียดใบเสนอราคา
                $actionUrl = "/quote/edit/new/{$quotationId}";
                
                // แจ้งเตือนไปยังผู้ใช้ทุกคนที่มีบทบาทที่กำหนด
                foreach ($users as $user) {
                    $this->createForUser(
                        $user->id,
                        $message,
                        'quotation',
                        $quotationId,
                        $actionUrl
                    );
                    
                    Log::info("สร้างการแจ้งเตือนเกี่ยวกับการอัพเดทใบเสนอราคาสำหรับผู้ใช้ {$user->name}");
                }
            }
            
            Log::info('ตรวจสอบใบเสนอราคาที่มีการอัพเดทเสร็จสิ้น');
        } catch (\Exception $e) {
            Log::error('เกิดข้อผิดพลาดในการตรวจสอบใบเสนอราคาที่มีการอัพเดท: ' . $e->getMessage());
        }
    }
}
