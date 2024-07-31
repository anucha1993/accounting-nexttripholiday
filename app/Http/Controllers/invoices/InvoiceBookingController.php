<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceBookingController extends Controller
{
    //

    public function index()
    {
        return view('invoices.form-booking');
    }
}
