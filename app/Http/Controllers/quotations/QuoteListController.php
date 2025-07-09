<?php

namespace App\Http\Controllers\quotations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\quotations\quotationModel;
use App\Models\sales\saleModel;
use App\Models\booking\countryModel;
use App\Models\wholesale\wholesaleModel;
use Illuminate\Support\Facades\DB;

class QuoteListController extends Controller
{
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
                return $query->whereBetween('quote_date', [$searchQuoteDateStart, $searchQuoteDateEnd]);
            })
            ->when($searchAirline && $searchAirline != 'all', function ($query) use ($searchAirline) {
                return $query->where('quote_airline', $searchAirline);
            })
            ->when($searchPax && $searchPax != null, function ($query) use ($searchPax) {
                return $query->where('quote_pax_total', $searchPax);
            })
            ->when($searchLogStatus && $searchLogStatus === 'allCheck', function ($query, $searchLogStatus) {
                return $query->whereHas('quoteLogStatus', function ($q1) use ($searchLogStatus) {
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
                return $query->whereHas('quoteLogStatus', function ($q1) use ($searchLogStatus) {
                    switch ($searchLogStatus) {
                        case 'booking_email_status': $q1->where('booking_email_status', 'ส่งแล้ว'); break;
                        case 'invoice_status': $q1->where('invoice_status', 'ได้แล้ว'); break;
                        case 'slip_status': $q1->where('slip_status', 'ส่งแล้ว'); break;
                        case 'passport_status': $q1->where('passport_status', 'ส่งแล้ว'); break;
                        case 'appointment_status': $q1->where('appointment_status', 'ส่งแล้ว'); break;
                        case 'withholding_tax_status': $q1->where('withholding_tax_status', 'ออกแล้ว'); break;
                        case 'wholesale_tax_status': $q1->where('wholesale_tax_status', 'ได้รับแล้ว'); break;
                    }
                });
            })
            ->when($searchNotLogStatus, function ($query, $searchNotLogStatus) {
                return $query->whereHas('quoteLogStatus', function ($q1) use ($searchNotLogStatus) {
                    switch ($searchNotLogStatus) {
                        case 'booking_email_status': $q1->where('booking_email_status', 'ยังไม่ได้ส่ง')->orWhereNull('booking_email_status'); break;
                        case 'invoice_status': $q1->where('invoice_status','ยังไม่ได้')->orWhereNull('invoice_status'); break;
                        case 'slip_status': $q1->where('slip_status','ยังไม่ได้ส่ง')->orWhereNull('slip_status'); break;
                        case 'passport_status': $q1->where('passport_status','ยังไม่ได้ส่ง')->orWhereNull('passport_status'); break;
                        case 'appointment_status': $q1->where('appointment_status','ยังไม่ได้ส่ง')->orWhereNull('appointment_status'); break;
                        case 'withholding_tax_status': $q1->where('withholding_tax_status','ยังไม่ได้ออก')->orWhereNull('withholding_tax_status'); break;
                        case 'wholesale_tax_status': $q1->where('wholesale_tax_status','ยังไม่ได้รับ')->orWhereNull('wholesale_tax_status'); break;
                    }
                });
            })
            ->when($searchSale && $searchSale != 'all', function ($query) use ($searchSale) {
                return $query->where('quote_sale', $searchSale);
            })
            ->when($searchCountry && $searchCountry != 'all', function ($query) use ($searchCountry) {
                return $query->where('quote_country', $searchCountry);
            })
            ->when($searchWholesale && $searchWholesale != 'all', function ($query) use ($searchWholesale) {
                return $query->where('quote_wholesale', $searchWholesale);
            })
            ->orderBy('created_at', 'desc');

        $quotations = $quotationsQuery->paginate($perPage)->withQueryString();

        // กรองสถานะลูกค้าด้วย PHP (ถ้าจำเป็น)
        if ($searchCustomerPayment !== 'all') {
            $filtered = $quotations->getCollection()->filter(function ($quotation) use ($searchCustomerPayment) {
                return $quotation->customer_payment_status === $searchCustomerPayment;
            })->values();
            $quotations->setCollection($filtered);
        }

        // กรองสถานะโฮลเซลล์ด้วย PHP (filter เฉพาะค่าที่ตรงกับ dropdown)
        if ($searchPaymentWholesaleStatus && $searchPaymentWholesaleStatus !== 'all') {
            $filtered = $quotations->getCollection()->filter(function ($quotation) use ($searchPaymentWholesaleStatus) {
                $status = strip_tags(getStatusPaymentWhosale($quotation));
                // รองรับสถานะ "โอนเงินให้โฮลเซลล์เกิน" ด้วย
                return $status === $searchPaymentWholesaleStatus;
            })->values();
            $quotations->setCollection($filtered);
        }

        // เพิ่มตัวเลือกสถานะโอนเงินให้โฮลเซลล์เกินใน dropdown
        $wholesalePaymentStatuses = [
            'รอชำระเงินมัดจำ',
            'รอชำระเงินส่วนที่เหลือ',
            'ชำระเงินครบแล้ว',
            'รอโฮลเซลคืนเงิน',
            'โฮลเซลคืนเงินแล้ว',
            'โอนเงินให้โฮลเซลล์เกิน',
        ];

        $SumPax = $quotations->sum('quote_pax_total');
        $SumTotal = $quotations->sum('quote_grand_total');

        $customerPaymentStatuses = [
            'รอคืนเงิน',
            'ยกเลิกการสั่งซื้อ',
            'ชำระเงินครบแล้ว',
            'ชำระเงินเกิน',
            'เกินกำหนดชำระเงิน',
            'รอชำระเงินเต็มจำนวน',
            'รอชำระเงินมัดจำ',
            'รอชำระเงิน',
        ];

        return view('quotations.list', compact('SumTotal', 'SumPax', 'airlines', 'sales', 'wholesales', 'quotations', 'country', 'request', 'customerPaymentStatuses', 'campaignSource'));
    }
}
