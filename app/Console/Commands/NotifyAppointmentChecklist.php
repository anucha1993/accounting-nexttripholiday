<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\quotations\quotationModel;
use App\Services\NotificationService;
use Carbon\Carbon;

class NotifyAppointmentChecklist extends Command
{
    protected $signature = 'notify:appointment-checklist';
    protected $description = 'แจ้งเตือน sale ให้ส่งใบนัดหมายการเดินทางให้ลูกค้า ก่อนเดินทาง 3 วัน';

    public function handle()
    {
        // กำหนดวันที่ปัจจุบัน (โซนเวลา Asia/Bangkok)
        $today = Carbon::now('Asia/Bangkok');
        // กำหนดวันที่เป้าหมาย (อีก 3 วันข้างหน้า)
        $targetDate = $today->copy()->addDays(3)->toDateString();
        $notificationService = new NotificationService();

        // ดึงใบเสนอราคาที่วันเดินทาง <= อีก 3 วัน, สถานะชำระเงินครบ
        $quotations = quotationModel::whereDate('quote_date_start', '<=', $targetDate)
            ->where('quote_status', 'success')
            ->where('quote_payment_status', 'success')
            ->get();

        // วนลูปใบเสนอราคาที่เข้าเงื่อนไข
        foreach ($quotations as $quotation) {
            // ดึง quote log ที่สัมพันธ์กับ quotation นี้
            $quoteLog = \App\Models\QuoteLogModel::where('quote_id', $quotation->quote_id)->
            first();
            // ถ้าไม่มี log หรือ appointment_status ยังไม่ติ๊ก (ไม่ใช่ 'ส่งแล้ว') ให้แจ้งเตือน
            if (!$quoteLog || $quoteLog->appointment_status !== 'ส่งแล้ว') {
                $saleId = $quotation->quote_sale;
                $quoteNumber = $quotation->quote_number;
                $customerName = $quotation->quoteCustomer ? $quotation->quoteCustomer->customer_name : '';
                $dateStart = $quotation->quote_date_start;
                $url = url('/quote/edit/new/' . $quotation->quote_id);
                $msg = "ใบเสนอราคา #{$quoteNumber} ลูกค้า {$customerName} วันเดินทาง {$dateStart} เหลือน้อยกว่า 3 วัน กรุณาส่งใบนัดหมายการเดินทางให้ลูกค้า | Sale : {$quotation->Salename->name}";
                // ป้องกันการแจ้งซ้ำ: ตรวจสอบว่ามี notification เดิมหรือยัง (สำหรับ sale)
                $saleNotified = \App\Models\NotificationSale::where('reference_id', $quotation->quote_id)
                    ->where('type', 'appointment-checklist')
                    ->where('sale_id', $saleId)
                    ->exists();
                $relatedId = $quotation->quote_id;
                $relatedType = 'appointment-checklist';
                // ส่งแจ้งเตือนให้ sale ถ้ายังไม่เคยแจ้ง
                if (!$saleNotified) {
                    $notificationService->sendToSale($saleId, $msg, $url, $relatedId, $relatedType);
                }
            }
        }
        $this->info('Appointment checklist notifications sent.');
    }
}
