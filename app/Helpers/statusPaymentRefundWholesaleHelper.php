<?php

if (! function_exists('payment_refund_status_text')) {
    /**
     * คืนข้อความสถานะการคืนเงินแบบ HTML
     *
     * @param  float|null $refundTotal จำนวนเงินที่ต้องคืน
     * @param  string|null $refundStatus  // 'success' หรือ 'pending'
     * @param  string|null $refundType    // 'some' หรือ 'full'
     * @param  string|null $refundFileName ชื่อไฟล์สลิปคืนเงิน (เช็คว่ามีไฟล์หรือไม่)
     * @return string
     */
    function payment_refund_status_text($refundTotal, $refundStatus, $refundType, $refundFileName = null): string
    {
        if ($refundTotal > 0) {
            // เช็คว่ามีการแนบสลิปคืนเงินหรือยัง
            $hasRefundSlip = !empty($refundFileName);
            
            // ถ้ามีสลิปแนบแล้ว = โฮลเซลล์คืนเงินแล้ว
            if ($hasRefundSlip) {
                if ($refundType === 'some') {
                    return '<span class="text-success">(โฮลเซลล์คืนยอดบางส่วนแล้ว)</span>';
                } elseif ($refundType === 'full') {
                    return '<span class="text-success">(โฮลเซลล์คืนยอดเต็มจำนวนแล้ว)</span>';
                }
            } else {
                // ถ้ายังไม่มีสลิป = รอโฮลเซลล์คืนเงิน
                if ($refundType === 'some') {
                    return '<span class="text-warning">(รอโฮลเซลล์คืนยอดบางส่วน)</span>';
                } elseif ($refundType === 'full') {
                    return '<span class="text-warning">(รอโฮลเซลล์คืนยอดเต็มจำนวน)</span>';
                }
            }
        }

        return '-'; // ถ้าไม่เข้าเงื่อนไขใดเลย (ไม่มีการคืนเงิน)
    }
}
