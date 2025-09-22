<?php

namespace App\Http\Controllers\withholding;

use Illuminate\Http\Request;
use App\Models\QuoteLogModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
use App\Models\inputTax\inputTaxModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\invoices\taxinvoiceModel;
use App\Models\quotations\quotationModel;
use App\Models\signTures\imageSigntureModel;
use App\Models\withholding\WithholdingTaxItem;
use App\Models\withholding\WithholdingTaxDocument;
use App\Exports\WithholdingTaxExport;
use App\Models\wholesale\wholesaleModel;
use Maatwebsite\Excel\Facades\Excel;

class withholdingTaxController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = WithholdingTaxDocument::with(['customer', 'wholesale', 'quote']);

        // Filter by document number
        if ($request->filled('document_number')) {
            $query->where('document_number', 'LIKE', '%' . trim($request->document_number) . '%');
        }

        // Filter by reference number
        if ($request->filled('ref_number')) {
            $query->where('ref_number', 'LIKE', '%' . trim($request->ref_number) . '%');
        }

        // Filter by withholding form
        if ($request->filled('withholding_form')) {
            $query->where('withholding_form', $request->withholding_form);
        }

        // Filter by date range - new style (date_start/date_end from daterangepicker)
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $query->whereBetween('document_doc_date', [$request->date_start, $request->date_end]);
        } elseif ($request->filled('date_start')) {
            $query->whereDate('document_doc_date', '>=', $request->date_start);
        } elseif ($request->filled('date_end')) {
            $query->whereDate('document_doc_date', '<=', $request->date_end);
        }
        // Filter by customer
        if ($request->filled('customer')) {
            $query->where('customer_id', $request->customer);
        }
        // Filter by wholesale
        if ($request->filled('wholesale')) {
            $query->where('wholesale_id', $request->wholesale);
        }
        
        // Order by latest document date, then by document number
        $query = $query->orderBy('document_doc_date', 'desc')->orderBy('document_number', 'desc');

        // Check if any filter is applied
        $hasFilters = $request->filled('document_number') || 
                     $request->filled('ref_number') || 
                     $request->filled('withholding_form') || 
                     $request->filled('date_start') || 
                     $request->filled('date_end') || 
                     $request->filled('customer') ||
                     $request->filled('wholesale');

        // If filters are applied, get all records, otherwise paginate
        $documents = $hasFilters ? $query->get() : $query->paginate(20);

        // Get unique customers and wholesales for filter dropdown - optimized query
        $customerWithholding = WithholdingTaxDocument::with(['customer', 'wholesale'])
            ->select('customer_id', 'wholesale_id')
            ->whereNotNull('customer_id')
            ->orWhereNotNull('wholesale_id')
            ->get()
            ->filter(function ($item) {
                return $item->customer || $item->wholesale;
            })
            ->unique(function ($item) {
                return $item->customer_id ?? $item->wholesale_id;
            })
            ->sortBy(function ($item) {
                return $item->customer->customer_name ?? ($item->wholesale->wholesale_name_th ?? '');
            });

        return view('withholding.index', compact('documents', 'customerWithholding'));
    }

    public function create()
    {
        $customers = customerModel::latest()->get();
        $imageSingture = imageSigntureModel::get();
        $campaignSource = DB::table('campaign_source')->get();
        $wholesales = wholesaleModel::latest()->get();
        return view('withholding.create', compact('customers', 'imageSingture', 'campaignSource', 'wholesales'));
    }

    public function createModal(quotationModel $quotationModel)
    {
        $customers = customerModel::latest()->get();
        $imageSingture = imageSigntureModel::get();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        $invoice = invoiceModel::where('invoice_quote_id', $quotationModel->quote_id)->first();
        $taxinvoice = taxinvoiceModel::where('invoice_id', $invoice->invoice_id)->first();
        return view('withholding.quote-withholding', compact('customers', 'imageSingture', 'quotationModel', 'customer', 'taxinvoice'));
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

        // สร้างรหัสเอกสารใหม่ โดยส่ง document_date ไป
        $documentNumber = WithholdingTaxDocument::generateDocumentNumber($request->document_date);
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
            'document_no' => $documentNumberNo,
            'document_doc_date' => $request->document_doc_date,
            'wholesale_id' => $request->wholesale_id,
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
        $QuoteLog = QuoteLogModel::where('quote_id', $request->quote_id)->first();
        if ($QuoteLog) {
            $QuoteLog->update([
                'withholding_tax_status' => 'ออกแล้ว',
                'withholding_tax_updated_at' => now(),
                'withholding_tax_created_by' => Auth::user()->name,
            ]);
        } else {
            if ($request->quote_id) {
                $checkList = QuoteLogModel::create([
                    'quote_id' => $request->quote_id,
                    'withholding_tax_status' => 'ออกแล้ว',
                    'withholding_tax_updated_at' => now(),
                    'withholding_tax_created_by' => Auth::user()->name,
                ]);
            }
        }

        if ($request->form_name === 'quote') {
            return redirect()->back();
        } else {
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
        return view('withholding.edit', compact('document', 'customers', 'imageSingture'));
    }
    public function modalEdit($id)
    {
        $imageSingture = imageSigntureModel::get();
        $document = WithholdingTaxDocument::findOrFail($id);
        $customers = customerModel::all();
        return view('withholding.modal-edit', compact('document', 'customers', 'imageSingture'));
    }

    public function editRepear($id)
    {
        $imageSingture = imageSigntureModel::get();
        $document = WithholdingTaxDocument::findOrFail($id);
        $customers = customerModel::all();
        return view('withholding.create_repear', compact('document', 'customers', 'imageSingture'));
    }

    /**
     * อัปเดตเอกสาร
     */

    public function update(Request $request, $id)
    {
        //dd($request->all());
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
            'image_signture_id' => $request->image_signture_id,
            'document_doc_date' => $request->document_doc_date,
            'withholding_note' => $request->withholding_note,
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

        $inputTaxModel = inputTaxModel::where('input_tax_id', $document->ref_input_tax)->update([
            'input_tax_service_total' => $totalAmount,
            'input_tax_vat' => $totalAmount * 0.07,
            'input_tax_withholding' => $totalAmount * 0.03,
            'input_tax_grand_total' => $totalAmount * 0.03,
        ]);

        return redirect()->back()->with('success', 'เอกสารถูกอัปเดตเรียบร้อยแล้ว');
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

    public function exportExcel(Request $request)
    {
        // Get filters from request
        $filters = [
            'document_number' => $request->document_number,
            'ref_number' => $request->ref_number,
            'withholding_form' => $request->withholding_form,
            'document_date_start' => $request->document_date_start,
            'document_date_end' => $request->document_date_end,
            'customer' => $request->customer,
        ];

        // Generate filename with current date
        $filename = 'รายการใบหัก ณ ที่จ่าย_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Check if specific documents are selected for export
        $selectedIds = $request->selected_ids ? explode(',', $request->selected_ids) : null;

        return Excel::download(new WithholdingTaxExport($selectedIds, $filters), $filename);
    }
}
