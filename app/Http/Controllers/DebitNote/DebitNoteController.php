<?php

namespace App\Http\Controllers\DebitNote;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\products\productModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\debitnote\debitNoteModel;
use App\Models\debits\debitNoteProductModel;
use App\Models\invoices\taxinvoiceModel;
use Illuminate\Support\Facades\Auth;

class DebitNoteController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

      $debitNote = debitNoteModel::get();
      return view('debit-note.index',compact('debitNote'));
    }

    public function create()
    {

      $products = productModel::where('product_type', '!=', 'discount')->get();
      $customers = DB::table('customer')->get();
      $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();
      $productDiscount = productModel::where('product_type', 'discount')->get();
      $taxinvoice = taxinvoiceModel::latest()->get();
      return view('debit-note.form-create',compact('products','customers','sales','productDiscount','taxinvoice'));
    }

    

    public function store(Request $request)
    {
        //dd($request);
        $debitNote = new debitNoteModel($request->all());
        $debitNote->debitnote_number = debitNoteModel::generateDebitNoteNumber();
        $debitNote->created_by = Auth::user()->name;
        $debitNote->save();

        foreach ($request->product_id as $key => $product) {
          $productName = productModel::where('id', $request->product_id[$key])->first();
          if ($request->product_id) {
              debitNoteProductModel::create([
                  'debitnote_id' => $debitNote->debitnote_id,
                  'product_id' => $request->product_id[$key],
                  'product_name' => $productName->product_name,
                  'product_qty' => $request->quantity[$key],
                  'product_price' => $request->price_per_unit[$key],
                  'product_sum' => $request->total_amount[$key],
                  'expense_type' => $request->expense_type[$key],
                  'vat_status' => $request->vat_status[$key],
                  'withholding_tax' => $request->withholding_tax[$key],
              ]);
          }
      }

      return redirect()->back();

    }


    public function edit(debitNoteModel $debitNoteModel)
    {
      
      $products = productModel::where('product_type', '!=', 'discount')->get();
      $customers = DB::table('customer')->get();
      $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();
      $productDiscount = productModel::where('product_type', 'discount')->get();
      $taxinvoice = taxinvoiceModel::latest()->get();
      $debitItem = debitNoteProductModel::where('debitnote_id', $debitNoteModel->debitnote_id)->where('expense_type','income')->get();
      $debitItemDiscont = debitNoteProductModel::where('debitnote_id', $debitNoteModel->debitnote_id)->where('expense_type','discount')->get();
      //dd($debitItemDiscont);
      return view('debit-note.form-edit',compact('products','customers','sales','productDiscount','taxinvoice','debitNoteModel','debitItem','debitItemDiscont'));
    }

    public function update(debitNoteModel $debitNote ,Request $request)
    {
        //dd($request);

        $debitNote->update_by = Auth::user()->name;
        $debitNote->update();

        // ลบ Product เก่า ออก
        if($debitNote) {
          debitNoteProductModel::where('debitnote_id', $debitNote->debitnote_id)->delete();
          foreach ($request->product_id as $key => $product) {
            $productName = productModel::where('id', $request->product_id[$key])->first();
            if ($request->product_id) {
                debitNoteProductModel::create([
                    'debitnote_id' => $debitNote->debitnote_id,
                    'product_id' => $request->product_id[$key],
                    'product_name' => $productName->product_name,
                    'product_qty' => $request->quantity[$key],
                    'product_price' => $request->price_per_unit[$key],
                    'product_sum' => $request->total_amount[$key],
                    'expense_type' => $request->expense_type[$key],
                    'vat_status' => $request->vat_status[$key],
                    'withholding_tax' => $request->withholding_tax[$key],
                ]);
            }
        }
        }
      
      return redirect()->back();

    }

 

    


}
