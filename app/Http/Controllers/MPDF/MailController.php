<?php

namespace App\Http\Controllers\MPDF;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
use App\Models\debits\debitModel;
use App\Models\invoices\taxinvoiceModel;
use App\Models\quotations\quotationModel;

class MailController extends Controller
{
    //

    public function formMailQuote(quotationModel $quotationModel)
    {
        $customer = customerModel::where('customer_id',$quotationModel->customer_id)->first();
        return view('MPDF.modal-quote',compact('quotationModel','customer'));
    }

    public function formMailInvoice(invoiceModel $invoiceModel)
    
    {
        $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
        return view('MPDF.modal-invoice',compact('invoiceModel','customer'));
    }
  
    public function formMailtaxReceipt(invoiceModel $invoiceModel)
    {
        $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
        $taxReceipt = taxinvoiceModel::where('invoice_number',$invoiceModel->invoice_number)->first();
        return view('MPDF.modal-taxreceipt',compact('invoiceModel','customer','taxReceipt'));
    }

    public function formMailDebitReceipt(debitModel $debitModel)
    {
        $customer = customerModel::where('customer_id',$debitModel->customer_id)->first();
        // $taxReceipt = taxinvoiceModel::where('invoice_number',$debitModel->invoice_number)->first();
        return view('MPDF.modal-debitReceipt',compact('debitModel','customer'));
    }

}