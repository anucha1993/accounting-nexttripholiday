<?php

use Carbon\Carbon;

if (!function_exists('getQuoteStatusPayment')) {
    /**
     * สรุปสถานะการชำระของลูกค้า โดยไม่ query เพิ่ม
     * อาศัยฟิลด์ที่ preload มากับ quotation:
     *  - $quotationModel->paid_total      (SUM payments ที่ไม่ใช่ refund และไม่ถูก cancel)
     *  - $quotationModel->refund_total    (SUM payments ที่เป็น refund และไม่ถูก cancel)
     *  - $quotationModel->has_refund_file (SELECT EXISTS(...) จาก controller)
     *  - ฟิลด์บน quotation เอง: quote_grand_total, quote_status, quote_payment_type,
     *    quote_payment_date, quote_payment_date_full
     */
    function getQuoteStatusPayment($quotationModel)
    {
        $now = Carbon::now();

        // ใช้ค่าที่คำนวณมาล่วงหน้าเท่านั้น (ไม่มีการแตะ relation)
        $depositTotal = (float) ($quotationModel->paid_total   ?? 0.0);   // เงินรับจากลูกค้า (ไม่รวม refund)
        $refundTotal  = (float) ($quotationModel->refund_total ?? 0.0);   // ยอดที่เป็น refund
        $grandTotal   = (float) ($quotationModel->quote_grand_total ?? 0.0);

        // ค่าช่วยคำนวณตามสูตรเดิม
        $paymentTotal = $grandTotal - $depositTotal + $refundTotal; // เหลือ/เกินจากมุมมองยอดสุทธิ
        $paymentNet   = $depositTotal - $refundTotal;               // รับมา - คืนไป (net in)

        // ฟังก์ชันช่วย parse วันแบบปลอดภัย
        $parseDate = static function ($date) {
            return $date ? Carbon::parse($date) : null;
        };

        // ---- จัดสถานะตามลำดับเดิม (ปรับให้อิง aggregate) ----

        // 1) รอคืนเงิน (มีรายการ refund เกิดขึ้น/มีไฟล์แนบคืนเงิน)
        //    ถ้าอยากเข้มขึ้น ให้ใช้ has_refund_file ด้วยร่วมกับ refundTotal > 0
        if (($quotationModel->has_refund_file ?? false) || $refundTotal > 0) {
            return '<span class="text-warning">รอคืนเงิน </span>';
        }

        // 2) ยกเลิก
        if (($quotationModel->quote_status ?? null) === 'cancel') {
            return '<span class="text-danger">ยกเลิกการสั่งซื้อ</span>';
        }

        // 3) ชำระเงินครบแล้ว (ไม่มียอดคงค้าง)
        if ($paymentTotal == 0.0 && $grandTotal > 0.0) {
            return '<span class="text-success">ชำระเงินครบแล้ว</span>';
        }

        // 4) ชำระเงินเกิน (รับสุทธิมากกว่ายอด)
        if ($paymentNet > $grandTotal && $grandTotal > 0.0) {
            return '<span class="text-info">ชำระเงินเกิน</span>';
        }

        // 5) ชำระบางส่วน → พิจารณากำหนดจ่ายเต็มจำนวน
        if ($paymentNet > 0.0 && $paymentNet < $grandTotal) {
            $fullDate = $parseDate($quotationModel->quote_payment_date_full ?? null);
            if ($fullDate && $now->gt($fullDate)) {
                return '<span class="text-danger">เกินกำหนดชำระเงิน</span>';
            }
            return '<span class="text-info">รอชำระเงินเต็มจำนวน</span>';
        }

        // 6) ยังไม่ชำระเลย: แยกตามชนิดการชำระ (deposit / full)
        $ptype = $quotationModel->quote_payment_type ?? null;

        if ($ptype === 'deposit' && $paymentNet == 0.0) {
            $due = $parseDate($quotationModel->quote_payment_date ?? null);
            if ($due && $now->gt($due)) {
                return '<span class="text-danger">เกินกำหนดชำระเงิน</span>';
            }
            return '<span class="text-warning">รอชำระเงินมัดจำ</span>';
        }

        if ($ptype === 'full' && $paymentNet == 0.0) {
            $due = $parseDate($quotationModel->quote_payment_date_full ?? null);
            if ($due && $now->gt($due)) {
                return '<span class="text-danger">เกินกำหนดชำระเงิน</span>';
            }
            return '<span class="text-info">รอชำระเงินเต็มจำนวน</span>';
        }
        // 7) กรณีทั่วไป 
        return '<span class="text-secondary">รอชำระเงิน</span>';

    }
}
