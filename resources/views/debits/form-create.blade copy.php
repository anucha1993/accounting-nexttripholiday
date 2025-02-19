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
            <h4 class="mb-0">สร้างใบลดหนี้</h4>

        </div>
        <hr>



        <!-- ตาราง -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light text-center">
                    <tr>
                        <th scope="col">ลำดับ</th>
                        <th scope="col">รายการ</th>
                        <th scope="col">จำนวน</th>
                        <th scope="col">ราคาต่อหน่วย</th>
                        <th scope="col">รวมทั้งสิ้น</th>
                        <th scope="col">ลบ</th>
                    </tr>
                </thead>
                <tbody id="dynamic-rows">
                    <tr>
                        <td class="text-center">1</td>
                        <td>
                            {{-- <input type="text" class="form-control" name="income_type[]" value="ค่าบริการ"> --}}
                            <select name="income_type[]" class="select2 product-select" style="width: 100%">
                                @forelse ($products as $item)
                                    <option value="{{ $item->id }}">{{ $item->product_name }}</option>
                                @empty
                                @endforelse
                            </select>

                        </td>
                        <td><input type="number" class="form-control tax-rate" name="tax_rate[]" value="2"></td>
                        <td><input type="number" class="form-control amount" name="amount[]" value="50000" step="0.01">
                        </td>
                        <td><input type="number" class="form-control withholding-tax" name="withholding_tax[]"
                                step="0.01" value="1000" readonly></td>
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
            </div>

            <div class="col-md-6">
                <div class="d-flex justify-content-between">
                    <span><strong>มูลค่าสินค้าหรือบริการตามใบกำกับภาษีเดิม</strong></span>
                    <span id="total-amount-old">0.00</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span><strong>มูลค่าสินค้าหรือบริการที่าถูกต้อง</strong></span>
                    <span id="total-amount-new">0.00</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span><strong>ผลต่าง</strong></span>
                    <span id="total-difference">0.00</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span><strong>จำนวนภาษีมูลค่าเพิ่ม</strong></span>
                    <span id="total-vat">0.00</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span><strong>จำนวนเงินรวมทั้งสิ้น</strong></span>
                    <span id="total-vat">0.00</span>
                </div>


                {{-- <div class="d-flex justify-content-between">
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
                </div> --}}
            </div>
        </div>
        <br>


        <!-- ปุ่มบันทึก -->
        <div class="text-end">
            <button type="submit" form="submit-form" class="btn btn-success">บันทึกเอกสาร</button>
            <button type="button" class="btn btn-secondary">ปิดหน้าต่าง</button>
        </div>
    </div>
    </form>
    <br>
    <br>




    <div>



        <script>
            const API_URL = '{{ route('apicustomer.store') }}';

            // Create Customer name
            $('#create-form-customer').submit(function(e) {
                e.preventDefault();
                const customer_name = $('#customer_name').val();
                const customer_texid = $('#customer_texid').val();
                const customer_email = $('#customer_email').val();
                const customer_tel = $('#customer_tel').val();
                const customer_fax = $('#customer_fax').val();
                const customer_social_id = $('#customer_social_id').val();
                const customer_campaign_source = $('#customer_campaign_source').val();
                const customer_address = $('#customer_address').val();
                $.post(API_URL, {
                    customer_name,
                    customer_texid,
                    customer_email,
                    customer_tel,
                    customer_fax,
                    customer_campaign_source,
                    customer_social_id,
                    customer_address
                }, function() {
                    location.reload();
                });

            });




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
                // เช็คเมื่อโหลดหน้าแล้วให้ดึงข้อมูลจากค่าเริ่มต้นใน <select>
                var selectedOption = $('#payerName').find(':selected');
                var address = selectedOption.data('address'); // ดึงค่า data-address
                var taxId = selectedOption.data('taxid'); // ดึงค่า data-taxid

                // ถ้ามีค่า ให้แสดงในฟิลด์
                $('#customerAddress').val(address); // แสดงที่ input address
                $('#customerTaxId').val(taxId); // แสดงที่ input tax ID

                // เพิ่มการทำงานเมื่อมีการเปลี่ยนแปลงใน <select>
                $('#payerName').on('change', function(e) {
                    var customerId = $(this).val();
                    var selectedOption = $(this).find(':selected');
                    var address = selectedOption.data('address'); // ดึงค่า data-address
                    var taxId = selectedOption.data('taxid'); // ดึงค่า data-taxid

                    // แสดงข้อมูลในฟิลด์
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
                        const withholdingTax = (amount * taxRate);

                        $(this).find('.withholding-tax').val(withholdingTax.toFixed(2));

                        totalAmount += amount;
                        totalWithholdingTax += withholdingTax;
                    });
                    const totalPayable = totalAmount + totalWithholdingTax;

                    $('#total-difference').text(totalWithholdingTax.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#total-withholding-tax').text(totalWithholdingTax.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#total-payable').text(totalPayable.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                }
                // Initialize Select2 สำหรับทุก select element ที่มี class .product-select
                function initializeSelect2() {
                    $('#dynamic-rows .product-select').each(function() {
                        if (!$(this).hasClass("select2-hidden-accessible")) {
                            $(this).select2({
                                width: 'resolve' // ตั้งค่า width ให้กับ select2
                            });
                        }
                    });
                };

                $('#add-row').click(function() {
                    const newRow = `
            <tr>
                <td class="text-center"></td>
                <td>
                     <select  name="income_type[]"  class="select2 product-select" style="width: 100%">
                                @forelse ($products as $item)
                                    <option value="{{ $item->id }}">{{ $item->product_name }}</option>
                                @empty
                                @endforelse
                                </select>
                </td>
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
                    initializeSelect2();
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
