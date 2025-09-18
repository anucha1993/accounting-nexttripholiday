<div class="modal-body">

    <style>
        /* 🔁 คงฟอนต์เดิม แต่ลดขนาดทั้งหมดลง */
        body,
        .page-content,
        .container,
        .form-control,
        .form-select,
        .select2-selection {
            font-family: 'Sarabun', 'Prompt', 'Segoe UI', sans-serif;
            font-size: 0.65rem !important;
        }

        /* 🔁 ลด padding/margin ของ section card */
        .section-card {
            padding: 5px 5px 5px 5px !important;
            margin-bottom: 5px !important;
        }

        /* 🔁 section title ลดขนาด padding และ font */
        .section-card .section-title {
            font-size: 0.85rem !important;
            padding: 6px 12px 6px 32px !important;
            margin-bottom: 12px !important;
        }

        .section-title .fa {
            top: 9px !important;
            left: 10px !important;
            font-size: 0.95em !important;
        }

        /* 🔁 Divider margin เล็กลง */
        .divider {
            margin: 2px 0 !important;
        }

        /* 🔁 ลด margin/padding ของ label และ input */
        label {
            font-size: 0.65rem !important;
            margin-bottom: 2px !important;
        }

        .form-control,
        .form-select,
        .select2-selection {
            font-size: 0.65rem !important;
            padding: 1px 2px !important;
            margin: 1px 0 !important;
            height: auto !important;
        }

        /* 🔁 Select2 เฉพาะแบบ single */
        .select2-container--default .select2-selection--single {
            height: 26px !important;
            padding: 0 6px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px !important;
            font-size: 0.65rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 24px !important;
            top: 1px !important;
        }

        /* 🔁 Table rows */
        .row.table-custom {
            margin-bottom: 10px !important;
        }

        .item-row {
            padding-left: 1px !important;
            padding-right: 1px !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        /* หรือถ้าใช้ container หรือ card-body หุ้มอยู่ */
        .card-body,
        .section-card,
        .container {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        /* ปรับ .row และ .col ให้ลดช่องว่างแนวขวาง */
        .row.g-0>[class*="col-"] {
            padding-left: 1px !important;
            padding-right: 1px !important;
        }

        .row.item-row>.row {
            margin-bottom: 1px !important;
        }

        /* .discount-row {
    padding-left: 1px !important;
    padding-right: 1px !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
} */

        /* 🔁 สรุปยอด */
        .summary {
            padding: 10px 8px 8px 8px !important;
        }

        .summary-row {
            padding: 4px 0 !important;
            margin-bottom: 4px !important;
        }

        #grand-total,
        #sum-include-vat {
            font-size: 0.8rem !important;
            padding: 1px 6px !important;
        }

        /* 🔁 ปรับปุ่มให้เล็กลง */
        .btn,
        .btn-sm,
        .btn-primary,
        .btn-danger,
        .btn-link {
            font-size: 0.65rem !important;
            padding: 4px 10px !important;
            border-radius: 6px !important;
        }

        /* 🔁 Group input */
        .input-group-text {
            padding: 2px 8px !important;
            font-size: 0.65rem !important;
        }

        /* 🔁 Responsive */
        @media (max-width: 991px) {
            .container.border.bg-white {
                padding: 8px 4px !important;
            }

            .summary {
                padding: 6px 4px !important;
            }
        }

        /* 📱 Mobile Only Styles - Force mobile layout */
        @media (max-width: 767px) {
            /* Force hide table headers in mobile */
            #quotation-table .item-row.bg-success-subtle {
                display: none !important;
            }

            /* Override table container for mobile */
            #quotation-table {
                background: transparent !important;
                padding: 0 !important;
                border-radius: 0 !important;
            }

            #table-income {
                background: transparent !important;
                border-radius: 0 !important;
            }

            /* Force mobile card layout with higher specificity */
            #quotation-table .item-row.table-income,
            #discount-list .item-row.table-discount {
                background: #ffffff !important;
                border: 2px solid #28a745 !important;
                border-radius: 10px !important;
                margin: 0 0 12px 0 !important;
                padding: 12px !important;
                display: block !important;
                box-shadow: 0 2px 6px rgba(0,0,0,0.1) !important;
                width: 100% !important;
            }

            #discount-list .item-row.table-discount {
                border-color: #ffc107 !important;
                background: #fffef5 !important;
            }

            /* Force all columns to be full width */
            #quotation-table .item-row > div,
            #discount-list .item-row > div {
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
                margin: 0 0 8px 0 !important;
                padding: 0 !important;
                display: block !important;
                position: relative !important;
            }

            /* Force input and select styling */
            #quotation-table .item-row input,
            #quotation-table .item-row select,
            #discount-list .item-row input,
            #discount-list .item-row select {
                width: 100% !important;
                font-size: 14px !important;
                padding: 6px 8px !important;
                border-radius: 6px !important;
                border: 1px solid #ddd !important;
                box-sizing: border-box !important;
            }

            /* Row number styling for mobile */
            #quotation-table .row-number,
            #discount-list .discount-row-number {
                display: inline-block !important;
                background: #28a745 !important;
                color: white !important;
                padding: 4px 12px !important;
                border-radius: 15px !important;
                font-size: 12px !important;
                font-weight: bold !important;
                margin-bottom: 8px !important;
                text-align: center !important;
            }

            #discount-list .discount-row-number {
                background: #ffc107 !important;
                color: #333 !important;
            }

            /* Add mobile labels with higher specificity */
            #quotation-table .item-row.table-income .col-md-3:nth-child(2)::before {
                content: "📦 รายการสินค้า" !important;
                font-weight: bold !important;
                color: #28a745 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            #quotation-table .item-row.table-income .col-md-1:nth-child(4)::before {
                content: "✅ รวม 3%" !important;
                font-weight: bold !important;
                color: #28a745 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            #quotation-table .item-row.table-income .col-1:nth-child(5)::before {
                content: "💰 NonVat" !important;
                font-weight: bold !important;
                color: #28a745 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            #quotation-table .item-row.table-income .col-md-1:nth-child(6)::before {
                content: "🔢 จำนวน" !important;
                font-weight: bold !important;
                color: #28a745 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            #quotation-table .item-row.table-income .col-md-2:nth-child(7)::before {
                content: "💵 ราคา/หน่วย" !important;
                font-weight: bold !important;
                color: #28a745 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            #quotation-table .item-row.table-income .col-md-2:nth-child(8)::before {
                content: "💸 ยอดรวม" !important;
                font-weight: bold !important;
                color: #28a745 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            /* Discount mobile labels */
            #discount-list .item-row.table-discount .col-md-3:nth-child(2)::before {
                content: "🏷️ ส่วนลด" !important;
                font-weight: bold !important;
                color: #e67e22 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            #discount-list .item-row.table-discount .col-md-1:nth-child(5)::before {
                content: "💰 NonVat" !important;
                font-weight: bold !important;
                color: #e67e22 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            #discount-list .item-row.table-discount .col-md-1:nth-child(6)::before {
                content: "🔢 จำนวน" !important;
                font-weight: bold !important;
                color: #e67e22 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            #discount-list .item-row.table-discount .col-md-2:nth-child(7)::before {
                content: "💵 ราคา/หน่วย" !important;
                font-weight: bold !important;
                color: #e67e22 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            #discount-list .item-row.table-discount .col-md-2:nth-child(8)::before {
                content: "💸 ยอดรวม" !important;
                font-weight: bold !important;
                color: #e67e22 !important;
                font-size: 12px !important;
                display: block !important;
                margin-bottom: 4px !important;
            }

            /* Delete button styling for mobile */
            #quotation-table .item-row .col-md-1:last-child,
            #discount-list .item-row .col-md-1:last-child {
                text-align: center !important;
                margin-top: 20px !important;
                padding-top: 20px !important;
                border-top: 2px solid #eee !important;
            }

            #quotation-table .remove-row-btn,
            #discount-list .remove-row-btn {
                background: #dc3545 !important;
                color: white !important;
                padding: 10px 15px !important;
                border-radius: 25px !important;
                text-decoration: none !important;
                font-size: 14px !important;
                display: inline-block !important;
            }

            /* Mobile section styling */
            .section-card {
                margin-bottom: 25px !important;
                padding: 20px !important;
            }

            /* Mobile button styling */
            .btn {
                font-size: 16px !important;
                padding: 12px 25px !important;
                border-radius: 8px !important;
                margin: 10px 5px !important;
            }

            /* Mobile checkbox styling */
            input[type="checkbox"] {
                width: 20px !important;
                height: 20px !important;
                margin-right: 15px !important;
            }

            /* Hide elements that are not needed in mobile */
            .item-row [style*="display: none"] {
                display: none !important;
            }

            /* Mobile summary section */
            .summary .row {
                margin-bottom: 12px !important;
                background: #f8f9fa !important;
                padding: 8px !important;
                border-radius: 6px !important;
            }

            .summary .col-md-10,
            .summary .col-md-2 {
                width: 100% !important;
                max-width: 100% !important;
                text-align: left !important;
                padding: 5px 0 !important;
            }

            .summary .col-md-10 {
                font-weight: bold !important;
                color: #495057 !important;
                font-size: 14px !important;
            }

            .summary .col-md-2 {
                font-size: 18px !important;
                font-weight: bold !important;
                color: #007bff !important;
                text-align: right !important;
                margin-top: 5px !important;
            }
        }
    </style>
    <div class="container-fluid page-content">
        <div class="todo-listing">
            <div class="container border bg-white">
                <h4 class="text-center my-1"><i class="fa fa-file-invoice-dollar"
                        style="color:#1976d2;margin-right:8px;"></i>แก้ไขใบเสนอราคา </h4>
                <form action="{{ route('quote.update', $quotationModel->quote_id) }}" id="formQuoteModern" method="post">
                    @csrf
                    @method('PUT')
                    <div class="section-card">
                        <div class="section-title" style="background:linear-gradient(90deg,#4b98e5 60%,#8bbdfa 100%)"><i
                                class="fa fa-user-tie"></i> ข้อมูลทั่วไป</div>
                        <div class="row table-custom ">

                            <div class="col-md-2 ms-auto">
                                <label>เซลล์ผู้ขายแพคเกจ:</label>
                                <select name="quote_sale" class="form-select select2" required>
                                    @foreach ($sales as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($quotationModel) && $quotationModel->quote_sale == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 ms-3">
                                <label>วันที่สั่งซื้อ/จองแพคเกจ:</label>
                                <input type="date" id="displayDatepicker" name="quote_booking_create"
                                    class="form-control" required
                                    value="{{ $quotationModel->quote_booking_create ?? date('Y-m-d') }}">
                                {{-- <input type="hidden" id="submitDatepicker" name="quote_booking_create"
                                    value="{{ $quotationModel->quote_booking_create ?? date('Y-m-d') }}"> --}}
                            </div>
                            <div class="col-md-2">
                                <label>เลขที่ใบเสนอราคา</label>
                                <input type="text" class="form-control"
                                    value="{{ $quotationModel->quote_number ?? '' }}" disabled>
                            </div>
                            <div class="col-md-2">
                                <label>วันที่เสนอราคา</label>
                                <input type="date" id="displayDatepickerQuoteDate" name="quote_date" n
                                    class="form-control" required
                                    value="{{ $quotationModel->quote_date ?? date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="section-card">
                        <div class="section-title" style="background:linear-gradient(90deg,#d84315 60%,#ff7043 100%)"><i
                                class="fa fa-suitcase-rolling"></i> รายละเอียดแพคเกจทัวร์</div>
                        <div class="row table-custom">
                            <div class="col-md-6 position-relative">
                                <label>ชื่อแพคเกจทัวร์:</label>
                                <input type="text" id="tourSearch" class="form-control" name="quote_tour_name"
                                    placeholder="ค้นหาแพคเกจทัวร์...ENTER เพื่อค้นหา" required autocomplete="off"
                                    value="{{ $quotationModel->quote_tour_name ?? '' }}">
                                <button type="button" id="resetTourSearch"
                                    class="btn btn-link btn-sm position-absolute end-0 top-0"
                                    style="z-index:1100;right:10px;top:30px"><i class="fa fa-times"></i></button>
                                <div id="tourResults" class="list-group position-absolute w-100" style="z-index: 1000;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="">รหัสทัวร์ API <small
                                        class="text-danger">แก้ไขไม่ได้*</small></label>
                                <input type="text" id="tour-code" name="quote_tour" class="form-control"
                                    value="{{ $quotationModel->quote_tour ?? '' }}" readonly
                                    style="background-color: #81c7844b">
                            </div>

                            <div class="col-md-3">
                                <label for="">รหัสทัวร์ กำหนดเอง </label>
                                <input type="text" name="quote_tour_code" class="form-control"
                                    value="{{ $quotationModel->quote_tour_code ?? '' }}">
                            </div>


                            <input type="hidden" id="tourSearch1" class="form-control" name="quote_tour_name1">
                            {{-- <input type="hidden" id="tour-code" name="quote_tour"> --}}
                            <input type="hidden" id="tour-id">
                            <div class="col-md-3">
                                <label>ระยะเวลาทัวร์ (วัน/คืน): </label>
                                <select name="quote_numday" class="form-select" id="numday" required>
                                    <option value="">--เลือกระยะเวลา--</option>
                                    @foreach ($numDays as $item)
                                        <option data-day="{{ $item->num_day_total }}"
                                            value="{{ $item->num_day_name }}"
                                            {{ isset($quotationModel) && $quotationModel->quote_numday == $item->num_day_name ? 'selected' : '' }}>
                                            {{ $item->num_day_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>ประเทศที่เดินทาง: </label>
                                <select name="quote_country" class="form-select select2" id="country"
                                    style="width: 100%" required>
                                    <option value="">--เลือกประเทศที่เดินทาง--</option>
                                    @foreach ($country as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($quotationModel) && $quotationModel->quote_country == $item->id ? 'selected' : '' }}>
                                            {{ $item->iso2 }}-{{ $item->country_name_th }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>โฮลเซลล์: </label>
                                <select name="quote_wholesale" class="form-select select2" style="width: 100%"
                                    id="wholesale" required>
                                    <option value="">--เลือกโฮลเซลล์--</option>
                                    @foreach ($wholesale as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($quotationModel) && $quotationModel->quote_wholesale == $item->id ? 'selected' : '' }}>
                                            {{ $item->code }}-{{ $item->wholesale_name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>สายการบิน:</label>
                                <select name="quote_airline" class="form-select select2" style="width: 100%"
                                    id="airline" required>
                                    <option value="">--เลือกสายการบิน--</option>
                                    @foreach ($airline as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($quotationModel) && $quotationModel->quote_airline == $item->id ? 'selected' : '' }}>
                                            {{ $item->code }}-{{ $item->travel_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 position-relative">
                                <label>วันออกเดินทาง: <a href="#" class="" id="list-period"
                                        style="color:#1976d2;font-weight:500;">เลือกวันที่</a></label>
                                <input type="date" class="form-control" id="date-start-display"
                                    placeholder="วันออกเดินทาง..." required autocomplete="off">
                                <div id="date-list" class="list-group position-absolute w-100"
                                    style="z-index: 1000;"></div>
                                <input type="hidden" id="period1" name="period1">
                                <input type="hidden" id="period2" name="period2">
                                <input type="hidden" id="period3" name="period3">
                                <input type="hidden" id="period4" name="period4">
                                <input type="hidden" id="date-start" name="quote_date_start"
                                    value="{{ $quotationModel->quote_date_start ?? '' }}">
                                <script>
                                    $(function() {
                                        $('#date-start-display').val("{{ $quotationModel->quote_date_start ?? '' }}");
                                    });
                                </script>
                            </div>
                            <div class="col-md-3">
                                <label>วันเดินทางกลับ: </label>
                                <input type="date" class="form-control" id="date-end-display"
                                    placeholder="วันเดินทางกลับ..." required>
                                <input type="hidden" id="date-end" name="quote_date_end"
                                    value="{{ $quotationModel->quote_date_end ?? '' }}">
                                <script>
                                    $(function() {
                                        $('#date-end-display').val("{{ $quotationModel->quote_date_end ?? '' }}");
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="section-card">
                        <div class="section-title" style="background:linear-gradient(90deg,#43a047 60%,#81c784 100%)">
                            <i class="fa fa-users"></i> ข้อมูลลูกค้า
                        </div>
                        <div class="row table-custom">
                            <div class="col-md-3 position-relative">
                                <label class="">ชื่อลูกค้า:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="customer_name" id="customerSearch"
                                        placeholder="ชื่อลูกค้า...ENTER เพื่อค้นหา" required
                                        aria-describedby="basic-addon1" autocomplete="off"
                                        value="{{ $customer->customer_name ?? '' }}">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-new-customer" title="เพิ่มข้อมูลใหม่"><i class="fa fa-plus"></i></button>
                                </div>
                                <div id="customerResults" class="list-group position-absolute w-100"
                                    style="z-index: 1000;"></div>
        
                            </div>
                            <input type="hidden" id="customer-id" name="customer_id"
                                value="{{ $customer->customer_id ?? '' }}">
                            <input type="hidden" id="customer-new" name="customer_type_new"
                                value="{{ isset($customer) ? 'customerOld' : 'customerNew' }}">
                            <div class="col-md-3">
                                <label>อีเมล์:</label>
                                <input type="email" class="form-control" name="customer_email" placeholder="Email"
                                    aria-describedby="basic-addon1" id="customer_email"
                                    value="{{ $customer->customer_email ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label>เลขผู้เสียภาษี:</label>
                                <input type="text" id="texid" class="form-control" name="customer_texid"
                                    mix="13" placeholder="เลขประจำตัวผู้เสียภาษี"
                                    aria-describedby="basic-addon1" value="{{ $customer->customer_texid ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label>เบอร์โทรศัพท์ :</label>
                                <input type="text" class="form-control" name="customer_tel" id="customer_tel"
                                    placeholder="เบอร์โทรศัพท์" aria-describedby="basic-addon1"
                                    value="{{ $customer->customer_tel ?? '' }}">
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="col-md-12">
                                            <label>เบอร์โทรสาร :</label>
                                            <input type="text" class="form-control" id="fax"
                                                name="customer_fax" placeholder="เบอร์โทรศัพท์"
                                                aria-describedby="basic-addon1"
                                                value="{{ $customer->customer_fax ?? '' }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label>ลูกค้าจาก :</label>
                                            <select name="customer_campaign_source" class="form-select">
                                                @foreach ($campaignSource as $item)
                                                    <option value="{{ $item->campaign_source_id }}"
                                                        {{ isset($customer) && $customer->customer_campaign_source == $item->campaign_source_id ? 'selected' : '' }}>
                                                        {{ $item->campaign_source_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Social id</label>
                                            <input type="text" class="form-control" name="customer_social_id" value="{{ $customer->customer_social_id ?? '' }}"
                                                placeholder="Social id">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="col-md-12">
                                            <label>ที่อยู่:</label>
                                            <textarea name="customer_address" class="form-control" id="customer_address" cols="30" rows="7"
                                                placeholder="ที่อยู่">{{ $customer->customer_address ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="section-card">
                        <h6 class="section-inline "><i class="fa fa-coins"></i> ข้อมูลค่าบริการ <span id="pax"
                                class="float-end"></span></h6>

                        <div id="quotation-table" class="table-custom text-center"
                            style="background:#55ffb848;border-radius:8px;">
                            <div class="row g-0 item-row bg-success-subtle px-1">
                                <div class="col-md-1" style="font-size: 12px"><b>ลำดับ</b></div>
                                <div class="col-md-3" style="font-size: 12px"><b>รายการสินค้า</b></div>
                                <div class="col-md-1" style="font-size: 12px"><b>รวม 3%</b></div>
                                <div class="col-md-1" style="font-size: 12px"><b>NonVat</b></div>
                                <div class="col-md-1" style="font-size: 12px"><b>จำนวน</b></div>
                                <div class="col-md-2" style="font-size: 12px"><b>ราคา/หน่วย</b></div>
                                <div class="col-md-2" style="font-size: 12px"><b>ยอดรวม</b></div>
                            </div>

                            {{-- <div id="table-income"> --}}
                            @php $rowNum = 1; @endphp
                            <div id="table-income" style="background:#55ffb848;border-radius:8px;">
                                @foreach ($quoteProducts as $row)
                                    <div class="row  item-row table-income align-items-center">
                                        <div class="col-md-1"><span class="row-number">{{ $rowNum++ }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="product_id[]" class="form-select product-select select2"
                                                style="width: 100%;">
                                                <option value="">--เลือกสินค้า--</option>
                                                @foreach ($products as $product)
                                                    <option data-pax="{{ $product->product_pax }}"
                                                        value="{{ $product->id }}"
                                                        {{ $row->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->product_name }}{{ $product->product_pax === 'Y' ? '(Pax)' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1" style="display: none">
                                            <select name="expense_type[]" class="form-select">
                                                <option value="income" selected> รายได้ </option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <input type="checkbox" name="withholding_tax[]" class="vat-3"
                                                value="Y" {{ $row->withholding_tax == 'Y' ? 'checked' : '' }}>
                                            <input type="hidden" name="withholding_tax[]" value="N" disabled>
                                        </div>
                                        <div class="col-1 text-center">
                                            <select name="vat_status[]" class="vat-status form-select"
                                                style="width: 180%;">
                                                <option value="nonvat"
                                                    {{ $row->vat_status == 'nonvat' ? 'selected' : '' }}>nonVat
                                                </option>
                                                <option value="vat"
                                                    {{ $row->vat_status == 'vat' ? 'selected' : '' }}>
                                                    Vat</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1"><input type="number" name="quantity[]"
                                                style="width: 150%;" class="quantity form-control text-end"
                                                step="1" value="{{ $row->product_qty ?? 1 }}"></div>
                                        <div class="col-md-2"><input type="number" name="price_per_unit[]"
                                                class="price-per-unit form-control text-end" step="0.01"
                                                value="{{ $row->product_price ?? 0 }}"></div>
                                        <div class="col-md-2"><input type="number" name="total_amount[]"
                                                class="total-amount form-control text-end"
                                                value="{{ $row->product_sum ?? 0 }}" readonly></div>
                                        <div class="col-md-1 text-center">
                                            <a href="#" class="remove-row-btn text-danger"><i
                                                    class="fa fa-trash"></i></a>
                                            {{-- <button type="button" class="btn btn-danger btn-sm remove-row-btn "
                                            title="ลบแถว" style="font-size: 13px 10px"><i
                                                class="fa fa-trash"></i></button> --}}
                                        </div>
                                    </div>
                                @endforeach



                            </div>
                            {{-- </div> --}}
                        </div>
                        <div class="add-row">
                            <button type="button" class="btn btn-outline-success btn-sm mt-1" id="add-row-service">
                                <i class="fa fa-plus"></i> เพิ่มรายการ
                            </button>

                            {{-- <i class="fa fa-plus"></i><span id="add-row-service" style="cursor:pointer;">
                                เพิ่มรายการ</span> --}}
                        </div>
                        <hr>

                        <div class="col-md-12">
                            <label class="text-danger">ส่วนลด</label>
                            <div id="discount-list">
                                @php $rowNum = 1; @endphp
                                @foreach ($quoteProductsDiscount as $row)
                                    <div class="row item-row table-discount mb-1 align-items-center discount-row"
                                        data-row-id="discount-row-{{ $row->id }}"
                                        style="background:#fffbe7;border-radius:8px;">

                                        <div class="col-md-1 text-center discount-row-number">{{ $rowNum++ }}
                                        </div>

                                        <div class="col-md-3">
                                            <select name="product_id[]" class="form-select product-select select2"
                                                style="width: 100%;">
                                                <option value="">--เลือกส่วนลด--</option>
                                                @foreach ($productDiscount as $product)
                                                    <option value="{{ $product->id }}"
                                                        {{ $row->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->product_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-1" style="display: none;">
                                            <select name="expense_type[]" class="form-select">
                                                <option value="discount" selected>ส่วนลด</option>
                                            </select>
                                        </div>

                                        <div class="col-md-1 text-center">
                                            <input type="hidden" name="withholding_tax[]" value="N">
                                        </div>

                                        <div class="col-md-1 text-center">
                                            <select name="vat_status[]" class="vat-status form-select"
                                                style="width: 180%;">
                                                <option value="nonvat"
                                                    {{ $row->vat_status == 'nonvat' ? 'selected' : '' }}>nonVat
                                                </option>
                                                <option value="vat"
                                                    {{ $row->vat_status == 'vat' ? 'selected' : '' }}>Vat</option>
                                            </select>
                                        </div>

                                        <div class="col-md-1">
                                            <input type="number" name="quantity[]"
                                                class="quantity form-control text-end" step="1"
                                                style="width: 150%;" value="{{ $row->product_qty ?? 1 }}">
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" name="price_per_unit[]"
                                                class="price-per-unit form-control text-end" step="0.01"
                                                value="{{ $row->product_price ?? 0 }}">
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" name="total_amount[]"
                                                class="total-amount form-control text-end"
                                                value="{{ $row->product_sum ?? 0 }}" readonly>
                                        </div>

                                        <div class="col-md-1 text-center">
                                            <a href="#" class="remove-row-btn text-danger remove-discount-row"><i
                                                    class="fa fa-trash"></i></a>

                                            {{-- <button type="button" class="btn btn-danger btn-sm remove-row-btn" title="ลบแถว">
                                            <i class="fa fa-trash"></i>
                                        </button> --}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-danger btn-sm mt-1"
                                    id="add-row-discount">
                                    <i class="fa fa-plus"></i> เพิ่มส่วนลด
                                </button>
                            </div>
                        </div>



                    </div>
                    <hr class="divider">
                    <div class="section-card">
                        <div class="section-title" style="background:linear-gradient(90deg,#1976d2 60%,#42a5f5 100%)">
                            <i class="fa fa-calculator"></i> สรุปยอดและ VAT
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="vat-method">การคำนวณ VAT:</label>
                                            <div>
                                                <input type="radio" id="vat-include" name="vat_type"
                                                    value="include"
                                                    {{ $quotationModel->vat_type == 'include' ? 'checked' : '' }}>
                                                <label for="vat-include">คำนวณรวมกับราคาสินค้าและบริการ (VAT
                                                    Include)</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="vat-exclude" name="vat_type"
                                                    value="exclude"
                                                    {{ $quotationModel->vat_type == 'exclude' ? 'checked' : '' }}>
                                                <label for="vat-exclude">คำนวณแยกกับราคาสินค้าและบริการ (VAT
                                                    Exclude)</label>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row summary-row">
                                            <div class="col-md-10">
                                                <input type="checkbox" name="quote_withholding_tax_status"
                                                    value="Y" id="withholding-tax"
                                                    {{ $quotationModel->quote_withholding_tax_status == 'Y' ? 'checked' : '' }}>
                                                <span class="">คิดภาษีหัก ณ ที่จ่าย 3% (คำนวณจากยอด
                                                    ราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT Amount)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>จำนวนเงินภาษีหัก ณ ที่จ่าย 3% : &nbsp;</label><span class="text-danger"
                                            id="withholding-amount"> 0.00</span> บาท
                                        <hr>
                                    </div>
                                    <div class="col-md-12" style="padding-bottom: 10px">
                                        <label>บันทึกเพิ่มเติม</label>
                                        <textarea name="quote_note" class="form-control" cols="30" rows="2">{{ $quotationModel->quote_note ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="summary text-info">
                                        <div class="row summary-row ">
                                            <div class="col-md-10 text-end">ยอดรวมยกเว้นภาษี / Vat-Exempted Amount
                                            </div>
                                            <div class="col-md-2 text-end"><span id="sum-total-nonvat">0.00</span>
                                            </div>
                                        </div>
                                        <div class="row summary-row ">
                                            <div class="col-md-10 text-end">ราคาสุทธิสินค้าที่เสียภาษี / Pre-Tax
                                                Amount:</div>
                                            <div class="col-md-2 text-end"><span id="sum-total-vat">0.00</span></div>
                                        </div>
                                        <div class="row summary-row">
                                            <div class="col-md-10 text-end">ส่วนลด / Discount :</div>
                                            <div class="col-md-2 text-end"><span id="sum-discount">0.00</span></div>
                                        </div>
                                        <div class="row summary-row">
                                            <div class="col-md-10 text-end">ราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT Amount:
                                            </div>
                                            <div class="col-md-2 text-end"><span id="sum-pre-vat">0.00</span></div>
                                        </div>
                                        <div class="row summary-row">
                                            <div class="col-md-10 text-end">ภาษีมูลค่าเพิ่ม VAT : 7%:</div>
                                            <div class="col-md-2 text-end"><span id="vat-amount">0.00</span></div>
                                        </div>
                                        <div class="row summary-row ">
                                            <div class="col-md-10 text-end">ราคารวมภาษีมูลค่าเพิ่ม / Include VAT:</div>
                                            <div class="col-md-2 text-end"><span id="sum-include-vat">0.00</span>
                                            </div>
                                        </div>
                                        <div class="row summary-row">
                                            <div class="col-md-10 text-end">จำนวนเงินรวมทั้งสิ้น / Grand Total:</div>
                                            <div class="col-md-2 text-end"><b><span class="bg-warning"
                                                        id="grand-total">0.00</span></b></div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="section-card">
                        <div class="section-title"
                            style="background:linear-gradient(90deg,#fbc02d 60%,#fff176 100%);color:#333;"><i
                                class="fa fa-hand-holding-usd"></i> เงื่อนไขการชำระเงิน</div>
                        <div class="row">
                            <div class="col-md-12">
                                <h6 style="color:#1976d2;">เงื่อนไขการชำระเงิน</h6>
                            </div>
                            {{-- {{$quotationModel->quote_payment_type}} --}}
                            <div class="col-md-12 ">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="radio" name="quote_payment_type" id="quote-payment-deposit"
                                            value="deposit"
                                            {{ isset($quotationModel) && $quotationModel->quote_payment_type == 'deposit' ? 'checked' : '' }}>
                                        <label for="quote-payment-type"> เงินมัดจำ </label>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {{-- DEBUG : {{ $quotationModel->quote_payment_date ?? '' }} --}}
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">ภายในวันที่</span>
                                                <input type="datetime-local" class="form-control"
                                                    name="quote_payment_date" id="quote-payment-date"
                                                    value="{{ $quotationModel->quote_payment_date ?? '' }}">

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group mb-4">
                                                <span class="input-group-text" for="">เรทเงินมัดจำ</span>
                                                <select name="quote_payment_price" class="form-select"
                                                    id="quote-payment-price">
                                                    <option value="0"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 0 ? 'selected' : '' }}>
                                                        0.00</option>
                                                    <option value="1000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 1000 ? 'selected' : '' }}>
                                                        1,000</option>
                                                    <option value="1500"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 1500 ? 'selected' : '' }}>
                                                        1,500</option>
                                                    <option value="2000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 2000 ? 'selected' : '' }}>
                                                        2,000</option>
                                                    <option value="3000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 3000 ? 'selected' : '' }}>
                                                        3,000</option>
                                                    <option value="4000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 4000 ? 'selected' : '' }}>
                                                        4,000</option>
                                                    <option value="5000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 5000 ? 'selected' : '' }}>
                                                        5,000</option>
                                                    <option value="6000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 6000 ? 'selected' : '' }}>
                                                        6,000</option>
                                                    <option value="7000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 7000 ? 'selected' : '' }}>
                                                        7,000</option>
                                                    <option value="8000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 8000 ? 'selected' : '' }}>
                                                        8,000</option>
                                                    <option value="9000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 9000 ? 'selected' : '' }}>
                                                        9,000</option>
                                                    <option value="10000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 10000 ? 'selected' : '' }}>
                                                        10,000</option>
                                                    <option value="15000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 15000 ? 'selected' : '' }}>
                                                        15,000</option>
                                                    <option value="20000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 20000 ? 'selected' : '' }}>
                                                        20,000</option>
                                                    <option value="30000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 30000 ? 'selected' : '' }}>
                                                        30,000</option>
                                                    <option value="24000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 24000 ? 'selected' : '' }}>
                                                        24,000</option>
                                                    <option value="25000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 25000 ? 'selected' : '' }}>
                                                        25,000</option>
                                                    <option value="28000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 28000 ? 'selected' : '' }}>
                                                        28,000</option>
                                                    <option value="29000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 29000 ? 'selected' : '' }}>
                                                        29,000</option>
                                                    <option value="34000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 34000 ? 'selected' : '' }}>
                                                        34,000</option>
                                                    <option value="50000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 50000 ? 'selected' : '' }}>
                                                        50,000</option>
                                                    <option value="70000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 70000 ? 'selected' : '' }}>
                                                        70,000</option>
                                                    <option value="35000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 35000 ? 'selected' : '' }}>
                                                        35,000</option>
                                                    <option value="40000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 40000 ? 'selected' : '' }}>
                                                        40,000</option>
                                                    <option value="45000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 45000 ? 'selected' : '' }}>
                                                        45,000</option>
                                                    <option value="80000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 80000 ? 'selected' : '' }}>
                                                        80,000</option>
                                                    <option value="30500"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 30500 ? 'selected' : '' }}>
                                                        30,500</option>
                                                    <option value="35500"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 35500 ? 'selected' : '' }}>
                                                        35,500</option>
                                                    <option value="36000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 36000 ? 'selected' : '' }}>
                                                        36,000</option>
                                                    <option value="38000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 38000 ? 'selected' : '' }}>
                                                        38,000</option>
                                                    <option value="100000"
                                                        {{ isset($quotationModel) && $quotationModel->quote_payment_price == 100000 ? 'selected' : '' }}>
                                                        100,000</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" for="">ชำระเพิ่มเติม</span>
                                                <input type="number" id="pay-extra" class="form-control"
                                                    name="quote_payment_extra" placeholder="0.00"
                                                    value="{{ $quotationModel->quote_payment_extra ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" for="">จำนวนที่ต้องชำระ</span>
                                                <input type="number" class="form-control pax-total"
                                                    name="quote_payment_total" step="0.01" placeholder="0.00"
                                                    value="{{ $quotationModel->quote_payment_total ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <input type="radio" name="quote_payment_type"
                                                    id="quote-payment-full" value="full"
                                                    {{ isset($quotationModel) && $quotationModel->quote_payment_type == 'full' ? 'checked' : '' }}>
                                                <label for="quote-payment-type"> ชำระเต็มจำนวน</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {{-- DEBUG : {{ $quotationModel->quote_payment_date_full ?? '' }} --}}
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">ภายในวันที่</span>
                                                <input type="datetime-local" class="form-control"
                                                    id="quote-payment-date-full" name="quote_payment_date_full"
                                                    value="{{ $quotationModel->quote_payment_date_full ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" for="">จำนวนเงิน</span>
                                                <input type="number" class="form-control"
                                                    name="quote_payment_total_full" id="payment-total-full"
                                                    step="0.01" placeholder="0.00"
                                                    value="{{ $quotationModel->quote_payment_total_full ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="booking-create-date" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        @can('quote.comission')
                            <div class="row mb-3 ">
                                <div class="col-md-3">
                                    <label>สถานะการจ่ายค่าคอมมิชชั่น:</label>
                                    <div>
                                        <input type="radio" name="quote_commission" id="commission-yes" value="Y"
                                            {{ isset($quotationModel) && $quotationModel->quote_commission == 'Y' ? 'checked' : '' }}>
                                        <label for="commission-yes">จ่ายค่าคอม</label>
                                        <input type="radio" name="quote_commission" id="commission-no" value="N"
                                            {{ isset($quotationModel) && $quotationModel->quote_commission == 'N' ? 'checked' : '' }}>
                                        <label for="commission-no">ไม่จ่ายค่าคอม</label>
                                    </div>
                                </div>

                                <div class="col-md-9" id="note-commission-block" style="display: none;">
                                    <label>บันทึกเหตุผลกรณีไม่จ่ายค่าคอมมิชชั่น</label>
                                    <textarea name="quote_note_commission" class="form-control" id="quote_note_commission" rows="2">{{ $quotationModel->quote_note_commission ?? '' }}</textarea>
                                </div>

                            </div>
                        @endcan

                        <input type="hidden" name="quote_vat_exempted_amount">
                        <input type="hidden" name="quote_pre_tax_amount">
                        <input type="hidden" name="quote_discount">
                        <input type="hidden" name="quote_pre_vat_amount">
                        <input type="hidden" name="quote_vat">
                        <input type="hidden" name="quote_include_vat">
                        <input type="hidden" name="quote_grand_total" id="quote-grand-total">
                        <input type="hidden" name="quote_withholding_tax">
                        <input type="hidden" name="quote_pax_total" id="quote-pax-total">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>

                        <button type="submit" class="btn btn-primary btn-sm mx-3" form="formQuoteModern"><i
                                class="fa fa-save"></i> บันทึกการเปลียนแปลง</button>
                    </div>

                    <br>
                </form>
            </div>
            <br>
        </div>
    </div>

    <script>

        // --- ปุ่มเพิ่มข้อมูลลูกค้าใหม่ ---
        $(document).on('click', '#btn-new-customer', function() {
            $('#customerSearch').val('');
            $('#customer-id').val('');
            $('#customer_email').val('');
            $('#customer_tel').val('');
            $('#customer_address').val('');
            $('#texid').val('');
            $('#fax').val('');
            $('select[name="customer_campaign_source"]').val('');
            $('input[name="customer_social_id"]').val('');
            $('#customer-new').val('customerNew');
        });

        $('.form-select.select2').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({
                    width: '100%',
                    dropdownParent: $(this).closest('.modal-body')
                });
            }
        });

        $('#formQuoteModern').on('submit', function() {
            // สำหรับทุก .vat-3 (checkbox)
            $('.vat-3').each(function() {
                // ถ้าไม่ได้ติ๊ก ให้ enable hidden input (N) และ disable checkbox
                if (!$(this).is(':checked')) {
                    $(this).prop('disabled', true)
                        .siblings('input[type="hidden"][name="withholding_tax[]"]').prop('disabled', false);
                } else {
                    $(this).siblings('input[type="hidden"][name="withholding_tax[]"]').prop('disabled',
                        true);
                }
            });
        });

        function formatNumber(num) {
            return Number(num).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }




        $(function() {
            function toggleNoteCommission() {
                var val = $('input[name="quote_commission"]:checked').val();
                if (val === 'N') {
                    $('#note-commission-block').show();
                } else {
                    $('#note-commission-block').hide();
                }
            }
            $(document).on('change', 'input[name="quote_commission"]', toggleNoteCommission);
            // เรียกครั้งแรกตอนโหลด ถ้ามีค่าเดิม
            toggleNoteCommission();
        });

        function calculateDatePayment() {
            // --- เงื่อนไขการชำระเงิน ---
            if (!skipPaymentCondition) {
                var bookingCreateDate = new Date($('#date-start').val());
                var travelDate = new Date($('#date-start').val());
                var dateNow = new Date();
                var bookingDate = new Date($('#booking-create-date').val());
                var diffDays = (travelDate - bookingDate) / (1000 * 60 * 60 * 24);

                if (diffDays >= 31) {
                    bookingCreateDate.setDate(bookingCreateDate.getDate() - 30);
                    $('#quote-payment-deposit').prop('checked', true);
                    // set default deposit rate to 5000 when auto-select deposit
                    $('#quote-payment-price').val('5000');
                } else {
                    bookingCreateDate = new Date();
                    bookingCreateDate.setDate(dateNow.getDate() + 1);
                    $('#quote-payment-full').prop('checked', true);
                }
                bookingCreateDate.setHours(13, 0, 0, 0);
                var year = bookingCreateDate.getFullYear();
                var month = ('0' + (bookingCreateDate.getMonth() + 1)).slice(-2);
                var day = ('0' + bookingCreateDate.getDate()).slice(-2);
                var hours = ('0' + bookingCreateDate.getHours()).slice(-2);
                var minutes = ('0' + bookingCreateDate.getMinutes()).slice(-2);
                var formattedDate = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                $('input[name="quote_payment_date"]').val(formattedDate);
                $('#quote-payment-date').val(formattedDate);
                $('#quote-payment-date-new').val(formattedDate);
                $('input[name="quote_payment_date_full"]').val(formattedDate);
                $('#quote-payment-date-full').val(formattedDate);
            }
        };



        $(function() {
            // เมื่อคลิก 'เลือกวันที่' ให้แสดง list วันที่เดินทางของทัวร์ที่เลือก
            $(document).on('click', '#list-period', function(e) {
                e.preventDefault();
                var tourId = $('#tour-id').val();
                $('#date-list').empty();
                if (!tourId) {
                    $('#date-list').append(
                        '<div class="list-group-item text-danger">กรุณาเลือกแพคเกจทัวร์ก่อน</div>');
                    return;
                }
                $.ajax({
                    url: '{{ route('api.period') }}',
                    method: 'GET',
                    data: {
                        search: tourId
                    },
                    success: function(periods) {
                        if (Array.isArray(periods) && periods.length > 0) {
                            var now = new Date();
                            $.each(periods, function(i, period) {
                                var dateObject = new Date(period.start_date);
                                if (dateObject > now) {
                                    var dateText = dateObject.toLocaleDateString(
                                        'th-TH', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        });
                                    var periodHtml =
                                        `<a href="#" class="period-select list-group-item list-group-item-action mb-1" data-tour="${tourId}" data-numday="${$('#numday option:selected').text()}" data-airline="${$('#airline').val()}" data-wholesale="${$('#wholesale').val()}" data-code="${$('#tour-code').val()}" data-name1="${$('#tourSearch1').val()}" data-name="${$('#tourSearch').val()}" data-period1="${period.price1}" data-period2="${period.price2}" data-period3="${period.price3}" data-period4="${period.price4}" data-date="${period.start_date}">${dateText}</a>`;
                                    $('#date-list').append(periodHtml);
                                }
                            });
                        } else {
                            $('#date-list').append(
                                '<div class="list-group-item text-danger">ไม่พบช่วงวันเดินทาง</div>'
                            );
                        }
                    },
                    error: function() {
                        $('#date-list').append(
                            '<div class="list-group-item text-danger">เกิดข้อผิดพลาดในการดึงข้อมูลวันเดินทาง</div>'
                        );
                    }
                });
            });
            // --- Customer Autocomplete (เหมือน create.blade.php) ---
            // --- Customer Autocomplete (เหมือน create.blade.php) ---
            $('#customerSearch').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
            $('#customerSearch').on('input', function(e) {
                var searchTerm = $(this).val();
                if (searchTerm.length >= 2) {
                    $.ajax({
                        url: '{{ route('api.customer') }}',
                        method: 'GET',
                        data: {
                            search: searchTerm
                        },
                        success: function(data) {
                            $('#customerResults').empty();
                            if (data.length > 0) {
                                $.each(data, function(index, item) {
                                    $('#customerResults').append(`
                                <a href="#" class="list-group-item list-group-item-action"
                                    data-id="${item.customer_id}"
                                    data-name="${item.customer_name}"
                                    data-email="${item.customer_email}"
                                    data-taxid="${item.customer_texid}"
                                    data-tel="${item.customer_tel}"
                                    data-fax="${item.customer_fax}"
                                    data-address="${item.customer_address}"
                                >${item.customer_email} - ${item.customer_name} - ${item.customer_tel}</a>
                            `);
                                });
                                // เพิ่มรายการ "กำหนดเอง"
                                $('#customerResults').append(`
                            <a href="#" id="custom-input" class="list-group-item list-group-item-action">กำหนดเอง</a>
                        `);
                            }
                        }
                    });
                } else {
                    $('#customerResults').empty();
                }
            });
            // เมื่อเลือกข้อมูลจากรายการค้นหา
            $(document).on('click', '#customerResults a', function(e) {
                e.preventDefault();
                var selectedId = $(this).data('id') || '';
                var selectedText = $(this).data('name') || '';
                var customerEmail = $(this).data('email') || '';
                var customerTaxid = $(this).data('taxid') || '';
                var customerTel = $(this).data('tel') || '';
                var customerFax = $(this).data('fax') || '';
                var customerAddress = $(this).data('address') || '';
                if ($(this).attr('id') === 'custom-input') {
                    var customSearchText = $('#customerSearch').val();
                    $('#customer_email').val('');
                    $('#texid').val('');
                    $('#customer_tel').val('');
                    $('#customer_fax').val('');
                    $('#customer_address').val('');
                    $('#customerSearch').val(customSearchText);
                    $('#customer-id').val('');
                    $('#customer-new').val('customerNew');
                } else {
                    $('#customer_email').val(customerEmail);
                    $('#texid').val(customerTaxid);
                    $('#customer_tel').val(customerTel);
                    $('#customer_fax').val(customerFax);
                    $('#customer_address').val(customerAddress);
                    $('#customerSearch').val(selectedText);
                    $('#customer-id').val(selectedId);
                    $('#customer-new').val('customerOld');
                }
                $('#customerResults').empty();
            });
            // ปิดผลลัพธ์เมื่อคลิกนอก
            $(document).on('click', function(event) {
                if (!$(event.target).closest('#customerResults, #customerSearch').length) {
                    $('#customerResults').empty();
                }
            });
            // แก้ select2 ใช้กับทุก product-select (ทั้งที่มาจาก Blade และที่เพิ่มใหม่) และให้ dropdown แสดงใน modal
            $('.product-select.select2').each(function() {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2({
                        width: '100%',
                        dropdownParent: $(this).closest('.modal-body')
                    });
                }
            });
            // trigger คำนวณใหม่เมื่อเปลี่ยน VAT Include/Exclude
            $('input[name="vat_type"]').on('change', function() {
                calculatePaymentCondition();
            });
            // trigger คำนวณ withholding ทันทีเมื่อเปลี่ยน checkbox สรุป
            $('#withholding-tax').on('change', function() {
                calculatePaymentCondition();
            });

            // ฟังก์ชันคำนวณยอดเงินมัดจำและ sync ช่องชำระเต็มจำนวน
            function syncDepositAndFullPayment() {
                var isDeposit = $('#quote-payment-deposit').is(':checked');
                var isFull = $('#quote-payment-full').is(':checked');
                var depositRate = parseFloat($('#quote-payment-price').val().replace(/,/g, '')) || 0;
                var pax = parseFloat($('#quote-pax-total').val().replace(/,/g, '')) || 0;
                var payExtra = parseFloat($('#pay-extra').val().replace(/,/g, '')) || 0;
                var grandTotal = parseFloat($('#grand-total').text().replace(/,/g, '')) || 0;
                var depositTotal = 0;
                // กรณีเลือกเงินมัดจำ
                if (isDeposit) {
                    depositTotal = (depositRate * pax) + payExtra;
                    $('input[name="quote_payment_total"]').val(depositTotal.toFixed(2));
                    // set default payment date to next day from today
                    var today = new Date();
                    today.setDate(today.getDate() + 1);
                    today.setHours(13, 0, 0, 0);
                    var year = today.getFullYear();
                    var month = ('0' + (today.getMonth() + 1)).slice(-2);
                    var day = ('0' + today.getDate()).slice(-2);
                    var hours = ('0' + today.getHours()).slice(-2);
                    var minutes = ('0' + today.getMinutes()).slice(-2);
                    var formattedDate = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                    $('input[name="quote_payment_date"]').val(formattedDate);
                    $('#quote-payment-date').val(formattedDate);
                    $('#quote-payment-date-new').val(formattedDate);
                } else if (isFull) {
                    // ถ้าเลือกชำระเต็มจำนวน แต่มีการกรอก payExtra ให้คำนวณ depositTotal เฉพาะ payExtra
                    if (payExtra > 0) {
                        depositTotal = payExtra;
                        $('input[name="quote_payment_total"]').val(depositTotal.toFixed(2));
                    } else {
                        $('input[name="quote_payment_total"]').val('');
                    }
                }
                // sync ช่องชำระเต็มจำนวน (ยอดที่เหลือ) ให้แสดงเสมอ
                var remain = grandTotal - depositTotal;
                if (remain < 0) remain = 0;
                $('#payment-total-full').val(remain.toFixed(2));
                // ถ้าเลือกเงินมัดจำ ให้ readonly ช่องนี้, ถ้าเลือกชำระเต็มจำนวน ให้แก้ไขได้
                if (isDeposit) {
                    $('#payment-total-full').prop('readonly', true);
                } else if (isFull) {
                    $('#payment-total-full').prop('readonly', false);
                }
            }

            // เมื่อเลือก radio ชำระเต็มจำนวน
            $('#quote-payment-full').on('change', function() {
                if ($(this).is(':checked')) {
                    // set default payment date for full payment to next day from today
                    var today = new Date();
                    today.setDate(today.getDate() + 1);
                    today.setHours(13, 0, 0, 0);
                    var year = today.getFullYear();
                    var month = ('0' + (today.getMonth() + 1)).slice(-2);
                    var day = ('0' + today.getDate()).slice(-2);
                    var hours = ('0' + today.getHours()).slice(-2);
                    var minutes = ('0' + today.getMinutes()).slice(-2);
                    var formattedDate = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                    $('input[name="quote_payment_date_full"]').val(formattedDate);
                    $('#quote-payment-date-full').val(formattedDate);
                    // clear all deposit fields
                    $('#quote-payment-price').val('0');
                    $('#pay-extra').val('');
                    $('input[name="quote_payment_total"]').val('');
                    $('input[name="quote_payment_date"]').val('');
                    $('#quote-payment-date').val('');
                    $('#quote-payment-date-new').val('');
                    syncDepositAndFullPayment();
                }
            });
            // เมื่อเลือก radio เงินมัดจำ หรือเปลี่ยนเรทเงินมัดจำ/จำนวน pax ให้คำนวณใหม่
            $('#quote-payment-deposit, #quote-payment-price').on('change input', function() {
                if ($('#quote-payment-deposit').is(':checked')) {
                    syncDepositAndFullPayment();
                }
            });
            // เมื่อกรอก payExtra ให้ trigger syncDepositAndFullPayment() เสมอ ไม่ว่าจะเลือก deposit หรือ full
            $('#pay-extra').on('change input', function() {
                syncDepositAndFullPayment();
            });
            // เมื่อผู้ใช้เลือก radio เงินมัดจำ ให้ set วันที่เป็นวันถัดไปของวันปัจจุบันเสมอ
            $('#quote-payment-deposit').on('change', function() {
                if ($(this).is(':checked')) {
                    // set default deposit rate to 5000
                    $('#quote-payment-price').val('5000');
                    // set default payment date to next day from today
                    var today = new Date();
                    today.setDate(today.getDate() + 1);
                    today.setHours(13, 0, 0, 0);
                    var year = today.getFullYear();
                    var month = ('0' + (today.getMonth() + 1)).slice(-2);
                    var day = ('0' + today.getDate()).slice(-2);
                    var hours = ('0' + today.getHours()).slice(-2);
                    var minutes = ('0' + today.getMinutes()).slice(-2);
                    var formattedDate = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                    $('input[name="quote_payment_date"]').val(formattedDate);
                    $('#quote-payment-date').val(formattedDate);
                    $('#quote-payment-date-new').val(formattedDate);

                    // set full payment date ตาม logic ใหม่
                    var travelDateStr = $('#date-start').val();
                    if (travelDateStr) {
                        var travelDate = new Date(travelDateStr);
                        var now = new Date();
                        now.setHours(0, 0, 0, 0);
                        var diffDays = (travelDate - now) / (1000 * 60 * 60 * 24);
                        var fullPayDateObj;
                        if (diffDays > 30) {
                            travelDate.setDate(travelDate.getDate() - 30);
                            travelDate.setHours(13, 0, 0, 0);
                            fullPayDateObj = travelDate;
                        } else {
                            fullPayDateObj = new Date();
                            fullPayDateObj.setDate(fullPayDateObj.getDate() + 1);
                            fullPayDateObj.setHours(13, 0, 0, 0);
                        }
                        var y = fullPayDateObj.getFullYear();
                        var m = ('0' + (fullPayDateObj.getMonth() + 1)).slice(-2);
                        var d = ('0' + fullPayDateObj.getDate()).slice(-2);
                        var h = ('0' + fullPayDateObj.getHours()).slice(-2);
                        var min = ('0' + fullPayDateObj.getMinutes()).slice(-2);
                        var fullPayDate = y + '-' + m + '-' + d + 'T' + h + ':' + min;
                        $('input[name="quote_payment_date_full"]').val(fullPayDate);
                        $('#quote-payment-date-full').val(fullPayDate);
                    } else {
                        $('input[name="quote_payment_date_full"]').val('');
                        $('#quote-payment-date-full').val('');
                    }

                    // recalculate and set deposit total immediately
                    syncDepositAndFullPayment();
                }
            });
            // เมื่อจำนวน pax เปลี่ยน (triggered จาก calculatePaymentCondition)
            $('#quote-pax-total').on('change input', function() {
                if ($('#quote-payment-deposit').is(':checked')) {
                    // set full payment date ตาม logic ใหม่
                    var travelDateStr = $('#date-start').val();
                    if (travelDateStr) {
                        var travelDate = new Date(travelDateStr);
                        var now = new Date();
                        now.setHours(0, 0, 0, 0);
                        var diffDays = (travelDate - now) / (1000 * 60 * 60 * 24);
                        var fullPayDateObj;
                        if (diffDays > 30) {
                            travelDate.setDate(travelDate.getDate() - 30);
                            travelDate.setHours(13, 0, 0, 0);
                            fullPayDateObj = travelDate;
                        } else {
                            fullPayDateObj = new Date();
                            fullPayDateObj.setDate(fullPayDateObj.getDate() + 1);
                            fullPayDateObj.setHours(13, 0, 0, 0);
                        }
                        var y = fullPayDateObj.getFullYear();
                        var m = ('0' + (fullPayDateObj.getMonth() + 1)).slice(-2);
                        var d = ('0' + fullPayDateObj.getDate()).slice(-2);
                        var h = ('0' + fullPayDateObj.getHours()).slice(-2);
                        var min = ('0' + fullPayDateObj.getMinutes()).slice(-2);
                        var fullPayDate = y + '-' + m + '-' + d + 'T' + h + ':' + min;
                        $('input[name="quote_payment_date_full"]').val(fullPayDate);
                        $('#quote-payment-date-full').val(fullPayDate);
                    } else {
                        $('input[name="quote_payment_date_full"]').val('');
                        $('#quote-payment-date-full').val('');
                    }
                    syncDepositAndFullPayment();
                }
            });
            // เรียก syncDepositAndFullPayment() หลังคำนวณเสร็จใน calculatePaymentCondition โดยตรง
            // เมื่อเลือกหรือเปลี่ยนวันออกเดินทาง ให้คำนวณวันเดินทางกลับอัตโนมัติ (ใช้ระยะเวลาทัวร์)
            $('#date-start-display').on('change.auto', function() {
                var val = $(this).val();
                var datePattern = /^\d{4}-\d{2}-\d{2}$/;
                var dateObject = null;
                if (datePattern.test(val)) {
                    dateObject = new Date(val);
                } else {
                    dateObject = new Date(val);
                }
                if (dateObject && !isNaN(dateObject.getTime())) {
                    $('#date-start').val(dateObject.toISOString().slice(0, 10));
                    // คำนวณวันเดินทางกลับ
                    var numDays = parseInt($('#numday option:selected').data('day')) || 0;
                    if (numDays > 0) {
                        var endDate = new Date(dateObject);
                        endDate.setDate(dateObject.getDate() + numDays - 1);
                        $('#date-end').val(endDate.toISOString().slice(0, 10));
                        $('#date-end-display').val(endDate.toISOString().slice(0, 10));
                    }
                    // ตรวจสอบวันออกเดินทางต้องไม่ย้อนหลังจากวันนี้
                    var today = new Date();
                    today.setHours(0, 0, 0, 0);
                    if (dateObject < today) {
                        alert('วันออกเดินทางต้องไม่ย้อนหลังจากวันปัจจุบัน');
                    }
                    // เรียกฟังก์ชันคำนวณเงื่อนไขการชำระเงิน
                    calculatePaymentCondition();
                }
            });
            // เมื่อเลือกหรือกรอกวันเดินทางกลับ ให้คำนวณวันออกเดินทางย้อนหลัง (ใช้ระยะเวลาทัวร์)
            $('#date-end-display').on('change.auto', function() {
                var val = $(this).val();
                var datePattern = /^\d{4}-\d{2}-\d{2}$/;
                var endDate = null;
                if (datePattern.test(val)) {
                    endDate = new Date(val);
                } else {
                    endDate = new Date(val);
                }
                if (endDate && !isNaN(endDate.getTime())) {
                    $('#date-end').val(endDate.toISOString().slice(0, 10));
                    // คำนวณวันออกเดินทางย้อนหลัง
                    var numDays = parseInt($('#numday option:selected').data('day')) || 0;
                    if (numDays > 0) {
                        var startDate = new Date(endDate);
                        startDate.setDate(endDate.getDate() - numDays + 1);
                        // ตรวจสอบวันออกเดินทางต้องไม่ย้อนหลังจากวันนี้
                        var today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (startDate < today) {
                            alert('วันออกเดินทางต้องไม่ย้อนหลังจากวันปัจจุบัน');
                        }
                        $('#date-start').val(startDate.toISOString().slice(0, 10));
                        $('#date-start-display').val(startDate.toISOString().slice(0, 10));
                    }
                }
            });

            // เมื่อเปลี่ยนระยะเวลาทัวร์ (วัน/คืน) ให้คำนวณวันเดินทางกลับใหม่ถ้ามีวันออกเดินทาง
            $('#numday').on('change.auto', function() {
                var startVal = $('#date-start-display').val();
                var datePattern = /^\d{4}-\d{2}-\d{2}$/;
                var dateObject = null;
                if (datePattern.test(startVal)) {
                    dateObject = new Date(startVal);
                } else {
                    dateObject = new Date(startVal);
                }
                if (dateObject && !isNaN(dateObject.getTime())) {
                    var numDays = parseInt($('#numday option:selected').data('day')) || 0;
                    if (numDays > 0) {
                        var endDate = new Date(dateObject);
                        endDate.setDate(dateObject.getDate() + numDays - 1);
                        $('#date-end').val(endDate.toISOString().slice(0, 10));
                        $('#date-end-display').val(endDate.toISOString().slice(0, 10));
                    }
                }
            });

            // ฟังก์ชันแยกสำหรับคำนวณเงื่อนไขการชำระเงิน (Deposit/Full) และวันที่
            // function calculatePaymentDateCondition() {
            //     var bookingCreateDate = new Date($('#date-start').val());
            //     var travelDate = new Date($('#date-start').val());
            //     var dateNow = new Date();
            //     var bookingDate = new Date($('#booking-create-date').val());
            //     var diffDays = (travelDate - bookingDate) / (1000 * 60 * 60 * 24);

            //     if (diffDays >= 31) {
            //         bookingCreateDate.setDate(bookingCreateDate.getDate() - 30);
            //         $('#quote-payment-deposit').prop('checked', true);
            //         $('#quote-payment-price').val('5000');
            //     } else {
            //         bookingCreateDate = new Date();
            //         bookingCreateDate.setDate(dateNow.getDate() + 1);
            //         $('#quote-payment-full').prop('checked', true);
            //     }
            //     bookingCreateDate.setHours(13, 0, 0, 0);
            //     var year = bookingCreateDate.getFullYear();
            //     var month = ('0' + (bookingCreateDate.getMonth() + 1)).slice(-2);
            //     var day = ('0' + bookingCreateDate.getDate()).slice(-2);
            //     var hours = ('0' + bookingCreateDate.getHours()).slice(-2);
            //     var minutes = ('0' + bookingCreateDate.getMinutes()).slice(-2);
            //     var formattedDate = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
            //     $('input[name="quote_payment_date"]').val(formattedDate);
            //     $('#quote-payment-date').val(formattedDate);
            //     $('#quote-payment-date-new').val(formattedDate);
            //     $('input[name="quote_payment_date_full"]').val(formattedDate);
            //     $('#quote-payment-date-full').val(formattedDate);
            // }
            function calculatePaymentDateCondition() {
                // วันเดินทาง
                var travelDate = new Date($('#date-start').val());
                // วันจอง (หรือวันที่สร้าง booking)
                var bookingDate = new Date($('#booking-create-date').val());
                // วันปัจจุบัน
                var dateNow = new Date();
                // จำนวนวันห่างระหว่างวันเดินทางกับวันจอง
                var diffDays = (travelDate - bookingDate) / (1000 * 60 * 60 * 24);

                // ฟังก์ชันช่วย format date
                function formatDateTime(dateObj) {
                    var year = dateObj.getFullYear();
                    var month = ('0' + (dateObj.getMonth() + 1)).slice(-2);
                    var day = ('0' + dateObj.getDate()).slice(-2);
                    var hours = ('0' + dateObj.getHours()).slice(-2);
                    var minutes = ('0' + dateObj.getMinutes()).slice(-2);
                    return year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                }

                if (diffDays >= 31) {
                    // มัดจำ: วันจอง + 1 วัน
                    var depositDate = new Date(bookingDate);
                    depositDate.setDate(depositDate.getDate() + 1);
                    depositDate.setHours(13, 0, 0, 0);

                    // เต็มจำนวน: วันเดินทาง - 30 วัน
                    var fullPayDate = new Date(travelDate);
                    fullPayDate.setDate(fullPayDate.getDate() - 30);
                    fullPayDate.setHours(13, 0, 0, 0);

                    $('#quote-payment-deposit').prop('checked', true);
                    $('#quote-payment-price').val('5000');
                    $('input[name="quote_payment_date"]').val(formatDateTime(depositDate));
                    $('#quote-payment-date').val(formatDateTime(depositDate));
                    $('#quote-payment-date-new').val(formatDateTime(depositDate));
                    $('input[name="quote_payment_date_full"]').val(formatDateTime(fullPayDate));
                    $('#quote-payment-date-full').val(formatDateTime(fullPayDate));
                } else {
                    // เต็มจำนวน: วันจอง + 1 วัน
                    var fullPayDate = new Date(bookingDate);
                    fullPayDate.setDate(fullPayDate.getDate() + 1);
                    fullPayDate.setHours(13, 0, 0, 0);

                    $('#quote-payment-full').prop('checked', true);
                    $('input[name="quote_payment_date_full"]').val(formatDateTime(fullPayDate));
                    $('#quote-payment-date-full').val(formatDateTime(fullPayDate));
                    // clear deposit
                    $('input[name="quote_payment_date"]').val('');
                    $('#quote-payment-date').val('');
                    $('#quote-payment-date-new').val('');
                    $('#quote-payment-price').val('0');
                }
            }
            $('#date-start-display, #date-end-display, #numday').on('change.auto', function() {
                calculatePaymentDateCondition();
                calculatePaymentCondition(
                true); // ส่ง true เพื่อข้าม block เงื่อนไขใน calculatePaymentCondition
            });



            // ฟังก์ชันคำนวณเงื่อนไขการชำระเงิน (Deposit/Full) และข้อมูลค่าบริการ (pax, รวม, vat, discount, grand total)
            function calculatePaymentCondition(skipPaymentCondition = false) {
      
                // --- เงื่อนไขการชำระเงิน ---
                //    if (!skipPaymentCondition) {
                // var bookingCreateDate = new Date($('#date-start').val());
                // var travelDate = new Date($('#date-start').val());
                // var dateNow = new Date();
                // var bookingDate = new Date($('#booking-create-date').val());
                // var diffDays = (travelDate - bookingDate) / (1000 * 60 * 60 * 24);

                // if (diffDays >= 31) {
                //     bookingCreateDate.setDate(bookingCreateDate.getDate() - 30);
                //     $('#quote-payment-deposit').prop('checked', true);
                //     // set default deposit rate to 5000 when auto-select deposit
                //     $('#quote-payment-price').val('5000');
                // } else {
                //     bookingCreateDate = new Date();
                //     bookingCreateDate.setDate(dateNow.getDate() + 1);
                //     $('#quote-payment-full').prop('checked', true);
                // }
                // bookingCreateDate.setHours(13, 0, 0, 0);
                // var year = bookingCreateDate.getFullYear();
                // var month = ('0' + (bookingCreateDate.getMonth() + 1)).slice(-2);
                // var day = ('0' + bookingCreateDate.getDate()).slice(-2);
                // var hours = ('0' + bookingCreateDate.getHours()).slice(-2);
                // var minutes = ('0' + bookingCreateDate.getMinutes()).slice(-2);
                // var formattedDate = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                // $('input[name="quote_payment_date"]').val(formattedDate);
                // $('#quote-payment-date').val(formattedDate);
                // $('#quote-payment-date-new').val(formattedDate);
                // $('input[name="quote_payment_date_full"]').val(formattedDate);
                // $('#quote-payment-date-full').val(formattedDate);
                // }

                // --- คำนวณข้อมูลค่าบริการ ---
                var sumTotalNonVat = 0;
                var sumTotalVat = 0;
                var sumDiscount = 0;
                var sumPreVat = 0;
                var sumVat = 0;
                var sumIncludeVat = 0;
                var grandTotal = 0;
                var withholdingAmount = 0;
                var paxTotal = 0;
                var vatRate = 0.07;
                var withholdingRows = [];

                // ใช้ selector แบบเดียวกับ create-modern-full
                $('.item-row.table-income, #discount-list .item-row.table-discount').each(function() {
                    var $row = $(this);
                    var qty = parseFloat($row.find('input[name="quantity[]"]').val()) || 0;
                    var price = parseFloat($row.find('input[name="price_per_unit[]"]').val()) || 0;
                    var isDiscount = $row.hasClass('table-discount');
                    if (isDiscount) {
                        var total = qty * price;
                        $row.find('input[name="total_amount[]"]').val(total.toFixed(2));
                        sumDiscount += total;
                    } else {
                        var isVat = $row.find('select[name="vat_status[]"]').val() === 'vat';
                        var isPax = $row.find('select[name="product_id[]"] option:selected').data('pax') ===
                            'Y';
                        var isWithholding = $row.find('input.vat-3').is(':checked');
                        var rowTotal = qty * price;
                        if (isWithholding) {
                            var plus3 = rowTotal * 0.03;
                            $row.find('input[name="total_amount[]"]').val((rowTotal + plus3).toFixed(2));
                            rowTotal = rowTotal + plus3;
                        } else {
                            $row.find('input[name="total_amount[]"]').val(rowTotal.toFixed(2));
                        }
                        if (isVat) {
                            sumTotalVat += rowTotal;
                        } else {
                            sumTotalNonVat += rowTotal;
                        }
                        if (isPax) {
                            paxTotal += qty;
                        }
                    }
                });

                // --- VAT Calculation ---
                var vatType = $('input[name="vat_type"]:checked').val();
                var listVatTotal = sumTotalVat; // ใช้ยอดรวมเฉพาะแถว vat
                if (listVatTotal === 0) {
                    // ไม่มีรายการ vat เลย
                    sumPreVat = 0;
                    sumVat = 0;
                    sumIncludeVat = 0;
                    grandTotal = sumTotalNonVat - sumDiscount;
                } else {
                    if (vatType === 'include') {
                        // VAT รวมอยู่ในยอดแล้ว
                        var vatBase = listVatTotal - sumDiscount;
                        sumPreVat = vatBase * 100 / 107;
                        sumVat = sumPreVat * vatRate;
                        sumIncludeVat = sumPreVat + sumVat;
                        grandTotal = sumTotalNonVat + sumIncludeVat;
                    } else {
                        if (sumDiscount < listVatTotal) {
                            sumPreVat = listVatTotal - sumDiscount;
                            sumVat = sumPreVat * vatRate;
                            sumIncludeVat = sumPreVat + sumVat;
                            grandTotal = sumTotalNonVat + sumIncludeVat;
                        } else {
                            sumPreVat = 0;
                            sumVat = 0;
                            sumIncludeVat = 0;
                            grandTotal = sumTotalNonVat;
                        }
                    }
                }


                // withholding tax 3% รวมทุกแถวที่ติ๊ก (เฉพาะรายได้)
                withholdingAmount = 0;
                if ($('#withholding-tax').is(':checked')) {
                    var sumVatRows = 0;
                    $('.item-row.table-income').each(function() {
                        var $row = $(this);
                        var isVat = $row.find('select[name="vat_status[]"]').val() === 'vat';
                        var qty = parseFloat($row.find('input[name="quantity[]"]').val()) || 0;
                        var price = parseFloat($row.find('input[name="price_per_unit[]"]').val()) || 0;
                        var rowTotal = qty * price;
                        if (isVat) {
                            if (vatType === 'include') {
                                sumVatRows += rowTotal / (1 + vatRate);
                            } else {
                                sumVatRows += rowTotal;
                            }
                        }
                    });
                    withholdingAmount = sumPreVat * 0.03;
                }
                $('#withholding-amount').text(withholdingAmount.toFixed(2));

                // set ค่า summary
                $('#sum-total-nonvat').text(formatNumber(sumTotalNonVat.toFixed(2)));
                $('#sum-total-vat').text(formatNumber(sumTotalVat.toFixed(2)));
                $('#sum-discount').text(formatNumber(sumDiscount.toFixed(2)));
                $('#sum-pre-vat').text(formatNumber(sumPreVat.toFixed(2)));
                $('#vat-amount').text(formatNumber(sumVat.toFixed(2)));
                $('#sum-include-vat').text(formatNumber(sumIncludeVat.toFixed(2)));
                $('#grand-total').text(formatNumber(grandTotal.toFixed(2)));
                $('#withholding-amount').text(formatNumber(withholdingAmount.toFixed(2)));
                $('#pax').text('Pax: ' + paxTotal);
                $('#quote-pax-total').val(paxTotal);
                // hidden fields
                $('input[name="quote_vat_exempted_amount"]').val(sumTotalNonVat.toFixed(2));
                $('input[name="quote_pre_tax_amount"]').val(sumTotalVat.toFixed(2));
                $('input[name="quote_discount"]').val(sumDiscount.toFixed(2));
                $('input[name="quote_pre_vat_amount"]').val(sumPreVat.toFixed(2));
                $('input[name="quote_vat"]').val(sumVat.toFixed(2));
                $('input[name="quote_include_vat"]').val(sumIncludeVat.toFixed(2));
                $('input[name="quote_grand_total"]').val(grandTotal.toFixed(2));
                $('input[name="quote_withholding_tax"]').val(withholdingAmount.toFixed(2));

                // syncDepositAndFullPayment();
            }

            // trigger คำนวณค่าบริการทุกครั้งที่มีการเปลี่ยนแปลง
            $(document).on('input change', '.quantity, .price-per-unit, .vat-status, .vat-3, .expense-type',
                function() {
                    calculatePaymentCondition();
                    // calculatePaymentDateCondition();
                    syncDepositAndFullPayment();
                });


            // เพิ่มรายการบริการ (row)
            $('#add-row-service').on('click', function() {
                // สร้าง row ใหม่แบบ discount-row (ไม่ clone)
                var rowCount = $('#table-income > .row').length + 1;
                var rowId = 'service-row-' + Date.now();
                var rowHtml = `
                
           <div class="row table-income item-row align-items-center">
                <div class="col-md-1"><span class="row-number"></span></div>
                <div class="col-md-3">
                    <select name="product_id[]" class="form-select product-select select2" style="width: 100%;">
                        <option value="">--เลือกสินค้า--</option>
                        @forelse ($products as $product)
                            <option data-pax="{{ $product->product_pax }}" value="{{ $product->id }}">{{ $product->product_name }}{{ $product->product_pax === 'Y' ? '(Pax)' : '' }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
               
                <div class="col-md-1" style="display: none">
                    <select name="expense_type[]" class="form-select">
                        <option selected value="income"> รายได้ </option>
                    </select>
                </div>
                <div class="col-md-1">
                    <input type="checkbox" name="withholding_tax[]" class="vat-3" value="Y">
                </div>
                <div class="col-md-1 text-center">
                    <select name="vat_status[]" class="vat-status form-select" style="width: 180%;">
                        <option selected value="nonvat">nonVat</option>
                        <option value="vat">Vat</option>
                    </select>
                </div>
                <div class="col-md-1"><input type="number" name="quantity[]" class="quantity form-control text-end" step="1" value="1" style="width: 150%;"></div>
                <div class="col-md-2"><input type="number" name="price_per_unit[]" class="price-per-unit form-control text-end" step="0.01" value="0"></div>
                <div class="col-md-2"><input type="number" name="total_amount[]" class="total-amount form-control text-end" value="0" readonly></div>
                <div class="col-md-1 text-center">
                  <a href="#" class="remove-row-btn text-danger"><i
                                            class="fa fa-trash"></i></a>
                </div>
            </div>
         `;
                $('#table-income').append(rowHtml);
                // init select2 เฉพาะแถวใหม่ (dropdownParent ให้แสดงใน modal)
                var $select = $('#table-income .row:last .product-select.select2');
                $select.select2({
                    width: '100%',
                    dropdownParent: $select.closest('.modal-body')
                });
                updateRowNumbers();
                calculatePaymentCondition(); // เรียกคำนวณยอดทันทีหลังเพิ่ม row
                syncDepositAndFullPayment()
            });

            // ฟังก์ชันอัปเดตเลขลำดับ row
            function updateRowNumbers() {
                $('#table-income > .row').each(function(i) {
                    $(this).find('.row-number').text(i + 1);
                });
            }

            // ลบรายการบริการ
            $(document).on('click', '.remove-row-btn', function() {
                // ลบเฉพาะแถวบริการ (table-income) หรือ discount-row ที่อยู่ใกล้ที่สุด
                var $row = $(this).closest('.item-row.table-income, .discount-row');
                if ($row.siblings('.table-income').length > 0 || $row.hasClass('discount-row')) {
                    $row.remove();
                    updateRowNumbers();
                    updateDiscountRowNumbers && updateDiscountRowNumbers();
                    calculatePaymentCondition();
                    syncDepositAndFullPayment()
                }
            });

            // อัปเดตเลขลำดับครั้งแรก (กรณีมี row เดียว)
            updateRowNumbers();

            // เพิ่มส่วนลด
            $('#add-row-discount').on('click', function() {
                addDiscountRow();
                updateDiscountRowNumbers();
                calculatePaymentCondition();
                syncDepositAndFullPayment()
            });

            // เพิ่ม discount row แรกอัตโนมัติถ้ายังไม่มี (เหมือนต้นฉบับ)
            // ไม่ต้องเพิ่ม discount row แรกอัตโนมัติ ให้ discount-list ว่างไว้ก่อน

            // ฟังก์ชันเพิ่ม discount row
            function addDiscountRow(rowData) {
                var rowCount = $('.discount-row').length + 1;
                var rowId = 'discount-row-' + Date.now();
                var selectedProduct = rowData && rowData.product_id ? rowData.product_id : '';
                var qty = rowData && rowData.qty ? rowData.qty : 1;
                var price = rowData && rowData.price ? rowData.price : 0;
                var vat = rowData && rowData.vat ? rowData.vat : 'nonvat';
                var isWithholding = rowData && rowData.withholding_tax === 'Y' ? 'checked' : '';
                var total = qty * price;
                var rowHtml = `
         <div class="row item-row table-discount mb-1 align-items-center discount-row" data-row-id="${rowId}" style="background:#fffbe7;border-radius:8px;">
                <div class="col-md-1 text-center discount-row-number">${rowCount}</div>
                <div class="col-md-3">
                    <select name="product_id[]" class="form-select product-select select2" style="width: 100%;">
                        <option value="">--เลือกส่วนลด--</option>
                        @foreach ($productDiscount as $product)
                            <option value="{{ $product->id }}" {{ $row->product_id == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1" style="display: none">
                    <select name="expense_type[]" class="form-select">
                        <option value="discount" selected> ส่วนลด </option>
                    </select>
                </div>
                <div class="col-md-1 text-center">
                    <input type="hidden" name="withholding_tax[]" value="N">
                   
                </div>
                <div class="col-md-1 text-center">
                    <select name="vat_status[]" class="vat-status form-select" style="width: 180%;">
                        <option value="nonvat" ${vat==='nonvat'?'selected':''}>nonVat</option>
                        <option value="vat" ${vat==='vat'?'selected':''}>Vat</option>
                    </select>
                </div>
                <div class="col-md-1"><input type="number" name="quantity[]" class="quantity form-control text-end" step="1" value="${qty}" style="width: 150%;"></div>
                <div class="col-md-2"><input type="number" name="price_per_unit[]" class="price-per-unit form-control text-end" step="0.01" value="${price}"></div>
                <div class="col-md-2"><input type="number" name="total_amount[]" class="total-amount form-control text-end" value="${total.toFixed(2)}" readonly></div>
                <div class="col-md-1 text-center">
                   <a href="#" class="remove-row-btn text-danger remove-discount-row"><i
                                            class="fa fa-trash"></i></a>
                </div>
                </div>
            
        `;
                $('#discount-list').append(rowHtml);
                // init select2 เฉพาะแถวใหม่ (ใช้ element ที่ render จริง)
                var $select = $('#discount-list .discount-row:last .product-select.select2');
                $select.select2({
                    width: '100%',
                    dropdownParent: $select.closest('.modal-body')
                });
                if (selectedProduct) {
                    $select.val(selectedProduct).trigger('change');
                }
            }

            // ลบแถวส่วนลด
            $(document).on('click', '.remove-discount-row', function() {
                $(this).closest('.discount-row').remove();
                updateDiscountRowNumbers();
                calculatePaymentCondition();
                syncDepositAndFullPayment()
            });

            // อัปเดตเลขลำดับ discount row
            function updateDiscountRowNumbers() {
                $('#discount-list .discount-row-number').each(function(i) {
                    $(this).text(i + 1);
                });
            }

            // trigger คำนวณเมื่อเปลี่ยน discount row
            $(document).on('input change',
                '.discount-qty, .discount-price, .discount-vat, .discount-product-select',
                function() {
                    var $row = $(this).closest('.discount-row');
                    var qty = parseFloat($row.find('.discount-qty').val()) || 0;
                    var price = parseFloat($row.find('.discount-price').val()) || 0;
                    var total = qty * price;
                    $row.find('.discount-total').val(total.toFixed(2));
                    calculatePaymentCondition();
                });

            // --- Discount Product List (for select2 in discount row) ---
            var discountProducts = [
                @foreach ($productDiscount as $product)
                    {
                        id: '{{ $product->id }}',
                        text: @json($product->product_name),
                        vat: '{{ $product->vat_status }}',
                    },
                @endforeach
            ];
            // ป้องกัน submit form เมื่อกด Enter ในช่องค้นหาแพคเกจทัวร์ และเลือกผลลัพธ์แรกอัตโนมัติ (เหมือนต้นฉบับ)
            $('#tourSearch').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    var $first = $('#tourResults a.list-group-item-action').first();
                    if ($first.length) {
                        $first.trigger('click');
                    }
                }
            });

            // Autocomplete ค้นหาแพคเกจทัวร์ (logic เหมือนต้นฉบับ)
            $('#tourSearch').on('input', function(e) {
                var searchTerm = $(this).val();
                if (searchTerm.length >= 2) {
                    $.ajax({
                        url: '{{ route('api.tours') }}',
                        method: 'GET',
                        data: {
                            search: searchTerm
                        },
                        success: function(data) {
                            $('#tourResults').empty();
                            if (data.length > 0) {
                                // limit แค่ 5 รายการ
                                var limited = data.slice(0, 5);
                                $.each(limited, function(index, item) {
                                    $('#tourResults').append(
                                        `<a href="#" id="tour-select" class="list-group-item list-group-item-action" data-tour="${item.id}" data-numday="${item.num_day}" data-airline="${item.airline_id}" data-wholesale="${item.wholesale_id}" data-code="${item.code}" data-name1="${item.code} - ${item.name}" data-name="${item.code} - ${item.code1} - ${item.name}">${item.code} - ${item.code1} - ${item.name}</a>`
                                    );
                                });
                            }
                            // เพิ่มตัวเลือก "กำหนดเอง"
                            $('#tourResults').append(
                                `<a href="#" class="list-group-item list-group-item-action" data-name="${searchTerm}">กำหนดเอง</a>`
                            );
                        }
                    });
                } else {
                    $('#tourResults').empty();
                }
            });

            // ปุ่ม reset การค้นหาแพคเกจทัวร์
            $('#resetTourSearch').on('click', function() {
                $('#tourSearch').val('');
                $('#tourResults').empty();
                $('#tour-id').val('');
                $('#tourSearch1').val('');
                $('#tour-code').val('');
                // reset dropdowns ที่เกี่ยวข้อง (optional, ถ้าต้องการ)
                // $('#airline').val('').trigger('change');
                // $('#numday').val('');
                // $('#wholesale').val('').trigger('change');
                // $('#country').val('').trigger('change');
            });
            // เมื่อคลิกเลือกแพคเกจจากผลลัพธ์การค้นหา
            $(document).on('click', '#tourResults a', function(e) {
                e.preventDefault();
                var selectedCode = $(this).data('code') || '';
                var selectedText = $(this).data('name');
                var selectedText1 = $(this).data('name1');
                var selectedAirline = $(this).data('airline');
                var selectedNumday = $(this).data('numday');
                var selectedTour = $(this).data('tour');
                $('#tour-id').val(selectedTour);
                $('#tourSearch').val(selectedText);
                $('#tourSearch1').val(selectedText1);
                $('#tour-code').val(selectedCode);
                $('#tourResults').empty();
                // set airline
                $('#airline').val(selectedAirline).change();
                // set numday
                $('#numday option').each(function() {
                    var optionText = $.trim($(this).text());
                    if (optionText === $.trim(selectedNumday)) {
                        $(this).prop('selected', true);
                        return false;
                    }
                });
                // set wholesale
                var selectedWholesale = $(this).data('wholesale');
                if (selectedWholesale) {
                    $.ajax({
                        url: '{{ route('api.wholesale') }}',
                        method: 'GET',
                        data: {
                            search: selectedWholesale
                        },
                        success: function(data) {
                            if (data) {
                                if (!$('#wholesale option[value="' + data.id + '"]').length) {
                                    $('#wholesale').append(
                                        `<option value="${data.id}">${data.wholesale_name_th}</option>`
                                    );
                                }
                                $('#wholesale').val(data.id).trigger('change');
                            }
                        }
                    });
                }
                // set country
                if (selectedCode) {
                    $.ajax({
                        url: '{{ route('api.country') }}',
                        method: 'GET',
                        data: {
                            search: selectedCode
                        },
                        success: function(data) {
                            if (data) {
                                if (!$('#country option[value="' + data.id + '"]').length) {
                                    $('#country').append(
                                        `<option value="${data.id}">${data.country_name_th}</option>`
                                    );
                                }
                                $('#country').val(data.id).trigger('change');
                            }
                        }
                    });
                }
                // เรียก AJAX ดึงช่วงวันเดินทาง (period) หลังเลือกทัวร์
                if (selectedTour) {
                    $.ajax({
                        url: '{{ route('api.period') }}',
                        method: 'GET',
                        data: {
                            search: selectedTour
                        },
                        success: function(data) {
                            $('#date-list').empty();
                            var now = new Date();
                            if (Array.isArray(data) && data.length > 0) {
                                $.each(data, function(index, period) {
                                    var dateObject = new Date(period.start_date);
                                    // เฉพาะวันที่มากกว่าหรือเท่ากับวันนี้
                                    if (dateObject > now) {
                                        var dateText = dateObject.toLocaleDateString(
                                            'th-TH', {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric'
                                            });
                                        $('#date-list').append(`
                                    <a href="#" class="list-group-item period-select" data-period1="${period.price1}" data-period2="${period.price2}" data-period3="${period.price3}" data-period4="${period.price4}" data-date="${period.start_date}">${dateText}</a>
                                `);
                                    }
                                });
                            }
                            // ไม่ต้องแสดงปุ่ม/ข้อความ "ระบุวันเดินทางเอง" อีกต่อไป
                        }
                    });
                }
            });

            // เมื่อคลิกเลือกวันที่จาก list
            $(document).on('click', '.period-select', function(e) {
                e.preventDefault();
                var selectedDate = $(this).data('date');
                var period1 = $(this).data('period1');
                var period2 = $(this).data('period2');
                var period3 = $(this).data('period3');
                var period4 = $(this).data('period4');
                $('#period1').val(period1);
                $('#period2').val(period2);
                $('#period3').val(period3);
                $('#period4').val(period4);
                var dateObject = new Date(selectedDate);
                var thaiFormattedDate = dateObject.toLocaleDateString('th-TH', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                $('#date-start-display').val(selectedDate);
                $('#date-start').val(selectedDate);
                $('#date-list').empty();
                var numDays = parseInt($('#numday option:selected').data('day')) || 0;
                if (numDays > 0 && selectedDate) {
                    var start = new Date(selectedDate);
                    var endDate = new Date(start);
                    endDate.setDate(start.getDate() + numDays - 1);
                    var thaiFormattedEndDate = endDate.toLocaleDateString('th-TH', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    $('#date-end-display').val(endDate.toISOString().slice(0, 10));
                    $('#date-end').val(endDate.toISOString().slice(0, 10));
                }
                // เรียกฟังก์ชันคำนวณวันครบกำหนดชำระเงิน
                calculatePaymentDateCondition();
                // เรียกฟังก์ชันคำนวณยอดอื่นๆ
                calculatePaymentCondition();
            });

            // ลบ logic/handler สำหรับปุ่มหรือข้อความ "ระบุวันเดินทางเอง" (ไม่ต้องมีอีกต่อไป)
            // ปิดผลลัพธ์เมื่อคลิกนอก
            $(document).on('click', function(event) {
                if (!$(event.target).closest('#tourResults, #tourSearch').length) {
                    $('#tourResults').empty();
                }
            });


            // เรียกคำนวณยอดและข้อมูลค่าบริการทันทีเมื่อโหลดหน้า modal
            calculatePaymentCondition(true);
        });
    </script>


</div>
