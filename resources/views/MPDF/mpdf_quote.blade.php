<!DOCTYPE html>
<html>

<head>
    <title>{{ $quotationModel->quote_number }}</title>
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
            border-collapse: collapse;
            /* รวมขอบของเซลล์ */
            margin: 20px;
            /* ระยะห่างรอบตาราง */
        }

        td {
            border: 2px solid #ffaa50;
            /* สีกรอบ */
            /* padding: 10px; /* ระยะห่างระหว่างข้อความกับกรอบ */
            background-color: #fff;
            /* สีพื้นหลัง */
            border-radius: 5px;
            /* มุมกรอบที่มน */
            text-align: left;
            /* จัดข้อความให้อยู่กลาง */
            font-size: 18px
        }
        .text-cancel {
            transform: rotate(45); /* ปรับมุมองศาตามต้องการ */
            position: absolute;
            top: 470px; /* ปรับตำแหน่งแนวตั้ง */
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
  

    {{-- <div style="width: 120%; float: left; padding: 10px;  position: absolute;  top: 300px; right: -375px">
        <img src="{{ asset('logo/text-cancel.png') }}" alt="">
    </div>
     --}}

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
                    Hotline: 091-091-6364 ,091-091-6463
                </span>
            </div>
            <div style="padding-top: -7px;">
                <span style="font-size: 14px; display: block;">
                    TAT License: 11/07440 ,TTAA License:1469
                </span>
            </div>
            <div style="padding-top: -7px;">
                <span style="font-size: 14px; display: block;">
                    Website: https://www.nexttripholiday.com , Email : nexttripholiday@gmail.com
                </span>
            </div>


        </div>

        <div style="width: 30%; float: left; padding: 0px;">
            <div class="text-center pt-6 " style="padding-left: 73px">
                <h5>ใบจองทัวร์ / ใบเสนอราคา</h5>
            </div>

            <div class="text-center pt-6 " style="padding-left: 80px; padding-top: -55px;">
                <h5><b>Booking / Quotation</b></h5>
            </div>
            <div class="" style="padding-left: 67px; padding-top: -55px;">
                <h5><b>สำหรับลูกค้า </b> <span style="font-size: 14px">(ไม่ใช่ใบกำกับภาษี)</span></h5>
            </div>
            <div style="margin-top: -45px; text-align: right;">
                <h4 style="background-color: #f9c68f; display: inline-block; padding-left: 73px">
                    <b>{{ $quotationModel->quote_number }}</b></h4>
            </div>


        </div>
        <div style="margin-top: -35px">
            <table style="margin-right: -35px; margin-left: -35px;">
                <tr>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; vertical-align: top;">
                        <p><b>Customer ID:</span></p>
                    </td>

                    <td
                        style="width: 400px; padding-left: 5px; border-left: none;  border-bottom: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_number }}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; vertical-align: top;">
                        <h4><b>Date:</b></h4>
                    </td>

                    <td
                        style="width: 150px; padding-left: 5px; border-left: none; border-bottom: none; vertical-align: top;">
                        <p><span>{{ thaidate('j F Y', $quotationModel->quote_date) }}</span></p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><b>Name :</span></p>
                    </td>

                    <td
                        style="width: 400px; padding-left: 5px; border-left: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_name }}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <h4><b>Boonking No:</b></h4>
                    </td>

                    <td
                        style="width: 150px; padding-left: 5px; border-left: none; border-bottom: none; border-top: none; vertical-align: top; ">
                        <p><span>{{ $quotationModel->quote_booking }}</span></p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><b>Address:</span></p>
                    </td>

                    <td
                        style="width: 400px; padding-left: 5px; border-left: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_address }}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <h4><b>Sale:</b></h4>
                    </td>

                    <td
                        style="width: 150px; padding-left: 5px; border-left: none; border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $sale->name }}</span></p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><b>Mobile:</span></p>
                    </td>

                    <td
                        style="width: 400px; padding-left: 5px; border-left: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_tel }}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <h4><b>Tel:</b></h4>
                    </td>

                    <td
                        style="width: 150px; padding-left: 5px; border-left: none; border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{$sale->phone??'-'}}</span></p>
                    </td>
                </tr>
                <tr>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><b>Tax ID:<p></p></p>
                    </td>

                    <td
                        style="width: 400px; padding-left: 5px; border-left: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_texid ? $customer->customer_texid : '-' }}</span></p>
                    </td>

                    <td style="border: none;"></td>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none; vertical-align: top;">
                        <h4><b>Tour Code:</b></h4>
                    </td>

                    <td
                        style="width: 150px; padding-left: 5px5px; border-left: none; border-bottom: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $quotationModel->quote_tour ? $quotationModel->quote_tour : $quotationModel->quote_tour_code }}</span>
                        </p>
                    </td>
                </tr>
                <tr style="padding: 3px">
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;  border-top: none; vertical-align: top; ">
                        <p><b>Email:</span></p>
                    </td>

                    <td
                        style="width: 400px; padding-left: 5px; border-left: none; border-top: none; vertical-align: top;">
                        <p><span>{{ $customer->customer_email }}</span></p>
                    </td>
                    <td style="border: none;"></td>
                    <td
                        style="width: 100px; padding-left: 5px; border-right: none;   border-top: none; vertical-align: top;">
                        <h4><b>Airline:</b></h4>
                    </td>
                    <td
                        style="width: 150px; padding: 0; text-align: center; border-left: none; border-top: none; background-color: #f9c68f; vertical-align: top;">
                        <p style="margin: 0; padding: 10px;">
                            <span>{{ $airline->travel_name }}</span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="border: none;"></td>
                </tr>
                <tr>
                    <td style="width: 100px; padding-right: 5px; text-align: right;">
                        <p><b>Program:</span></p>
                    </td>

                    <td style="width: 400px; padding-left: 5px; background-color: #f9c68f;">
                        {{-- @php
                            $original_string = $quotationModel->quote_tour_name1 ? $quotationModel->quote_tour_name1 : $quotationModel->quote_tour_name;

                            // Regular expression เพื่อจับส่วนที่เป็นตัวอักษรภาษาอังกฤษและเครื่องหมายขีด
                            $pattern = "/[A-Za-z0-9-]/";

                            // แทนที่ส่วนที่ตรงกับ pattern ด้วยสตริงว่าง
                            $new_string = preg_replace($pattern, '', $original_string);

                            // echo $new_string; // ผลลัพธ์:  สิงคโปร์ เกาะเซ็นโตซ่า ยูนิเวอร์แซล สตูดิโอ (ฟรีเดย์ 1 วัน)
                        @endphp
                        {{-- <p><span>{{ $quotationModel->quote_tour_name1 ? $quotationModel->quote_tour_name1 : $quotationModel->quote_tour_name }}</span> --}}  
                        <p><span>{{ $quotationModel->quote_tour_name }}</span>
                        </p>
                    </td>
                    <td style="border: none;"></td>
                    <td style="width: 100px; padding-left: 5px">
                        <h4><b> Period:</b></h4>
                    </td>
                    <td style="width: 150px; padding: 0; text-align: center; background-color: #f9c68f;">
                        <p style="margin: 0; padding: 10px;">
                            <span>{{ date('j', strtotime($quotationModel->quote_date_start)) }}-{{ thaidate('j F Y', $quotationModel->quote_date_end) }}<p>(<?php echo e($quotationModel->quote_numday); ?>)</p></span>
                        </p>
                    </td>
                </tr>
            </table>


        </div>

        <div style="margin-top: -36px">
            <table style="margin-right: -35px; margin-left: -35px;">
                <tr>
                    <td style="width: 65px; text-align: center; background-color: #f9c68f;">
                        <b>ลำดับ</br>
                            <p>Item</p>
                        </b>
                    </td>

                    <td style="width: 400px; text-align: center; background-color: #f9c68f;"><b>รายการ </br>
                            <p>Descriptons</p>
                        </b></td>
                    <td style="width: 65px; text-align: center; background-color: #f9c68f;"><b>จำนวน </br>
                            <p>Quanily</p>
                        </b></td>
                    <td style="width: 120px; text-align: center; background-color: #f9c68f;"><b>ราคาต่อหน่วย </br>
                            <p>Unit Price</p>
                        </b></td>
                    <td style="width: 120px; text-align: center; background-color: #f9c68f;"><b>ราคารวม </br>
                            <p>Total Amout</p>
                        </b></td>
                </tr>

                <tr>

                    <td style="width: 65px; height: 260px; text-align: center; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                            <p style="margin: 0;">{{ $key + 1 }}</p>
                        @empty
                        @endforelse

                    </td>

                    <td style="width: 270px;  text-align: left; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                            @if ($item->expense_type === 'discount')
                                <p style="margin: 0;">{{ $item->product_name }} <b>(ส่วนลด)</b></p>
                            @else
                                <p style="margin: 0;">{{ $item->product_name }}</p>
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
                                    {{ number_format($item->product_price, 2, '.', ',') }}
                                @else
                                    {{ number_format($item->product_price * 0.03 + $item->product_price, 2, '.', ',') }}
                                @endif
                            </p>
                        @empty
                        @endforelse
                    </td>
                    <td style="width: 120px; text-align: center; vertical-align: top;">
                        @forelse ($productLists as $key => $item)
                            <p style="margin: 0;">{{ number_format($item->product_sum, 2, '.', ',') }}</p>
                        @empty
                        @endforelse
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="width: 465px; text-align: right; background-color: #ffffff;">

                    </td>
                    <td colspan="2" style="width: 185px; text-align: center; background-color: #ffffff;">
                        <h3> รวมเป็นเงิน / Amount </h3>
                    </td>
                    <td style="width: 120px; text-align: center; background-color: #ffffff;">
                        <h3>
                            <p style="margin: 0;">
                                {{ $quotationModel->quote_vat >= 0 ? number_format($quotationModel->quote_grand_total - $quotationModel->quote_vat, 2, '.', ',') : number_format($quotationModel->quote_grand_total, 2, '.', ',') }}
                            </p>
                        </h3>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="width: 465px; text-align: right; background-color: #ffffff;">

                    </td>
                    <td colspan="2" style="width: 185px; text-align: center; background-color: #ffffff;">
                        <h3> ภาษีมูลค่าเพิ่ม / Vat 7%</h3>
                    </td>
                    <td style="width: 120px; text-align: center; background-color: #ffffff;">
                        <h3>
                            <p style="margin: 0;">
                                {{ $quotationModel->quote_vat >= 0 ? number_format($quotationModel->quote_vat, 2, '.', ',') : '-' }}
                            </p>
                        </h3>
                    </td>
                </tr>


                <tr>
                    <td colspan="2" style="width: 465px; text-align: right; background-color: #f9c68f;">
                        <h3> @bathText($quotationModel->quote_grand_total)</h3>
                    </td>
                    <td colspan="2" style="width: 185px; text-align: center; background-color: #f9c68f;">
                        <h3> ยอดรวม / Grand Total</h3>
                    </td>
                    <td style="width: 120px; text-align: center; background-color: #f9c68f;">
                        <h3>
                            <p style="margin: 0;">
                                {{ number_format($quotationModel->quote_grand_total, 2, '.', ',') }}</p>
                        </h3>
                    </td>
                </tr>


            </table>
        </div>

        <footer>

            <div style="line-height: 1; text-align: left; display: block; margin-top: -15px">
                <b>หมายเหตุ : </b> <span style="">-หากไม่ชำระเงินตามกำหนดด้านล่าง ทางบริษัทฯ
                    ขอสงวนสิทธิ์ในการตัดที่นั่งโดยไม่แจ้งให้ทราบล่วงหน้า,-หากชำระมัดจำมาแล้วท่านไม่ชำระส่วนที่เหลือ
                    ขออนุญาตยึดเงินมัดจำตามเงื่อนไขบริษัท
                    -สำเนาพาสปอร์ตกรุณาจัดส่งให้บริษัทก่อนเดินทาง 30 วันผ่านทางไลน์หรืออีเมลล์,-ใบนัดหมายการเดินทาง
                    จะจัดส่งให้ก่อนการเดินทางระยะเวลา 1-3 วัน</span>
            </div>

            <div style="margin-top: -20px">
                <table style="margin-right: -35px; margin-left: -35px;">
                    @if ($quotationModel->quote_payment_type === 'deposit')
                        <tr style="border-right: none;">
                            <td style="width: 100px; padding: 5x; border-right: none; border-bottom: none;">
                                <b>วันที่ชำระเงินมัดจำ</b></td>
                            <td style="width: 110px; border-right: none; border-left: none; border-bottom: none;">
                                {{ thaidate('j F Y', $quotationModel->quote_payment_date) }}</td>
                            <td style="width: 100px; border-right: none; border-left: none; border-bottom: none;">
                                <b>ก่อนเวลา</b></td>
                            <td style="width: 110px; border-right: none; border-left: none; border-bottom: none;">
                                {{ date('H:i', strtotime($quotationModel->quote_payment_date)) }} น.</td>
                            <td style="width: 100px; border-right: none; border-left: none; border-bottom: none;">
                                <b>จำนวนเงิน</b></td>
                            <td
                                style="width: 110px; border-right: none; text-align: center; border-left: none; border-bottom: none;">
                                {{ number_format($quotationModel->quote_payment_total, 2, '.', ',') }}</td>
                            <td style="width: 200px; text-align: right; border-left: none; border-bottom: none;">
                                <b>บาท</b></td>
                        </tr>
                    @else
                        <tr style="border-right: none;">
                            <td style="width: 100px; padding: 5x; border-right: none; border-bottom: none;">
                                <b>วันที่ชำระเงินมัดจำ</b></td>
                            <td style="width: 110px; border-right: none; border-left: none; border-bottom: none;">-
                            </td>
                            <td style="width: 100px; border-right: none; border-left: none; border-bottom: none;">
                                <b>ก่อนเวลา</b></td>
                            <td style="width: 110px; border-right: none; border-left: none; border-bottom: none;">-น.
                            </td>
                            <td style="width: 100px; border-right: none; border-left: none; border-bottom: none;">
                                <b>จำนวนเงิน</b></td>
                            <td style="width: 110px; border-right: none; border-left: none; border-bottom: none;">-
                            </td>
                            <td style="width: 200px; text-align: right; border-left: none; border-bottom: none;">
                                <b>บาท</b></td>
                        </tr>
                    @endif

                    <tr style="border-right: none;">
                        <td style="width: 100px; padding: 5x; border-right: none; border-top: none;">
                            <b>วันที่ชำระยอดเต็ม</b></td>
                        <td style="width: 110px; border-right: none; border-left: none; border-top: none; ">
                            {{ thaidate('j F Y', $quotationModel->quote_payment_date_full) }}</td>
                        <td style="width: 100px; border-right: none; border-left: none; border-top: none;">
                            <b>ก่อนเวลา</b></td>
                            {{ $quotationModel->quote_payment_date_full }};;
                        <td style="width: 110px; border-right: none; border-left: none; border-top: none;">
                            {{ date('H:i', strtotime($quotationModel->quote_payment_date_full)) }} น.</td>
                        <td style="width: 100px; border-right: none; border-left: none; border-top: none;">
                            <b>จำนวนเงิน</b></td>
                        <td
                            style="width: 110px; border-right: none; text-align: center; border-left: none; border-top: none;">
                            {{ number_format($quotationModel->quote_payment_total_full, 2, '.', ',') }}</td>
                        <td style="width: 200px; text-align: right; border-left: none; border-top: none;"><b>บาท</b>
                        </td>
                    </tr>

                </table>
            </div>

            <div style="margin-top: -32px">
                <table style="margin-right: -35px; margin-left: -35px;">
                    <tr style="border-right: none;">
                        <td style="width: 276.6px; padding: 5x; text-align: center;">
                          <b style="color: #fff">ว่าง</b></br>
                            <p>___________________________</p>
                            <p><b>Customer</b></p>
                            <p><b></b></p>
                            <p><b>{{ thaidate('j F Y', $quotationModel->quote_date) }}</b></p>
                        </td>
                        <td style="border: none;"></td>
                        <td style="width: 276.6px; text-align: center;">

                           <b>{{ $sale->name }}</b></br>
                            <p>___________________________</p>
                            <p><b>Sale / Operation</b></p>
                            <p><b>{{ thaidate('j F Y', $quotationModel->quote_date) }}</b></p>
                        </td>
                        <td style="border: none;"></td>
                        <td style="width: 277px; text-align: right; text-align: center;">
                           
                            <img src="{{URL::asset('signature/next_signature_01.png')}}" alt="Image" class="image" style="width: 120px; ">
                        
                            <p><b>ผู้อนุมัติ</b></p>
                            <p><b>{{ thaidate('j F Y', $quotationModel->quote_date) }}</b></p>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-top: -17px">
                กรุณาชำระเงินค่าทัวร์ หรือตั๋วเครื่องบินโดยการโอน
                <p style="margin-top: -5px"><b>ชื่อบัญชี บจก.เน็กซ์ ทริป ฮอลิเดย์</b></p>
            </div>
            <div style="margin-top: -20px; margin-bottom: -300px;">
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

            <b>แจ้งชำระเงิน</b>
            <p style="margin-top: -5px">สามารถแจ้งได้ทุกช่องทาง Line :@nexttripholiday ,อีเมล:nexttripholiday@gmail.com
                หรือทางไลน์กับพนักงานขายที่ท่านทำการจอง</p>



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
