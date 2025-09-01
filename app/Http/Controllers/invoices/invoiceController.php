<?php

namespace App\Http\Controllers\invoices;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Models\mumday\numDayModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use Illuminate\Support\Facades\Storage;
use App\Models\invoices\taxinvoiceModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;
use App\Models\invoices\invoicePorductsModel;
use App\Http\Controllers\quotations\quoteController;

class invoiceController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');

    }




   public function generateRunningCodeIVS()
{
    $prefix = 'IVN';
    $year   = date('y');
    $month  = date('m');

    // หา invoice ล่าสุดของเดือน/ปีปัจจุบัน
    $invoice = invoiceModel::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', date('m'))
        ->latest('invoice_id')
        ->first();

    if ($invoice) {
        $lastFourDigits = substr($invoice->invoice_number, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
    } else {
        $incrementedNumber = 1;
    }

    $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
    return $prefix . $year . $month . '-' . $newNumber;
}




    public function create(quotationModel $quotationModel, Request $request)
    {

        $bookingModel = bookingModel::where('code', $quotationModel->quote_booking)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $products = productModel::where('product_type', '!=', 'discount')->get();
        $productDiscount = productModel::where('product_type', 'discount')->get();
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'income')->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'discount')->get();
        $campaignSource = DB::table('campaign_source')->get();


        return view('invoices.modal-create', compact('campaignSource', 'customer', 'quoteProducts', 'quotationModel', 'sales', 'country', 'airline', 'numDays', 'wholesale', 'products', 'productDiscount', 'quoteProductsDiscount'));
    }

    public function store(Request $request)
    {
        //dd($request);
        $runningCode = $this->generateRunningCodeIVS();
        $request->merge([
            'invoice_sale' => $request->quote_sale,
            'taxinvoice_date' => date('Y-m-d'),
            'invoice_number' => $runningCode,
            'invoice_status' => 'wait',
            'invoice_withholding_tax_status' => isset($request->invoice_withholding_tax_status) ? 'Y' : 'N',
        ]);

        if ($request->customer_id) {
            customerModel::where('customer_id', $request->customer_id)->update([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'customer_texid' => $request->customer_texid,
                'customer_tel' => $request->customer_tel,
                'customer_fax' => $request->customer_fax,
                'customer_date' => $request->customer_date,
                'customer_campaign_source' => $request->customer_campaign_source,
            ]);
        }

        $request->merge(['created_by' => Auth::user()->name]);

        $invoice = invoiceModel::create($request->all());
        quotationModel::where('quote_id', $invoice->invoice_quote_id)->update(['quote_status' => 'invoice']);

        // Create product lits
        foreach ($request->product_id as $key => $product) {

            if ($request->product_id[$key]) {
                $productName = productModel::where('id', $request->product_id[$key])->first();
                invoicePorductsModel::create([
                    'invoice_id' => $invoice->invoice_id,
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


    public function edit(invoiceModel $invoiceModel, Request $request)
    {

        $quotationModel = quotationModel::where('quote_id', $invoiceModel->invoice_quote_id)->first();
        $bookingModel = bookingModel::where('code', $quotationModel->quote_booking)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $products = productModel::where('product_type', '!=', 'discount')->get();
        $productDiscount = productModel::where('product_type', 'discount')->get();
        $invoiceProducts = invoicePorductsModel::where('invoice_id', $invoiceModel->invoice_id)->where('expense_type', 'income')->get();
        $invoiceProductsDiscount = invoicePorductsModel::where('invoice_id', $invoiceModel->invoice_id)->where('expense_type', 'discount')->get();
        $campaignSource = DB::table('campaign_source')->get();
        $mode = $request->get('mode', 'view'); // ค่าเริ่มต้นเป็น 'view'
        $taxinvoice = taxinvoiceModel::where('invoice_number',$invoiceModel->invoice_number)->first();
        return view('invoices.modal-edit', compact('mode', 'invoiceModel', 'campaignSource','taxinvoice','customer', 'invoiceProducts', 'quotationModel', 'sales', 'country', 'airline', 'numDays', 'wholesale', 'products', 'productDiscount', 'invoiceProductsDiscount'));
    }

    public function update(invoiceModel $invoiceModel, Request $request)
    {
        //dd($request);

        if ($request->customer_id) {
            customerModel::where('customer_id', $request->customer_id)->update([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'customer_texid' => $request->customer_texid,
                'customer_tel' => $request->customer_tel,
                'customer_fax' => $request->customer_fax,
                'customer_date' => $request->customer_date,
                'customer_campaign_source' => $request->customer_campaign_source,
            ]);
        }
         taxinvoiceModel::where('invoice_number',$invoiceModel->invoice_number)->update([
            'taxinvoice_date' => $request->taxinvoice_date,
        ]);


        $request->merge([
            'invoice_withholding_tax_status' => isset($request->invoice_withholding_tax_status) ? 'Y' : 'N',
            'updated_by' => Auth::user()->name,
            'revised' => true,
            'revision_date' => now(),
            'revision_reason' => $request->revision_reason ?? 'แก้ไขข้อมูลใบแจ้งหนี้'
        ]);
        $invoiceModel->update($request->all());

        // delete product lits Old
        invoicePorductsModel::where('invoice_id', $invoiceModel->invoice_id)->delete();
        foreach ($request->product_id as $key => $value) {
            if ($request->product_id[$key]) {
                $product = productModel::where('id', $request->product_id[$key])->first();
                $request->merge(['vat' => isset($request->non_vat[$key]) ? 'Y' : 'N']);
                invoicePorductsModel::create([
                    'invoice_id' => $invoiceModel->invoice_id,
                    'product_id' => $request->product_id[$key],
                    'product_name' => $product->product_name,
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

    public function cancel(Request $request, invoiceModel $invoiceModel)
    {
        $invoiceModel->update(['invoice_cancel_note' => $request->invoice_cancel_note, 'invoice_status' => 'cancel']);
        return redirect()->back();
    }

    public function modalCancel(invoiceModel $invoiceModel)
    {
        return view('invoices.modal-cancel', compact('invoiceModel'));
    }





    public function uploadInvoiceImage(Request $request)
{
    try {
        // รับข้อมูล invoice_id และ invoice_file จาก JSON
        $invoiceId = $request->input('invoice_id');
        $base64File = $request->input('invoice_file');

        // ตรวจสอบว่า invoice_id และไฟล์ถูกส่งมาครบถ้วน
        if (!$invoiceId || !$base64File) {
            return response()->json(['message' => 'Invoice ID or file is missing'], 400);
        }

        // ค้นหา invoice โดยใช้ invoice_id
        $invoice = InvoiceModel::find($invoiceId);
        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        // ถอดรหัส Base64 และบันทึกไฟล์
        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File));
        $filePath = 'invoices/' . uniqid() . '.png';

        // บันทึกไฟล์ลง storage
        Storage::disk('public')->put($filePath, $fileData);

        // บันทึกข้อมูลเส้นทางไฟล์ลงในฐานข้อมูล
        $invoice->invoice_image = $filePath;
        $invoice->save();

        return response()->json([
            'message' => 'File uploaded successfully',
            'path' => asset('storage/' . $filePath),
        ], 200);

    } catch (\Exception $e) {
        // ส่งข้อผิดพลาดกลับไปยังผู้ใช้หากเกิดปัญหา
        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}


    

public function deleteInvoiceImage(Request $request)
{
    try {
        // รับค่า invoice_id
        $invoiceId = $request->input('invoice_id');
        
        // ค้นหา invoice โดยใช้ invoice_id
        $invoice = InvoiceModel::find($invoiceId);
        if (!$invoice || !$invoice->invoice_image) {
            return response()->json(['message' => 'Invoice or file not found'], 404);
        }

        // ลบไฟล์จาก storage
        $filePath = 'public/' . $invoice->invoice_image;
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        // อัปเดตข้อมูลในฐานข้อมูล
        $invoice->invoice_image = null;
        $invoice->save();

        return response()->json(['message' => 'File deleted successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}

public function markAsRevised(invoiceModel $invoiceModel, Request $request)
    {
        $invoiceModel->update([
            'revised' => true,
            'revision_reason' => $request->revision_reason ?? 'แก้ไขข้อมูลใบแจ้งหนี้',
            'revision_date' => now(),
            'updated_by' => Auth::user()->name
        ]);
        
        return response()->json(['success' => true, 'message' => 'ทำเครื่องหมายเป็น Revised แล้ว']);
    }

    public function unmarkRevised(invoiceModel $invoiceModel)
    {
        $invoiceModel->update([
            'revised' => false,
            'revision_reason' => null,
            'revision_date' => null,
            'updated_by' => Auth::user()->name
        ]);
        
        return response()->json(['success' => true, 'message' => 'ยกเลิกการทำเครื่องหมาย Revised แล้ว']);
    }

    public function delete(invoiceModel $invoiceModel)
    {
        // ลบข้อมูลที่เกี่ยวข้องกับ invoice นี้
        invoicePorductsModel::where('invoice_id', $invoiceModel->invoice_id)->delete();
        taxinvoiceModel::where('invoice_id', $invoiceModel->invoice_id)->delete();
        $invoiceModel->delete();

        return redirect()->back()->with('success', 'ลบใบแจ้งหนี้เรียบร้อยแล้ว');
    }

}
