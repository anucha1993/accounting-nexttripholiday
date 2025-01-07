<?php

namespace App\Http\Controllers\withholding;

use Illuminate\Http\Request;
use App\Models\QuoteLogModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\invoices\taxinvoiceModel;
use App\Models\quotations\quotationModel;
use App\Models\signTures\imageSigntureModel;
use App\Models\withholding\WithholdingTaxItem;
use App\Models\withholding\WithholdingTaxDocument;

class withholdingTaxController extends Controller
{
    //
    public function index()
    {
        $documents = WithholdingTaxDocument::with('customer')->get();
        return view('withholding.index', compact('documents'));
    }

    public function create()
    {
        $customers = customerModel::latest()->get();
        $imageSingture = imageSigntureModel::get();
        return view('withholding.create', compact('customers','imageSingture'));
    }

    public function createModal(quotationModel $quotationModel)
    {
        $customers = customerModel::latest()->get();
        $imageSingture = imageSigntureModel::get();
        $customer = customerModel::where('customer_id',$quotationModel->customer_id)->first();
        $invoice = invoiceModel::where('invoice_quote_id',$quotationModel->quote_id)->first();
        $taxinvoice = taxinvoiceModel::where('invoice_id',$invoice->invoice_id)->first();
        return view('withholding.quote-withholding', compact('customers','imageSingture','quotationModel','customer','taxinvoice'));
    }
    

  
    public function taxNumber(Request $request)
    {
        $query = $request->get('query'); // รับค่าการค้นหา
    
        // ดึง ref_number ทั้งหมดที่มีใน withholding_tax_documents
        $existingRefNumbers = WithholdingTaxDocument::pluck('ref_number');
    
        // ค้นหา taxinvoice ที่ยังไม่มีใน ref_number
        $documents = taxinvoiceModel::where('taxinvoice_number', 'LIKE', "%{$query}%")
            ->whereNotIn('taxinvoice_number', $existingRefNumbers) // กรองข้อมูลที่ยังไม่มีใน ref_number
            ->get(['taxinvoice_id', 'taxinvoice_number']); // ดึงเฉพาะ ID และ tax_number
    
        return response()->json($documents);
    }
    
    public function store(Request $request)
    {
        
        $totalAmount = 0;
        $totalWithholdingTax = 0;

        foreach ($request->amount as $index => $amount) {
            $amount = (float) $amount;
            $taxRate = (float) $request->tax_rate[$index];
            $withholdingTax = ($amount * $taxRate) / 100;

            $totalAmount += $amount;
            $totalWithholdingTax += $withholdingTax;
        }

        $totalPayable = $totalAmount - $totalWithholdingTax;
        

        // สร้างรหัสเอกสารใหม่
        $documentNumber = WithholdingTaxDocument::generateDocumentNumber();
        $documentNumberNo = WithholdingTaxDocument::generateDocumentNumberNo();

        // บันทึกเอกสาร
        $document = WithholdingTaxDocument::create([
            'quote_id' => $request->quote_id, // เพิ่มฟิลด์นี้
            'document_number' => $documentNumber, // เพิ่มฟิลด์นี้
            'withholding_branch' => $request->withholding_branch, // เพิ่มฟิลด์นี้
            'withholding_note' => $request->withholding_note, // เพิ่มฟิลด์นี้
            'customer_id' => $request->customer_id,
            'document_date' => $request->document_date,
            'ref_number' => $request->ref_number,
            'withholding_form' => $request->withholding_form,
            // ค่าที่คำนวณได้
            'total_amount' => $totalAmount,
            'total_withholding_tax' => $totalWithholdingTax,
            'total_payable' => $totalPayable,
            'image_signture_id' => $request->image_signture_id,
            'book_no' => date('Y-m'),
            'document_no' => $documentNumberNo
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

        // บันทึก Check List 
        $QuoteLog = QuoteLogModel::where('quote_id',$request->quote_id)->first();
        if($QuoteLog){
            $QuoteLog->update([
                "withholding_tax_status" => 'ออกแล้ว',
                "withholding_tax_updated_at" => now(),
                "withholding_tax_created_by" => Auth::user()->name
            ]);
        }else{
            $checkList = QuoteLogModel::create([
                'quote_id' => $request->quote_id,
                "withholding_tax_status" => 'ออกแล้ว',
                "withholding_tax_updated_at" => now(),
                "withholding_tax_created_by" => Auth::user()->name
            ]);
        }

        if($request->form_name === 'quote'){
            return redirect()->back();
        }else{
        return redirect()->route('withholding.index')->with('success', 'เอกสารถูกบันทึกเรียบร้อยแล้ว');
        } 
    }
    /**
     * แสดงรายละเอียดเอกสาร
     */
    public function show($id)
    {
        $document = WithholdingTaxDocument::with('customer')->findOrFail($id);
        return view('withholding.show', compact('document'));
    }

    /**
     * แสดงฟอร์มสำหรับแก้ไขเอกสาร
     */
    public function edit($id)
    {
        $imageSingture = imageSigntureModel::get();
        $document = WithholdingTaxDocument::findOrFail($id);
        $customers = customerModel::all();
        return view('withholding.edit', compact('document', 'customers','imageSingture'));
    }

    public function editRepear($id)
    {
        $imageSingture = imageSigntureModel::get();
        $document = WithholdingTaxDocument::findOrFail($id);
        $customers = customerModel::all();
        return view('withholding.create_repear', compact('document', 'customers','imageSingture'));
    }

    /**
     * อัปเดตเอกสาร
     */
    public function update(Request $request, $id)
    {
        // คำนวณยอดรวมและภาษี
        $totalAmount = 0;
        $totalWithholdingTax = 0;
    
        foreach ($request->amount as $index => $amount) {
            $amount = (float) $amount;
            $taxRate = (float) $request->tax_rate[$index];
            $withholdingTax = ($amount * $taxRate) / 100;
    
            $totalAmount += $amount;
            $totalWithholdingTax += $withholdingTax;
        }
    
        $totalPayable = $totalAmount - $totalWithholdingTax;
    
        // ค้นหาเอกสาร
        $document = WithholdingTaxDocument::findOrFail($id);
    
        // อัปเดตข้อมูลในเอกสาร
        $document->update([
            'customer_id' => $request->customer_id,
            'document_date' => $request->document_date,
            'ref_number' => $request->ref_number,
            'withholding_form' => $request->withholding_form,
            'total_amount' => $totalAmount,
            'total_withholding_tax' => $totalWithholdingTax,
            'total_payable' => $totalPayable,
            'image_signture_id' => $request->image_signture_id
        ]);
    
        // ลบรายการเก่าที่เกี่ยวข้องกับเอกสาร
        $document->items()->delete();
    
        // เพิ่มรายการใหม่
        foreach ($request->income_type as $index => $incomeType) {
            WithholdingTaxItem::create([
                'document_id' => $document->id,
                'income_type' => $incomeType,
                'tax_rate' => $request->tax_rate[$index],
                'amount' => $request->amount[$index],
                'withholding_tax' => $request->withholding_tax[$index],
            ]);
        }
    
        return redirect()->route('withholding.index')->with('success', 'เอกสารถูกอัปเดตเรียบร้อยแล้ว');
    }
    

    /**
     * ลบเอกสาร
     */
    public function destroy($id)
    {
        $document = WithholdingTaxDocument::findOrFail($id);
        $document->delete();

        return redirect()->route('withholding.index')->with('success', 'เอกสารถูกลบเรียบร้อยแล้ว');
    }
}
