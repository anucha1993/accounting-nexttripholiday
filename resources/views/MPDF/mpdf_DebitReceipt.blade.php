<!DOCTYPE html>
<html>

<head>
    <title>{{ $debitModel->debit_note_number }}</title>
    <meta http-equiv="Content-Language" content="th" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'sarabun_new', sans-serif;
            font-size: 20px;
            margin-top: 0;
            padding-top: 0;
            flex-grow: 1;

        }

        ,
        table {
            table-layout: fixed;
            border-collapse: collapse;
            /* รวมขอบของเซลล์ */
            margin: 20px;
            /* ระยะห่างรอบตาราง */
            width: 100%;
        }

        td {
            border: 1px solid #95cfff;
            /* สีกรอบ */
            /* padding: 10px; /* ระยะห่างระหว่างข้อความกับกรอบ */
            background-color: #fff;
            /* สีพื้นหลัง */
            border-radius: 5px;
            /* มุมกรอบที่มน */
            text-align: left;
            /* จัดข้อความให้อยู่กลาง */
            font-size: 16px;
            word-wrap: break-word;

            
        }
        
       
       
   

        
    </style>
</head>

<body style="margin-top: 0px; padding-top: 0;">
    <header class="content">
        <div style="width: 15%; float: left; padding: 10px;">
            <img src="{{ asset('logo/Logo-docs.png') }}" alt="">
        </div>

        <div style="width: 50%; float: left; padding: 0px;">
            <h5>บริษัท เน็กซ์ ทริป ฮอลิเดย์ จำกัด (สำนักงานใหญ่)</h5>
            <div style="padding-top: -30px;">
                <span style="font-size: 14px; display: block; ">
                    222/2 โกลเด้นทาวน์ บางนา-สวนหลวง แขวงดอกไม้ เขตประเวศ กทม 10250
                </span>
            </div>

            <div style="padding-top: -7px;">
                <span style="font-size: 14px; display: block;">
                    โทรศัพท์:02-136-9144 อัตโนมัติ 16 คู่สาย โทรสาร(Fax): 02-136-9146
                </span>
            </div>

            <div style="padding-top: -7px;">
                <span style="font-size: 14px; display: block;">
                    Website: https://www.nexttripholiday.com , Email : nexttripholiday@gmail.com
                </span>
            </div>
            <div style="padding-top: -7px;">
                <span style="font-size: 15px; display: block;">
                    <b>เลขประจำตัวผู้เสียภาษี TaxID: 0115556013658</b>
                </span>
            </div>


        </div>

        <div style="width: 30%; float: left; padding: 0px;">


            <div class="text-center" style="padding-left: 80px">
                <h4>ต้นฉบับ/ใบเพิ่มหนี้</h4>
            </div>

            <div class="text-center " style="padding-left: 70px; padding-top: -55px;">
                <h4><b>Original / Debit Note</b></h4>
            </div>
            <div class="" style="padding-left: 100px; padding-top: -35px;">
                <h5><b>(สำหรับลูกค้า) </b> </h5>
            </div>
        </div>
        <div style="margin-top: -45px">
            <table style="margin-right: -41px; margin-left: -37px;">
                <tr>

                    <td 
                        style="width: 155px; padding-left: 5px; border-right: none;  border-bottom: none; vertical-align: top;">
                        <p><b>ชื่อลูกค้า/Customer Name:</span></p>
                    </td>
                    <td style=" padding-left: 5px; border-left: none;  border-bottom: none; vertical-align: top;">
                        <p><span>{{$customer->customer_name}}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td style="padding-left: 5px; border-right: none;  border-bottom: none; vertical-align: top;">
                        <h4><b>เลขที่/No:</b></h4>
                    </td>
                    <td style="padding-left: 5px; border-left: none; border-bottom: none; vertical-align: top;">
                        <p><span>{{$debitModel->debit_note_number}}</span></p>
                    </td>
                
                </tr>

                <tr>
                    <td
                        style=" padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><b>อีเมล์/Email:</span></p>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{$customer->customer_email}}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td
                        style=" padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <h4><b>วันที่/Date:</b></h4>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none; border-bottom: none; border-top: none; vertical-align: top; ">
                        <p><span>{{ thaidate('j M Y', $debitModel->debit_note_date) }}</span></p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><b>ที่อยู่/Address:</span></p>
                    </td>

                    <td 
                        style="padding-left: 5px; border-left: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_address }}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td
                        style="padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <h4><b>วันที่อ้างอิง/Ref Date.:</b></h4>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none; border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ thaidate('j M Y', $invoiceModel->invoice_date) }}</span></p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><b>เบอร์โทรศัพท์/Phone No.:</span></p>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_tel }}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td
                        style="padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <h4><b>เลขที่อ้างอิง/Ref No.:</b></h4>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none; border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $invoiceModel->invoice_number }}</span></p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><b>แฟกซ์/Fax:</span></p>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_fax ? $customer->customer_fax : '-' }}</span></p>
                    </td>

                    <td style="border: none;"></td>
                    <td
                        style="padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <h4><b>เลขที่จอง/Booking No.:</b></h4>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none; border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $invoiceModel->invoice_booking }}</span></p>
                    </td>
                </tr>
                <tr style="padding: 3px">
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none; border-top: none; vertical-align: top; ">
                        <p><b>เลขประจำตัวผู้เสียภาษี/Tax ID:</span></p>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_texid }}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td
                        style="padding-left: 5px; border-right: none; border-top: none; vertical-align: top;">
                        <h4><b>รหัสทัวร์/Tour Code:</b></h4>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none; border-top: none; vertical-align: top;">
                        {{ $invoiceModel->invoice_tour_code }}</span></p>
                    </td>
                </tr>
                {{-- <tr style="padding: 3px">
                    <td style="padding-left: 5px; border-right: none;  border-top: none; vertical-align: top; ">

                    </td>

                    <td style="wpadding-left: 5px; border-left: none; border-top: none; vertical-align: top;">

                    </td>
                    <td style="border: none;"></td>
                    <td style="padding-left: 5px; border-right: none;   border-top: none; vertical-align: top;">
                        <h4><b>ชำระส่วนที่เหลือ/Full Payment Date:</b></h4>
                    </td>
                    <td
                        style="padding: 0; text-align: left; border-left: none;  ; background-color: #bbdefb; vertical-align: top;">
                        <p style="margin: 0; padding: 10px;">
                            @if ($quotationModel->quote_payment_type === 'full')
                                <span> {{ thaidate('j M Y', $quotationModel->quote_payment_date) }} เวลา
                                    {{ date('H:m', strtotime($quotationModel->quote_payment_date)) }} น.</span>
                            @else
                                - ก่อนเวลา - น.
                            @endif

                        </p>
                    </td>
                </tr> --}}
                <tr>
                    <td style="border: none;"></td>
                </tr>

            </table>


        </div>

        <div style="margin-top: -35px">
            <table style="margin-right: -41px; margin-left: -37px;">
                <tr>
                    <td style="width: 55px; text-align: center; background-color: #bbdefb;">
                        <b>ลำดับ</br>
                            <p>Item</p>
                        </b>
                    </td>

                    <td style="width: 330px; text-align: center; background-color: #bbdefb;"><b>รายการ </br>
                            <p>Descriptons</p>
                        </b></td>
                    <td style="width: 100px; text-align: center; background-color: #bbdefb;"><b>จำนวน </br>
                            <p>Quanily</p>
                        </b></td>
                    <td style="width: 140px; text-align: right; background-color: #bbdefb;"><b>ราคาต่อหน่วย </br>
                            <p>Unit Price</p>
                        </b></td>
                    <td style="width: 120px; text-align: right; background-color: #bbdefb;"><b>ราคารวม </br>
                            <p>Total Amout</p>
                        </b></td>
                </tr>

                <tr>

                    <td style="width: 65px; height: 200px; text-align: center; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                        @if ($item->expense_type === 'income')
                            <p style="margin: 0;">{{ $key + 1 }}</p>
                         @endif
                        @empty
                        @endforelse

                    </td>

                    <td style="width: 270px;  text-align: left; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                            @if ($item->expense_type === 'income')
                            <p style="margin: 0;">{{ $item->product_name }} {{$item->vat === 'Y' ? '**(Non VAT)' : ''}}</p>
                            @endif

                        @empty
                        @endforelse

                    </td>
                    <td style="width: 70px; text-align: center; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                        @if ($item->expense_type === 'income')
                            <p style="margin: 0;">{{ $item->product_qty }}</p>
                            @endif
                        @empty
                        @endforelse
                    </td>
                    <td style="width: 120px; text-align: right; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                        @if ($item->expense_type === 'income')
                            <p style="margin: 0;">{{ number_format($item->product_price, 2, '.', ',') }}</p>
                            @endif
                        @empty
                        @endforelse
                    </td>
                    <td style="width: 120px; text-align: right; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                        @if ($item->expense_type === 'income')
                            <p style="margin: 0;">{{ number_format($item->product_sum, 2, '.', ',') }}</p>
                            @endif
                        @empty
                        @endforelse
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="11" style="text-align: left; vertical-align: top; padding: 10px;">
                        **(Non VAT) = ค่าบริการไม่คิดภาษีมูลค่าเพิ่ม<br><br>
                        <b>หมายเหตุ / Remark:</b><br>
                        {{$debitModel->debit_note_cause ? $debitModel->debit_note_cause :'-' }}
                        <br><br>
                        ภาษีหัก ณ ที่จ่าย 3%: {{ number_format($debitModel->vat_3_total, 2, '.', ',') }} บาท<br>
                        (คำนวณจากยอดรวมก่อนภาษีมูลค่าเพิ่ม / Pre-VAT Amount)<br>
                        ข้อสังเกต: กรุณาตรวจสอบกับบริษัทเกี่ยวกับการหักภาษี ณ ที่จ่าย 3% ภายใต้เงื่อนไขที่กำหนด
                    </td>
                    <td colspan="2" style="text-align: right; padding: 0px;">มูลค่าตามใบกำกับภาษีเดิม/Original Tax Invoice Value
                    </td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_grand_total, 2, '.', ',') }}</td>
                    
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">มูลค่าที่ถูกต้อง / Correct Value
                    </td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_grand_total+$debitModel->grand_total, 2, '.', ',') }}</td>
                    
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ผลต่าง / Defference
                    </td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($debitModel->difference, 2, '.', ',') }}</td>
                    
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ยอดรวมยกเว้นภาษี / Vat-Exempted Amount
                    </td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($NonVat, 2, '.', ',') }}</td>
                    
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ราคาสุทธิสินค้าที่เสียภาษี / Pre-Tax
                        Amount</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($VatTotal, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ส่วนลด / Discount</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($debitModel->debit_note_discount, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT
                        Amount</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($totalVat = $VatTotal-$debitModel->debit_note_discount, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ภาษีมูลค่าเพิ่ม VAT 7%</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($debitModel->vat_7_total, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ราคาพร้อมภาษีมูลค่าเพิ่ม / Include VAT
                    </td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($totalVat+$debitModel->vat_7_total, 2, '.', ',') }}</td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">หักเงินมัดจำ / Deposit</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($paymentDeposit, 2, '.', ',') }}</td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ยอดชำระทั้งสิ้น / Grand Total</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($debitModel->grand_total, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; background-color: #fff;">
                        <h3>จำนวนเงินตัวอักษร:</h3>
                    </td>
                    <td colspan="3" style="text-align: right; background-color: #bbdefb;">
                        <h3>(@bathText($debitModel->grand_total))</h3>
                    </td>

                </tr>


            </table>
        </div>

        <footer>


            <div style="margin-top: -17px">
                <b>ชำระเงินโดย / Form of payment: </b><br>
                <span style="font-family: @if($payment->payment_method === 'cash') DejaVuSans; @endif">&#9745;</span> <b>เงินสด</b><br>
               
                <span style="font-family: @if($payment->payment_method === 'check') DejaVuSans; @endif">&#9745;</span> <b>เช็คธนาคาร</b><br>

                @if($payment->payment_method === 'check') 
                {{$payment->bank_name}} เลขที่เช็ค : {{$payment->payment_check_number}}  โอนเมื่อวันที่ : {{ thaidate('j F Y', $payment->payment_date_time) }} เวลา: {{ date('H:m', strtotime($quotationModel->quote_payment_date)) }} น.
                @endif

                <span style="font-family: @if($payment->payment_method === 'credit') DejaVuSans; @endif">&#9745;</span> <b>บัตรเครดิต</b><br>
                
                @if($payment->payment_method === 'credit') 
                {{$payment->bank_name}}  เลขที่สลิป : {{$payment->payment_credit_slip_number}}
                @endif

                <span style="font-family: @if($payment->payment_method === 'transfer-money') DejaVuSans; @endif">&#9745;</span> <b>โอนเงินเข้าบัญชี</b>
                @if($payment->payment_method === 'transfer-money') 
                {{$payment->bank_name}}   โอนเมื่อวันที่ : {{ thaidate('j F Y', $payment->payment_date_time) }} เวลา: {{ date('H:m', strtotime($quotationModel->quote_payment_date)) }} น.
                @endif
                <br>
                
            </div>
            
            

            

                <div style="margin-top: -10px">
                      <table style="margin-right: -41px; margin-left: -37px;">
                        <tr style="border-right: none;">
                            <td style="width: 276.6px; padding: 5x; text-align: center;">
                                <b>{{ $sale->name }}</b></br>
                                <p>___________________________</p>
                                <p><b>Sale / Operation</b></p>
                                <p><b>{{ thaidate('j F Y', $invoiceModel->invoice_date) }}</b></p>
                            </td>
                            <td style="border: none;"></td>
                            <td style="width: 276.6px; text-align: center;">
                                <b style="color: #fff">ว่าง</b></br>
                                <p>___________________________</p>
                                <p><b>Sale / Operation</b></p>
                                <p><b>{{ thaidate('j F Y', $invoiceModel->invoice_date) }}</b></p>
                            </td>
                            <td style="border: none;"></td>
                            <td style="width: 277px; text-align: right; text-align: center;">
                                <b style="color: #fff">ว่าง</b></br>
                                <p style="color: #fff">ว่าง</p></br>
                                <p style="color: #fff">ว่าง</p></br>
                                <p><b>ผู้อนุมัติ</b></p>
                                <p><b>{{ thaidate('j F Y', $invoiceModel->invoice_date) }}</b></p>
                            </td>
                        </tr>
                    </table>
                </div>


        </footer>


    </header>

    <style>
        footer {
            margin-top: 0px;
            font-size: 16px;
        }
    </style>

</body>

</html>