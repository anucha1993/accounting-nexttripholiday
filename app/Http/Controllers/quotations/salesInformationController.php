<?php

namespace App\Http\Controllers\quotations;

use App\Http\Controllers\Controller;
use App\Models\credits\creditModel;
use App\Models\debits\debitModel;
use App\Models\invoices\invoiceModel;
use App\Models\invoices\taxinvoiceModel;
use App\Models\quotations\quotationModel;
use Illuminate\Http\Request;

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
        

        return view('sales-info.index',compact('creditnote','quotationModel','invoices','taxinvoices','taxinvoices','debitnote'));
    }
}
