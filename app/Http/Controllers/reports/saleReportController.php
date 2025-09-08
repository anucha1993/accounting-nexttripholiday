<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SaleReportExport;
use App\Models\invoices\taxinvoiceModel;
use App\Models\wholesale\wholesaleModel;
use App\Services\QuotationFilterService;
use App\Models\quotations\quotationModel;
use App\Models\commissions\commissionListModel;
use App\Models\commissions\commissionGroupModel;

class saleReportController extends Controller
{
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
        $mode = $request->commission_mode ?? 'all';

        // Fetch all dropdown data in parallel using promises
        $promises = [
            'campaignSource' => DB::table('campaign_source')->get(),
            'wholesales' => WholesaleModel::where('status', 'on')->get(),
            'country' => DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get(),
        ];

        $user = Auth::user();
        // Get sales list based on user role
        $salesQuery = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin']);
            
        if ($user && $user->hasRole('sale')) {
            $salesQuery->where('id', $user->sale_id);
        }
        
        $promises['sales'] = $salesQuery->get();
        
        // Get filtered quotations with eager loaded relationships from the Service
        $quotationSuccess = QuotationFilterService::filter($request);
        
        // Convert promises to values
        $data = [
            'request' => $request,
            'mode' => $mode,
            'quotationSuccess' => $quotationSuccess,
        ];
        
        foreach ($promises as $key => $promise) {
            $data[$key] = $promise;
        }
        
        return view('reports.sales-form', $data);

        // Handle total mode grouping
        if ($mode === 'total') {
            $saleGroups = $quotationSuccess
                ->filter(function ($item) {
                    $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'total', $item->quote_pax_total);
                    return in_array($commission['type'], ['step-Total', 'percent-Total']);
                })
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
                
            $data['saleGroups'] = $saleGroups;
            unset($data['quotationSuccess']);
        } elseif ($mode === 'qt') {
            // Filter for qt mode
            $data['quotationSuccess'] = $quotationSuccess
                ->filter(function ($item) {
                    $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'qt', $item->quote_pax_total);
                    return in_array($commission['type'], ['step-QT', 'percent-QT', 'no-commission']);
                })
                ->values();
        }
        // ✅ ถ้าเลือก mode = total
        if ($mode === 'total') {
            $quotationSuccess = $quotationSuccess
                ->filter(function ($item) {
                    $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'total', $item->quote_pax_total);
                    return in_array($commission['type'], ['step-Total', 'percent-Total']);
                })
                ->values();
            // group by sale_id
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
            return view('reports.sales-form', compact('saleGroups', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
        }
        // ✅ ถ้าเลือก mode = qt
        if ($mode === 'qt') {
            $quotationSuccess = $quotationSuccess
                ->filter(function ($item) {
                    $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'qt', $item->quote_pax_total);
                    return in_array($commission['type'], ['step-QT', 'percent-QT', 'no-commission']);
                })
                ->values();
            return view('reports.sales-form', compact('quotationSuccess', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
        }
        // ✅ ถ้าเลือก mode = all หรือไม่มีการเลือก
        logger(DB::getQueryLog());
        return view('reports.sales-form', compact('quotationSuccess', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
    }

 
}
