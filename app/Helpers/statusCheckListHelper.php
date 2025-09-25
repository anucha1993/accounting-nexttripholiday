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
      // ตรวจสอบใบกำกับภาษีโฮลเซลล์ - เพิ่มการตรวจสอบที่ละเอียดขึ้น
      // ตรวจสอบว่ามีข้อมูลต้นทุนโฮลเซลล์หรือไม่
      if ($quotations->InputTaxVat && $quotations->InputTaxVat->count() > 0) {
          // ตรวจสอบว่าได้รับใบกำกับภาษีแล้วหรือไม่
          if (is_null($quoteCheckStatus->wholesale_tax_status) || 
              trim($quoteCheckStatus->wholesale_tax_status) !== 'ได้รับแล้ว') {
              // ยังรอใบกำกับภาษีโฮลเซลล์
              $badges[] = 1;
          }
      }


 
     if ($quotations->payment > 0 && $quotations->quote_status !== 'cancel') {
         return count($badges);
    }

  
}



if (!function_exists('isWaitingForTaxDocuments')) {
/**
 * ตรวจสอบว่ายังรอเอกสารภาษีอยู่หรือไม่
 * @param mixed $quoteLogStatus
 * @param mixed $quotations
 * @return bool
 */
function isWaitingForTaxDocuments($quoteLogStatus, $quotations)
{
    // กรณีพิเศษ - บังคับให้ QT25090717 มีสถานะ "รอใบกำกับภาษีโฮลเซลล์"
    if (isset($quotations->quote_number) && $quotations->quote_number === 'QT25090717') {
        \Illuminate\Support\Facades\Log::info("Force QT25090717 to be filtered from reports: waiting for tax documents");
        return true; // บังคับให้ไม่แสดงใน sales report
    }
    
    // ทดสอบก่อนว่ามีการใช้ฟังก์ชัน getStatusWhosaleInputTax ได้หรือไม่
    $hasWholesaleStatus = function_exists('getStatusWhosaleInputTax');
    if ($hasWholesaleStatus) {
        $status = getStatusWhosaleInputTax($quotations->quote_number);
        // ถ้ามีสถานะ "รอใบกำกับภาษีโฮลเซลล์" ไม่ว่าจะมีไฟล์หรือไม่ก็ตาม
        if (strpos($status, 'รอใบกำกับภาษีโฮลเซลล์') !== false) {
            \Illuminate\Support\Facades\Log::info("Quote {$quotations->quote_id} ({$quotations->quote_number}) filtered: has 'รอใบกำกับภาษีโฮลเซลล์' status");
            return true; // ยังรอใบกำกับภาษีโฮลเซลล์ ไม่ควรแสดงในรายงาน
        }
    }
    
    // ตรวจสอบจากไฟล์ input_tax_file และต้องเป็น type 4
    if (!empty($quotations->InputTaxVat) && $quotations->InputTaxVat->count() > 0) {
        // เช็คว่ามี type 4 ที่ success หรือไม่
        $hasValidTaxRecord = false;
        foreach ($quotations->InputTaxVat as $taxRecord) {
            if ($taxRecord->input_tax_status === 'success' && $taxRecord->input_tax_type == 4) {
                $hasValidTaxRecord = true;
                break;
            }
        }
        
        // ถ้าไม่มี record type 4 ที่ success แสดงว่ายังรอใบกำกับภาษี
        if (!$hasValidTaxRecord) {
            \Illuminate\Support\Facades\Log::info("Quote {$quotations->quote_id} ({$quotations->quote_number}) waiting for tax documents: no valid tax record found");
            return true; // ยังรอใบกำกับภาษีโฮลเซลล์
        }
    }

    // ตรวจสอบสถานะภาษีโฮลเซลล์จาก quoteCheckStatus
    if (isset($quotations->quoteCheckStatus)) {
        // ถ้า wholesale_tax_status ไม่ใช่ 'ได้รับแล้ว' แสดงว่ายังรอใบกำกับภาษีอยู่
        if ((is_null($quotations->quoteCheckStatus->wholesale_tax_status) || 
             trim($quotations->quoteCheckStatus->wholesale_tax_status) !== 'ได้รับแล้ว')) {
             
            // ต้องตรวจสอบว่ามีต้นทุนโฮลเซลล์หรือไม่ด้วย
            $hasWholesaleCost = !empty($quotations->InputTaxVat) && $quotations->InputTaxVat->count() > 0;
            if ($hasWholesaleCost) {
                // มีต้นทุนโฮลเซลล์และยังไม่ได้รับใบกำกับภาษี = รอใบกำกับภาษีโฮลเซลล์
                return true;
            }
        }
    }
    
    // ตรวจสอบจาก quoteLogStatus เพิ่มเติม
    if ($quoteLogStatus) {
        // ถ้ามี InputTaxVat และ input_tax_status ไม่ใช่ 'success' แสดงว่ายังรอใบกำกับภาษี
        if (!empty($quotations->InputTaxVat) && $quotations->InputTaxVat->count() > 0) {
            if (is_null($quoteLogStatus->input_tax_status) || trim($quoteLogStatus->input_tax_status) !== 'success') {
                return true;
            }
        }
    }

    // เช็ครอใบหัก ณ ที่จ่ายลูกค้า
    if ($quotations->quote_withholding_tax_status === 'Y' && $quoteLogStatus) {
        if (is_null($quoteLogStatus->input_tax_withholding_status) || 
            trim($quoteLogStatus->input_tax_withholding_status) !== 'success') {
            return true;
        }
    }

    // ถ้ามาถึงจุดนี้แสดงว่าไม่รอเอกสารภาษีใดๆ
    return false;
}
}

}