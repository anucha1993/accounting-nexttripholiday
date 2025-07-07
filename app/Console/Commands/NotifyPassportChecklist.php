<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\quotations\quotationModel;
use App\Services\NotificationService;
use Carbon\Carbon;

class NotifyPassportChecklist extends Command
{
    protected $signature = 'notify:passport-checklist';
    protected $description = 'แจ้งเตือน sale/sa ให้ส่งพาสปอร์ตให้โฮลเซลล์ ก่อนเดินทาง 15 วัน';

    public function handle()
    {
        // กำหนดวันที่ปัจจุบัน (โซนเวลา Asia/Bangkok)
        $today = Carbon::now('Asia/Bangkok');
        // กำหนดวันที่เป้าหมาย (อีก 15 วันข้างหน้า)
        $targetDate = $today->copy()->addDays(15)->toDateString();
        $notificationService = new NotificationService();

        // ดึงใบเสนอราคาที่วันเดินทาง <= อีก 15 วัน, สถานะชำระเงินครบ
        $quotations = quotationModel::whereDate('quote_date_start', '<=', $targetDate)
            ->where('quote_status', 'success')
            ->where('quote_payment_status', 'success')
            ->get();

        // วนลูปใบเสนอราคาที่เข้าเงื่อนไข
        foreach ($quotations as $quotation) {
            // ดึง quote log ที่สัมพันธ์กับ quotation นี้
            $quoteLog = \App\Models\QuoteLogModel::where('quote_id', $quotation->quote_id)->first();
            // ถ้าไม่มี log หรือ passport_status ยังไม่ติ๊ก (ไม่ใช่ 'ส่งแล้ว') ให้แจ้งเตือน
            if (!$quoteLog || $quoteLog->passport_status !== 'ส่งแล้ว') {
                $saleId = $quotation->quote_sale; // รหัสเซลล์
                $quoteNumber = $quotation->quote_number; // เลขที่ใบเสนอราคา
                $customerName = $quotation->quoteCustomer ? $quotation->quoteCustomer->customer_name : '';
                $dateStart = $quotation->quote_date_start;
                $url = url('/quote/edit/new/' . $quotation->quote_id); // ใช้ลิงก์นี้แทน route ที่ไม่มี
                $msg = "ใบเสนอราคา #{$quoteNumber} ลูกค้า {$customerName} วันเดินทาง {$dateStart} เหลือน้อยกว่า 15 วัน กรุณาส่งพาสปอร์ตให้โฮลเซลล์";
                // ป้องกันการแจ้งซ้ำ: ตรวจสอบว่ามี notification เดิมหรือยัง (สำหรับ sale)
                $saleNotified = \App\Models\NotificationSale::where('reference_id', $quotation->quote_id)
                    ->where('type', 'passport-checklist')
                    ->where('sale_id', $saleId)
                    ->exists();
                // ป้องกันการแจ้งซ้ำ: ตรวจสอบว่ามี notification เดิมหรือยัง (สำหรับ super admin)
                $saNotified = \App\Models\NotificationSA::where('reference_id', $quotation->quote_id)
                    ->where('type', 'passport-checklist')
                    ->exists();
                $relatedId = $quotation->quote_id;
                $relatedType = 'passport-checklist';
                // ส่งแจ้งเตือนให้ sale ถ้ายังไม่เคยแจ้ง
                if (!$saleNotified) {
                    $notificationService->sendToSale($saleId, $msg, $url, $relatedId, $relatedType);
                }
                // ส่งแจ้งเตือนให้ super admin ถ้ายังไม่เคยแจ้ง
                if (!$saNotified) {
                    $notificationService->sendToSuperAdmin($msg, $url, $relatedId, $relatedType);
                }
            }
        }
        // แสดงข้อความใน console เมื่อส่งแจ้งเตือนเสร็จ
        $this->info('Passport checklist notifications sent.');
    }
}
