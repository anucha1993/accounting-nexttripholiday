<?php

namespace App\Http\Controllers\invoices;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\invoices\creditNoteModel;
use App\Models\invoices\invoicePorductsModel;
use App\Models\invoices\creditNoteProductModel;

class creditNoteController extends Controller
{
    //
      //
      public function create(Request $request)
      {
          $invoiceId = $request->invoiceID;
          $invoices = invoiceModel::where('invoice_id', $invoiceId)->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')->first();
  
          $productIncome = productModel::get();
          $customer = customerModel::where('customer_id', $invoices->customer_id)->first();
          $sale = saleModel::where('id', $invoices->invoice_sale)->first();
          $tour = DB::connection('mysql2')
              ->table('tb_tour')
              ->select('code', 'airline_id')
              ->where('id', $invoices->tour_id)
              ->first();
          $airline = DB::connection('mysql2')
              ->table('tb_travel_type')
              ->select('travel_name')
              ->where('id', $tour->airline_id)
              ->first();
          $invoiceProduct = invoicePorductsModel::where('invoice_id', $invoiceId)
              ->select(
                  'invoice_product.invoice_qty',
                  'invoice_product.invoice_price',
                  'invoice_product.invoice_sum',
                  'invoice_product.product_id',
                  // product
                  'products.product_name',
              )
              ->leftjoin('products', 'products.id', 'invoice_product.product_id')
              ->get();
          return view('invoices.creditNote.form-creditNote-add', compact('invoices', 'sale', 'productIncome', 'customer', 'tour', 'airline', 'invoiceProduct'));
      }
  
      public function edit(Request $request)
      {
          $credit_noteID = $request->creditNoteID;
  
          $creditNote = creditNoteModel::where('credit_note_id', $credit_noteID)->first();

         
          $invoices = invoiceModel::where('invoice_number', $creditNote->invoice_number)
              ->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')
              ->first();
        //  dd($creditNote);

          $productIncome = productModel::get();
          $customer = customerModel::where('customer_id', $invoices->customer_id)->first();
 
          $sale = saleModel::where('id', $invoices->invoice_sale)->first();
          $tour = DB::connection('mysql2')
              ->table('tb_tour')
              ->select('code', 'airline_id')
              ->where('id', $invoices->tour_id)
              ->first();
          $airline = DB::connection('mysql2')
              ->table('tb_travel_type')
              ->select('travel_name')
              ->where('id', $tour->airline_id)
              ->first();
          $creditNoteProduct = creditNoteProductModel::where('credit_note_id', $creditNote->credit_note_id)
              ->select(
                  'credit_note_product.credit_note_qty',
                  'credit_note_product.credit_note_price',
                  'credit_note_product.credit_note_sum',
                  'credit_note_product.product_id',
                  // product
                  'products.product_name',
              )
              ->leftjoin('products', 'products.id', 'credit_note_product.product_id')
              ->get();
  
          return view('invoices.creditNote.form-creditNote-edit', compact('creditNote', 'invoices', 'sale', 'productIncome', 'customer', 'tour', 'airline', 'creditNoteProduct'));
      }
  
      // function Runnumber  เลขที่อ้างอิง
      public function generateRunningCodeDBN($credit_note)
      {
          $prefix = 'CB';
          $year = date('y'); // Last two digits of the current year
          $month = date('m'); // Current month
          // Extract the last 4 digits from the booking number
          $lastFourDigits = substr($credit_note, -4);
          // Convert the last four digits to an integer and increment
          $incrementedNumber = intval($lastFourDigits) + 1;
          // Format the incremented number to be 4 digits with leading zeros
          $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
          // Generate the new running code
          $runningCode = $prefix . $year . $month . $newNumber;
          return $runningCode;
      }
  
      public function store(Request $request)
  {
      $totalQty = 0;
  
      // Get the latest credit_note record
      $credit_note = creditNoteModel::latest()->first();
  
      // Check if there's an existing credit_note record
      if ($credit_note) {
          $last_credit_note_number = $credit_note->credit_note_number;
      } else {
          // If no credit_note records exist, start the sequence with a base number
          $last_credit_note_number = 'CB' . date('y') . date('m') . '0000';
      }
  
      // Generate a new running code using the last credit_note number
      $new_credit_note_number = $this->generateRunningCodeDBN($last_credit_note_number);
  
      // Merge the new credit_note number into the request data
      $request->merge(['credit_note_number' => $new_credit_note_number]);
      $request->merge(['credit_note_status' => 'wait']);
      $request->merge(['credit_note_date' => date('Y-m-d')]);
      $request->merge(['created_by' => Auth::user()->name]);
  
      // Create a new credit_note record with the request data
      $credit_noteStore = creditNoteModel::create($request->all());
  
      // เพิ่มรายการใหม่
      if ($request->has('product_id')) {
          foreach ($request->product_id as $key => $product_id) {
              // ตรวจสอบค่าแต่ละค่าใน $request
              $product_id = $request->input('product_id.' . $key);
              $credit_note_qty = $request->input('product_qty.' . $key);
              $credit_note_price = $request->input('product_price.' . $key);
              $credit_note_sum = $request->input('product_sum.' . $key);
              $expense_type = $request->input('expense.' . $key);
  
              $productModel = productModel::where('id', $product_id)->first();
  
              // ตรวจสอบว่า $productModel ไม่เป็น null และ product_pax มีค่าเป็น Y
              if ($productModel && $productModel->product_pax === 'Y') {
                  $totalQty += $credit_note_qty;
              }
  
              if ($product_id && $productModel) {
                  // เพิ่มข้อมูลเข้าในตาราง
                  creditNoteProductModel::create([
                      'credit_note_id' => $credit_noteStore->credit_note_id,
                      'product_id' => $product_id,
                      'product_name' => $productModel->product_name,
                      'credit_note_qty' => $credit_note_qty,
                      'credit_note_price' => $credit_note_price,
                      'credit_note_sum' => $credit_note_sum,
                      'expense_type' => $expense_type,
                  ]);
              }
          }
      }
  
      // อัปเดตยอดรวมใน total_qty
      $credit_noteStore->update(['total_qty' => $totalQty]);
  }
  
  
      public function update(creditNoteModel $creditNoteModel, Request $request)
      {
          $totalQty = 0;
          $request->merge(['updated_by' => Auth::user()->name]);
          $creditNoteModel->update($request->all());
          //ลบรายการเก่า
          creditNoteProductModel::where('credit_note_id', $request->credit_note_id)->delete();
          // เพิ่มรายการใหม่
          if ($request->has('product_id')) {
              foreach ($request->product_id as $key => $product_id) {
                  // ตรวจสอบค่าแต่ละค่าใน $request
                  $product_id = $request->input('product_id.' . $key);
                  $credit_note_qty = $request->input('product_qty.' . $key);
                  $credit_note_price = $request->input('product_price.' . $key);
                  $credit_note_sum = $request->input('product_sum.' . $key);
                  $expense_type = $request->input('expense.' . $key);
  
                  $productModel = productModel::where('id', $product_id)->first();
  
                    // ตรวจสอบว่า $productModel ไม่เป็น null และ product_pax มีค่าเป็น Y
              if ($productModel && $productModel->product_pax === 'Y') {
                  $totalQty += $credit_note_qty;
              }
  
                  if ($product_id) {
                      // เพิ่มข้อมูลเข้าในตาราง
                      creditNoteProductModel::create([
                          'credit_note_id' => $request->credit_note_id,
                          'product_id' => $product_id,
                          'product_name' => $productModel->product_name,
                          'credit_note_qty' => $credit_note_qty,
                          'credit_note_price' => $credit_note_price,
                          'credit_note_sum' => $credit_note_sum,
                          'expense_type' => $expense_type,
                      ]);
                  }
              }
          }
  
          $creditNoteModel->update(['total_qty' => $totalQty]);
  
          return response()->json(['message' => 'Update successful!']);
      }
      
}
