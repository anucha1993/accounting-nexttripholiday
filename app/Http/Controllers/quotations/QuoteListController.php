<?php

namespace App\Http\Controllers\quotations;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
// use App\Helpers\statusQuoteWithholdingTaxHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\booking\countryModel;
use App\Models\inputTax\inputTaxModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\payments\paymentWholesaleModel;

class QuoteListController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-quote', ['only' => ['index']]);
        $this->middleware('permission:create-quote', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-quote', ['only' => ['edit', 'update', 'cancel']]);
        $this->middleware('permission:delete-quote', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50);
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

        $sales = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();
        $airlines = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $country = countryModel::get();
        $wholesales = wholesaleModel::get();
        $campaignSource = DB::table('campaign_source')->get();

        $quotationsQuery = quotationModel::with('Salename', 'quoteCustomer', 'quoteWholesale', 'paymentWholesale', 'quoteInvoice', 'quoteLogStatus', 'airline', 'quoteCountry')
            ->when($searchKeyword, function ($query, $searchKeyword) {
                return $query->where(function ($q) use ($searchKeyword) {
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
            })
            ->when($searchPeriodDateStart && $searchPeriodDateEnd, function ($query) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                return $query->where(function ($q) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                    $q->whereBetween('quote_date_start', [$searchPeriodDateStart, $searchPeriodDateEnd])
                        ->orWhereBetween('quote_date_end', [$searchPeriodDateStart, $searchPeriodDateEnd])
                        ->orWhere(function ($q) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                            $q->where('quote_date_start', '<=', $searchPeriodDateStart)->where('quote_date_end', '>=', $searchPeriodDateEnd);
                        });
                });
            })
            ->when($searchQuoteDateStart && $searchQuoteDateEnd, function ($query) use ($searchQuoteDateStart, $searchQuoteDateEnd) {
                return $query->whereBetween('quote_booking_create', [$searchQuoteDateStart, $searchQuoteDateEnd]);
            })
            ->when($searchAirline && $searchAirline != 'all', function ($query) use ($searchAirline) {
                return $query->where('quote_airline', $searchAirline);
            })
            ->when($searchPax && $searchPax != null, function ($query) use ($searchPax) {
                return $query->where('quote_pax_total', $searchPax);
            })

            ->when($searchLogStatus && $searchLogStatus === 'allCheck', function ($query, $searchLogStatus) {
                return $query->whereHas('quoteLog', function ($q1) use ($searchLogStatus) {
                    $q1->where('booking_email_status', 'ส่งแล้ว');
                    $q1->where('invoice_status', 'ได้แล้ว');
                    $q1->where('slip_status', 'ส่งแล้ว');
                    $q1->where('passport_status', 'ส่งแล้ว');
                    $q1->where('appointment_status', 'ส่งแล้ว');
                    $q1->where('withholding_tax_status', 'ออกแล้ว');
                    $q1->where('wholesale_tax_status', 'ได้รับแล้ว');
                });
            })

            ->when($searchLogStatus, function ($query, $searchLogStatus) {
                return $query->whereHas('quoteLog', function ($q1) use ($searchLogStatus) {
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
            })

            // ไม่ filter ที่ SQL สำหรับ Check List ให้ filter หลัง paginate เท่านั้น เพื่อความตรงกับ badge ที่แสดงจริง
            ->when($searchSale && $searchSale != 'all', function ($query) use ($searchSale) {
                return $query->where('quote_sale', $searchSale);
            })
            ->when($searchCountry && $searchCountry != 'all', function ($query) use ($searchCountry) {
                return $query->where('quote_country', $searchCountry);
            })
            ->when($searchWholesale && $searchWholesale != 'all', function ($query) use ($searchWholesale) {
                return $query->where('quote_wholesale', $searchWholesale);
            })
            ->when($request->input('search_campaign_source') && $request->input('search_campaign_source') != 'all', function ($query) use ($request) {
                return $query->whereHas('quoteCustomer', function ($q) use ($request) {
                    $q->where('customer_campaign_source', $request->input('search_campaign_source'));
                });
            })

            ->orderBy('created_at', 'desc');

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
        // Filter สถานะชำระโฮลเซลล์
        if (!empty($searchPaymentWholesaleStatus) && $searchPaymentWholesaleStatus !== 'all') {
            $filtered = $quotations
                ->getCollection()
                ->filter(function ($quotation) use ($searchPaymentWholesaleStatus) {
                    // ดึงยอดชำระโฮลเซลล์
                    $paymentQuery = paymentWholesaleModel::where('payment_wholesale_quote_id', $quotation->quote_id);
                    $netPaid = $paymentQuery->selectRaw('COALESCE(SUM(payment_wholesale_total),0) - COALESCE(SUM(payment_wholesale_refund_total),0) as net_paid')->value('net_paid') ?? 0;
                    // ตรวจสอบไฟล์แนบสลิป paid
                    $hasPaidSlip = $paymentQuery->whereNotNull('payment_wholesale_file_path')->where('payment_wholesale_file_path', '!=', '')->exists();
                    // ตรวจสอบไฟล์แนบ refund ถ้ามียอดคืนเงิน
                    $hasRefundSlip = true;
                    $refundQuery = paymentWholesaleModel::where('payment_wholesale_quote_id', $quotation->quote_id)->where('payment_wholesale_refund_total', '>', 0);
                    if ($refundQuery->exists()) {
                        $hasRefundSlip = $refundQuery->whereNotNull('payment_wholesale_refund_file_path')->where('payment_wholesale_refund_file_path', '!=', '')->exists();
                    }
                    // ดึงต้นทุนโฮลเซลล์
                    $cost = inputTaxModel::where('input_tax_quote_id', $quotation->quote_id)
                        ->whereIn('input_tax_type', [2, 4, 5, 6, 7])
                        ->sum('input_tax_grand_total');
                    if ($searchPaymentWholesaleStatus == '0') {
                        return $netPaid == 0;
                    } elseif ($searchPaymentWholesaleStatus == '1') {
                        return $netPaid > 0 && $netPaid < $cost && $hasPaidSlip && $hasRefundSlip;
                    } elseif ($searchPaymentWholesaleStatus == '2') {
                        return $netPaid >= $cost && $cost > 0 && $hasPaidSlip && $hasRefundSlip;
                    }
                    return true;
                })
                ->values();
            $quotations->setCollection($filtered);
        }

        // Filter ด้วย getQuoteStatusPaymentKey (Helper) หลัง paginate เฉพาะกรณีเลือกสถานะ (เพื่อให้ตรงกับ Blade)
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

        // Filter ด้วย getQuoteStatusPaymentKey (Helper) หลัง paginate เฉพาะกรณีเลือกสถานะ (เพื่อให้ตรงกับ Blade)
        if (!empty($searchNotLogStatus) && $searchNotLogStatus !== 'all') {
            $filtered = $quotations->getCollection()->filter(function ($quotation) use ($searchNotLogStatus) {
                $statusText = trim(strip_tags(getStatusBadge($quotation->quoteCheckStatus)));
                // แยก badge ด้วยช่องว่าง 1 ตัวขึ้นไป (รองรับ badge หลายอัน)
                $badgeList = preg_split('/\s{2,}|(?<=\S) (?=\S)/u', $statusText);
                return in_array($searchNotLogStatus, array_map('trim', $badgeList));
            })->values();
            $quotations->setCollection($filtered);
        }

        // Filter สถานะลูกค้าชำระเงินเกิน
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

        // Filter สถานะชำระเงินโฮลเซลเกิน
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

        // Filter ด้วย getQuoteStatusQuotePayment และ getStatusWithholdingTax หลัง paginate เฉพาะกรณีเลือกสถานะ (CheckList ให้ตรงกับ badge จริง)
        // $quotations = $this->filterByCheckListStatus($quotations, $searchNotLogStatus);

        $SumPax = $quotations->sum('quote_pax_total');
        $SumTotal = $quotations->sum('quote_grand_total');

        $customerPaymentStatuses = ['รอคืนเงิน', 'ยกเลิกการสั่งซื้อ', 'ชำระเงินครบแล้ว', 'ชำระเงินเกิน', 'เกินกำหนดชำระเงิน', 'รอชำระเงินเต็มจำนวน', 'รอชำระเงินมัดจำ', 'คืนเงินแล้ว'];

        // ส่ง $allQuoteStatusQuotePayment ไปที่ view
        return view('quotations.list', compact('SumTotal', 'SumPax', 'airlines', 'sales', 'wholesales', 'quotations', 'country', 'request', 'customerPaymentStatuses', 'campaignSource', 'allQuoteStatusQuotePayment', 'queryString', 'queryBindings'));
    }

    /**
     * Filter quotations collection by all badge logic (CheckList)
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $quotations
     * @param string $searchNotLogStatus
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    // protected function filterByCheckListStatus($quotations, $searchNotLogStatus)
    // {
    //     if (empty($searchNotLogStatus) || $searchNotLogStatus === 'all') {
    //         return $quotations;
    //     }
    //     $filtered = $quotations->getCollection()->filter(function ($quotation) use ($searchNotLogStatus) {
    //         $badges = [
    //             trim(strip_tags(getQuoteStatusQuotePayment($quotation))),
    //             trim(strip_tags(getStatusWithholdingTax($quotation->quoteInvoice))),
    //             trim(strip_tags(getQuoteStatusWithholdingTax($quotation->quoteLogStatus))),
    //             trim(strip_tags(getStatusWhosaleInputTax($quotation->checkfileInputtax))),
    //             trim(strip_tags(getStatusCustomerRefund($quotation->quoteLogStatus))),
    //             trim(strip_tags(getStatusWholesaleRefund($quotation->quoteLogStatus))),
    //             // เพิ่ม helper อื่นๆ ได้ที่นี่
    //         ];
    //         $search = trim($searchNotLogStatus);
    //         Log::debug('CheckList filter', ['badges' => $badges, 'search' => $search, 'quote_id' => $quotation->quote_id]);
    //         return in_array($search, $badges);
    //     })->values();
    //     $quotations->setCollection($filtered);
    //     return $quotations;
    // }

    public function destroy($id)
    {
        $quotation = \App\Models\quotations\quotationModel::findOrFail($id);
        // สามารถเพิ่ม logic ตรวจสอบสิทธิ์/soft delete/ลบข้อมูลที่เกี่ยวข้องได้ที่นี่
        $quotation->delete();
        return redirect()->route('quotelist.index')->with('success', 'ลบใบเสนอราคาเรียบร้อยแล้ว');
    }
}
