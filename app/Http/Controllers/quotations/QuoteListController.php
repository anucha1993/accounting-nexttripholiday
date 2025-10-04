<?php

namespace App\Http\Controllers\quotations;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
// use App\Helpers\statusQuoteWithholdingTaxHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        set_time_limit(600); // 10 นาที
        ini_set('memory_limit', '1024M');
        
        $perPage = $request->input('per_page', 50);

        if ($request->has('search_keyword') || $request->has('search_period_start') || $request->has('search_not_check_list') || $request->has('search_period_end') || $request->has('search_booking_start') || $request->has('search_booking_end')) {
            $perPage = 2000;
        }

        $searchKeyword = $request->input('search_keyword');
        $searchPeriodDateStart = $request->input('search_period_start');
        $searchPeriodDateEnd = $request->input('search_period_end');
        $searchQuoteDateStart = $request->input('search_booking_start'); /// จากวันจอง เปลี่ยนเป็น วันเสนอราคา แทน
        $searchQuoteDateEnd = $request->input('search_booking_end'); /// จากวันจอง เปลี่ยนเป็น วันเสนอราคา แทน
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

        // dd($searchNotLogStatus);
        // dd($searchPaymentWholesaleStatus);

        $sales = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();
        $airlines = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $country = countryModel::get();
        $wholesales = wholesaleModel::get();
        $campaignSource = DB::table('campaign_source')->get();
        $user = Auth::user();
        $userRoles = $user->getRoleNames();


        $quotationsQuery = quotationModel::leftJoin('customer', 'customer.customer_id', '=', 'quotation.customer_id')
                           ->leftJoin('campaign_source', 'campaign_source.campaign_source_id', '=', 'customer.customer_campaign_source')
                           ->with([
            'Salename:id,name',
            'quoteCustomer' => function($query) {
                $query->select('customer_id', 'customer_name', 'customer_campaign_source');
            },
            'quoteWholesale:id,wholesale_name_th,code',
            'quoteInvoice',
            'quoteLogStatus',
            'airline:id,code,travel_name',
            'quoteCountry:id,country_name_th',
            'creditNote',
            'debitNote',
            'quotePayment',  // เพิ่มสำหรับ GetDeposit() และ Refund() methods
            'payment',  // เพิ่มสำหรับ GetDeposit() และ Refund() methods
            // Payments with optimized loading including sum calculations
            'quotePayments' => function($query) {
                $query->select([
                    'payments.payment_id',
                    'payments.payment_quote_id',
                    'payments.payment_total',
                    'payments.payment_type',
                    'payments.payment_status',
                    'payments.payment_file_path',
                    DB::raw('SUM(CASE WHEN payment_status != "cancel" THEN payment_total ELSE 0 END) as total_payments')
                ])
                ->where(function($q) {
                    $q->where('payment_status', '!=', 'cancel')
                      ->where(function($sq) {
                          $sq->where('payment_type', '!=', 'refund')
                            ->orWhere(function($ssq) {
                                $ssq->where('payment_type', 'refund')
                                   ->whereNotNull('payment_file_path');
                            });
                      });
                })
                ->groupBy([
                    'payments.payment_id',
                    'payments.payment_quote_id',
                    'payments.payment_total',
                    'payments.payment_type',
                    'payments.payment_status',
                    'payments.payment_file_path'
                ]);
            },
            // Payment Wholesale with optimized loading and eager loading
            'paymentWholesale' => function($query) {
                $query->select([
                    'payment_wholesale_id',
                    'payment_wholesale_quote_id',
                    'payment_wholesale_file_name',
                    'payment_wholesale_refund_status',
                    'payment_wholesale_total',
                    'payment_wholesale_refund_total'
                ])
                ->where(function($q) {
                    $q->where('payment_wholesale_file_name', '!=', '')
                      ->orWhere('payment_wholesale_refund_status', 'success')
                      ->orWhere('payment_wholesale_refund_status', '!=', 'success');
                })
                ->groupBy([
                    'payment_wholesale_id',
                    'payment_wholesale_quote_id',
                    'payment_wholesale_file_name',
                    'payment_wholesale_refund_status',
                    'payment_wholesale_total',
                    'payment_wholesale_refund_total'
                ]);
            },
            // Input Tax with optimized loading
            'InputTaxVat' => function($query) {
                $query->select([
                    'input_tax_id',
                    'input_tax_quote_id', 
                    'input_tax_quote_number',
                    'input_tax_type',
                    'input_tax_status',
                    'input_tax_file',
                    'input_tax_grand_total',
                    'input_tax_withholding',
                    'input_tax_vat'
                ])
                ->where('input_tax_status', 'success')
                ->whereIn('input_tax_type', [2, 4, 5, 6, 7])
                ->groupBy([
                    'input_tax_id',
                    'input_tax_quote_id', 
                    'input_tax_quote_number',
                    'input_tax_type',
                    'input_tax_status',
                    'input_tax_file',
                    'input_tax_grand_total',
                    'input_tax_withholding',
                    'input_tax_vat'
                ]);
            },
            // Quote Log Status with optimized loading
            'quoteLog' => function($query) {
                $query->select(
                    'quote_id',
                    'booking_email_status',
                    'invoice_status', 
                    'slip_status',
                    'passport_status',
                    'appointment_status',
                    'withholding_tax_status',
                    'wholesale_tax_status'
                );
            },
            'checkfileInputtax'
        ])->select([
            'quotation.quote_id',
            'quotation.quote_number',
            'quotation.quote_date',
            'quotation.quote_date_start',
            'quotation.quote_sale',
            'quotation.quote_wholesale',
            'quotation.quote_country',
            'quotation.quote_airline',
            'quotation.quote_pax_total',
            'quotation.quote_grand_total',
            'quotation.quote_tour_name',
            'quotation.quote_tour_name1',
            'quotation.quote_booking',
            'quotation.quote_status',  // เพิ่มฟิลด์สำคัญสำหรับ Helper
            'quotation.quote_commission',  // เพิ่มฟิลด์ commission
            'quotation.quote_payment_type',  // สำหรับ statusPaymentHelper
            'quotation.quote_payment_date',  // สำหรับ statusPaymentHelper
            'quotation.quote_payment_date_full',  // สำหรับ statusPaymentHelper
            'quotation.created_at',  // เพิ่มฟิลด์ created_at สำหรับ orderBy
            'customer.customer_name',
            'customer.customer_campaign_source',
            'campaign_source.campaign_source_name'
        ]);

        if ($searchKeyword) {
            $quotationsQuery = $quotationsQuery->where(function ($q) use ($searchKeyword) {
                $q->whereHas('quoteCustomer', function ($q1) use ($searchKeyword) {
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
            $quotationsQuery = $quotationsQuery->where('quote_sale', $user->sale_id);
        }
        if ($searchPeriodDateStart && $searchPeriodDateEnd) {
            $quotationsQuery = $quotationsQuery->where(function ($q) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                $q->whereBetween('quote_date_start', [$searchPeriodDateStart, $searchPeriodDateEnd])
                    ->orWhere(function ($q) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                        $q->where('quote_date_start', '<=', $searchPeriodDateStart)->where('quote_date_start', '>=', $searchPeriodDateEnd);
                    });
            });
        }
        if ($searchQuoteDateStart && $searchQuoteDateEnd) {
            $quotationsQuery = $quotationsQuery->whereBetween('quote_date', [$searchQuoteDateStart, $searchQuoteDateEnd]);
        }
        if ($searchAirline && $searchAirline != 'all') {
            $quotationsQuery = $quotationsQuery->where('quote_airline', $searchAirline);
        }
        if ($searchPax && $searchPax != null) {
            $quotationsQuery = $quotationsQuery->where('quote_pax_total', $searchPax);
        }
        if ($searchLogStatus && $searchLogStatus === 'allCheck') {
            $quotationsQuery = $quotationsQuery->whereHas('quoteLog', function ($q1) {
                $q1->where('booking_email_status', 'ส่งแล้ว');
                $q1->where('invoice_status', 'ได้แล้ว');
                $q1->where('slip_status', 'ส่งแล้ว');
                $q1->where('passport_status', 'ส่งแล้ว');
                $q1->where('appointment_status', 'ส่งแล้ว');
                $q1->where('withholding_tax_status', 'ออกแล้ว');
                $q1->where('wholesale_tax_status', 'ได้รับแล้ว');
            });
        } elseif ($searchLogStatus) {
            $quotationsQuery = $quotationsQuery->whereHas('quoteLog', function ($q1) use ($searchLogStatus) {
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
            $quotationsQuery = $quotationsQuery->where('quote_sale', $searchSale);
        }
        if ($searchCountry && $searchCountry != 'all') {
            $quotationsQuery = $quotationsQuery->where('quote_country', $searchCountry);
        }
        if ($searchWholesale && $searchWholesale != 'all') {
            $quotationsQuery = $quotationsQuery->where('quote_wholesale', $searchWholesale);
        }
        if ($request->input('search_campaign_source') && $request->input('search_campaign_source') != 'all') {
            $quotationsQuery = $quotationsQuery->whereHas('quoteCustomer', function ($q) use ($request) {
                $q->where('customer_campaign_source', $request->input('search_campaign_source'));
            });
        }

    
        $quotationsQuery = $quotationsQuery->orderBy('quotation.created_at', 'desc');

        // ดึง status ทั้งหมดของ getQuoteStatusQuotePayment และ getStatusWithholdingTax (ก่อน paginate/filter)
        $allQuoteStatusQuotePayment = $quotationsQuery
            ->get()
            ->flatMap(function ($item) {
                return [
                    strip_tags(getQuoteStatusQuotePayment($item)),
                    strip_tags(getStatusWithholdingTax($item->quoteInvoice)),
                    strip_tags(getQuoteStatusWithholdingTax($item->quoteLogStatus)),
                    strip_tags(\getStatusWhosaleInputTax($item->checkfileInputtax)),
                    // เพิ่ม helper อื่นๆ ได้ที่นี่
                ];
            })
            ->unique()
            ->filter()
            ->values();

        $queryString = $quotationsQuery->toSql();
        $queryBindings = $quotationsQuery->getBindings();

        $quotations = $quotationsQuery->paginate($perPage)->withQueryString();

          // Filter เงื่อนไขสถานะชำระโฮลเซลล์ (searchPaymentWholesaleStatus) ด้วย where
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


        // เฉพาะ filter ที่ต้องใช้ helper หรือ logic ซับซ้อนมาก ให้ filter ใน PHP หลัง paginate
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
          if (!empty($searchNotLogStatus) && $searchNotLogStatus == 'ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์') {
            $filtered = $quotations
                ->getCollection()
                ->filter(function ($quotation) use ($searchNotLogStatus) {
                
                    $statusText = trim(strip_tags(getStatusWhosaleInputTax($quotation->checkfileInputtax)));
                    $badgeList = preg_split('/\s{2,}|(?<=\S) (?=\S)/u', $statusText);
                    return in_array('รอใบกำกับภาษีโฮลเซลล์', array_map('trim', $badgeList));
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

        $SumPax = $quotations->sum('quote_pax_total');
        $SumTotal = $quotations->sum('quote_grand_total');

        $SumPaymentTotal = $quotations->getCollection()->sum(function($quotation) {
    // แปลงผลลัพธ์เป็นตัวเลขก่อนนำมาคำนวณ
    $deposit = is_numeric($quotation->GetDeposit()) ? $quotation->GetDeposit() : 0;
    $refund = is_numeric($quotation->Refund()) ? $quotation->Refund() : 0;
    return $deposit - $refund;
});
        

        $customerPaymentStatuses = ['รอคืนเงิน', 'ยกเลิกการสั่งซื้อ', 'ชำระเงินครบแล้ว', 'ชำระเงินเกิน', 'เกินกำหนดชำระเงิน', 'รอชำระเงินเต็มจำนวน', 'รอชำระเงินมัดจำ', 'คืนเงินแล้ว'];

        return view('quotations.list', compact('SumTotal', 'SumPaymentTotal', 'SumPax', 'airlines', 'sales', 'wholesales', 'quotations', 'country', 'request', 'customerPaymentStatuses', 'campaignSource', 'allQuoteStatusQuotePayment', 'queryString', 'queryBindings'));
    }

    public function destroy($id)
    {
        $quotation = \App\Models\quotations\quotationModel::findOrFail($id);
        // สามารถเพิ่ม logic ตรวจสอบสิทธิ์/soft delete/ลบข้อมูลที่เกี่ยวข้องได้ที่นี่
        $quotation->delete();
        return redirect()->route('quotelist.index')->with('success', 'ลบใบเสนอราคาเรียบร้อยแล้ว');
    }
}
