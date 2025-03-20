<?php

namespace App\Helpers;

function testHelper() {
    return "Test Helper";
}
if (!function_exists('getStatusBadge')) {
function getStatusBadge($quoteLogStatus)
{
    if ($quoteLogStatus->booking_email_status === 'ยังไม่ได้ส่ง' || $quoteLogStatus->booking_email_status === null) {
        return '<span class="badge rounded-pill bg-danger">ยังไม่ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์</span>';
    }

    if ($quoteLogStatus->invoice_status !== 'ได้แล้ว' || $quoteLogStatus->invoice_status === null) {
        return '<span class="badge rounded-pill bg-danger">ยังไม่ได้อินวอยโฮลเซลล์</span>';
    }

    if ($quoteLogStatus->slip_status === 'ยังไม่ได้ส่ง' || $quoteLogStatus->slip_status === null) {
        return '<span class="badge rounded-pill bg-danger">ยังไม่ได้ส่งสลิปให้โฮลเซลล์</span>';
    }

    if ($quoteLogStatus->passport_status === 'ยังไม่ได้ส่ง' || $quoteLogStatus->passport_status === null) {
        return '<span class="badge rounded-pill bg-danger">ยังไม่ได้ส่งพาสปอตให้โฮลเซลล์</span>';
    }

    if ($quoteLogStatus->appointment_status === 'ยังไม่ได้ส่ง' || $quoteLogStatus->appointment_status === null) {
        return '<span class="badge rounded-pill bg-danger">ส่งใบนัดหมายให้ลูกค้า</span>';
    }

    if ($quoteLogStatus->wholesale_tax_status !== 'ได้รับแล้ว' || $quoteLogStatus->wholesale_tax_status === null) {
        return '<span class="badge rounded-pill bg-danger">ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์</span>';
    }

    if ($quoteLogStatus->wholesale_skip_status !== 'ไม่ต้องการออก' && ($quoteLogStatus->withholding_tax_status === 'ยังไม่ได้ออก' || $quoteLogStatus->withholding_tax_status === null)) {
        return '<span class="badge rounded-pill bg-danger">ยังไม่ได้ออกใบหัก ณ ที่จ่าย</span>';
    }

    return ''; // คืนค่าว่างหากไม่มีเงื่อนไขใดๆ ตรงกับสถานะ
}
}