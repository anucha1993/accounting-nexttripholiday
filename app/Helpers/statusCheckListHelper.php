<?php

if (!function_exists('getStatusBadge')) {
function getStatusBadge($quoteCheckStatus, $quotations)
{
    if (!$quoteCheckStatus) {
        return '';
    }
    $badges = [];
    // 1. booking_email_status
    if (is_null($quoteCheckStatus->booking_email_status) || trim($quoteCheckStatus->booking_email_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์</span>';
    }
    // 2. invoice_status (ต้องได้ทั้ง quote_status และ inv_status)
    $quoteStatusOk = !is_null($quoteCheckStatus->quote_status) && trim($quoteCheckStatus->quote_status) === 'ได้แล้ว';
    $invStatusOk = !is_null($quoteCheckStatus->inv_status) && trim($quoteCheckStatus->inv_status) === 'ได้แล้ว';
    if (!($quoteStatusOk && $invStatusOk)) {
        $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้อินวอยโฮลเซลล์</span>';
    }
    // 4. slip_status (ต้องได้ทั้ง depositslip_status และ fullslip_status)
    $depositslipOk = !is_null($quoteCheckStatus->depositslip_status) && trim($quoteCheckStatus->depositslip_status) === 'ส่งแล้ว';
    $fullslipOk = !is_null($quoteCheckStatus->fullslip_status) && trim($quoteCheckStatus->fullslip_status) === 'ส่งแล้ว';
    if (!($depositslipOk && $fullslipOk)) {
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
    // if (is_null($quoteCheckStatus->wholesale_tax_status) || trim($quoteCheckStatus->wholesale_tax_status) == 'ยังไม่ได้รับ' && !empty($quotations->checkfileInputtax)) {
    //     $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์</span>';
    // }

    // 7. wholesale_tax_status
    if (!empty($quotations->checkfileInputtax)) {
        $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์</span>';
    }

    // 8. withholding_tax_status
   if (
    (is_null($quoteCheckStatus->wholesale_skip_status) || $quoteCheckStatus->wholesale_skip_status !== 'ไม่ต้องการออก')
    && (is_null($quoteCheckStatus->withholding_tax_status) || trim($quoteCheckStatus->withholding_tax_status) === 'ยังไม่ได้ออก')
) {
    $badges[] = '<span class="badge rounded-pill bg-danger">ยังไม่ได้ออกใบหัก.ณ.ที่จ่ายโฮลเซลล์</span>';
}

     if ($quotations->payment > 0 && $quotations->quote_status !== 'cancel') {
            return implode(' ', $badges);
        }

   
}


function getStatusBadgeCount($quoteCheckStatus, $quotations)
{
    if (!$quoteCheckStatus) {
        return 0;
    }
    $badges = [];
    //ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์ 1
    if (is_null($quoteCheckStatus->booking_email_status) || trim($quoteCheckStatus->booking_email_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = 1;
    }
    //อินวอยโฮลเซลล์ 2 (ต้องได้ทั้ง quote_status และ inv_status)
    $quoteStatusOk = !is_null($quoteCheckStatus->quote_status) && trim($quoteCheckStatus->quote_status) === 'ได้แล้ว';
    $invStatusOk = !is_null($quoteCheckStatus->inv_status) && trim($quoteCheckStatus->inv_status) === 'ได้แล้ว';
    if (!($quoteStatusOk && $invStatusOk)) {
        $badges[] = 1;
    }
    //ส่งสลิปให้โฮลเซลล์ 3 (ต้องได้ทั้ง depositslip_status และ fullslip_status)
    $depositslipOk = !is_null($quoteCheckStatus->depositslip_status) && trim($quoteCheckStatus->depositslip_status) === 'ส่งแล้ว';
    $fullslipOk = !is_null($quoteCheckStatus->fullslip_status) && trim($quoteCheckStatus->fullslip_status) === 'ส่งแล้ว';
    if (!($depositslipOk && $fullslipOk)) {
        $badges[] = 1;
    }
     //ส่งพาสปอตให้โฮลเซลล์ 4
    if (is_null($quoteCheckStatus->passport_status) || trim($quoteCheckStatus->passport_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = 1;
    }
     //ส่งใบนัดหมายให้ลูกค้า 5
    if (is_null($quoteCheckStatus->appointment_status) || trim($quoteCheckStatus->appointment_status) === 'ยังไม่ได้ส่ง') {
        $badges[] = 1;
    }
    
      //ออกใบหักณที่จ่าย 6
  if (
    (is_null($quoteCheckStatus->wholesale_skip_status) || $quoteCheckStatus->wholesale_skip_status !== 'ไม่ต้องการออก')
    && (is_null($quoteCheckStatus->withholding_tax_status) || trim($quoteCheckStatus->withholding_tax_status) === 'ยังไม่ได้ออก')
) {
    $badges[] = 1;
}
      if (is_null($quoteCheckStatus->wholesale_tax_status) || trim($quoteCheckStatus->wholesale_tax_status) !== 'ได้รับแล้ว' && !empty($quotations->checkfileInputtax)) {
        $badges[] = 1;
    }

    //     //ไฟล์ใบแจ้งหนี้ 7
    // if (is_null($quoteCheckStatus->inv_status) || trim($quoteCheckStatus->inv_status) === 'ยังไม่ได้') {
    //     $badges[] = 1;
    // }
       //ใบกำกับภาษีโฮลเซลล์ 8
  

 
     if ($quotations->payment > 0 && $quotations->quote_status !== 'cancel') {
         return count($badges);
    }

  
}




}