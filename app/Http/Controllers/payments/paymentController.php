<?php

namespace App\Http\Controllers\payments;


use Illuminate\Http\Request;
use Spatie\FlareClient\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
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
        // ตรวจสอบข้อมูลที่รับมาจาก request
        $quote = quotationModel::where('quote_number', $request->payment_doc_number)->first();
        $paymentModel = paymentModel::create($request->all());
    
        // สร้างพาธที่ถูกต้อง
        $folderPath = 'public/' . $quote->customer_id . '/' . $quote->quote_number;
        $absolutePath = storage_path('app/' . $folderPath);
    
        // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0775, true);
        }
    
        $file = $request->file('payment_file');
    
        if ($file) {
            $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
            $uniqueName = $paymentModel->payment_id . '_' . $paymentModel->payment_doc_number . '_' . date('Ymd') . '.' . $extension;
            $filePath = $absolutePath . '/' . $uniqueName;
            
            // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
            $file->move($absolutePath, $uniqueName);
    
            // อัปเดตพาธไฟล์ในฐานข้อมูล
            $paymentModel->update(['payment_file_path' => $folderPath . '/' . $uniqueName]);
        }
    
        // การจัดการการชำระเงิน
        $totalOld = $quote->payment !== NULL ? $quote->payment : 0;
        $total = $totalOld + $request->payment_total;
        quotationModel::where('quote_number', $request->payment_doc_number)->update(['payment' => $total]);
    
        // การอัปเดตสถานะของใบเสนอราคา
        if ($total >= $quote->quote_grand_total) {
            quotationModel::where('quote_number', $request->payment_doc_number)->update(['quote_status' => 'success']);
        } else {
            quotationModel::where('quote_number', $request->payment_doc_number)->update(['quote_status' => 'payment']);
        }
    
        return redirect()->back()->with('success', 'Payment processed successfully.');
    }
    
    
    
}