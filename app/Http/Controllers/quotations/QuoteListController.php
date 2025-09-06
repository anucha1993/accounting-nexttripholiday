<?php

namespace App\Http\Controllers\quotations;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
// use App\Helpers\statusQuoteWithholdingTaxHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\booking\countryModel;
use Illuminate\Support\Facades\Auth;
use App\Models\inputTax\inputTaxModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\payments\paymentWholesaleModel;

class QuoteListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
       //DB::enableQueryLog();
        // // perPage guard - คุมจำนวนรายการต่อหน้าเมื่อมีการค้นหา
        $perPage = $request->integer('per_page', 50);
        
        if ($request->has('search_keyword') || $request->has('search_period_start') || $request->has('search_not_check_list') || $request->has('search_period_end') || $request->has('search_booking_start') || $request->has('search_booking_end')) {
            $perPage = min($perPage, 100); // จำกัดไม่เกิน 100 เมื่อมีการค้นหา
        }
        
        $searchKeyword = $request->input('search_keyword');
        $searchPeriodDateStart = $request->input('search_period_start');
        $searchPeriodDateEnd = $request->input('search_period_end');
        $searchQuoteDateStart = $request->input('search_booking_start'); 
        $searchQuoteDateEnd = $request->input('search_booking_end'); 
        $searchDateStart = $request->input('search_date_start');
        $searchDateEnd = $request->input('search_date_end');
        $searchTourDateStart = $request->input('search_tour_date_start');
        $searchTourDateEnd = $request->input('search_tour_date_end');
        $searchQuoteStatus = $request->input('search_quote_status', 'all');
        $searchCampaignSource = $request->input('search_campaign_source', 'all');
        $searchSale = $request->input('search_sale');
        $searchCountry = $request->input('search_country');
        $searchWholesale = $request->input('search_wholesale');
        $searchAirline = $request->input('search_airline');
        $searchPax = $request->input('search_pax');
        $searchLogStatus = $request->input('search_check_list');
        $searchNotLogStatus = $request->input('search_not_check_list');
        $searchPaymentWholesaleStatus = $request->input('search_wholesale_payment');
        $searchCustomerPayment = $request->input('search_customer_payment', 'all');
        $searchPaymentOverpays = $request->input('search_payment_overpays', 'all');
        $searchPaymentWholesaleOverpays = $request->input('search_payment_wholesale_overpays', 'all');

        // Lookup data - เพิ่ม Cache เพื่อลดการคิวรี่ข้อมูลที่ไม่เปลี่ยนแปลงบ่อย
        $sales = Cache::remember('sales_list', 600, function() {
            return saleModel::select('name', 'id')
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        });
        
        $airlines = Cache::remember('airlines_list', 600, function() {
            return DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        });
        
        $country = Cache::remember('country_list', 600, function() {
            return countryModel::get();
        });
        
        $wholesales = Cache::remember('wholesales_list', 600, function() {
            return wholesaleModel::get();
        });
        
        $campaignSource = Cache::remember('campaign_source_list', 600, function() {
            return DB::table('campaign_source')->get();
        });
        
        $user = Auth::user();
        $userRoles = $user->getRoleNames();

        // Base query - เลือกเฉพาะคอลัมน์ที่จำเป็น
        $baseSelect = [
            'quote_id','quote_number','quote_tour_name','quote_tour_name1',
            'quote_booking','quote_sale','quote_country','quote_airline',
            'quote_wholesale','quote_pax_total','quote_grand_total',
            'quote_date','quote_date_start','created_at','customer_id'
        ];
        
        $q = quotationModel::query()->select($baseSelect);

        // Aggregates ให้ comment ออกก่อนเพื่อทดสอบ
        /*
        $q->withSum(['quotePayments as paid_total' => function($p){
            $p->where('payment_status','!=','cancel');
        }], 'payment_total');

        $q->withSum(['quotePayments as refund_total' => function($p){
            $p->where('payment_status','!=','cancel')->where('payment_type','=','refund');
        }], 'payment_total');

        $q->withCount(['quotePayments as payments_count_non_refund' => function($p){
            $p->where('payment_status','!=','cancel')->where('payment_type','!=','refund');
        }]);
        */

        $q->withExists(['quotePayments as has_refund_file' => function($p){
            $p->where('payment_status','!=','cancel')->where('payment_type','=','refund')->whereNotNull('payment_file_path');
        }]);

        $q->withExists(['InputTaxVat as has_input_tax_success' => function($t){
            $t->where('input_tax_type',0)->where('input_tax_status','success');
        }]);

        // Filters - คงเงื่อนไขค้นหาเดิมทั้งหมด
        if ($searchKeyword) {
            $q->where(function ($query) use ($searchKeyword) {
                $query->whereHas('quoteCustomer', function ($q1) use ($searchKeyword) {
                    $q1->where('customer_name', 'LIKE', '%' . $searchKeyword . '%');
                })
                    ->orWhere('quote_number', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('quote_tour_name', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('quote_tour_name1', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('quote_booking', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhereHas('quoteInvoice', function ($q2) use ($searchKeyword) {
                        $q2->where('invoice_number', 'LIKE', '%' . $searchKeyword . '%');
                    });
            });
        }

        // Date filters
        if ($searchDateStart) {
            $q->where('quote_date', '>=', $searchDateStart);
        }

        if ($searchDateEnd) {
            $q->where('quote_date', '<=', $searchDateEnd);
        }

        if ($searchTourDateStart) {
            $q->where('quote_date_start', '>=', $searchTourDateStart);
        }

        if ($searchTourDateEnd) {
            $q->where('quote_date_start', '<=', $searchTourDateEnd);
        }

        // Status filters
        if ($searchQuoteStatus !== 'all' && !empty($searchQuoteStatus)) {
            $q->where('quote_status', $searchQuoteStatus);
        }

        if ($searchCountry !== 'all' && !empty($searchCountry)) {
            $q->where('quote_country', $searchCountry);
        }

        if ($searchSale !== 'all' && !empty($searchSale)) {
            $q->where('quote_sale', $searchSale);
        }

        if ($searchAirline !== 'all' && !empty($searchAirline)) {
            $q->where('quote_airline', $searchAirline);
        }

        if ($searchWholesale !== 'all' && !empty($searchWholesale)) {
            $q->where('quote_wholesale', $searchWholesale);
        }

        if ($searchPaymentWholesaleStatus !== 'all' && !empty($searchPaymentWholesaleStatus)) {
            if ($searchPaymentWholesaleStatus === 'รอชำระมัดจำโฮลเซลล์') {
                $q->where('wholesale_payment_status', 'deposit');
            } elseif ($searchPaymentWholesaleStatus === 'รอชำระเงินส่วนที่เหลือ') {
                $q->where('wholesale_payment_status', 'remaining');
            } elseif ($searchPaymentWholesaleStatus === 'ชำระเงินครบแล้ว') {
                $q->where('wholesale_payment_status', 'success');
            }
        }

        if ($searchCampaignSource !== 'all' && !empty($searchCampaignSource)) {
            $q->whereHas('quoteCustomer', function ($q1) use ($searchCampaignSource) {
                $q1->where('customer_campaign_source', $searchCampaignSource);
            });
        }

        // Load quotations with eager loading
        $q->with([
            'Salename:id,name',
            'quoteCustomer:customer_id,customer_name,customer_campaign_source',
            'quoteWholesale:id,wholesale_name_th,code',
            'quoteInvoice:invoice_id,invoice_quote_id,invoice_number,invoice_image',
            'quoteCheckStatus:id,quote_id,booking_email_status,invoice_status,slip_status,passport_status,appointment_status,withholding_tax_status,wholesale_tax_status',
            'airline:id,code',
            'quoteCountry:id,country_name_th',
            'paymentWholesale:payment_wholesale_id,payment_wholesale_quote_id,payment_wholesale_total,payment_wholesale_file_name,payment_wholesale_refund_status,payment_wholesale_refund_total',
            'checkfileInputtax:input_tax_id,input_tax_quote_id,input_tax_type,input_tax_status',
            // เพิ่ม quotePayments และ quotePayment (singular) เพื่อใช้ใน helper function
            'quotePayments:payment_id,payment_quote_id,payment_type,payment_total,payment_status,payment_file_path',
            'quotePayment:payment_id,payment_quote_id,payment_type,payment_total,payment_status,payment_file_path'
        ]);

        $quotations = $q->orderBy('quote_id', 'desc')->paginate(50);

        // ดึง status ทั้งหมดของ getQuoteStatusQuotePayment และ getStatusWithholdingTax
        $allQuoteStatusQuotePayment = $quotations
            ->getCollection()
            ->flatMap(function ($item) {
                return [
                    strip_tags(getQuoteStatusQuotePayment($item)),
                    strip_tags(getStatusWithholdingTax($item->quoteInvoice)),
                    strip_tags(getQuoteStatusWithholdingTax($item->quoteCheckStatus)),
                    strip_tags(\getStatusWhosaleInputTax($item->checkfileInputtax)),
                ];
            })
            ->unique()
            ->filter()
            ->values();

        // Post-filter เฉพาะเงื่อนไขที่ซับซ้อน
        if (!empty($searchPaymentWholesaleStatus) && $searchPaymentWholesaleStatus !== 'all') {
            $filtered = $quotations
                ->getCollection()
                ->filter(function ($quotation) use ($searchPaymentWholesaleStatus) {
                    $statusKey = trim(strip_tags(getStatusPaymentWhosale($quotation)));
                    return $statusKey == $searchPaymentWholesaleStatus;
                })
                ->values();
            $quotations->setCollection($filtered);
        }

        if (!empty($searchCustomerPayment) && $searchCustomerPayment !== 'all') {
            $filtered = $quotations
                ->getCollection()
                ->filter(function ($quotation) use ($searchCustomerPayment) {
                    $statusKey = trim(strip_tags(getQuoteStatusPayment($quotation)));
                    return $statusKey == $searchCustomerPayment;
                })
                ->values();
            $quotations->setCollection($filtered);
        }
        
        if (!empty($searchNotLogStatus) && $searchNotLogStatus !== 'all') {
            $filtered = $quotations
                ->getCollection()
                ->filter(function ($quotation) use ($searchNotLogStatus) {
                    $statusText = trim(strip_tags(getStatusBadge($quotation->quoteCheckStatus, $quotation)));
                    $badgeList = preg_split('/\s{2,}|(?<=\S) (?=\S)/u', $statusText);
                    return in_array($searchNotLogStatus, array_map('trim', $badgeList));
                })
                ->values();
            $quotations->setCollection($filtered);
        }
        
        if (!empty($searchPaymentOverpays) && $searchPaymentOverpays !== 'all') {
            $filtered = $quotations
                ->getCollection()
                ->filter(function ($quotation) use ($searchPaymentOverpays) {
                    if ($searchPaymentOverpays === 'รอใบหัก จากลูกค้า') {
                        $statusText = trim(strip_tags(getStatusWithholdingTax($quotation->quoteInvoice)));
                    } else {
                        $statusText = trim(strip_tags(getQuoteStatusQuotePayment($quotation)));
                    }
                    return $statusText == $searchPaymentOverpays;
                })
                ->values();
            $quotations->setCollection($filtered);
        }

        if (!empty($searchPaymentWholesaleOverpays) && $searchPaymentWholesaleOverpays !== 'all') {
            $filtered = $quotations
                ->getCollection()
                ->filter(function ($quotation) use ($searchPaymentWholesaleOverpays) {
                    $statusText = trim(strip_tags(getStatusPaymentWhosale($quotation)));
                    return $statusText == $searchPaymentWholesaleOverpays;
                })
                ->values();
            $quotations->setCollection($filtered);
        }

        // Summaries - คำนวณจาก collection ที่ดึงมาแล้ว
        $SumPax = $quotations->getCollection()->sum('quote_pax_total');
        $SumTotal = $quotations->getCollection()->sum('quote_grand_total');
        
        // คำนวณ payment total แยกด้วย single query
        $quoteIds = $quotations->getCollection()->pluck('quote_id')->toArray();
        $paymentTotals = \App\Models\payments\paymentModel::whereIn('payment_quote_id', $quoteIds)
            ->where('payment_status', '!=', 'cancel')
            ->selectRaw('payment_quote_id, 
                SUM(CASE WHEN payment_type = "refund" THEN payment_total ELSE 0 END) as refund_total,
                SUM(CASE WHEN payment_type != "refund" THEN payment_total ELSE 0 END) as paid_total')
            ->groupBy('payment_quote_id')
            ->get()
            ->keyBy('payment_quote_id');
            
        $SumPaymentTotal = $quotations->getCollection()->sum(function($quotation) use ($paymentTotals) {
            $payment = $paymentTotals[$quotation->quote_id] ?? null;
            $paidTotal = $payment ? (float)$payment->paid_total : 0;
            $refundTotal = $payment ? (float)$payment->refund_total : 0;
            return $paidTotal - $refundTotal;
        });
       //logger(DB::getQueryLog());
        $customerPaymentStatuses = ['รอคืนเงิน', 'ยกเลิกการสั่งซื้อ', 'ชำระเงินครบแล้ว', 'ชำระเงินเกิน', 'เกินกำหนดชำระเงิน', 'รอชำระเงินเต็มจำนวน', 'รอชำระเงินมัดจำ', 'คืนเงินแล้ว'];

        return view('quotations.list', compact('SumTotal', 'SumPaymentTotal', 'SumPax', 'airlines', 'sales', 'wholesales', 'quotations', 'country', 'request', 'customerPaymentStatuses', 'campaignSource', 'allQuoteStatusQuotePayment'));
    }

    public function destroy($id)
    {
        $quotation = \App\Models\quotations\quotationModel::findOrFail($id);
        // สามารถเพิ่ม logic ตรวจสอบสิทธิ์/soft delete/ลบข้อมูลที่เกี่ยวข้องได้ที่นี่
        $quotation->delete();
        return redirect()->route('quotelist.index')->with('success', 'ลบใบเสนอราคาเรียบร้อยแล้ว');
    }
}
