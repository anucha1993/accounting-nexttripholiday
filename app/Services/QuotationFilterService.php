<?php
namespace App\Services;

use App\Models\quotations\quotationModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class QuotationFilterService
{
    public static function filter(Request $request)
    {
        // เพิ่ม execution time limit
        set_time_limit(300); // 5 นาที
        ini_set('memory_limit', '512M'); // เพิ่ม memory limit
        
        Log::info("QuotationFilterService::filter() called - Starting filter process");
        Log::info("FILTER DEBUG: Looking for specific quotes");
        
        // เพิ่ม log เพื่อดูค่า parameters ที่ส่งเข้ามา
        Log::info("Request parameters:", [
            'date_start' => $request->input('date_start'),
            'date_end' => $request->input('date_end'),
            'sale_id' => $request->input('sale_id'),
            'wholsale_id' => $request->input('wholsale_id'),
            'country_id' => $request->input('country_id'),
            'keyword' => $request->input('keyword')
        ]);
        
        $user = Auth::user();
        $userRoles = $user->roles->pluck('name'); // แก้ไข getRoleNames()

        // ถ้ามีการค้นหาด้วย keyword ให้ตรวจสอบการมีอยู่ของ quote ก่อน
        if ($request->filled('keyword')) {
            $checkQuote = quotationModel::where('quote_number', 'LIKE', "%{$request->keyword}%")->first();
            if ($checkQuote) {
                Log::info("Found quote before status filter:", [
                    'quote_number' => $checkQuote->quote_number,
                    'quote_status' => $checkQuote->quote_status
                ]);
            } else {
                Log::info("Quote not found in database: {$request->keyword}");
            }
        }

        $query = quotationModel::whereIn('quote_status', ['success', 'invoice']);

        if ($userRoles->contains('sale')) {
            $query->where('quote_sale', $user->sale_id);
        }

        if ($request->filled('date_start')) {
            $query->where('quote_date_start', '>=', $request->input('date_start'));
        }

        if ($request->filled('date_end')) {
            $query->where('quote_date_start', '<=', $request->input('date_end'));
        }

        if ($request->filled('sale_id')) {
            $query->where('quote_sale', $request->input('sale_id'));
        }

        if ($request->filled('campaign_source_id')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('customer_campaign_source', $request->campaign_source_id);
            });
        }

        if ($request->filled('wholsale_id')) {
            $query->where('quote_wholesale', $request->input('wholsale_id'));
        }

        if ($request->filled('country_id')) {
            $query->where('quote_country', $request->input('country_id'));
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('quote_number', 'LIKE', "%$keyword%")
                  ->orWhere('quote_tour_name', 'LIKE', "%$keyword%")
                  ->orWhereHas('customer', function ($q2) use ($keyword) {
                      $q2->where('customer_name', 'LIKE', "%$keyword%");
                  });
            });
        }

        Log::info("SQL Query:", ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        // ดึงข้อมูลก่อน eager loading เพื่อตรวจสอบ
        $rawResults = $query->get();
        Log::info("Raw SQL Results:", $rawResults->map(function($q) {
            return [
                'quote_number' => $q->quote_number,
                'quote_status' => $q->quote_status,
                'quote_id' => $q->quote_id
            ];
        })->toArray());

        // แก้ปัญหา N+1 Query โดยใช้ Eager Loading ครบถ้วน
        $quotations = $query->with([
            // Customer relation
            'customer:customer_id,customer_name,customer_campaign_source',
            // Payment relations - จำกัดเฉพาะ field ที่ใช้
            'quotePayments:payment_quote_id,payment_total,payment_type,payment_status,payment_file_path',
            // Wholesale payment relations
            'paymentWholesale:payment_wholesale_quote_id,payment_wholesale_total,payment_wholesale_refund_total,payment_wholesale_refund_status,payment_wholesale_file_name',
            // Input tax relations - ตรวจสอบโดยไม่กรองสถานะ
            'InputTaxVat:input_tax_id,input_tax_quote_id,input_tax_grand_total,input_tax_type,input_tax_file,input_tax_status,input_tax_withholding,input_tax_vat',
            // Invoice relations
            'quoteInvoice:invoice_quote_id,invoice_withholding_tax',
            // Customer relation
            'customer:customer_id,customer_campaign_source,customer_name',
            // เพิ่ม relationship ที่จำเป็นสำหรับ getStatusBadgeCount
            'quoteCheckStatus' => function($q) {
                $q->select('quote_id', 'booking_email_status', 'quote_status', 'inv_status', 
                          'depositslip_status', 'fullslip_status', 'passport_status', 'appointment_status', 
                          'wholesale_skip_status', 'withholding_tax_status', 'wholesale_tax_status');
            },
            'quoteLogStatus' => function($q) {
                $q->select('input_tax_id', 'input_tax_quote_id', 'input_tax_status', 'input_tax_withholding_status');
            },
            'checkfileInputtax'
        ])->get()->filter(function($quotation) {
            Log::debug("Filtering quote: {$quotation->quote_number}", [
                'quote_id' => $quotation->quote_id,
                'status' => $quotation->quote_status,
                'has_wholesale_skip_status' => isset($quotation->quoteCheckStatus) ? 'yes' : 'no',
                'wholesale_skip_status' => isset($quotation->quoteCheckStatus) ? $quotation->quoteCheckStatus->wholesale_skip_status : 'N/A',
                'has_InputTaxVat' => $quotation->InputTaxVat ? 'yes' : 'no',
                'InputTaxVat_count' => $quotation->InputTaxVat ? $quotation->InputTaxVat->count() : 0,
                'quoteCheckStatus_full' => isset($quotation->quoteCheckStatus) ? json_encode($quotation->quoteCheckStatus) : 'null'
            ]);

            // ตรวจสอบว่ามีการใช้ฟังก์ชัน getStatusWhosaleInputTax
            if (!function_exists('getStatusWhosaleInputTax')) {
                Log::debug("getStatusWhosaleInputTax function not found for quote: {$quotation->quote_number}");
                return true; // ถ้าไม่มีฟังก์ชัน ให้แสดงทุกรายการ // 
            }
            
            // ใช้เงื่อนไขเดียวกับ getStatusWhosaleInputTax
            if ($quotation->InputTaxVat && $quotation->InputTaxVat->count() > 0) {
                $hasValidFile = false;
                foreach ($quotation->InputTaxVat as $record) {
                    if ($record->input_tax_status === 'success' && 
                        $record->input_tax_type == 4 && 
                        !empty($record->input_tax_file)) {
                        $filePath = public_path($record->input_tax_file);
                        if (file_exists($filePath)) {
                            $hasValidFile = true;
                            break;
                        }
                    }
                }
                // แสดงเฉพาะรายการที่มีใบกำกับภาษีโฮลเซลแล้ว
                return $hasValidFile;
            }
            return true; // ถ้าไม่มี InputTaxVat ให้แสดงรายการนั้น
        });

     

        // Pre-calculate values เพื่อหลีกเลี่ยง N+1 Query
        $processedQuotations = $quotations->map(function($item) {
            // Cache ค่าต่างๆ ไว้ใน object
            $item->_cached_deposit = self::calculateDeposit($item);
            $item->_cached_refund = self::calculateRefund($item);
            $item->_cached_wholesale_paid = self::calculateWholesalePaid($item);
            $item->_cached_wholesale_refund = self::calculateWholesaleRefund($item);
            $item->_cached_inputtax_total = self::calculateInputtaxTotal($item);
            $item->_cached_wholesale_payment_count = self::calculateWholesalePaymentCount($item);
            
            return $item;
        });

        return $processedQuotations->filter(function ($item) {
            Log::debug("Checking quote {$item->quote_id} ({$item->quote_number}) in filter");
            
            try {
                // เช็คสถานะงานว่าเสร็จหรือยัง - ถ้ายังไม่เสร็จ ไม่ให้แสดงกำไร
                if ($item->quote_number === 'QT25080005') {
                    Log::debug("QT25080005 - Status check starting...");
                    Log::debug("QT25080005 - wholesale_skip_status: " . ($item->quoteCheckStatus->wholesale_skip_status ?? 'NULL'));
                    Log::debug("QT25080005 - withholding_tax_status: " . ($item->quoteCheckStatus->withholding_tax_status ?? 'NULL'));
                }

                // ตรวจสอบ wholesale_skip_status ก่อน getStatusBadgeCount
                if (isset($item->quoteCheckStatus) && $item->quoteCheckStatus->wholesale_skip_status === 'ไม่ต้องการออก') {
                    if ($item->quote_number === 'QT25080005') {
                        Log::debug("QT25080005 - Skipping badge count check due to wholesale_skip_status");
                    }
                    return true;
                }

                if (function_exists('getStatusBadgeCount')) {
                    $statusCount = getStatusBadgeCount($item->quoteCheckStatus, $item);
                    if ($item->quote_number === 'QT25080005') {
                        Log::debug("QT25080005 - Badge count: " . $statusCount);
                    }
                    if ($statusCount > 0) {
                        return false; // มีงานที่ยังไม่เสร็จ ไม่แสดงกำไร
                    }
                }

                // เช็คว่ายังรอเอกสารภาษีหรือไม่ - ตรวจสอบโดยตรงจากสถานะ
                
                //ตรวจสอบเงื่อนไขใบหัก ณ ที่จ่าย
                if (isset($item->quoteCheckStatus)) {
                    // ถ้า wholesale_skip_status ไม่ใช่ null และเป็น 'ไม่ต้องการออก' ให้แสดงรายการนี้
                    if ($item->quoteCheckStatus->wholesale_skip_status === 'ไม่ต้องการออก') {
                        return true;
                    }
                    
                    // ถ้าไม่มีสถานะ หรือ สถานะเป็น 'ยังไม่ได้ออก' ให้กรองออก
                    if (is_null($item->quoteCheckStatus->withholding_tax_status) || 
                        trim($item->quoteCheckStatus->withholding_tax_status) === NULL
                    ) {
                        Log::info("Quote {$item->quote_id} ({$item->quote_number}) filtered: waiting for withholding tax");
                        return false;
                    }
                
                }

                // เช็คสถานะใบกำกับภาษีโฮลเซลล์โดยใช้ฟังก์ชัน getStatusWhosaleInputTax
                if (function_exists('getStatusWhosaleInputTax')) {
                    $status = getStatusWhosaleInputTax($item->checkfileInputtax);
                    if (strpos($status, 'รอใบกำกับภาษีโฮลเซลล์') !== false) {
                        Log::info("Quote {$item->quote_id} ({$item->quote_number}) filtered: has 'รอใบกำกับภาษีโฮลเซลล์' status");
                        return false;
                    }
                }

                if (function_exists('isWaitingForTaxDocuments')) {
                    try {
                        // เช็คว่าต้องตรวจสอบสถานะภาษีหรือไม่ (มีต้นทุนโฮลเซลล์หรือไม่)
                        if ($item->InputTaxVat && $item->InputTaxVat->count() > 0) {
                            // ตรวจสอบสถานะของใบกำกับภาษีโฮลเซลล์
                            $wholesaleTaxStatus = isset($item->quoteCheckStatus) ? 
                                $item->quoteCheckStatus->wholesale_tax_status : null;
                                
                            // ตรวจสอบเพิ่มเติมจาก input_tax_file โดยตรงจาก InputTaxVat
                            $hasInputTaxFile = false;
                            
                            // วนลูปเช็คทุก InputTaxVat ว่ามีไฟล์หรือไม่ และต้องเป็น type 4 ด้วย
                            if ($item->InputTaxVat) {
                                foreach ($item->InputTaxVat as $taxRecord) {
                                    // ต้องมีไฟล์ และต้องเป็น type 4 (โฮลเซล) และสถานะต้องเป็น success
                                    if (!empty($taxRecord->input_tax_file) 
                                        && $taxRecord->input_tax_status === 'success'
                                        && $taxRecord->input_tax_type == 4) {
                                        
                                        // เช็คเพิ่มเติมว่าไฟล์มีอยู่จริงหรือไม่
                                        $filePath = public_path($taxRecord->input_tax_file);
                                        if (file_exists($filePath)) {
                                            $hasInputTaxFile = true;
                                            break; // พบไฟล์อย่างน้อยหนึ่งไฟล์ ไม่ต้องตรวจสอบต่อ
                                        }
                                    }
                                }
                            }
                                
                            // Log ข้อมูลโควต
                            Log::debug("Quote ID: {$item->quote_id}, Number: {$item->quote_number}, Tax Status: {$wholesaleTaxStatus}, Has File: " . ($hasInputTaxFile ? 'Yes' : 'No'));
                            
                            // ตรวจสอบว่ามี InputTaxVat ที่เป็นประเภทโฮลเซล (type 4) หรือไม่
                            $hasWholesaleTax = false;
                            foreach ($item->InputTaxVat as $taxRecord) {
                                if ($taxRecord->input_tax_type == 4) {
                                    $hasWholesaleTax = true;
                                    break;
                                }
                            }
                            
                            // ถ้าไม่มีต้นทุนโฮลเซล ไม่ต้องตรวจสอบใบกำกับภาษี
                            if (!$hasWholesaleTax) {
                                Log::debug("Quote {$item->quote_id} ({$item->quote_number}): No wholesale tax records, skipping tax document check");
                            }
                            else {
                                // ตัดเงื่อนไขรอใบกำกับภาษีโฮลเซลล์ออก - ไม่กรองอะไร
                                Log::debug("Quote {$item->quote_id} ({$item->quote_number}): Has wholesale tax, but skipping tax document filter");
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Error checking tax document status for quote {$item->quote_id}: " . $e->getMessage());
                    }
                }

                
                
                // ใช้ค่าที่ cache ไว้แล้ว
                $customerPaid = $item->_cached_deposit - $item->_cached_refund;
                $grandTotal = $item->quote_grand_total ?? 0;

                // เช็คเงื่อนไขพื้นฐานก่อน
                if ($customerPaid < $grandTotal) {
                    return false;
                }
                
                // เช็คสถานะใบกำกับภาษีโฮลเซลล์อีกรอบ (สำคัญมาก) - เฉพาะโควตที่มี InputTaxVat ประเภท 4
                if (function_exists('getStatusWhosaleInputTax') && $item->InputTaxVat && $item->InputTaxVat->count() > 0) {
                    // ตรวจสอบว่ามี InputTaxVat ที่เป็นประเภทโฮลเซล (type 4) หรือไม่
                    $hasWholesaleTax = false;
                    foreach ($item->InputTaxVat as $taxRecord) {
                        if ($taxRecord->input_tax_type == 4) {
                            $hasWholesaleTax = true;
                            break;
                        }
                    }
                    
                    // ถ้ามีต้นทุนโฮลเซล จึงจะตรวจสอบสถานะรอใบกำกับภาษี
                    if ($hasWholesaleTax) {
                        $status = getStatusWhosaleInputTax($item->quote_number);
                        if (strpos($status, 'รอใบกำกับภาษีโฮลเซลล์') !== false) {
                            Log::info("Quote {$item->quote_id} ({$item->quote_number}) filtered by getStatusWhosaleInputTax status: รอใบกำกับภาษีโฮลเซลล์");
                            return false; // ยังรอใบกำกับภาษีโฮลเซลล์ ไม่แสดงยอดขาย
                        }
                    }
                }

                // ใช้ pre-calculated values
                $inputtaxTotal = $item->_cached_inputtax_total;
                $countPayment = $item->_cached_wholesale_payment_count;
                $wholesalePaidNet = $item->_cached_wholesale_paid - $item->_cached_wholesale_refund;

                // เงื่อนไขการแสดงกำไร
                if ($countPayment > 0 && $inputtaxTotal > 0) {
                    // มีต้นทุนโฮลเซลล์ - ต้องชำระครบ
                    return abs($wholesalePaidNet - $inputtaxTotal) < 0.01;
                } elseif ($inputtaxTotal == 0 && $customerPaid > 0) {
                    // ไม่มีต้นทุนโฮลเซลล์ - เพียงลูกค้าชำระครบ
                    return true;
                } else {
                    // กรณีอื่นๆ
                    return abs($inputtaxTotal - $wholesalePaidNet) < 0.01;
                }
                
            } catch (\Exception $e) {
                Log::warning("QuotationFilterService error for quote_id: " . $item->quote_id . " - " . $e->getMessage());
                return false;
            }
        })->values();
    }

    /**
     * คำนวณยอดเงินที่ลูกค้าชำระ (ไม่รวม refund)
     */
    private static function calculateDeposit($item)
    {
        if (!$item->quotePayments || $item->quotePayments->isEmpty()) {
            return 0;
        }
        
        return $item->quotePayments->where('payment_status', '!=', 'cancel')
                                  ->where('payment_type', '!=', 'refund')
                                  ->sum('payment_total');
    }

    /**
     * คำนวณยอดเงินคืน
     */
    private static function calculateRefund($item)
    {
        if (!$item->quotePayments || $item->quotePayments->isEmpty()) {
            return 0;
        }
        
        return $item->quotePayments->where('payment_status', '!=', 'cancel')
                                  ->where('payment_type', '=', 'refund')
                                  ->whereNotNull('payment_file_path')
                                  ->sum('payment_total');
    }

    /**
     * คำนวณยอดที่ชำระโฮลเซลล์
     */
    private static function calculateWholesalePaid($item)
    {
        if (!$item->paymentWholesale || $item->paymentWholesale->isEmpty()) {
            return 0;
        }
        
        return $item->paymentWholesale->where('payment_wholesale_file_name', '!=', '')
                                     ->where('payment_wholesale_file_name', '!=', null)
                                     ->sum('payment_wholesale_total');
    }

    /**
     * คำนวณยอดเงินคืนจากโฮลเซลล์
     */
    private static function calculateWholesaleRefund($item)
    {
        if (!$item->paymentWholesale || $item->paymentWholesale->isEmpty()) {
            return 0;
        }
        
        return $item->paymentWholesale->where('payment_wholesale_refund_status', '=', 'success')
                                     ->sum('payment_wholesale_refund_total');
    }

    /**
     * คำนวณต้นทุนโฮลเซลล์รวม
     */
    private static function calculateInputtaxTotal($item)
    {
        if (!$item->InputTaxVat || $item->InputTaxVat->isEmpty()) {
            return 0;
        }
        
        return $item->InputTaxVat->whereIn('input_tax_type', [2, 4, 5, 6, 7])
                                ->sum('input_tax_grand_total');
    }

    /**
     * นับจำนวนการชำระเงินโฮลเซลล์
     */
    private static function calculateWholesalePaymentCount($item)
    {
        if (!$item->paymentWholesale || $item->paymentWholesale->isEmpty()) {
            return 0;
        }
        
        return $item->paymentWholesale->where('payment_wholesale_file_name', '!=', '')
                                     ->where('payment_wholesale_file_name', '!=', null)
                                     ->count();
    }
}
