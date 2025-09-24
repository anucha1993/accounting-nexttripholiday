<?php
namespace App\Services;

use App\Models\quotations\quotationModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class QuotationFilterServiceOptimized
{
    public static function filter(Request $request)
    {
        // เพิ่ม execution time และ memory limit
        set_time_limit(300);
        ini_set('memory_limit', '1024M');
        
        $user = Auth::user();
        $userRoles = $user->roles->pluck('name'); // แทน getRoleNames()

        // สร้าง Base Query
        $query = quotationModel::select([
            'quote_id', 'quote_number', 'quote_date_start', 'quote_date_end', 
            'quote_grand_total', 'quote_pax_total', 'quote_sale', 'quote_tour_name',
            'quote_country', 'quote_wholesale', 'quote_status', 'quote_commission'
        ])->where('quote_status', 'success');

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

        // กรอง Database Level - เฉพาะรายการที่ลูกค้าชำระครบ
        $query->whereRaw('
            (SELECT COALESCE(SUM(p.payment_total), 0) 
             FROM payments p 
             WHERE p.payment_quote_id = quotations.quote_id 
             AND p.payment_status != "cancel" 
             AND p.payment_type != "refund") >= quotations.quote_grand_total
        ');

        // Load เฉพาะ Relations ที่จำเป็น
        $quotations = $query->with([
            'quotePayments' => function($q) {
                $q->select('payment_quote_id', 'payment_total', 'payment_type', 'payment_status');
            },
            'paymentWholesale' => function($q) {
                $q->select('payment_wholesale_quote_id', 'payment_wholesale_total', 
                          'payment_wholesale_refund_total', 'payment_wholesale_refund_status',
                          'payment_wholesale_file_name');
            },
            'quoteCheckStatus' => function($q) {
                $q->select('quote_id', 'booking_email_status', 'quote_status', 'inv_status', 
                          'depositslip_status', 'fullslip_status', 'passport_status', 'appointment_status', 
                          'wholesale_skip_status', 'withholding_tax_status', 'wholesale_tax_status');
            },
            'quoteLogStatus' => function($q) {
                $q->select('input_tax_quote_id', 'input_tax_status', 'input_tax_withholding_status');
            },
            'checkfileInputtax' // เพิ่มเพื่อใช้ใน getStatusBadgeCount
        ])->orderBy('quote_date_start', 'desc')
          ->limit(5000) // จำกัดจำนวน
          ->get();

        // กรองด้วย Application Logic (ย่อให้เหลือเฉพาะที่จำเป็น)
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

                // ใช้การคำนวณแบบง่าย
                $inputtaxTotal = self::getInputtaxTotal($item->quote_id);
                
                // กรณีไม่มีต้นทุนโฮลเซลล์ - ผ่านเลย
                if ($inputtaxTotal == 0) {
                    return true;
                }
                
                // กรณีมีต้นทุนโฮลเซลล์ - ตรวจสอบว่าชำระครบหรือยัง
                $wholesalePaid = self::getWholesalePaid($item->quote_id);
                return abs($wholesalePaid - $inputtaxTotal) < 0.01;
                
            } catch (\Exception $e) {
                return false;
            }
        })->values();
    }

    /**
     * คำนวณต้นทุนโฮลเซลล์แบบง่าย
     */
    private static function getInputtaxTotal($quoteId)
    {
        return DB::table('input_tax')
            ->where('input_tax_quote_id', $quoteId)
            ->whereIn('input_tax_type', [2, 4, 5, 6, 7])
            ->sum('input_tax_grand_total');
    }

    /**
     * คำนวณยอดที่ชำระโฮลเซลล์แล้วแบบง่าย
     */
    private static function getWholesalePaid($quoteId)
    {
        $paid = DB::table('payment_wholesale')
            ->where('payment_wholesale_quote_id', $quoteId)
            ->where('payment_wholesale_file_name', '!=', '')
            ->sum('payment_wholesale_total');
            
        $refund = DB::table('payment_wholesale')
            ->where('payment_wholesale_quote_id', $quoteId)
            ->where('payment_wholesale_refund_status', 'success')
            ->sum('payment_wholesale_refund_total');
            
        return $paid - $refund;
    }
}
