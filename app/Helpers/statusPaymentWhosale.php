<?php

if (!function_exists('getStatusPaymentWhosale')) {
    function getStatusPaymentWhosale($quotationModel)
    {
        // 1. ยอดที่เราโอนไปยังโฮลเซลล์
        $depositTotal = $quotationModel->GetDepositWholesale();

        // 2. ยอดที่โฮลเซลล์คืนกลับมาแล้ว (refund สำเร็จ)
        $refundSuccessTotal = $quotationModel
            ->paymentWholesale()
            ->where('payment_wholesale_refund_status', 'success')
            ->get()
            ->sum(function ($row) {
                return abs($row->payment_wholesale_refund_total);
            });
        // 3. ยอดที่ยังรอคืน (refund ยังไม่ success)
        $refundPendingTotal = $quotationModel
            ->paymentWholesale()
            ->where('payment_wholesale_refund_status', '!=', 'success')
            ->get()
            ->sum(function ($row) {
                return abs($row->payment_wholesale_refund_total);
            });

        // 📌 แสดงสถานะเฉพาะเมื่อมีการ "โอนเกิน"
        if ($depositTotal > 0 && $refundSuccessTotal + $refundPendingTotal > 0) {
            // ✅ กรณีคืนเงินครบแล้ว
            if ($refundPendingTotal <= 0 && $refundSuccessTotal > 0) {
                return '<span class="badge rounded-pill bg-success">คืนเงินแล้ว</span>';
            }

            // 🟡 กรณีคืนเงินยังไม่ครบ
            if ($refundPendingTotal > 0) {
                return '<span class="badge rounded-pill bg-warning text-dark">รอโฮลเซลล์คืนเงิน</span>';
            }
        }

        return ''; // ไม่แสดงอะไรหากไม่มี refund เกิน
    }
}
