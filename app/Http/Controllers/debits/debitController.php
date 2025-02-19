<?php

namespace App\Http\Controllers\debits;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Models\debits\debitModel;
use App\Models\mumday\numDayModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\invoices\taxinvoiceModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\debits\debitNoteProductModel;
use App\Models\invoices\invoicePorductsModel;

class debitController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');

    }

    // function Runnumber Debit
    public function generateRunningCodeDBN()
    {
        $debitModel = debitModel::select('debit_number')->latest()->first();
        if (!empty($debitModel)) {
            $Number = $debitModel->invoice_number;
        } else {
            $Number = 'DBN' . date('Y') . date('m') .'-'. '0000';
        }
        $prefix = 'DBN';
        $year = date('Y');
        $month = date('m');
        $lastFourDigits = substr($Number, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month .'-'. $newNumber;
        return $runningCode;
    }

    public function index()
    {
      return view('debits.index');
    }

    public function create()
    {

      $products = productModel::where('product_type', '!=', 'discount')->get();
      $customers = DB::table('customer')->get();
      $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();
      $productDiscount = productModel::where('product_type', 'discount')->get();
      return view('debits.form-create',compact('products','customers','sales','productDiscount'));
    }


    

}
