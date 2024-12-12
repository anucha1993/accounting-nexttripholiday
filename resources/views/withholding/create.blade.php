@extends('layouts.template')

@section('content')
    <style>
        /* ปรับสไตล์ของส่วนหัว */
        .card-header {
            background-color: #f8f9fa;
            /* สีพื้นหลังอ่อน */
            border-bottom: 2px solid #e7e7e7;
            /* เส้นขอบ */
            font-weight: bold;
            font-size: 18px;
        }

        /* ปรับฟอร์มให้ขอบมนและแสดงชัดเจน */
        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 5px 5px;
        }

        /* สไตล์ของตาราง */
        .table {
            border-collapse: collapse;
            background-color: white;
            border: 1px solid #e7e7e7;
        }

        .table th {
            background-color: #f2f8fc;
            /* สีพื้นหลังหัวตาราง */
            color: #333;
            text-align: center;
        }

        .table td {
            vertical-align: middle;
            padding: 8px;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #e7e7e7;
            /* เส้นขอบ */
        }

        /* ปรับปุ่ม */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: bold;
            border-radius: 6px;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            border-radius: 6px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            font-weight: bold;
            border-radius: 6px;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            border-radius: 6px;
        }

        /* เพิ่มการแสดงผลตัวเลขและยอดรวม */
        .summary-section span {
            font-size: 16px;
            font-weight: bold;
        }

        /* เพิ่มระยะห่าง */
        .container {
            margin-top: 20px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        /* เพิ่มปุ่มด้านขวา */
        .text-end .btn {
            margin-right: 10px;
        }

        /* แถวของตารางเมื่อชี้เมาส์ */
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
    </style>
    <div class="container py-4 email-app todo-box-container container-fluid" style="background-color: #ffffff">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">แก้ไขใบหัก ณ ที่จ่าย</h4>
            {{-- <div>
                <button class="btn btn-outline-primary me-2">พิมพ์เอกสาร</button>
                <button class="btn btn-outline-secondary me-2">ดาวน์โหลด</button>
                <button class="btn btn-outline-danger">คัดลอกเอกสาร</button>
            </div> --}}
        </div>
        <hr>

        <form action="{{ route('withholding.store') }}" method="post">
            @csrf
            @method('post')
            <!-- ส่วนข้อมูลผู้จ่าย -->
            <div class="row mb-2 ">
                <div class="col-md-6">
                    <label for="payerName" class="form-label">ชื่อผู้จ่ายเงิน</label>
                    <select class="form-select" id="payerName" name="customer_id">
                        <option value="" disabled selected>เลือกผู้จ่ายเงิน</option>
                        @forelse ($customers as $item)
                            <option data-address="{{ $item->customer_address }}" data-taxid="{{ $item->customer_texid }}"
                                value="{{ $item->customer_id }}">{{ $item->customer_name }}</option>
                        @empty
                            <option value="">ไม่มีข้อมูลลูกค้า</option>
                        @endforelse
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="documentDate" class="form-label">วันที่</label>
                    <input type="date" class="form-control" id="documentDate" name="document_date"
                        value="{{ date('Y-m-d') }}">

                </div>

                <div class="col-md-6">

                    <label for="customerAddress" class="form-label">รายละเอียด</label>
                    <textarea class="form-control" id="customerAddress" name="details" rows="3"></textarea>
                </div>

                <div class="col-md-6">
                    <label for="documentNumber" class="form-label">เลขที่เอกสาร</label>
                    <input type="text" class="form-control" id="documentNumber" name="ref_number"
                        placeholder="ค้นหาเลขที่เอกสาร">
                    <div id="documentSuggestions" class="list-group position-absolute w-50"
                        style="z-index: 1000; display: none; background-color: #FFFF"></div>
                </div>

            </div>

            <!-- ที่อยู่ และ เลขประจำตัวผู้เสียภาษี -->
            <div class="row mb-2">
                <div class="col-md-6">
                    <label for="customerTaxId" class="form-label">เลขประจำตัวผู้เสียภาษี</label>
                    <input type="text" class="form-control" id="customerTaxId" placeholder="1234567890123">
                </div>
                <div class="col-md-6">
                    <label for="withholdingForm" class="form-label">แบบฟอร์ม</label>
                    <select id="withholdingForm" name="withholding_form" class="form-select">
                        <option value="ภ.ง.ด.53">ภ.ง.ด.53</option>
                        <option value="ภ.ง.ด.3">ภ.ง.ด.3</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="">สำนักงาน/สาขาเลขที่</label>
                    <input type="text" name="withholding_branch" class="form-control"  placeholder="สำนักงาน/สาขาเลขที่">
                </div>
              </div>

            <!-- ตาราง -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-light text-center">
                        <tr>
                            <th scope="col">ลำดับ</th>
                            <th scope="col">ประเภทเงินได้</th>
                            <th scope="col">อัตราภาษีที่หัก (%)</th>
                            <th scope="col">จำนวนเงิน</th>
                            <th scope="col">ภาษีหัก ณ ที่จ่าย</th>
                            <th scope="col">ลบ</th>
                        </tr>
                    </thead>
                    <tbody id="dynamic-rows">
                        <tr>
                            <td class="text-center">1</td>
                            <td><input type="text" class="form-control" name="income_type[]" value="ค่าบริการ"></td>
                            <td><input type="number" class="form-control tax-rate" name="tax_rate[]" value="2"></td>
                            <td><input type="number" class="form-control amount" name="amount[]" value="50000"></td>
                            <td><input type="number" class="form-control withholding-tax" name="withholding_tax[]"
                                    value="1000" readonly></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger remove-row">ลบ</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button id="add-row" type="button" class="btn btn-primary mt-2">+ เพิ่มแถวรายการ</button>
            </div>

            <!-- สรุปยอด -->
            <div class="row mb-2">
                <div class="col-md-6">
                    <label for="">ลายเซ็นอิเล็กทรอนิกส์ และตรายาง</label>
                    <select name="image_signature_name" class="form-select">
                        @forelse ($imageSingture as $singture)
                            <option value="{{$singture->image_signture_id}}">{{$singture->image_signture_name}}</option>
                        @empty
                        @endforelse
                    </select>
                    <br>
                    <label for="">บันทึกเพิ่มเติม</label>
                    <textarea name="withholding_note" class="form-control" cols="30" rows="2" placeholder="บันทึกเพิ่มเติม"></textarea>
                   </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-between">
                        <span><strong>จำนวนเงินรวม (ไม่รวมภาษี):</strong></span>
                        <span id="total-amount">50,000.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>ภาษีมูลค่าเพิ่ม 7%:</strong></span>
                        <span id="vat-amount">3,500.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>ภาษีที่หัก:</strong></span>
                        <span id="total-withholding-tax">1,000.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>ยอดชำระ:</strong></span>
                        <span id="total-payable">52,500.00</span>
                    </div>
                </div>
            </div>
            

            <!-- ปุ่มบันทึก -->
            <div class="text-end">
                <button type="submit" class="btn btn-success">บันทึกเอกสาร</button>
                <button type="button" class="btn btn-secondary">ปิดหน้าต่าง</button>
            </div>
    </div>
    </form>
    <br>
    <br>






    <script>
        $(document).ready(function() {
            $('#documentNumber').on('keydown', function(e) {
                if (e.keyCode === 13) { // ตรวจสอบว่าคีย์ที่กดคือ Enter
                    e.preventDefault(); // ยกเลิกการทำงานเริ่มต้น (prevent form submit)
                }
            });
            $('#documentNumber').on('keyup', function() {
                const query = $(this).val();

                if (query.length > 1) { // เริ่มค้นหาหลังจากพิมพ์ 2 ตัวอักษร
                    $.ajax({
                        url: "{{ route('withholding.taxNumber') }}",
                        method: "GET",
                        data: {
                            query: query
                        },
                        success: function(data) {
                            let suggestions = '';
                            data.forEach(function(item) {
                                suggestions +=
                                    `<a href="#" class="list-group-item list-group-item-action select-document" data-id="${item.taxinvoice_id}" data-tax-number="${item.taxinvoice_number}">${item.taxinvoice_number}</a>`;
                            });

                            $('#documentSuggestions').html(suggestions).fadeIn();
                        }
                    });
                } else {
                    $('#documentSuggestions').fadeOut();
                }
            });

            // เลือกเลขที่เอกสารจากผลลัพธ์
            $(document).on('click', '.select-document', function(e) {
                e.preventDefault();
                const taxNumber = $(this).data('tax-number');
                $('#documentNumber').val(taxNumber); // กำหนดค่าใน Input
                $('#documentSuggestions').fadeOut(); // ปิดรายการแนะนำ
            });

            // คลิกที่อื่นเพื่อปิดรายการแนะนำ
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#documentNumber, #documentSuggestions').length) {
                    $('#documentSuggestions').fadeOut();
                }
            });
        });


        $(document).ready(function() {
            $('#payerName').on('change', function(e) {

                var customerId = $(this).val();
                var selectedOption = $(this).find(':selected');
                var address = selectedOption.data('address'); // ดึงค่า data-address
                var taxId = selectedOption.data('taxid'); // ดึงค่า data-taxid

                // แสดงข้อมูลใน Alert (หรือจะนำไปแสดงในฟิลด์ก็ได้)
                //  alert("Customer ID: " + customerId + "\nAddress: " + address + "\nTax ID: " + taxId);

                // ตัวอย่าง: การนำข้อมูลไปแสดงในฟิลด์
                $('#customerAddress').val(address); // แสดงที่ input address
                $('#customerTaxId').val(taxId); // แสดงที่ input tax ID
            });
        });

        $(document).ready(function() {
            function recalculate() {
                let totalAmount = 0;
                let totalWithholdingTax = 0;

                $('#dynamic-rows tr').each(function() {
                    const amount = parseFloat($(this).find('.amount').val()) || 0;
                    const taxRate = parseFloat($(this).find('.tax-rate').val()) || 0;
                    const withholdingTax = (amount * taxRate) / 100;

                    $(this).find('.withholding-tax').val(withholdingTax.toFixed(2));

                    totalAmount += amount;
                    totalWithholdingTax += withholdingTax;
                });

                const totalPayable = totalAmount - totalWithholdingTax;

                $('#total-amount').text(totalAmount.toLocaleString('en-US', {
                    minimumFractionDigits: 2
                }));
                $('#total-withholding-tax').text(totalWithholdingTax.toLocaleString('en-US', {
                    minimumFractionDigits: 2
                }));
                $('#total-payable').text(totalPayable.toLocaleString('en-US', {
                    minimumFractionDigits: 2
                }));
            }


            $('#add-row').click(function() {
                const newRow = `
            <tr>
                <td class="text-center"></td>
                <td><input type="text" class="form-control" name="income_type[]"></td>
                <td><input type="number" class="form-control tax-rate" name="tax_rate[]" value="0"></td>
                <td><input type="number" class="form-control amount" name="amount[]" value="0"></td>
                <td><input type="number" class="form-control withholding-tax" name="withholding_tax[]" value="0" readonly></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger remove-row">ลบ</button>
                </td>
            </tr>
        `;
                $('#dynamic-rows').append(newRow);
                updateRowNumbers();
            });

            $(document).on('input', '.amount, .tax-rate', function() {
                recalculate();
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateRowNumbers();
                recalculate();
            });

            function updateRowNumbers() {
                $('#dynamic-rows tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }

            // Initial calculation
            recalculate();
        });
    </script>
@endsection
