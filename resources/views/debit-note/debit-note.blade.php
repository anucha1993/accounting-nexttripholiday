@extends('layouts.template')

@section('content')
    <br>
    <div class="container-fluid page-content">
        <div class="card">
            <div class="card-body">


                <h1 class="mb-4">ใบลดหนี้</h1>

                <!-- ตารางแสดงรายการสินค้า -->
                <table class="table table-bordered ">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคาต่อหน่วย</th>
                            <th>จำนวน</th>
                            <th>ประเภทภาษี</th>
                            <th>ราคารวม</th>
                            {{-- <th>Vat3</th> --}}
                            <th>ราคาสุทธิ</th>
                            <th class="table-actions" style="width: 200px">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <!-- รายการจะถูกเพิ่มที่นี่ -->
                    </tbody>
                    <tbody>
                        <tr class="editable">
                            <td><input type="hidden" id="number" value="0"></td>
                            <td style="width: 500px">
                                <select class="form-select" id="product">
                                    <option value="">-- เลือกสินค้า --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product['id'] }}" data-price="{{ $product['price'] }}">
                                            {{ $product['name'] }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control" id="price" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control" id="quantity" min="1" value="1">
                            </td>
                            <td style="width: 200px">
                                <div class="tax-checkbox">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="non_vat" name="non_vat"
                                            checked>
                                        <label class="form-check-label" for="non_vat">VAT</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="vat3" name="vat_3">
                                        <label class="form-check-label" for="vat3">VAT 3%</label>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <input type="text" id="totalPrice" class="form-control" readonly>
                            </td>
                            <td  style="display: none">
                                <input type="text" id="vatAmount" class="form-control" readonly>

                            </td>
                            <td style="display: none">
                                <input type="text" id="vat7Amount" class="form-control" readonly>

                            </td>


                            <td>
                                <input type="text" id="netPrice" class="form-control" readonly>
                            </td>



                            <td>
                                <button class="btn btn-success btn-sm" id="addBtn">เพิ่ม</button>
                            </td>


                        </tr>
                    </tbody>
                </table>

                <!-- สรุปผลลัพธ์ -->
                <div class="mt-4 p-3 bg-light rounded">

                    <div class="row ">
                        <div class="col-md-10 float-end">
                            <label for=""><b>การคำนวณ VAT:</b></label></br>
                            <input type="radio" name="vat_type" id="vat-include" value="include">
                            คำนวณรวมกับราคาสินค้าและบริการ (VAT Include)</br>
                            <input type="radio" name="vat_type" id="vat-exclude" value="exclude" checked>
                            คำนวณแยกกับราคาสินค้าและบริการ (VAT Exclude)
                        </div>
                        <div class="col-md-2  float-end">
                            <table>
                                <tr>
                                    <th class="float-end">ราคารวมทั้งหมด :</th>
                                    <td ><span id="grandTotal">0.00</span> บาท</td>
                                </tr>
                                <tr>
                                    <th class="float-end">Vat 3% :</th>
                                    <td><span id="grandTax">0.00</span> บาท</td>
                                </tr>
                                <tr>
                                    <th class="float-end">Vat 7% :</th>
                                    <td><span id="grandTax7">0.00</span> บาท</td>
                                </tr>
                                <tr>
                                    <th class="float-end">ราคาสุทธิทั้งหมด :</th>
                                    <td><span id="grandNet">0.00</span> บาท</td>
                                </tr>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // ตั้งค่า Checkbox
            // $('input[name="vat_3"]').change(function() {
            //     if ($(this).attr('id') === 'vat3') {
            //         $('#vat3').prop('checked', false);
            //     } 
            // });

            // อัปเดตราคาอัตโนมัติเมื่อเลือกสินค้า
            $('#product').change(function() {
                const price = $(this).find('option:selected').data('price') || 0;
                $('#price').val(price);
                calculateRowTotal();
            });

            // คำนวณราคารวม, ภาษี, และราคาสุทธิสำหรับแต่ละแถว
            function calculateRowTotal() {
                const price = parseFloat($('#price').val()) || 0;
                const quantity = parseInt($('#quantity').val()) || 0;
                const isVat3 = $('#vat3').is(':checked');
                const isnonVat = $('#non_vat').is(':checked');

                const total = price * quantity;
                const vat = isVat3 ? total * 0.03 : 0;
                const net = total + vat;
                const vat7 = isnonVat ? total * 0.07 : 0;

                $('#totalPrice').val(total.toFixed(2));
                $('#vatAmount').val(vat.toFixed(2));
                $('#vat7Amount').val(vat7.toFixed(2));
                $('#netPrice').val(net.toFixed(2));
            }

            // เพิ่มรายการ
            $('#addBtn').click(function() {
                const product = $('#product option:selected').text();
                const price = parseFloat($('#price').val()) || 0;
                const quantity = parseInt($('#quantity').val()) || 0;
                const isVat3 = $('#vat3').is(':checked');
                const nonVat = $('#non_vat').is(':checked');

                if (!product || price <= 0 || quantity <= 0) {
                    alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                    return;
                }

                // ถ้ากำลังแก้ไขแถวเดิม
                if (editingRow) {
                    updateRow(editingRow, product, price, quantity, isVat3, nonVat, originalIndex);
                    console.log("ก่อนเรียก updateRow:", originalIndex); // เพิ่มบรรทัดนี้
                    editingRow = null;
                    $('#addBtn').text('เพิ่ม').removeClass('btn-primary').addClass('btn-success');
                } else {
                    addItem(product, price, quantity, isVat3, nonVat);
                }

                // addItem(product, price, quantity, isVat3,nonVat,number);
                calculateGrandTotal();
                clearInputs();
            });

            // เพิ่มรายการในตาราง
            function addItem(product, price, quantity, isVat3, nonVat) {
                let indexNumber = 0;
                const total = price * quantity;
                const tax = isVat3 ? total * 0.03 : 0;
                const net = total + tax;
                const vat7 = nonVat ? net * 0.07 : 0;

                // Determine the next index number
                $('#itemsBody tr').each(function() {
                    const rowNumber = parseFloat($(this).find('td:eq(0)').text());
                    if (!isNaN(rowNumber) && rowNumber > indexNumber) {
                        indexNumber = rowNumber;
                    }
                });
                const newRow = `
            <tr>
                <td>${indexNumber+1}</td>
                <td>${product}</td>
                <td>${price.toFixed(2)}</td>
                <td>${quantity}</td>
                <td>${isVat3 ? 'VAT 3%'+'</br>' : ''}${nonVat ? 'VAT' : 'NON-VAT'}</td>
                <td>${total.toFixed(2)}</td>
                <td  style="display: none">${tax.toFixed(2)}</td>
                <td>${net.toFixed(2)}</td>
                <td  style="display: none">${vat7.toFixed(2)}</td>
                <td>
                    <button class="btn btn-warning btn-sm editBtn">แก้ไข</button>
                    <button class="btn btn-danger btn-sm deleteBtn">ลบ</button>
                </td>
            </tr>
        `;



                $('#itemsBody').append(newRow);
            }

            // เพิ่มตัวแปรเก็บสถานะการแก้ไขและแถวที่กำลังแก้ไข
            let editingRow = null;

            // แก้ไขรายการ
            $(document).on('click', '.editBtn', function() {
                editingRow = $(this).closest('tr');
                const cells = editingRow.find('td');
                originalIndex = cells.eq(0).text();


                // เตรียมข้อมูลในฟอร์มแก้ไข
                $('#indexNumber').val(originalIndex);
                $('#product').val($('#product option:contains(' + cells.eq(1).text() + ')').val());
                $('#price').val(parseFloat(cells.eq(2).text()));
                $('#quantity').val(cells.eq(3).text());
                // เปลี่ยนปุ่ม "เพิ่ม" เป็น "อัปเดต"
                $('#addBtn').text('อัปเดต').removeClass('btn-success').addClass('btn-primary');
            });


            // ฟังก์ชันอัปเดตแถว
            function updateRow(row, product, price, quantity, isVat3, nonVat, originalIndex) {
                // console.log("ประเภท originalIndex:", typeof originalIndex); // เพิ่มบรรทัดนี้
                // console.log("ค่า originalIndex:", originalIndex); // เพิ่มบรรทัดนี้
                const total = price * quantity;
                const tax = isVat3 ? total * 0.03 : 0;
                const net = total + tax;
                const vat7 = nonVat ? net * 0.07 : 0;

                row.find('td:eq(0)').text(originalIndex);
                row.find('td:eq(1)').text(product);
                row.find('td:eq(2)').text(price.toFixed(2));
                row.find('td:eq(3)').text(quantity);
                row.find('td:eq(4)').html((isVat3 ? 'VAT 3%<br>' : '') + (nonVat ? 'VAT' : 'NON-VAT'));
                row.find('td:eq(5)').text(total.toFixed(2));
                row.find('td:eq(6)').text(tax.toFixed(2));
                row.find('td:eq(7)').text(net.toFixed(2));
                row.find('td:eq(8)').text(vat7.toFixed(2));
                calculateGrandTotal();
            }




            // ลบรายการ
            $(document).on('click', '.deleteBtn', function() {
                $(this).closest('tr').remove();
                calculateGrandTotal();
            });

            // คำนวณผลรวมทั้งหมด
            function calculateGrandTotal() {
                let grandTotal = 0;
                let grandTax = 0;
                let grandTax7 = 0;
                let grandNet = 0;
                let total = 0;

                let vatMethod = $('input[name="vat_type"]:checked').val() || 'exclude';

                $('#itemsBody tr').each(function() {
                    grandTotal += parseFloat($(this).find('td:eq(5)').text());
                    grandNet += parseFloat($(this).find('td:eq(6)').text());
                    grandTax += parseFloat($(this).find('td:eq(7)').text());

                    grandTax7 += parseFloat($(this).find('td:eq(8)').text());

                    if (vatMethod == 'exclude') {
                        total += parseFloat($(this).find('td:eq(7)').text()) + parseFloat($(this).find(
                            'td:eq(8)').text());
                    } else {
                        total += parseFloat($(this).find('td:eq(7)').text()) * (100 / 107);
                    }

                });

                $('#grandTotal').text(grandTotal.toFixed(2));
                $('#grandTax').text(grandTax.toFixed(2));
                $('#grandTax7').text(grandTax7.toFixed(2));
                $('#grandNet').text(total.toFixed(2));
            }

            // ล้างข้อมูลในฟอร์ม
            function clearInputs() {
                $('#product').val('');
                $('#price').val('');
                $('#quantity').val(1);
                $('#non_vat').prop('checked', true);
                $('#vat3').prop('checked', false);
                $('#totalPrice').val('');
                $('#vatAmount').val('');
                $('#netPrice').val('');
            }

            // คำนวณอัตโนมัติเมื่อเปลี่ยนจำนวนหรือประเภทภาษี
            $('#quantity, #vat3, #non_vat').change(function() {
                calculateRowTotal();
            });
            $('#quantity, #vat3, #non_vat').on('keyup', function() {
                calculateRowTotal();
            });

            $('input[name="vat_type"]').change(function() {
    calculateGrandTotal();
});



        });
    </script>
@endsection
