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

    // public function index(Request $request)
    // {
    //     $searchDateStart = $request->input('date_start');
    //     $searchDateEnd = $request->input('date_end');
    //     $column_name = $request->input('column_name');
    //     $keyword = $request->input('keyword');
    //     $wholesaleId = $request->input('wholsale_id');
    //     $countryId = $request->input('country_id');
    //     $saleId = $request->input('sale_id');
    //     $mode = $request->commission_mode ?? 'all'; // กำหนด default เป็น qt
    //     $campaignSource = DB::table('campaign_source')->get();
    //     $wholesales = WholesaleModel::where('status', 'on')->get();
    //     $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
    //       $user = Auth::user();
    //     $userRoles = $user->getRoleNames();
    //     if (Auth::user()->getRoleNames()->contains('sale')) {
    //         $sales = saleModel::select('name', 'id')
    //             ->where('id', Auth::user()->sale_id)
    //             ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
    //             ->get();
    //     } else {
    //         $sales = saleModel::select('name', 'id')
    //             ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
    //             ->get();
    //     }

    //     // ดึงใบเสนอราคาทั้งหมดที่สำเร็จ
    //     $quotationQuery = quotationModel::where('quote_status', 'success')
    //      ->when($userRoles->contains('sale'), function ($query) use ($user) {
    //         return $query->where('quote_sale', $user->sale_id);
    //         });

    //     // Filter วันที่ออกเดินทาง (quote_date_start)
    //     if ($searchDateStart) {
    //         $quotationQuery = $quotationQuery->where('quote_date_start', '>=', $searchDateStart);
    //     }
    //     if ($searchDateEnd) {
    //         $quotationQuery = $quotationQuery->where('quote_date_start', '<=', $searchDateEnd);
    //     }

    //     // Filter เซลล์ผู้ขาย
    //     if ($saleId) {
    //         $quotationQuery = $quotationQuery->where('quote_sale', $saleId);
    //     }

    //     // Filter ที่มาของลูกค้า (campaign source) จาก customer
    //     if ($request->filled('campaign_source_id')) {
    //         $quotationQuery = $quotationQuery->whereHas('customer', function($q) use ($request) {
    //             $q->where('customer_campaign_source', $request->campaign_source_id);
    //         });
    //     }

    //     // Filter โฮลเซลล์
    //     if ($wholesaleId) {
    //         $quotationQuery = $quotationQuery->where('quote_wholesale', $wholesaleId);
    //     }

    //     // Filter ประเทศ
    //     if ($countryId) {
    //         $quotationQuery = $quotationQuery->where('quote_country', $countryId);
    //     }

    //     // Filter คีย์เวิร์ด (Quotes/ชื่อลูกค้า/แพคเกจทัวร์ที่ซื้อ)
    //     if ($keyword) {
    //         $quotationQuery = $quotationQuery->where(function($q) use ($keyword) {
    //             $q->where('quote_number', 'LIKE', "%$keyword%") // Quotes
    //               ->orWhere('quote_tour_name', 'LIKE', "%$keyword%") // แพคเกจทัวร์ที่ซื้อ
    //               ->orWhereHas('customer', function($q2) use ($keyword) { // ชื่อลูกค้า (ต้องมี relation customer)
    //                   $q2->where('customer_name', 'LIKE', "%$keyword%");
    //               });
    //         });
    //     }

    //     $quotationSuccess = $quotationQuery->get()

    //         ->filter(function ($item) {
    //             // ลูกค้าต้องชำระเงินครบ (GetDeposit() >= quote_grand_total)
    //             $customerPaid = $item->GetDeposit()?? 0;
    //             $grandTotal = $item->quote_grand_total ?? 0;
    //             // โฮลเซลต้องชำระครบ (inputtaxTotalWholesale() - getWholesalePaidNet() == 0)
    //             $wholesaleOutstanding = 0;
    //             if (method_exists($item, 'inputtaxTotalWholesale') && method_exists($item, 'getWholesalePaidNet')) {
    //                 $wholesaleOutstanding = $item->inputtaxTotalWholesale() - $item->getWholesalePaidNet();
    //             }
    //             // ห้ามมีสถานะคืนเงินลูกค้าทุกแบบ
    //             $status = getQuoteStatusQuotePayment($item);
    //             $forbidden = [
    //                 'รอคืนเงินลูกค้า',
    //                 'ยังไม่ได้คืนเงินลูกค้า',
    //             ];
    //             foreach ($forbidden as $word) {
    //                 if (strpos($status, $word) !== false) {
    //                     return false;
    //                 }
    //             }
    //             // ห้ามมีสถานะโฮลเซลล์ที่ไม่อนุญาต
    //             $wholesaleStatus = getStatusPaymentWhosale($item);
    //             $forbiddenWholesale = [
    //                 'รอโฮลเซลล์คืนเงิน',
    //                 'โอนเงินให้โฮลเซลล์เกิน',
    //                 'รอชำระเงินมัดจำ',
    //                 'รอชำระเงินส่วนที่เหลือ',
    //             ];
    //             foreach ($forbiddenWholesale as $word) {
    //                 if (strpos($wholesaleStatus, $word) !== false) {
    //                     return false;
    //                 }
    //             }
    //             return ($customerPaid >= $grandTotal) && ($wholesaleOutstanding == 0);
    //         })
    //         ->values();

    //     // Filter เฉพาะใบเสนอราคาที่ค่าคอมตรงกับ mode ที่เลือก (qt หรือ total)
    //     if ($mode === 'total') {
    //         // Filter เฉพาะใบเสนอราคาที่ค่าคอมเป็น step-Total หรือ percent-Total
    //         $quotationSuccess = $quotationSuccess
    //             ->filter(function ($item) {
    //                 $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'total', $item->quote_pax_total);
    //                 return in_array($commission['type'], ['step-Total', 'percent-Total']);
    //             })
    //             ->values();
    //         // Group by sale_id
    //         $saleGroups = $quotationSuccess
    //             ->groupBy('quote_sale')
    //             ->map(function ($items, $saleId) {
    //                 $netProfitSum = $items->sum(function ($item) {
    //                     return $item->getNetProfit();
    //                 });
    //                 $paxSum = $items->sum('quote_pax_total');
    //                 return [
    //                     'sale_id' => $saleId,
    //                     'items' => $items,
    //                     'net_profit_sum' => $netProfitSum,
    //                     'pax_sum' => $paxSum,
    //                 ];
    //             })
    //             ->values();
    //         return view('reports.sales-form', compact('saleGroups', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
    //     } elseif ($mode === 'qt') {
    //         // รายใบเสนอราคา (qt)
    //         $quotationSuccess = $quotationSuccess
    //             ->filter(function ($item) use ($mode) {
    //                 $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'qt', $item->quote_pax_total);
    //                 if ($mode === 'qt') {
    //                    return in_array($commission['type'], ['step-QT', 'percent-QT', 'no-commission']);
    //                 }
    //                 return true;
    //             })
    //             ->values();
    //         return view('reports.sales-form', compact('quotationSuccess', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
    //     } else {
    //         // รายใบเสนอราคา (all) ไม่ต้อง filter ใดๆ แสดงทุก commission
    //         return view('reports.sales-form', compact('quotationSuccess', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
    //     }
    // }
}
