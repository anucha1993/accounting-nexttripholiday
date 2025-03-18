<?php

namespace App\Http\Controllers\report;

use function Ramsey\Uuid\v1;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Model;
use App\Models\quotations\quotationModel;
use Yajra\Datatables\Datatables;

class quoteReportController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = quotationModel::with('customer', 'quoteCountry', 'quoteWholesale', 'Salename', 'airline')
                ->get()
                ->map(function ($row) {
                    $row['customer_name'] = $row->customer ? $row->customer->customer_name : '';
                    return $row;
                })
                ->map(function ($row) {
                    $row['country_name_th'] = $row->quoteCountry ? $row->quoteCountry->country_name_th : '';
                    return $row;
                })
                ->map(function ($row) {
                    $row['code'] = $row->quoteWholesale ? $row->quoteWholesale->code : '';
                    return $row;
                })
                ->map(function ($row) {
                    $row['sale_name'] = $row->Salename ? $row->Salename->name : '';
                    return $row;
                })
                ->map(function ($row) {
                    $row['travel_name'] = $row->airline ? $row->airline->code : '';
                    return $row;
                });

            return Datatables::of($data)
                ->addColumn('payment_status', function ($row) {
                    $output = getQuoteStatusPaymentReport($row);
                    return $output;
                })
                ->addColumn('payment_wholesale', function ($row) {
                    $latestPayment = $row->paymentWholesale()->latest('payment_wholesale_id')->first();

                    if (!$latestPayment || $latestPayment->payment_wholesale_type === null) {
                        $output = 'รอชำระเงิน';
                    } elseif ($latestPayment->payment_wholesale_type === 'deposit') {
                        $output = 'รอชำระเงินเต็มจำนวน';
                    } elseif ($latestPayment->payment_wholesale_type === 'full') {
                        $output = 'ชำระเงินแล้ว';
                    }
                    return $output;
                })
                ->rawColumns(['payment_status', 'payment_wholesale'])
                ->make(true);
        }
        return view('reports.quote-form');
    }
}
