<?php

namespace App\Http\Controllers\quotations;

use App\Http\Controllers\Controller;
use App\Models\invoices\invoiceModel;
use App\Models\quotations\quotationModel;
use Illuminate\Http\Request;

class salesInformationController extends Controller
{
    //

    public function index(quotationModel $quotationModel)
    {
        $quotationModel = $quotationModel->leftjoin('customer','customer.customer_id','quotation.customer_id')->first();
        $invoices= invoiceModel::where('quote_number',$quotationModel->quote_number)->leftjoin('customer','customer.customer_id','invoices.customer_id')->get();
        return view('sales-info.index',compact('quotationModel','invoices'));
    }
}
