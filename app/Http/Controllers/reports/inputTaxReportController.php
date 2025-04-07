<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\inputTax\inputTaxModel;
use App\Models\quotations\quotationModel;

class inputTaxReportController extends Controller
{
    //

    public function index()
    {
        $inputTaxs = inputTaxModel::whereNotNull('input_tax_number_tax')->get();


        $grandTotalSum = $inputTaxs->sum(function ($inputTax) {
            return $inputTax->input_tax_service_total;
        });
        
        $vat = $inputTaxs->sum(function ($inputTax) {
            return $inputTax->input_tax_vat;
        });

        return view('reports.input-tax-form',compact('inputTaxs','grandTotalSum','vat'));
    }


}
