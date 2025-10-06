<style>
    .payment-table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
        font-size: 15px;
        padding: 14px 20px;
    }

    .payment-table-summary {
        background: #f8f9fa;
        font-weight: 600;
        color: #28a745;
        border-top: 2px solid #28a745;
    }

    .payment-table th {
        background: #f3f6fb;
        color: #495057;
        font-weight: 500;
        font-size: 13px;
        border-bottom: 2px solid #dee2e6;
    }

    .payment-table td {
        font-size: 13px;
        vertical-align: middle;
    }

    .payment-table .badge {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 12px;
    }

    .payment-table .fa-file {
        color: #e74c3c;
    }

    .payment-table .fa-print {
        color: #007bff;
    }

    .payment-table .fa-edit {
        color: #17a2b8;
    }

    .payment-table .fa-trash {
        color: #e74c3c;
    }

    .payment-table .fa-minus-circle {
        color: #e67e22;
    }

    .payment-table .fa-recycle {
        color: #28a745;
    }

    .payment-table .fa-envelope {
        color: #17a2b8;
    }
</style>

<div class="col-md-12">
    <div class="card info-card shadow-sm">
        <div class="payment-table-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-credit-card me-2"></i>รายการชำระเงิน</span>
            <a href="javascript:void(0)" class="text-white"
                onclick="toggleAccordion('table-payment', 'toggle-arrow-payment')">
                <i class="fas fa-chevron-down" id="toggle-arrow-payment"></i>
            </a>
        </div>
        <div class="card-body" id="table-payment" style="display: block">
            <div class="table-responsive">
                <table class="table payment-table table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th style="width: 48px;">#</th>
                            <th>เลขที่ชำระ</th>
                            <th>วันที่ทำรายการ</th>
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
                            <tr class="{{ $item->payment_type === 'refund' ? 'table-danger' : '' }}">
                                <td>{{ ++$key }}</td>
                                <td>{{ $item->payment_number }}</td>
                                 <td>{{ date('d-m-Y H:m:s', strtotime($item->created_at)) }}</td>
                                <td>{{ date('d-m-Y H:m:s', strtotime($item->payment_in_date)) }}</td>
                                <td>
                                    @if ($item->payment_method === 'cash')
                                        เงินสด </br>
                                    @endif
                                    @if ($item->payment_method === 'transfer-money')
                                        โอนเงิน</br>
                                    @endif
                                    @if ($item->payment_method === 'check')
                                        เช็ค</br>
                                    @endif

                                    @if ($item->payment_method === 'credit')
                                        บัตรเครดิต </br>
                                    @endif

                                </td>
                                <td class="text-center">

                                    @if ($item->payment_status === 'cancel')
                                        <span class="text-danger">ยกเลิก</span>
                                    @else
                                        @php

                                            $paymentTotal += $item->payment_total - $item->payment_refund_total;
                                        @endphp
                                        {{ number_format($item->payment_total - $item->payment_refund_total, 2, '.', ',') }}
                                    @endif

                                </td>
                                <td class="text-center">
                                    @if ($item->payment_file_path)
                                        <a href="{{ asset('storage/' . $item->payment_file_path) }}"
                                            class="dropdown-item" onclick="openPdfPopup(this.href); return false;"><i
                                                class="fa fa-file text-danger"></i> สลิปโอน</a>
                                    @else
                                        -
                                    @endif


                                </td>
                                <td class="text-center">

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
                                <td class="text">
                                    @if ($item->payment_type !== 'refund')
                                        @canany(['payment.view'])
                                            <a href="{{ route('mpdf.payment', $item->payment_id) }}"
                                                onclick="openPdfPopup(this.href); return false;"><i
                                                    class="fa fa-print text-danger"></i> พิมพ์</a>
                                        @endcanany
                                        @if (!empty($item->payment_file_path))
                                        <a class="dropdown-item payment-sendmail-pdf" href="#"
                                            data-payment-id="{{ $item->payment_id }}"
                                            data-payment-number="{{ $item->payment_number }}"
                                            data-payment-email="{{ $item->paymentCustomer->customer_email ?? '' }}"
                                            data-modal-index="{{ $key }}">
                                            <i class="fas fa-envelope text-success"></i> ส่งอีเมลใบเสร็จรับเงิน
                                        </a>
                                    @endif
                                        <!-- Modal ส่งเมลใบเสร็จ PDF (แยก index) -->
                                        <div class="modal fade" id="modal-payment-sendmail-pdf-{{ $key }}" tabindex="-1"
                                            aria-labelledby="modalPaymentSendMailPDFLabel-{{ $key }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalPaymentSendMailPDFLabel-{{ $key }}">
                                                            ส่งอีเมลใบเสร็จรับเงิน (PDF)</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="sendPaymentPDFMailForm-{{ $key }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="payment_id"
                                                                id="mail-pdf-payment-id-{{ $key }}">
                                                            <div class="mb-3">
                                                                <label for="mail-pdf-payment-number-{{ $key }}"
                                                                    class="form-label">เลขที่ชำระ</label>
                                                                <input type="text" class="form-control"
                                                                    id="mail-pdf-payment-number-{{ $key }}" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="mail-pdf-payment-email-{{ $key }}"
                                                                    class="form-label">อีเมลผู้รับ</label>
                                                                <input type="email" class="form-control"
                                                                    name="email" id="mail-pdf-payment-email-{{ $key }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="mail-pdf-payment-subject-{{ $key }}"
                                                                    class="form-label">หัวข้อ</label>
                                                                <input type="text" class="form-control"
                                                                    name="subject" id="mail-pdf-payment-subject-{{ $key }}"
                                                                    value="แจ้งใบเสร็จรับเงิน">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="mail-pdf-payment-detail-{{ $key }}"
                                                                    class="form-label">รายละเอียด</label>
                                                                <textarea class="form-control" name="text_detail" id="mail-pdf-payment-detail-{{ $key }}" rows="6">
                                                                     <p>เรียน คุณ {{  $item->paymentCustomer->customer_name }}</p>
                                                                     <p>ใบเสร็จรับเงินเลขที่ {{ $item->receipt_number }}</p>
                                                                     <p>กรุณาตรวจสอบไฟล์แนบที่ส่งมาพร้อมกับอีเมลล์นี้</p>
                                                                     <br>
                                                                     
                                                                     <p>**Email นี้ เป็น Email ตอบรับอัตโนมัติ ไม่สามารถตอบกลับได้</p>
                                                                     <p>สอบถามรายละเอียดและจองทัวร์ได้ที่   Line: @nexttripholiday</p>
                                                 
                                                                     <p>ขอแสดงความนับถือ</p>
                                                                     
                                                                     <p>บริษัท เน็กซ์ ทริป ฮอลิเดย์ จำกัด (สำนักงานใหญ่)</p>
                                                                     <p>โทรศัพท์:02-136-9144 อัตโนมัติ 16 คู่สาย โทรสาร(Fax): 02-136-9146</p>
                                                                     <p>Hotline: 091-091-6364 ,091-091-6463</p>
                                                                     <p>TAT License: 11/07440 ,TTAA License:1469</p>
                                                                     <p>Website: https://www.nexttripholiday.com , Email : nexttripholiday@gmail.com</p>
                                                                </textarea>
                                                            </div>
                                                            <div class="text-end">
                                                                <button type="submit" class="btn btn-success"><i
                                                                        class="fas fa-paper-plane"></i>
                                                                    ส่งอีเมล</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $(document).ready(function() {
                                                // เปิด modal ส่ง PDF เฉพาะ index
                                                $(document).on('click', '.payment-sendmail-pdf[data-modal-index="{{ $key }}"]', function(e) {
                                                    e.preventDefault();
                                                    var paymentId = $(this).data('payment-id');
                                                    var paymentNumber = $(this).data('payment-number');
                                                    var paymentEmail = $(this).data('payment-email') || '';
                                                    $('#mail-pdf-payment-id-{{ $key }}').val(paymentId);
                                                    $('#mail-pdf-payment-number-{{ $key }}').val(paymentNumber);
                                                    $('#mail-pdf-payment-email-{{ $key }}').val(paymentEmail);
                                                    $('#mail-pdf-payment-subject-{{ $key }}').val('ใบเสร็จรับเงิน จองทัวร์ที่ Next Trip Holiday');
                                                    // CKEditor สำหรับรายละเอียดใบเสร็จ
                                                    if (typeof CKEDITOR !== 'undefined') {
                                                        if (CKEDITOR.instances['mail-pdf-payment-detail-{{ $key }}']) {
                                                            CKEDITOR.instances['mail-pdf-payment-detail-{{ $key }}'].destroy(true);
                                                        }
                                                        CKEDITOR.replace('mail-pdf-payment-detail-{{ $key }}', { height: 200 });
                                                        CKEDITOR.instances['mail-pdf-payment-detail-{{ $key }}'].setData(`
                                                                    <p>เรียน คุณ {{  $item->paymentCustomer->customer_name }}</p>
                                                                     <p>ใบเสร็จรับเงินเลขที่ {{ $item->payment_number }}</p>
                                                                     <p>กรุณาตรวจสอบไฟล์แนบที่ส่งมาพร้อมกับอีเมลล์นี้</p>
                                                                     <br>
                                                                     
                                                                     <p>**Email นี้ เป็น Email ตอบรับอัตโนมัติ ไม่สามารถตอบกลับได้</p>
                                                                     <p>สอบถามรายละเอียดและจองทัวร์ได้ที่   Line: @nexttripholiday</p>
                                                 
                                                                     <p>ขอแสดงความนับถือ</p>
                                                                     
                                                                     <p>บริษัท เน็กซ์ ทริป ฮอลิเดย์ จำกัด (สำนักงานใหญ่)</p>
                                                                     <p>โทรศัพท์:02-136-9144 อัตโนมัติ 16 คู่สาย โทรสาร(Fax): 02-136-9146</p>
                                                                     <p>Hotline: 091-091-6364 ,091-091-6463</p>
                                                                     <p>TAT License: 11/07440 ,TTAA License:1469</p>
                                                                     <p>Website: https://www.nexttripholiday.com , Email : nexttripholiday@gmail.com</p>`);
                                                    } else {
                                                        $('#mail-pdf-payment-detail-{{ $key }}').val('<p>เรียนลูกค้า</p><p>แนบใบเสร็จรับเงินตามไฟล์ PDF ที่แนบมานี้</p><br><p>ขอบคุณที่ใช้บริการ Next Trip Holiday</p>');
                                                    }
                                                    $('#modal-payment-sendmail-pdf-{{ $key }}').modal('show');
                                                });
                                                // ส่งเมล PDF ผ่าน Ajax เฉพาะ index
                                                $('#sendPaymentPDFMailForm-{{ $key }}').on('submit', function(e) {
                                                    e.preventDefault();
                                                    // อัปเดต textarea ด้วยข้อมูลจาก CKEditor ก่อนส่ง
                                                    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['mail-pdf-payment-detail-{{ $key }}']) {
                                                        CKEDITOR.instances['mail-pdf-payment-detail-{{ $key }}'].updateElement();
                                                    }
                                                    var form = this;
                                                    var formData = new FormData(form);
                                                    Swal.fire({
                                                        title: 'กำลังส่งอีเมล...',
                                                        html: 'กรุณารอสักครู่...',
                                                        allowOutsideClick: false,
                                                        showConfirmButton: false,
                                                        didOpen: () => { Swal.showLoading(); }
                                                    });
                                                    fetch("{{ route('payments.sendMailWithPDF') }}", {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        },
                                                        body: formData
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'ส่งอีเมลสำเร็จ!',
                                                                text: 'อีเมลได้ถูกส่งแล้ว'
                                                            });
                                                            $('#modal-payment-sendmail-pdf-{{ $key }}').modal('hide');
                                                        } else {
                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'เกิดข้อผิดพลาด',
                                                                text: data.message || 'ไม่สามารถส่งอีเมลได้'
                                                            });
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error:', error);
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'เกิดข้อผิดพลาด',
                                                            text: 'ไม่สามารถส่งอีเมลได้'
                                                        });
                                                    });
                                                });
                                            });
                                        </script>
                                    @elseif($item->payment_type === 'refund' && !empty($item->payment_file_path))
                                        <a class="dropdown-item payment-sendmail-refund" href="#"
                                            data-payment-id="{{ $item->payment_id }}"
                                            data-payment-number="{{ $item->payment_number }}"
                                            data-payment-email="{{ $item->paymentCustomer->customer_email ?? '' }}"
                                            data-payment-file-path="{{ $item->payment_file_path ?? '' }}"
                                            data-modal-index="{{ $key }}"><i
                                                class="fas fa-envelope text-info"></i> ส่งอีเมลคืนเงินลูกค้า</a>
                                        <!-- Modal ส่งเมลรายการชำระเงิน (แยก index) -->
                                        <div class="modal fade" id="modal-payment-sendmail-refund-{{ $key }}" tabindex="-1"
                                            aria-labelledby="modalPaymentSendMailLabel-{{ $key }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">

                                                        <h5 class="modal-title" id="modalPaymentSendMailLabel-{{ $key }}">
                                                            ส่งอีเมลรายการคืนเงินลูกค้า</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form id="sendPaymentMailForm-{{ $key }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="payment_id"
                                                                id="mail-payment-id-{{ $key }}">
                                                            <div class="mb-3">
                                                                <label for="mail-payment-number-{{ $key }}"
                                                                    class="form-label">เลขที่ชำระ</label>
                                                                <input type="text" class="form-control"
                                                                    id="mail-payment-number-{{ $key }}" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="mail-payment-email-{{ $key }}"
                                                                    class="form-label">อีเมลผู้รับ</label>
                                                                <input type="email" class="form-control"
                                                                    name="email" id="mail-payment-email-{{ $key }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="mail-payment-subject-{{ $key }}"
                                                                    class="form-label">หัวข้อ</label>
                                                                <input type="text" class="form-control"
                                                                    name="subject" id="mail-payment-subject-{{ $key }}"
                                                                    value="แจ้งรายการชำระเงิน">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="mail-payment-detail-{{ $key }}"
                                                                    class="form-label">รายละเอียด</label>
                                                                <textarea class="form-control" name="text_detail" id="mail-payment-detail-{{ $key }}" rows="8"><p>เรียนลูกค้า</p><p>แนบรายละเอียดการชำระเงินตามไฟล์ที่แนบมานี้</p><br><p>ขอบคุณที่ใช้บริการ Next Trip Holiday</p></textarea>
                                                            </div>
                                                            <div class="mb-3" id="mail-payment-slip-group-{{ $key }}"
                                                                style="display:none;">
                                                                <label class="form-label">ไฟล์แนบสลิป</label>
                                                                <div id="mail-payment-slip-link-{{ $key }}"></div>
                                                                <input type="hidden" name="payment_file_path"
                                                                    id="mail-payment-file-path-{{ $key }}">
                                                            </div>
                                                            <div class="text-end">
                                                                <button type="submit" class="btn btn-info"><i
                                                                        class="fas fa-paper-plane"></i>
                                                                    ส่งอีเมล</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $(document).ready(function() {
                                                // เปิด modal ส่ง refund เฉพาะ index
                                                $(document).on('click', '.payment-sendmail-refund[data-modal-index="{{ $key }}"]', function(e) {
                                                    e.preventDefault();
                                                    var paymentId = $(this).data('payment-id');
                                                    var paymentNumber = $(this).data('payment-number');
                                                    var paymentEmail = $(this).data('payment-email') || '';
                                                    var slipPath = $(this).data('payment-file-path') || '';
                                                    if (slipPath) {
                                                        $('#mail-payment-slip-group-{{ $key }}').show();
                                                        var slipUrl = "{{ asset('storage') }}/" + slipPath;
                                                        $('#mail-payment-slip-link-{{ $key }}').html('<a href="' + slipUrl + '" target="_blank">ดูไฟล์สลิป</a>');
                                                        $('#mail-payment-file-path-{{ $key }}').val(slipPath);
                                                    } else {
                                                        $('#mail-payment-slip-group-{{ $key }}').hide();
                                                        $('#mail-payment-slip-link-{{ $key }}').html('');
                                                        $('#mail-payment-file-path-{{ $key }}').val('');
                                                    }
                                                    $('#mail-payment-id-{{ $key }}').val(paymentId);
                                                    $('#mail-payment-number-{{ $key }}').val(paymentNumber);
                                                    $('#mail-payment-email-{{ $key }}').val(paymentEmail);
                                                    $('#mail-payment-subject-{{ $key }}').val('สลิปคืนเงิน จองทัวร์ที่ Next Trip Holiday');
                                                    if (typeof CKEDITOR !== 'undefined') {
                                                        if (CKEDITOR.instances['mail-payment-detail-{{ $key }}']) {
                                                            CKEDITOR.instances['mail-payment-detail-{{ $key }}'].destroy(true);
                                                        }
                                                        CKEDITOR.replace('mail-payment-detail-{{ $key }}', { height: 250 });
                                                        CKEDITOR.instances['mail-payment-detail-{{ $key }}'].setData(`
                                                         <p>เรียน คุณ {{  $item->paymentCustomer->customer_name }}</p>
                                                                     <p>บริษัทได้จัดส่งสลิปคืนเงิน กรุณาตรวจสอบไฟล์แนบที่ส่งมาพร้อมกับอีเมลล์นี้</p>
                                                                    
                                                                     
                                                                     <p>**Email นี้ เป็น Email ตอบรับอัตโนมัติ ไม่สามารถตอบกลับได้</p>
                                                                     <p>สอบถามรายละเอียดและจองทัวร์ได้ที่   Line: @nexttripholiday</p>
                                                 
                                                                     <p>ขอแสดงความนับถือ</p>
                                                                     
                                                                     <p>บริษัท เน็กซ์ ทริป ฮอลิเดย์ จำกัด (สำนักงานใหญ่)</p>
                                                                     <p>โทรศัพท์:02-136-9144 อัตโนมัติ 16 คู่สาย โทรสาร(Fax): 02-136-9146</p>
                                                                     <p>Hotline: 091-091-6364 ,091-091-6463</p>
                                                                     <p>TAT License: 11/07440 ,TTAA License:1469</p>
                                                                     <p>Website: https://www.nexttripholiday.com , Email : nexttripholiday@gmail.com</p>
                                                        `);
                                                    } else {
                                                        $('#mail-payment-detail-{{ $key }}').val('<p>เรียนลูกค้า</p><p>แนบรายละเอียดการคืนเงินตามไฟล์ที่แนบมานี้</p><br><p>ขอบคุณที่ใช้บริการ Next Trip Holiday</p>');
                                                    }
                                                    $('#modal-payment-sendmail-refund-{{ $key }}').modal('show');
                                                });
                                                // ส่งเมล refund ผ่าน Ajax เฉพาะ index
                                                $('#sendPaymentMailForm-{{ $key }}').on('submit', function(e) {
                                                    e.preventDefault();
                                                    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['mail-payment-detail-{{ $key }}']) {
                                                        CKEDITOR.instances['mail-payment-detail-{{ $key }}'].updateElement();
                                                    }
                                                    var form = this;
                                                    var formData = new FormData(form);
                                                    Swal.fire({
                                                        title: 'กำลังส่งอีเมล...',
                                                        html: 'กรุณารอสักครู่...',
                                                        allowOutsideClick: false,
                                                        showConfirmButton: false,
                                                        didOpen: () => { Swal.showLoading(); }
                                                    });
                                                    fetch("{{ route('payments.sendMail') }}", {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        },
                                                        body: formData
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'ส่งอีเมลสำเร็จ!',
                                                                text: 'อีเมลได้ถูกส่งแล้ว'
                                                            });
                                                            $('#modal-payment-sendmail-refund-{{ $key }}').modal('hide');
                                                        } else {
                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'เกิดข้อผิดพลาด',
                                                                text: 'ไม่สามารถส่งอีเมลได้'
                                                            });
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error:', error);
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'เกิดข้อผิดพลาด',
                                                            text: 'ไม่สามารถส่งอีเมลได้'
                                                        });
                                                    });
                                                });
                                            });
                                        </script>
                                    @endif
                                </td>



                                <td class="text-center">
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
                                        @canany(['payment.edit'])
                                            <a class="dropdown-item payment-modal"
                                                href="{{ route('payment.edit', $item->payment_id) }}"><i
                                                    class="fa fa-edit text-info"></i>
                                                แก้ไข</a>
                                        @endcanany
                                        @canany(['payment.edit'])
                                            <a class="dropdown-item text-danger payment-modal-cancel"
                                                href="{{ route('payment.cancelModal', $item->payment_id) }}"><i
                                                    class=" fas fa-minus-circle"></i> ยกเลิก</a>
                                        @endcanany
                                    @else
                                        {{ $item->payment_cancel_note }}
                                        @canany(['payment.edit'])
                                            <a href="{{ route('payment.RefreshCancel', $item->payment_id) }}"
                                                class="dropdown-item text-primary"
                                                onclick="return confirm('ยืนยันการคืนสถานะ');"> <i
                                                    class="fas fa-recycle"></i> นำกลับมาใช้ใหม่ </a>
                                        @endcanany
                                    @endif
                                    @canany(['payment.delete'])
                                        <a href="{{ route('payment.delete', $item->payment_id) }}"
                                            onclick="return confirm('ยืนยันการลบ');"><i
                                                class="fa fa-trash text-danger"></i> ลบ</a>
                                    @endcanany



                                </td>
                            </tr>

                        @empty

                        @endforelse


                        <tr class="payment-table-summary">
                            <td colspan="7" align="right"><b>(@bathText($quotation->GetDeposit() - $quotation->Refund()))</b></td>
                            <td align="center">
                                <b>{{ number_format($quotation->GetDeposit() - $quotation->Refund(), 2) }}</b>
                            </td>
                            <td align="center" colspan="2"><b><span class="text-danger">( ยอดค้างชำระ :
                                        {{ number_format($quotation->quote_grand_total - $quotation->GetDeposit() + $quotation->Refund(), 2, '.', ',') }}
                                        )</span></b></td>
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
    $(document).ready(function() {
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
