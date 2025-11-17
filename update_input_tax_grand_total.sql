-- สคริปต์สำหรับอัพเดท input_tax_grand_total ให้คำนวณถูกต้อง
-- ใช้คำสั่งนี้ผ่าน phpMyAdmin หรือ MySQL Client

-- อัพเดทภาษีซื้อ (type = 0) ที่มีไฟล์แนบ
-- สูตร: VAT - Withholding
UPDATE input_tax 
SET input_tax_grand_total = (COALESCE(input_tax_vat, 0) - COALESCE(input_tax_withholding, 0))
WHERE input_tax_type = 0 
  AND input_tax_file IS NOT NULL
  AND input_tax_file != '';

-- อัพเดทภาษีซื้อ (type = 0) ที่ไม่มีไฟล์แนบ
-- สูตร: VAT + Withholding
UPDATE input_tax 
SET input_tax_grand_total = (COALESCE(input_tax_vat, 0) + COALESCE(input_tax_withholding, 0))
WHERE input_tax_type = 0 
  AND (input_tax_file IS NULL OR input_tax_file = '');

-- ตรวจสอบผลลัพธ์
SELECT 
    input_tax_id,
    input_tax_type,
    input_tax_ref,
    input_tax_vat as 'VAT',
    input_tax_withholding as 'Withholding',
    input_tax_grand_total as 'Grand Total',
    CASE 
        WHEN input_tax_file IS NOT NULL AND input_tax_file != '' THEN 'มีไฟล์'
        ELSE 'ไม่มีไฟล์'
    END as 'สถานะไฟล์'
FROM input_tax 
WHERE input_tax_type = 0
ORDER BY input_tax_id DESC;
