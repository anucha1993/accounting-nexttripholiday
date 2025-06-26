<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\inputTax\inputTaxModel;
use App\Models\quotations\quotationModel;
use App\Models\sales\saleModel;

class inputTaxReportController extends Controller
{
    //

    public function index(Request $request)
    {
        $searchDateStart = $request->input('date_start');
        $searchDateEnd = $request->input('date_end');
        $status = $request->input('status');
        $sellerId = $request->input('seller_id');

        $inputTaxs = inputTaxModel::whereNotNull('input_tax_number_tax')
        ->when($searchDateStart && $searchDateEnd, function ($query) use ($searchDateStart, $searchDateEnd) {
            return $query->whereBetween('input_tax_date_tax', [$searchDateStart, $searchDateEnd]);
        })
        ->when($status ,function ($query) use ($status) {
            if ($status === 'not_null') {
                 return $query->whereNotNull('input_tax_file');
            } else {
                return $query->whereNull('input_tax_file');
            }
        })
        ->when($sellerId, function ($query) use ($sellerId) {
            return $query->whereHas('quote', function ($q) use ($sellerId) {
                $q->where('quote_sale', $sellerId);
            });
        })
        ->get();

        $grandTotalSum = $inputTaxs->sum(function ($inputTax) {
            return $inputTax->input_tax_service_total;
        });
        $vat = $inputTaxs->sum(function ($inputTax) {
            return $inputTax->input_tax_vat;
        });

        $sellers = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();

        return view('reports.input-tax-form',compact('inputTaxs','grandTotalSum','vat','sellers'));
    }


}
