<!DOCTYPE html>
<html>

<head>
    <title>{{ $invoiceModel->invoice_number }}</title>
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
            border: 1px solid #ffaa50;
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
            <div class="text-center pt-6 " style="padding-left: 85px">
                <h4>ต้นฉบับใบแจ้งหนี้</h4>
            </div>

            <div class="text-center pt-6 " style="padding-left: 85px; padding-top: -55px;">
                <h4><b>Original Invoice</b></h4>
            </div>
            <div class="" style="padding-left: 67px; padding-top: -55px;">
                <h5><b>สำหรับลูกค้า </b> <span style="font-size: 14px">(ไม่ใช่ใบกำกับภาษี)</span></h5>
            </div>
        </div>
        <div style="margin-top: -25px">
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
                        <p><span>{{$invoiceModel->invoice_number }}</span></p>
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
                        <p><span>{{ thaidate('j M Y', $invoiceModel->invoice_date) }}</span></p>
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
                        <p><span>{{ $invoiceModel->quote_number }}</span></p>
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
                        {{ $invoiceModel->invoice_tour_code }}</span></p>
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
                        style="padding-left: 5px; border-right: none;  border-bottom: none;  border-top: none; vertical-align: top;">
                        <h4><b>ชำระเงินมัดจำ/deposit Date:</b></h4>
                    </td>
                    <td
                        style="padding: 0; text-align: left; border-left: none;border-bottom:  none;  border-top: none; background-color: #f9c68f; vertical-align: top;">
                        <p style="margin: 0; padding: 10px;">
                            @if ($quotationModel->quote_payment_type === 'deposit')
                                <span> {{ thaidate('j M Y', $quotationModel->quote_payment_date) }} เวลา
                                    {{ date('H:m', strtotime($quotationModel->quote_payment_date)) }} น.</span>
                            @else
                                - ก่อนเวลา - น.
                            @endif

                        </p>
                    </td>
                </tr>
                <tr style="padding: 3px">
                    <td style="padding-left: 5px; border-right: none;  border-top: none; vertical-align: top; ">

                    </td>

                    <td style="wpadding-left: 5px; border-left: none; border-top: none; vertical-align: top;">

                    </td>
                    <td style="border: none;"></td>
                    <td style="padding-left: 5px; border-right: none;   border-top: none; vertical-align: top;">
                        <h4><b>ชำระส่วนที่เหลือ/Full Payment Date:</b></h4>
                    </td>
                    <td
                        style="padding: 0; text-align: left; border-left: none;  ; background-color: #f9c68f; vertical-align: top;">
                        <p style="margin: 0; padding: 10px;">
                            @if ($quotationModel->quote_payment_type === 'full')
                                <span> {{ thaidate('j M Y', $quotationModel->quote_payment_date) }} เวลา
                                    {{ date('H:m', strtotime($quotationModel->quote_payment_date)) }} น.</span>
                            @else
                                - ก่อนเวลา - น.
                            @endif

                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="border: none;"></td>
                </tr>

            </table>


        </div>

        <div style="margin-top: -35px">
            <table style="margin-right: -41px; margin-left: -37px;">
                <tr>
                    <td style="width: 55px; text-align: center; background-color: #f9c68f;">
                        <b>ลำดับ</br>
                            <p>Item</p>
                        </b>
                    </td>

                    <td style="width: 330px; text-align: center; background-color: #f9c68f;"><b>รายการ </br>
                            <p>Descriptons</p>
                        </b></td>
                    <td style="width: 100px; text-align: center; background-color: #f9c68f;"><b>จำนวน </br>
                            <p>Quanily</p>
                        </b></td>
                    <td style="width: 140px; text-align: right; background-color: #f9c68f;"><b>ราคาต่อหน่วย </br>
                            <p>Unit Price</p>
                        </b></td>
                    <td style="width: 120px; text-align: right; background-color: #f9c68f;"><b>ราคารวม </br>
                            <p>Total Amout</p>
                        </b></td>
                </tr>

                <tr>

                    <td style="width: 65px; height: 300px; text-align: center; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                        <p style="margin: 0;">{{ $key+1}}</p>
                        @empty
                            
                        @endforelse
                    
                    </td>

                    <td style="width: 270px;  text-align: left; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                        @if ($item->expense_type === 'discount')
                        <p style="margin: 0;">{{ $item->product_name}} <b>(ส่วนลด)</b></p>
                        @else
                        <p style="margin: 0;">{{ $item->product_name}}</p>
                        @endif
                       
                        @empty
                            
                        @endforelse
                    </td>
                    <td style="width: 70px; text-align: center; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                        <p style="margin: 0;">{{ $item->product_qty}}</p>
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
                        <p style="margin: 0;">{{  number_format($item->product_sum  , 2, '.', ',')}}</p>
                        @empty
                            
                        @endforelse
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="8" style="text-align: left; vertical-align: top; padding: 10px;">
                        **(Non VAT) = ค่าบริการไม่คิดภาษีมูลค่าเพิ่ม<br><br>
                        <b>หมายเหตุ / Remark:</b><br>
                        -<br>
                        ภาษีหัก ณ ที่จ่าย 3%: {{ number_format($invoiceModel->invoice_vat_3, 2, '.', ',') }} บาท<br>
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
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_withholding_tax, 2, '.', ',') }}</td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: right; padding: 3px;">ยอดชำระทั้งสิ้น / Grand Total</td>
                    <td style="text-align: right; padding: 3px;">{{ number_format($invoiceModel->invoice_grand_total-$invoiceModel->invoice_withholding_tax, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right; background-color: #fff;">
                        <h3>จำนวนเงินตัวอักษร:</h3>
                    </td>
                    <td colspan="3" style="text-align: right; background-color: #f9c68f;">
                        <h3> @bathText($invoiceModel->invoice_grand_total-$invoiceModel->invoice_withholding_tax)</h3>
                    </td>

                </tr>


            </table>
        </div>

        <footer>


            <div style="margin-top: -17px">
                <b>วิธีการชำระเงิน: </b>
                <span style="margin-top: px">ชื่อบัญชี บจก.เน็กซ์ ทริป ฮอลิเดย์</p>
            </div>
            <div style="margin-top: 0px; margin-bottom: -300px;">
                <div style="float: left; width: 25%; text-align: left;">
                    <b>ธนาคาร</b>
                </div>
                <div style="float: left; width: 25%; text-align: left;">
                    <b>ประเภทบัญชี</b>
                </div>
                <div style="float: left; width: 25%; text-align: left;">
                    <b>สาขา</b>
                </div>
                <div style="float: left; width: 25%; text-align: left;">
                    <b>เลขบัญชี</b>
                </div>

            </div>

            <div style="margin-top: 0px; ">
                <div style="float: left; width: 25%; text-align: left;">
                    <span>กรุงศรีอยุธยา</span>
                </div>
                <div style="float: left; width: 25%; text-align: left;">
                    <span>ออมทรัพย์</span>
                </div>
                <div style="float: left; width: 25%; text-align: left;">
                    <span>เมกาบางนา</span>
                </div>
                <div style="float: left; width: 25%; text-align: left;">
                    <span>688-1-28842-5</span>
                </div>
                <div style="clear: both;"></div> <!-- Clear float -->
            </div>

            <b>แจ้งชำระเงิน :</b>
            <span style="margin-top: -5px">สามารถแจ้งได้ทุกช่องทาง Line :@nexttripholiday
                ,อีเมล:nexttripholiday@gmail.com หรือทางไลน์กับพนักงานขายที่ท่านทำการจอง</p>

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
