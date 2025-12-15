<?php
namespace App\Services;

use App\Models\quotations\quotationModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class internalQuotationFilterService
{
    public static function filter(Request $request)
    {
        // เพิ่ม execution time limit
        set_time_limit(300); // 5 นาที
        ini_set('memory_limit', '512M'); // เพิ่ม memory limit
        
      
        
        $user = Auth::user();
        $userRoles = $user->roles->pluck('name'); // แก้ไข getRoleNames()

        // ถ้ามีการค้นหาด้วย keyword ให้ตรวจสอบการมีอยู่ของ quote ก่อน
        if ($request->filled('keyword')) {
            $checkQuote = quotationModel::where('quote_number', 'LIKE', "%{$request->keyword}%")->first();
           
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

        // โหลดข้อมูลที่จำเป็นสำหรับการคำนวณเงินเท่านั้น
        $quotations = $query->with([
            'customer:customer_id,customer_name,customer_campaign_source',
            'quotePayments:payment_quote_id,payment_total,payment_type,payment_status,payment_file_path',
            'paymentWholesale:payment_wholesale_quote_id,payment_wholesale_total,payment_wholesale_refund_total,payment_wholesale_refund_status,payment_wholesale_file_name',
            'InputTaxVat:input_tax_id,input_tax_quote_id,input_tax_grand_total,input_tax_type'
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
                // ใช้ค่าที่ cache ไว้แล้ว
                $customerPaid = $item->_cached_deposit - $item->_cached_refund;
                $grandTotal = $item->quote_grand_total ?? 0;
                $inputtaxTotal = $item->_cached_inputtax_total;
                $wholesalePaidNet = $item->_cached_wholesale_paid - $item->_cached_wholesale_refund;

                // เงื่อนไข 1: ลูกค้าชำระเงินครบ
                if ($customerPaid < $grandTotal) {
                    return false;
                }

                // เงื่อนไข 2: ชำระเงินโฮลเซลล์ครบ (ถ้ามีต้นทุนโฮลเซลล์)
                if ($inputtaxTotal > 0) {
                    // มีต้นทุนโฮลเซลล์ - ต้องชำระครบ
                    return abs($wholesalePaidNet - $inputtaxTotal) < 0.01;
                }

                // ไม่มีต้นทุนโฮลเซลล์ - เพียงลูกค้าชำระครบ
                return true;
                
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