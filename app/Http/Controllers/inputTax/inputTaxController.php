<?php

namespace App\Http\Controllers\inputTax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Models\inputTax\inputTaxModel;
use App\Models\quotations\quotationModel;
use App\Http\Controllers\uploadfiles\uploadfileQuoteController;
use App\Models\invoices\invoiceModel;
use App\Models\invoices\taxinvoiceModel;

class inputTaxController extends Controller
{
    //

    public function createWholesale(quotationModel $quotationModel)
    {
        return view('inputTax.modal-create', compact('quotationModel'));
    }

    public function editWholesale(inputTaxModel $inputTaxModel)
    {
        $quotationModel = quotationModel::where('quote_id', $inputTaxModel->input_tax_quote_id)->first();
        return view('inputTax.modal-edit', compact('inputTaxModel', 'quotationModel'));
    }
   
    public function cancelWholesale(inputTaxModel $inputTaxModel)
    {
        $quotationModel = quotationModel::where('quote_id', $inputTaxModel->input_tax_quote_id)->first();
        return view('inputTax.modal-cancel', compact('inputTaxModel', 'quotationModel'));
    }

    public function  updateCancel(Request $request, inputTaxModel $inputTaxModel)
    {
        $inputTaxModel->update(['input_tax_status' => 'cancel','input_tax_cancel' => $request->input_tax_cancel]);
        return redirect()->back();
    }

    public function update(Request $request, inputTaxModel $inputTaxModel)
    {
        $fileUploadController = new uploadfileQuoteController();
        $filePath = $fileUploadController->uploadFile($request, $request->input_tax_quote_number, $request->customer_id);
        $requestData = $request->all();
        if ($filePath) {
            // เช็คว่าไฟล์มีอยู่หรือไม่ ถ้ามีจะลบไฟล์
            if (File::exists('storage/'.$inputTaxModel->input_tax_file)) {
                File::delete('storage/'.$inputTaxModel->input_tax_file); // ลบไฟล์
            }

            $requestData['input_tax_file'] = $filePath;
        }
        $requestData['updated_by'] = Auth::user()->name;
        // สร้างข้อมูลใหม่ใน inputTaxModel
        $inputTaxModel->update($requestData);

        return redirect()->back();
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
        $invoiceModel = invoiceModel::where('invoice_quote_id',$quotationModel->quote_id)->get();
        $inputTax = inputTaxModel::where('input_tax_quote_id', $quotationModel->quote_id)->get();
        return View::make('inputTax.inputtax-table', compact('quotationModel', 'inputTax','invoiceModel'))->render();
    }
}
