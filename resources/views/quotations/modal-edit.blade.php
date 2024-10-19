<style>
    .form-control,
    .form-select,
    .input-group-text {
        height: 29px;
        font-size: 12px
    }

    .select2-selection {
        height: 30px !important;
        text-align: left;
        z-index: 1000000;
        height: 26px;
        font-size: 12px
    }

    .select2-selection__rendered {
        line-height: 31px !important;
    }


    #tourResults {
        position: absolute;
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
        width: 48%;
    }

    .add-row {
        margin: 10px 0;
        text-align: left;
    }

    .table-custom input,
    .table-custom select {
        width: 150%;
        padding: 3px;
        margin-bottom: 10px;
    }
    .selectpicker-select {
    text-align: left; /* ทำให้ข้อความภายใน select ชิดซ้าย */
    padding-left: 0px; /* ลด padding ด้านซ้าย */
    margin-left: 0px; /* ลดระยะห่างด้านซ้าย */
    width: 100%; /* เพิ่มความกว้างให้เต็มคอลัมน์ */
}

.bootstrap-select .dropdown-toggle {
    text-align: left; /* ทำให้ปุ่ม dropdown ของ selectpicker ชิดซ้าย */
    width: 100%; /* ให้ dropdown กว้างเต็มคอลัมน์ */
    padding-left: 0px; /* ลด padding ด้านซ้าย */
    margin-left: 0px; /* ลด margin ด้านซ้าย */
}
.quantity {
 width: 10%;     
}
</style>
<!-- Bootstrap CSS -->





<div class="modal-body">
    <form action="{{ route('quote.update', $quotationModel->quote_id) }}" id="formQuote" method="post">
        @csrf
        @method('PUT')
        {{-- รายละเอียดใบเสนอราคา --}}
        <fieldset class="border p-2">
            <legend class="float-none w-auto text-danger" style="font-size: 15px"><span>รายละเอียดใบเสนอราคา</span>
            </legend>

            <div class="row" style="font-size: 12px ">
                <div class="col-md-2 ms-auto">
                    <label><b>เซลล์ผู้ขายแพคเกจ:</b> {{ $quotationModel->quote_sale }}</label>
                    <select name="quote_sale" class="form-select">
                        @forelse ($sales as $item)
                            <option @if ($quotationModel->quote_sale === $item->id) selected @endif value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @empty
                            <option value="">--Select Sale--</option>
                        @endforelse
                    </select>
                </div>

                <div class="col-md-2">
                    <label>วันที่เสนอราคา</label>
                    <input type="text" id="displayDatepickerQuoteDate" class="form-control">

                    <input type="hidden" id="submitDatepickerQuoteDate" name="quote_date"
                        value="{{ $quotationModel->quote_date }}" class="form-control">
                </div>


                <div class="col-md-2 ms-3">
                    <label>วันที่สั่งซื้อ,จองแพคเกจ:</label>
                    <input type="text" id="displayDatepicker" class="form-control">
                    <input type="hidden" id="submitDatepicker" name="quote_booking_create"
                        value="{{ date('Y-m-d', strtotime($quotationModel->quote_booking_create)) }}">
                </div>

                <input type="hidden" id="tour-id">

                <div class="col-md-2">
                    <label>เลขที่ใบจองทัวร์</label>
                    <input type="text" name="quote_booking" value="{{ $quotationModel->quote_booking }}"
                        class="form-control" readonly>
                </div>
                <div class="col-md-2">
                    <label>รหัสทัวร์</label>
                    @if ($quotationModel->quote_tour)
                        <input type="text" name="quote_tour" value="{{ $quotationModel->quote_tour }}"
                            class="form-control" readonly>
                    @else
                        <input type="text" name="quote_tour_code_old" value="{{ $quotationModel->quote_tour_code }}"
                            class="form-control" readonly>
                    @endif
                </div>
            </div>
        </fieldset>
        {{-- รายละเอียดแพคเกจทัวร์ --}}
        <fieldset class="border p-2">
            <legend class="float-none w-auto text-danger" style="font-size: 15px"><span>รายละเอียดแพคเกจทัวร์</span>
            </legend>
            <div class="row">
                <div class="col-md-6">
                    <label>ชื่อแพคเกจทัวร์:</label>
                    <input type="text" id="tourSearch" class="form-control" name="quote_tour_name"
                        placeholder="ค้นหาแพคเกจทัวร์...ENTER เพื่อค้นหา"
                        value="{{ $quotationModel->quote_tour_name }}">
                    <div id="tourResults" class="list-group" style="">
                    </div>
                </div>

                <input type="hidden" id="tour-code" name="quote_tour" value="{{ $quotationModel->quote_tour }}">
                <input type="hidden" id="tourSearch1" class="form-control" name="quote_tour_name1">

                <div class="col-md-3">
                    <label>ระยะเวลาทัวร์ (วัน/คืน): </label>
                    <select name="quote_numday" class="form-select" id="numday">
                        <option value="">--เลือกระยะเวลา--</option>
                        @forelse ($numDays as $item)
                            <option @if ($quotationModel->quote_numday === $item->num_day_name) selected @endif
                                data-day="{{ $item->num_day_total }}" value="{{ $item->num_day_name }}">
                                {{ $item->num_day_name }}</option>
                        @empty
                        @endforelse

                    </select>

                </div>


                <div class="col-md-3">
                    <label>ประเทศที่เดินทาง: {{ $quotationModel->quote_country }}</label>
                    <select name="quote_country" class="form-select country-select select" id="country"
                        style="width: 100%">
                        <option value="">--เลือกประเทศที่เดินทาง--</option>
                        @forelse ($country as $item)
                            <option @if ($item->id === $quotationModel->quote_country) selected @endif value="{{ $item->id }}">
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
                    <select name="quote_wholesale" class="form-select country-select select" style="width: 100%"
                        id="wholesale">
                        <option value="">--เลือกโฮลเซลล์--</option>
                        @forelse ($wholesale as $item)
                            <option @if ($quotationModel->quote_wholesale === $item->id) selected @endif value="{{ $item->id }}">
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
                            <option @if ($quotationModel->quote_airline === $item->id) selected @endif value="{{ $item->id }}">
                                {{ $item->code }}-{{ $item->travel_name }}
                            </option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-md-3">
                    <label>วันออกเดินทาง: <a href="#" class="" id="list-period">เลือกวันที่</a></label>
                    <input type="text" class="form-control" id="date-start-display" placeholder="วันออกเดินทาง..." style="width: 100%">


                    <div id="date-list" class="list-group" style="position: absolute; z-index: 1000; width: 20%;">
                        
                    </div>

                    <input type="hidden" id="date-start" name="quote_date_start"
                        value="{{ date('Y-m-d', strtotime($quotationModel->quote_date_start)) }}">
                        
                </div>
            
                <div class="col-md-3">
                    <label>วันเดินทางกลับ: </label>
                    <input type="text" class="form-control" id="date-end-display"  style="width: 100%"
                        placeholder="วันเดินทางกลับ...">
                    <input type="hidden" id="date-end" name="quote_date_end"
                        value="{{ date('Y-m-d', strtotime($quotationModel->quote_date_end)) }}">
                </div>
            </div>
        </fieldset>


        <fieldset class="border p-2">
            <legend class="float-none w-auto text-danger" style="font-size: 15px"><span>ข้อมูลลูกค้า:</span></legend>
            <input type="hidden" name="customer_id" value="{{$customer->customer_id}}">
            <div class="row">
                <div class="col-md-3">
                    <label class="">ชื่อลูกค้า:</label>
                    <input type="text" class="form-control" name="customer_name" placeholder="ชื่อลูกค้า"
                        value="{{ $customer->customer_name }}" required aria-describedby="basic-addon1">
                </div>

                <div class="col-md-3">
                    <label>อีเมล์:</label>
                    <input type="email" class="form-control" name="customer_email"
                        value="{{ $customer->customer_email }}" placeholder="email@domail.com"
                        aria-describedby="basic-addon1">

                </div>

                <div class="col-md-3">
                    <label>เลขผู้เสียภาษี:</label>
                    <input type="text" id="texid" class="form-control" name="customer_texid" mix="13"
                        value="{{ $customer->customer_texid }}" placeholder="เลขประจำตัวผู้เสียภาษี"
                        aria-describedby="basic-addon1">
                </div>

                <div class="col-md-3">
                    <label>เบอร์โทรศัพท์ :</label>
                    <input type="text" class="form-control" name="customer_tel"
                        value="{{ $customer->customer_tel }}" placeholder="เบอร์โทรศัพท์"
                        aria-describedby="basic-addon1">

                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="col-md-12">
                                <label>เบอร์โทรสาร :</label>
                                <input type="text" class="form-control" id="fax" name="customer_fax"
                                    value="{{ $customer->customer_fax }}" placeholder="เบอร์โทรศัพท์"
                                    aria-describedby="basic-addon1">
                            </div>

                            <div class="col-md-12">
                                <label>ลูกค้ามาจาก :</label>
                                <select name="customer_campaign_source" class="form-select">
                                    @forelse ($campaignSource as $item)
                                        <option @if ($customer->customer_campaign_source === $item->campaign_source_id) selected @endif
                                            value="{{ $item->campaign_source_id }}">
                                            {{ $item->campaign_source_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label>Social id</label>
                                <input type="text" class="form-control" name="customer_social_id"
                                    placeholder="Social id" value="{{ $customer->customer_social_id }}">
                            </div>

                        </div>
                        <div class="col-md-9">
                            <label>ที่อยู่:</label>
                            <textarea name="customer_address" id="address" class="form-control" cols="30" rows="10"
                                style="height: 130px" placeholder="ที่อยู่">{{ $customer->customer_address }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

    


        <fieldset class="border p-2">
            <legend class="float-none w-auto text-success" style="font-size: 15px"><span>ข้อมูลค่าบริการ</span> <span
                    id="pax" class="float-end"></span></legend>
            <div class="row">
                <div id="quotation-table" class="table-custom text-center">
                    <div class="row header-row" style="padding: 5px">
                        <div class="col-md-1">ลำดับ</div>
                        <div class="col-md-4">รายการสินค้า</div>
                        <div class="col-md-1">รวม 3%</div>
                        <div class="col-md-1">NonVat</div>
                        <div class="col-md-1">จำนวน</div>
                        <div class="col-md-2">ราคา/หน่วย</div>
                        <div class="col-md-2">ยอดรวม</div>
                    </div>
                    <hr>

                    {{-- ค่าบริการ --}}
                    @php
                        $Runnumber = 0;
                        $W_tax = 0;
                    @endphp




                    @forelse ($quoteProducts as $key => $item)
                        <div class="row item-row ">

                            <div class="col-md-1"><span class="row-number"> {{ ++$Runnumber }}</span> <a
                                    href="javascript:void(0)" class="remove-row-btn text-danger"><span
                                        class=" fa fa-trash"></span></a></div>
                            <div class="col-md-4">
                              
                                <select class="selectpicker product-select selectpicker-select" name="product_id[]"  data-live-search="true" id="product-select">
                                             @forelse ($products as  $product)
                                             <option @if ($item->product_id === $product->id) selected @endif
                                                 data-pax="{{ $product->product_pax }}" value="{{ $product->id }}">
                                                 {{ $product->product_name }}
                                                 {{ $product->product_pax === 'Y' ? '(Pax)' : '' }}</option>
                                         @empty
                                         @endforelse
                                </select>


                            </div>

                            
                            <div class="col-md-1">

                                <input type="checkbox" name="withholding_tax[]" class="vat-3" value="Y"
                                    @if ($item->withholding_tax === 'Y') checked @endif>
                            </div>
                            <div class="col-md-1" style="display: none">
                                <select name="expense_type[]" class="form-select">
                                    <option selected value="income"> รายได้ </option>
                                </select>
                            </div>
                            <div class="col-md-1 text-center">
                                <select name="vat_status[]" class="vat-status form-select" style="width: 110%;">
                                    <option @if ($item->vat_status === 'vat') selected @endif value="vat">
                                        Vat</option>
                                    <option @if ($item->vat_status === 'nonvat') selected @endif value="nonvat">
                                        nonVat</option>
                                </select>
                            </div>
                            <div class="col-md-1"><input type="number" name="quantity[]"  style="width: 70%"
                                    class="quantity form-control text-end" value="{{ $item->product_qty }}"
                                    step="0.01"></div>
                            <div class="col-md-2"><input type="number" name="price_per_unit[]" style="width: 80%"
                                    class="price-per-unit form-control text-end" value="{{ $item->product_price }}"
                                    step="0.01">
                            </div>
                            <div class="col-md-2"><input type="number" name="total_amount[]" style="width: 80%"
                                    class="total-amount form-control text-end" value="0" readonly>
                            </div>
                        </div>

                    @empty
                    @endforelse
                    {{-- เพิ่มรายการใหม่ --}}
                    <div class="table-income">

                    </div>

                    <div class="add-row text-left">
                        <i class="fa fa-plus"></i><a id="add-row-service" href="javascript:void(0)" class="">
                            เพิ่มรายการ</a>
                    </div>
                    <hr>

                    <div class="col-md-12 " style="text-align: left">
                        <label class="text-danger">ส่วนลด</label>

                    </div>

                    {{-- ส่วนลด --}}
                    @forelse ($quoteProductsDiscount as $keyD => $itemD)
                        <div class="row item-row" data-row-id="{{ $keyD }}">
                            <div class="col-md-1"><span class="row-number">{{ ++$Runnumber }}</span>
                                <a href="javascript:void(0)" class="remove-row-btn text-danger"><span
                                        class=" fa fa-trash"></span></a>
                            </div>
                            <div class="col-md-4">
                              <select class="selectpicker product-select selectpicker-select" name="product_id[]"  data-live-search="true" id="product-select">
                                    <option value="">--เลือกส่วนลด--</option>
                                    @foreach ($productDiscount as $product)
                                        <option @if ($itemD->product_id === $product->id) selected @endif
                                            value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1">
                                <input type="checkbox" name="withholding_tax[]" class="vat-3" disabled>
                            </div>
                            <div class="col-md-1" style="display: none">
                                <select name="expense_type[]" class="form-select">
                                    <option selected value="discount"> ส่วนลด </option>
                                </select>
                            </div>
                            <div class="col-md-1 text-center">
                                <select name="vat_status[]" class="vat-status form-select" style="width: 100%;">

                                    <option value="nonvat" selected>nonVat</option>
                                </select>
                            </div>
                            <div class="col-md-1"><input type="number" name="quantity[]" style="width: 70%;"
                                    class="quantity form-control text-end" value="{{ $itemD->product_qty }}"
                                    step="0.01"></div>
                            <div class="col-md-2"><input type="number" name="price_per_unit[]" style="width: 80%;"
                                    class="price-per-unit form-control text-end" value="{{ $itemD->product_price }}"
                                    step="0.01">
                            </div>
                            <div class="col-md-2"><input type="number" name="total_amount[]" style="width: 80%;"
                                    class="total-amount form-control text-end" value="0" readonly></div>
                        </div>

                    @empty
                    @endforelse

                    <div class="table-discount">

                    </div>

                    <div class="add-row">
                        <i class="fa fa-plus"></i><a id="add-row-discount" href="javascript:void(0)" class="">
                            เพิ่มส่วนลด</a>
                    </div>

                </div>
            </div>


        </fieldset>

        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="vat-method">การคำนวณ VAT:</label>
                            <div>
                                <input type="radio" id="vat-include" name="vat_type"
                                    @if ($quotationModel->vat_type === 'include') checked @endif value="include">
                                <label for="vat-include">คำนวณรวมกับราคาสินค้าและบริการ (VAT
                                    Include)</label>
                            </div>
                            <div>
                                <input type="radio" id="vat-exclude" name="vat_type" value="exclude"
                                    @if ($quotationModel->vat_type === 'exclude') checked @endif>
                                <label for="vat-exclude">คำนวณแยกกับราคาสินค้าและบริการ (VAT
                                    Exclude)</label>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="col-md-12">
                        <div class="row summary-row">
                            <div class="col-md-10">
                                <input type="checkbox" name="quote_withholding_tax_status" value="Y"
                                    id="withholding-tax" @if ($quotationModel->quote_withholding_tax_status === 'Y') checked @endif>
                                <span class="">
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
                        <textarea name="quote_note" class="form-control" cols="30" rows="2">{{ $quotationModel->quote_note }}</textarea>
                    </div>
                </div>

            </div>
            <div class="col-6">
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
            <div class="row">
                <div class="col-md-12">
                    <h5>เงือนไขการชำระเงิน</h5>
                </div>
                <div class="col-md-12 ">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="radio" name="quote_payment_type" id="quote-payment-deposit"
                                {{ $quotationModel->quote_payment_type === 'deposit' ? 'checked' : '' }}
                                value="deposit"> <label for="quote-payment-type"> เงินมัดจำ </label>
                        </div>

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">ภายในวันที่</span>
                                    <input type="datetime-local" class="form-control" name="quote_payment_date" 
                                        id="quote-payment-date" value="{{ $quotationModel->quote_payment_date }}">
                                    <input type="datetime-local" class="form-control" name="quote_payment_date"
                                        id="quote-payment-date-new" value="{{ $quotationModel->quote_payment_date }}"
                                        value="" style="display: none" disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" for="">เรทเงินมัดจำ</span>
                                    <select name="quote_payment_price" class="form-select" id="quote-payment-price">
                                        <option @if ($quotationModel->quote_payment_price == 0.0) selected @endif value="0">
                                            0.00</option>
                                        <option @if ($quotationModel->quote_payment_price == 1000) selected @endif value="1000">
                                            1,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 1500) selected @endif value="1500">
                                            1,500</option>
                                        <option @if ($quotationModel->quote_payment_price == 2000) selected @endif value="2000">
                                            2,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 3000) selected @endif value="3000">
                                            3,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 4000) selected @endif value="4000">
                                            4,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 5000) selected @endif value="5000">
                                            5,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 6000) selected @endif value="6000">
                                            6,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 7000) selected @endif value="7000">
                                            7,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 8000) selected @endif value="8000">
                                            8,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 9000) selected @endif value="9000">
                                            9,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 10000) selected @endif value="10000">
                                            10,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 15000) selected @endif value="15000">
                                            15,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 20000) selected @endif value="20000">
                                            20,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 30000) selected @endif value="30000">
                                            30,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 24000) selected @endif value="24000">
                                            24,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 25000) selected @endif value="25000">
                                            25,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 28000) selected @endif value="28000">
                                            28,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 29000) selected @endif value="29000">
                                            29,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 34000) selected @endif value="34000">
                                            34,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 50000) selected @endif value="50000">
                                            50,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 70000) selected @endif value="70000">
                                            70,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 35000) selected @endif value="35000">
                                            35,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 40000) selected @endif value="40000">
                                            40,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 45000) selected @endif value="45000">
                                            45,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 80000) selected @endif value="80000">
                                            80,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 30500) selected @endif value="30500">
                                            30,500</option>
                                        <option @if ($quotationModel->quote_payment_price == 35500) selected @endif value="35500">
                                            35,500</option>
                                        <option @if ($quotationModel->quote_payment_price == 36000) selected @endif value="36000">
                                            36,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 38000) selected @endif value="38000">
                                            38,000</option>
                                        <option @if ($quotationModel->quote_payment_price == 100000) selected @endif value="100000">
                                            100,000</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" for="">ชำระเพิ่มเติม</span>
                                    <input type="number" id="pay-extra" class="form-control"
                                        name="quote_payment_extra" value="{{ $quotationModel->quote_payment_extra }}"
                                        placeholder="0.00">
                                </div>

                            </div>

                            <div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" for="">จำนวนเงินที่ต้องชำระ </span>
                                    <input type="number" class="form-control pax-total" name="quote_payment_total" step="0.01" placeholder="0.00"  >
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <input type="radio" name="quote_payment_type" id="quote-payment-full"
                                        {{ $quotationModel->quote_payment_type === 'full' ? 'checked' : '' }}
                                        value="full"> <label for="quote-payment-type"> ชำระเต็มจำนวน</label>
                                </div>



                            </div>
                            <div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">ภายในวันที่</span>
                                    <input type="datetime-local" class="form-control" id="quote-payment-date-full"
                                        name="quote_payment_date_full"
                                        value="{{ $quotationModel->quote_payment_date_full }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" for="">จำนวนเงิน</span>
                                    <input type="number" class="form-control" name="quote_payment_total_full"
                                        id="payment-total-full" step="0.01" placeholder="0.00"
                                        value="{{ $quotationModel->quote_payment_total_full }}">
                                </div>

                            </div>


                        </div>

                    </div>


                </div>


                {{-- <input type="hidden" id="booking-create-date"> --}}
                <input type="hidden" id="booking-create-date" value="{{ date('Y-m-d') }}">



            </div>
        </div>



        <div class="text-end mt-3">
            {{-- hidden --}}
            <input type="hidden" name="quote_vat_exempted_amount">
            <input type="hidden" name="quote_pre_tax_amount">
            <input type="hidden" name="quote_discount">
            <input type="hidden" name="quote_pre_vat_amount">
            <input type="hidden" name="quote_vat">
            <input type="hidden" name="quote_include_vat">
            <input type="hidden" name="quote_grand_total" id="quote-grand-total">
            <input type="hidden" name="quote_withholding_tax">
            <a class="btn btn-sm btn-info text-left" href="{{ route('quote.editNew', $quotationModel->quote_id) }}">
                Back</a>
            <button type="submit" class="btn btn-primary btn-sm  mx-3" form="formQuote"><i class="fa fa-save"></i>
                Update</button>


        </div>

    </form>


    <script>
               $(document).ready(function() {
                 $('.selectpicker').selectpicker({
                     width: '400px' 
                 });
             });
             
                 
             </script>
             



    <script>
        $(document).ready(function() {
            $('.country-select').select2({
                dropdownParent: $('#modal-quote-edit')
            });
            //         $('.product-select').select2({
            //             dropdownParent: $('#modal-quote-edit')
            //         });
        });



        $(document).ready(function() {
            // เมื่อ form ถูก submit
            $('form').on('submit', function() {
                // loop ผ่าน checkbox แต่ละตัว
                $('.vat-3').each(function(index, element) {
                    // ตรวจสอบว่าถ้า checkbox ไม่ได้ถูกติ๊ก
                    if (!$(element).is(':checked')) {
                        // สร้าง hidden input ที่มีค่าเป็น 'N' เพื่อส่งไปกับ form
                        $(element).after(
                            '<input type="hidden" name="withholding_tax[]" value="N">');
                    }
                });
                // window.close();
            });
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

            // ฟังก์ชันคำนวณยอดรวม

            function calculateTotals() {
                let sumTotal = 0;
                let sumDiscount = 0;
                let sumPriceExcludingVat = 0;
                let sumPriceExcludingVatNonVat = 0;
                let totalBeforeDiscount = 0;
                let withholdingTaxTotal = 0;
                let listVatTotal = 0;

                let processedDiscountRows = [];

                // ตรวจสอบและกำหนด vatMethod จาก input[name="vat_type"]
                let vatMethod = $('input[name="vat_type"]:checked').val() ||
                    'exclude'; // กำหนดค่าเริ่มต้นเป็น 'exclude' หากไม่มีค่า

                $('#quotation-table .item-row').each(function(index) {
                    const rowId = $(this).attr('data-row-id');
                    const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    const pricePerUnit = parseFloat($(this).find('.price-per-unit').val()) || 0;
                    const vatStatus = $(this).find('.vat-status').val(); // ตรวจสอบค่าจาก select
                    const isVat3 = $(this).find('.vat-3').is(':checked'); // ตรวจสอบการติ๊ก checkbox
                    const expenseType = $(this).find('select[name="expense_type[]"]').val();

                    // คำนวณ total เบื้องต้น
                    let total = quantity * pricePerUnit;
                    let priceExcludingVat = total;

                    // ตรวจสอบรายการ discount และหักออกก่อนการคำนวณ VAT
                    if (expenseType === 'discount') {
                        if (!rowId || rowId === 'undefined') {
                            return; // ข้ามการคำนวณถ้า rowId เป็น undefined
                        }

                        const discountAmount = quantity * pricePerUnit;
                        sumDiscount += discountAmount; // เก็บค่าส่วนลด

                        // เพิ่ม rowId ในรายการที่ถูกประมวลผลแล้ว
                        processedDiscountRows.push(rowId);
                    }

                    // คำนวณ VAT 3% หากมีการเลือก
                    if (isVat3) {
                        const vat3 = total * 0.03;
                        total += vat3;
                    }

                    // แสดงผลยอดรวมในฟิลด์ total-amount
                    $(this).find('.total-amount').val(total.toFixed(2));

                    totalBeforeDiscount += (expenseType !== 'discount') ? total : 0;

                    // กรณี Non-VAT
                    if (vatStatus === 'nonvat') {
                        $(this).find('.price-excluding-vat').val(total.toFixed(2));
                        sumPriceExcludingVatNonVat += total; // เพิ่มยอดไปที่ Non-VAT รวม
                    } else {
                        listVatTotal += total; // เพิ่มยอดไปที่รายการที่ต้องเสีย VAT
                    }

                    sumTotal += total;
                });

                // คำนวณยอดหลังส่วนลด (หักส่วนลดออกจาก total ก่อนการคำนวณ VAT)
                const afterDiscount = totalBeforeDiscount - sumDiscount;

                let vatAmount = 0;
                let preVatAmount = 0;
                let grandTotal = 0;
                let sumPreVat = 0;

                if (vatMethod === 'include') {
                    // VAT รวมอยู่ในยอดแล้ว
                    preVatAmount = sumPriceExcludingVat * 0.07;
                    sumPreVat = listVatTotal - sumDiscount; // หักส่วนลดออกก่อนคำนวณ
                    sumPreVat = sumPreVat * 100 / 107;
                    vatAmount = sumPreVat * 0.07;
                    grandTotal = sumPriceExcludingVatNonVat + sumPreVat + vatAmount;
                } else {
                    // คำนวณ VAT 7% กรณี Exclude VAT
                    if (sumDiscount < listVatTotal) {
                        sumPreVat = listVatTotal - sumDiscount;
                        vatAmount = sumPreVat * 0.07;
                        grandTotal = sumPriceExcludingVatNonVat + sumPreVat + vatAmount;
                    } else {
                        sumPreVat = 0;
                        vatAmount = sumPreVat * 0.07;
                        grandTotal = (sumPriceExcludingVatNonVat + sumPreVat + vatAmount) - sumDiscount;
                    }
                    // หักส่วนลดออกก่อนคำนวณ
                }

                // คำนวณหักภาษี ณ ที่จ่าย (Withholding Tax)
                const withholdingTax = $('#withholding-tax').is(':checked') ? sumPreVat * 0.03 : 0;

                // อัปเดตค่าต่างๆ ในหน้าจอ
                $('#sum-total').text(formatNumber(sumTotal.toFixed(2)));

                $('#quote-total').val(sumTotal.toFixed(2));
                $('#after-discount').text(formatNumber(afterDiscount.toFixed(2)));
                $('#quote-after-discount').val(afterDiscount.toFixed(2));
                $('#quote-vat-7').val(vatAmount.toFixed(2));
                $('#price-excluding-vat').text(formatNumber((sumPriceExcludingVat + sumPriceExcludingVatNonVat)
                    .toFixed(2)));
                $('#quote-price-excluding-vat').val(((sumPriceExcludingVat + sumPriceExcludingVatNonVat).toFixed(
                    2)));
                $('#withholding-amount').text(formatNumber(withholdingTax.toFixed(2)));
                $('input[name="quote_withholding_tax"]').val(withholdingTax.toFixed());

                $('#sum-total-nonvat').text(formatNumber((sumPriceExcludingVatNonVat - sumDiscount).toFixed(2)));
                $('input[name="quote_vat_exempted_amount"]').val((sumPriceExcludingVatNonVat - sumDiscount).toFixed(
                    2));
                $('#sum-total-vat').text(formatNumber(listVatTotal.toFixed(2)));
                $('input[name="quote_pre_tax_amount"]').val(listVatTotal.toFixed(2));
                $('#sum-discount').text(formatNumber(sumDiscount.toFixed(2)));
                $('input[name="quote_discount"]').val(sumDiscount.toFixed(2));
                $('#sum-pre-vat').text(formatNumber(sumPreVat.toFixed(2)));
                $('input[name="quote_pre_vat_amount"]').val(sumPreVat.toFixed(2));
                $('#vat-amount').text(formatNumber(vatAmount.toFixed(2)));
                $('input[name="quote_vat"]').val(vatAmount.toFixed(2));
                $('#sum-include-vat').text(formatNumber((sumPreVat + vatAmount).toFixed(2)));
                $('input[name="quote_include_vat"]').val((sumPreVat + vatAmount).toFixed(2));
                $('#grand-total').text(formatNumber((grandTotal - sumDiscount).toFixed(2)));
                $('input[name="quote_grand_total"]').val((grandTotal - sumDiscount).toFixed(2));
            }



            // Initialize Select2 สำหรับทุก select element ที่มี class .product-select
//             function initializeSelect2() {
//                 $('.product-select').each(function() {
//                     if (!$(this).hasClass("select2-hidden-accessible")) {
//                         $(this).select2({
//                             width: 'resolve', // ตั้งค่า width ให้กับ select2
//                             dropdownParent: $('#modal-quote-edit')
//                         });
//                     }
//                 });
//             }


            // ฟังก์ชันเพิ่มแถวใหม่สำหรับค่าบริการ
            function addNewServiceRow() {
                const newRow = `
                   <div class="row item-row" >
                       <div class="col-md-1"><span class="row-number"></span>
                           <a href="javascript:void(0)" class="remove-row-btn text-danger"><span class=" fa fa-trash"></span></a>
                        </div>
                       <div class="col-md-4">
                           <select class="selectpicker product-select selectpicker-select" name="product_id[]"  data-live-search="true" id="product-select">
                               <option value="">--เลือกสินค้า--</option>
                               @foreach ($products as $product)
                                   <option  data-pax="{{ $product->product_pax }}" value="{{ $product->id }}">{{ $product->product_name }}{{ $product->product_pax === 'Y' ? '(Pax)' : '' }}</option>
                               @endforeach
                           </select>
                       </div>
                       
                       <div class="col-md-1">
                           <input type="checkbox" name="vat3[]" class="vat-3">
                       </div>
                        <div class="col-md-1" style="display: none">
                                               <select name="expense_type[]" class="form-select">
                                                   <option selected value="income"> รายได้ </option>
                                               </select>
                                           </div>
                      <div class="col-md-1 text-center">
           <select name="vat_status[]" class="vat-status form-select" style="width: 110%;">
               <option value="vat">Vat</option>
           <option selected value="nonvat">nonVat</option>
           </select>
       </div>
                       <div class="col-md-1"><input type="number" name="quantity[]" class="quantity form-control text-end" value="1" step="0.01" style="width: 70%"></div>
                       <div class="col-md-2"><input type="number" name="price_per_unit[]" class="price-per-unit form-control text-end" value="0" step="0.01" style="width: 80%"></div>
                       <div class="col-md-2"><input type="number" name="total_amount[]" class="total-amount form-control text-end" value="0" readonly style="width: 80%"></div>
                   </div>
                   
                   `;

                // Append แถวใหม่ไปที่ table-income
                $('.table-income').append(newRow);
                $('.selectpicker').selectpicker({
                              width: '400px' 
                });
                // Reinitialize Select2 สำหรับทุก select element
               //  initializeSelect2({
               //      dropdownParent: $('#modal-quote-edit')
               //  });

                // อัปเดตลำดับแถว (ถ้ามีฟังก์ชันนี้อยู่)
                updateRowNumbers();
            }

            // ฟังก์ชันเพิ่มแถวใหม่สำหรับส่วนลด
            let currentRowId = 1;

            function addNewDiscountRow() {
                currentRowId++;
                const newRow = `
                   <div class="row item-row" data-row-id="${currentRowId}">
                       <div class="col-md-1"><span class="row-number"></span>
                           <a href="javascript:void(0)" class="remove-row-btn text-danger"><span class=" fa fa-trash"></span></a>
                        </div>
                       <div class="col-md-4">
                           <select class="selectpicker product-select selectpicker-select" name="product_id[]"  data-live-search="true" id="product-select">
                               <option value="">--เลือกส่วนลด--</option>
                               @foreach ($productDiscount as $product)
                                   <option value="{{ $product->id }}">{{ $product->product_name }}{{ $product->product_pax === 'Y' ? '(Pax)' : '' }}</option>
                               @endforeach
                           </select>
                       </div>
                                   
                       <div class="col-md-1">
                           <input type="checkbox" name="vat3[]" class="vat-3" disabled>
                       </div>
                        <div class="col-md-1" style="display: none">
                                               <select name="expense_type[]" class="form-select" >
                                                   <option selected value="discount"> ส่วนลด </option>
                                               </select>
                                           </div>
                       <div class="col-md-1 text-center">
           <select name="vat_status[]" class="vat-status form-select" style="width: 110%;">
       
               <option value="nonvat">nonVat</option>
           </select>
       </div>
                       <div class="col-md-1"><input type="number" name="quantity[]" class="quantity form-control text-end" value="1" step="0.01" style="width: 70%"></div>
                       <div class="col-md-2"><input type="number" name="price_per_unit[]" class="price-per-unit form-control text-end" value="0" step="0.01" style="width: 80%"></div>
                       <div class="col-md-2"><input type="number" name="total_amount[]" class="total-amount form-control text-end" value="0" readonly style="width: 80%"></div>
                   </div>`;

                // Append แถวใหม่ไปที่ table-discount
                $('.table-discount').append(newRow);

                $('.selectpicker').selectpicker({
                              width: '400px' 
                });
                // Reinitialize Select2 สำหรับทุก select element
               //  initializeSelect2({
               //      dropdownParent: $('#modal-quote-edit')
               //  });

                // อัปเดตลำดับแถว (ถ้ามีฟังก์ชันนี้อยู่)
                updateRowNumbers();
            }

            // Add new service row when button clicked
            $('#add-row-service').click(function() {
                addNewServiceRow();
            });

            // Add new discount row when button clicked
            $('#add-row-discount').click(function() {
                addNewDiscountRow();
            });

            // เรียกฟังก์ชันคำนวณใหม่ทุกครั้งที่มีการเปลี่ยนแปลง checkbox หรือ input
            $('#quotation-table').on('change', '.vat-3, .vat-status, select[name="expense_type[]"]',
                calculateTotals);
            $('#quotation-table').on('input', '.quantity, .price-per-unit', calculateTotals);
            $('#withholding-tax').change(calculateTotals);
            $('input[name="vat_type"]').change(calculateTotals);

            // Remove row
            $('#quotation-table').on('click', '.remove-row-btn', function() {
                $(this).closest('.item-row').remove();
                calculateTotals();
                updateRowNumbers();
            });

            // เริ่มต้นการคำนวณ
            calculateTotals();

            // Initialize Select2 for existing rows
           // initializeSelect2();
            /// ดึงข้อมูลตามการเลือก period
            $(document).ready(function() {
                // ใช้ event delegation เพื่อจับเหตุการณ์การเปลี่ยนแปลงใน .product-select ที่ถูกเพิ่มเข้ามาใหม่ได้
                $('#quotation-table').on('change', '.product-select', function() {
                    var productId = $(this).val(); // รับค่า productId จาก select
                    var period1 = $('#period1').val();
                    var period2 = $('#period2').val();
                    var period3 = $('#period3').val();
                    var period4 = $('#period4').val();

                    // อ้างถึง .item-row ที่เกี่ยวข้องกับ product-select ที่เลือก
                    var row = $(this).closest('.item-row');

                    // อ้างถึง price-per-unit และ quantity ที่อยู่ในแถวที่เลือก
                    var priceInput = row.find('.price-per-unit');
                    var quantityInput = row.find('.quantity');

                    // กำหนดค่าเริ่มต้นให้ quantity เป็น 1
                    quantityInput.val(1);

                    // 189 ค่าทัวร์ผู้ใหญ่พักคู่ period1
                    if (productId == 189) {
                        priceInput.val(period1); // แสดงค่า period1 ใน input .price-per-unit
                        console.log(period1);
                    }

                    // 185 ค่าทัวร์ผู้ใหญ่พักเดี่ยว period2
                    if (productId == 185) {
                        priceInput.val(period2); // แสดงค่า period2 ใน input .price-per-unit
                    }

                    // 187 เด็กมีเตียง period3
                    if (productId == 187) {
                        priceInput.val(period3); // แสดงค่า period3 ใน input .price-per-unit
                    }

                    // 186 เด็กไม่มีเตียง period4
                    if (productId == 186) {
                        priceInput.val(period4); // แสดงค่า period4 ใน input .price-per-unit
                    }
                    calculateTotals();
                });
            });

        });

        $(document).ready(function() {
            function checkPaymentCondition() {
                var travelDate = new Date($('#date-start').val());
                //console.log(travelDate);
                var bookingDate = new Date($('#booking-create-date').val());
                // คำนวณจำนวนวันระหว่างวันจองและวันออกเดินทาง
                var diffDays = (travelDate - bookingDate) / (1000 * 60 * 60 * 24);
                // ตรวจสอบเงื่อนไข
                if (diffDays > 30) {
                    // เงื่อนไข 1: เลือกวิธีชำระเงินมัดจำ
                    $('#quote-payment-deposit').prop('checked', true);
                    $('#quote-payment-price').prop('disabled', false); // เปิดการใช้งาน dropdown
                    $('#quote-payment-deposit').prop('disabled', false);
                    $('#quote-payment-date').prop('disabled', false);
                    setPaymentDueDate();

                } else {
                    // หากไม่เข้าเงื่อนไข 1: เลือกชำระเต็มจำนวน
                    $('#quote-payment-full').prop('checked', true);
                    $('#quote-payment-deposit').prop('disabled', true);
                    $('#quote-payment-price').prop('disabled', true); // ปิดการใช้งาน dropdown
                    $('#quote-payment-date').prop('disabled', true);

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
            //ตั้งค่าฟิลด์ "ภายในวันที่" เมื่อโหลดหน้าเว็บ
            //setPaymentDueDate();

            function setPaymentDueDate30() {
                var bookingCreateDate = new Date($('#date-start').val());
                var travelDate = new Date($('#date-start').val());
                console.log(travelDate);
                var bookingDate = new Date($('#booking-create-date').val());
                // คำนวณจำนวนวันระหว่างวันจองและวันออกเดินทาง
                var diffDays = (travelDate - bookingDate) / (1000 * 60 * 60 * 24);

                if (diffDays >= 31) {
                    // ลบ 31 วัน
                    bookingCreateDate.setDate(bookingCreateDate.getDate() - 30);
                } else {
                    // เพิ่ม 1 วัน
                    bookingCreateDate.setDate(bookingCreateDate.getDate() + 1);
                }

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

                $('input[name="quote_payment_date_full"]').val(formattedDate);
            }
            //setPaymentDueDate30();
           
            // ตรวจสอบเมื่อผู้ใช้เลือกชำระเงินเต็มจำนวน
            function checkedPaymentFull() {
                var QuoteTotalGrand = $('#quote-grand-total').val();
                if ($('#quote-payment-full').is(':checked')) {
                    $('#quote-payment-price').prop('disabled', true); // ปิด dropdown เรทเงินมัดจำ
                    $('#payment-total-full').val(QuoteTotalGrand);
                    $('.pax-total').val(0.00);
                    $('#quote-payment-price').val(0);
                    $('#quote-payment-date').prop('disabled', true);
                }
            }

            $('#quote-payment-full, .quantity, .price-per-unit').on('change', function() {
                checkedPaymentFull();
            });
            checkedPaymentFull();

            $('#quote-payment-deposit').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#quote-payment-price').prop('disabled', false); // เปิด dropdown เรทเงินมัดจำ
                    $('#quote-payment-date').prop('disabled', false);
                    $('#quote-payment-price').val(0.00);
                }
            });

            function calculatePaxAndTotal() {
                var QuoteTotalGrand = $('#quote-grand-total').val();
                // ตรวจสอบว่าการชำระเงินเต็มจำนวนถูกเลือกหรือไม่
                if ($('#quote-payment-deposit,#quote-payment-full').is(':checked')) {
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
                        $('#pax').text('(จำนวน : ' + totalQuantity + ' ท่าน)');
                        $('#quote-pax-total').val(totalQuantity);
                    });



                    // คำนวณยอด Pax โดยใช้ totalQuantity ที่รวมแล้ว
                    var paymentPrice = parseFloat($('#quote-payment-price').val()) || 0;
                    var payExtra = parseFloat($('#pay-extra').val()) || 0;
                    var paxTotal = (totalQuantity * paymentPrice) + payExtra;

                    // อัพเดตยอด Pax ในทุกแถวที่มี Pax
                    $('#quotation-table .item-row').each(function() {
                        const selectedProduct = $(this).find('select[name="product_id[]"] option:selected');
                        var isPax = selectedProduct.data('pax') === "Y";
                        if (isPax) {
                            $('.pax-total').val(paxTotal.toFixed(2)); // อัพเดตยอด Pax
                            $('#payment-total-full').val(QuoteTotalGrand - paxTotal);

                        }
                    });
                } else {
                    // ถ้าไม่ได้เลือกชำระเต็มจำนวน ให้ล้างค่า Pax
                    $('#quotation-table .pax-total').val();
                }
            }

            calculatePaxAndTotal()

            // เรียกใช้ calculatePaxAndTotal เมื่อมีการเปลี่ยนแปลงใน quantity, product-select หรือ quote-payment-price
            $(document).on('change',
                '.quantity, .product-select, #quote-payment-price, #pay-extra, .price-per-unit',
                function() {
                    calculatePaxAndTotal();
                    // checkPaymentCondition();
                    checkedPaymentFull();
                });


            // ตรวจสอบเมื่อมีการเปลี่ยนแปลงในการเลือกชำระเงิน
            $('#quote-payment-deposit,#quote-payment-full').on('change', function() {
                if ($(this).is(':checked')) {
                    calculatePaxAndTotal(); // คำนวณยอด Pax เฉพาะเมื่อเลือกชำระเต็มจำนวน
                }
            });

            // เรียกใช้ฟังก์ชันเมื่อเริ่มต้น
            // checkedPaymentFull()
            //calculatePaxAndTotal();



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
                    dateFormat: 'dd MM yy',
                    firstDay: 0,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: ''
                };
                $.datepicker.setDefaults($.datepicker.regional['th']);

                // ฟังก์ชันคำนวณวันสิ้นสุด
                function calculateEndDate() {
                    var numDays = parseInt($('#numday option:selected').data('day')) || 0;
                    var startDate = $('#date-start').val();



                    if (numDays > 0 && startDate) {
                        var start = new Date(startDate);
                        var endDate = new Date(start);
                        endDate.setDate(start.getDate() + numDays - 1); // คำนวณวันสิ้นสุด

                        // แสดงวันสิ้นสุดในรูปแบบภาษาไทย
                        var thaiFormattedEndDate = $.datepicker.formatDate('dd MM yy', endDate);
                        $('#date-end-display').val(thaiFormattedEndDate); // แสดงใน input
                        $('#date-end').val($.datepicker.formatDate('yy-mm-dd',
                            endDate)); // เก็บค่าแบบ yyyy-mm-dd ใน hidden input
                    }
                }

                // ตั้งค่า Datepicker สำหรับวันเริ่มต้น
                $('#date-start-display').datepicker({
                    dateFormat: 'dd MM yy',
                    onSelect: function(dateText) {
                        var isoDate = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker(
                            'getDate'));
                        $('#date-start').val(isoDate);
                        calculateEndDate(); // คำนวณวันสิ้นสุดเมื่อเลือกวันเริ่มต้น
                        setPaymentDueDate30(); // คำนวณวันสิ้นสุดเมื่อเลือกวันเริ่มต้น
                        checkPaymentCondition()
                    }
                });

                // ตั้งค่า Datepicker สำหรับวันสิ้นสุด
                $('#date-end-display').datepicker({
                    dateFormat: 'dd MM yy',
                    onSelect: function(dateText) {
                        var isoDate = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker(
                            'getDate'));
                        $('#date-end').val(isoDate);
                    }
                });

                // กำหนดให้คำนวณวันสิ้นสุดเมื่อเปลี่ยนจำนวนวัน
                $('#numday').on('change', function() {

                    if ($('#date-start').val()) {
                        calculateEndDate();
                    }
                });



                // ตรวจสอบและแสดงวันที่เริ่มต้นและวันสิ้นสุดในรูปแบบภาษาไทยหากมีข้อมูล
                var startDate = $('#date-start').val();
                var endDate = $('#date-end').val();

                if (startDate) {
                    $('#date-start-display').datepicker('setDate', new Date(startDate));
                }
                if (endDate) {
                    $('#date-end-display').datepicker('setDate', new Date(endDate));
                }


            });
        });

        $(document).ready(function() {

            $('#customerSearch').on('keydown', function(e) {
                if (e.key === 'Enter') { // ตรวจสอบว่ากดปุ่ม Enter หรือไม่
                    e.preventDefault(); // ป้องกันการ submit ฟอร์ม
                }
            });

            // เมื่อพิมพ์ในช่องค้นหา
            $('#customerSearch').on('input', function(e) {
                var searchTerm = $(this).val();

                console.log(searchTerm);

                if (searchTerm.length >= 2) { // คำค้นหาต้องมีอย่างน้อย 2 ตัวอักษร
                    $.ajax({
                        url: '{{ route('api.customer') }}', // URL สำหรับดึงข้อมูลทัวร์
                        method: 'GET',
                        data: {
                            search: searchTerm
                        },

                        success: function(data) {
                            $('#customerResults').empty(); // ล้างข้อมูลผลลัพธ์เดิม
                            if (data.length > 0) {
                                // วนลูปแสดงรายการผลลัพธ์
                                $.each(data, function(index, item) {
                                    $('#customerResults').append(`
                                       <a href="#"  class="list-group-item list-group-item-action" 
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



                // ถ้าเลือก "กำหนดเอง"
                if ($(this).attr('id') === 'custom-input') {
                    var customSearchText = $('#customerSearch').val(); // ดึงค่าที่กรอกใน customerSearch
                    $('#customer_email').val('');
                    $('#texid').val('');
                    $('#customer_tel').val('');
                    $('#customer_fax').val('');
                    $('#customer_address').val('');
                    $('#customerSearch').val(
                        customSearchText); // ใส่ค่าที่ผู้ใช้กรอกกลับเข้าไปใน customerSearch
                    $('#customer-id').val('');
                    $('#customer-new').val('customerNew');
                } else {
                    // ถ้าเลือกจากรายการอื่นๆ
                    $('#customer_email').val(customerEmail);
                    $('#texid').val(customerTaxid);
                    $('#customer_tel').val(customerTel);
                    $('#customer_fax').val(customerFax);
                    $('#customer_address').val(customerAddress);
                    $('#customerSearch').val(selectedText);
                    $('#customer-id').val(selectedId);
                    $('#customer-new').val('customerOld');
                }

                $('#customerResults').empty(); // ล้างผลลัพธ์การค้นหา

            });

            // เมื่อคลิกนอกผลลัพธ์การค้นหา ให้ล้างข้อมูล
            $(document).on('click', function(event) {
                if (!$(event.target).closest('#customerResults, #customerSearch').length) {
                    $('#customerResults').empty(); // ล้างผลลัพธ์เมื่อคลิกนอกการค้นหา
                }
            });



            $('#tourSearch').on('keydown', function(e) {
                if (e.key === 'Enter') { // ตรวจสอบว่ากดปุ่ม Enter หรือไม่
                    e.preventDefault(); // ป้องกันการ submit ฟอร์ม
                }
            });
            // เมื่อพิมพ์ในช่องค้นหา
            $('#tourSearch').on('input', function(e) {
                var searchTerm = $(this).val();
                if (searchTerm.length >= 2) { // คำค้นหาต้องมีอย่างน้อย 2 ตัวอักษร
                    $.ajax({
                        url: '{{ route('api.tours') }}', // URL สำหรับดึงข้อมูลทัวร์
                        method: 'GET',
                        data: {
                            search: searchTerm
                        },
                        success: function(data) {
                            $('#tourResults').empty(); // ล้างข้อมูลผลลัพธ์เดิม
                            if (data.length > 0) {
                                // วนลูปแสดงรายการผลลัพธ์
                                $.each(data, function(index, item) {
                                    $('#tourResults').append(`<a href="#" id="tour-select" class="list-group-item list-group-item-action" data-tour="${item.id}" data-numday="${item.num_day}" data-airline="${item.airline_id}"  data-wholesale="${item.wholesale_id}" data-code="${item.code}" data-name1="${item.code} - ${item.name}" data-name="${item.code} - ${item.code1} - ${item.name}">${item.code} - ${item.code1} - ${item.name}</a>
                                   `);
                                });
                            }
                            // ถ้าไม่มีข้อมูล
                            $('#tourResults').append(
                                `<a href="#" class="list-group-item list-group-item-action" data-name="${searchTerm}">กำหนดเอง</a>`
                            );
                        }
                    });
                } else {
                    $('#tourResults').empty(); // ล้างผลลัพธ์เมื่อไม่มีคำค้นหา
                }
            });

            // เมื่อคลิกเลือกแพคเกจจากผลลัพธ์การค้นหา
            $(document).on('click', '#tourResults a', function(e) {
                e.preventDefault();
                var selectedCode = $(this).data('code') || ''; // ถ้า selectedCode ไม่มีค่า ให้ใส่ค่าว่าง
                var selectedText = $(this).data('name');
                var selectedText1 = $(this).data('name1');
                var selectedAirline = $(this).data('airline');
                var selectedNumday = $(this).data('numday'); // ข้อความ 6 วัน 4 คืน
                var selectedTour = $(this).data('tour'); // 

                $('#tour-id').val(selectedTour); // แสดงชื่อแพคเกจที่เลือกใน input
                $('#tourSearch').val(selectedText); // แสดงชื่อแพคเกจที่เลือกใน input
                $('#tourSearch1').val(selectedText1); // แสดงชื่อแพคเกจที่เลือกใน input
                $('#tour-code').val(selectedCode); // เก็บค่า code ใน hidden input หรือค่าว่าง
                $('#tourResults').empty(); // ล้างผลลัพธ์การค้นหา

                // ตั้งค่า airline
                $('#airline').val(selectedAirline).change();

                // ลูปผ่าน option ทั้งหมดใน #numday และตรวจสอบว่า num_day_name ตรงกับ selectedNumday หรือไม่
                $('#numday option').each(function() {
                    // ตัดช่องว่างหน้าและหลังข้อความและเปรียบเทียบ
                    var optionText = $.trim($(this).text());
                    if (optionText === $.trim(selectedNumday)) {
                        $(this).prop('selected', true); // เลือก option ที่ตรงกัน
                        return false; // หยุดการลูปเมื่อเจอค่าที่ตรงกัน
                    }
                });
            });


            // Select Wholesale 
            $(document).ready(function() {
                // Select Wholesale เมื่อคลิกเลือกทัวร์
                $(document).on('click', '#tour-select', function(e) {
                    e.preventDefault();
                    var selectedCode = $(this).data('wholesale') || '';
                    if (selectedCode) {
                        $.ajax({
                            url: '{{ route('api.wholesale') }}', // URL สำหรับดึงข้อมูลโฮลเซลล์
                            method: 'GET',
                            data: {
                                search: selectedCode
                            },
                            success: function(data) {
                                if (data) {
                                    // ตรวจสอบว่าตัวเลือกนี้มีอยู่แล้วใน select หรือไม่
                                    if (!$('#wholesale option[value="' + data.id + '"]')
                                        .length) {
                                        // เพิ่มตัวเลือกใหม่ใน select ของ Wholesale ถ้ายังไม่มีตัวเลือกนี้
                                        $('#wholesale').append(`
                                       <option value="${data.id}">${data.wholesale_name_th}</option>
                                   `);
                                    }
                                    // ตั้งค่าให้ตัวเลือกนั้นถูกเลือก
                                    $('#wholesale').val(data.id).trigger('change');
                                } else {
                                    console.log("ไม่พบข้อมูลโฮลเซลล์");
                                }
                            },
                            error: function() {
                                console.log("เกิดข้อผิดพลาดในการดึงข้อมูล");
                            }
                        });
                    }
                });


            });

            // วันที่ออกเดินทาง
            $(document).on('click', '#tour-select, #list-period', function(e) {
                e.preventDefault();
                var tourId = $(this).data('tour'); // ดึงค่า tour_id
                if (tourId === undefined) {
                    tourId = $('#tour-id').val();
                }

                // ส่ง tour_id ไปที่ API เพื่อดึงข้อมูล period
                $.ajax({
                    url: '{{ route('api.period') }}', // URL สำหรับดึงข้อมูล period
                    method: 'GET',
                    data: {
                        search: tourId
                    },
                    success: function(data) {
                        $('#date-list').empty(); // ล้างรายการวันที่เดิม
                        //  $('#date-list').append(` <a href="#" class="list-group-item list-group-item-action period-custom"> กำหนดเอง</a>`);
                        if (data.length > 0) {
                            // วนลูปแสดงรายการวันที่
                            $.each(data, function(index, period) {
                                // แปลงวันที่ที่ได้รับจาก API เป็น object ของ Date
                                var dateObject = new Date(period.start_date);

                                // แปลงวันที่เป็นรูปแบบภาษาไทย
                                var thaiFormattedDate = $.datepicker.formatDate(
                                    'dd MM yy', dateObject);

                                // แสดงวันที่ในรูปแบบภาษาไทย
                                $('#date-list').append(`
                               <a href="#" class="list-group-item  period-select" data-period1="${period.price1}" data-period2="${period.price2}"  data-period3="${period.price3}" data-period4="${period.price4}" data-date="${period.start_date}">
                                   ${thaiFormattedDate}
                               </a>
                           `);
                            });
                        } else {
                            $('#date-list').append('<p>ไม่มีข้อมูลวันที่</p>');
                        }
                    }
                });
            });


            // เมื่อคลิกเลือกวันที่จาก list
            $(document).on('click', '.period-select', function(e) {
                e.preventDefault();
                var selectedDate = $(this).data('date'); // ดึงค่าของวันที่ที่เลือก
                var period1 = $(this).data('period1'); // ผู้ใหญ่พักคู่
                var period2 = $(this).data('period2'); // ผู้ใหญ่พักเดียว
                var period3 = $(this).data('period3'); // เด็กมีเตียง
                var period4 = $(this).data('period4'); // เด็กไม่มีเตียง
                var selectedNumday = $('#numday').data('day');

                $('#period1').val(period1);
                $('#period2').val(period2);
                $('#period3').val(period3);
                $('#period4').val(period4);

                // แปลงวันที่เป็นรูปแบบภาษาไทยสำหรับแสดงใน input
                var dateObject = new Date(selectedDate);
                var thaiFormattedDate = $.datepicker.formatDate('dd MM yy', dateObject);

                // แสดงวันที่ที่เลือกใน input id="date-start-display"
                $('#date-start-display').val(thaiFormattedDate);

                // เก็บค่า ISO ใน hidden input
                $('#date-start').val(selectedDate);

                // ล้างรายการวันที่หลังจากเลือก
                $('#date-list').empty();

                function setPaymentDueDate30() {
                    var bookingCreateDate = new Date($('#date-start').val());
                    var travelDate = new Date($('#date-start').val());
                    //console.log(travelDate);
                    var bookingDate = new Date($('#booking-create-date').val());
                    // คำนวณจำนวนวันระหว่างวันจองและวันออกเดินทาง
                    var diffDays = (travelDate - bookingDate) / (1000 * 60 * 60 * 24);
                    if (diffDays >= 31) {
                        // ลบ 31 วัน
                        bookingCreateDate.setDate(bookingCreateDate.getDate() - 31);
                    } else {
                        // เพิ่ม 1 วัน
                        bookingCreateDate = new Date();
                        bookingCreateDate.setDate(bookingCreateDate.getDate() + 1);
                    }

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

                    $('input[name="quote_payment_date_full"]').val(formattedDate);
                }
                setPaymentDueDate30()

                // เรียกฟังก์ชันคำนวณวันเดินทางกลับ
                calculateEndDate();
            });

            // ฟังก์ชันคำนวณวันเดินทางกลับ
            function calculateEndDate() {
                var numDays = parseInt($('#numday option:selected').data('day')) || 0; // จำนวนวันที่เดินทาง
                var startDate = $('#date-start').val(); // วันที่เริ่มต้น

                if (numDays > 0 && startDate) {
                    var start = new Date(startDate);
                    var endDate = new Date(start);
                    endDate.setDate(start.getDate() + numDays - 1); // คำนวณวันสิ้นสุด (บวกจำนวนวัน)

                    // แปลงวันสิ้นสุดเป็นรูปแบบภาษาไทย
                    var thaiFormattedEndDate = $.datepicker.formatDate('dd MM yy', endDate);

                    // แสดงวันสิ้นสุดใน input id="date-end-display"
                    $('#date-end-display').val(thaiFormattedEndDate);

                    // เก็บค่า ISO ของวันสิ้นสุดใน hidden input
                    $('#date-end').val($.datepicker.formatDate('yy-mm-dd', endDate));
                }
            }

            // กำหนดให้คำนวณวันเดินทางกลับเมื่อเปลี่ยนจำนวนวัน
            $('#numday').on('change', function() {
                if ($('#date-start').val()) {
                    calculateEndDate(); // คำนวณวันเดินทางกลับเมื่อเปลี่ยนจำนวนวัน
                }
            });


            // Select country 
            $(document).ready(function() {
                // Select Wholesale เมื่อคลิกเลือกทัวร์
                $(document).on('click', '#tour-select', function(e) {
                    e.preventDefault();
                    var selectedCode = $(this).data('code') || '';
                    if (selectedCode) {
                        $.ajax({
                            url: '{{ route('api.country') }}', // URL สำหรับดึงข้อมูลโฮลเซลล์
                            method: 'GET',
                            data: {
                                search: selectedCode
                            },
                            success: function(data) {
                                //console.log(data);

                                if (data) {
                                    // ตรวจสอบว่าตัวเลือกนี้มีอยู่แล้วใน select หรือไม่
                                    if (!$('#country option[value="' + data.id + '"]')
                                        .length) {
                                        // เพิ่มตัวเลือกใหม่ใน select ของ Wholesale ถ้ายังไม่มีตัวเลือกนี้
                                        $('#country').append(`
                                       <option value="${data.id}">${data.country_name_th}</option>
                                   `);
                                    }
                                    // ตั้งค่าให้ตัวเลือกนั้นถูกเลือก
                                    $('#country').val(data.id).trigger('change');
                                } else {
                                    console.log("ไม่พบข้อมูลโฮลเซลล์");
                                }
                            },
                            error: function() {
                                console.log("เกิดข้อผิดพลาดในการดึงข้อมูล");
                            }
                        });
                    }
                });


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
                    $('#quote-date').val(isoDate);
                }
            });

            // กำหนดค่าเริ่มต้นให้กับ Datepicker (แสดงเป็นภาษาไทย) และ hidden input
            let defaultDate = '{{ date('Y-m-d', strtotime(now())) }}';
            $('#submitDatepicker').val(defaultDate);
            $('#quote-date').val(defaultDate);
            const thaiFormattedDate = formatDateToThai(defaultDate);
            $('#displayDatepicker').val(thaiFormattedDate);
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
            let defaultDate = '{{ date('Y-m-d') }}';
            $('#submitDatepicker').val(defaultDate);
            const thaiFormattedDate = formatDateToThai(defaultDate);
            $('#displayDatepicker').val(thaiFormattedDate);

            /// วันที่เสนอราคา
            $('#displayDatepickerQuoteDate').datepicker({
                dateFormat: 'dd MM yy', // รูปแบบการแสดงผลใน Datepicker
                onSelect: function(dateText, inst) {
                    // แปลงวันที่ที่เลือกเป็นรูปแบบ Y-m-d และอัพเดต hidden input
                    const selectedDate = new Date(inst.selectedYear, inst.selectedMonth, inst
                        .selectedDay);
                    const isoDate = $.datepicker.formatDate('yy-mm-dd', selectedDate);
                    $('#submitDatepickerQuoteDate').val(isoDate);
                }
            });

            // กำหนดค่าเริ่มต้นให้กับ Datepicker quote_date
            let defaultDateQuoteDate = '{{ date('Y-m-d') }}';
            $('#submitDatepickerQuoteDate').val(defaultDateQuoteDate);
            const thaiFormattedDateQuoteDate = formatDateToThai(defaultDateQuoteDate);
            $('#displayDatepickerQuoteDate').val(thaiFormattedDateQuoteDate);

        });
    </script>