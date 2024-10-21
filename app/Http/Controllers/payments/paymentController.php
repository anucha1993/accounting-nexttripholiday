<?php

namespace App\Http\Controllers\payments;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\bank\bankCompanyModel;
use App\Models\bank\bankModel;
use Illuminate\Support\Facades\File;
use App\Models\invoices\invoiceModel;
use App\Models\payments\paymentModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\quotations\quotationModel;
use Illuminate\Support\Facades\View;

class paymentController extends Controller
{
    //
    public function index(quotationModel $quotationModel, Request $request)
    {
        //dd($quotationModel->quote_number);
        $quotationModel = quotationModel::where('quotation.quote_number', $quotationModel->quote_number)
        ->leftjoin('invoices','invoices.invoice_quote_number','quotation.quote_number')
        ->leftjoin('debit_note','debit_note.debit_invoice_id','invoices.invoice_id')
        ->leftjoin('credit_note','credit_note.credit_invoice_id','invoices.invoice_id')
        ->first();

        $quotation = quotationModel::where('quote_number', $quotationModel->quote_number)->first();
        
    
        $payments = paymentModel::where('payment_doc_number', $quotationModel->quote_number)
        ->where('payment_doc_type','quote')
        ->get();
        
        $paymentDebit = paymentModel::where('payment_doc_number', $quotationModel->debit_number)
        ->where('payment_doc_type','debit-note')
        ->get();

        $paymentCredit = paymentModel::where('payment_doc_number', $quotationModel->credit_number)
        ->where('payment_doc_type','credit-note')
        ->get();
        
        return View::make('payments.payment-table',compact('payments','quotationModel','quotation','paymentDebit','paymentCredit'))->render();
    }


    public function quotation(quotationModel $quotationModel, Request $request)
    {
        $bank = bankModel::where('bank_status','active')->get();

        $bankCompany = bankCompanyModel::where('bank_company_status','active')->get();
        $totaPayment = 0 ;
        $paymentType = '';
        if ($quotationModel->payment <= 0) {
            if($quotationModel->quote_payment_type === 'full') {
                $totaPayment = $quotationModel->quote_grand_total;
                $paymentType = 'full';
            }else{
                $totaPayment = $quotationModel->quote_payment_total ? $quotationModel->quote_payment_total : 0;
                $paymentType = 'deposit';
            }
        } else {
            $totaPayment = $quotationModel->quote_grand_total - $quotationModel->payment;
            $paymentType = 'full';
        }
        
        return view('payments.quote-modal', compact('quotationModel','bank','bankCompany','totaPayment','paymentType'));
    }


      // function Runnumber Payment
      public function generateRunningCodePM()
      {
          $code = paymentModel::latest()->first();
          if (!empty($code)) {
              $codeNumber = $code->payment_number;
          } else {
              $codeNumber = 'PM' . date('y') . date('m'). '-' . '0000';
          }
          $prefix = 'PM';
          $year = date('y');
          $month = date('m');
          $lastFourDigits = substr($codeNumber, -4);
          $incrementedNumber = intval($lastFourDigits) + 1;
          $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
          $runningCode = $prefix . $year . $month . '-' . $newNumber;
          return $runningCode;
      }


    public function payment(Request $request)
    {
        // ตรวจสอบข้อมูลที่รับมาจาก request
        $quote = quotationModel::where('quote_number', $request->payment_doc_number)->first();

        $request->merge([
            'payment_number' => $this->generateRunningCodePM(),
        ]);

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
            $filePath = $quote->customer_id . '/' . $quote->quote_number . '/' . $uniqueName;

            // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
            $file->move($absolutePath, $uniqueName);

            // อัปเดตพาธไฟล์ในฐานข้อมูล
            $paymentModel->update(['payment_file_path' => $filePath]);
        }

        // การจัดการการชำระเงิน
        $totalOld = $quote->payment !== NULL ? $quote->payment : 0;
        $total = $totalOld + $request->payment_total;
        quotationModel::where('quote_number', $request->payment_doc_number)->update(['payment' => $total]);

        // การอัปเดตสถานะของใบเสนอราคา
        if ($total >= $quote->quote_grand_total) {
            quotationModel::where('quote_number', $request->payment_doc_number)->update(['quote_status' => 'success','quote_payment_status' => 'success']);
        } else {
            quotationModel::where('quote_number', $request->payment_doc_number)->update(['quote_payment_status' => 'payment']);
        }

        return redirect()->back()->with('success', 'Payment processed successfully.');
    }

    public function cancelModal(paymentModel $paymentModel)
    {
        return view('payments.camcel-payment',compact('paymentModel'));
    }

    public function cancel(paymentModel $paymentModel, Request $request)
    {
;
        $paymentStatus = 'refund';
        if($request->payment_total <= 0){
            $paymentStatus = 'cancel';
        }
        $request->merge([
            'payment_status' => $paymentStatus,
        ]);
        
        $paymentModel->update($request->all());
       
        // quote
        $quotationModel = quotationModel::where('quote_number', $paymentModel->payment_doc_number)->first();

        $deposit = $quotationModel->GetDeposit();
        $quotePayment = 'payment';

        if($deposit <= 0)
        {
         $quotePayment = 'wait';
        }

        if($deposit >= $quotationModel->quote_grand_total)
        {
         $quoteStatus = 'success';
        }else{
         $quoteStatus = 'wait';
        }
        

        $quotationModel->update([
            'payment' => $deposit,
            'quote_status' =>$quoteStatus,
            'quote_payment_status' =>$quotePayment
        ]);

        $folderPath = 'public/' . $quotationModel->customer_id . '/' . $quotationModel->quote_number;
        $absolutePath = storage_path('app/' . $folderPath);
        // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
        if (!File::exists($absolutePath)) {
             File::makeDirectory($absolutePath, 0775, true);
        }


        $file = $request->file('payment_cancel_file_path');

        if ($file) {
            $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
            $uniqueName = $paymentModel->payment_id . '_' . $paymentModel->payment_doc_number . '_cancel_' . date('Ymd') . '.' . $extension;
            $filePath = $quotationModel->customer_id . '/' . $quotationModel->quote_number . '/' . $uniqueName;

            // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
            $file->move($absolutePath, $uniqueName);

            // อัปเดตพาธไฟล์ในฐานข้อมูล
            $paymentModel->update(['payment_cancel_file_path' => $filePath]);
        }


        return redirect()->back();
    }

    public function edit(paymentModel $paymentModel)
    {
        $bank = bankModel::where('bank_status','active')->get();
        $bankCompany = bankCompanyModel::where('bank_company_status','active')->get();
        $quotationModel = quotationModel::where('quote_number', $paymentModel->payment_doc_number)->first();
        return view('payments.quote-modal-edit', compact('quotationModel', 'paymentModel','bankCompany','bank'));
    }

    public function update(paymentModel $paymentModel, Request $request)
    {
        $totalOld = $request->payment_total_old;
        $totaNew = $request->payment_total;

        //$request->merge(['invoice_number' => $runningCode]); 
        $paymentModel->update($request->all());


        $quote = quotationModel::where('quote_number', $paymentModel->payment_doc_number)->first();
        $quote->update(['payment' => $quote->payment - $totalOld]);
        $quote->update(['payment' => $totaNew + $quote->payment]);

        if ($quote->payment >= $quote->quote_grand_total) {
            quotationModel::where('quote_number', $request->payment_doc_number)->update(['quote_status' => 'success']);
        } else {
            quotationModel::where('quote_number', $request->payment_doc_number)->update(['quote_payment_status' => 'payment']);
        }

        $file = $request->file('payment_file');
        if ($file) {
            // ลบไฟล์เก่า
            $absoluteFilePath = storage_path('app/public/' .$paymentModel->payment_file_path);
            if (File::exists($absoluteFilePath)) {
                File::delete($absoluteFilePath);
            }

            // สร้างพาธที่ถูกต้อง
            $folderPath = 'public/' . $quote->customer_id . '/' . $quote->quote_number;
            $absolutePath = storage_path('app/' . $folderPath);

            // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
            if (!File::exists($absolutePath)) {
                File::makeDirectory($absolutePath, 0775, true);
            }


            $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
            $uniqueName = $paymentModel->payment_id . '_' . $paymentModel->payment_doc_number . '_' . date('Ymd') . '.' . $extension;
            $filePath = $quote->customer_id . '/' . $quote->quote_number . '/' . $uniqueName;

            // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
            $file->move($absolutePath, $uniqueName);

            // อัปเดตพาธไฟล์ในฐานข้อมูล
            $paymentModel->update(['payment_file_path' => $filePath]);
        }

        return redirect()->back();
    }
}
