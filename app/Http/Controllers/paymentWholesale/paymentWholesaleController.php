<?php

namespace App\Http\Controllers\paymentWholesale;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\payments\paymentModel;
use App\Models\quotations\quotationModel;
use App\Models\payments\paymentWholesaleModel;
use Illuminate\Support\Facades\Auth;

class paymentWholesaleController extends Controller
{
    //

    // function Runnumber paymentWholesale
    public function generateRunningCodeWS()
    {
        $code = paymentWholesaleModel::latest()->first();
        if (!empty($code)) {
            $codeNumber = $code->payment_wholesale_number;
        } else {
            $codeNumber = 'WS' . date('y') . date('m'). '0000';
        }
        $prefix = 'WS';
        $year = date('y');
        $month = date('m');
        $lastFourDigits = substr($codeNumber, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month . $newNumber;
        return $runningCode;
    }


    public function index(quotationModel $quotationModel, Request $request)
    {
        $paymentWholesale = paymentWholesaleModel::where('payment_wholesale_doc',$quotationModel->quote_number)->get();
        return view('paymentWholesale.index',compact('quotationModel','paymentWholesale'));
    }
    
    public function store(Request $request)
    {
    $quote = quotationModel::where('quote_number', $request->payment_wholesale_doc)->first();
        // สร้างพาธที่ถูกต้อง
    $folderPath = 'public/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number;
    $absolutePath = storage_path('app/' . $folderPath);

    // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
    if (!File::exists($absolutePath)) {
        File::makeDirectory($absolutePath, 0775, true);
    }

    $file = $request->file('file');

    if ($file) {
        // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
        $extension = $file->getClientOriginalExtension();
        $uniqueName = $this->generateRunningCodeWS() . date('Ymd') . '.' . $extension;

        // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
        $file->move($absolutePath, $uniqueName);

        // สร้างพาธสัมพันธ์เพื่อจัดเก็บไฟล์ในฐานข้อมูล
        $filePath = 'storage/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number . '/' . $uniqueName;

        // อัปเดตข้อมูลใน request เพื่อเก็บในฐานข้อมูล
        $request->merge([
            'payment_wholesale_number' => $this->generateRunningCodeWS(),
            'payment_wholesale_file_name' => $uniqueName,
            'payment_wholesale_file_path' => $filePath,
            'created_by' => Auth::user()->name
        ]);
    }
        $paymentWholesale =  paymentWholesaleModel::create($request->all());
      
        return redirect()->back();
    }



    
public function delete(paymentWholesaleModel $paymentWholesaleModel)
{
    // เช็คว่าไฟล์มีอยู่หรือไม่ ถ้ามีจะลบไฟล์
    if (File::exists($paymentWholesaleModel->payment_wholesale_file_path)) {
        File::delete($paymentWholesaleModel->payment_wholesale_file_path); // ลบไฟล์
    }
    // ลบเรคคอร์ดออกจากฐานข้อมูล 
    $paymentWholesaleModel->delete();
    return redirect()->back()->with('success', 'ไฟล์ถูกลบเรียบร้อยแล้ว');
}

public function quote(quotationModel $quotationModel)
{
    return view('paymentWholesale.modal-quote',compact('quotationModel'));
}

}