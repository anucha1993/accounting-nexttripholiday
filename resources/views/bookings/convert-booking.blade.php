@extends('layouts.template')

@section('content')
    <style>
        <style>.table-custom {
            border: 1px solid #e0e0e0;
            padding: 10px;
            margin-bottom: 20px;
        }


        .table-custom input,
        .table-custom select {
            width: 100%;
            padding: 3px;
            margin-bottom: 10px;
        }

        .add-row {
            margin: 10px 0;
            text-align: left;
        }

        .select2-selection {
            height: 30px !important;
            text-align: left;
            z-index: 9999;
        }

        .select2-selection__rendered {
            line-height: 31px !important;
        }
    </style>

    </style>
    <div class="container-fluid page-content">
        <!-- Todo list-->
        <div class="todo-listing ">
            <div class="container border bg-white">
                <h4 class="text-center my-4">สร้างใบเสนอราคา
                </h4>
                <hr>

                <form action="" method="post">
                    @csrf

                    <div class="row table-custom ">
                        <div class="col-md-3 ms-auto">
                            <label><b>เซลล์ผู้ขายแพคเกจ:</b></label>
                            <select name="quote_sale" class="form-select">
                                @forelse ($sales as $item)
                                    <option @if ($bookingModel->sale_id === $item->id) selected @endif value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @empty
                                    <option value="">--Select Sale--</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-3 ms-3">
                            <label>วันที่สั่งซื้อ,จองแพคเกจ:</label>
                            <input type="text" id="displayDatepicker" class="form-control">
                            <input type="hidden" id="submitDatepicker" name="date" name="quote_booking_date"
                                value="{{ date('Y-m-d', strtotime($bookingModel->created_at)) }}">
                        </div>
                    </div>
                    <hr>
                    <h5 class="text-danger">รายละเอียดแพคเกจทัวร์:</h5>

                    <div class="row table-custom">
                        <div class="col-md-6">
                            <label>ชื่อแพคเกจทัวร์:</label>
                            <input type="text" id="tourSearch" class="form-control"
                                placeholder="ค้นหาแพคเกจทัวร์...ENTER เพื่อค้นหา"
                                value="{{ $tour->code }}-{{ $tour->name }}">

                            <div id="tourResults" class="list-group" style="position: absolute; z-index: 1000; width: 35%;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>ระยะเวลาทัวร์ (วัน/คืน): {{ $tour->num_day }}</label>
                            <select name="quote_numday" class="form-select" id="numday">
                                <option value="">--เลือกระยะเวลา--</option>
                                @forelse ($numDays as $item)
                                    <option @if ($tour->num_day === $item->num_day_name) selected @endif
                                        data-day="{{ $item->num_day_total }}" value="{{ $item->num_day_id }}">
                                        {{ $item->num_day_name }}</option>
                                @empty
                                @endforelse

                            </select>
                        </div>
                        @php
                            $countryId = json_decode($tour->country_id); // แปลงให้เป็น array
                        @endphp

                        <div class="col-md-3">
                            <label>ประเทศที่เดินทาง: {{ $tour->country_id }}</label>
                            <select name="quote_country" class="form-select country-select select" style="width: 100%">
                                <option value="">--เลือกประเทศที่เดินทาง--</option>
                                @forelse ($country as $item)
                                    <option @if (in_array($item->id, $countryId)) selected @endif value="{{ $item->id }}">
                                        {{ $item->iso2 }}-{{ $item->country_name_th }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <div class="row table-custom">
                        <div class="col-md-3">
                            <label>โฮลเซลล์: </label>
                            <select name="quote_wholesale" class="form-select country-select select" style="width: 100%">
                                <option value="">--เลือกโฮลเซลล์--</option>
                                @forelse ($wholesale as $item)
                                    <option @if ($tour->wholesale_id === $item->id) selected @endif value="{{ $item->id }}">
                                        {{ $item->code }}-{{ $item->wholesale_name_th }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>สายการบิน:</label>
                            <select name="quote_airline" class="form-select country-select select" style="width: 100%">
                                <option value="">--เลือกสายการบิน--</option>
                                @forelse ($airline as $item)
                                    <option @if ($tour->airline_id === $item->id) selected @endif value="{{ $item->id }}">
                                        {{ $item->code }}-{{ $item->travel_name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>วันออกเดินทาง:</label>
                            <input type="text" class="form-control" id="date-start-display"
                                placeholder="วันออกเดินทาง...">
                            <input type="hidden" id="date-start" name="quote_date_start"
                                value="{{ date('Y-m-d', strtotime($bookingModel->start_date)) }}">
                        </div>
                        <div class="col-md-3">
                            <label>วันเดินทางกลับ: </label>
                            <input type="text" class="form-control" id="date-end-display"
                                placeholder="วันเดินทางกลับ...">
                            <input type="hidden" id="date-end" name="quote_date_end"
                                value="{{ date('Y-m-d', strtotime($bookingModel->end_date)) }}">
                        </div>
                    </div>
                    <hr>
                    <h5 class="text-danger">ข้อมูลลูกค้า:</h5>

                    <div class="row table-custom">
                        <div class="col-md-3">
                            <label class="">ชื่อลูกค้า:</label>
                            <input type="text" class="form-control" name="customer_name" placeholder="ชื่อลูกค้า"
                                value="{{ $bookingModel->name . ' ' . $bookingModel->surname }}" required
                                aria-describedby="basic-addon1">
                            @if ($checkCustomer && $checkCustomer->customer_name !== $bookingModel->name)
                                <small
                                    class="form-text text-muted text-danger check-customer">{{ $checkCustomer->customer_name }}</small>
                            @endif
                        </div>

                        <div class="col-md-3">
                            <label>อีเมล์:</label>
                            <input type="email" class="form-control" name="customer_email"
                                value="{{ $bookingModel->email }}" placeholder="email@domail.com" required
                                aria-describedby="basic-addon1">
                            @if ($checkCustomer && $checkCustomer->customer_email !== $bookingModel->email)
                                <small id="name" class="form-text  check-customer text-danger">ข้อมูลในระบบ :
                                    {{ $checkCustomer->customer_email }}</small>
                            @endif
                        </div>

                        <div class="col-md-3">
                            <label>เลขผู้เสียภาษี:</label>
                            <input type="text" id="texid" class="form-control" name="customer_texid" mix="13"
                                value="{{ $checkCustomer ? $checkCustomer->customer_texid : '' }}"
                                placeholder="เลขประจำตัวผู้เสียภาษี" required aria-describedby="basic-addon1">
                        </div>

                        <div class="col-md-3">
                            <label>เบอร์โทรศัพท์ :</label>
                            <input type="text" class="form-control" name="customer_tel"
                                value="{{ $bookingModel->phone }}" placeholder="เบอร์โทรศัพท์" required
                                aria-describedby="basic-addon1">
                            @if ($checkCustomer && $checkCustomer->customer_tel !== $bookingModel->phone)
                                <small id="name" class="form-text check-customer  text-danger">ข้อมูลในระบบ :
                                    {{ $checkCustomer->customer_tel }}</small>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label>เบอร์โทรสาร :</label>
                            <input type="text" class="form-control" id="fax" name="customer_fax"
                                value="{{ $checkCustomer ? $checkCustomer->customer_fax : '' }}"
                                placeholder="เบอร์โทรศัพท์" aria-describedby="basic-addon1">
                        </div>
                        <div class="col-md-9">
                            <label>ที่อยู่:</label>
                            <textarea name="customer_address" id="address" class="form-control" cols="30" rows="2"
                                placeholder="ที่อยู่" required>{{ $checkCustomer ? $checkCustomer->customer_address : '' }}</textarea>
                        </div>
                    </div>
                    <br>


                    <h5 style="background: #e0e0e0; padding: 5px">ข้อมูลค่าบริการ-รายได้</h5>
                    <hr>
                    <div id="quotation-table" class="table-custom text-center">
                        <div class="row header-row" style="padding: 5px">
                            <div class="col-md-1">ลำดับ</div>
                            <div class="col-md-3">รายการสินค้า</div>
                            <div class="col-md-2">ประเภทค่าใช้จ่าย</div>
                            <div class="col-md-1">NonVat</div>
                            <div class="col-md-1">จำนวน</div>
                            <div class="col-md-2">ราคา/หน่วย</div>
                            <div class="col-md-2">ยอดรวม</div>
                        </div>
                        <hr>
                        @forelse ($quoteProducts as $key => $item)
                            @if ($item['product_qty'])
                                <div class="row item-row">
                                    <div class="col-md-1"><span class="row-number"> {{ $key + 1 }}</span> <a
                                            href="javascript:void(0)" class="remove-row-btn text-danger"><span
                                                class=" fa fa-trash"></span></a></div>
                                    <div class="col-md-3">
                                        <select name="product_id[]" class="form-select product-select"
                                            id="product-select" style="width: 100%;">
                                            @forelse ($products as $product)
                                                <option @if ($item['product_id'] === $product->id) selected @endif
                                                    data-pax="{{ $product->product_pax }}" value="{{ $product->id }}">
                                                    {{ $product->product_name }}
                                                    {{ $product->product_pax === 'Y' ? '(Pax)' : '' }}</option>
                                            @empty
                                            @endforelse
                                        </select>

                                    </div>
                                    <div class="col-md-2">
                                        <select name="expense_type[]" class="form-select">
                                            <option selected value="income"> รายได้ </option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 text-center"><input type="checkbox" name="non_vat[]"
                                            class="non-vat">
                                    </div>
                                    <div class="col-md-1"><input type="number" name="quantity[]"
                                            class="quantity form-control text-end" value="{{ $item['product_qty'] }}"
                                            step="0.01"></div>
                                    <div class="col-md-2"><input type="number" name="price_per_unit[]"
                                            class="price-per-unit form-control text-end"
                                            value="{{ $item['product_price'] }}" step="0.01">
                                    </div>
                                    <div class="col-md-2"><input type="number" name="total_amount[]"
                                            class="total-amount form-control text-end" value="0" readonly></div>
                                </div>
                            @endif

                        @empty
                        @endforelse

                    </div>
                    <div class="add-row">
                        <i class="fa fa-plus"></i><a id="add-row" href="javascript:void(0)" class="">
                            เพิ่มรายการ</a>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="vat-method">การคำนวณ VAT:</label>
                                        <div>
                                            <input type="radio" id="vat-include" name="vat_type" value="include">
                                            <label for="vat-include">คำนวณรวมกับราคาสินค้าและบริการ (VAT
                                                Include)</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="vat-exclude" name="vat_type" value="exclude">
                                            <label for="vat-exclude">คำนวณแยกกับราคาสินค้าและบริการ (VAT
                                                Exclude)</label>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <div class="col-md-12">
                                    <div class="row summary-row">
                                        <div class="col-md-10">
                                            <input type="checkbox" name="vat3_status" value="Y"
                                                id="withholding-tax"> <span class="">
                                                คิดภาษีหัก ณ ที่จ่าย 3% (คำนวณจากยอด ราคาก่อนภาษีมูลค่าเพิ่ม /
                                                Pre-VAT
                                                Amount)</span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md012">
                                    <label>จำนวนเงินภาษีหัก ณ ที่จ่าย 3% : &nbsp;</label><span class="text-danger"
                                        id="withholding-amount"> 0.00</span> บาท
                                    <hr>
                                </div>

                                <div class="col-md-12" style="padding-bottom: 10px">
                                    <label>บันทึกเพิ่มเติม</label>
                                    <textarea name="quote_note" class="form-control" cols="30" rows="2"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="summary text-info">
                                    <div class="row summary-row ">
                                        <div class="col-md-10 text-end">รวมเป็นเงิน</div>
                                        <div class="col-md-2 text-end"><span id="sum-total">0.00</span></div>
                                    </div>
                                    <div class="row summary-row">
                                        <div class="col-md-10 text-end">ส่วนลด</div>
                                        <div class="col-md-2 text-end"><span id="sum-discount">0.00</span></div>
                                    </div>
                                    <div class="row summary-row">
                                        <div class="col-md-10 text-end">ราคาหลังหักส่วนลด</div>
                                        <div class="col-md-2 text-end"><span id="after-discount">0.00</span></div>
                                    </div>
                                    <div class="row summary-row">
                                        <div class="col-md-10 text-end">ภาษีมูลค่าเพิ่ม 7%</div>
                                        <div class="col-md-2 text-end"><span id="vat-amount">0.00</span></div>
                                    </div>
                                    <div class="row summary-row">
                                        <div class="col-md-10 text-end">ราคาไม่รวมภาษีมูลค่าเพิ่ม</div>
                                        <div class="col-md-2 text-end"><span id="price-excluding-vat">0.00</span>
                                        </div>
                                    </div>
                                    <div class="row summary-row">
                                        <div class="col-md-10 text-end">จำนวนเงินรวมทั้งสิ้น</div>
                                        <div class="col-md-2 text-end"><b><span class="bg-warning"
                                                    id="grand-total">0.00</span></b></div>
                                    </div>

                                </div>
                            </div>
                            <br>



                            <div class="row">
                                <div class="col-md-12">
                                    <h5>เงือนไขการชำระเงิน</h5>
                                </div>
                                <div class="col-md-12 ">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="radio" name="quote_payment_type" id="quote-payment-deposit"
                                                value="deposit"> <label for="quote-payment-type"> เงินมัดจำ </label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="radio" name="quote_payment_type" id="quote-payment-full"
                                                value="full"> <label for="quote-payment-type"> ชำระเต็มจำนวน </label>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <span for="">ภายในวันที่</span>
                                    <input type="datetime-local" class="form-control" name="quote_payment_date"
                                        value="">
                                </div>
                                <div class="col-md-4">
                                    <span for="">เรทเงินมัดจำ</span>
                                    <select name="quote_payment_price" class="form-select" id="quote-payment-price">
                                        <option value="0.00">0.00</option>
                                        <option value="1000">1,000</option>
                                        <option value="1500">1,500</option>
                                        <option value="2000">2,000</option>
                                        <option value="3000">3,000</option>
                                        <option value="4000">4,000</option>
                                        <option value="5000">5,000</option>
                                        <option value="6000">6,000</option>
                                        <option value="7000">7,000</option>
                                        <option value="8000">8,000</option>
                                        <option value="9000">9,000</option>
                                        <option value="10000">10,000</option>
                                        <option value="15000">15,000</option>
                                        <option value="20000">20,000</option>
                                        <option value="30000">30,000</option>
                                        <option value="24000">24,000</option>
                                        <option value="25000">25,000</option>
                                        <option value="28000">28,000</option>
                                        <option value="29000">29,000</option>
                                        <option value="34000">34,000</option>
                                        <option value="50000">50,000</option>
                                        <option value="70000">70,000</option>
                                        <option value="35000">35,000</option>
                                        <option value="40000">40,000</option>
                                        <option value="45000">45,000</option>
                                        <option value="80000">80,000</option>
                                        <option value="30500">30,500</option>
                                        <option value="35500">35,500</option>
                                        <option value="36000">36,000</option>
                                        <option value="38000">38,000</option>
                                        <option value="100000">100,000</option>
                                    </select>
                                </div>
                                <input type="hidden" id="booking-create-date">
                                <input type="hidden" id="booking-date">
                                <div class="col-md-4 ">
                                    <span for="">จำนวนเงินที่ต้องชำระ</span>
                                    <input type="number" class="form-control pax-total" name="quote_payment_total"
                                        step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <br>

                            <span>วันที่จอง : <label class="text-info">
                                </label></span>
                            <span>วันที่เดินทาง <label class="text-info">
                                </label></span>
                            {{-- <input type="text" class="form-control pax-total" readonly
                                placeholder="ยอด Pax ที่คำนวณได้"> --}}
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        {{-- hidden --}}
                        <input type="hidden" name="quote_total" id="quote-total">
                        <input type="hidden" name="quote_discount" id="quote-discount">
                        <input type="hidden" name="quote_after_discount" id="quote-after-discount">
                        <input type="hidden" name="quote_price_excluding_vat" id="quote-price-excluding-vat">
                        <input type="hidden" name="quote_grand_total" id="quote-grand-total">
                        <input type="hidden" name="quote_vat_3" id="quote-withholding-amount">
                        <input type="hidden" name="quote_vat_7" id="quote-vat-7">

                        <button type="submit" class="btn btn-success btn-sm  mx-3" form="form-update">
                            <i class="fa fa-save"></i> Update</button>
                    </div>
                    <br>
            </div>

            <br>
        </div>



        </form>
        <br>
    </div>
    </div>
    </div>





    </div>
    <script>
        $(document).ready(function() {
            $('.country-select').select2();
            $('.product-select').select2();
        });
    </script>


    {{-- การคำนวนใบเสนอราคา --}}

    <script>
        $(document).ready(function() {

            // ฟังก์ชันจัดรูปแบบตัวเลข
            function formatNumber(num) {
                return parseFloat(num).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            function updateRowNumbers() {
                $('#quotation-table .item-row').each(function(index) {
                    $(this).find('.row-number').text(index + 1);
                });
            }

            function calculateTotals() {
                let sumTotal = 0;
                let sumDiscount = 0;
                let sumPriceExcludingVat = 0;
                let sumPriceExcludingVatNonVat = 0;
                let totalBeforeDiscount = 0;
                let withholdingTaxTotal = 0

                $('#quotation-table .item-row').each(function() {
                    const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    const pricePerUnit = parseFloat($(this).find('.price-per-unit').val()) || 0;
                    const isNonVat = $(this).find('.non-vat').is(':checked');
                    const expenseType = $(this).find('select[name="expense_type[]"]').val();
                    const vatMethod = $('input[name="vat_type"]:checked').val();

                    let total = quantity * pricePerUnit;
                    let priceExcludingVat = total;

                    if (expenseType === 'discount') {
                        sumDiscount += total;
                        total = total * -1;
                    }

                    totalBeforeDiscount += (expenseType !== 'discount') ? total : 0;

                    if (isNonVat) {
                        $(this).find('.total-amount').val(total.toFixed(2));
                        $(this).find('.price-excluding-vat').val(total.toFixed(2));
                        sumPriceExcludingVatNonVat += total;
                        sumTotal += total;
                    } else {
                        $(this).find('.total-amount').val(total.toFixed(2));
                        $(this).find('.price-excluding-vat').val(priceExcludingVat.toFixed(2));
                        if (vatMethod === 'include') {
                            // คำนวณ VAT และราคาก่อน VAT ตามสูตรที่กำหนด
                            const vatAmount = total - (total * 100 / 107);
                            priceExcludingVat = total - vatAmount;

                            $(this).find('.total-amount').val(total.toFixed(2));
                            $(this).find('.price-excluding-vat').val(priceExcludingVat.toFixed(2));

                            sumPriceExcludingVat += priceExcludingVat;
                        } else {
                            sumPriceExcludingVat += total;
                        }
                        sumTotal += total;
                        withholdingTaxTotal += total;
                    }
                });

                const afterDiscount = totalBeforeDiscount - sumDiscount;
                let vatAmount = 0;

                if ($('input[name="vat_type"]:checked').val() === 'include') {
                    // vatAmount = sumTotal - sumPriceExcludingVat;
                    // เนื่องจากเป็น VAT Include ให้ตั้ง Grand Total เป็นยอดเดิม
                    grandTotal = sumTotal;
                } else {
                    vatAmount = sumPriceExcludingVat * 0.07;
                    grandTotal = afterDiscount + vatAmount;
                }

                const withholdingTax = $('#withholding-tax').is(':checked') ? withholdingTaxTotal * 0.03 : 0;
                $('#quote-withholding-amount').val(withholdingTax.toFixed(2));

                let GrandTotalOld = parseFloat($('#GrandTotalOld').val()) || 0;



                $('#sum-total').text(formatNumber(sumTotal.toFixed(2)));
                $('#quote-total').val(sumTotal.toFixed(2));

                $('#sum-discount').text(formatNumber(sumDiscount.toFixed(2)));
                $('#quote-discount').val(sumDiscount.toFixed(2));

                $('#after-discount').text(formatNumber(afterDiscount.toFixed(2)));
                $('#quote-after-discount').val(afterDiscount.toFixed(2));

                $('#vat-amount').text(formatNumber(vatAmount.toFixed(2)));
                $('#quote-vat-7').val(vatAmount.toFixed(2));

                $('#price-excluding-vat').text(formatNumber((sumPriceExcludingVat + sumPriceExcludingVatNonVat)
                    .toFixed(2)));
                $('#quote-price-excluding-vat').val((sumPriceExcludingVat + sumPriceExcludingVatNonVat).toFixed(2));

                $('#grand-total').text(formatNumber(grandTotal.toFixed(2)));
                $('#quote-grand-total').val(grandTotal.toFixed(2));

                $('#withholding-amount').text(formatNumber(withholdingTax.toFixed(2)));

                // ยอดเดิม (GrandTotalOld)
                $('#invoice-total-old').text(formatNumber(GrandTotalOld.toFixed(2)));

                // มูลค่าที่ถูกต้อง (GrandTotalOld + sumPriceExcludingVat + sumPriceExcludingVatNonVat)
                const grandTotalNew = GrandTotalOld - sumPriceExcludingVat - sumPriceExcludingVatNonVat;
                $('#grand-total-new').text(formatNumber(grandTotalNew.toFixed(2)));
                $('#grand-total-new-val').val(grandTotalNew);
                // ส่วนต่าง (Difference)
                $('#difference').text(formatNumber((GrandTotalOld - grandTotalNew).toFixed(2)));
                $('#difference-val').val(GrandTotalOld - grandTotalNew);
            }

            // Add a new row

            $('#add-row').click(function() {
                // Clone แถวแรกในตาราง
                const newRow = $('#quotation-table .item-row:first').clone();
                // ทำลาย Select2 ออกก่อนที่จะทำการ clone
                newRow.find('.product-select').select2('destroy');
                // รีเซ็ตค่าใน input และ select ใหม่
                newRow.find('input').val(0); // Clear all input values

                newRow.find('input[type="checkbox"]').prop('checked', false); // Uncheck all checkboxes
                // ลบ class ที่เกี่ยวข้องกับ Select2 ที่เหลืออยู่
                newRow.find('.select2').remove(); // Remove existing select2 container
                newRow.find('.product-select').removeClass(
                'select2-hidden-accessible'); // Remove select2-hidden-accessible class
                // Append แถวใหม่ไปที่ตาราง
                newRow.appendTo('#quotation-table');
                // Initialize select2 สำหรับ select element ใหม่
                newRow.find('.product-select').select2({
                    width: 'resolve' // ตั้งค่า width ให้กับ select2
                });
                // อัปเดตลำดับแถว (ถ้ามีฟังก์ชันนี้อยู่)
                updateRowNumbers();
            });





            // Remove row
            $('#quotation-table').on('click', '.remove-row-btn', function() {
                $(this).closest('.item-row').remove();
                updateRowNumbers();
                calculateTotals();
            });

            // Bind input changes and VAT method change to recalculate totals
            $('#quotation-table').on('input', '.quantity, .price-per-unit', calculateTotals);
            $('#quotation-table').on('change', '.non-vat, select[name="expense_type[]"]', calculateTotals);
            $('input[name="vat_type"]').change(calculateTotals);
            $('#withholding-tax').change(calculateTotals);

            // Initial calculation
            calculateTotals();


        });
    </script>


    <script>
        $(document).ready(function() {
            function checkPaymentCondition() {
                var travelDate = new Date($('#booking-date').val());
                var bookingDate = new Date($('#booking-create-date').val());
                // คำนวณจำนวนวันระหว่างวันจองและวันออกเดินทาง
                var diffDays = (travelDate - bookingDate) / (1000 * 60 * 60 * 24);
                // ตรวจสอบเงื่อนไข
                if (diffDays > 30) {
                    // เงื่อนไข 1: เลือกวิธีชำระเงินมัดจำ
                    $('#quote-payment-deposit').prop('checked', true);
                    $('#quote-payment-price').prop('disabled', false); // เปิดการใช้งาน dropdown

                } else {
                    // หากไม่เข้าเงื่อนไข 1: เลือกชำระเต็มจำนวน
                    $('#quote-payment-full').prop('checked', true);
                    $('#quote-payment-price').prop('disabled', true); // ปิดการใช้งาน dropdown
                }
            }

            function setPaymentDueDate() {
                var bookingCreateDate = new Date($('#booking-create-date').val());
                // เพิ่ม 1 วัน
                bookingCreateDate.setDate(bookingCreateDate.getDate() + 1);
                // ตั้งค่าเวลาเป็น 13:00 น.
                bookingCreateDate.setHours(13);
                bookingCreateDate.setMinutes(0);
                bookingCreateDate.setSeconds(0);
                bookingCreateDate.setMilliseconds(0);
                // สร้างฟังก์ชันเพื่อแปลงวันที่เป็นรูปแบบ YYYY-MM-DDTHH:MM
                var year = bookingCreateDate.getFullYear();
                var month = ('0' + (bookingCreateDate.getMonth() + 1)).slice(-2);
                var day = ('0' + bookingCreateDate.getDate()).slice(-2);
                var hours = ('0' + bookingCreateDate.getHours()).slice(-2);
                var minutes = ('0' + bookingCreateDate.getMinutes()).slice(-2);
                var formattedDate = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                // ตั้งค่าให้กับ input datetime-local
                $('input[name="quote_payment_date"]').val(formattedDate);
            }
            checkPaymentCondition();
            // ตั้งค่าฟิลด์ "ภายในวันที่" เมื่อโหลดหน้าเว็บ
            setPaymentDueDate();
            // ตรวจสอบเมื่อผู้ใช้เลือกชำระเงินเต็มจำนวน
            function checkedPaymentFull() {
                var QuoteTotalGrand = $('#quote-grand-total').val();
                if ($('#quote-payment-full').is(':checked')) {
                    $('#quote-payment-price').prop('disabled', true); // ปิด dropdown เรทเงินมัดจำ
                    $('.pax-total').val(QuoteTotalGrand);
                }
            }
            $('#quote-payment-full').on('change', function() {
                checkedPaymentFull();
            });
            //checkedPaymentFull();

            // ตรวจสอบเมื่อผู้ใช้เลือกชำระเงินมัดจำ
            $('#quote-payment-deposit').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#quote-payment-price').prop('disabled', false); // เปิด dropdown เรทเงินมัดจำ
                    $('#quote-payment-price').val(0.00);
                }

            });

            function calculatePaxAndTotal() {
                // ตรวจสอบว่าการชำระเงินเต็มจำนวนถูกเลือกหรือไม่
                if ($('#quote-payment-deposit').is(':checked')) {
                    // ตัวแปรเก็บผลรวมของ quantity
                    let totalQuantity = 0;
                    $('#quotation-table .item-row').each(function() {
                        // ดึง option ที่ถูกเลือกจาก select product_id[]
                        const selectedProduct = $(this).find('select[name="product_id[]"] option:selected');
                        var quantity = parseFloat($(this).find('.quantity').val()) || 0;
                        var isPax = selectedProduct.data('pax') === "Y";

                        // ถ้าเป็น Pax ให้รวมค่า quantity
                        if (isPax) {
                            totalQuantity += quantity;
                        }
                    });

                    // คำนวณยอด Pax โดยใช้ totalQuantity ที่รวมแล้ว
                    var paymentPrice = parseFloat($('#quote-payment-price').val()) || 0;
                    var paxTotal = totalQuantity * paymentPrice;

                    // อัพเดตยอด Pax ในทุกแถวที่มี Pax
                    $('#quotation-table .item-row').each(function() {
                        const selectedProduct = $(this).find('select[name="product_id[]"] option:selected');
                        var isPax = selectedProduct.data('pax') === "Y";

                        if (isPax) {
                            $('.pax-total').val(paxTotal.toFixed(2)); // อัพเดตยอด Pax
                        }
                    });
                } else {
                    // ถ้าไม่ได้เลือกชำระเต็มจำนวน ให้ล้างค่า Pax
                    $('#quotation-table .pax-total').val();
                }
            }

            // เรียกใช้ calculatePaxAndTotal เมื่อมีการเปลี่ยนแปลงใน quantity, product-select หรือ quote-payment-price
            $(document).on('change', '.quantity, .product-select, #quote-payment-price', function() {
                calculatePaxAndTotal();
            });

            // ตรวจสอบเมื่อมีการเปลี่ยนแปลงในการเลือกชำระเงิน
            $('#quote-payment-deposit').on('change', function() {
                if ($(this).is(':checked')) {
                    calculatePaxAndTotal(); // คำนวณยอด Pax เฉพาะเมื่อเลือกชำระเต็มจำนวน
                }
            });

            // เรียกใช้ฟังก์ชันเมื่อเริ่มต้น
            //calculatePaxAndTotal();
        });
    </script>



    <script>
        $(function() {
            // ตั้งค่าภาษาไทยให้กับ Datepicker
            $.datepicker.regional['th'] = {
                closeText: 'ปิด',
                prevText: 'ย้อน',
                nextText: 'ถัดไป',
                currentText: 'วันนี้',
                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                    'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                ],
                monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
                    'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
                ],
                dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
                dayNamesShort: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
                dayNamesMin: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
                weekHeader: 'Wk',
                dateFormat: 'dd MM yy', // รูปแบบการแสดงผลเป็น วัน เดือน ปี
                firstDay: 0,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['th']);

            // ฟังก์ชันแปลงวันที่จาก yyyy-mm-dd เป็นรูปแบบ dd MM yy
            function setThaiDate(inputSelector, date) {
                if (date) {
                    var formattedDate = $.datepicker.formatDate('dd MM yy', new Date(date));
                    $(inputSelector).datepicker('setDate', formattedDate); // แสดงผลใน input
                }
            }

            // ฟังก์ชันคำนวณวันกลับ
            function calculateEndDate() {
                var numDays = parseInt(document.querySelector('#numday option:checked').getAttribute('data-day')) ||
                    0;
                var startDate = $('#date-start').val();

                if (numDays > 0 && startDate) {
                    var start = new Date(startDate);
                    var endDate = new Date(start);
                    endDate.setDate(start.getDate() + numDays - 1); // คำนวณวันกลับตามจำนวนวันที่เลือก

                    // แปลงวันกลับเป็นภาษาไทยและแสดงใน input
                    $('#date-end-display').datepicker('setDate', endDate);
                    $('#date-end').val($.datepicker.formatDate('yy-mm-dd', endDate)); // ส่งค่าแบบ yyyy-mm-dd
                }
            }

            // ตั้งค่า Datepicker สำหรับวันเริ่มต้น
            $('#date-start-display').datepicker({
                dateFormat: 'dd MM yy', // รูปแบบแสดงผลเป็น วัน เดือน ปี
                onSelect: function(dateText) {
                    var isoDate = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker('getDate'));
                    $('#date-start').val(isoDate); // เก็บค่าวันที่ในรูปแบบ yyyy-mm-dd
                    calculateEndDate(); // คำนวณวันกลับทันทีเมื่อเลือกวันออกเดินทาง
                }
            });

            // ตั้งค่า Datepicker สำหรับวันกลับ (การแสดงผล)
            $('#date-end-display').datepicker({
                dateFormat: 'dd MM yy' // รูปแบบแสดงผลเป็น วัน เดือน ปี
            });

            // กำหนดให้คำนวณวันกลับเมื่อเปลี่ยนจำนวนวัน
            document.getElementById('numday').addEventListener('change', calculateEndDate);

            // ตรวจสอบและแสดงวันที่เริ่มต้นและวันกลับในรูปแบบภาษาไทยหากมีข้อมูล
            var startDate = $('#date-start').val();
            var endDate = $('#date-end').val();

            setThaiDate('#date-start-display', startDate);
            setThaiDate('#date-end-display', endDate);
        });
    </script>


    {{-- API TOUR --}}
    <script>
        $(document).ready(function() {
            $('#tourSearch').on('keypress', function(e) {
                if (e.which === 13) { // ตรวจจับการกดปุ่ม Enter (keyCode 13)
                    e.preventDefault(); // ป้องกันการ reload หน้าเมื่อกด Enter
                    var searchTerm = $(this).val();
                    if (searchTerm.length >= 2) {
                        $.ajax({
                            url: '{{ route('api.tours') }}', // URL สำหรับดึงข้อมูลทัวร์
                            method: 'GET',
                            data: {
                                search: searchTerm
                            },
                            success: function(data) {
                                $('#tourResults').empty();
                                if (data.length > 0) {
                                    $.each(data, function(index, item) {
                                        $('#tourResults').append(`
                                            <a href="#" class="list-group-item list-group-item-action" data-id="${item.id}">
                                                ${item.code} - ${item.name}
                                            </a>
                                        `);
                                    });
                                } else {
                                    $('#tourResults').append(
                                        '<a href="#" class="list-group-item list-group-item-action disabled">ไม่พบข้อมูล</a>'
                                    );
                                }
                            }
                        });
                    } else {
                        $('#tourResults').empty(); // ล้างผลลัพธ์เมื่อไม่มีคำค้นหา
                    }
                }
            });
            // เมื่อคลิกเลือกแพคเกจจากผลลัพธ์การค้นหา
            $(document).on('click', '#tourResults a', function(e) {
                e.preventDefault();
                var selectedId = $(this).data('id');
                var selectedText = $(this).text();
                $('#tourSearch').val(selectedText); // แสดงชื่อแพคเกจที่เลือกใน input
                $('#selectedTour').val(selectedId); // เก็บค่า id ใน hidden input
                $('#tourResults').empty(); // ล้างผลลัพธ์การค้นหา
            });
        });
    </script>



    <script>
        $(function() {
            // ตั้งค่าภาษาไทยให้กับ Datepicker
            $.datepicker.regional['th'] = {
                closeText: 'ปิด',
                prevText: 'ย้อน',
                nextText: 'ถัดไป',
                currentText: 'วันนี้',
                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                    'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                ],
                monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
                    'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
                ],
                dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
                dayNamesShort: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
                dayNamesMin: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
                weekHeader: 'Wk',
                dateFormat: 'dd MM yy', // รูปแบบการแสดงผลเป็นวัน เดือน ปี
                firstDay: 0,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['th']);

            // แปลงวันที่จากรูปแบบ Y-m-d เป็นรูปแบบภาษาไทย
            function formatDateToThai(dateString) {
                const date = new Date(dateString);
                return $.datepicker.formatDate('dd MM yy', date, $.datepicker.regional['th']);
            }

            // ตั้งค่า Datepicker ให้แสดงผลภาษาไทยและจัดการเมื่อเลือกวันที่
            $('#displayDatepicker').datepicker({
                dateFormat: 'dd MM yy', // รูปแบบการแสดงผลใน Datepicker
                onSelect: function(dateText, inst) {
                    // แปลงวันที่ที่เลือกเป็นรูปแบบ Y-m-d และอัพเดต hidden input
                    const selectedDate = new Date(inst.selectedYear, inst.selectedMonth, inst
                        .selectedDay);
                    const isoDate = $.datepicker.formatDate('yy-mm-dd', selectedDate);
                    $('#submitDatepicker').val(isoDate);
                }
            });

            // กำหนดค่าเริ่มต้นให้กับ Datepicker (แสดงเป็นภาษาไทย) และ hidden input
            let defaultDate = '{{ date('Y-m-d', strtotime($bookingModel->created_at)) }}';
            $('#submitDatepicker').val(defaultDate);
            const thaiFormattedDate = formatDateToThai(defaultDate);
            $('#displayDatepicker').val(thaiFormattedDate);
        });
    </script>
@endsection
