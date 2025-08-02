<!DOCTYPE html>
<html>

<head>
    <title>{{ $texreceipt->taxinvoice_number }}</title>
    <meta http-equiv="Content-Language" content="th" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
        .text-cancel {
            transform: rotate(45); /* ปรับมุมองศาตามต้องการ */
            position: absolute;
            top: 400px; /* ปรับตำแหน่งแนวตั้ง */
            right: auto; /* ปรับตำแหน่งแนวนอน */
            font-size:50px;
            color: rgba(255, 0, 0, 0.007);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        } 
       
   

        
    </style>
</head>

<body style="margin-top: 0px; padding-top: 0;">
    @if ($quotationModel->quote_status === 'cancel')
    <div class="text-cancel">
        <b>ยกเลิก {{$quotationModel->quote_cancel_note}}</b>
     </div>
    @endif

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
            <div class="text-center  " style="padding-left: 52px">
                <h4>ต้นฉบับ/ใบกำกับภาษี</h4>
            </div>

            <div class="text-center " style="padding-left: 50px; padding-top: -55px;">
                <h4><b>Original/Tax Receipt</b></h4>
            </div>
            <div class="" style="padding-left: 65px; padding-top: -35px;">
                <h5><b>(สำหรับลูกค้า) </b> </h5>
            </div>
        </div>
        <div style="margin-top: -45px">
            <table style="margin-right: -41px; margin-left: -37px;">
                <tr>

                    <td 
                        style="width: 150px; padding-left: 5px; border-right: none;  border-bottom: none; vertical-align: top;">
                        <p><b>ชื่อลูกค้า/Customer Name:</span></p>
                    </td>
                    <td style="width: 290px; padding-left: 5px; border-left: none;  border-bottom: none; vertical-align: top;">
                        <p><span>{{$customer->customer_name}}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td style="width: 185px; padding-left: 5px; border-right: none;  border-bottom: none; vertical-align: top;">
                        <h4><b>เลขที่/No:</b></h4>
                    </td>
                    <td style="padding-left: 5px; border-left: none; border-bottom: none; vertical-align: top;">
                        <p><span>{{ $texreceipt->taxinvoice_number }}</span></p>
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
                        <p><span>{{ thaidate('j M Y', $texreceipt->taxinvoice_date) }}</span></p>
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
                        <p><b>เบอร์โทรศัพท์/Phone No.:</span></p>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_tel }}</span></p>
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
                        <h4><b>รหัสทัวร์/Tour Code:</b></h4>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none; border-bottom: none; border-top: none; vertical-align: top;">
                        {{ $quotationModel->quote_tour ? $quotationModel->quote_tour : $quotationModel->quote_tour_code  }}</span></p>
                    </td>
                </tr>
                <tr style="padding: 3px">
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none; border-bottom: none; border-top: none; vertical-align: top; ">
                        <p><b>เลขประจำตัวผู้เสียภาษี/Tax ID:</span></p>
                    </td>

                    <td
                        style="padding-left: 5px; border-left: none; border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_texid }}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td
                        style="padding-left: 5px; border-right: none;    border-top: none; vertical-align: top;">
                        {{-- <h4><b>ชำระเงินมัดจำ/deposit Date:</b></h4> --}}
                    </td>
                    <td
                        style="padding: 0; text-align: left; border-left: none;   border-top: none; background-color: #ffffff; vertical-align: top;">
                        {{-- <p style="margin: 0; padding: 10px;">
                            @if ($quotationModel->quote_payment_type === 'deposit')
                                <span> {{ thaidate('j M Y', $quotationModel->quote_payment_date) }} เวลา
                                    {{ date('H:i', strtotime($quotationModel->quote_payment_date)) }} น.</span>
                            @else
                                - ก่อนเวลา - น.
                            @endif

                        </p> --}}
                    </td>
                </tr>
                <tr style="padding: 3px">
                    <td style="padding-left: 5px; border-right: none;  border-top: none; vertical-align: top; ">

                    </td>

                    <td style="wpadding-left: 5px; border-left: none; border-top: none; vertical-align: top;">

                    </td>
                    <td style="border: none;"></td>
                    {{-- <td style="padding-left: 5px; border-right: none;   border-top: none; vertical-align: top;">
                        <h4><b>ชำระส่วนที่เหลือ/Full Payment Date:</b></h4>
                    </td>
                    <td
                        style="padding: 0; text-align: left; border-left: none;  ; background-color: #ffffff; vertical-align: top;">
                        <p style="margin: 0; padding: 10px;">
                            @if ($quotationModel->quote_payment_type === 'full')
                                <span> {{ thaidate('j M Y', $quotationModel->quote_payment_date) }} เวลา
                                    {{ date('H:i', strtotime($quotationModel->quote_payment_date)) }} น.</span>
                            @else
                                - ก่อนเวลา - น.
                            @endif

                        </p>
                    </td> --}}
                </tr>
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

                    <td style="width: 65px; height: 250px; text-align: center; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                       
                            <p style="margin: 0;">{{ $key + 1 }}</p>
                       
                        @empty
                        @endforelse

                    </td>

                    <td style="width: 270px;  text-align: left; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                        

                        @if ($item->expense_type === 'income')
                        @if ($item->vat_status === 'vat')
                        <p style="margin: 0;">{{ $item->product_name}} <b> &nbsp;**(VAT) </b></p>
                        @else
                        <p style="margin: 0;">{{ $item->product_name}} <b>  &nbsp;**(Non VAT) </b></p>
                        @endif

                        @else
                         ส่วนลด
                        @endif
                        @empty
                            
                        @endforelse
                    </td>

                    <td style="width: 70px; text-align: center; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
           
                            <p style="margin: 0;">{{ $item->product_qty }}</p>
                
                        @empty
                        @endforelse
                    </td>
                    <td style="width: 120px; text-align: center; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                        <p style="margin: 0;">
                            @if ($item->withholding_tax === 'N')
                            {{  number_format( $item->product_price  , 2, '.', ',')}}
                            @else
     
                            {{  number_format( ($item->product_price * 0.03)+$item->product_price  , 2, '.', ',')}}
                            @endif
                        </p>
                        @empty
                            
                        @endforelse
                    </td>
                    <td style="width: 120px; text-align: right; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                       
                            <p style="margin: 0;">{{ number_format($item->product_sum, 2, '.', ',') }}</p>
                       
                        @empty
                        @endforelse
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="8" style="text-align: left; vertical-align: top; padding: 10px;">
                        **(Non VAT) = ค่าบริการไม่คิดภาษีมูลค่าเพิ่ม<br><br>
                        <b>หมายเหตุ / Remark:</b><br>
                        -<br>
                        ภาษีหัก ณ ที่จ่าย 3% : จำนวนเงิน {{ number_format($invoiceModel->invoice_withholding_tax, 2, '.', ',') }} บาท<br>
                        (คำนวณจากยอดรวมก่อนภาษีมูลค่าเพิ่ม / Pre-VAT Amount)<br>
                        ข้อสังเกต: กรุณาตรวจสอบกับบริษัทเกี่ยวกับการหักภาษี ณ ที่จ่าย 3% ภายใต้เงื่อนไขที่กำหนด
                    </td>
                    <td colspan="2" style="text-align: right; padding: 3px;">ยอดรวมยกเว้นภาษี / Vat-Exempted Amount
                    </td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_vat_exempted_amount, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ราคาสุทธิสินค้าที่เสียภาษี / Pre-Tax
                        Amount</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_pre_tax_amount, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ส่วนลด / Discount</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_discount, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT
                        Amount</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_pre_vat_amount, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ภาษีมูลค่าเพิ่ม VAT 7%</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_vat, 2, '.', ',') }}</td>
                </tr>
                
                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ราคาพร้อมภาษีมูลค่าเพิ่ม / Include VAT
                    </td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_include_vat, 2, '.', ',') }}</td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">หักเงินมัดจำ / Deposit</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->deposit, 2, '.', ',') }}</td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ยอดชำระทั้งสิ้น / Grand Total</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_grand_total-$invoiceModel->payment, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; background-color: #fff;">
                        <h3>จำนวนเงินตัวอักษร:</h3>
                    </td>
                    <td colspan="3" style="text-align: right; background-color: #bbdefb;">
                        <h3>(@bathText($invoiceModel->invoice_grand_total-$invoiceModel->payment))</h3>
                    </td>

                </tr>


            </table>
        </div>

        <footer>
{{-- {{$payment->payment_id}} --}}
            <div style="margin-top: -17px">
                <b>ชำระเงินโดย / Form of payment: </b><br>
                <span style="font-family: @if(!empty($payment->payment_method) && $payment->payment_method === 'cash') DejaVuSans; @endif">&#9745;</span> <b>เงินสด</b><br>
               
                <span style="font-family: @if(!empty($payment->payment_method) && $payment->payment_method === 'check') DejaVuSans; @endif">&#9745;</span> <b>เช็คธนาคาร</b>

                @if(!empty($payment->payment_method) && $payment->payment_method === 'check') 
                {{-- {{$payment->bank_name}}  --}}
                เลขที่เช็ค : {{$payment->payment_check_number}}  เช็คลงวันที่ : {{ thaidate('j F Y', $payment->payment_check_date) }} เวลา: {{ date('H:i', strtotime($quotationModel->payment_check_date)) }} น.
                @endif
                <br>

                <span style="font-family: @if(!empty($payment->payment_method) && $payment->payment_method === 'credit') DejaVuSans; @endif">&#9745;</span> <b>บัตรเครดิต</b>
                
                @if(!empty($payment->payment_method) && $payment->payment_method === 'credit') 
                {{-- {{$payment->bank_name}}   --}}
                เลขที่สลิป : {{$payment->payment_credit_slip_number}}
                @endif
                <br>
                <span style="font-family: @if(!empty($payment->payment_method) && $payment->payment_method === 'transfer-money') DejaVuSans; @endif">&#9745;</span> <b>โอนเงินเข้าบัญชี</b>
               @if(
    !empty($payment->payment_method) 
    && $payment->payment_method === 'transfer-money' 
    && !empty($payment->payment_in_date)
)
    {{-- {{$payment->bank_name}}    --}}
    โอนเมื่อวันที่ : {{ thaidate('j F Y', $payment->payment_in_date) }} 
    เวลา: {{ !empty($payment->payment_in_date) ? date('H:i', strtotime($payment->payment_in_date)) : '-' }} น.
@endif
                <br>
                
            </div>

                <div style="margin-top: -10px">
                      <table style="margin-right: -41px; margin-left: -37px;">
                        <tr style="border-right: none;">
                            <td style="width: 276.6px; padding: 5x; text-align: center;">
                               <b>{{ $invoiceModel->quote->Salename->name }}</b></br>
                                <p>___________________________</p>
                                <p><b>Sale / Operation</b></p>
                                <p><b>{{ thaidate('j F Y', $invoiceModel->invoice_date) }}</b></p>
                            </td>
                            <td style="border: none;"></td>
                            <td style="width: 276.6px; text-align: center;">
                                <b style="color: #fff">ว่าง</b></br>
			<b >{{$invoiceModel->created_by}}</b>
                                <p>___________________________</p>
                                <p><b>ผู้รับเงิน / Collector</b></p>
                                <p><b>{{ thaidate('j F Y', $invoiceModel->invoice_date) }}</b></p>
                            </td>
                            <td style="border: none;"></td>
                            <td style="width: 277px; text-align: right; text-align: center;">
                                <img src="{{URL::asset('signature/next_signature_01.png')}}" alt="Image" class="image" style="width: 120px; ">
                                <p><b>ผู้มีอำนาจลงนาม/
Authorized signature</b></p>
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
