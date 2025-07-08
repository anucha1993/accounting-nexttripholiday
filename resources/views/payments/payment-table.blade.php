<div class="col-md-12">
    <div class="card info-card">
        <div class="card-header">
            <i class="fas fa-credit-card me-2"></i>รายการชำระเงิน
            <a href="javascript:void(0)" class="float-end text-white" onclick="toggleAccordion('table-payment', 'toggle-arrow-payment')">
                <i class="fas fa-chevron-down" id="toggle-arrow-payment"></i>
            </a>
        </div>
        <div class="card-body" id="table-payment" style="display: block">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>เลขที่ชำระ</th>
                            <th>วันที่ชำระ</th>
                            <th>รายละเอียด</th>
                            <th class="text-end">จำนวนเงิน</th>
                            <th class="text-center">ไฟล์แนบ</th>
                            <th class="text-center">ประเภท</th>
                            <th class="text-center">ใบเสร็จ</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    @php
                        $paymentTotal = 0;
                        $paymentDebitTotal = 0;
                    @endphp
                   
                    <tbody>
                        
                        @forelse ($payments as $key => $item)
                            <tr style="{{$item->payment_type === 'refund' ? "background-color: rgb(250, 163, 163)" :'' }}">
                                <td>{{ ++$key }}</td>
                                <td>
                                    {{ $item->payment_number }}
                                </td>
                                <td>
                                    {{ date('d-m-Y H:m:s', strtotime($item->payment_in_date)) }}
                                </td>
                                <td>

                                    @if ($item->payment_method === 'cash')
                                        เงินสด </br>
                                    @endif
                                    @if ($item->payment_method === 'transfer-money')
                                        โอนเงิน</br>
                                        {{-- เช็คธนาคาร : {{ $item->bank_name }} --}}
                                    @endif
                                    @if ($item->payment_method === 'check')
                                        เช็ค</br>
                                        {{-- โอนเข้าบัญชี : {{ $item->payment_bank }} </br>
                                        เลขที่เช็ค : {{ $item->payment_check_number }} </br> --}}
                                    @endif

                                    @if ($item->payment_method === 'credit')
                                        บัตรเครดิต </br>
                                        {{-- เลขที่สลิป : {{ $item->payment_credit_slip_number }} </br> --}}
                                    @endif

                                </td>
                                <td>

                                    @if ($item->payment_status === 'cancel')
                                        
                                    @else
                                         @php
                                         
                                             $paymentTotal += $item->payment_total - $item->payment_refund_total;
                                         @endphp
                                        {{ number_format($item->payment_total - $item->payment_refund_total , 2, '.', ',') }}
                                    @endif

                                </td>
                                <td>
                                    @if ($item->payment_file_path)
                                    <a href="{{ asset('storage/' . $item->payment_file_path) }}" class="dropdown-item"
                                        onclick="openPdfPopup(this.href); return false;"><i
                                            class="fa fa-file text-danger"></i> สลิปโอน</a>
                                    @else
                                        -
                                    @endif

                                    {{-- @if ($item->payment_status === 'cancel' || $item->payment_status === 'refund')
                                    <a href="{{ asset('storage/' . $item->payment_cancel_file_path) }}" class="dropdown-item"
                                        onclick="openPdfPopup(this.href); return false;"><i
                                            class="fa fa-file text-danger"></i> สลิปคืนเงิน</a>
                                    @else
                                    @endif --}}

                                </td>
                                <td>

                                    @if ($item->payment_status === 'cancel')
                                        -
                                    @else
                                        @if ($item->payment_type === 'deposit')
                                            ชำระมัดจำ
                                        @elseif($item->payment_type === 'full')
                                            ชำระเงินเต็มจำนวน
                                        @elseif($item->payment_type === 'refund')
                                             คืนเงิน
                                        @endif
                                    @endif


                                </td>
                                <td>
                                    @if ($item->payment_type !== 'refund')
                                    <a href="{{ route('mpdf.payment', $item->payment_id) }}" onclick="openPdfPopup(this.href); return false;"><i
                                        class="fa fa-print text-danger"></i> พิมพ์</a>
                                    @endif
                                    <a class="dropdown-item " href=""><i class="fas fa-envelope text-info"></i>ส่งเมล</a>
                                </td>
                                
                              

                                <td>
                                    @if ($item->payment_status === 'cancel')
                                        <span class="badge rounded-pill bg-danger">ยกเลิก</span>
                                    @elseif ($item->payment_type === 'refund')
                                        @if ($item->payment_file_path !== null)
                                            <span class="badge rounded-pill bg-success">คืนเงินแล้ว</span>
                                        @else
                                            <span class="badge rounded-pill bg-warning">รอคืนเงิน</span>
                                        @endif
                                    @elseif ($item->payment_status === 'success')
                                        <span class="badge rounded-pill bg-success">สำเร็จ</span>
                                    @elseif ($item->payment_status === 'wait')
                                        <span class="badge rounded-pill bg-warning">รอแนบสลิป</span>
                                    @elseif ($item->payment_status === null)
                                        <span class="badge rounded-pill bg-secondary">ไม่มีข้อมูล</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->payment_status !== 'cancel')
                                        <a class="dropdown-item payment-modal"
                                            href="{{ route('payment.edit', $item->payment_id) }}"><i
                                                class="fa fa-edit text-info"></i>
                                            แก้ไข</a>

                                     <a class="dropdown-item text-danger payment-modal-cancel" href="{{ route('payment.cancelModal', $item->payment_id) }}"><i class=" fas fa-minus-circle"></i> ยกเลิก</a>
                                    @else
                                    {{$item->payment_cancel_note}}

                                    <a href="{{route('payment.RefreshCancel',$item->payment_id)}}" class="dropdown-item text-primary" onclick="return confirm('ยืนยันการคืนสถานะ');"> <i class="fas fa-recycle"></i> นำกลับมาใช้ใหม่ </a>
                                    @endif

                                    <a href="{{route('payment.delete',$item->payment_id)}}" onclick="return confirm('ยืนยันการลบ');"><i class="fa fa-trash text-danger"></i> ลบ</a>



                                </td>
                            </tr>

                        @empty

                        @endforelse

                    
                        <tr>
                             {{-- {{$quotation->GetDeposit()}}
                             {{$quotation->Refund()}} --}}

                            <td align="right" class="text-success" colspan="7"><b>(@bathText($quotation->GetDeposit()- $quotation->Refund()))</b></td>
                            <td align="center" class="text-success" ><b>{{number_format($quotation->GetDeposit()- $quotation->Refund(),2)}}</b></td>
                            <td align="center" class="text-danger" colspan="2"><b>( ยอดค้างชำระ : {{ number_format($quotation->quote_grand_total - $quotation->GetDeposit()+$quotation->Refund() , 2, '.', ',') }} )</b></td>
                        </tr>



                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>



{{-- payment-modal --}}
<div class="modal fade bd-example-modal-sm modal-xl" id="modal-payment-edit" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

{{-- payment-modal --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="modal-payment-cancel" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>


<script>
  $(document).ready( function() {
      // modal   payment-modal
      $(".payment-modal").click("click", function(e) {
        e.preventDefault();
        $("#modal-payment-edit")
            .modal("show")
            .addClass("modal-lg")
            .find(".modal-content")
            .load($(this).attr("href"));
    });
  // modal   payment-modal camcel
  $(".payment-modal-cancel").click("click", function(e) {
        e.preventDefault();
        $("#modal-payment-cancel")
            .modal("show")
            .addClass("modal-lg")
            .find(".modal-content")
            .load($(this).attr("href"));
    });

    
  })
</script>
