<?php

namespace App\Http\Controllers\quotations;

use App\Http\Controllers\Controller;
use App\Models\quotations\quotationModel;
use Illuminate\Http\Request;

class salesInformationController extends Controller
{
    //

    public function index(quotationModel $quotationModel)
    {
        return view('sales-info.index',compact('quotationModel'));
    }
}
