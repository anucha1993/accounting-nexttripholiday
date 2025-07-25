<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\taxinvoiceModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\commissions\commissionListModel;
use App\Models\commissions\commissionGroupModel;

class saleReportController extends Controller
{
    //

    public function index(Request $request)
    {
        $searchDateStart = $request->input('date_start');
        $searchDateEnd = $request->input('date_end');
        $column_name = $request->input('column_name');
        $keyword = $request->input('keyword');
        $wholesaleId = $request->input('wholsale_id');
        $countryId = $request->input('country_id');
        $saleId = $request->input('sale_id');
        $mode = $request->commission_mode ?? 'all'; // กำหนด default เป็น qt
        $campaignSource = DB::table('campaign_source')->get();
        $wholesales = WholesaleModel::where('status', 'on')->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        
        if (Auth::user()->getRoleNames()->contains('sale')) {
            $sales = saleModel::select('name', 'id')
                ->where('id', Auth::user()->sale_id)
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        } else {
            $sales = saleModel::select('name', 'id')
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        }

        // ดึงใบเสนอราคาทั้งหมดที่สำเร็จ
        $quotationSuccess = quotationModel::where('quote_status', 'success')
            ->get()
            ->filter(function ($item) {
                // ลูกค้าต้องชำระเงินครบ (GetDeposit() >= quote_grand_total)
                $customerPaid = $item->GetDeposit()?? 0;
                $grandTotal = $item->quote_grand_total ?? 0;
                // โฮลเซลต้องชำระครบ (inputtaxTotalWholesale() - getWholesalePaidNet() == 0)
                $wholesaleOutstanding = 0;
                if (method_exists($item, 'inputtaxTotalWholesale') && method_exists($item, 'getWholesalePaidNet')) {
                    $wholesaleOutstanding = $item->inputtaxTotalWholesale() - $item->getWholesalePaidNet();
                }
                // ห้ามมีสถานะคืนเงินลูกค้าทุกแบบ
                $status = getQuoteStatusQuotePayment($item);
                $forbidden = [
                    'รอคืนเงินลูกค้า',
                    'ยังไม่ได้คืนเงินลูกค้า',
                ];
                foreach ($forbidden as $word) {
                    if (strpos($status, $word) !== false) {
                        return false;
                    }
                }
                // ห้ามมีสถานะโฮลเซลล์ที่ไม่อนุญาต
                $wholesaleStatus = getStatusPaymentWhosale($item);
                $forbiddenWholesale = [
                    'รอโฮลเซลล์คืนเงิน',
                    'โอนเงินให้โฮลเซลล์เกิน',
                    'รอชำระเงินมัดจำ',
                    'รอชำระเงินส่วนที่เหลือ',
                ];
                foreach ($forbiddenWholesale as $word) {
                    if (strpos($wholesaleStatus, $word) !== false) {
                        return false;
                    }
                }
                return ($customerPaid >= $grandTotal) && ($wholesaleOutstanding == 0);
            })
            ->values();
           

        // Filter เฉพาะใบเสนอราคาที่ค่าคอมตรงกับ mode ที่เลือก (qt หรือ total)
        if ($mode === 'total') {
            // Filter เฉพาะใบเสนอราคาที่ค่าคอมเป็น step-Total หรือ percent-Total
            $quotationSuccess = $quotationSuccess
                ->filter(function ($item) {
                    $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'total', $item->quote_pax_total);
                    return in_array($commission['type'], ['step-Total', 'percent-Total']);
                })
                ->values();
            // Group by sale_id
            $saleGroups = $quotationSuccess
                ->groupBy('quote_sale')
                ->map(function ($items, $saleId) {
                    $netProfitSum = $items->sum(function ($item) {
                        return $item->getNetProfit();
                    });
                    $paxSum = $items->sum('quote_pax_total');
                    return [
                        'sale_id' => $saleId,
                        'items' => $items,
                        'net_profit_sum' => $netProfitSum,
                        'pax_sum' => $paxSum,
                    ];
                })
                ->values();
            return view('reports.sales-form', compact('saleGroups', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
        } elseif ($mode === 'qt') {
            // รายใบเสนอราคา (qt)
            $quotationSuccess = $quotationSuccess
                ->filter(function ($item) use ($mode) {
                    $commission = calculateCommission($item->getNetProfit(), $item->quote_sale, 'qt', $item->quote_pax_total);
                    if ($mode === 'qt') {
                        return in_array($commission['type'], ['step-QT', 'percent-QT']);
                    }
                    return true;
                })
                ->values();
            return view('reports.sales-form', compact('quotationSuccess', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
        } else {
            // รายใบเสนอราคา (all) ไม่ต้อง filter ใดๆ แสดงทุก commission
            return view('reports.sales-form', compact('quotationSuccess', 'request', 'wholesales', 'country', 'sales', 'campaignSource', 'mode'));
        }
    }
}
