<?php

namespace App\Http\Controllers\payments;

use Illuminate\Http\Request;
use Spatie\FlareClient\View;
use App\Http\Controllers\Controller;
use App\Models\invoices\invoiceModel;
use App\Models\quotations\quotationModel;

class paymentController extends Controller
{
    //

    public function index(invoiceModel $invoiceModel, Request $request)
    {
        $quotationModel = quotationModel::where('quote_number',$invoiceModel->quote_number)->first();

        return view('payments.index',compact('quotationModel','invoiceModel'));
    }

    public function invoice(invoiceModel $invoiceModel, Request $request)
    {
        return view('payments.invoice-modal',compact('invoiceModel'));
    }
}
