<?php

namespace App\Http\Controllers\payments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\debits\debitModel;
use App\Models\invoices\invoiceModel;
use Illuminate\Support\Facades\File;
use App\Models\payments\paymentModel;
use App\Models\quotations\quotationModel;

class paymentDebitController extends Controller
{
    //
    public function debit(debitModel $debitModel, Request $request)
    {
        return view('payments.debit-modal', compact('debitModel'));
    }
    public function payment(Request $request)
    {
        // ตรวจสอบข้อมูลที่รับมาจาก request
        $debit = quotationModel::where('debit_note_number', $request->payment_doc_number)->first();

        $invoice = invoiceModel::where('invoice_number', $debit->invoice_number)->first();

        $paymentModel = paymentModel::create($request->all());

        // สร้างพาธที่ถูกต้อง
        $folderPath = 'public/' . $invoice->customer_id . '/' . $debit->debit_note_number;
        $absolutePath = storage_path('app/' . $folderPath);

        // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0775, true);
        }

        $file = $request->file('payment_file');

        if ($file) {
            $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
            $uniqueName = $paymentModel->payment_id . '_' . $paymentModel->payment_doc_number . '_' . date('Ymd') . '.' . $extension;
            $filePath = $invoice->customer_id . '/' . $debit->debit_note_number . '/' . $uniqueName;

            // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
            $file->move($absolutePath, $uniqueName);

            // อัปเดตพาธไฟล์ในฐานข้อมูล
            $paymentModel->update(['payment_file_path' => $filePath]);
        }

        // การจัดการการชำระเงิน
        $totalOld = $debit->payment !== NULL ? $debit->payment : 0;
        $total = $totalOld + $request->payment_total;

        debitModel::where('debit_note_number', $request->payment_doc_number)->update(['payment' => $total]);

        // การอัปเดตสถานะของใบเพิ่มหนี้
        if ($total >= $debit->grand_total) {
            debitModel::where('debit_note_number', $request->payment_doc_number)->update(['debit_note_status' => 'success']);
        } else {
            debitModel::where('debit_note_number', $request->payment_doc_number)->update(['debit_note_status' => 'payment']);
        }

        return redirect()->back()->with('success', 'Payment processed successfully.');
    }

    public function cancel(paymentModel $paymentModel, Request $request)
    {
        $totalOld = $paymentModel->payment_total;
        $paymentModel->update(['payment_total' => NULL, 'payment_status' => 'cancel']);

        // quote
        $debitModel = debitModel::where('debit_note_number', $paymentModel->payment_doc_number)->first();
        $debitTotalOld = $debitModel->payment;
        $totalNew = $debitTotalOld - $totalOld;
        $debitModel->update(['payment' => $totalNew, 'debit_note_status' => 'payment']);

          // การอัปเดตสถานะของใบเพิ่มหนี้
          if ($debitModel->payment <= 0 ) {
            debitModel::where('debit_note_number', $request->payment_doc_number)->update(['debit_note_status' => 'wait']);
        } else {
            debitModel::where('debit_note_number', $request->payment_doc_number)->update(['debit_note_status' => 'payment']);
        }

        return redirect()->back();
    }

    public function edit(paymentModel $paymentModel)
    {
        $debitModel = debitModel::where('debit_note_number', $paymentModel->payment_doc_number)->first();
        return view('payments.debit-modal-edit', compact('debitModel', 'paymentModel'));
    }

    public function update(paymentModel $paymentModel, Request $request)
    {
        $totalOld = $request->payment_total_old;
        $totaNew = $request->payment_total;

        //$request->merge(['invoice_number' => $runningCode]); 
        $paymentModel->update($request->all());


        $debit = debitModel::where('debit_note_number', $paymentModel->payment_doc_number)->first();
        $debit->update(['payment' => $debit->debit - $totalOld]);
        $debit->update(['payment' => $debit + $debit->payment]);

        $invoice = invoiceModel::where('invoice_number', $debit->invoice_number)->first();

        if ($debit->payment >= $debit->grand_total) {
            debitModel::where('debit_note_number', $request->payment_doc_number)->update(['quote_status' => 'success']);
        } else {
            debitModel::where('debit_note_number', $request->payment_doc_number)->update(['quote_status' => 'payment']);
        }

        $file = $request->file('payment_file');
        if ($file) {
            // ลบไฟล์เก่า
            $absoluteFilePath = storage_path('app/public/' .$paymentModel->payment_file_path);
            if (File::exists($absoluteFilePath)) {
                File::delete($absoluteFilePath);
            }

            // สร้างพาธที่ถูกต้อง
            $folderPath = 'public/' . $invoice->customer_id . '/' . $debit->debit_note_number;
            $absolutePath = storage_path('app/' . $folderPath);

            // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
            if (!File::exists($absolutePath)) {
                File::makeDirectory($absolutePath, 0775, true);
            }


            $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
            $uniqueName = $paymentModel->payment_id . '_' . $paymentModel->payment_doc_number . '_' . date('Ymd') . '.' . $extension;
            $filePath = $invoice->customer_id . '/' .$debit->debit_note_number . '/' . $uniqueName;

            // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
            $file->move($absolutePath, $uniqueName);

            // อัปเดตพาธไฟล์ในฐานข้อมูล
            $paymentModel->update(['payment_file_path' => $filePath]);
        }

        return redirect()->back();
    }
}
