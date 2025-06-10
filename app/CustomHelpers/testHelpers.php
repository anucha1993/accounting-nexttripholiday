<?php

namespace App\CustomHelpers;

function getStatusBadge($quoteLogStatus)
{
    $badges = [];
    $allDone = true; // เริ่มต้นด้วย true

    // if ($quoteLogStatus->booking_email_status === 'ยังไม่ได้ส่ง' || $quoteLogStatus->booking_email_status === null) {
    //     $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์</span>';
    //     $allDone = false;
    // }

    // if ($quoteLogStatus->invoice_status !== 'ได้แล้ว' || $quoteLogStatus->invoice_status === null) {
    //     $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้อินวอยโฮลเซลล์</span>';
    //     $allDone = false;
    // }

    // if ($quoteLogStatus->slip_status === 'ยังไม่ได้ส่ง' || $quoteLogStatus->slip_status === null) {
    //     $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้ส่งสลิปให้โฮลเซลล์</span>';
    //     $allDone = false;
    // }

    // if ($quoteLogStatus->passport_status === 'ยังไม่ได้ส่ง' || $quoteLogStatus->passport_status === null) {
    //     $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้ส่งพาสปอตให้โฮลเซลล์</span>';
    //     $allDone = false;
    // }

    // if ($quoteLogStatus->appointment_status === 'ยังไม่ได้ส่ง' || $quoteLogStatus->appointment_status === null) {
    //     $badges[] = '<span class="badge rounded-pill bg-danger">ส่งใบนัดหมายให้ลูกค้า</span>';
    //     $allDone = false;
    // }

    // if ($quoteLogStatus->wholesale_tax_status !== 'ได้รับแล้ว' || $quoteLogStatus->wholesale_tax_status === null) {
    //     $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์</span>';
    //     $allDone = false;
    // }

    // if ($quoteLogStatus->wholesale_skip_status !== 'ไม่ต้องการออก' && ($quoteLogStatus->withholding_tax_status === 'ยังไม่ได้ออก' || $quoteLogStatus->withholding_tax_status === null)) {
    //     $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้ออกใบหัก ณ ที่จ่าย</span>';
    //     $allDone = false;
    // }

    // if ($allDone) {
    //     return '<span class="badge rounded-pill bg-success">ทำหมดแล้ว</span>';
    // }

    return implode(' ', $badges); // คืนค่าผลลัพธ์ทั้งหมดโดยคั่นด้วยช่องว่าง
}