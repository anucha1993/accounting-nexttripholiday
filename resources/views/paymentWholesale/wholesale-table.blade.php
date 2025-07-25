<div class="col-md-12">
    <style>
        .wholesale-table-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
            font-size: 15px;
            padding: 14px 20px;
        }
        .wholesale-table th {
            background: #f3f6fb;
            color: #495057;
            font-weight: 500;
            font-size: 13px;
            border-bottom: 2px solid #dee2e6;
        }
        .wholesale-table td {
            font-size: 13px;
            vertical-align: middle;
        }
        .wholesale-table .badge {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 12px;
        }
        .wholesale-table .fa-building {
            color: #007bff;
        }
        .wholesale-table .fa-edit {
            color: #17a2b8;
        }
        .wholesale-table .fa-envelope {
            color: #17a2b8;
        }
        .wholesale-table .fa-trash {
            color: #e74c3c;
        }
        .wholesale-table-summary {
            background: #f8f9fa;
            font-weight: 600;
            color: #28a745;
            border-top: 2px solid #28a745;
        }
    </style>
    <div class="card info-card shadow-sm">
        <div class="wholesale-table-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-building me-2"></i>รายการชำระเงินโฮลเซลล์</span>
            <a href="javascript:void(0)" class="text-white" onclick="toggleAccordion('table-payment-wholesale', 'toggle-arrow-payment-wholesale')">
                <i class="fas fa-chevron-down" id="toggle-arrow-payment-wholesale"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="table-payment-wholesale" style="display: block">
                <table class="table wholesale-table table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>Payment No.</th>
                            <th>วันที่ทำรายการ</th>
                            <th>วันที่ชำระ</th>
                            <th class="text-end">จำนวนเงิน</th>
                            <th class="text-end">ยอดคืน</th>
                            <th class="text-center">สถานะการคืน</th>
                            <th class="text-center">ไฟล์แนบ</th>
                            <th class="text-center">ประเภท</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    @php
                        $paymentTotal = 0;
                    @endphp
                    <tbody>

                        @foreach ($paymentWholesale as $key => $item)
                            <tr class="text-center">
                                <td>{{ $key + 1 }}</td>

                                <td>{{ $item->payment_wholesale_number }}</td>
                                <td>{{ date('d/m/Y : H:m:s', strtotime($item->created_at)) }}</td>
                                <td>
                                    @if ($item->payment_wholesale_date)
                                        {{ date('d/m/Y : H:m:s', strtotime($item->payment_wholesale_date)) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($item->payment_wholesale_total, 2, '.', ',') }}
                                </td>
                                <td>
                                    @if ($item->payment_wholesale_refund_type !== null)
                                        {!! '<span class="text-danger">' . number_format($item->payment_wholesale_refund_total, 2) . '</span>' !!}
                                    @else
                                       N/A
                                    @endif
                                </td>
                                <td>
                                    @if ($item->payment_wholesale_refund_total > 0)
                                        @if ($item->payment_type === 'some')
                                            <span class="text-success">ชำระเงินบางส่วน</span>
                                        @elseif($item->payment_type === 'full')
                                            <span class="text-success">(ชำระเงินเต็มจำนวน)</span>
                                        @endif
                                         {{ $item->payment_wholesale_refund_status === 'success' ? 'ได้เงินคืนแล้ว' : 'รอการคืนเงิน' }}
                                        @else
                                        N/A
                                    @endif
                                </td>

                                <td>
                                    @if ($item->payment_wholesale_file_path !== null)
                                        สลิปชำระ : <a onclick="openPdfPopup(this.href); return false;"
                                            href="{{ asset($item->payment_wholesale_file_path) }}">{{ $item->payment_wholesale_file_name }}</a>
                                    @else
                                        <span class="text-info">รอยืนยันการชำระเงิน</span>
                                    @endif
                                    <br>
                                    @if ($item->payment_wholesale_refund_file_name !== null)
                                        สลิปคืนยอด :
                                        <a onclick="openPdfPopup(this.href); return false;" class="text-danger"
                                            href="{{ asset($item->payment_wholesale_refund_file_path) }}">{{ $item->payment_wholesale_refund_file_name }}</a><br>
                                        <a onclick="openPdfPopup(this.href); return false;" class="text-danger"
                                            href="{{ asset($item->payment_wholesale_refund_file_path1) }}">{{ $item->payment_wholesale_refund_file_name1 }}</a><br>
                                        <a onclick="openPdfPopup(this.href); return false;" class="text-danger"
                                            href="{{ asset($item->payment_wholesale_refund_file_path2) }}">{{ $item->payment_wholesale_refund_file_name2 }}</a>
                                    @else
                                        
                                    @endif
                                </td>
                                <td>

                                    @if ($item->payment_wholesale_type === 'full')
                                        ชำระเต็มจำนวน
                                    @else
                                        ชำระมัดจำ
                                    @endif


                                </td>

                                <td>
                                    <a href="{{ route('paymentWholesale.edit', $item->payment_wholesale_id) }}"
                                        class=" text-info payment-wholesale-edit"><i class="fa fa-edit"></i> แก้ไข</a>
                                    &nbsp;
                                    <a class="wholesale-mail"
                                        href="{{ route('paymentWholesale.modalMailWholesale', $item->payment_wholesale_id) }}"><i
                                            class="fas fa-envelope text-info"></i>ส่งเมล</a>
                                    &nbsp;

                                    <a href="{{ route('paymentWholesale.editRefund', $item->payment_wholesale_id) }}"
                                        class="text-primary edit-refund"><i class="fa fas fa-edit"></i>การคืนยอด</a>
                                    &nbsp;

                                    <a href="{{ route('paymentWholesale.delete', $item->payment_wholesale_id) }}"
                                        onclick="return confirm('คุณต้องการลบข้อมูลใช่ไหม');" class="text-danger"><i
                                            class="fa fas fa-trash"></i> ลบ</a>
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td align="right" class="text-success" colspan="8"><b>(@bathText($quotationModel->GetDepositWholesale() - $quotationModel->GetDepositWholesaleRefund()))</b></td>
                            <td align="center" class="text-success">
                                <b>{{ number_format($quotationModel->GetDepositWholesale() - $quotationModel->GetDepositWholesaleRefund(), 2) }}</b>
                            </td>
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

<div class="modal fade bd-example-modal-sm modal-lg" id="wholesale-mail" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // modal 
        $(".wholesale-mail").click("click", function(e) {
            e.preventDefault();
            $("#wholesale-mail")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });


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
