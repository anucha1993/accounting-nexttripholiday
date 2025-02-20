<?php

namespace App\Http\Controllers\DebitNote;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\invoices\taxinvoiceModel;
use App\Models\products\productModel;
use Illuminate\Database\Eloquent\Model;

class DebitNoteController extends Controller
{
    //

    public function index()
    {

      $products = productModel::where('product_type', '!=', 'discount')->get();
      $customers = DB::table('customer')->get();
      $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();
      $productDiscount = productModel::where('product_type', 'discount')->get();
      $taxinvoice = taxinvoiceModel::latest()->get();
      return view('debit-note.form-create',compact('products','customers','sales','productDiscount','taxinvoice'));
    }

    

    public function calculate(Request $request)
    {
        // รับข้อมูลจากฟอร์ม
        $data = $request->all();
        return response()->json($data); // ส่งกลับเป็น JSON สำหรับการทดสอบ
    }

}
