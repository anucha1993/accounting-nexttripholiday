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
            font-size: 16px;
            margin: 0;
            padding: 20px;
            width: 800px;
            margin-left: auto;
            margin-right: auto;
            background-color: #f5f5f5;
        }
        .content-wrapper {
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        header, footer {
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        td, th {
            border: 1px solid #ffaa50;
            padding: 8px;
            background-color: #fff;
            text-align: left;
            font-size: 14px;
            vertical-align: top;
        }
        h5, h4, h3, p, b {
            margin: 0;
            padding: 2px 0;
        }
        .header-section {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .logo-section {
            display: table-cell;
            width: 15%;
            vertical-align: top;
        }
        .company-info {
            display: table-cell;
            width: 55%;
            vertical-align: top;
            padding-left: 10px;
        }
        .quote-header {
            display: table-cell;
            width: 30%;
            vertical-align: top;
            text-align: center;
            padding-left: 10px;
        }
        .company-info h5 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-info p {
            font-size: 12px;
            line-height: 1.3;
            margin: 1px 0;
        }
        .quote-header h5 {
            font-size: 14px;
            margin: 2px 0;
        }
        .quote-number {
            background-color: #f9c68f;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
            display: inline-block;
        }
        .customer-table td {
            padding: 5px 8px;
            font-size: 13px;
        }
        .customer-table .label-cell {
            width: 120px;
            font-weight: bold;
        }
        .customer-table .data-cell {
            width: 200px;
        }
        .customer-table .empty-cell {
            width: 20px;
            border: none;
            background: none;
        }
        .highlight-cell {
            background-color: #f9c68f !important;
            text-align: center;
            font-weight: bold;
        }
        .quotation-table {
            margin-top: 15px;
        }
        .quotation-table th {
            background-color: #f9c68f;
            font-weight: bold;
            text-align: center;
            padding: 10px 5px;
            font-size: 13px;
        }
        .quotation-table td {
            text-align: center;
            padding: 8px 5px;
            font-size: 13px;
        }
        .quotation-table .item-desc {
            text-align: left;
            width: 40%;
        }
        .quotation-table .item-number {
            width: 8%;
        }
        .quotation-table .item-qty {
            width: 12%;
        }
        .quotation-table .item-price {
            width: 20%;
        }
        .quotation-table .item-total {
            width: 20%;
        }
        .summary-row td {
            background-color: #f9f9f9;
            font-weight: bold;
            padding: 8px;
        }
        .grand-total-row td {
            background-color: #f9c68f !important;
            font-weight: bold;
            text-align: center;
            font-size: 14px;
            padding: 10px;
        }
        .notes {
            margin: 15px 0;
            font-size: 12px;
            line-height: 1.4;
        }
        .payment-table td {
            font-size: 13px;
            padding: 6px 8px;
        }
        .signature-table {
            margin-top: 20px;
        }
        .signature-table td {
            text-align: center;
            padding: 15px 5px;
            font-size: 13px;
            border: 1px solid #ffaa50;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            margin: 10px 0;
            padding-bottom: 20px;
        }
        .bank-info {
            margin-top: 15px;
            font-size: 12px;
        }
        .bank-details {
            display: table;
            width: 100%;
            margin: 10px 0;
        }
        .bank-details > div {
            display: table-cell;
            width: 25%;
            padding: 5px;
            font-size: 12px;
        }
        .text-cancel {
            transform: rotate(45deg);
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
            font-size: 60px;
            color: rgba(255, 0, 0, 0.15);
            font-weight: bold;
            z-index: 1000;
            pointer-events: none;
        }
        .empty-rows td {
            height: 25px;
            border-left: 1px solid #ffaa50;
            border-right: 1px solid #ffaa50;
            border-bottom: none;
        }
        .invisible-text {
            color: transparent;
        }
        @media (max-width: 800px) {
            body {
                width: 800px;
            }
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        @if ($quotationModel->quote_status === 'cancel')
        <div class="text-cancel">
            ยกเลิก {{$quotationModel->quote_cancel_note}}
        </div>
        @endif

        <header>
            <div class="header-section">
                <div class="logo-section">
                    <img src="{{ asset('logo/Logo-docs.png') }}" alt="Logo" style="max-width: 100%; height: auto;">
                </div>
                <div class="company-info">
                    <h5>บริษัท เน็กซ์ ทริป ฮอลิเดย์ จำกัด (สำนักงานใหญ่)</h5>
                    <p>222/2 โกลเด้นทาวน์ บางนา-สวนหลวง แขวงดอกไม้ เขตประเวศ กทม 10250</p>
                    <p>โทรศัพท์: 02-136-9144 อัตโนมัติ 16 คู่สาย โทรสาร(Fax): 02-136-9146</p>
                    <p>Hotline: 091-091-6364 , 091-091-6463</p>
                    <p>TAT License: 11/07440 , TTAA License: 1469</p>
                    <p>Website: https://www.nexttripholiday.com , Email : nexttripholiday@gmail.com</p>
                </div>
                <div class="quote-header">
                    <h5>ใบจองทัวร์ / ใบเสนอราคา</h5>
                    <h5><b>Booking / Quotation</b></h5>
                    <h5><b>สำหรับลูกค้า</b> <span style="font-size: 12px;">(ไม่ใช่ใบกำกับภาษี)</span></h5>
                    <div class="quote-number">{{ $quotationModel->quote_number }}</div>
                </div>
            </div>

            <table class="customer-table">
                <tr>
                    <td class="label-cell"><b>Customer ID:</b></td>
                    <td class="data-cell">{{ $customer->customer_number }}</td>
                    <td class="empty-cell"></td>
                    <td class="label-cell"><b>Date:</b></td>
                    <td class="data-cell">{{ thaidate('j F Y', $quotationModel->quote_date) }}</td>
                </tr>
                <tr>
                    <td class="label-cell"><b>Name:</b></td>
                    <td class="data-cell">{{ $customer->customer_name }}</td>
                    <td class="empty-cell"></td>
                    <td class="label-cell"><b>Booking No:</b></td>
                    <td class="data-cell">{{ $quotationModel->quote_booking }}</td>
                </tr>
                <tr>
                    <td class="label-cell"><b>Address:</b></td>
                    <td class="data-cell">{{ $customer->customer_address }}</td>
                    <td class="empty-cell"></td>
                    <td class="label-cell"><b>Sale:</b></td>
                    <td class="data-cell">{{ $sale->name }}</td>
                </tr>
                <tr>
                    <td class="label-cell"><b>Mobile:</b></td>
                    <td class="data-cell">{{ $customer->customer_tel }}</td>
                    <td class="empty-cell"></td>
                    <td class="label-cell"><b>Tel:</b></td>
                    <td class="data-cell">091-091-6364</td>
                </tr>
                <tr>
                    <td class="label-cell"><b>Tax ID:</b></td>
                    <td class="data-cell">{{ $customer->customer_texid ? $customer->customer_texid : '-' }}</td>
                    <td class="empty-cell"></td>
                    <td class="label-cell"><b>Tour Code:</b></td>
                    <td class="data-cell">{{ $quotationModel->quote_tour ?: $quotationModel->quote_tour_code }}</td>
                </tr>
                <tr>
                    <td class="label-cell"><b>Email:</b></td>
                    <td class="data-cell">{{ $customer->customer_email }}</td>
                    <td class="empty-cell"></td>
                    <td class="label-cell"><b>Airline:</b></td>
                    <td class="data-cell highlight-cell">{{ $airline->travel_name }}</td>
                </tr>
                <tr>
                    <td class="label-cell"><b>Program:</b></td>
                    <td class="data-cell highlight-cell">
                        @php
                            $original_string = $quotationModel->quote_tour_name1 ? $quotationModel->quote_tour_name1 : $quotationModel->quote_tour_name;
                            $pattern = "/[A-Za-z0-9-]/";
                            $new_string = preg_replace($pattern, '', $original_string);
                        @endphp
                        {{ $new_string }}
                    </td>
                    <td class="empty-cell"></td>
                    <td class="label-cell"><b>Period:</b></td>
                    <td class="data-cell highlight-cell">{{ date('d', strtotime($quotationModel->quote_date_start)) }}-{{ thaidate('j F Y', $quotationModel->quote_date_end) }}</td>
                </tr>
            </table>

        <table class="quotation-table">
            <tr>
                <th class="item-number">ลำดับ<br>Item</th>
                <th class="item-desc">รายการ<br>Descriptions</th>
                <th class="item-qty">จำนวน<br>Quantity</th>
                <th class="item-price">ราคาต่อหน่วย<br>Unit Price</th>
                <th class="item-total">ราคารวม<br>Total Amount</th>
            </tr>
            @forelse ($productLists as $key => $item)
                <tr>
                    <td class="item-number">{{ $key + 1 }}</td>
                    <td class="item-desc">{{ $item->product_name }} {{ $item->expense_type === 'discount' ? '(ส่วนลด)' : '' }}</td>
                    <td class="item-qty">{{ $item->product_qty }}</td>
                    <td class="item-price">
                        @if ($item->withholding_tax === 'N')
                            {{ number_format($item->product_price, 2, '.', ',') }}
                        @else
                            {{ number_format(($item->product_price * 0.03) + $item->product_price, 2, '.', ',') }}
                        @endif
                    </td>
                    <td class="item-total">{{ number_format($item->product_sum, 2, '.', ',') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #999;">No data available</td>
                </tr>
            @endforelse
            
            @for ($i = 0; $i < (8 - count($productLists)); $i++)
                <tr class="empty-rows">
                    <td class="invisible-text">{{ count($productLists) + $i + 1 }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
            
            <tr class="summary-row">
                <td colspan="3"></td>
                <td><b>รวมเป็นเงิน / Amount</b></td>
                <td><b>{{ $quotationModel->quote_vat >= 0 ? number_format($quotationModel->quote_grand_total - $quotationModel->quote_vat, 2, '.', ',') : number_format($quotationModel->quote_grand_total, 2, '.', ',') }}</b></td>
            </tr>
            <tr class="summary-row">
                <td colspan="3"></td>
                <td><b>ภาษีมูลค่าเพิ่ม / Vat 7%</b></td>
                <td><b>{{ $quotationModel->quote_vat >= 0 ? number_format($quotationModel->quote_vat, 2, '.', ',') : '0.00' }}</b></td>
            </tr>
            <tr class="grand-total-row">
                <td colspan="2"><b>@bathText($quotationModel->quote_grand_total)</b></td>
                <td><b>ยอดรวม / Grand Total</b></td>
                <td colspan="2"><b>{{ number_format($quotationModel->quote_grand_total, 2, '.', ',') }}</b></td>
            </tr>
        </table>

        <div class="notes">
            <b>หมายเหตุ :</b> -หากไม่ชำระเงินตามกำหนดด้านล่าง ทางบริษัทฯ ขอสงวนสิทธิ์ในการตัดที่นั่งโดยไม่แจ้งให้ทราบล่วงหน้า -หากชำระมัดจำมาแล้วท่านไม่ชำระส่วนที่เหลือ ขออนุญาตยึดเงินมัดจำตามเงื่อนไขบริษัท -สำเนาพาสปอร์ตกรุณาจัดส่งให้บริษัทก่อนเดินทาง 30 วันผ่านทางไลน์หรืออีเมลล์ -ใบนัดหมายการเดินทาง จะจัดส่งให้ก่อนการเดินทางระยะเวลา 1-3 วัน
        </div>

        <table class="payment-table">
            @if ($quotationModel->quote_payment_type === 'deposit')
            <tr>
                <td><b>วันที่ชำระเงินมัดจำ</b></td>
                <td>{{ thaidate('j F Y',$quotationModel->quote_payment_date) }}</td>
                <td><b>ก่อนเวลา</b></td>
                <td>{{ date('H:i', strtotime($quotationModel->quote_payment_date)) }} น.</td>
                <td><b>จำนวนเงิน</b></td>
                <td style="text-align: right;">{{ number_format($quotationModel->quote_payment_total, 2, '.', ',') }}</td>
                <td><b>บาท</b></td>
            </tr>
            @else
            <tr>
                <td><b>วันที่ชำระเงินมัดจำ</b></td>
                <td>-</td>
                <td><b>ก่อนเวลา</b></td>
                <td>- น.</td>
                <td><b>จำนวนเงิน</b></td>
                <td style="text-align: right;">-</td>
                <td><b>บาท</b></td>
            </tr>
            @endif
            <tr>
                <td><b>วันที่ชำระยอดเต็ม</b></td>
                <td>{{ thaidate('j F Y',$quotationModel->quote_payment_date_full) }}</td>
                <td><b>ก่อนเวลา</b></td>
                <td>{{ date('H:i', strtotime($quotationModel->quote_payment_date_full)) }} น.</td>
                <td><b>จำนวนเงิน</b></td>
                <td style="text-align: right;">{{ number_format($quotationModel->quote_payment_total_full, 2, '.', ',') }}</td>
                <td><b>บาท</b></td>
            </tr>
        </table>

        <table class="signature-table">
            <tr>
                <td>
                     <span style="color: transparent;">ว่าง</span><br>
                    <div class="signature-line"></div>
                    <b>Customer</b><br>
                    <b>{{ thaidate('j F Y', $quotationModel->quote_date) }}</b>
                </td>
                <td style="border: none; width: 20px;"></td>
                <td>
                     <b>{{ $sale->name }}</b><br>
                    <span style="color: transparent;">ว่าง</span><br>
                   
                    <div class="signature-line"></div>
                    <b>Sale / Operation</b><br>
                    <b>{{ thaidate('j F Y', $quotationModel->quote_date) }}</b>
                </td>
                <td style="border: none; width: 20px;"></td>
                <td>
<img src="{{URL::asset('signature/next_signature_01.png')}}" alt="Image" class="image" style="width: 100px; ">
                    <span style="color: transparent;">ว่าง</span><br>
                    <b>ผู้อนุมัติ</b><br>
                    <b>{{ thaidate('j F Y', $quotationModel->quote_date) }}</b>
                </td>
            </tr>
        </table>

    </header>

    <footer>
        <div class="bank-info">
            กรุณาชำระเงินค่าทัวร์ หรือตั๋วเครื่องบินโดยการโอน<br>
            <b>ชื่อบัญชี บจก.เน็กซ์ ทริป ฮอลิเดย์</b>
        </div>
        
        <div class="bank-details">
            <div><b>ธนาคาร</b><br>กรุงศรีอยุธยา</div>
            <div><b>ประเภทบัญชี</b><br>ออมทรัพย์</div>
            <div><b>สาขา</b><br>เมกาบางนา</div>
            <div><b>เลขบัญชี</b><br>688-1-28842-5</div>
        </div>
        
        <div style="margin-top: 10px; font-size: 12px;">
            <b>แจ้งชำระเงิน</b><br>
            สามารถแจ้งได้ทุกช่องทาง Line: @nexttripholiday, อีเมล: nexttripholiday@gmail.com หรือทางไลน์กับพนักงานขายที่ท่านทำการจอง
        </div>
    </footer>

    </div>
</body>
</html>
