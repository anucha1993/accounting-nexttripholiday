<!-- resources/views/withholding-tax.blade.php -->
@extends('layouts.template')

@section('content')
    <div class="card">
        <br>
        <div class="container py-4" style="background-color: rgba(196, 196, 196, 0.171)">
            <h4 class="text-center mb-2">แก้ไขใบหัก ณ ที่จ่าย</h4>
            <hr>
            <!-- ส่วนข้อมูลผู้จ่าย -->
            <div class="row mb-2">
                <div class="col-md-6">
                    <label for="payerName" class="form-label">ชื่อผู้จ่ายเงิน</label>
                    <select class="form-select" id="payerName" name="payer_name">
                        @forelse ($customers as $item)
                            <option data-address="{{ $item->customer_address }}" data-taxid="{{ $item->customer_texid }}"
                                value="{{ $item->id }}">{{ $item->customer_name }}</option>
                        @empty
                        @endforelse

                    </select>
                </div>
                <div class="col-md-6">
                    <label for="documentNumber" class="form-label">เลขที่เอกสาร</label>
                    <input type="text" class="form-control" id="documentNumber" placeholder="ค้นหาเลขที่เอกสาร">
                    <div id="documentSuggestions" class="list-group position-absolute w-100"
                        style="z-index: 1000; display: none;"></div>
                </div>


            </div>

            <!-- วันที่ และ เลขที่เอกสาร -->
            <div class="row mb-2">
                <div class="col-md-6">
                    <label for="details" class="form-label">ที่อยู่</label>
                    <textarea class="form-control" id="customerAddress" name="details" rows="3" placeholder="141/12 ชั้น 11..."></textarea>
                </div>
                <div class="col-md-6">
                    <label for="date" class="form-label">วันที่</label>
                    <input type="date" class="form-control" id="date" value="{{ date('Y-m-d') }}">
                </div>

            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="taxod" class="form-label">เลขประจำตัวผู้เสียภาษี</label>
                    <input type="text" class="form-control" id="customerTaxId" name="customer_taxid"
                        placeholder="1234...">
                </div>
                <div class="col-md-6">
                    <label for="form" class="form-label">แบบฟอร์ม</label>
                    <select name="withholding_form" class="form-select">
                        <option value="ภ.ง.ด.53">ภ.ง.ด.53</option>
                        <option value="ภ.ง.ด.3">ภ.ง.ด.3</option>
                    </select>
                </div>
                {{-- <div class="col-md-6">
                    <label for="date" class="form-label">วันที่</label>
                    <input type="date" class="form-control" id="date" value="2018-01-06">
                </div> --}}
            </div>


            <!-- ตาราง -->
            <div class="mb-2">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center">ลำดับ</th>
                            <th scope="col">ประเภทเงินได้</th>
                            <th scope="col">อัตราภาษีที่หัก (%)</th>
                            <th scope="col">จำนวนเงิน</th>
                            <th scope="col">ภาษีหัก ณ ที่จ่าย</th>
                            <th scope="col" class="text-center">ลบ</th>
                        </tr>
                    </thead>
                    <tbody id="dynamic-rows">
                        <tr>
                            <td class="text-center">1</td>
                            <td><input type="text" class="form-control" name="income_type[]" value="ค่าบริการจองทัวร์"></td>
                            <td><input type="number" class="form-control tax-rate" name="tax_rate[]" value="1"></td>
                            <td><input type="number" class="form-control amount" name="amount[]" value="0.00"></td>
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
            <div class="mb-2 text-end">
                <div class="row ">
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                        <strong>จำนวนเงินรวม (ไม่รวมภาษี):</strong>
                    </div>
                    <div class="col-md-2" id="total-amount">0.00</div>
                </div>
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                        <strong>ภาษีที่หักรวม:</strong>
                    </div>
                    <div class="col-md-2 " id="total-withholding-tax">0.00<</div>
                </div>
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                        <strong>ยอดชำระ:</strong>
                    </div>
                    <div class="col-md-2" id="total-payable">0.00<</div>
                </div>
            </div>

            <!-- ปุ่มบันทึก -->
            <div class="text-end">
                <button type="button" class="btn btn-success me-2">บันทึกเอกสาร</button>
                <button type="button" class="btn btn-secondary">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
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
            $('#payerName').on('change', function() {
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
