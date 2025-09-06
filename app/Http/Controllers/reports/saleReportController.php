<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Exports\SaleReportExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\invoices\taxinvoiceModel;
use App\Models\wholesale\wholesaleModel;
use App\Services\QuotationFilterService;
use App\Models\quotations\quotationModel;
use App\Models\commissions\commissionListModel;
use App\Models\commissions\commissionGroupModel;

class saleReportController extends Controller
{

    public function __construct()
    {
        DB::listen(function($query) {
            Log::info(
                'SQL: ' . $query->sql . ' [' . 
                implode(', ', $query->bindings) . ']' . 
                ' Time: ' . $query->time . 'ms'
            );
        });
    }

    public function export(Request $request)
    {
       
        $mode = $request->commission_mode ?? 'all';
        $quotationSuccess = QuotationFilterService::filter($request);

        if ($mode === 'total') {
            $quotationSuccess = $quotationSuccess
                ->filter(function ($item) {
                    $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'total', $item->quote_pax_total);
                    return in_array($commission['type'], ['step-Total', 'percent-Total']);
                })
                ->values();

            // Group by sale_id for total mode
            $saleGroups = $quotationSuccess
                ->groupBy('quote_sale')
                ->map(function ($items, $saleId) {
                    return [
                        'sale_id' => $saleId,
                        'items' => $items,
                        'net_profit_sum' => $items->sum(fn($i) => $i->getNetProfit()),
                        'pax_sum' => $items->sum('quote_pax_total'),
                    ];
                })
                ->values();

            $exportData = collect();
            foreach ($saleGroups as $group) {
                $exportData = $exportData->merge($group['items']);
            }
        } elseif ($mode === 'qt') {
            $quotationSuccess = $quotationSuccess
                ->filter(function ($item) {
                    $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'qt', $item->quote_pax_total);
                    return in_array($commission['type'], ['step-QT', 'percent-QT', 'no-commission']);
                })
                ->values();
            $exportData = $quotationSuccess;
        } else {
            $exportData = $quotationSuccess;
        }

        $filename = 'sale_report_' . $mode . '_' . date('Y-m-d') . '.xlsx';
        $campaignSource = DB::table('campaign_source')->get();
        
        // ตรวจสอบว่า $exportData เป็น Collection หรือไม่
        if (!$exportData instanceof \Illuminate\Support\Collection) {
            $exportData = collect($exportData);
        }
        
        return Excel::download(new SaleReportExport($exportData, $mode, $campaignSource), $filename);
    }


    public function index(Request $request)
    {
        DB::enableQueryLog();
        $mode = $request->commission_mode ?? 'all';
        
        // ดึงข้อมูลอื่น ๆ สำหรับ filter dropdown
        $campaignSource = DB::table('campaign_source')->get();
        $wholesales = WholesaleModel::where('status', 'on')->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        
        $user = Auth::user();
        $userRoles = $user->getRoleNames();
        
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

        // ✅ ใช้ Service ที่แยก logic ออกมาแล้ว
        $quotationSuccess = QuotationFilterService::filter($request);

        // ✅ ถ้าเลือก mode = total
        if ($mode === 'total') {
            $quotationData = collect($quotationSuccess->items())
                ->filter(function ($item) {
                    $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'total', $item->quote_pax_total);
                    return in_array($commission['type'], ['step-Total', 'percent-Total']);
                })
                ->values();

            // group by sale_id
            $saleGroups = $quotationData
                ->groupBy('quote_sale')
                ->map(function ($items, $saleId) {
                    return [
                        'sale_id' => $saleId,
                        'items' => $items,
                        'net_profit_sum' => $items->sum(fn($i) => $i->getNetProfit()),
                        'pax_sum' => $items->sum('quote_pax_total'),
                    ];
                })
                ->values();

            Log::info('Queries:', DB::getQueryLog());
            DB::disableQueryLog();
            
            return view('reports.sales-form', compact('saleGroups', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
        }

        // ✅ ถ้าเลือก mode = qt
        if ($mode === 'qt') {
            $quotationSuccess = collect($quotationSuccess->items())
                ->filter(function ($item) {
                    $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'qt', $item->quote_pax_total);
                    return in_array($commission['type'], ['step-QT', 'percent-QT', 'no-commission']);
                })
                ->values();

            Log::info('Queries:', DB::getQueryLog());
            DB::disableQueryLog();

            return view('reports.sales-form', compact('quotationSuccess', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
        }

        // ✅ ถ้าเลือก mode = all หรือไม่มีการเลือก
        Log::info('Queries:', DB::getQueryLog());
        DB::disableQueryLog();
        
        return view('reports.sales-form', compact('quotationSuccess', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));

   
}
}
