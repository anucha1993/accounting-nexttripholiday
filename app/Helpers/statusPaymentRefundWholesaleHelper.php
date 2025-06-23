<?php

if (! function_exists('payment_refund_status_text')) {
    /**
     * คืนข้อความสถานะการคืนเงินแบบ HTML
     *
     * @param  float|null $refundTotal
     * @param  string|null $refundStatus  // 'success' หรือ 'pending'
     * @param  string|null $refundType    // 'some' หรือ 'full'
     * @return string
     */
    function payment_refund_status_text($refundTotal, $refundStatus, $refundType): string
    {
        if ($refundTotal > 0) {
            if ($refundStatus === 'success') {
                if ($refundType === 'some') {
                    return '<span class="text-success">(คืนยอดบางส่วนแล้ว)</span>';
                } elseif ($refundType === 'full') {
                    return '<span class="text-success">(คืนยอดเต็มจำนวนแล้ว)</span>';
                }
            } else {
                if ($refundType === 'some') {
                    return '<span class="text-danger">(รอคืนยอดบางส่วน)</span>';
                } elseif ($refundType === 'full') {
                    return '<span class="text-danger">(รอคืนยอดเต็มจำนวน)</span>';
                }
            }
        }

        return '-'; // ถ้าไม่เข้าเงื่อนไขใดเลย
    }
}
