<?php

namespace App\Http\Controllers\payments;

use File;
use Illuminate\Http\Request;
use Spatie\FlareClient\View;
use App\Http\Controllers\Controller;
use App\Models\invoices\invoiceModel;
use App\Models\payments\paymentModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\quotations\quotationModel;

class paymentController extends Controller
{
    //

    public function index(invoiceModel $invoiceModel, Request $request)
    {
        $quotationModel = quotationModel::where('quote_number', $invoiceModel->quote_number)->first();

        return view('payments.index', compact('quotationModel', 'invoiceModel'));
    }

    public function quotation(quotationModel $quotationModel, Request $request)
    {
        return view('payments.quote-modal', compact('quotationModel'));
    }


    public function payment(Request $request)
    {
        // dd($request);
        $quote = quotationModel::where('quote_number', $request->payment_quote)->first();
        $paymentModel = paymentModel::create($request->all());
    
        // สร้างพาธที่ถูกต้องโดยการต่อพาธให้เป็นพาธสัมพันธ์ (relative path)
        $folderPath = 'public/storage/' . $quote->customer_id . '/' . $quote->quote_number;
       
        // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
        if (!File::exists(public_path($folderPath))) {
            \File::makeDirectory(public_path($folderPath), 0777, true);
        }
    
        $file = $request->file('payment_file');
        $path = null; // กำหนดค่าเริ่มต้นของ path เป็น null
        if ($file) {
            $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
            $uniqueName = $paymentModel->payment_id . '_' . $paymentModel->payment_quote . '_' . date('Ymd') . '.' . $extension;
            $payment_file_path = $quote->customer_id . '/' . $quote->quote_number.'/'.$uniqueName;
            // ใช้พาธสัมพันธ์ในการจัดเก็บไฟล์
            $path = $file->storeAs($folderPath, $uniqueName, 'public');
        }
    
        if ($path) {
            $paymentModel->update(['payment_file_path' => $payment_file_path]);
        }
    
        $checkPayment = paymentModel::where('payment_quote', $request->payment_quote)->get();

    
        // Loop จำนวนเงินทั้งหมดที่ชำระไปแล้ว
        $totalOld = $quote->payment !== NULL ? $quote->payment : 0;
        $total = $totalOld+$request->payment_total;
       
        $quotationModel = quotationModel::where('quote_number', $request->payment_quote)->update(['payment' => $total]);
    
        if($total >= $quote->quote_grand_total) {
           quotationModel::where('quote_number', $request->payment_quote)->update(['quote_status' => 'success']);
        }else{
          quotationModel::where('quote_number', $request->payment_quote)->update(['quote_status' => 'payment']);
        }

        return redirect()->back();
    }
    
}
