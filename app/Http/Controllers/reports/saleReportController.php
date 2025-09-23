<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SaleReportExport;
use App\Models\wholesale\wholesaleModel;
use App\Services\QuotationFilterService;
use App\Services\QuotationFilterServiceOptimized;
use App\Services\QuotationFilterServiceNew;
use Illuminate\Pagination\LengthAwarePaginator;

class saleReportController extends Controller
{
    public function export(Request $request)
    {
        // เพิ่ม time limit สำหรับ export
        set_time_limit(600); // 10 นาที
        ini_set('memory_limit', '1024M');
        
        $mode = $request->commission_mode ?? 'all';
        
        // ใช้ Service ใหม่ที่มีเงื่อนไขการแสดงกำไรที่ถูกต้อง
        try {
            $quotationSuccess = QuotationFilterServiceNew::filter($request);
        } catch (\Exception $e) {
            // Fallback ไปใช้ Service เดิมถ้ามีปัญหา
            Log::warning("QuotationFilterServiceNew failed in export, fallback to old service: " . $e->getMessage());
            try {
                $quotationSuccess = QuotationFilterServiceOptimized::filter($request);
            } catch (\Exception $e2) {
                $quotationSuccess = QuotationFilterService::filter($request);
            }
        }

        if ($mode === 'total') {
            $quotationSuccess = $quotationSuccess->filter(function ($item) {
                $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'total', $item->quote_pax_total);
                return in_array($commission['type'], ['step-Total', 'percent-Total']);
            })->values();

            $saleGroups = $quotationSuccess->groupBy('quote_sale')->map(function ($items, $saleId) {
                return [
                    'sale_id'       => $saleId,
                    'items'         => $items,
                    'net_profit_sum'=> $items->sum(fn($i) => $i->getNetProfit()),
                    'pax_sum'       => $items->sum('quote_pax_total'),
                ];
            })->values();

            $exportData = collect();
            foreach ($saleGroups as $group) {
                $exportData = $exportData->merge($group['items']);
            }
        } elseif ($mode === 'qt') {
            $quotationSuccess = $quotationSuccess->filter(function ($item) {
                $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'qt', $item->quote_pax_total);
                return in_array($commission['type'], ['step-QT', 'percent-QT', 'no-commission']);
            })->values();
            $exportData = $quotationSuccess;
        } else {
            $exportData = $quotationSuccess;
        }

        $filename = 'sale_report_' . $mode . '_' . date('Y-m-d') . '.xlsx';
        $campaignSource = DB::table('campaign_source')->get();

        return Excel::download(new SaleReportExport(collect($exportData), $mode, $campaignSource), $filename);
    }

    public function index(Request $request)
    {
        $mode = $request->commission_mode ?? 'all';

        // dropdown data
        $campaignSource = DB::table('campaign_source')->get();
        $wholesales     = WholesaleModel::where('status', 'on')->get();
        $country        = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();

        $user = Auth::user();
        $userRoles = $user->roles->pluck('name'); // แทน getRoleNames()
        if ($userRoles->contains('sale')) {
            $sales = saleModel::select('name', 'id')
                ->where('id', $user->sale_id)
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        } else {
            $sales = saleModel::select('name', 'id')
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        }

        // ใช้ Service ใหม่ที่มีเงื่อนไขการแสดงกำไรที่ถูกต้อง
        try {
            $quotationSuccess = QuotationFilterServiceNew::filter($request);
        } catch (\Exception $e) {
            // Fallback ไปใช้ Service เดิมถ้ามีปัญหา
            Log::warning("QuotationFilterServiceNew failed, fallback to old service: " . $e->getMessage());
            try {
                $quotationSuccess = QuotationFilterServiceOptimized::filter($request);
            } catch (\Exception $e2) {
                $quotationSuccess = QuotationFilterService::filter($request);
            }
        }
        
        // ตรวจสอบว่ามีการค้นหาหรือไม่
        $hasSearch = $this->hasSearchCriteria($request);

        if ($mode === 'total') {
            $quotationSuccess = $quotationSuccess->filter(function ($item) {
                $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'total', $item->quote_pax_total);
                return in_array($commission['type'], ['step-Total', 'percent-Total']);
            })->values();

            $saleGroups = $quotationSuccess->groupBy('quote_sale')->map(function ($items, $saleId) {
                return [
                    'sale_id'        => $saleId,
                    'items'          => $items,
                    'net_profit_sum' => $items->sum(fn($i) => $i->getNetProfit()),
                    'pax_sum'        => $items->sum('quote_pax_total'),
                ];
            })->values();

            return view('reports.sales-form', compact('saleGroups', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode', 'hasSearch'));
        }

        // --------- QT หรือ ALL → ทำ Pagination จาก Collection ----------
        // ถ้ามีการค้นหา ให้แสดง 4000 records/หน้า ถ้าไม่มีให้แสดง 50 records/หน้า (หรือตามที่ผู้ใช้เลือก)
        if ($hasSearch) {
            $perPage = 4000;
        } else {
            $allowedPerPage = [50, 100, 200, 1000];
            $requestedPerPage = (int) $request->input('per_page', 50);
            $perPage = in_array($requestedPerPage, $allowedPerPage) ? $requestedPerPage : 50;
        }
        $page = max(1, (int) $request->input('page', 1));

        if ($mode === 'qt') {
            $quotationSuccess = $quotationSuccess->filter(function ($item) {
                $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'qt', $item->quote_pax_total);
                return in_array($commission['type'], ['step-QT', 'percent-QT', 'no-commission']);
            })->values();
        }

        // slice หน้าปัจจุบันและห่อเป็น paginator
        $paginated = new LengthAwarePaginator(
            $quotationSuccess->forPage($page, $perPage)->values(),
            $quotationSuccess->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ส่งตัวแปรชื่อเดิมให้ Blade ใช้ต่อได้
        $quotationSuccess = $paginated;

        return view('reports.sales-form', compact('quotationSuccess', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode', 'hasSearch'));
    }

    /**
     * ตรวจสอบว่ามีการค้นหาหรือไม่
     * ไม่นับฟิลด์ที่เป็นการเลือกประเภทการแสดงผล เช่น commission_mode, per_page, page
     */
    private function hasSearchCriteria(Request $request)
    {
        // ฟิลด์ที่ถือเป็นการค้นหาจริงๆ (ไม่รวมการเลือกประเภทการแสดงผล)
        $searchFields = [
            'date_start', 'date_end', 'quote_country', 'quote_wholesale', 
            'quote_sale', 'customer_campaign_source', 'quote_number', 
            'customer_name', 'quote_tour_name'
        ];
        
        foreach ($searchFields as $field) {
            if ($request->filled($field)) {
                return true;
            }
        }
        
        return false;
    }
}