<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;

class apiInvoiceController extends Controller
{
    //

    public function invoice(Request $request)
    {
        $invoices = invoiceModel::where('invoice_id',$request->invoice_id)->first();
        $quote = quotationModel::where('quote_id',$invoices->invoice_quote_id)->first();
        $customer = customerModel::where('customer_id',$quote->customer_id)->first();
        $wholesale = wholesaleModel::where('id', $quote->quote_wholesale)->first();

        return response()->json(['invoice' => $invoices,'quote' => $quote, 'customer' => $customer, 'wholesale' => $wholesale]);
    }
}
