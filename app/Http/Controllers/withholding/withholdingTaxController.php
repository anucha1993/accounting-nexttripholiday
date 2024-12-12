<?php

namespace App\Http\Controllers\withholding;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\invoices\taxinvoiceModel;
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

    public function taxNumber(Request $request)
    {
        $query = $request->get('query'); // รับค่าการค้นหา
        $documents  = taxinvoiceModel::where('taxinvoice_number', 'LIKE', "%{$query}%")
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

        // บันทึกเอกสาร
        $document = WithholdingTaxDocument::create([
            'document_number' => $documentNumber, // เพิ่มฟิลด์นี้
            'customer_id' => $request->customer_id,
            'document_date' => $request->document_date,
            'ref_number' => $request->ref_number,
            'withholding_form' => $request->withholding_form,
            // ค่าที่คำนวณได้
            'total_amount' => $totalAmount,
            'total_withholding_tax' => $totalWithholdingTax,
            'total_payable' => $totalPayable,
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

    /**
     * อัปเดตเอกสาร
     */
    public function update(Request $request, $id)
    {
        // ตรวจสอบความถูกต้องของข้อมูล
        // $request->validate([
        //     'document_number' => 'required|unique:withholding_tax_documents,document_number,' . $id,
        //     'customer_id' => 'required|exists:customers,id',
        //     'document_date' => 'required|date',
        //     'withholding_form' => 'required',
        //     'income_type.*' => 'required|string',
        //     'tax_rate.*' => 'required|numeric',
        //     'amount.*' => 'required|numeric',
        //     'withholding_tax.*' => 'required|numeric',
        // ]);
    
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
