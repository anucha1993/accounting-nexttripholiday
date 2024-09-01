<?php

namespace App\Http\Controllers\quotations;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Models\debits\debitModel;
use Illuminate\Support\Facades\DB;
use App\Models\credits\creditModel;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
use App\Models\invoices\taxinvoiceModel;
use App\Models\quotations\quotationModel;
use App\Models\wholesale\wholesaleModel;

class salesInformationController extends Controller
{
    //

    public function index(quotationModel $quotationModel)
    {
        $quotationModel = $quotationModel->leftjoin('customer','customer.customer_id','quotation.customer_id')->first();
        $invoices= invoiceModel::where('quote_number',$quotationModel->quote_number)->leftjoin('customer','customer.customer_id','invoices.customer_id')->get();

        $invoice = invoiceModel::where('quote_number',$quotationModel->quote_number)->first();

        $taxinvoices= taxinvoiceModel::where('taxinvoices.invoice_number',$invoice->invoice_number)
        ->leftjoin('invoices','invoices.invoice_number','taxinvoices.invoice_number')
        ->leftjoin('customer','customer.customer_id','invoices.customer_id')
        ->get();

        $debitnote = debitModel::where('debit_note.invoice_number',$invoice->invoice_number)
        ->leftjoin('invoices','invoices.invoice_number','debit_note.invoice_number')
        ->leftjoin('customer','customer.customer_id','invoices.customer_id')
        ->get();

        
        $creditnote = creditModel::where('credit_note.invoice_number',$invoice->invoice_number)
        ->leftjoin('invoices','invoices.invoice_number','credit_note.invoice_number')
        ->leftjoin('customer','customer.customer_id','invoices.customer_id')
        ->get();
        

        return view('sales-info.index',compact('creditnote','quotationModel','invoices','taxinvoices','taxinvoices','debitnote','invoice'));
    }

    public function info(quotationModel $quotationModel)
    {
        $customer = customerModel::where('customer_id',$quotationModel->customer_id)->first();
        $invoice = invoiceModel::where('quote_number',$quotationModel->quote_number)->first();
        $sale = saleModel::where('id',$quotationModel->quote_sale)->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->where('id',$quotationModel->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$tour->airline_id)->first();
        $booking = bookingModel::where('code',$quotationModel->quote_booking)->first();
        $wholesale = wholesaleModel::where('id',$tour->wholesale_id)->first();
        return view('sales-info.info',compact('quotationModel','customer','invoice','sale','tour','airline','booking','wholesale'));
    }
}
