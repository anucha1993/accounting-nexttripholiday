<?php

namespace App\Http\Controllers\inputTax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\inputTax\inputTaxModel;
use App\Models\quotations\quotationModel;
use App\Http\Controllers\uploadfiles\uploadfileQuoteController;

class inputTaxController extends Controller
{
    //

    public function createWholesale(quotationModel $quotationModel)
    {
       return view('inputTax.modal-create',compact('quotationModel'));
    }
    public function store(Request $request)
    {
        // เรียกใช้งาน uploadFile จาก uploadfileQuoteController
        $fileUploadController = new uploadfileQuoteController();
    
        // อัปโหลดไฟล์โดยเรียกใช้งานฟังก์ชัน uploadFile
        $filePath = $fileUploadController->uploadFile($request, $request->input_tax_quote_number, $request->customer_id);
    
        // รวมข้อมูลเพิ่มเติมเข้ากับ request
        $requestData = $request->all();
    
        if ($filePath) {
            // ถ้ามีไฟล์อัปโหลด ให้เพิ่มพาธไฟล์เข้าไปในข้อมูล
            $requestData['input_tax_file'] = $filePath;
        }
    
        // เพิ่มข้อมูลผู้สร้าง
        $requestData['created_by'] = Auth::user()->name;
    
        // สร้างข้อมูลใหม่ใน inputTaxModel
        inputTaxModel::create($requestData);
    
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }


    public function table(quotationModel $quotationModel)
    {
        $inputTax = inputTaxModel::where('input_tax_quote_id',$quotationModel->quote_id)->get();
        return View::make('inputTax.inputtax-table', compact('quotationModel','inputTax'))->render();
    }
}
