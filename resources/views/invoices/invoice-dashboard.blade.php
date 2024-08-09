<div class="row">
    <h4>Invoice Number : {{ $invoice->invoice_number }}</h4>
    <hr>
    <div class="col-md-12">
        <div class="row">
            <div class="col-6">
                <div class="card border">
                    <div class="card-header bg-primary">
                        <h4 class="mb-0 text-white">รายละเอียดลูกค้า (Customer)</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 ">
                                <table>
                                    <tr>
                                        <td class="text-end"><b>ชื่อลูกค้า :</b> </td>
                                        <td>{{ $customer->customer_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><b>ที่อยู่ :</b> </td>
                                        <td>{{ $customer->customer_address }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><b>Tex ID :</b> </td>
                                        <td>{{ $customer->customer_texid }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><b>เบอร์ติดต่อ :</b> </td>
                                        <td>{{ $customer->customer_tel }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><b>email :</b> </td>
                                        <td>{{ $customer->customer_email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><b>Fax :</b> </td>
                                        <td>{{ $customer->customer_fax ?: '-' }}</td>
                                    </tr>
                                </table>
                            </div>


                        </div>




                    </div>
                </div>
            </div>


            <div class="col-6">
                <div class="card border">
                    <div class="card-header bg-info">
                        <h4 class="mb-0 text-white">รายละเอียดใบจองทัวร์ (Booking Form)</h4>
                    </div>
                    <div class="card-body">
                        <table class="">
                            <tr>
                                <td class="text-end"><b>เลขที่ใบแจ้งหนี้ :</b> </td>
                                <td>{{ $invoice->invoice_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>วันที่ออกใบแจ้งหนี้ :</b> </td>
                                <td>{{ date('d/M/Y', strtotime($invoice->crated_at)) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>เลขที่ใบจองทัวร์ :</b> </td>
                                <td>{{ $invoice->invoice_booking }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>ผู้ขาย :</b> </td>
                                <td>{{ $sale->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>เมลผู้ขาย :</b> </td>
                                <td>{{ $sale->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>เลขที่ทัวร์ :</b> </td>
                                <td>{{ $tour->code }}</td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card border">
                    <div class="card-header bg-danger">
                        <h4 class="mb-0 text-white">รายละเอียดแพคเกจที่ซื้อ/วันเดินทาง</h4>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td class="text-end"><b>ชื่อแพคเกจ:</b></td>
                                <td>{{ $tour->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>สายการบิน:</b></td>
                                <td>{{ $airline->travel_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>ช่วงเวลาเดินทาง:</b></td>
                                <td>{{ date('d', strtotime($booking->start_date)) }}-{{ date('d-M-Y', strtotime($booking->end_date)) }}
                                    <b>({{ $tour->num_day }})</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>ผู้เดินทาง (PAX):</b></td>
                                <td>{{ $booking->total_qty }} ท่าน</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>โฮลเซลล์:</b></td>
                                <td>{{ $wholesale->wholesale_name_th }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>


            <div class="col-6">
                <div class="card border">
                    <div class="card-header bg-success">
                        <h4 class="mb-0 text-white">ยอดรวมสุทธิและกำหนดชำระเงิน</h4>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td class="text-end"><b>ราคารวมสุทธิ:</b></td>
                                <td class="text-success"> <b class=" text-success"
                                        style="">{{ number_format($invoice->invoice_grand_total, 2, '.', ',') }}
                                    </b> บาท</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>ราคา(ตัวอักษร):</b></td>
                                <td class="text-danger"> @bathText($invoice->invoice_grand_total)</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>กำหนดชำระเต็ม:</b></td>
                                <td>ก่อนวันที่ {{ date('d-M-Y : H:m', strtotime($invoice->payment_before_date)) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>วันที่ชำระ: </b></td>
                                <td>{{ $invoice->payment_date ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>สถานะการชำระเงิน:</b></td>
                                <td>
                                    @if ($invoice->payment_before_date < date(now()))
                                        <span class="text-danger">เกินกำหนดชำระเงิน</span>
                                    @else
                                        @if ($invoice->invoice_status === 'wait')
                                            <span class="text-warning">รอชำระเงิน</span>
                                        @endif
                                        @if ($invoice->invoice_status === 'success')
                                            <span class="text-success">ชำระครบแล้ว</span>
                                        @endif
                                        @if ($invoice->invoice_status === 'deposit')
                                            <span class="text-primary">รอชำระเงินเต็มจำนวน</span>
                                        @endif
                                        @if ($invoice->invoice_status === 'cancel')
                                            <span class="text-warning">ลูกค้ายกเลิก</span>
                                        @endif
                                    @endif


                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="card border">
                    <div class="card-header">
                        <label>ข้อมูลการขาย</label>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>ประเภท :  <b class="text-success">ใบแจ้งหนี้</b></td>
                                <td>เลขที่ใบแจ้งหนี้ :  <b class="text-info">#{{$invoice->invoice_number}}</b></td>
                                <td>จำนวน : <b class="text-primary">{{$booking->total_qty}}</b></td>
                                <td><b class="">{{ number_format($invoice->invoice_grand_total, 2, '.', ',') }} .-</b></td>
                                <td>
                                    @if ($invoice->payment_before_date < date(now()))
                                    <span class="text-danger">เกินกำหนดชำระเงิน</span>
                                @else
                                    @if ($invoice->invoice_status === 'wait')
                                        <span class="text-warning">รอชำระเงิน</span>
                                    @endif
                                    @if ($invoice->invoice_status === 'success')
                                        <span class="text-success">ชำระครบแล้ว</span>
                                    @endif
                                    @if ($invoice->invoice_status === 'deposit')
                                        <span class="text-primary">รอชำระเงินเต็มจำนวน</span>
                                    @endif
                                    @if ($invoice->invoice_status === 'cancel')
                                        <span class="text-warning">ลูกค้ายกเลิก</span>
                                    @endif
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>ประเภท :  <b class="text-primary">ใบเพิ่มหนี้</b></td>
                                <td>เลขที่ใบแจ้งหนี้ :  <b class="text-info">#{{$invoice->invoice_number}}</b></td>
                                <td>จำนวน : <b class="text-primary">{{$booking->total_qty}}</b></td>
                                <td><b class="">{{ number_format($invoice->invoice_grand_total, 2, '.', ',') }} .-</b></td>
                                <td>
                                    @if ($invoice->payment_before_date < date(now()))
                                    <span class="text-danger">เกินกำหนดชำระเงิน</span>
                                @else
                                    @if ($invoice->invoice_status === 'wait')
                                        <span class="text-warning">รอชำระเงิน</span>
                                    @endif
                                    @if ($invoice->invoice_status === 'success')
                                        <span class="text-success">ชำระครบแล้ว</span>
                                    @endif
                                    @if ($invoice->invoice_status === 'deposit')
                                        <span class="text-primary">รอชำระเงินเต็มจำนวน</span>
                                    @endif
                                    @if ($invoice->invoice_status === 'cancel')
                                        <span class="text-warning">ลูกค้ายกเลิก</span>
                                    @endif
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>ประเภท :  <b class="text-danger">ใบลดหนี้</b></td>
                                <td>เลขที่ใบแจ้งหนี้ :  <b class="text-info">#{{$invoice->invoice_number}}</b></td>
                                <td>จำนวน : <b class="text-primary">{{$booking->total_qty}}</b></td>
                                <td><b class="">{{ number_format($invoice->invoice_grand_total, 2, '.', ',') }} .-</b></td>
                                <td>
                                    @if ($invoice->payment_before_date < date(now()))
                                    <span class="text-danger">เกินกำหนดชำระเงิน</span>
                                @else
                                    @if ($invoice->invoice_status === 'wait')
                                        <span class="text-warning">รอชำระเงิน</span>
                                    @endif
                                    @if ($invoice->invoice_status === 'success')
                                        <span class="text-success">ชำระครบแล้ว</span>
                                    @endif
                                    @if ($invoice->invoice_status === 'deposit')
                                        <span class="text-primary">รอชำระเงินเต็มจำนวน</span>
                                    @endif
                                    @if ($invoice->invoice_status === 'cancel')
                                        <span class="text-warning">ลูกค้ายกเลิก</span>
                                    @endif
                                @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
