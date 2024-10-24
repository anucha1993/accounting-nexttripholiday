<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info">
            <h5 class="mb-0 text-white"><i class="fas fa-dollar-sign"></i>
                รายการชำระเงินโฮลเซลล์ / Payment Wholesale </h5>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table product-overview">
                    <thead>
                        <tr class="custom-row-height" style="line-height: -500px;">
                            <th>ลำดับ</th>
                            <th>Payment No.</th>
                            <th>วันที่ชำระเงิน</th>
                            <th>จำนวนเงิน</th>
                            <th>ไฟล์แนบ</th>
                            <th>ประเภทการชำระเงิน</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @php
                        $paymentTotal = 0;
                    @endphp
                    <tbody>

                        @foreach ($paymentWholesale as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>{{ $item->payment_wholesale_number }}</td>
                                <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                <td>
                                    @php
                                        $paymentTotal += $item->payment_wholesale_total;
                                    @endphp

                                    @if ($item->payment_wholesale_refund_file_name === NULL || $item->payment_wholesale_refund_file_name === '' )
                                    {{ number_format($item->payment_wholesale_total, 2, '.', ',') }} {!! '<span class="text-danger">('.number_format($item->payment_wholesale_refund_total,2).')</span>' !!}
                                    @else
                                    {{ number_format($item->payment_wholesale_total - $item->payment_wholesale_refund_total, 2, '.', ',') }}
                                    @endif
                                    
                                </td>
                                <td>
                                    @if ($item->payment_wholesale_file_path !== NULL || $item->payment_wholesale_file_path !== '' )
                                        สลิปชำระ :  <a onclick="openPdfPopup(this.href); return false;"
                                        href="{{ asset($item->payment_wholesale_file_path) }}">{{ $item->payment_wholesale_file_name }}</a>
                                    @else
                                        -
                                    @endif
                                    <br>
                                    @if ($item->payment_wholesale_refund_file_name !== NULL || $item->payment_wholesale_refund_file_name !== '' )
                                        สลิปคืนยอด :  <a onclick="openPdfPopup(this.href); return false;" class="text-danger"
                                        href="{{ asset($item->payment_wholesale_refund_file_path) }}">{{ $item->payment_wholesale_refund_file_name }}</a>
                                    @else
                                        -
                                    @endif
                                    
                                </td>
                                <td>
                                    @if ($item->payment_wholesale_refund_file_name)
                                        @if ($item->payment_wholesale_type === 'full')
                                            ชำระเต็มจำนวน {!! $item->payment_wholesale_refund_type === 'some'
                                                ? '<span class="text-success">(คืนยอดบางส่วนแล้ว)</span>'
                                                : '<span class="text-success">(คืนยอดเต็มจำนวนแล้ว)</span>' !!}
                                        @else
                                            ชำระมัดจำ {!! $item->payment_wholesale_refund_type === 'some'
                                                 ? '<span class="text-success">(คืนยอดบางส่วนแล้ว)</span>'
                                                 : '<span class="text-success">(คืนยอดเต็มจำนวนแล้ว)</span>' !!}
                                        @endif
                                    @else
                                        @if ($item->payment_wholesale_type === 'full')
                                            ชำระเต็มจำนวน {!! $item->payment_wholesale_refund_type === 'some'
                                                ? '<span class="text-danger">(รอคืนยอดบางส่วน)</span>'
                                                : '<span class="text-danger">(รอคืนยอดเต็มจำนวน)</span>' !!}
                                        @else
                                            ชำระมัดจำ {!! $item->payment_wholesale_refund_type === 'some'
                                                ? '<span class="text-danger">(รอคืนยอดบางส่วน)</span>'
                                                : '<span class="text-danger">(รอคืนยอดเต็มจำนวน)</span>' !!}
                                        @endif
                                    @endif

                                </td>
                                <td>
                                    <a href="{{ route('paymentWholesale.edit', $item->payment_wholesale_id) }}"
                                        class=" text-info payment-wholesale-edit"><i class="fa fa-edit"></i> แก้ไข</a>

                                    &nbsp;
                                    @if ($item->payment_wholesale_refund_type)
                                    <a href="{{ route('paymentWholesale.editRefund', $item->payment_wholesale_id) }}"
                                        class="text-primary edit-refund"><i
                                            class="fa fas fa-reply-all"></i>แก้ไขยอดคืน</a>


                                    @else
                                    <a href="{{ route('paymentWholesale.refund', $item->payment_wholesale_id) }}"
                                        class="text-primary refund"><i
                                            class="fa fas fa-reply-all"></i>ยกเลิกรอคืนยอด</a>

                                    @endif
                                   
                                    &nbsp;
 
                                    <a href="{{ route('paymentWholesale.delete', $item->payment_wholesale_id) }}"
                                        onclick="return confirm('คุฯต้องการลบข้อมูลใช่ไหม');" class="text-danger"><i
                                            class="fa fas fa-trash"></i> ลบ</a>
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td align="right" class="text-success" colspan="8"><b>(@bathText($paymentTotal))</b></td>
                            <td align="center" class="text-success"><b>{{ number_format($paymentTotal, 2) }}</b></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Payment Wholesale edit --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="payment-wholesale-edit" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm modal-lg" id="refund" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm modal-lg" id="edit-refund" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

         // modal Payment Refund
         $(".edit-refund").click("click", function(e) {
            e.preventDefault();
            $("#edit-refund")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });


        // modal Payment Refund
        $(".refund").click("click", function(e) {
            e.preventDefault();
            $("#refund")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });

        // modal Payment Wholesale
        $(".payment-wholesale-edit").click("click", function(e) {
            e.preventDefault();
            $("#payment-wholesale-edit")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
    });
</script>
