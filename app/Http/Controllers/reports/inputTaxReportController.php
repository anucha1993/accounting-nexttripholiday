<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class inputTaxReportController extends Controller
{
    //

    public function index()
    {
        return view('reports.input-tax-form');
    }

}
