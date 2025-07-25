@extends('layouts.template')
@section('content')
<div class="container-fluid page-content">
    <div class="todo-listing">
        <div class="container border bg-white">
            <h4 class="text-center my-4">สร้างใบเสนอราคา (UI ปรับปรุง)</h4>
            <hr>
            <form action="{{ route('quote.store') }}" id="formQuoteModern" method="post">
                @csrf
                <!-- SECTION: ข้อมูลทั่วไป -->
                <div class="row table-custom ">
                    <div class="col-md-2 ms-auto">
                        <label><b>เซลล์ผู้ขายแพคเกจ:</b></label>
                        <select name="quote_sale" class="form-select select2" required>
                            @forelse ($sales as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @empty
                                <option value="">--Select Sale--</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-2 ms-3">
                        <label>วันที่สั่งซื้อ,จองแพคเกจ:</label>
                        <input type="date" id="displayDatepicker" class="form-control" required value="{{ date('Y-m-d') }}">
                        <input type="hidden" id="submitDatepicker" name="quote_booking_create">
                        <input type="hidden" id="quote-date" name="quote_booking_create">
                    </div>
                    <div class="col-md-2">
                        <label>เลขที่ใบเสนอราคา</label>
                        <input type="text" class="form-control" placeholder="???????" disabled>
                    </div>
                    <div class="col-md-2">
                        <label>วันที่เสนอราคา</label>
                        <input type="date" id="displayDatepickerQuoteDate" class="form-control" required value="{{ date('Y-m-d') }}">
                        <input type="hidden" id="submitDatepickerQuoteDate" name="quote_date" class="form-control">
                    </div>
                </div>
                <hr>
                <h5 class="text-danger">รายละเอียดแพคเกจทัวร์:</h5>
                <div class="row table-custom">
                    <div class="col-md-6 position-relative">
                        <label>ชื่อแพคเกจทัวร์:</label>
                        <input type="text" id="tourSearch" class="form-control" name="quote_tour_name" placeholder="ค้นหาแพคเกจทัวร์...ENTER เพื่อค้นหา" required autocomplete="off">
                        <button type="button" id="resetTourSearch" class="btn btn-link btn-sm position-absolute end-0 top-0" style="z-index:1100;right:10px;top:30px"><i class="fa fa-times"></i></button>
                        <div id="tourResults" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                    </div>
                    <input type="hidden" id="tourSearch1" class="form-control" name="quote_tour_name1">
                    <input type="hidden" id="tour-code" name="quote_tour">
                    <input type="hidden" id="tour-id">
                    <div class="col-md-3">
                        <label>ระยะเวลาทัวร์ (วัน/คืน): </label>
                        <select name="quote_numday" class="form-select" id="numday" required>
                            <option value="">--เลือกระยะเวลา--</option>
                            @forelse ($numDays as $item)
                                <option data-day="{{ $item->num_day_total }}" value="{{ $item->num_day_name }}">{{ $item->num_day_name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>ประเทศที่เดินทาง: </label>
                        <select name="quote_country" class="form-select select2" id="country" style="width: 100%" required>
                            <option value="">--เลือกประเทศที่เดินทาง--</option>
                            @forelse ($country as $item)
                                <option value="{{ $item->id }}">{{ $item->iso2 }}-{{ $item->country_name_th }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="row table-custom">
                    <div class="col-md-3">
                        <label>โฮลเซลล์: </label>
                        <select name="quote_wholesale" class="form-select select2" style="width: 100%" id="wholesale" required>
                            <option value="">--เลือกโฮลเซลล์--</option>
                            @forelse ($wholesale as $item)
                                <option value="{{ $item->id }}">{{ $item->code }}-{{ $item->wholesale_name_th }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>สายการบิน:</label>
                        <select name="quote_airline" class="form-select select2" style="width: 100%" id="airline" required>
                            <option value="">--เลือกสายการบิน--</option>
                            @forelse ($airline as $item)
                                <option value="{{ $item->id }}">{{ $item->code }}-{{ $item->travel_name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3 position-relative">
                        <label>วันออกเดินทาง: <a href="#" class="" id="list-period">เลือกวันที่</a></label>
                        <input type="date" class="form-control" id="date-start-display" placeholder="วันออกเดินทาง..." required autocomplete="off">
                        <div id="date-list" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                        <input type="hidden" id="period1" name="period1">
                        <input type="hidden" id="period2" name="period2">
                        <input type="hidden" id="period3" name="period3">
                        <input type="hidden" id="period4" name="period4">
                        <input type="hidden" id="date-start" name="quote_date_start">
                    </div>
                    <div class="col-md-3">
                        <label>วันเดินทางกลับ: </label>
                        <input type="date" class="form-control" id="date-end-display" placeholder="วันเดินทางกลับ..." required>
                        <input type="hidden" id="date-end" name="quote_date_end">
                    </div>
                </div>
                <hr>
                <h5 class="text-danger">ข้อมูลลูกค้า:</h5>
                <div class="row table-custom">
                    <div class="col-md-3 position-relative">
                        <label class="">ชื่อลูกค้า:</label>
                        <input type="text" class="form-control" name="customer_name" id="customerSearch" placeholder="ชื่อลูกค้า...ENTER เพื่อค้นหา" required aria-describedby="basic-addon1" autocomplete="off">
                        <div id="customerResults" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                    </div>
                    <input type="hidden" id="customer-id" name="customer_id">
                    <input type="hidden" id="customer-new" name="customer_type_new" value="customerNew">
                    <div class="col-md-3">
                        <label>อีเมล์:</label>
                        <input type="email" class="form-control" name="customer_email" placeholder="Email" aria-describedby="basic-addon1" id="customer_email">
                    </div>
                    <div class="col-md-3">
                        <label>เลขผู้เสียภาษี:</label>
                        <input type="text" id="texid" class="form-control" name="customer_texid" mix="13" placeholder="เลขประจำตัวผู้เสียภาษี" aria-describedby="basic-addon1">
                    </div>
                    <div class="col-md-3">
                        <label>เบอร์โทรศัพท์ :</label>
                        <input type="text" class="form-control" name="customer_tel" id="customer_tel" placeholder="เบอร์โทรศัพท์" aria-describedby="basic-addon1">
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="col-md-12">
                                    <label>เบอร์โทรสาร :</label>
                                    <input type="text" class="form-control" id="fax" name="customer_fax" placeholder="เบอร์โทรศัพท์" aria-describedby="basic-addon1">
                                </div>
                                <div class="col-md-12">
                                    <label>ลูกค้าจาก :</label>
                                    <select name="customer_campaign_source" class="form-select">
                                        @forelse ($campaignSource as $item)
                                            <option value="{{ $item->campaign_source_id }}">{{ $item->campaign_source_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Social id</label>
                                    <input type="text" class="form-control" name="customer_social_id" placeholder="Social id">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="col-md-12">
                                    <label>ที่อยู่:</label>
                                    <textarea name="customer_address" class="form-control" id="customer_address" cols="30" rows="7" placeholder="ที่อยู่"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <h5 style="background: #e0e0e0; padding: 5px">ข้อมูลค่าบริการ <span id="pax" class="float-end"></span></h5>
                <hr>
                <div id="quotation-table" class="table-custom text-center">
                    <div class="row header-row" style="padding: 5px">
                        <div class="col-md-1">ลำดับ</div>
                        <div class="col-md-3">รายการสินค้า</div>
                      
                        <div class="col-md-1">รวม 3%</div>
                        <div class="col-md-1">NonVat</div>
                        <div class="col-md-1">จำนวน</div>
                        <div class="col-md-1">ราคา/หน่วย</div>
                        <div class="col-md-2">ยอดรวม</div>
                    </div>
                    <hr>
                    @php $key = 0; @endphp
                    <div class="row item-row table-income" id="table-income">
                        <div class="row">
                            <div class="col-md-1"><span class="row-number"> {{ $key + 1 }}</span> <a href="javascript:void(0)" class="remove-row-btn text-danger"><span class="fa fa-trash"></span></a></div>
                            <div class="col-md-3">
                                <select name="product_id[]" class="form-select product-select select2" id="product-select" style="width: 100%;">
                                    <option value="">--เลือกสินค้า--</option>
                                    @forelse ($products as $product)
                                        <option data-pax="{{ $product->product_pax }}" value="{{ $product->id }}">{{ $product->product_name }}{{ $product->product_pax === 'Y' ? '(Pax)' : '' }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <!-- dropdown ประเภท (รายได้/ส่วนลด) ซ่อนแบบต้นฉบับ -->
                            <div class="col-md-1" style="display: none">
                                <select name="expense_type[]" class="form-select">
                                    <option selected value="income"> รายได้ </option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" name="withholding_tax[]" class="vat-3" value="Y">
                            </div>
                            <div class="col-md-1 text-center">
                                <select name="vat_status[]" class="vat-status form-select" style="width: 110%;">
                                    <option selected value="nonvat">nonVat</option>
                                    <option value="vat">Vat</option>
                                </select>
                            </div>
                            <div class="col-md-1"><input type="number" name="quantity[]" class="quantity form-control text-end" step="1" value="1"></div>
                            <div class="col-md-1"><input type="number" name="price_per_unit[]" class="price-per-unit form-control text-end" step="0.01" value="0"></div>
                            <div class="col-md-2"><input type="number" name="total_amount[]" class="total-amount form-control text-end" value="0" readonly></div>
                        </div>
                    </div>
                    <div class="add-row">
                        <i class="fa fa-plus"></i><a id="add-row-service" href="javascript:void(0)" class=""> เพิ่มรายการ</a>
                    </div>
                    <hr>
                    <div class="add-row">
                        <i class="fa fa-plus"></i><a id="add-row-discount" href="javascript:void(0)" class=""> เพิ่มส่วนลด</a>
                    </div>
                    <div class="row item-row table-discount">
                        <div class="col-md-12" style="text-align: left">
                            <label class="text-danger">ส่วนลด</label>
                            <div id="discount-list">
                                <!-- Discount rows will be rendered here by JS -->
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vat-method">การคำนวณ VAT:</label>
                                    <div>
                                        <input type="radio" id="vat-include" name="vat_type" value="include" checked>
                                        <label for="vat-include">คำนวณรวมกับราคาสินค้าและบริการ (VAT Include)</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="vat-exclude" name="vat_type" value="exclude">
                                        <label for="vat-exclude">คำนวณแยกกับราคาสินค้าและบริการ (VAT Exclude)</label>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="row summary-row">
                                    <div class="col-md-10">
                                        <input type="checkbox" name="quote_withholding_tax_status" value="Y" id="withholding-tax"> <span class="">คิดภาษีหัก ณ ที่จ่าย 3% (คำนวณจากยอด ราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT Amount)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md012">
                                <label>จำนวนเงินภาษีหัก ณ ที่จ่าย 3% : &nbsp;</label><span class="text-danger" id="withholding-amount"> 0.00</span> บาท
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
                                    <div class="col-md-10 text-end">ยอดรวมยกเว้นภาษี / Vat-Exempted Amount</div>
                                    <div class="col-md-2 text-end"><span id="sum-total-nonvat">0.00</span></div>
                                </div>
                                <div class="row summary-row ">
                                    <div class="col-md-10 text-end">ราคาสุทธิสินค้าที่เสียภาษี / Pre-Tax Amount:</div>
                                    <div class="col-md-2 text-end"><span id="sum-total-vat">0.00</span></div>
                                </div>
                                <div class="row summary-row">
                                    <div class="col-md-10 text-end">ส่วนลด / Discount :</div>
                                    <div class="col-md-2 text-end"><span id="sum-discount">0.00</span></div>
                                </div>
                                <div class="row summary-row">
                                    <div class="col-md-10 text-end">ราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT Amount:</div>
                                    <div class="col-md-2 text-end"><span id="sum-pre-vat">0.00</span></div>
                                </div>
                                <div class="row summary-row">
                                    <div class="col-md-10 text-end">ภาษีมูลค่าเพิ่ม VAT : 7%:</div>
                                    <div class="col-md-2 text-end"><span id="vat-amount">0.00</span></div>
                                </div>
                                <div class="row summary-row ">
                                    <div class="col-md-10 text-end">ราคารวมภาษีมูลค่าเพิ่ม / Include VAT:</div>
                                    <div class="col-md-2 text-end"><span id="sum-include-vat">0.00</span></div>
                                </div>
                                <div class="row summary-row">
                                    <div class="col-md-10 text-end">จำนวนเงินรวมทั้งสิ้น / Grand Total:</div>
                                    <div class="col-md-2 text-end"><b><span class="bg-warning" id="grand-total">0.00</span></b></div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h5>เงือนไขการชำระเงิน</h5>
                    </div>
                    <div class="col-md-12 ">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="radio" name="quote_payment_type" id="quote-payment-deposit" value="deposit"> <label for="quote-payment-type"> เงินมัดจำ </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">ภายในวันที่</span>
                                        <input type="datetime-local" class="form-control" name="quote_payment_date" id="quote-payment-date" value="">
                                        <input type="datetime-local" class="form-control" name="quote_payment_date" id="quote-payment-date-new" value="" style="display: none" >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" for="">เรทเงินมัดจำ</span>
                                        <select name="quote_payment_price" class="form-select" id="quote-payment-price">
                                            <option value="0">0.00</option>
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
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" for="">ชำระเพิ่มเติม</span>
                                        <input type="number" id="pay-extra" class="form-control" name="quote_payment_extra" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" for="">จำนวนเงินที่ต้องชำระ</span>
                                        <input type="number" class="form-control pax-total" name="quote_payment_total" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="radio" name="quote_payment_type" id="quote-payment-full" value="full"> <label for="quote-payment-type"> ชำระเต็มจำนวน</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">ภายในวันที่</span>
                                        <input type="datetime-local" class="form-control" id="quote-payment-date-full" name="quote_payment_date_full" value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" for="">จำนวนเงิน</span>
                                        <input type="number" class="form-control" name="quote_payment_total_full" id="payment-total-full" step="0.01" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="booking-create-date" value="{{ date('Y-m-d') }}">
                </div>
                <div class="text-end mt-3">
                    <input type="hidden" name="quote_vat_exempted_amount">
                    <input type="hidden" name="quote_pre_tax_amount">
                    <input type="hidden" name="quote_discount">
                    <input type="hidden" name="quote_pre_vat_amount">
                    <input type="hidden" name="quote_vat">
                    <input type="hidden" name="quote_include_vat">
                    <input type="hidden" name="quote_grand_total" id="quote-grand-total">
                    <input type="hidden" name="quote_withholding_tax">
                    <input type="hidden" name="quote_pax_total" id="quote-pax-total">
                    <button type="submit" class="btn btn-primary btn-sm mx-3" form="formQuoteModern"><i class="fa fa-save"></i> สร้างใบเสนอราคา</button>
                </div>
                <br>
            </form>
        </div>
        <br>
    </div>
</div>

<script>
$(function() {
    // trigger คำนวณใหม่เมื่อเปลี่ยน VAT Include/Exclude
    $('input[name="vat_type"]').on('change', function() {
        calculatePaymentCondition();
    });
    // trigger คำนวณ withholding ทันทีเมื่อเปลี่ยน checkbox สรุป
    $('#withholding-tax').on('change', function() {
        calculatePaymentCondition();
    });
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
            $('#date-start').val(dateObject.toISOString().slice(0,10));
            // คำนวณวันเดินทางกลับ
            var numDays = parseInt($('#numday option:selected').data('day')) || 0;
            if (numDays > 0) {
                var endDate = new Date(dateObject);
                endDate.setDate(dateObject.getDate() + numDays - 1);
                $('#date-end').val(endDate.toISOString().slice(0,10));
                $('#date-end-display').val(endDate.toISOString().slice(0,10));
            }
            // ตรวจสอบวันออกเดินทางต้องไม่ย้อนหลังจากวันนี้
            var today = new Date();
            today.setHours(0,0,0,0);
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
            $('#date-end').val(endDate.toISOString().slice(0,10));
            // คำนวณวันออกเดินทางย้อนหลัง
            var numDays = parseInt($('#numday option:selected').data('day')) || 0;
            if (numDays > 0) {
                var startDate = new Date(endDate);
                startDate.setDate(endDate.getDate() - numDays + 1);
                // ตรวจสอบวันออกเดินทางต้องไม่ย้อนหลังจากวันนี้
                var today = new Date();
                today.setHours(0,0,0,0);
                if (startDate < today) {
                    alert('วันออกเดินทางต้องไม่ย้อนหลังจากวันปัจจุบัน');
                }
                $('#date-start').val(startDate.toISOString().slice(0,10));
                $('#date-start-display').val(startDate.toISOString().slice(0,10));
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
                $('#date-end').val(endDate.toISOString().slice(0,10));
                $('#date-end-display').val(endDate.toISOString().slice(0,10));
            }
        }
    });
    // ฟังก์ชันคำนวณเงื่อนไขการชำระเงิน (Deposit/Full) และข้อมูลค่าบริการ (pax, รวม, vat, discount, grand total)
    function calculatePaymentCondition() {
        // --- เงื่อนไขการชำระเงิน ---
        var bookingCreateDate = new Date($('#date-start').val());
        var travelDate = new Date($('#date-start').val());
        var dateNow = new Date();
        var bookingDate = new Date($('#booking-create-date').val());
        var diffDays = (travelDate - bookingDate) / (1000 * 60 * 60 * 24);
        if(diffDays >= 31) {
            bookingCreateDate.setDate(bookingCreateDate.getDate() - 30);
            $('#quote-payment-deposit').prop('checked', true);
        } else {
            bookingCreateDate = new Date();
            bookingCreateDate.setDate(dateNow.getDate() + 1);
            $('#quote-payment-full').prop('checked', true);
        }
        bookingCreateDate.setHours(13,0,0,0);
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

        // คำนวณแต่ละแถวสินค้า (เฉพาะรายได้)
        $('#table-income .row').each(function() {
            var $row = $(this);
            var qty = parseFloat($row.find('.quantity').val()) || 0;
            var price = parseFloat($row.find('.price-per-unit').val()) || 0;
            var isVat = $row.find('.vat-status').val() === 'vat';
            var isPax = $row.find('.product-select option:selected').data('pax') === 'Y';
            var isWithholding = $row.find('.vat-3').is(':checked');
            var rowTotal = qty * price;
            var withholding = 0;
            // รวม 3% (ถ้ามี) เฉพาะแสดงผล ไม่เกี่ยวกับภาษีหัก ณ ที่จ่าย
            if (isWithholding) {
                var plus3 = rowTotal * 0.03;
                $row.find('.total-amount').val((rowTotal + plus3).toFixed(2));
                rowTotal = rowTotal + plus3;
            } else {
                $row.find('.total-amount').val(rowTotal.toFixed(2));
            }
            if (isVat) {
                sumTotalVat += rowTotal;
            } else {
                sumTotalNonVat += rowTotal;
            }
            if (isPax) {
                paxTotal += qty;
            }
        });

        // ส่วนลด (discount-list)
        sumDiscount = 0;
        $('#discount-list .discount-row').each(function() {
            var $row = $(this);
            var qty = parseFloat($row.find('.discount-qty').val()) || 0;
            var price = parseFloat($row.find('.discount-price').val()) || 0;
            var total = qty * price;
            $row.find('.discount-total').val(total.toFixed(2));
            sumDiscount += total;
        });

        // --- VAT Calculation ---
        var vatType = $('input[name="vat_type"]:checked').val();
        if (vatType === 'include') {
            // VAT Include: ราคาสินค้า/บริการรวม VAT แล้ว
            sumPreVat = sumTotalVat / (1 + vatRate); // ราคาก่อน VAT เฉพาะแถวที่เลือก Vat
            sumVat = sumTotalVat - sumPreVat; // VAT เฉพาะแถวที่เลือก Vat
            sumIncludeVat = sumTotalVat; // รวม VAT เฉพาะแถวที่เลือก Vat
            // grand total = (nonvat + vat รวม) - discount
            grandTotal = sumTotalNonVat + sumIncludeVat - sumDiscount;
        } else {
            // VAT Exclude: ราคาสินค้า/บริการยังไม่รวม VAT
            sumPreVat = sumTotalVat; // ราคาก่อน VAT เฉพาะแถวที่เลือก Vat
            sumVat = sumPreVat * vatRate; // VAT เฉพาะแถวที่เลือก Vat
            sumIncludeVat = sumPreVat + sumVat; // รวม VAT เฉพาะแถวที่เลือก Vat
            // grand total = (nonvat + vat รวม + vat) - discount
            grandTotal = sumTotalNonVat + sumIncludeVat - sumDiscount;
        }

        // withholding tax 3% รวมทุกแถวที่ติ๊ก (เฉพาะรายได้)
        // คำนวณภาษีหัก ณ ที่จ่าย 3% (คิดจากยอดรวมเฉพาะรายการที่เลือก Vat เท่านั้น)
        withholdingAmount = 0;
        if ($('#withholding-tax').is(':checked')) {
            // รวมยอดเฉพาะแถวที่เลือก Vat (คิดจากยอดก่อน vat)
            var sumVatRows = 0;
            $('#table-income .row').each(function() {
                var $row = $(this);
                var isVat = $row.find('.vat-status').val() === 'vat';
                var qty = parseFloat($row.find('.quantity').val()) || 0;
                var price = parseFloat($row.find('.price-per-unit').val()) || 0;
                var isWithholding = $row.find('.vat-3').is(':checked');
                var rowTotal = qty * price;
                if (isVat) {
                    if (vatType === 'include') {
                        // ถ้าเป็น include ต้องใช้ยอดก่อน VAT
                        sumVatRows += rowTotal / (1 + vatRate);
                    } else {
                        sumVatRows += rowTotal;
                    }
                }
            });
            withholdingAmount = sumVatRows * 0.03;
        }
        // อัปเดตแสดงผลทันทีเมื่อเปลี่ยน checkbox
        $('#withholding-amount').text(withholdingAmount.toFixed(2));

        // set ค่า summary
        $('#sum-total-nonvat').text(sumTotalNonVat.toFixed(2));
        $('#sum-total-vat').text(sumTotalVat.toFixed(2));
        $('#sum-discount').text(sumDiscount.toFixed(2));
        $('#sum-pre-vat').text(sumPreVat.toFixed(2));
        $('#vat-amount').text(sumVat.toFixed(2));
        $('#sum-include-vat').text(sumIncludeVat.toFixed(2));
        $('#grand-total').text(grandTotal.toFixed(2));
        $('#withholding-amount').text(withholdingAmount.toFixed(2));
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
    }

    // trigger คำนวณค่าบริการทุกครั้งที่มีการเปลี่ยนแปลง
    $(document).on('input change', '.quantity, .price-per-unit, .vat-status, .vat-3, .expense-type', function() {
        calculatePaymentCondition();
    });


    // เพิ่มรายการบริการ (row)
    $('#add-row-service').on('click', function() {
        var $lastRow = $('#table-income > .row').last();
        var $newRow = $lastRow.clone(false, false); // clone เฉพาะโครงสร้าง ไม่เอา event เดิม
        // สุ่ม id ใหม่ให้ select2 และ input ที่จำเป็น (ป้องกัน id ซ้ำ)
        $newRow.find('select, input').each(function() {
            var $el = $(this);
            if ($el.attr('id')) {
                $el.attr('id', $el.attr('id') + '_' + Date.now());
            }
        });
        // ล้างค่า input/select ในแถวใหม่
        $newRow.find('input, select').each(function() {
            if ($(this).is('select')) {
                $(this).val('');
            } else if ($(this).is(':checkbox')) {
                $(this).prop('checked', false);
            } else if ($(this).hasClass('quantity')) {
                $(this).val(1);
            } else if ($(this).hasClass('price-per-unit')) {
                $(this).val(0);
            } else if ($(this).hasClass('total-amount')) {
                $(this).val(0);
            } else {
                $(this).val('');
            }
        });
        $newRow.find('.remove-row-btn').show();
        // รีเซ็ต select2 เฉพาะแถวใหม่ (ถ้าใช้ select2)
        $newRow.find('select.select2').removeClass('select2-hidden-accessible').next('.select2').remove();
        // เพิ่มแถวใหม่
        $('#table-income').append($newRow);
        // รีอินิท select2 เฉพาะแถวใหม่
        $newRow.find('select.select2').select2({width:'100%'});
        updateRowNumbers();
        calculatePaymentCondition();
    });

    // ลบรายการบริการ
    $(document).on('click', '.remove-row-btn', function() {
        if ($('#table-income .row').length > 1) {
            $(this).closest('.row').remove();
            updateRowNumbers();
            calculatePaymentCondition();
        }
    });

    // เพิ่มส่วนลด
    $('#add-row-discount').on('click', function() {
        addDiscountRow();
        updateDiscountRowNumbers();
        calculatePaymentCondition();
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
        var total = qty * price;
        var rowHtml = `
            <div class="row discount-row mb-2" data-row-id="${rowId}">
                <div class="col-md-1 discount-row-number">${rowCount}</div>
                <div class="col-md-3">
                    <select class="form-select discount-product-select select2" name="discount_product_id[]" style="width:100%">
                        <option value="">--เลือกส่วนลด--</option>
                        @foreach ($productDiscount as $product)
                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <input type="number" class="form-control discount-qty" name="discount_qty[]" min="1" value="${qty}">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control discount-price" name="discount_price[]" step="0.01" value="${price}">
                </div>
                <div class="col-md-2">
                    <select class="form-select discount-vat" name="discount_vat[]">
                        <option value="nonvat" ${vat==='nonvat'?'selected':''}>nonVat</option>
                        <option value="vat" ${vat==='vat'?'selected':''}>Vat</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control discount-total" name="discount_total[]" value="${total.toFixed(2)}" readonly>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-discount-row"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        `;
        $('#discount-list').append(rowHtml);
        // init select2 เฉพาะแถวใหม่ (ใช้ element ที่ render จริง)
        var $select = $('#discount-list .discount-row:last .discount-product-select');
        $select.select2({
            width: '100%',
            dropdownParent: $('#discount-list'),
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
    });

    // อัปเดตเลขลำดับ discount row
    function updateDiscountRowNumbers() {
        $('#discount-list .discount-row-number').each(function(i) {
            $(this).text(i + 1);
        });
    }

    // trigger คำนวณเมื่อเปลี่ยน discount row
    $(document).on('input change', '.discount-qty, .discount-price, .discount-vat, .discount-product-select', function() {
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
                data: { search: searchTerm },
                success: function(data) {
                    $('#tourResults').empty();
                    if (data.length > 0) {
                        // limit แค่ 5 รายการ
                        var limited = data.slice(0, 5);
                        $.each(limited, function(index, item) {
                            $('#tourResults').append(`<a href="#" id="tour-select" class="list-group-item list-group-item-action" data-tour="${item.id}" data-numday="${item.num_day}" data-airline="${item.airline_id}" data-wholesale="${item.wholesale_id}" data-code="${item.code}" data-name1="${item.code} - ${item.name}" data-name="${item.code} - ${item.code1} - ${item.name}">${item.code} - ${item.code1} - ${item.name}</a>`);
                        });
                    }
                    // เพิ่มตัวเลือก "กำหนดเอง"
                    $('#tourResults').append(`<a href="#" class="list-group-item list-group-item-action" data-name="${searchTerm}">กำหนดเอง</a>`);
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
                data: { search: selectedWholesale },
                success: function(data) {
                    if (data) {
                        if (!$('#wholesale option[value="' + data.id + '"]').length) {
                            $('#wholesale').append(`<option value="${data.id}">${data.wholesale_name_th}</option>`);
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
                data: { search: selectedCode },
                success: function(data) {
                    if (data) {
                        if (!$('#country option[value="' + data.id + '"]').length) {
                            $('#country').append(`<option value="${data.id}">${data.country_name_th}</option>`);
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
                data: { search: selectedTour },
                success: function(data) {
                    $('#date-list').empty();
                    var now = new Date();
                    if (Array.isArray(data) && data.length > 0) {
                        $.each(data, function(index, period) {
                            var dateObject = new Date(period.start_date);
                            // เฉพาะวันที่มากกว่าหรือเท่ากับวันนี้
                            if (dateObject > now) {
                                var dateText = dateObject.toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' });
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
        // แปลงวันที่เป็นไทย
        var dateObject = new Date(selectedDate);
        var thaiFormattedDate = dateObject.toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' });
        $('#date-start-display').val(thaiFormattedDate);
        $('#date-start').val(selectedDate);
        $('#date-list').empty();
        // คำนวณวันเดินทางกลับ
        var numDays = parseInt($('#numday option:selected').data('day')) || 0;
        if (numDays > 0 && selectedDate) {
            var start = new Date(selectedDate);
            var endDate = new Date(start);
            endDate.setDate(start.getDate() + numDays - 1);
            var thaiFormattedEndDate = endDate.toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' });
            $('#date-end-display').val(thaiFormattedEndDate);
            $('#date-end').val(endDate.toISOString().slice(0,10));
        }
        // เรียกฟังก์ชันคำนวณเงื่อนไขการชำระเงิน
        calculatePaymentCondition();
    });

    // ลบ logic/handler สำหรับปุ่มหรือข้อความ "ระบุวันเดินทางเอง" (ไม่ต้องมีอีกต่อไป)
    // ปิดผลลัพธ์เมื่อคลิกนอก
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#tourResults, #tourSearch').length) {
            $('#tourResults').empty();
        }
    });
});
</script>
@endsection
