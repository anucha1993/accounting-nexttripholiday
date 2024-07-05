<?php

namespace App\Http\Controllers\invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class invoiceController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-invoice|edit-invoice|delete-invoice|view-invoice', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-invoice', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-invoice', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-invoice', ['only' => ['destroy']]);
    }

    public function edit()
    {
        return view('invoices.edit-invoice');
    }


}
