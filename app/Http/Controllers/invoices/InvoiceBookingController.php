<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\invoices\invoiceModel;
use App\Models\invoices\invoicePorductsModel;
use App\Models\products\productModel;
use Illuminate\Http\Request;

class InvoiceBookingController extends Controller
{
    //

    public function index(Request $request)
    {
        $invoiceId = $request->invoiceID;
        $invoices = invoiceModel::where('invoice_id', $invoiceId)->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')->get();
        return view('invoices.table-invoice', compact('invoices'));
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
                // product
                'products.product_name'
            )
            ->leftjoin('products', 'products.id', 'invoice_product.product_id')
            ->get();
        $productIncome = productModel::where('product_type', 'income')->get();
        return view('invoices.form-invoice-edit', compact('invoices', 'invoiceProduct', 'productIncome'));
    }

    public function update(Request $request)
    {
        return response()->json($request);
    }
}
