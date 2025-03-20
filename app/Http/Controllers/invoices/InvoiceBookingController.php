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
use App\Models\invoices\invoicePorductsModel;


class InvoiceBookingController extends Controller
{
    //

    public function index(Request $request)
    {
        $invoiceId = $request->invoiceID;
        $invoices = invoiceModel::where('invoice_id', $invoiceId)->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')->get();
        $invoice = invoiceModel::where('invoice_id', $invoiceId)->first();


        return view('invoices.table-invoice', compact('invoices','invoice'));
    }

    public function edit(Request $request)
    {
        $invoiceId = $request->invoiceID;
        $invoices = invoiceModel::where('invoice_id', $invoiceId)
            ->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')->first();
        $invoiceProduct = invoicePorductsModel::where('invoice_id', $invoiceId)
            ->select(
                'invoice_product.invoice_qty',
                'invoice_product.invoice_price',
                'invoice_product.invoice_sum',
                'invoice_product.product_id',
                // product
                'products.product_name'
            )
            ->leftjoin('products', 'products.id', 'invoice_product.product_id')
            ->get();
        $productIncome = productModel::get();
        $customer = customerModel::where('customer_id',$invoices->customer_id)->first();
        $sale = saleModel::where('id',$invoices->invoice_sale)->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->select('code','airline_id')->where('id',$invoices->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$tour->airline_id)->first();
        return view('invoices.form-invoice-edit', compact('invoices','sale', 'invoiceProduct', 'productIncome','customer','tour','airline'));
    }

    public function update(Request $request)
    {
        // หาและลบรายการเดิมออก
        //return response()->json($request);
        $invoiceProduct = invoicePorductsModel::where('invoice_id', $request->invoice_id);
       

        $invoice = invoiceModel::where('invoice_id',$request->invoice_id)->update([
            'invoice_note' => $request->invoice_note,
            'invoice_discount' => $request->invoice_discount,
            'invoice_grand_total' => $request->invoice_grand_total,
            'invoice_total' => $request->invoice_total,
            'vat_3_status' => $request->vat_3_status,
            'vat_type' => $request->vat_type,
            'invoice_vat_7' => $request->invoice_vat_7,
            'payment_type' => $request->payment_type,
            'payment_before_date' => $request->payment_before_date,
            'deposit' => $request->deposit,
            'updated_by' => Auth::user()->name,
        ]);
        $invoiceProduct->delete();
        // เพิ่มรายการใหม่
        if ($request->has('product_id')) {
            foreach ($request->product_id as $key => $product_id) {
                // ตรวจสอบค่าแต่ละค่าใน $request
                $product_id = $request->input('product_id.' . $key);
                $invoice_qty = $request->input('product_qty.' . $key);
                $invoice_price = $request->input('product_price.' . $key);
                $invoice_sum = $request->input('product_sum.' . $key);
                $expense_type = $request->input('expense.' . $key);

                $productModel = productModel::where('id',$product_id)->first();
                if ($product_id) {
                    // เพิ่มข้อมูลเข้าในตาราง
                    invoicePorductsModel::create([
                        'invoice_id' => $request->invoice_id,
                        'product_id' => $product_id,
                        'product_name' => $productModel->product_name,
                        'invoice_qty' => $invoice_qty,
                        'invoice_price' => $invoice_price,
                        'invoice_sum' => $invoice_sum,
                        'expense_type' => $expense_type,
                    ]);
                }
            }
        }
       
    }
}
