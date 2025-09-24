<?php
namespace App\Services;

use App\Models\quotations\quotationModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class QuotationFilterServiceNew
{
    public static function filter(Request $request)
    {
        // เพิ่ม execution time limit
        set_time_limit(300);
        ini_set('memory_limit', '512M');
        
        $user = Auth::user();
        $userRoles = $user->roles->pluck('name');

        $query = quotationModel::whereNotIn('quote_status', ['cancel', 'wait']);

        // User Role Filter
        if ($userRoles->contains('sale')) {
            $query->where('quote_sale', $user->sale_id);
        }

        // Date Filters
        if ($request->filled('date_start')) {
            $query->where('quote_date_start', '>=', $request->input('date_start'));
        }
        if ($request->filled('date_end')) {
            $query->where('quote_date_start', '<=', $request->input('date_end'));
        }

        // Other Filters
        if ($request->filled('sale_id')) {
            $query->where('quote_sale', $request->input('sale_id'));
        }
        if ($request->filled('wholsale_id')) {
            $query->where('quote_wholesale', $request->input('wholsale_id'));
        }
        if ($request->filled('country_id')) {
            $query->where('quote_country', $request->input('country_id'));
        }

        // Customer Campaign Source Filter
        if ($request->filled('campaign_source_id')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('customer_campaign_source', $request->campaign_source_id);
            });
        }

        // Keyword Search
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

        // Eager Loading เพื่อแก้ปัญหา N+1 Query
        $quotations = $query->with([
            'quotePayments' => function($q) {
                $q->select('payment_quote_id', 'payment_total', 'payment_type', 'payment_status', 'payment_file_path');
            },
            'paymentWholesale' => function($q) {
                $q->select('payment_wholesale_quote_id', 'payment_wholesale_total', 
                          'payment_wholesale_refund_total', 'payment_wholesale_refund_status', 
                          'payment_wholesale_file_name');
            },
            'InputTaxVat' => function($q) {
                $q->select('input_tax_quote_id', 'input_tax_grand_total', 'input_tax_type', 
                          'input_tax_file', 'input_tax_status', 'input_tax_withholding', 'input_tax_vat');
            },
            'quoteInvoice' => function($q) {
                $q->select('invoice_quote_id', 'invoice_withholding_tax');
            },
            'quoteCheckStatus' => function($q) {
                $q->select('quote_id', 'booking_email_status', 'quote_status', 'inv_status', 
                          'depositslip_status', 'fullslip_status', 'passport_status', 'appointment_status', 
                          'wholesale_skip_status', 'withholding_tax_status', 'wholesale_tax_status');
            },
            'quoteLogStatus' => function($q) {
                $q->select('input_tax_quote_id', 'input_tax_status', 'input_tax_withholding_status');
            },
            'checkfileInputtax'
        ])->get();

        // กรองตามเงื่อนไขที่ระบุ
        return $quotations->filter(function ($item) {
            try {
                // เช็คสถานะงานว่าเสร็จหรือยัง - ถ้ายังไม่เสร็จ ไม่ให้แสดงกำไร
                if (function_exists('getStatusBadgeCount')) {
                    $statusCount = getStatusBadgeCount($item->quoteCheckStatus, $item);
                    if ($statusCount > 0) {
                        return false; // มีงานที่ยังไม่เสร็จ ไม่แสดงกำไร
                    }
                }

                // เช็คว่ายังรอเอกสารภาษีหรือไม่
                if (function_exists('isWaitingForTaxDocuments')) {
                    if (isWaitingForTaxDocuments($item->quoteLogStatus, $item)) {
                        return false; // ยังรอเอกสารภาษี ไม่แสดงกำไร
                    }
                }
                
                // คำนวณค่าต่างๆ
                $customerPaid = self::getCustomerPaid($item);
                $grandTotal = $item->quote_grand_total ?? 0;
                
                // เงื่อนไขพื้นฐาน: ลูกค้าต้องชำระครบ
                if ($customerPaid < $grandTotal) {
                    return false;
                }

                $inputtaxTotalWholesale = self::getInputtaxTotalWholesale($item);
                $countPaymentWholesale = self::getCountPaymentWholesale($item);
                
                // กรณีที่ 1: มีต้นทุนโฮลเซลล์
                if ($countPaymentWholesale > 0 && $inputtaxTotalWholesale > 0) {
                    
                    // ตรวจสอบเงื่อนไขย่อย
                    $wholesalePayment = self::getWholesalePayment($item);
                    $paymentInputtaxTotal = self::getPaymentInputtaxTotal($item);
                    $wholesalePaidNet = self::getWholesalePaidNet($item);
                    
                    // มีการชำระโฮลเซลล์หรือภาษีซื้อ
                    $hasPayment = ($wholesalePayment > 0 || $paymentInputtaxTotal > 0);
                    
                    // ยอดชำระโฮลเซลล์สุทธิต้องเท่ากับยอดที่ต้องชำระ
                    $isPaidComplete = (abs($wholesalePaidNet - $inputtaxTotalWholesale) < 0.01);
                    
                    return $hasPayment && $isPaidComplete;
                }
                
                // กรณีที่ 2: ไม่มีต้นทุนโฮลเซลล์
                elseif ($inputtaxTotalWholesale == 0 && $customerPaid > 0) {
                    return true; // ลูกค้าชำระเงินแล้ว (ตรวจแล้วข้างบน)
                }
                
                // กรณีอื่นๆ ไม่ผ่าน
                return false;
                
            } catch (\Exception $e) {
                Log::warning("QuotationFilterServiceNew error for quote_id: " . $item->quote_id . " - " . $e->getMessage());
                return false;
            }
        })->values();
    }

    /**
     * คำนวณยอดที่ลูกค้าชำระ (สุทธิ)
     */
    private static function getCustomerPaid($item)
    {
        if (!$item->quotePayments || $item->quotePayments->isEmpty()) {
            return 0;
        }
        
        $deposit = $item->quotePayments->where('payment_status', '!=', 'cancel')
                                     ->where('payment_type', '!=', 'refund')
                                     ->sum('payment_total');
                                     
        $refund = $item->quotePayments->where('payment_status', '!=', 'cancel')
                                    ->where('payment_type', '=', 'refund')
                                    ->whereNotNull('payment_file_path')
                                    ->sum('payment_total');
        
        return $deposit - $refund;
    }

    /**
     * คำนวณต้นทุนโฮลเซลล์รวม
     */
    private static function getInputtaxTotalWholesale($item)
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
    private static function getCountPaymentWholesale($item)
    {
        if (!$item->paymentWholesale || $item->paymentWholesale->isEmpty()) {
            return 0;
        }
        
        return $item->paymentWholesale->where('payment_wholesale_file_name', '!=', '')
                                     ->where('payment_wholesale_file_name', '!=', null)
                                     ->count();
    }

    /**
     * คำนวณยอดชำระโฮลเซลล์ (ไม่รวม refund)
     */
    private static function getWholesalePayment($item)
    {
        if (!$item->paymentWholesale || $item->paymentWholesale->isEmpty()) {
            return 0;
        }
        
        $paid = $item->paymentWholesale->where('payment_wholesale_file_name', '!=', '')
                                     ->where('payment_wholesale_file_name', '!=', null)
                                     ->sum('payment_wholesale_total');
                                     
        $refund = $item->paymentWholesale->where('payment_wholesale_refund_status', '=', 'success')
                                       ->sum('payment_wholesale_refund_total');
        
        return $paid - $refund;
    }

    /**
     * คำนวณยอดภาษีซื้อสุทธิ
     */
    private static function getPaymentInputtaxTotal($item)
    {
        $withholdingTaxAmount = 0;
        if ($item->quoteInvoice) {
            $withholdingTaxAmount = $item->quoteInvoice->invoice_withholding_tax ?? 0;
        }
        
        $getTotalInputTaxVat = 0;
        if ($item->InputTaxVat && !$item->InputTaxVat->isEmpty()) {
            // คำนวณ getTotalInputTaxVat ตามตรรกะเดิม
            $pendingVat = $item->InputTaxVat->whereIn('input_tax_type', [1, 3])
                                          ->whereNull('input_tax_file')
                                          ->sum('input_tax_grand_total');
                                          
            $fileVat = $item->InputTaxVat->whereNotNull('input_tax_file')
                                       ->whereIn('input_tax_type', [1, 3])
                                       ->where('input_tax_status', 'success')
                                       ->sum(function($tax) {
                                           return $tax->input_tax_vat - $tax->input_tax_withholding;
                                       });
            
            $getTotalInputTaxVat = $pendingVat + $fileVat;
        }
        
        // ตรวจสอบว่ามีไฟล์ input tax หรือไม่
        $hasInputTaxFile = $item->InputTaxVat && 
                          $item->InputTaxVat->whereNotNull('input_tax_file')->count() > 0;
        
        return $hasInputTaxFile ? 
            $withholdingTaxAmount - $getTotalInputTaxVat : 
            $withholdingTaxAmount + $getTotalInputTaxVat;
    }

    /**
     * คำนวณยอดโฮลเซลล์ที่ชำระสุทธิ
     */
    private static function getWholesalePaidNet($item)
    {
        if (!$item->paymentWholesale || $item->paymentWholesale->isEmpty()) {
            return 0;
        }
        
        $paid = $item->paymentWholesale->where('payment_wholesale_file_name', '!=', '')
                                     ->where('payment_wholesale_file_name', '!=', null)
                                     ->sum('payment_wholesale_total');
                                     
        $refund = $item->paymentWholesale->where('payment_wholesale_refund_status', '=', 'success')
                                       ->sum('payment_wholesale_refund_total');
        
        return $paid - $refund;
    }
}
