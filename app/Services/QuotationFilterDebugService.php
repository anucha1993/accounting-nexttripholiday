<?php
namespace App\Services;

use App\Models\quotations\quotationModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class QuotationFilterDebugService
{
    /**
     * Debug เงื่อนไขสำหรับ quote_id ที่ระบุ
     */
    public static function debugQuotation($quoteId)
    {
        $item = quotationModel::with([
            'quotePayments',
            'paymentWholesale',
            'InputTaxVat',
            'quoteInvoice',
            'quoteCheckStatus',
            'quoteLogStatus',
            'checkfileInputtax'
        ])->find($quoteId);

        if (!$item) {
            return ['error' => 'Quotation not found'];
        }

        $debug = [];
        
        // ข้อมูลพื้นฐาน
        $debug['quote_id'] = $item->quote_id;
        $debug['quote_number'] = $item->quote_number;
        $debug['quote_grand_total'] = $item->quote_grand_total;
        $debug['quote_withholding_tax_status'] = $item->quote_withholding_tax_status;
        
        // เช็คสถานะงาน
        if (function_exists('getStatusBadgeCount')) {
            $statusCount = getStatusBadgeCount($item->quoteCheckStatus, $item);
            $debug['status_badge_count'] = $statusCount;
            $debug['status_complete'] = $statusCount == 0;
        }
        
        // เช็คเอกสารภาษี
        if (function_exists('isWaitingForTaxDocuments')) {
            $waitingTax = isWaitingForTaxDocuments($item->quoteLogStatus, $item);
            $debug['waiting_for_tax_documents'] = $waitingTax;
            
            // รายละเอียดเอกสารภาษี
            $debug['tax_documents'] = [
                'has_input_tax_files' => !empty($item->checkfileInputtax),
                'wholesale_tax_status' => $item->quoteLogStatus->wholesale_tax_status ?? null,
                'withholding_tax_status' => $item->quoteLogStatus->withholding_tax_status ?? null,
                'need_customer_withholding' => $item->quote_withholding_tax_status === 'Y',
            ];
        }
        
        // การคำนวณยอดชำระลูกค้า
        $customerPaid = self::getCustomerPaid($item);
        $debug['customer_paid'] = $customerPaid;
        $debug['customer_payment_complete'] = $customerPaid >= $item->quote_grand_total;
        
        // ข้อมูลต้นทุนโฮลเซลล์
        $inputtaxTotalWholesale = self::getInputtaxTotalWholesale($item);
        $countPaymentWholesale = self::getCountPaymentWholesale($item);
        
        $debug['inputtax_total_wholesale'] = $inputtaxTotalWholesale;
        $debug['count_payment_wholesale'] = $countPaymentWholesale;
        
        // เงื่อนไขหลัก
        $debug['has_wholesale_cost'] = ($countPaymentWholesale > 0 && $inputtaxTotalWholesale > 0);
        $debug['no_wholesale_cost'] = ($inputtaxTotalWholesale == 0);
        
        if ($debug['has_wholesale_cost']) {
            // กรณีที่ 1: มีต้นทุนโฮลเซลล์
            $wholesalePayment = self::getWholesalePayment($item);
            $paymentInputtaxTotal = self::getPaymentInputtaxTotal($item);
            $wholesalePaidNet = self::getWholesalePaidNet($item);
            
            $debug['wholesale_payment'] = $wholesalePayment;
            $debug['payment_inputtax_total'] = $paymentInputtaxTotal;
            $debug['wholesale_paid_net'] = $wholesalePaidNet;
            
            $debug['has_payment'] = ($wholesalePayment > 0 || $paymentInputtaxTotal > 0);
            $debug['is_paid_complete'] = (abs($wholesalePaidNet - $inputtaxTotalWholesale) < 0.01);
            $debug['paid_difference'] = $wholesalePaidNet - $inputtaxTotalWholesale;
            
            $debug['should_show_profit_case1'] = $debug['has_payment'] && $debug['is_paid_complete'];
        }
        
        if ($debug['no_wholesale_cost']) {
            // กรณีที่ 2: ไม่มีต้นทุนโฮลเซลล์
            $debug['should_show_profit_case2'] = ($customerPaid > 0);
        }
        
        // ผลลัพธ์สุดท้าย
        $shouldShow = false;
        if ($debug['customer_payment_complete']) {
            if ($debug['has_wholesale_cost']) {
                $shouldShow = $debug['should_show_profit_case1'] ?? false;
            } elseif ($debug['no_wholesale_cost']) {
                $shouldShow = $debug['should_show_profit_case2'] ?? false;
            }
        }
        
        $debug['final_result'] = $shouldShow;
        
        // สรุปการตัดสินใจ
        $debug['final_result'] = [
            'should_show_profit' => self::shouldShowProfit($debug),
            'blocking_conditions' => self::getBlockingConditions($debug)
        ];
        
        return $debug;
    }
    
    // คัดลอก methods จาก QuotationFilterServiceNew
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

    private static function getInputtaxTotalWholesale($item)
    {
        if (!$item->InputTaxVat || $item->InputTaxVat->isEmpty()) {
            return 0;
        }
        
        return $item->InputTaxVat->whereIn('input_tax_type', [2, 4, 5, 6, 7])
                                ->sum('input_tax_grand_total');
    }

    private static function getCountPaymentWholesale($item)
    {
        if (!$item->paymentWholesale || $item->paymentWholesale->isEmpty()) {
            return 0;
        }
        
        return $item->paymentWholesale->where('payment_wholesale_file_name', '!=', '')
                                     ->where('payment_wholesale_file_name', '!=', null)
                                     ->count();
    }

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

    private static function getPaymentInputtaxTotal($item)
    {
        $withholdingTaxAmount = 0;
        if ($item->quoteInvoice) {
            $withholdingTaxAmount = $item->quoteInvoice->invoice_withholding_tax ?? 0;
        }
        
        $getTotalInputTaxVat = 0;
        if ($item->InputTaxVat && !$item->InputTaxVat->isEmpty()) {
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
        
        $hasInputTaxFile = $item->InputTaxVat && 
                          $item->InputTaxVat->whereNotNull('input_tax_file')->count() > 0;
        
        return $hasInputTaxFile ? 
            $withholdingTaxAmount - $getTotalInputTaxVat : 
            $withholdingTaxAmount + $getTotalInputTaxVat;
    }

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

    /**
     * ตรวจสอบว่าควรแสดงกำไรหรือไม่
     */
    private static function shouldShowProfit($debug)
    {
        // เช็คสถานะงาน
        if (isset($debug['status_complete']) && !$debug['status_complete']) {
            return false;
        }
        
        // เช็คเอกสารภาษี
        if (isset($debug['waiting_for_tax_documents']) && $debug['waiting_for_tax_documents']) {
            return false;
        }
        
        // เช็คการชำระลูกค้า
        if (!$debug['customer_payment_complete']) {
            return false;
        }
        
        // เงื่อนไขโฮลเซลล์
        if ($debug['has_wholesale_cost']) {
            return isset($debug['wholesale_conditions']) && 
                   $debug['wholesale_conditions']['has_payment'] && 
                   $debug['wholesale_conditions']['is_paid_complete'];
        } elseif ($debug['no_wholesale_cost']) {
            return true;
        }
        
        return false;
    }
    
    /**
     * หาเงื่อนไขที่ขัดขวาง
     */
    private static function getBlockingConditions($debug)
    {
        $blocking = [];
        
        if (isset($debug['status_complete']) && !$debug['status_complete']) {
            $blocking[] = 'มีงานที่ยังไม่เสร็จ (' . ($debug['status_badge_count'] ?? 0) . ' รายการ)';
        }
        
        if (isset($debug['waiting_for_tax_documents']) && $debug['waiting_for_tax_documents']) {
            $taxDetails = $debug['tax_documents'] ?? [];
            if ($taxDetails['has_input_tax_files'] && $taxDetails['wholesale_tax_status'] !== 'ได้รับแล้ว') {
                $blocking[] = 'รอใบกำกับภาษีโฮลเซลล์';
            }
            if ($taxDetails['need_customer_withholding'] && $taxDetails['withholding_tax_status'] !== 'ออกแล้ว') {
                $blocking[] = 'รอใบหัก ณ ที่จ่ายลูกค้า';
            }
        }
        
        if (!$debug['customer_payment_complete']) {
            $blocking[] = 'ลูกค้าชำระไม่ครบ (ชำระแล้ว: ' . number_format($debug['customer_paid'], 2) . 
                         ' จาก ' . number_format($debug['quote_grand_total'], 2) . ')';
        }
        
        if ($debug['has_wholesale_cost'] && isset($debug['wholesale_conditions'])) {
            $wc = $debug['wholesale_conditions'];
            if (!$wc['has_payment']) {
                $blocking[] = 'ยังไม่มีการชำระโฮลเซลล์หรือภาษีซื้อ';
            }
            if (!$wc['is_paid_complete']) {
                $blocking[] = 'ชำระโฮลเซลล์ไม่ครบ (ชำระแล้ว: ' . number_format($wc['wholesale_paid_net'], 2) . 
                             ' จาก ' . number_format($debug['inputtax_total_wholesale'], 2) . ')';
            }
        }
        
        return $blocking;
    }
}
