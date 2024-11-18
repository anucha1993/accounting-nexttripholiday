<?php

namespace App\Http\Controllers\withholding;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;
use App\Models\invoices\taxinvoiceModel;

class withholdingTaxController extends Controller
{
    //

    public function create()
    {
        $customers = customerModel::latest()->get();
        return view('withholding.create',compact('customers'));
    }

    public function taxNumber(Request $request)
    {
        $query = $request->get('query'); // รับค่าการค้นหา
       $documents  = taxinvoiceModel::where('taxinvoice_number', 'LIKE', "%{$query}%")
       ->get(['taxinvoice_id', 'taxinvoice_number']); // ดึงเฉพาะ ID และ tax_number
       return response()->json($documents );
    }
}
