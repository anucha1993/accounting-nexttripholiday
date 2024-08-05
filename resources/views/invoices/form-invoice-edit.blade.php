ไม่ได้ผล คุณต้องแก้ไข code นี้ทั้งหมดให้ถูกต้อง <div class="row">
    <div class="col-md-8 border" style="padding: 10px">
        <div class="row">
            <div class="col-md-12">
                <b>Customer ID :</b> <span style="margin: 20px;">15215498748</span> <a
                    href="#">แก้ไขข้อมูลลูกค้า</a></br>
                <b>Name : </b> <span style="margin: 60px;"> Anucha Yothanan</span></br>
                <b>Address : </b> <span style="margin: 45px;"> เลขที่ 1525 ซอยลาดพร้าว 94(ปัญจมิตร) แขวงพลับพลา
                    เขตวังทองหลาง กรุงเทพมหานคร 10310</span></br>
                <b>Moblie : </b> <span style="margin: 55px;"> 066-095-2919</span></br>
                <b>Fax : </b> <span style="margin: 75px;"> -</span></br>
                <b>Email : </b> <span style="margin: 60px;"> ap.anucha@hotmail.com</span></br>
            </div>

        </div>


    </div>

    <div class="col-md-4 border" style="padding-top: 10px">
        <div class="row">
            <div class="col-md-12">
                <b>Date :</b> <span style="margin: 50px;"> 31-July-24</span></br>
                <b>Booking No :</b> <span style="margin: 5px;"> BO20242969</span></br>
                <b>Sale :</b> <span style="margin: 53px;"> อนุชา โยธานันท์</span></br>
                <b>Email :</b> <span style="margin: 45px;"> ap.anucha@hotmail.com</span></br>
                <b>Tour Code :</b> <span style="margin: 15px;">21541247</span></br>
                <b>Airline :</b> <span style="margin: 40px;"> AirThai</span></br>
            </div>
        </div>
    </div>

    <form id="invoice-form" method="post">
        @csrf

        <br>
        <div class="col-md-12">
            <table class="table table-bordered" id="table-product">
                <thead>
                    <tr class="bg-info text-white">
                        <td style="width: 10px">ลำดับ</td>
                        <td class="text-center" style="width: 650px">รายละเอียด/รายการ</td>
                        <td class="text-center">ประเภทค่าใช้จ่าย</td>
                        <td class="text-center" style="width: 100px">จำนวน</td>
                        <td class="text-center" style="width: 200px">ราคา/หน่วย</td>
                        <td class="text-center" style="width: 200px">ราคารวม</td>
                        <td class="text-center" style="width: 100px">ลบ</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoiceProduct as $key => $item)
                        <tr id="productList" class="mt-0">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <select name="product_id[]" class="form-select">
                                    <option value="{{ $item->product_id }}" selected>{{ $item->product_name }}</option>
                                    @foreach ($productIncome as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="expense[]" class="form-select">
                                    <option value="expense" {{ $item->expense_type == 'expense' ? 'selected' : '' }}>
                                        รายรับ</option>
                                    <option value="discount" {{ $item->expense_type == 'discount' ? 'selected' : '' }}>
                                        ส่วนลด</option>
                                </select>
                            </td>
                            <td><input name="product_qty[]" type="number" value="{{ $item->invoice_qty }}"
                                    class="form-control text-end"></td>
                            <td><input name="product_price[]" type="number" value="{{ $item->invoice_price }}"
                                    class="form-control text-end"></td>
                            <td><input name="product_sum[]" type="number" value="{{ $item->invoice_sum }}"
                                    class="form-control text-end" readonly></td>
                            <td class="text-center"><a href="javascript:void(0)" class="text-danger removeRow"> <i
                                        class="fa fa-trash"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No data</td>
                        </tr>
                    @endforelse
                    <tr id="productAdd" style="visibility: hidden; position: absolute;" class="mt-0">
                        <td></td>
                        <td>
                            <select name="product_id[]" class="form-select">
                                <option value="">เลือกสินค้า</option>
                                @foreach ($productIncome as $product)
                                    <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="expense[]" class="form-select">
                                <option value="expense">รายรับ</option>
                                <option value="discount">ส่วนลด</option>
                            </select>
                        </td>
                        <td><input name="product_qty[]" type="number" class="form-control text-end"></td>
                        <td><input name="product_price[]" type="number" class="form-control text-end"></td>
                        <td><input name="product_sum[]" type="number" class="form-control text-end" readonly></td>
                        <td class="text-center"><a href="javascript:void(0)" class="text-danger removeRow"> <i
                                    class="fa fa-trash"></i></a></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="6">
                            <a href="javascript:void(0)" id="addRow" class="btn btn-info btn-sm">เพิ่มรายการ</a>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
        <!-- รวมทั้งหมด -->

        <div class="row">
            <div class="col-md-6">
                <div class="col-md-12">
                    <input type="checkbox" id="vat3" value="vat3">
                    <label for="vat3">คิดภาษีหัก ณ ที่จ่าย 3% (คำนวณจากยอดราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT
                        Amount)</label>
                </div>
                <hr>
                <div class="col-md-12">
                    <label class="text-danger">หมายเหตุ : </label>
                    <textarea name="invoice_note" id="" cols="30" rows="3" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">

                    <div class="col-md-8 text-end">
                        <label class="text-info">รวมเป็นเงิน :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <label id="totalSum">0.00</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 text-end">
                        <label class="text-info">ส่วนลด :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <label id="discount">0.00</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 text-end">
                        <label class="text-info">ราคาหลังหักส่วนลด :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <label id="totalAfterDiscount">0.00</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 text-end">
                        <label class="text-info">คำนวน Vat 7% :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <label id="vatAmount">0.00</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 text-end">
                        <label class="text-info">หัก ณ ที่จ่าย 3% :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <label id="withholdingTax">0.00</label>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-8 text-end">
                        <label class="text-info">จำนวนเงินรวมทั้งสิ้น :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <label id="grandTotal">0.00</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 text-end">
                        <label class="text-info">คำนวน Vat :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <select name="vat" id="vat" class="form-select">
                            <option value="in">Vat Include</option>
                            <option value="out">Vat Exclude</option>
                        </select>
                    </div>
                </div>

            </div>


        </div>

        <input type="hidden" name="invoice_total" id="invoiceTotal">
        <input type="hidden" name="invoice_discount" id="invoiceDiscount">

        <input type="hidden" name="invoice_vat_7" id="invoiceVat7">
        <input type="hidden" name="invoice_grand_total" id="invoiceGrandTotal">
        <input type="hidden" name="invoice_vat_3" id="invoiceVat3">
        <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $invoices->invoice_id }}">

        <button id="submit" class="btn btn-success btn-sm">บันทึก</button>


    </form>



    <script>
        $(document).ready(function() {
            // ฟังก์ชันคำนวณราคารวม
            function calculateRow(row) {
                var qty = parseFloat(row.find('input[name="product_qty[]"]').val()) || 0;
                var price = parseFloat(row.find('input[name="product_price[]"]').val()) || 0;
                var sum = (qty * price).toFixed(2);
                row.find('input[name="product_sum[]"]').val(sum);
            }

            // ฟังก์ชันจัดรูปแบบตัวเลข
            function formatNumber(num) {
                return parseFloat(num).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            // คำนวณค่าทั้งหมด
            function calculateTotals() {
                var totalSum = 0;
                var totalDiscount = 0;

                // คำนวณยอดรวมและส่วนลด
                $('input[name="product_sum[]"]').each(function() {
                    var expenseType = $(this).closest('tr').find('select[name="expense[]"]').val();
                    var sum = parseFloat($(this).val()) || 0;

                    if (expenseType === 'expense') {
                        totalSum += sum;
                    } else if (expenseType === 'discount') {
                        totalDiscount += sum;
                    }
                });

                $('#totalSum').text(formatNumber(totalSum));
                $('#invoiceTotal').val(formatNumber(totalSum));
                $('#discount').text(formatNumber(totalDiscount));
                $('#invoiceDiscount').val(formatNumber(totalDiscount));

                var totalAfterDiscount = totalSum - totalDiscount;
                $('#totalAfterDiscount').text(formatNumber(totalAfterDiscount));

                var vatType = $('#vat').val();
                var vatAmount = 0;

                if (vatType === 'in') {
                    vatAmount = (totalAfterDiscount * 0.07 / 1.07).toFixed(2); // VAT Inclusive
                } else if (vatType === 'out') {
                    vatAmount = (totalAfterDiscount * 0.07).toFixed(2); // VAT Exclusive
                }

                $('#vatAmount').text(formatNumber(vatAmount));
                $('#invoiceVat7').val(formatNumber(vatAmount));

                var withholdingTax = 0;
                if ($('#vat3').is(':checked')) {
                    withholdingTax = (totalAfterDiscount * 0.03).toFixed(2); // Withholding Tax 3%
                }
                $('#withholdingTax').text(formatNumber(withholdingTax));
                $('#invoiceVat3').val(formatNumber(withholdingTax));

                var grandTotal = totalAfterDiscount + parseFloat(vatAmount) - parseFloat(withholdingTax);
                $('#grandTotal').text(formatNumber(grandTotal.toFixed(2)));
                $('#invoiceGrandTotal').val(formatNumber(grandTotal.toFixed(2)));
            }

            // เพิ่มแถวสินค้าใหม่
            $('#addRow').click(function(event) {
                event.preventDefault();
                var newRow = $('#productAdd').clone(true, true).removeAttr('id').removeAttr('style');
                newRow.find('input, select').val(''); // เคลียร์ค่าในฟิลด์ input
                newRow.find('input[name="product_sum[]"]').val(''); // เคลียร์ค่าในฟิลด์ product_sum[]
                $('#table-product tbody .total-row').before(newRow);
            });

            // คำนวณเมื่อเปลี่ยนแปลงฟิลด์ quantity, price, discount หรือ VAT
            $(document).on('input', 'input[name="product_qty[]"], input[name="product_price[]"], #discount',
                function() {
                    var row = $(this).closest('tr');
                    calculateRow(row);
                    calculateTotals();
                });

            $(document).on('change', 'select[name="expense[]"], #vat', function() {
                calculateTotals();
            });

            $('#vat3').change(function() {
                calculateTotals();
            });

            // delete row
            $(document).on('click', '.removeRow', function(event) {
                event.preventDefault();
                $(this).closest('tr').remove();
                calculateTotals();
            });
            // คำนวณยอดรวมเมื่อเริ่มต้น
            calculateTotals();

        });



        $(document).ready(function() {
            $('#invoice-form').submit(function(event) {
                event.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ

                var form = $(this);
                var formData = form.serialize(); // แปลงข้อมูลฟอร์มเป็นแบบ URL-encoded
                $.ajax({
                    url: '{{ route('invoiceBooking.update') }}', // URL ของ endpoint ที่ต้องการส่งข้อมูลไป
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        
                        // ดำเนินการหลังจากได้รับการตอบกลับจากเซิร์ฟเวอร์
                        alert('ข้อมูลถูกอัปเดตเรียบร้อยแล้ว');
                        // ทำความสะอาดฟอร์มหรือรีเซ็ตค่าอื่นๆ ตามต้องการ
                        form[0].reset();
                    },
                    error: function(xhr) {
                        // จัดการกรณีที่เกิดข้อผิดพลาด
                        alert('เกิดข้อผิดพลาดในการส่งข้อมูล');
                    }
                });
            });
        });
    </script>
