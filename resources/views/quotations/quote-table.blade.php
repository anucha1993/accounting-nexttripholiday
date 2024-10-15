<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-success">
            <h5 class="mb-0 text-white"><i class="fa fa-file"></i>
                รายละเอียดใบจองใบทัวร์ <span class="float-end">Booking No. :
                    {{ $quotationModel->quote_booking }}</span></h5>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table product-overview">
                    <thead>
                        <tr>
                            <th style="width: 100px">ปรเภท</th>
                            <th>วันที่</th>
                            <th>เลขที่เอกสาร</th>
                            {{-- <th style="width: 500px">ชื่อลูกค้า</th> --}}
                            <th style="text-align: center">ยอดรวมสิทธิ์</th>
                            <th style="text-align: center">ยอดชำระแล้ว</th>
                            <th style="text-align: center">ยอดคงค้าง</th>
                            <th style="text-align: center">หัก ณ. ที่จ่าย</th>
                            <th style="text-align: left">Actions Report</th>

                            <th style="text-align: left">Actions</th>
                            <th style="text-align: left">Cancel</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($quotations as $item)
                            <tr>
                                <td>ใบเสนอราคา</td>
                                <td>{{ date('d/m/Y', strtotime($quotationModel->created_at)) }}</td>
                                <td><span class="badge bg-dark">{{ $quotationModel->quote_number }} </span>

                                </td>
                                {{-- <td>{{ $item->customer_name }}</td> --}}
                                <td align="center">
                                    {{ number_format($quotationModel->quote_grand_total, 2, '.', ',') }}</td>
                                <td align="center">{{ number_format($quotationModel->payment, 2, '.', ',') }}
                                </td>
                                <td align="center">
                                    {{ number_format($quotationModel->quote_grand_total - $quotationModel->payment, 2, '.', ',') }}
                                </td>
                                <td align="center">
                                    @if ($item->quote_withholding_tax_status === 'Y')
                                        {{ number_format($item->quote_withholding_tax, 2, '.', ',') }}
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td>
                                    <a class="dropdown-item" target="_blank"
                                        href="{{ route('mpdf.quote', $quotationModel->quote_id) }}"
                                        onclick="openPdfPopup(this.href); return false;">
                                        <i class="fa fa-print text-danger "></i>
                                        พิมพ์ใบเสนอราคา
                                    </a>
                                    <a class="dropdown-item mail-quote"
                                        href="{{ route('mail.quote.formMail', $quotationModel->quote_id) }}">
                                        <i class="fas fa-envelope text-info"></i>
                                        ส่งเมล
                                    </a>
                                </td>


                                <td align="left">

                                    @if ($quotationModel->quote_status != 'cancel')
                                        @can('edit-quote')
                                            <a class="dropdown-item modal-quote-edit"
                                                href="{{ route('quote.modalEdit', $quotationModel->quote_id) }}"><i
                                                    class="fa fa-edit text-info"></i> แก้ไข</a>
                                        @endcan
                                        @can('create-invoice')
                                            @if ($quotationModel->quote_status === 'invoice')
                                                <span class="badge rounded-pill bg-success">ออกใบแจ้งหนี้แล้ว</span>
                                            @else
                                                <a class="dropdown-item modal-invoice"
                                                    href="{{ route('invoice.create', $quotationModel->quote_id) }}"><i
                                                        class="fas fa-file-alt"></i> ออกใบแจ้งหนี้</a>
                                            @endif
                                        @endcan
                                    @endif
                                </td>

                                <td>
                                    @can('edit-quote')
                                        <a class="dropdown-item"
                                            href="{{ route('quote.cancel', $quotationModel->quote_id) }}"
                                            onclick="return confirm('ยืนยันการยกเลิกใบเสนอราคา')"><i
                                                class="fas fa-minus-circle text-danger"></i> ยกเลิกใบงาน</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty

                        @endforelse


                        {{-- Invoice table --}}

                        @forelse ($invoices as $itemInvoice)
                            <tr>
                                <td class="text-success">ใบแจ้งหนี้</td>
                                <td>{{ date('d/m/Y', strtotime($itemInvoice->invoice_date)) }}</td>
                                <td><span class="badge bg-dark">{{ $itemInvoice->invoice_number }}</span>
                                </td>
                                <td align="center">
                                    {{ number_format($itemInvoice->invoice_grand_total, 2, '.', ',') }}</td>
                                <td align="center">
                                    N/A
                                </td>
                                <td align="center">
                                    N/A
                                </td>
                                <td align="center">
                                    @if ($itemInvoice->invoice_withholding_tax_status === 'Y')
                                        {{ number_format($itemInvoice->invoice_withholding_tax, 2, '.', ',') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <a class="dropdown-item" onclick="openPdfPopup(this.href); return false;"
                                        href="{{ route('mpdf.invoice', $itemInvoice->invoice_id) }}"><i
                                            class="fa fa-print text-danger"></i>
                                        พิมพ์ใบแจ้งหนี้</a>

                                    <a class="dropdown-item mail-quote"
                                        href="{{ route('mail.invoice.formMail', $itemInvoice->invoice_id) }}"><i
                                            class="fas fa-envelope text-info"></i>
                                        ส่งเมล</a>
                                </td>
                                {{-- <td>
                                    <a class="dropdown-item"
                                    href="{{ route('invoice.taxinvoice', $item->invoice_id) }}"
                                    onclick="return confirm('ระบบจะอ้างอิงรายการสินค้าจากใบแจ้งหนี้');"><i
                                        class="fas fa-file-alt"></i> ออกใบกำกับภาษี</a>
                                </td> --}}
                                <td>
                                    @can('edit-invoice')
                                        <a class="dropdown-item modal-invoice-edit"
                                            href="{{ route('invoice.edit', $itemInvoice->invoice_id) }}">
                                            <i class="fa fa-edit text-info"></i> แก้ไข</a>

                                            @if ($itemInvoice->invoice_status === 'wait')
                                            <a class="dropdown-item"
                                                href="{{ route('invoice.taxinvoice', $itemInvoice->invoice_id) }}"
                                                onclick="return confirm('ระบบจะอ้างอิงรายการสินค้าจากใบแจ้งหนี้');"><i
                                                    class="fas fa-file-alt"></i> ออกใบกำกับภาษี</a>
                                        @endif
                                    @endcan

                                   
                                </td>
                                <td>
                                    @can('cancel-invoice')
                                        <a class="dropdown-item" href="{{ route('invoice.cancel', $itemInvoice->invoice_id) }}"
                                            onclick="return confirm('ยืนยันการยกเลิกใบแจ้งหนี้')"><i
                                                class="fas fa-minus-circle text-danger"></i> ยกเลิกใบงาน</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        {{-- taxinvoices table --}}

                        @forelse ($taxinvoices as $item)
                            <tr>
                                <td class="text-primary">ใบกำกับภาษี</td>
                                <td>{{ date('d/m/Y', strtotime($item->taxinvoice_date)) }}</td>
                                <td><span class="badge bg-dark">{{ $item->taxinvoice_number }}</span>
                                </td>
                                <td align="center">
                                    {{ number_format($item->invoice_grand_total, 2, '.', ',') }}</td>
                                <td align="center">
                                    N/A
                                </td>
                                <td align="center">
                                    N/A
                                </td>
                                <td align="center">
                                    @if ($item->invoice_withholding_tax_status === 'Y')
                                        {{ number_format($item->invoice_withholding_tax, 2, '.', ',') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <a class="dropdown-item" onclick="openPdfPopup(this.href); return false;"
                                        href="{{ route('mpdf.taxreceipt', $item->invoice_id) }}"><i
                                            class="fa fa-print text-danger"></i>
                                        พิมพ์ใบกำกับภาษี</a>

                                    <a class="dropdown-item mail-quote"
                                        href="{{ route('mail.taxreceipt.formMail', $item->invoice_id) }}"><i
                                            class="fas fa-envelope text-info"></i>
                                        ส่งเมล</a>
                                </td>
                                {{-- <td>
                                 <a class="dropdown-item"
                                 href="{{ route('invoice.taxinvoice', $item->invoice_id) }}"
                                 onclick="return confirm('ระบบจะอ้างอิงรายการสินค้าจากใบแจ้งหนี้');"><i
                                     class="fas fa-file-alt"></i> ออกใบกำกับภาษี</a>
                             </td> --}}
                                <td>
                                    @can('edit-invoice')
                                        <a class="dropdown-item modal-invoice-edit"
                                            href="{{ route('invoice.edit', $item->invoice_id) }}">
                                            <i class="fa fa-edit text-info"></i> แก้ไข</a>
                                            <a class="dropdown-item"
                                            href="{{ route('invoice.taxinvoice', $item->invoice_id) }}"
                                            onclick="return confirm('ระบบจะอ้างอิงรายการสินค้าจากใบแจ้งหนี้');"><i
                                                class="fas fa-file-alt"></i> ออกใบกำกับภาษี</a>
                                  
                                    @endcan


                                </td>
                                <td>
                                    @can('cancel-invoice')
                                        <a class="dropdown-item" href="{{ route('taxinvoice.cancel', $item->taxinvoice_id) }}"
                                            onclick="return confirm('ยืนยันการยกเลิกใบกำกับภาษี')"><i
                                                class="fas fa-minus-circle text-danger"></i> ยกเลิกใบงาน</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty

                        @endforelse



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


{{-- invoice payment Modal --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="invoice-payment" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

{{-- debit payment Modal --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="debit-payment" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

{{-- credit payment Modal --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="credit-payment" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

{{-- credit payment WholeSale  Quote --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="quote-payment-wholesale" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

{{-- mail form quote --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="modal-mail-quote" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


{{-- create form invoice --}}
<div class="modal fade bd-example-modal-sm modal-xl" id="modal-invoice-create" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

{{-- Edit form invoice --}}
<div class="modal fade bd-example-modal-sm modal-xl" id="modal-invoice-edit" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

{{-- Edit form quote --}}
<div class="modal fade bd-example-modal-sm modal-xl" id="modal-quote-edit" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>






<script>
    function openPdfPopup(url) {
        var width = 800; // กำหนดความกว้างของหน้าต่าง
        var height = 600; // กำหนดความสูงของหน้าต่าง
        var left = (window.innerWidth - width) / 2; // คำนวณตำแหน่งจากด้านซ้ายของหน้าจอ
        var top = (window.innerHeight - height) / 2; // คำนวณตำแหน่งจากด้านบนของหน้าจอ

        // เปิดหน้าต่างใหม่ด้วยการคำนวณตำแหน่งและขนาด
        window.open(url, 'PDFPopup', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
    }
    // เปิด modal แก้ไขใบเสนอราคา
    $(".modal-quote-edit").off("click").on("click", function(e) {
        e.preventDefault();
        var modal = $("#modal-quote-edit");

        // ล้างข้อมูลเก่าก่อนเปิด modal
        modal.find(".modal-content").html('');

        // โหลดเนื้อหาใหม่
        modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

        // เมื่อปิด modal, ล้างข้อมูล
        modal.on('hidden.bs.modal', function() {
            $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
        });
    });

    // เปิด modal แก้ไขใบแจ้งหนี้
    $(".modal-invoice").off("click").on("click", function(e) {
        e.preventDefault();
        var modal = $("#modal-invoice-create");

        // ล้างข้อมูลเก่าก่อนเปิด modal
        modal.find(".modal-content").html('');

        // โหลดเนื้อหาใหม่
        modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

        // เมื่อปิด modal, ล้างข้อมูล
        modal.on('hidden.bs.modal', function() {
            $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
        });
    });

    // เปิด modal แก้ไขใบแจ้งหนี้
    $(".modal-invoice-edit").off("click").on("click", function(e) {
        e.preventDefault();
        var modal = $("#modal-invoice-edit");

        // ล้างข้อมูลเก่าก่อนเปิด modal
        modal.find(".modal-content").html('');

        // โหลดเนื้อหาใหม่
        modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

        // เมื่อปิด modal, ล้างข้อมูล
        modal.on('hidden.bs.modal', function() {
            $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
        });
    });





    $(document).ready(function() {
        // modal add payment wholesale quote
        $(".mail-quote").click("click", function(e) {
            e.preventDefault();
            $("#modal-mail-quote")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });

        // modal add payment wholesale quote
        $(".payment-quote-wholesale").click("click", function(e) {
            e.preventDefault();
            $("#quote-payment-wholesale")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });



        // modal add payment invoice
        $(".invoice-modal").click("click", function(e) {
            e.preventDefault();
            $("#invoice-payment")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
        // modal add payment debit
        $(".debit-modal").click("click", function(e) {
            e.preventDefault();
            $("#debit-payment")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
        // modal add payment credit
        $(".credit-modal").click("click", function(e) {
            e.preventDefault();
            $("#credit-payment")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });



    })
</script>
