<?php

namespace App\Http\Controllers\invoices;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use App\Models\invoices\addDebtModel;
use App\Models\invoices\debtProductsModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\invoices\invoicePorductsModel;
use Illuminate\Support\Facades\Auth;

class addDebtController extends Controller
{
    // 
    public function create(Request $request)
    {
        $invoiceId = $request->invoiceID;
        $invoices = invoiceModel::where('invoice_id', $invoiceId)
            ->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')->first();

        $productIncome = productModel::get();
        $customer = customerModel::where('customer_id', $invoices->customer_id)->first();
        $sale = saleModel::where('id', $invoices->invoice_sale)->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->select('code', 'airline_id')->where('id', $invoices->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id', $tour->airline_id)->first();

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



        return view('invoices.addDebt.form-addDebt-add', compact('invoices', 'sale', 'productIncome', 'customer', 'tour', 'airline','invoiceProduct'));
    }



    // function Runnumber  เลขที่อ้างอิง
    public function generateRunningCodeDBN($debt)
    {
        $prefix = 'DB';
        $year = date('y'); // Last two digits of the current year
        $month = date('m'); // Current month
        // Extract the last 4 digits from the booking number
        $lastFourDigits = substr($debt, -4);
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
        // Get the latest debt record
        $debt = addDebtModel::latest()->first();

        // Check if there's an existing debt record
        if ($debt) {
            $last_debt_number = $debt->debt_number;
        } else {
            // If no debt records exist, start the sequence with a base number
            $last_debt_number = 'DB' . date('y') . date('m') . '0000';
        }

        // Generate a new running code using the last debt number
        $new_debt_number = $this->generateRunningCodeDBN($last_debt_number);

        // Merge the new debt number into the request data
        $request->merge(['debt_number' => $new_debt_number]);
        $request->merge(['debt_status' => 'wait']);
        $request->merge(['debt_date' => date('Y-m-d')]);
        $request->merge(['created_by' => Auth::user()->name]);

        // Create a new debt record with the request data
        $debtStore = addDebtModel::create($request->all());

         // เพิ่มรายการใหม่
         if ($request->has('product_id')) {
            foreach ($request->product_id as $key => $product_id) {
                // ตรวจสอบค่าแต่ละค่าใน $request
                $product_id = $request->input('product_id.' . $key);
                $debt_qty = $request->input('product_qty.' . $key);
                $debt_price = $request->input('product_price.' . $key);
                $debt_sum = $request->input('product_sum.' . $key);
                $expense_type = $request->input('expense.' . $key);

                $productModel = productModel::where('id',$product_id)->first();

                if ($product_id) {
                    // เพิ่มข้อมูลเข้าในตาราง
                    debtProductsModel::create([
                        'debt_id' => $debtStore->debt_id,
                        'product_id' => $product_id,
                        'product_name' => $productModel->product_name,
                        'debt_qty' => $debt_qty,
                        'debt_price' => $debt_price,
                        'debt_sum' => $debt_sum,
                        'expense_type' => $expense_type,
                    ]);
                }
            }
        }


    }
}
