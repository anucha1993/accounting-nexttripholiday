<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;
use Illuminate\Http\Request;

class customerController extends Controller
{
    //

    public function ajaxEdit(Request $request)
    {
        $customer = customerModel::where('customer_id',$request->customerID)->first();

        return response()->json($customer);
    }
    public function ajaxUpdate(Request $request)
    {
         customerModel::where('customer_id',$request->customer_id)
        ->update([
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_texid' => $request->customer_texid,
            'customer_tel' => $request->customer_tel,
            'customer_fax' => $request->customer_fax,
            'customer_email' => $request->customer_email,
            'customer_name' => $request->customer_name,
        ]);
        $customer = customerModel::where('customer_id',$request->customer_id)->first();

        return response()->json($customer);
    }
}
