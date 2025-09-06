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
        // perPage guard - คุมจำนวนรายการต่อหน้าเมื่อมีการค้นหา
        $perPage = $request->integer('per_page', 50);
        
        if ($request->has('search_keyword') || $request->has('search_period_start') || $request->has('search_not_check_list') || $request->has('search_period_end') || $request->has('search_booking_start') || $request->has('search_booking_end')) {
            $perPage = min($perPage, 100); // จำกัดไม่เกิน 100 เมื่อมีการค้นหา
        }

        $searchKeyword = $request->input('search_keyword');
        $searchPeriodDateStart = $request->input('search_period_start');
        $searchPeriodDateEnd = $request->input('search_period_end');
        $searchQuoteDateStart = $request->input('search_booking_start'); 
        $searchQuoteDateEnd = $request->input('search_booking_end'); 
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

        // Aggregates เพื่อตัด N+1 queries
        $q->withSum(['quotePayments as paid_total' => function($p){
            $p->where('payment_status','!=','cancel');
        }], 'payment_total');

        $q->withSum(['quotePayments as refund_total' => function($p){
            $p->where('payment_status','!=','cancel')->where('payment_type','=','refund');
        }], 'payment_total');

        $q->withCount(['quotePayments as payments_count_non_refund' => function($p){
            $p->where('payment_status','!=','cancel')->where('payment_type','!=','refund');
        }]);

        $q->withExists(['quotePayments as has_refund_file' => function($p){
            $p->where('payment_status','!=','cancel')->where('payment_type','=','refund')->whereNotNull('payment_file_path');
        }]);

        $q->withExists(['InputTaxVat as has_input_tax_success' => function($t){
            $t->where('input_tax_type',0)->where('input_tax_status','success');
        }]);

        // Eager load relations + จำกัดคอลัมน์
        $q->with([
            'Salename:id,name',
            'quoteCustomer:customer_id,customer_name,customer_campaign_source',
            'quoteWholesale:id,wholesale_name',
            'quoteInvoice:id,invoice_quote_id,invoice_number,invoice_image',
            'quoteCheckStatus:id,quote_id,booking_email_status,invoice_status,slip_status,passport_status,appointment_status,withholding_tax_status,wholesale_tax_status',
            'airline:id,name',
            'quoteCountry:id,name',
            'paymentWholesale:payment_wholesale_id,payment_wholesale_quote_id,payment_wholesale_total,payment_wholesale_file_name,payment_wholesale_refund_status,payment_wholesale_refund_total',
            'checkfileInputtax:input_tax_id,input_tax_quote_id,input_tax_type,input_tax_status',
        ]);

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
        
        if ($userRoles->contains('sale')) {
            $q->where('quote_sale', $user->sale_id);
        }
        
        if ($searchPeriodDateStart && $searchPeriodDateEnd) {
            $q->where(function ($query) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                $query->whereBetween('quote_date_start', [$searchPeriodDateStart, $searchPeriodDateEnd])
                    ->orWhere(function ($q) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                        $q->where('quote_date_start', '<=', $searchPeriodDateStart)->where('quote_date_start', '>=', $searchPeriodDateEnd);
                    });
            });
        }
        
        if ($searchQuoteDateStart && $searchQuoteDateEnd) {
            $q->whereBetween('quote_date', [$searchQuoteDateStart, $searchQuoteDateEnd]);
        }
        
        if ($searchAirline && $searchAirline != 'all') {
            $q->where('quote_airline', $searchAirline);
        }
        
        if ($searchPax && $searchPax != null) {
            $q->where('quote_pax_total', $searchPax);
        }
        
        if ($searchLogStatus && $searchLogStatus === 'allCheck') {
            $q->whereHas('quoteLog', function ($q1) {
                $q1->where('booking_email_status', 'ส่งแล้ว');
                $q1->where('invoice_status', 'ได้แล้ว');
                $q1->where('slip_status', 'ส่งแล้ว');
                $q1->where('passport_status', 'ส่งแล้ว');
                $q1->where('appointment_status', 'ส่งแล้ว');
                $q1->where('withholding_tax_status', 'ออกแล้ว');
                $q1->where('wholesale_tax_status', 'ได้รับแล้ว');
            });
        } elseif ($searchLogStatus) {
            $q->whereHas('quoteLog', function ($q1) use ($searchLogStatus) {
                switch ($searchLogStatus) {
                    case 'booking_email_status':
                        $q1->where('booking_email_status', 'ส่งแล้ว');
                        break;
                    case 'invoice_status':
                        $q1->where('invoice_status', 'ได้แล้ว');
                        break;
                    case 'slip_status':
                        $q1->where('slip_status', 'ส่งแล้ว');
                        break;
                    case 'passport_status':
                        $q1->where('passport_status', 'ส่งแล้ว');
                        break;
                    case 'appointment_status':
                        $q1->where('appointment_status', 'ส่งแล้ว');
                        break;
                    case 'withholding_tax_status':
                        $q1->where('withholding_tax_status', 'ออกแล้ว');
                        break;
                    case 'wholesale_tax_status':
                        $q1->where('wholesale_tax_status', 'ได้รับแล้ว');
                        break;
                }
            });
        }
        
        if ($searchSale && $searchSale != 'all') {
            $q->where('quote_sale', $searchSale);
        }
        
        if ($searchCountry && $searchCountry != 'all') {
            $q->where('quote_country', $searchCountry);
        }
        
        if ($searchWholesale && $searchWholesale != 'all') {
            $q->where('quote_wholesale', $searchWholesale);
        }
        
        if ($request->input('search_campaign_source') && $request->input('search_campaign_source') != 'all') {
            $q->whereHas('quoteCustomer', function ($query) use ($request) {
                $query->where('customer_campaign_source', $request->input('search_campaign_source'));
            });
        }

        // Ordering + Paginate - ห้ามมี limit() ก่อน paginate
        $quotations = $q->orderByDesc('created_at')->paginate($perPage)->withQueryString();

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
        $SumPaymentTotal = $quotations->getCollection()->sum(function($quotation) {
            return (float)($quotation->paid_total ?? 0) - (float)($quotation->refund_total ?? 0);
        });
        
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
