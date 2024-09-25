<?php

namespace App\Http\Controllers\MPDF;

use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;
use App\Models\quotations\quotationModel;
use Illuminate\Http\Request;

class MailQuoteController extends Controller
{
    //

    public function formMail(quotationModel $quotationModel)
    {
        $customer = customerModel::where('customer_id',$quotationModel->customer_id)->first();
        return view('MPDF.modal-quote',compact('quotationModel','customer'));
    }
}
