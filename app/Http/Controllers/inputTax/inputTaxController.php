<?php

namespace App\Http\Controllers\inputTax;

use Illuminate\Http\Request;
use App\Models\QuoteLogModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Models\invoices\invoiceModel;
use App\Services\NotificationService;
use App\Models\inputTax\inputTaxModel;
use Illuminate\Support\Facades\Storage;
use App\Models\invoices\taxinvoiceModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\withholding\WithholdingTaxItem;
use App\Models\withholding\WithholdingTaxDocument;
use App\Http\Controllers\uploadfiles\uploadfileQuoteController;

class inputTaxController extends Controller
{
    //

    public function createWholesale(quotationModel $quotationModel)
    {
        $invoice = [];
        $taxinvoice = [];
        $wholesale = wholesaleModel::get();
        $invoice = invoiceModel::where('invoice_quote_id',$quotationModel->quote_id)->first();
       
        if($invoice){
            $taxinvoice = taxinvoiceModel::where('invoice_id',$invoice->invoice_id)->first();
        }
        $document = WithholdingTaxDocument::where('quote_id',$quotationModel->quote_id)->first();
        return view('inputTax.modal-create', compact('quotationModel', 'wholesale','taxinvoice','document'));
    }


    public function inputtaxCreateWholesale(quotationModel $quotationModel)
    {
        $wholesale = wholesaleModel::get();
        return view('inputTax.modal-create-wholesale', compact('quotationModel', 'wholesale'));
    }

    public function editWholesale(inputTaxModel $inputTaxModel)
    {
       
        $wholesale = wholesaleModel::get();
        $quotationModel = quotationModel::where('quote_id', $inputTaxModel->input_tax_quote_id)->first();
        $document = WithholdingTaxDocument::where('quote_id',$quotationModel->quote_id)->first();
        return view('inputTax.modal-edit', compact('inputTaxModel', 'quotationModel', 'wholesale','document'));
    }
    

    public function inputtaxEditWholesale(inputTaxModel $inputTaxModel)
    {
        $wholesale = wholesaleModel::get();
        $quotationModel = quotationModel::where('quote_id', $inputTaxModel->input_tax_quote_id)->first();
        return view('inputTax.modal-edit-wholesale', compact('inputTaxModel', 'quotationModel', 'wholesale'));
    }

    public function cancelWholesale(inputTaxModel $inputTaxModel)
    {
        $quotationModel = quotationModel::where('quote_id', $inputTaxModel->input_tax_quote_id)->first();
        return view('inputTax.modal-cancel', compact('inputTaxModel', 'quotationModel'));
    }

    public function  updateCancel(Request $request, inputTaxModel $inputTaxModel)
    {
        $inputTaxModel->update(['input_tax_status' => 'cancel', 'input_tax_cancel' => $request->input_tax_cancel]);
        return redirect()->back();
    }

    public function deletefile(Request $request, inputTaxModel $inputTaxModel)
    {
        // if ($inputTaxModel->input_tax_file && File::exists('storage/' . $inputTaxModel->input_tax_file)) {
        //     File::delete('storage/' . $inputTaxModel->input_tax_file); // ลบไฟล์
        //     $inputTaxModel->update(['input_tax_file' => NULL]);
        // }

        if($inputTaxModel->input_tax_file && storage_path('app/' . $inputTaxModel->input_tax_file))
        {
            Storage::delete('app/' . $inputTaxModel->input_tax_file);
            $inputTaxModel->update(['input_tax_file' => NULL]);
        }

        return redirect()->back();
    }


    public function update(Request $request, inputTaxModel $inputTaxModel)
    {
		//dd($request->all());
		
        $requestData = $request->all();
         $quotationModel = quotationModel::where('quote_id', $inputTaxModel->input_tax_quote_id)->first();
       // dd($requestData);
		

        // ตรวจสอบว่าเลือก "ลบไฟล์แนบ" หรือไม่
        if ($request->has('delete_file') && $request->delete_file === 'Y') {
            // ลบไฟล์แนบถ้ามีอยู่
            if ($inputTaxModel->input_tax_file && File::exists('storage/' . $inputTaxModel->input_tax_file)) {
                File::delete('storage/' . $inputTaxModel->input_tax_file); // ลบไฟล์
            }

            // ตั้งค่า input_tax_file เป็น NULL
            $requestData['input_tax_file'] = null;
			
        } else {
            // อัปโหลดไฟล์ใหม่ (ถ้ามีการอัปโหลด)
            $fileUploadController = new uploadfileQuoteController();
            $filePath = $fileUploadController->uploadFile($request, $request->input_tax_quote_number, $request->customer_id);

            if ($filePath) {
                // ลบไฟล์เก่าถ้ามีอยู่
                if ($inputTaxModel->input_tax_file && File::exists('storage/' . $inputTaxModel->input_tax_file)) {
                    File::delete('storage/' . $inputTaxModel->input_tax_file);
                }

                $requestData['input_tax_file'] = $filePath;
            }
        }


        // ถ้ามีการแนบไฟล์ต้นทุนโฮลเซล
    if ($request->hasFile('input_tax_file')) {
       
        $notificationService = new NotificationService();
        
        // สร้าง URL สำหรับลิงก์ในการแจ้งเตือน
        $quoteUrl = route('quote.editNew', $quotationModel->quote_id);
        
        // ข้อความแจ้งเตือน
        $wholesaleName = $quotationModel->quoteWholesale ? $quotationModel->quoteWholesale->wholesale_name_th : 'ไม่ระบุ';
        //$amount = number_format($request->input_tax_service_total, 2);
        $amount = number_format($quotationModel->inputtaxTotalWholesale(), 2);
        $quoteNumber = $quotationModel->quote_number;
        $saleName = $quotationModel->Salename->name;

        // 1. แจ้งบัญชีว่ามีการแนบไฟล์ต้นทุนโฮลเซล
        $msgAcc = "มีการแนบเอกสารต้นทุนโฮลเซล {$wholesaleName} จำนวนเงิน: {$amount} บาท ".
                 "เลขที่ใบเสนอราคา #{$quoteNumber} | Sale: {$saleName}";
        $notificationService->sendToAccounting(
            $msgAcc, 
            $quoteUrl, 
            $quotationModel->quote_id, 
            'wholesale-cost'
        );
    }

    // ถ้ามีการแก้ไขไฟล์ต้นทุนโฮลเซล
    if ($requestData) {
        $notificationService = new NotificationService();
        
        $quoteUrl = route('quote.editNew', $quotationModel->quote_id);
        
        // ข้อความแจ้งเตือนการแก้ไข
        $wholesaleName = $quotationModel->quoteWholesale ? $quotationModel->quoteWholesale->wholesale_name_th : 'ไม่ระบุ';
        $amount = number_format($quotationModel->inputtaxTotalWholesale(), 2);
        $quoteNumber = $quotationModel->quote_number;
        $saleName = $quotationModel->Salename->name;
        
        $msgAcc = "มีการแก้ไขเอกสารต้นทุนโฮลเซล {$wholesaleName} จำนวนเงิน: {$amount} บาท ".
                 "เลขที่ใบเสนอราคา #{$quoteNumber} | Sale: {$saleName}";
        
        $notificationService->sendToAccounting(
            $msgAcc, 
            $quoteUrl, 
            $quotationModel->quote_id, 
            'wholesale-cost-update'
        );
    }


        // อัปเดตข้อมูลเพิ่มเติม
        $requestData['updated_by'] = Auth::user()->name;
        
        // ใช้ค่า input_tax_grand_total ที่ผู้ใช้กรอกมาจากฟอร์ม
        // (อาจเป็นค่าที่คำนวณอัตโนมัติหรือแก้ไขเองก็ได้)
        if ($request->has('input_tax_grand_total')) {
            $requestData['input_tax_grand_total'] = (float) $request->input_tax_grand_total;
        }
        
        // บันทึกการเปลี่ยนแปลงในฐานข้อมูล
        $inputTaxModel->update($requestData);
        
        $serviceTotal = 0;
        $serviceTotal = $request->input_tax_service_total * 0.03;

        if($request->document_id){
            WithholdingTaxItem::where('document_id',$request->document_id)->delete();
            WithholdingTaxItem::create([
                'document_id' => $request->document_id,
                'income_type' => 'ค่าบริการ '. $request->input_tax_ref,
                'tax_rate' => 3.00,
                'amount' => $request->input_tax_service_total,
                'withholding_tax' => $serviceTotal,
            ]);

        }
        //สร้างใบหัก ณ ที่จ่าย
        if($request->input_tax_withholding_status === 'Y'){

            $serviceTotal = 0;
            $withholdingTotal = 0;
    
            $serviceTotal = $request->input_tax_service_total * 0.03;
            $withholdingTotal = $request->input_tax_service_total -  $serviceTotal;
            // สร้างรหัสเอกสารใหม่ โดยส่ง document_date ไป
            $documentNumber = WithholdingTaxDocument::generateDocumentNumber($request->input_tax_date);
            $documentNumberNo = WithholdingTaxDocument::generateDocumentNumberNo();
    
            // บันทึกเอกสาร
            $document = WithholdingTaxDocument::create([
                'quote_id' => $request->input_tax_quote_id, // เพิ่มฟิลด์นี้
                'customer_id' => $request->customer_id,
                'wholesale_id' => $request->input_tax_wholesale,
                'document_number' => $documentNumber, // เพิ่มฟิลด์นี้
                'withholding_branch' => 'สำนักงานใหญ่', // เพิ่มฟิลด์นี้
                'ref_input_tax' => $inputTaxModel->input_tax_id,
                'document_date' => $request->input_tax_date,
                'document_doc_date' => $request->input_tax_date_doc,
                'ref_number' => $request->input_tax_ref,
                'withholding_form' => 'ภ.ง.ด.53',
                // ค่าที่คำนวณได้
                'total_amount' => $request->input_tax_service_total,
                'total_withholding_tax' => $request->input_tax_withholding,
                'total_payable' => $withholdingTotal,
                'image_signture_id' => 1,
                'book_no' => date('Y-m'),
                'document_no' => $documentNumberNo
            ]);
    
            WithholdingTaxItem::create([
                'document_id' => $document->id,
                'income_type' => 'ค่าบริการ '. $request->input_tax_ref,
                'tax_rate' => 3.00,
                'amount' => $request->input_tax_service_total,
                'withholding_tax' => $serviceTotal,
            ]);
    
            // บันทึก Check List 
            $QuoteLog = QuoteLogModel::where('quote_id',$request->input_tax_quote_id)->first();
            if($QuoteLog){
                $QuoteLog->update([
                    "withholding_tax_status" => 'ออกแล้ว',
                    "withholding_tax_updated_at" => now(),
                    "withholding_tax_created_by" => Auth::user()->name
                ]);
            }else{
                $checkList = QuoteLogModel::create([
                    'quote_id' => $request->input_tax_quote_id,
                    "withholding_tax_status" => 'ออกแล้ว',
                    "withholding_tax_updated_at" => now(),
                    "withholding_tax_created_by" => Auth::user()->name
                ]);
            }
    
           }
           
        return redirect()->back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }

    public function store(Request $request)
    {
        //dd($request);
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
        
        // คำนวณ input_tax_grand_total ให้ถูกต้อง
        $serviceTotal = (float) ($request->input_tax_service_total ?? 0);
        $withholding = (float) ($request->input_tax_withholding ?? 0);
        $vat = (float) ($request->input_tax_vat ?? 0);
        
        // ตรวจสอบว่ามีไฟล์แนบหรือไม่
        $hasFile = !empty($filePath);
        
        // ถ้ามีไฟล์: VAT - Withholding, ถ้าไม่มี: VAT + Withholding
        $requestData['input_tax_grand_total'] = $hasFile ? ($vat - $withholding) : ($vat + $withholding);
        
        // สร้างข้อมูลใหม่ใน inputTaxModel
       $inputTaxModel =  inputTaxModel::create($requestData);

       // เพิ่มการส่ง notification เมื่อมีการสร้างต้นทุนใหม่
       $quotationModel = quotationModel::where('quote_id', $request->input_tax_quote_id)->first();
       $notificationService = new NotificationService();
       
       // สร้าง URL สำหรับลิงก์ในการแจ้งเตือน
       $quoteUrl = route('quote.editNew', $quotationModel->quote_id);
       
       // ข้อความแจ้งเตือน
       $wholesaleName = $quotationModel->quoteWholesale ? $quotationModel->quoteWholesale->wholesale_name_th : 'ไม่ระบุ';
       $amount = number_format($quotationModel->inputtaxTotalWholesale(), 2);
       $quoteNumber = $quotationModel->quote_number;
       $saleName = $quotationModel->Salename->name;

       // ถ้ามีการแนบไฟล์ต้นทุนโฮลเซล
       if ($request->hasFile('input_tax_file')) {
           // แจ้งบัญชีว่ามีการเพิ่มเอกสารต้นทุนโฮลเซลใหม่ (พร้อมไฟล์)
           $msgAcc = "มีการเพิ่มเอกสารต้นทุนโฮลเซล {$wholesaleName} จำนวนเงิน: {$amount} บาท ".
                    "เลขที่ใบเสนอราคา #{$quoteNumber} | Sale: {$saleName}";
           $notificationService->sendToAccounting(
               $msgAcc, 
               $quoteUrl, 
               $quotationModel->quote_id, 
               'wholesale-cost'
           );
       } else {
           // แจ้งบัญชีว่ามีการเพิ่มข้อมูลต้นทุนโฮลเซลใหม่ (ไม่มีไฟล์แนบ)
           $msgAcc = "มีการเพิ่มข้อมูลต้นทุนโฮลเซล {$wholesaleName} จำนวนเงิน: {$amount} บาท ".
                    "เลขที่ใบเสนอราคา #{$quoteNumber} | Sale: {$saleName} (ยังไม่มีไฟล์แนบ)";
           $notificationService->sendToAccounting(
               $msgAcc, 
               $quoteUrl, 
               $quotationModel->quote_id, 
               'wholesale-cost-created'
           );
       }

       $quoteLog = QuoteLogModel::where('quote_id', $request->input_tax_quote_id)->first();

       if ($request->input_tax_type === '2') {
           if (!$quoteLog) {
               // Create a new record if no existing record is found
               $quoteLog = QuoteLogModel::create([
                   'quote_id' => $request->input_tax_quote_id,
                   'invoice_status' => 'ได้แล้ว',
                   'invoice_updated_at' => now(),
                   'invoice_created_by' => Auth::user()->name,
               ]);
           } else {
               // Update the existing record
               $quoteLog->update([
                   'invoice_status' => 'ได้แล้ว',
                   'invoice_updated_at' => now(),
                   'invoice_created_by' => Auth::user()->name,
               ]);
           }
       }

       if($request->input_tax_withholding_status === 'Y'){

        $serviceTotal = 0;
        $withholdingTotal = 0;

        $serviceTotal = $request->input_tax_service_total * 0.03;
        $withholdingTotal = $request->input_tax_service_total -  $serviceTotal;
        // สร้างรหัสเอกสารใหม่ โดยส่ง document_date ไป
        $documentNumber = WithholdingTaxDocument::generateDocumentNumber($request->input_tax_date);
        $documentNumberNo = WithholdingTaxDocument::generateDocumentNumberNo();

        // บันทึกเอกสาร
        $document = WithholdingTaxDocument::create([
            'quote_id' => $request->input_tax_quote_id, // เพิ่มฟิลด์นี้
            'customer_id' => $request->customer_id,
            'wholesale_id' => $request->input_tax_wholesale,
            'document_number' => $documentNumber, // เพิ่มฟิลด์นี้
            'withholding_branch' => 'สำนักงานใหญ่', // เพิ่มฟิลด์นี้
            'ref_input_tax' => $inputTaxModel->input_tax_id,
            // 'withholding_note' => $request->withholding_note, // เพิ่มฟิลด์นี้
            // 'customer_id' => $request->customer_id,
            'document_date' => $request->input_tax_date,
            'document_doc_date' => $request->input_tax_date_doc,
            'ref_number' => $request->input_tax_ref,
            'withholding_form' => 'ภ.ง.ด.53',
            // ค่าที่คำนวณได้
            'total_amount' => $request->input_tax_service_total,
            'total_withholding_tax' => $request->input_tax_withholding,
            'total_payable' => $withholdingTotal,
            'image_signture_id' => 1,
            'book_no' => date('Y-m'),
            'document_no' => $documentNumberNo
        ]);

        WithholdingTaxItem::create([
            'document_id' => $document->id,
            'income_type' => 'ค่าบริการ '. $request->input_tax_ref,
            'tax_rate' => 3.00,
            'amount' => $request->input_tax_service_total,
            'withholding_tax' => $serviceTotal,
        ]);


        // บันทึก Check List 
        $QuoteLog = QuoteLogModel::where('quote_id',$request->input_tax_quote_id)->first();
        if($QuoteLog){
            $QuoteLog->update([
                "withholding_tax_status" => 'ออกแล้ว',
                "withholding_tax_updated_at" => now(),
                "withholding_tax_created_by" => Auth::user()->name
            ]);
        }else{
            $checkList = QuoteLogModel::create([
                'quote_id' => $request->input_tax_quote_id,
                "withholding_tax_status" => 'ออกแล้ว',
                "withholding_tax_updated_at" => now(),
                "withholding_tax_created_by" => Auth::user()->name
            ]);
        }

       }
       
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }


    // public function uploadfile()
    // {

    // }

    public function table(quotationModel $quotationModel)

    {
        $document = WithholdingTaxDocument::where('quote_id',$quotationModel->quote_id)->first();
        $invoice = invoiceModel::where('invoice_quote_id', $quotationModel->quote_id)->first();
        if ($invoice) {
            $invoiceModel = taxinvoiceModel::select('invoices.*')
                ->where('taxinvoices.invoice_id', $invoice->invoice_id)
                ->leftjoin('invoices', 'invoices.invoice_id', 'taxinvoices.invoice_id')
                ->get();
        } else {
            $invoiceModel = [];
        }
        $inputTax = inputTaxModel::where('input_tax_quote_id', $quotationModel->quote_id)->where('input_tax_wholesale_type','N')->get();
        return View::make('inputTax.inputtax-table', compact('quotationModel', 'inputTax', 'invoiceModel', 'invoice','document'))->render();
    }

    public function tableWholesale(quotationModel $quotationModel)
    {
       
        $inputTax = inputTaxModel::where('input_tax_quote_id', $quotationModel->quote_id)->where('input_tax.input_tax_wholesale_type', 'Y')->get();
        return View::make('inputTax.inputtax-wholesale-table', compact('inputTax','quotationModel'))->render();
    }

    public function delete(inputTaxModel $inputTaxModel)
    {
        
        if ($inputTaxModel->input_tax_file && File::exists('storage/' . $inputTaxModel->input_tax_file)) {
            File::delete('storage/' . $inputTaxModel->input_tax_file);
        }

        $inputTaxModel->delete();
        WithholdingTaxDocument::where('quote_id',$inputTaxModel->input_tax_quote_id)->delete();

        return redirect()->back();
    }
}
