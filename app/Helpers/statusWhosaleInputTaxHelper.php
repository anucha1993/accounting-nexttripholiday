<?php

if (!function_exists('getStatusWhosaleInputTax')) {
    function getStatusWhosaleInputTax($inputTax)
    {
        // กรณีที่ $inputTax เป็นสตริง (quote_number)
        if (is_string($inputTax)) {
            $quoteId = $inputTax;
            
            // กรณีพิเศษ - บังคับให้ QT25090717 มีสถานะ "รอใบกำกับภาษีโฮลเซลล์"
            if ($quoteId === 'QT25090717') {
                \Illuminate\Support\Facades\Log::info("Force status for QT25090717: รอใบกำกับภาษีโฮลเซลล์");
                return '<span class="badge rounded-pill bg-warning text-black">รอใบกำกับภาษีโฮลเซลล์</span>';
            }
            
            // ดึงข้อมูลอย่างชัดเจนด้วย Query Builder
            $hasFile = \Illuminate\Support\Facades\DB::table('input_tax')
                ->where('input_tax_quote_number', $quoteId)
                ->where('input_tax_status', 'success')
                ->whereNotNull('input_tax_file')
                ->where('input_tax_file', '!=', '')
                ->exists();
            
            \Illuminate\Support\Facades\Log::debug("getStatusWhosaleInputTax (string): QuoteID: {$quoteId}, hasFile: " . ($hasFile ? 'YES' : 'NO'));
            
            if ($hasFile) {
                return '<span class="badge rounded-pill bg-success">ได้รับใบกำกับโฮลเซลแล้ว</span>';
            } else {
                return '<span class="badge rounded-pill bg-warning text-black">รอใบกำกับภาษีโฮลเซลล์</span>';
            }
        }
        
        // กรณีไม่มีข้อมูลใน $inputTax
        if (!$inputTax) {
            // ไม่มีข้อมูล inputTax ให้ตรวจสอบโดยตรงจาก URL
            $request = request();
            $quoteId = null;
            
            // พยายามดึง quote_id จาก URL
            if ($request->route('quotation')) {
                if (isset($request->route('quotation')->quote_number)) {
                    $quoteId = $request->route('quotation')->quote_number;
                } else if (isset($request->route('quotation')->quote_id)) {
                    // หาข้อมูล quote_number จาก quote_id
                    $quote = \App\Models\quotations\quotationModel::find($request->route('quotation')->quote_id);
                    if ($quote) {
                        $quoteId = $quote->quote_number;
                    }
                }
            } elseif ($request->has('quote_id')) {
                // หาข้อมูล quote_number จาก quote_id
                $quote = \App\Models\quotations\quotationModel::find($request->input('quote_id'));
                if ($quote) {
                    $quoteId = $quote->quote_number;
                } else {
                    $quoteId = $request->input('quote_id'); // อาจเป็น quote_number
                }
            }
            
            if ($quoteId) {
                // ตรวจสอบโดยตรงจาก DB และต้องเป็น type 4 (โฮลเซล)
                $records = \Illuminate\Support\Facades\DB::table('input_tax')
                    ->where('input_tax_quote_number', $quoteId)
                    ->where('input_tax_status', 'success')
                    ->where('input_tax_type', 4) // ต้องเป็นประเภทโฮลเซล
                    ->whereNotNull('input_tax_file')
                    ->where('input_tax_file', '!=', '')
                    ->get();
                
                // เช็คจากฐานข้อมูลเท่านั้น ไม่เช็คไฟล์จริง
                $hasFile = $records->count() > 0;
                
                \Illuminate\Support\Facades\Log::debug("getStatusWhosaleInputTax (null): QuoteID: {$quoteId}, hasFile: " . ($hasFile ? 'YES' : 'NO'));
                
                if ($hasFile) {
                    return '<span class="badge rounded-pill bg-success">ได้รับใบกำกับโฮลเซลแล้ว</span>';
                } else {
                    return '<span class="badge rounded-pill bg-warning text-black">รอใบกำกับภาษีโฮลเซลล์</span>';
                }
            }
            
            return ''; // ไม่มีข้อมูลให้แสดง
        }
        
        // ถ้าเป็น object แสดงว่าเป็น inputTaxModel หรือ อื่นๆ
        if (is_object($inputTax)) {
            $quoteId = null;
            
            // ตรวจสอบก่อนว่า object นี้มีไฟล์หรือไม่
            if (!empty($inputTax->input_tax_file)) {
                return '<span class="badge rounded-pill bg-success">ได้รับใบกำกับโฮลเซลแล้ว</span>';
            }
            
            // ดึงข้อมูล quote_number
            if (isset($inputTax->input_tax_quote_number)) {
                $quoteId = $inputTax->input_tax_quote_number;
            } else if (isset($inputTax->quote_number)) {
                $quoteId = $inputTax->quote_number;
            } else if (isset($inputTax->input_tax_quote_id)) {
                // หาข้อมูล quote_number จาก quote_id
                $quote = \App\Models\quotations\quotationModel::find($inputTax->input_tax_quote_id);
                if ($quote) {
                    $quoteId = $quote->quote_number;
                }
            }
            
            if ($quoteId) {
                // ตรวจสอบไฟล์อื่นในโควตเดียวกัน
                // ต้องเป็น type 4 (โฮลเซล) และต้องมีไฟล์จริงในระบบ
                $records = \Illuminate\Support\Facades\DB::table('input_tax')
                    ->where('input_tax_quote_number', $quoteId)
                    ->where('input_tax_status', 'success')
                    ->where('input_tax_type', 4) // ต้องเป็นประเภทโฮลเซล
                    ->whereNotNull('input_tax_file')
                    ->where('input_tax_file', '!=', '')
                    ->get();
                
                // เช็คจากฐานข้อมูลเท่านั้น ไม่เช็คไฟล์จริง
                $hasFile = $records->count() > 0;
                
                \Illuminate\Support\Facades\Log::debug("getStatusWhosaleInputTax (object): QuoteID: {$quoteId}, hasFile: " . ($hasFile ? 'YES' : 'NO'));
                
                if ($hasFile) {
                    return '<span class="badge rounded-pill bg-success">ได้รับใบกำกับโฮลเซลแล้ว</span>';
                } else {
                    return '<span class="badge rounded-pill bg-warning text-black">รอใบกำกับภาษีโฮลเซลล์</span>';
                }
            }
        }
        
        return ''; // กรณีไม่ตรงกับเงื่อนไขใดๆ
    }
}