<?php

namespace App\Http\Controllers\inputTax;

use Illuminate\Http\Request;
use App\Models\QuoteLogModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Models\invoices\invoiceModel;
use App\Models\inputTax\inputTaxModel;
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
        $wholesale = wholesaleModel::get();
        return view('inputTax.modal-create', compact('quotationModel', 'wholesale'));
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
        return view('inputTax.modal-edit', compact('inputTaxModel', 'quotationModel', 'wholesale'));
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

    // public function update(Request $request, inputTaxModel $inputTaxModel)
    // {
    //    // dd($request);
    //     $fileUploadController = new uploadfileQuoteController();
    //     $filePath = $fileUploadController->uploadFile($request, $request->input_tax_quote_number, $request->customer_id);
    //     $requestData = $request->all();
    //     if ($filePath) {
    //         // เช็คว่าไฟล์มีอยู่หรือไม่ ถ้ามีจะลบไฟล์
    //         if (File::exists('storage/'.$inputTaxModel->input_tax_file)) {
    //             File::delete('storage/'.$inputTaxModel->input_tax_file); // ลบไฟล์
    //         }

    //         $requestData['input_tax_file'] = $filePath;
    //     }
    //     $requestData['updated_by'] = Auth::user()->name;
    //     // สร้างข้อมูลใหม่ใน inputTaxModel
    //     $inputTaxModel->update($requestData);

    //     return redirect()->back();
    // }

    public function update(Request $request, inputTaxModel $inputTaxModel)
    {
        $requestData = $request->all();

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

        // อัปเดตข้อมูลเพิ่มเติม
        $requestData['updated_by'] = Auth::user()->name;

        // บันทึกการเปลี่ยนแปลงในฐานข้อมูล
        $inputTaxModel->update($requestData);

        return redirect()->back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }


    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'document_date' => 'required|date',
            'withholding_form' => 'required',
            'total_amount' => 'required|numeric',
            'total_withholding_tax' => 'required|numeric',
            'total_payable' => 'required|numeric',
            'income_type.*' => 'required|string',
            'tax_rate.*' => 'required|numeric',
            'amount.*' => 'required|numeric',
            'withholding_tax.*' => 'required|numeric',
        ]);

        // สร้างรหัสเอกสารใหม่
        $documentNumber = WithholdingTaxDocument::generateDocumentNumber();

        // บันทึกเอกสาร
        $document = WithholdingTaxDocument::create([
            'document_number' => $documentNumber,
            'customer_id' => $request->customer_id,
            'document_date' => $request->document_date,
            'withholding_form' => $request->withholding_form,
            'total_amount' => $request->total_amount,
            'total_withholding_tax' => $request->total_withholding_tax,
            'total_payable' => $request->total_payable,
        ]);

        // บันทึกรายการ
        foreach ($request->income_type as $index => $incomeType) {
            WithholdingTaxItem::create([
                'document_id' => $document->id,
                'income_type' => $incomeType,
                'tax_rate' => $request->tax_rate[$index],
                'amount' => $request->amount[$index],
                'withholding_tax' => $request->withholding_tax[$index],
            ]);
        }

        return redirect()->route('withholding.index')->with('success', 'เอกสารถูกบันทึกเรียบร้อยแล้ว');
    }

    public function table(quotationModel $quotationModel)

    {

        $invoice = invoiceModel::where('invoice_quote_id', $quotationModel->quote_id)->first();
        if ($invoice) {
            $invoiceModel = taxinvoiceModel::select('invoices.*')
                ->where('taxinvoices.invoice_id', $invoice->invoice_id)
                ->leftjoin('invoices', 'invoices.invoice_id', 'taxinvoices.invoice_id')
                ->get();
        } else {
            $invoiceModel = [];
        }
        $inputTax = inputTaxModel::where('input_tax_quote_id', $quotationModel->quote_id)->where('input_tax.input_tax_type', '!=', 2)->get();
        return View::make('inputTax.inputtax-table', compact('quotationModel', 'inputTax', 'invoiceModel', 'invoice'))->render();
    }

    public function tableWholesale(quotationModel $quotationModel)

    {
        $invoice = invoiceModel::where('invoice_quote_id', $quotationModel->quote_id)->first();
        if ($invoice) {
            $invoiceModel = taxinvoiceModel::select('invoices.*')
                ->where('taxinvoices.invoice_id', $invoice->invoice_id)
                ->leftjoin('invoices', 'invoices.invoice_id', 'taxinvoices.invoice_id')
                ->get();
        } else {
            $invoiceModel = [];
        }

        $inputTax = inputTaxModel::where('input_tax_quote_id', $quotationModel->quote_id)->where('input_tax.input_tax_type', 2)->get();
        return View::make('inputTax.inputtax-wholesale-table', compact('quotationModel', 'inputTax', 'invoiceModel', 'invoice'))->render();
    }
}
