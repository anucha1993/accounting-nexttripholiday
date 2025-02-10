<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="th">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quotationModel->quote_number }}</title>
    <style>
        body {
            font-family: 'sarabun_new', sans-serif;
            font-size: 20px;
            margin: 0;
            padding: 0;
            width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        header, footer {
            width: 100%;
            padding: 10px 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        td, th {
            border: 2px solid #ffaa50;
            padding: 10px;
            background-color: #fff;
            text-align: left;
            font-size: 18px;
        }
        h5, h4, h3, p, b {
            margin: 0;
        }
        .company-info, .quotation-header, .quotation-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .company-info img {
            max-width: 150px;
            height: auto;
        }
        .company-text {
            width: 55%;
        }
        .quotation-header {
            text-align: right;
            width: 30%;
        }
        .quote-number {
            background-color: #f9c68f;
            padding: 10px;
            font-weight: bold;
        }
        .quotation-details {
            margin-top: 20px;
        }
        .quotation-table th, .quotation-table td {
            text-align: center;
            padding: 10px;
        }
        .quotation-table th {
            background-color: #f9c68f;
            font-weight: bold;
        }
        .grand-total {
            background-color: #f9c68f;
            text-align: center;
            font-size: 20px;
        }
        .notes {
            margin-top: 10px;
            font-size: 16px;
        }
        .bank-details {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        .bank-details div {
            width: 24%;
        }

        /* Fix width for all devices */
        @media (max-width: 800px) {
            body {
                width: 800px;
            }
        }
    </style>
</head>
<body>

    <header>
        <div class="company-info">
            <div style="width: 15%;">
                <img src="{{ asset('logo/Logo-docs.png') }}" alt="Logo">
            </div>
            <div class="company-text">
                <h5>บริษัท เน็กซ์ ทริป ฮอลิเดย์ จำกัด (สำนักงานใหญ่)</h5>
                <p>222/2 โกลเด้นทาวน์ บางนา-สวนหลวง แขวงดอกไม้ เขตประเวศ กทม 10250</p>
                <p>โทรศัพท์: 02-136-9144 อัตโนมัติ 16 คู่สาย โทรสาร(Fax): 02-136-9146</p>
                <p>Hotline: 091-091-6364 , 091-091-6463</p>
                <p>TAT License: 11/07440 , TTAA License: 1469</p>
                <p>Website: https://www.nexttripholiday.com , Email : nexttripholiday@gmail.com</p>
            </div>
            <div class="quotation-header">
                <h5>ใบจองทัวร์ / ใบเสนอราคา</h5>
                <h5><b>Booking / Quotation</b></h5>
                <h5><b>สำหรับลูกค้า</b> <span style="font-size: 14px;">(ไม่ใช่ใบกำกับภาษี)</span></h5>
                <div class="quote-number">{{ $quotationModel->quote_number }}</div>
            </div>
        </div>

        <div class="quotation-details">
            <table>
                <tr>
                    <td><b>Customer ID:</b></td>
                    <td>{{ $customer->customer_number }}</td>
                    <td></td>
                    <td><b>Date:</b></td>
                    <td>{{date('d/m/Y',$quotationModel->quote_date) }}</td>
                </tr>
                <tr>
                    <td><b>Name:</b></td>
                    <td>{{ $customer->customer_name }}</td>
                    <td></td>
                    <td><b>Booking No:</b></td>
                    <td>{{ $quotationModel->quote_booking }}</td>
                </tr>
                <tr>
                    <td><b>Address:</b></td>
                    <td>{{ $customer->customer_address }}</td>
                    <td></td>
                    <td><b>Sale:</b></td>
                    <td>{{ $sale->name }}</td>
                </tr>
                <tr>
                    <td><b>Mobile:</b></td>
                    <td>{{ $customer->customer_tel }}</td>
                    <td></td>
                    <td><b>Tel:</b></td>
                    <td>091-091-6364</td>
                </tr>
                <tr>
                    <td><b>Fax:</b></td>
                    <td>{{ $customer->customer_fax ? $customer->customer_fax : '-' }}</td>
                    <td></td>
                    <td><b>Tour Code:</b></td>
                    <td>{{ $quotationModel->quote_tour ?: $quotationModel->quote_tour_code }}</td>
                </tr>
                <tr>
                    <td><b>Email:</b></td>
                    <td>{{ $customer->customer_email }}</td>
                    <td></td>
                    <td><b>Airline:</b></td>
                    <td style="background-color: #f9c68f;">{{ $airline->travel_name }}</td>
                </tr>
                <tr>
                    <td><b>Program:</b></td>
                    <td  style="background-color: #f9c68f;"><p><span>{{$quotationModel->quote_tour_name1 ? $quotationModel->quote_tour_name1 : $quotationModel->quote_tour_name}}</span></p></td>
                    <td></td>
                    <td>Period: </td>
                    <td style="background-color: #f9c68f;">{{ date('d', strtotime($quotationModel->quote_date_start)) }}-{{  thaidate('j F Y',$quotationModel->quote_date_end) }}</td>
                </tr>
            </table>
        </div>

        <table class="quotation-table">
            <tr>
                <th>ลำดับ <br> Item</th>
                <th>รายการ <br> Descriptons</th>
                <th>จำนวน <br> Quantity</th>
                <th>ราคาต่อหน่วย <br> Unit Price</th>
                <th>ราคารวม <br> Total Amount</th>
            </tr>
            <tbody>
                @forelse ($productLists as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->product_name }} {{ $item->expense_type === 'discount' ? '(ส่วนลด)' : '' }}</td>
                        <td>{{ $item->product_qty }}</td>
                        <td>
                            @if ($item->withholding_tax === 'N')
                                {{ number_format($item->product_price, 2, '.', ',') }}
                            @else
                                {{ number_format(($item->product_price * 0.03) + $item->product_price, 2, '.', ',') }}
                            @endif
                        </td>
                        <td>{{ number_format($item->product_sum, 2, '.', ',') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No data available</td>
                    </tr>
                @endforelse
            
                @for ($i = 0; $i < 10 - count($productLists); $i++)
                    <tr>
                        <td style="color: #ffffff00">{{ count($productLists) + $i + 1 }}</td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                    </tr>
                @endfor
            </tbody>
            
            <tr>
                <td colspan="3"></td>
                <td class="grand-total"><h3>ยอดรวม / Grand Total</h3></td>
                <td class="grand-total"><h3>{{ number_format(($quotationModel->quote_vat_exempted_amount + $quotationModel->quote_pre_tax_amount) - $quotationModel->quote_discount, 2, '.', ',') }}</h3></td>
            </tr>
        </table>

        <div class="notes">
            <b>หมายเหตุ : </b> <span>-หากไม่ชำระเงินตามกำหนดด้านล่าง ทางบริษัทฯ ขอสงวนสิทธิ์ในการตัดที่นั่งโดยไม่แจ้งให้ทราบล่วงหน้า,-หากชำระมัดจำมาแล้วท่านไม่ชำระส่วนที่เหลือ ขออนุญาตยึดเงินมัดจำตามเงื่อนไขบริษัท</span>
        </div>
        <br>

        <div style="margin-top: -20px">
            <table>
                @if ($quotationModel->quote_payment_type === 'deposit')
                <tr>
                    <td><b>วันที่ชำระเงินมัดจำ</b></td>
                    <td>{{ thaidate('j F Y',$quotationModel->quote_payment_date) }}</td>
                    <td><b>ก่อนเวลา</b></td>
                    <td>{{date('H:m',strtotime($quotationModel->quote_payment_date))}} น.</td>
                    <td><b>จำนวนเงิน</b></td>
                    <td>{{  number_format($quotationModel->quote_payment_total  , 2, '.', ',')}}</td>
                    <td><b>บาท</b></td>
                </tr>
                @else
                <tr>
                    <td><b>วันที่ชำระเงินมัดจำ</b></td>
                    <td>-</td>
                    <td><b>ก่อนเวลา</b></td>
                    <td>-น.</td>
                    <td><b>จำนวนเงิน</b></td>
                    <td>-</td>
                    <td><b>บาท</b></td>
                </tr>
                @endif
               
                <tr>
                    <td><b>วันที่ชำระยอดเต็ม</b></td>
                    <td>{{ thaidate('j F Y',$quotationModel->quote_payment_date_full) }}</td>
                    <td><b>ก่อนเวลา</b></td>
                    <td>{{date('H:m',strtotime($quotationModel->quote_payment_date_full))}} น.</td>
                    <td><b>จำนวนเงิน</b></td>
                    <td>{{  number_format($quotationModel->quote_payment_total_full  , 2, '.', ',')}}</td>
                    <td><b>บาท</b></td>
                </tr>
            </table>
        </div>

    </header>

    <footer>
        <div class="bank-details">
            <div><b>ธนาคาร</b><br>กรุงศรีอยุธยา</div>
            <div><b>ประเภทบัญชี</b><br>ออมทรัพย์</div>
            <div><b>สาขา</b><br>เมกาบางนา</div>
            <div><b>เลขบัญชี</b><br>688-1-28842-5</div>
        </div>
        <b>แจ้งชำระเงิน</b>
        <p style="margin-top: -5px">สามารถแจ้งได้ทุกช่องทาง Line :@nexttripholiday ,อีเมล:nexttripholiday@gmail.com หรือทางไลน์กับพนักงานขายที่ท่านทำการจอง</p>
    </footer>

</body>
</html>
