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
                                <td style="font-size: 30px; border-bottom: 1px solid;" class="text-primary align-top"> <b  style="" class=" text-primary" id="TotalAllLabel"> </b> บาท  </td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>ชำระแล้ว:</b></td>
                                <td class="text-success"> <b  class=" text-success" id=""></b>00.00 บาท</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>ยอดคงค้าง:</b></td>
                                <td class="text-danger"> <b  class=" text-danger" id=""></b>00.00 บาท</td>
                            </tr>
                            <tr>
                                <td class="text-end"><b>ชำระให้โฮลเซลล์:</b></td>
                                <td class="text-info"> <b  class=" text-info" id=""></b>00.00 บาท</td>
                            </tr>
                           
                        </table>

                    </div>
                </div>
            </div>

             @php
                 $totalAll = $invoice->invoice_grand_total;
             @endphp


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
                                <td>จำนวน : <b class="text-primary">{{$booking->total_qty}} ท่าน</b></td>
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

                            {{-- ใบเพิ่มหนี้ --}}
                            @forelse ($debts as $item)
                            @php
                            $totalAll += $item->debt_grand_total;
                            @endphp

                            <tr>
                                <td>ประเภท :  <b class="text-primary">ใบเพิ่มหนี้</b></td>
                                <td>เลขที่ใบแจ้งหนี้ :  <b class="text-info">#{{$item->debt_number}}</b></td>
                                <td>จำนวน : <b class="text-primary">{{$item->total_qty}} ท่าน</b></td>
                                <td><b class="">{{ number_format($item->debt_grand_total, 2, '.', ',') }} .-</b></td>
                                <td>
                                    @if ($item->payment_before_date < date(now()))
                                    <span class="text-danger">เกินกำหนดชำระเงิน</span>
                                @else
                                    @if ($item->debt_status === 'wait')
                                        <span class="text-warning">รอชำระเงิน</span>
                                    @endif
                                    @if ($item->debt_status === 'success')
                                        <span class="text-success">ชำระครบแล้ว</span>
                                    @endif
                                    @if ($item->debt_status === 'deposit')
                                        <span class="text-primary">รอชำระเงินเต็มจำนวน</span>
                                    @endif
                                    @if ($item->debt_status === 'cancel')
                                        <span class="text-warning">ลูกค้ายกเลิก</span>
                                    @endif
                                @endif
                                </td>
                            </tr>
                            @empty
                                
                            @endforelse
                            

                              {{-- ใบเพิ่มหนี้ --}}
                              @forelse ($creditNotes as $item)
                              @php
                              $totalAll += $item->credit_note_grand_total;
                              @endphp
  
                              <tr>
                                  <td>ประเภท :  <b class="text-danger">ใบลดหนี้</b></td>
                                  <td>เลขที่ใบแจ้งหนี้ :  <b class="text-info">#{{$item->credit_note_number}}</b></td>
                                  <td>จำนวน : <b class="text-primary">{{$item->total_qty}} ท่าน</b></td>
                                  <td><b class="">{{ number_format($item->credit_note_grand_total, 2, '.', ',') }} .-</b></td>
                                  <td>
                                      @if ($item->payment_before_date < date(now()))
                                      <span class="text-danger">เกินกำหนดชำระเงิน</span>
                                  @else
                                      @if ($item->credit_note_status === 'wait')
                                          <span class="text-warning">รอคืนเงิน</span>
                                      @endif
                                      @if ($item->credit_note_status === 'success')
                                          <span class="text-success">คืนเงินครบแล้ว</span>
                                      @endif

                                      @if ($item->credit_note_status === 'deposit')
                                          <span class="text-primary">รอชำระเงินเต็มจำนวน</span>
                                      @endif
                                      @if ($item->credit_note_status === 'cancel')
                                          <span class="text-warning">ยกเลิก</span>
                                      @endif
                                  @endif
                                  </td>
                              </tr>
                              @empty
                                  
                              @endforelse

                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<input type="hidden" id="TotalAll" value="{{$totalAll}}">


<script>
    
    $(document).ready(function (){
        // ฟังก์ชันจัดรูปแบบตัวเลข
        function formatNumber(num) {
                return parseFloat(num).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

        var TotalAll =  $('#TotalAll').val();
        $('#TotalAllLabel').html(formatNumber(TotalAll));
    });
</script>
