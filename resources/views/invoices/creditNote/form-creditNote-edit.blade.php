<div class="row">
    <h4 class="text-info">ใบลดหนี้ #{{$creditNote->credit_note_number}}</h4>
    <div class="col-md-8 border" style="padding: 10px">
        <div class="row">
            <div class="col-md-12">
                <b>Customer ID :</b> <span style="margin: 20px;">CUS-000{{ $customer->customer_id }}</span> </br>
                <b>Name : </b> <span style="margin: 60px;"> คุณ <label
                        id="customer_name-label">{{ $customer->customer_name }}</label></span></br>
                <b>Address : </b> <span style="margin: 45px;"> <label
                        id="customer_address-label">{{ $customer->customer_address }}</label></span></br>
                <b>Tax ID: </b> <span style="margin: 60px;"> <label
                        id="customer_texid-label">{{ $customer->customer_texid }}</label></span></br>
                <b>Moblie : </b> <span style="margin: 55px;"> <label
                        id="customer_tel-label">{{ $customer->customer_tel }}</label></span></br>
                <b>Fax : </b> <span style="margin: 75px;"> <label
                        id="customer_fax-label">{{ $customer->customer_fax ?: '-' }}</label></span></br>
                <b>Email : </b> <span style="margin: 60px;"> <label
                        id="customer_email-label">{{ $customer->customer_email }}</label></span></br>
            </div>
        </div>
    </div>



    <div class="col-md-4 border" style="padding-top: 10px">
        <div class="row">
            <div class="col-md-12">
                <b>Date :</b> <span style="margin: 50px;">
                    {{ date('d-M-Y', strtotime($invoices->created_at)) }}</span></br>
                <b>Booking No :</b> <span style="margin: 5px;"> {{ $invoices->invoice_booking }}</span></br>
                <b>Sale :</b> <span style="margin: 53px;"> {{ $sale->name }}</span></br>
                <b>Email :</b> <span style="margin: 45px;"> {{ $sale->email }}</span></br>
                <b>Tour Code :</b> <span style="margin: 15px;"> {{ $tour->code }}</span></br>
                <b>Airline :</b> <span style="margin: 40px;"> {{ $airline->travel_name }}</span></br>
                <b>เลขที่อ้างอิง :</b> <span style="margin: 10px;" class="text-danger"> {{ $invoices->invoice_number }}</span></br>
            </div>
        </div>
    </div>

    <form id="editCredit-form" method="post">
        @csrf
        @method('PUT')
        {{-- <br>
        <div class="row">
            <div class="col-md-4">
                <label class="text-primary">ประเภทการชำระเงิน</label>
                <select name="payment_type" class="form-select payment-type" required>
                    <option @if($creditNote->payment_type === 'full') selected @endif  value="full">ชำระเต็มจำนวน</option>
                    <option @if($creditNote->payment_type === 'deposit') selected @endif value="deposit">มัดจำ(แบ่งชำระ)</option>
                </select>
            </div>
            <div class="col-md-8">
                <div class="full">
                    <div class="row">
                        <div class="col-md-4">
                            <label>กำหนดระยะเวลาการชำระ</label>
                            <input type="datetime-local" name="payment_before_date" 
                                class="form-control" value="{{$creditNote->payment_before_date}}" required>
                        </div>
                        <div class="col-md-4">
                            <label id="text-deposit">จำนวนเงิน มัดจำ</label>
                            <input type="number" name="deposit" class="form-control deposit text-end"
                               placeholder="00.00" min ="0.01" step="0.01"  value="{{$creditNote->deposit}}">
                        </div>
                        <div class="col-md-4">
                            <label>ยอดคงค้าง</label>
                            <input type="text"
                                class="form-control outstanding-balance text-end text-danger bg-warning"
                                placeholder="00.00" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br> --}}


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
                    
                     @forelse ($creditNoteProduct as $key => $item)

                        <tr id="productList" class="mt-0">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <input type="hidden" name="product_name[]" value="{{ $item->product_name }}">
                                <select name="product_id[]" class="form-select">
                                    <option value="{{ $item->product_id }}" selected>{{ $item->product_name }}
                                    </option>
                                    @foreach ($productIncome as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <select name="expense[]" class="form-select">
                                    <option value="income" {{ $item->expense_type == 'income' ? 'selected' : '' }}>
                                        รายรับ</option>
                                    <option value="discount" {{ $item->expense_type == 'discount' ? 'selected' : '' }}>
                                        ส่วนลด</option>
                                </select>
                            </td>
                            <td><input name="product_qty[]" type="number" value="{{ $item->credit_note_qty }}"
                                    class="form-control text-end"></td>
                            <td><input name="product_price[]" type="number" value="{{ $item->credit_note_price }}"
                                    class="form-control text-end"></td>
                            <td><input name="product_sum[]" type="number" value="{{ $item->credit_note_sum }}"
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
                                    <option value="{{ $product->id }}">
                                        
                                        {{ $product->product_name }}</option>
                                @endforeach

                            </select>
                            
                        </td>
                        <td>
                            <select name="expense[]" class="form-select">
                                <option value="income">รายรับ</option>
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
                    <input type="checkbox" name="vat_3_status" id="vat3" value="Y"
                        @if ($creditNote->vat_3_status === 'Y') checked @endif>
                    <label for="vat3">คิดภาษีหัก ณ ที่จ่าย 3% (คำนวณจากยอดราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT
                        Amount)</label>
                </div>
                <hr>
                <div class="col-md-12 mt-3">
                    <label class="">สาเหตุที่ออกใบแจ้งหนี้เพิ่ม : </label>
                  <input type="text" name="credit_note_add_note" class="form-control" value="{{$creditNote->credit_note_add_note}}" placeholder="กรุณาระบุ" required>
                </div>

              
                <div class="col-md-12">
                    <label class="text-danger">หมายเหตุ : </label>
                    <textarea name="credit_note_note" cols="30" rows="3" class="form-control">{{$creditNote->credit_note_note}}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">

                    <div class="col-md-8 text-end">
                        <label class="">มูลค่าตามใบกำกับภาษีเดิม :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <label >{{ number_format($invoices->invoice_grand_total, 2, '.', ',');  }}</label>
                        <input type="hidden" name="grand_total_old" id="grandTotalOld"  value="{{$invoices->invoice_grand_total}}">
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-8 text-end">
                        <label class="">มูลค่าที่ถูกต้อง :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <label id="totalSumEdit">0.00</label>
                        <input type="hidden" name="total_sum_new"  id="totalSumNew">
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-8 text-end">
                        <label class="">ผลต่าง :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <label id="difference">0.00</label>
                        <input type="hidden" name="difference_total"  id="differenceTotal">
                    </div>
                   
                </div>
  
               <br>

            
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
                        <label id="grandTotal" class="bg-warning">0.00</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 text-end">
                        <label class="text-info">คำนวน Vat :</label>
                    </div>
                    <div class="col-md-4 text-end">
                        <select name="vat_type" id="vat" class="form-select">
                            <option @if ($invoices->vat_type === 'include') selected @endif value="include">Vat Include
                            </option>
                            <option @if ($invoices->vat_type === 'exclude') selected @endif value="exclude">Vat Exclude
                            </option>
                        </select>
                    </div>
                </div>

            </div>


        </div>

        <input type="hidden" name="credit_note_id" id="credit-note-id" value="{{$creditNote->credit_note_id}}">
        <input type="hidden" name="credit_note_total" id="invoiceTotal">
        <input type="hidden" name="credit_note_discount" id="invoiceDiscount">

        <input type="hidden" name="credit_note_vat_7" id="invoiceVat7">
        <input type="hidden" name="credit_note_grand_total" id="invoiceGrandTotal">
        <input type="hidden" name="credit_note_vat_3" id="invoiceVat3">
        <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $invoices->invoice_id }}">
        <input type="hidden" name="credit_note_number"  value="{{ $creditNote->credit_note_number }}">

        <input type="hidden" name="invoice_number" value="{{$invoices->invoice_number}}">


        <button id="submit" class="btn btn-success btn-sm">บันทึก</button>


    </form>


    

    <script>
        $(document).ready(function() {
            var originalDeposit = parseFloat($('.deposit').val()) || 0; // เก็บค่าเดิมของ deposit ไว้

            // ประเภทค่าใช้จ่าย
            $('.payment-type').on('change', function() {
                paymentType();
            });

            // การเปลี่ยนแปลงค่า deposit
            $('.deposit').on('change', function() {
                var deposit = parseFloat($(this).val()) || 0;
                originalDeposit = deposit; // อัปเดตค่าเดิมของ deposit
                paymentType(); // คำนวณใหม่ตามค่า deposit ที่เปลี่ยนแปลง
            });

            function paymentType() {
                var type = $('.payment-type').val();
                var deposit = parseFloat($('.deposit').val()) || 0;
                var invoiceGrandTotal = parseFloat($('#invoiceGrandTotal').val());

                if (isNaN(invoiceGrandTotal)) {
                    invoiceGrandTotal = 0;
                }

                if (type === 'deposit') {
                    // ใช้ค่า deposit เดิมที่เก็บไว้
                    deposit = originalDeposit;

                    var grandTotal = invoiceGrandTotal - deposit;
                    $('.outstanding-balance').val(formatNumber(grandTotal));
                    $('#text-deposit').html('จำนวนเงิน มัดจำ');
                    $('.deposit').val(deposit.toFixed(2)); // แสดงค่า deposit เดิม
                } else {
                    // คำนวณโดยไม่ใช่ deposit
                    $('#text-deposit').html('ชำระเต็มจำนวน');
                    $('.outstanding-balance').val(formatNumber(invoiceGrandTotal));
                    $('.deposit').val(invoiceGrandTotal.toFixed(2));
                }
            }

            // เรียกฟังก์ชันนี้เมื่อโหลดหน้า
            paymentType();

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

                    if (expenseType === 'income') {
                        totalSum += sum;
                    } else if (expenseType === 'discount') {
                        totalDiscount += sum;
                    }
                });

                $('#totalSum').text(formatNumber(totalSum));
                $('#invoiceTotal').val(totalSum);
                $('#discount').text(formatNumber(totalDiscount));
                $('#invoiceDiscount').val(totalDiscount);

                            // ผลต่าง
         


                var totalAfterDiscount = totalSum - totalDiscount;
                $('#totalAfterDiscount').text(formatNumber(totalAfterDiscount));

                var vatType = $('#vat').val();
                var vatAmount = 0;

                if (vatType === 'include') {
                    vatAmount = (totalAfterDiscount * 0.07 / 1.07).toFixed(2); // VAT Inclusive
                } else if (vatType === 'exclude') {
                    vatAmount = (totalAfterDiscount * 0.07).toFixed(2); // VAT Exclusive
                }

                $('#vatAmount').text(formatNumber(vatAmount));
                $('#invoiceVat7').val(vatAmount);

                var withholdingTax = 0;
                if ($('#vat3').is(':checked')) {
                    withholdingTax = (totalAfterDiscount * 0.03).toFixed(2); // Withholding Tax 3%
                }
                $('#withholdingTax').text(formatNumber(withholdingTax));
                $('#invoiceVat3').val(withholdingTax);
                var grandTotalOld = parseFloat($('#grandTotalOld').val()) || 0;
                var grandTotal = totalAfterDiscount + parseFloat(vatAmount) - parseFloat(withholdingTax);
                var grandTotalNew = grandTotal + parseFloat(grandTotalOld);


            // มูลค่าที่ถูกต้อง
            $('#totalSumEdit').text(formatNumber(grandTotalNew));
            $('#totalSumNew').val(grandTotalNew);

            // จำนวนเงินรวมทั้งสิ้น
            $('#grandTotal').text(formatNumber(grandTotal));
            $('#invoiceGrandTotal').val(grandTotal);
            $('#difference').text(formatNumber(grandTotal));
            $('#differenceTotal').val(grandTotal);
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
                    paymentType();
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
            paymentType();

        });




       // add Edit

$(document).ready(function() {
    $('#editCredit-form').submit(function(event) {
        event.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ
        var creditNoteId = $('#credit-note-id').val();
        var form = $(this);
        var formData = form.serialize(); // แปลงข้อมูลฟอร์มเป็นแบบ URL-encoded
        $.ajax({
            url: '{{ url("invoice/credit/update/") }}/' + creditNoteId, // URL ของ endpoint ที่ต้องการส่งข้อมูลไป
            type: 'PUT',
            data: formData,
            success: function(response) {
                alert('บันทึกข้อมูลสำเร็จ');
            },
            error: function(xhr) {
                alert('เกิดข้อผิดพลาดในการส่งข้อมูล');
            }
        });
    });
});





    </script>