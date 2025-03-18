<?php

namespace App\Http\Controllers\CreditNote;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Models\creditnote\creditNoteModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use App\Models\invoices\taxinvoiceModel;
use App\Models\creditnote\creditNoteProductModel;




class creditNoteController extends Controller
{
    //
     //

     public function __construct()
     {
         $this->middleware('auth');
     }
 
     public function index(Request $request)
     {
         $dateStart = $request->date_start;
         $dateEnd = $request->date_end;
 
         $creditNote = creditNoteModel::with('quote', 'taxinvoice')
             ->when($request->creditnote_number, function ($query) use ($request) {
                 return $query->where('creditnote_number', $request->creditnote_number);
             })
 
             ->when($request->creditnote_quote, function ($query) use ($request) {
                 // แก้ไข closure
                 return $query->whereHas('quote', function ($q1) use ($request) {
                     $q1->where('quote_number', $request->creditnote_quote);
                 });
             })
             ->when($request->customer_id, function ($query) use ($request) {
                 // แก้ไข closure
                 return $query->whereHas('quote', function ($q1) use ($request) {
                     $q1->where('customer_id', $request->customer_id);
                 });
             })
             ->when($request->creditnote_tax, function ($query) use ($request) {
                 // แก้ไข closure
                 return $query->whereHas('taxinvoice', function ($q1) use ($request) {
                     $q1->where('taxinvoice_number', $request->creditnote_tax);
                 });
             })
 
             //Search Quote Date
             ->when($dateStart && $dateEnd, function ($query) use ($dateStart, $dateEnd) {
                 return $query->where(function ($q) use ($dateStart, $dateEnd) {
                     $q->whereBetween('creditnote_date', [$dateStart, $dateEnd])
                         ->orWhereBetween('creditnote_date', [$dateStart, $dateEnd])
                         ->orWhere(function ($q) use ($dateStart, $dateEnd) {
                             $q->where('creditnote_date', '<=', $dateStart)->where('creditnote_date', '>=', $dateEnd);
                         });
                 });
             })
 
             ->paginate(10);
 
         $customers = customerModel::latest()->get();
         return view('credit-note.index', compact('creditNote', 'customers'));
     }
 
     public function create()
     {
         $products = productModel::where('product_type', '!=', 'discount')->get();
         $customers = DB::table('customer')->get();
         $sales = saleModel::select('name', 'id')
             ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
             ->get();
         $productDiscount = productModel::where('product_type', 'discount')->get();
         $taxinvoice = taxinvoiceModel::latest()->get();
         return view('credit-note.form-create', compact('products', 'customers', 'sales', 'productDiscount', 'taxinvoice'));
     }
 
     public function store(Request $request)
     {
         //dd($request);
         $creditNote = new creditNoteModel($request->all());
         $creditNote->creditnote_number = creditNoteModel::generateDebitNoteNumber();
         $creditNote->created_by = Auth::user()->name;
         $creditNote->save();
 
         foreach ($request->product_id as $key => $product) {
             $productName = productModel::where('id', $request->product_id[$key])->first();
             if ($request->product_id) {
                creditNoteProductModel::create([
                     'creditnote_id' => $creditNote->creditnote_id,
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
 
     public function edit(creditNoteModel $creditNoteModel)
     {
         $products = productModel::where('product_type', '!=', 'discount')->get();
         $customers = DB::table('customer')->get();
         $sales = saleModel::select('name', 'id')
             ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
             ->get();
         $productDiscount = productModel::where('product_type', 'discount')->get();
         $taxinvoice = taxinvoiceModel::latest()->get();
         $creditItem = creditNoteProductModel::where('creditnote_id', $creditNoteModel->creditnote_id)->where('expense_type', 'income')->get();
         $creditItemDiscont = creditNoteProductModel::where('creditnote_id', $creditNoteModel->creditnote_id)->where('expense_type', 'discount')->get();
         //dd($creditItemDiscont);
         return view('credit-note.form-edit', compact('products', 'customers', 'sales', 'productDiscount', 'taxinvoice', 'creditNoteModel', 'creditItem', 'creditItemDiscont'));
     }
 
     public function copy(creditNoteModel $creditNoteModel)
     {
         $products = productModel::where('product_type', '!=', 'discount')->get();
         $customers = DB::table('customer')->get();
         $sales = saleModel::select('name', 'id')
             ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
             ->get();
         $productDiscount = productModel::where('product_type', 'discount')->get();
         $taxinvoice = taxinvoiceModel::latest()->get();
         $creditItem = creditNoteProductModel::where('creditnote_id', $creditNoteModel->creditnote_id)->where('expense_type', 'income')->get();
         $creditItemDiscont = creditNoteProductModel::where('creditnote_id', $creditNoteModel->creditnote_id)->where('expense_type', 'discount')->get();
         //dd($creditItemDiscont);
         return view('credit-note.form-copy', compact('products', 'customers', 'sales', 'productDiscount', 'taxinvoice', 'creditNoteModel', 'creditItem', 'creditItemDiscont'));
     }
 
     public function update(creditNoteModel $creditNoteModel, Request $request)
     {
         //dd($request);
 
         $creditNoteModel->updated_by = Auth::user()->name;
         $creditNoteModel->update($request->all());
 
         //dd($creditNoteModel);
 
         // ลบ Product เก่า ออก
         if ($creditNoteModel) {
             creditNoteProductModel::where('creditnote_id', $creditNoteModel->creditnote_id)->delete();
             foreach ($request->product_id as $key => $product) {
                 $productName = productModel::where('id', $request->product_id[$key])->first();
                 if ($request->product_id) {
                     creditNoteProductModel::create([
                         'creditnote_id' => $creditNoteModel->creditnote_id,
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
 
     public function delete(creditNoteModel $creditNoteModel)
     {
         $creditNoteModel->delete();
 
         return redirect()->back();
     }
}
