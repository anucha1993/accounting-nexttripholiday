<?php

namespace App\Http\Controllers\report;

use function Ramsey\Uuid\v1;
use Illuminate\Http\Request;
use App\Models\sales\saleModel;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\countryModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;

class quoteReportController extends Controller
{
    //

    // public function index(Request $request)
    // {
       
    //     if ($request->ajax()) {
    //         $searchKeyword = $request->get('search_keyword');
    //         $data = quotationModel::with('Salename', 'quoteCustomer', 'quoteWholesale', 'paymentWholesale', 'quoteInvoice', 'quoteLogStatus')
    //             ->where('quote_number', 'LIKE', '%' .$searchKeyword.'%')
    //             ->get()
    //             ->map(function ($row) {
    //                 $row['customer_name'] = $row->customer ? $row->customer->customer_name : '';
    //                 return $row;
    //             })
    //             ->map(function ($row) {
    //                 $row['country_name_th'] = $row->quoteCountry ? $row->quoteCountry->country_name_th : '';
    //                 return $row;
    //             })
    //             ->map(function ($row) {
    //                 $row['code'] = $row->quoteWholesale ? $row->quoteWholesale->code : '';
    //                 return $row;
    //             })
    //             ->map(function ($row) {
    //                 $row['sale_name'] = $row->Salename ? $row->Salename->name : '';
    //                 return $row;
    //             })
    //             ->map(function ($row) {
    //                 $row['travel_name'] = $row->airline ? $row->airline->code : '';
    //                 return $row;
    //             });

    //         return Datatables::of($data)
    //             ->addColumn('payment_status', function ($row) {
    //                 $output = getQuoteStatusPaymentReport($row);
    //                 return $output;
    //             })
    //             ->addColumn('payment_wholesale', function ($row) {
    //                 $latestPayment = $row->paymentWholesale()->latest('payment_wholesale_id')->first();

    //                 if (!$latestPayment || $latestPayment->payment_wholesale_type === null) {
    //                     $output = 'รอชำระเงิน';
    //                 } elseif ($latestPayment->payment_wholesale_type === 'deposit') {
    //                     $output = 'รอชำระเงินเต็มจำนวน';
    //                 } elseif ($latestPayment->payment_wholesale_type === 'full') {
    //                     $output = 'ชำระเงินแล้ว';
    //                 }
    //                 return $output;
    //             })
    //             ->rawColumns(['payment_status', 'payment_wholesale'])
    //             ->make(true);
    //     }

    //     $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();
    //     $airlines = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
    //     $country = countryModel::get();
    //     $wholesales = wholesaleModel::get();
    //     return view('reports.quote-form',compact('request','sales','airlines','country','wholesales'));
    // }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50);
        $searchKeyword = $request->input('search_keyword');
        $searchPeriodDateStart = $request->input('search_period_start');
        $searchPeriodDateEnd = $request->input('search_period_end');
        $searchQuoteDateStart = $request->input('search_booking_start');
        $searchQuoteDateEnd = $request->input('search_booking_end');

        $searchDateStartCreated = $request->input('search_tour_date_start_created');
        $searchDateEndCreated = $request->input('search_tour_date_end_created');
        $searchSale = $request->input('search_sale');
        $searchCountry = $request->input('search_country');
        $searchWholesale = $request->input('search_wholesale');
        $searchAirline = $request->input('search_airline');
        $searchPax = $request->input('search_pax');
        $searchLogStatus = $request->input('Search_check_list');
        $searchPaymentWholesaleStatus = $request->input('search_wholesale_payment');
        $searchCustomerPayment = $request->input('search_customer_payment', 'all');
        $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();
        $airlines = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $country = countryModel::get();
        $wholesales = wholesaleModel::get();
        
        $quotations = quotationModel::with('Salename', 'quoteCustomer', 'quoteWholesale', 'paymentWholesale', 'quoteInvoice', 'quoteLogStatus')
            // Search คียร์เวิร์ด
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
            //Search Quote Date
            ->when($searchPeriodDateStart && $searchPeriodDateEnd, function ($query) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                return $query->where(function ($q) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                    $q->whereBetween('quote_date_start', [$searchPeriodDateStart, $searchPeriodDateEnd])
                        ->orWhereBetween('quote_date_end', [$searchPeriodDateStart, $searchPeriodDateEnd])
                        ->orWhere(function ($q) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                            $q->where('quote_date_start', '<=', $searchPeriodDateStart)->where('quote_date_end', '>=', $searchPeriodDateEnd);
                        });
                });
            })

            // Searchs Quote Date
            ->when($searchQuoteDateStart && $searchQuoteDateEnd, function ($query) use ($searchQuoteDateStart, $searchQuoteDateEnd) {
                return $query->whereBetween('quote_date', [$searchQuoteDateStart, $searchQuoteDateEnd]);
            })

            // Search Airline
            ->when($searchAirline && $searchAirline != 'all', function ($query) use ($searchAirline) {
                return $query->where('quote_airline', $searchAirline);
            })
            // Search Pax
            ->when($searchPax && $searchPax != null, function ($query) use ($searchPax) {
                return $query->where('quote_pax_total', $searchPax);
            })
            // Search Quote Log Status
            ->when($searchLogStatus, function ($query, $searchLogStatus) {
                return $query->whereHas('quoteLogStatus', function ($q1) use ($searchLogStatus) {
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
                        case 'withholding_tax_status':
                            $q1->where('withholding_tax_status', 'ได้รับแล้ว');
                            break;
                    }
                });
            })

            ->when($searchDateStartCreated && $searchDateEndCreated, function ($query) use ($searchDateStartCreated, $searchDateEndCreated) {
                return $query->whereBetween('quote_booking_create', [$searchDateStartCreated, $searchDateEndCreated]);
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

            ->when($searchPaymentWholesaleStatus === 'NULL', function ($query) {
                // กรณี "รอชำระเงิน" หมายถึงไม่มีข้อมูลใน paymentWholesale เลย
                $query->whereDoesntHave('paymentWholesale');
            })
            ->when($searchPaymentWholesaleStatus === 'deposit', function ($query) {
                // กรณี "รอชำระเงินเต็มจำนวน" หมายถึงแถวล่าสุดของ paymentWholesale เป็น deposit
                $query->whereHas('paymentWholesale', function ($q) {
                    $q->where('payment_wholesale_id', function ($subquery) {
                        $subquery->select('payment_wholesale_id')->from('payment_wholesale')->whereColumn('payment_wholesale.payment_wholesale_quote_id', 'quotation.quote_id')->orderBy('payment_wholesale_id', 'desc')->limit(1);
                    })->where('payment_wholesale_type', 'deposit');
                });
            })
            ->when($searchPaymentWholesaleStatus === 'full', function ($query) {
                // กรณี "ชำระเงินแล้ว" หมายถึงแถวล่าสุดของ paymentWholesale เป็น full
                $query->whereHas('paymentWholesale', function ($q) {
                    $q->where('payment_wholesale_id', function ($subquery) {
                        $subquery->select('payment_wholesale_id')->from('payment_wholesale')->whereColumn('payment_wholesale.payment_wholesale_quote_id', 'quotation.quote_id')->orderBy('payment_wholesale_id', 'desc')->limit(1);
                    })->where('payment_wholesale_type', 'full');
                });
            })

            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // กรองสถานะใน PHP
        if ($searchCustomerPayment !== 'all') {
            $filtered = $quotations->getCollection()->filter(function ($quotation) use ($searchCustomerPayment) {
                return strip_tags(getQuoteStatusPayment($quotation)) === $searchCustomerPayment;
            });

            // กำหนด Collection ที่กรองแล้วกลับเข้าไปใน Paginator
            $quotations->setCollection($filtered);
        }

        $statuses = $quotations
            ->map(function ($quotation) {
                return strip_tags(getQuoteStatusPayment($quotation));
            })
            ->unique();

        $SumPax = $quotations->sum('quote_pax_total');
        $SumTotal = $quotations->sum('quote_grand_total');
   

        return view('reports.quote-form', compact('SumTotal', 'SumPax', 'airlines', 'sales', 'wholesales', 'quotations', 'country', 'request', 'statuses'));
    }


}
