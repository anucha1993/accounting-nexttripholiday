<?php

namespace App\Http\Controllers\invoices;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
use App\Models\invoices\addDebtModel;
use App\Models\invoices\creditNoteModel;
use App\Models\wholesale\wholesaleModel;

class invoiceDashboardController extends Controller
{
    //

    public function index(Request $request)
    {
        $invoice = invoiceModel::where('invoice_id',$request->invoiceID)->first();
        $customer = customerModel::where('customer_id',$invoice->customer_id)->first();
        $sale = saleModel::where('id',$invoice->invoice_sale)->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->where('id',$invoice->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('id',$tour->airline_id)->first();
        $booking = bookingModel::where('code',$invoice->invoice_booking)->first();
        $wholesale = wholesaleModel::where('id',$invoice->wholesale_id)->first();

        //get
        $debts = addDebtModel::where('invoice_number',$invoice->invoice_number)->get();
        $creditNotes = creditNoteModel::where('invoice_number',$invoice->invoice_number)->get();

        return view('invoices.invoice-dashboard',compact('customer','invoice','sale','tour','airline','booking','wholesale','debts','creditNotes'));
    }
}
