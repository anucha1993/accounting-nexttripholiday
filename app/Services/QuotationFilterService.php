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
        
        $user = Auth::user();
        $userRoles = $user->roles->pluck('name'); // แก้ไข getRoleNames()

        $query = quotationModel::where('quote_status', 'success');

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

        // แก้ปัญหา N+1 Query โดยใช้ Eager Loading ครบถ้วน
        $quotations = $query->with([
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
        ])->get();

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
            try {
                // เช็คสถานะงานว่าเสร็จหรือยัง - ถ้ายังไม่เสร็จ ไม่ให้แสดงกำไร
                if (function_exists('getStatusBadgeCount')) {
                    $statusCount = getStatusBadgeCount($item->quoteCheckStatus, $item);
                    if ($statusCount > 0) {
                        return false; // มีงานที่ยังไม่เสร็จ ไม่แสดงกำไร
                    }
                }

                // เช็คว่ายังรอเอกสารภาษีหรือไม่ - ตรวจสอบโดยตรงจากสถานะ
                if (function_exists('isWaitingForTaxDocuments')) {
                    try {
                        // เช็คว่าต้องตรวจสอบสถานะภาษีหรือไม่ (มีต้นทุนโฮลเซลล์หรือไม่)
                        if ($item->InputTaxVat && $item->InputTaxVat->count() > 0) {
                            // ตรวจสอบสถานะของใบกำกับภาษีโฮลเซลล์
                            $wholesaleTaxStatus = isset($item->quoteCheckStatus) ? 
                                $item->quoteCheckStatus->wholesale_tax_status : null;
                                
                            // ตรวจสอบเพิ่มเติมจาก input_tax_file ซึ่งเป็นฟิลด์ที่ใช้แสดงสถานะในหน้า UI
                            $hasInputTaxFile = !empty($item->checkfileInputtax) && !empty($item->checkfileInputtax->input_tax_file);
                                
                            // Log ข้อมูลโควต
                            Log::debug("Quote ID: {$item->quote_id}, Number: {$item->quote_number}, Tax Status: {$wholesaleTaxStatus}, Has File: " . ($hasInputTaxFile ? 'Yes' : 'No'));
                            
                            // ตรวจสอบเงื่อนไขโดยตรง: ถ้าไม่ใช่ 'ได้รับแล้ว' หรือไม่มีไฟล์ แสดงว่ายังรอ
                            $isWaiting = is_null($wholesaleTaxStatus) || 
                                         trim($wholesaleTaxStatus) !== 'ได้รับแล้ว' || 
                                         !$hasInputTaxFile; // เพิ่มเงื่อนไขตรวจสอบไฟล์
                                         
                            if ($isWaiting) {
                                Log::info("Quote {$item->quote_id} ({$item->quote_number}) filtered out: รอใบกำกับภาษีโฮลเซลล์");
                                return false; // ยังรอใบกำกับภาษีโฮลเซลล์ ไม่แสดงยอดขาย
                            }
                            
                            // ใช้ฟังก์ชันตรวจสอบเพิ่มเติม (ใช้เฉพาะกรณีที่การตรวจสอบแบบตรงไม่พบปัญหา)
                            $waitingForTax = isWaitingForTaxDocuments($item->quoteLogStatus, $item);
                            if ($waitingForTax) {
                                Log::info("Quote {$item->quote_id} ({$item->quote_number}) filtered out by isWaitingForTaxDocuments");
                                return false; // ยังรอเอกสารภาษี ไม่แสดงกำไร
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
