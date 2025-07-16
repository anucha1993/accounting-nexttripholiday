<?php

if (!function_exists('getStatusBadge')) {
function getStatusBadge($quoteCheckStatus)
{
    if (!$quoteCheckStatus) {
        return '';
    }
    $badges = [];
    // 1. booking_email_status
    if (is_null($quoteCheckStatus->booking_email_status) || trim($quoteCheckStatus->booking_email_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์</span>';
    }
    // 2. invoice_status
    if (is_null($quoteCheckStatus->invoice_status) || trim($quoteCheckStatus->invoice_status) !== 'ได้แล้ว') {
        $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้อินวอยโฮลเซลล์</span>';
    }
    // 3. inv_status
    if (is_null($quoteCheckStatus->inv_status) || trim($quoteCheckStatus->inv_status) === 'ยังไม่ได้') {
        $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้ใบแจ้งหนี้โฮลเซลล์</span>';
    }
    // 4. slip_status
    if (is_null($quoteCheckStatus->slip_status) || trim($quoteCheckStatus->slip_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้ส่งสลิปให้โฮลเซลล์</span>';
    }
    // 5. passport_status
    if (is_null($quoteCheckStatus->passport_status) || trim($quoteCheckStatus->passport_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้ส่งพาสปอตให้โฮลเซลล์</span>';
    }
    // 6. appointment_status
    if (is_null($quoteCheckStatus->appointment_status) || trim($quoteCheckStatus->appointment_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = '<span class="badge rounded-pill bg-danger">ส่งใบนัดหมายให้ลูกค้า</span>';
    }
    // 7. wholesale_tax_status
    if (is_null($quoteCheckStatus->wholesale_tax_status) || trim($quoteCheckStatus->wholesale_tax_status) !== 'ได้รับแล้ว') {
        $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์</span>';
    }
    // 8. withholding_tax_status
    if ($quoteCheckStatus->wholesale_skip_status !== 'ไม่ต้องการออก' && (is_null($quoteCheckStatus->withholding_tax_status) || trim($quoteCheckStatus->withholding_tax_status) === 'ยังไม่ได้ออก')) {
        $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้ออกใบหัก ณ ที่จ่าย</span>';
    }
    return implode(' ', $badges);
}


function getStatusBadgeCount($quoteCheckStatus)
{
    if (!$quoteCheckStatus) {
        return 0;
    }
    $badges = [];
    if (is_null($quoteCheckStatus->booking_email_status) || trim($quoteCheckStatus->booking_email_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = 1;
    }
    if (is_null($quoteCheckStatus->invoice_status) || trim($quoteCheckStatus->invoice_status) !== 'ได้แล้ว') {
        $badges[] = 1;
    }
    if (is_null($quoteCheckStatus->inv_status) || trim($quoteCheckStatus->inv_status) === 'ยังไม่ได้') {
        $badges[] = 1;
    }
    if (is_null($quoteCheckStatus->slip_status) || trim($quoteCheckStatus->slip_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = 1;
    }
    if (is_null($quoteCheckStatus->passport_status) || trim($quoteCheckStatus->passport_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = 1;
    }
    if (is_null($quoteCheckStatus->appointment_status) || trim($quoteCheckStatus->appointment_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = 1;
    }
    if (is_null($quoteCheckStatus->wholesale_tax_status) || trim($quoteCheckStatus->wholesale_tax_status) !== 'ได้รับแล้ว') {
        $badges[] = 1;
    }
    if ($quoteCheckStatus->wholesale_skip_status !== 'ไม่ต้องการออก' && (is_null($quoteCheckStatus->withholding_tax_status) || trim($quoteCheckStatus->withholding_tax_status) === 'ยังไม่ได้ออก')) {
        $badges[] = 1;
    }
    return count($badges);
}




}