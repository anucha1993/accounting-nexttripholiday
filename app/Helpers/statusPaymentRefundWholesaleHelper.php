<?php

if (! function_exists('payment_refund_status_text')) {
    /**
     * คืนข้อความสถานะการคืนเงินแบบ HTML
     *
     * @param  float|null $refundTotal จำนวนเงินที่ต้องคืน
     * @param  string|null $refundStatus  // 'success' หรือ 'pending'
     * @param  string|null $refundType    // 'some' หรือ 'full'
     * @param  float $paidTotal จำนวนเงินที่ชำระแล้ว (optional)
     * @return string
     */
    function payment_refund_status_text($refundTotal, $refundStatus, $refundType, $paidTotal = 0): string
    {
        if ($refundTotal > 0) {
            // ถ้ายอดที่คืนเท่ากับยอดที่ชำระ ให้แสดงว่าคืนเงินแล้ว
            if ($refundStatus === 'success' || $refundTotal === $paidTotal) { // เพิ่มเช็คยอดเท่ากัน
                if ($refundType === 'some') {
                    return '<span class="text-success">(คืนยอดบางส่วนแล้ว)</span>';
                } elseif ($refundType === 'full') {
                    return '<span class="text-success">(คืนยอดเต็มจำนวนแล้ว)</span>';
                }
            } else {
                if ($refundType === 'some') {
                    return '<span class="text-danger">(รอคืนยอดบางส่วน)</span>';
                } elseif ($refundType === 'full') {
                    return sprintf(
                        '<span class="text-danger">(รอคืนยอดเต็มจำนวน)</span>',
                        // $refundTotal,
                        // $refundStatus,
                        // $refundType,
                        // $paidTotal
                    );
                }
            }
        }

        return '-'; // ถ้าไม่เข้าเงื่อนไขใดเลย
    }
}
