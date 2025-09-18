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


    public function generateRunningCodeCUS()
    {
        $customer = customerModel::select('customer_number')->latest()->first();
        if (!empty($customer)) {
            $CusNumber = $customer->customer_number;
        } else {
            $CusNumber = 'CUS-' . date('y') . date('m') . '0000';
        }
        $prefix = 'CUS-';
        $year = date('y');
        $month = date('m');
        $lastFourDigits = substr($CusNumber, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month . $newNumber;
        return $runningCode;
    }


    // api create
    public function store(Request $request)
    {
        $runningCodeCus = $this->generateRunningCodeCUS();
        $request->merge(['customer_number' => $runningCodeCus]);
        
        $customer = customerModel::create($request->all());
        return response()->json($customer, 201);
    }

}