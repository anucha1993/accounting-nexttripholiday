@extends('layouts.template')

@section('content')
    <div class="email-app todo-box-container">
        <!-- -------------------------------------------------------------- -->
        <!-- Left Part -->
        <!-- -------------------------------------------------------------- -->
        <div class="left-part list-of-tasks bg-white">
            <a class="ti-menu ti-close btn btn-success show-left-part d-block d-md-none" href="javascript:void(0)"></a>
            <div class="scrollable" style="height: 100%">
                <div class="p-3">

                </div>
                <div class="divider"></div>
                <ul class="list-group">
                    <li>
                        <small class="p-3 d-block text-uppercase text-dark font-weight-medium"> ข้อมูลการขาย</small>
                    </li>
                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('saleInfo.info', $quotationModel->quote_id) }}" id="invoice-dashboard"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center">
                            <i class="far fa-file-alt"></i>
                            &nbsp; รายละเอียดรวม
                            <span
                                class="todo-badge badge bg-light-info text-info rounded-pill px-3 font-weight-medium ms-auto"></span>
                        </a>

                    </li>

                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('saleInfo.index', $quotationModel->quote_id) }}"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center btn-booking ">
                            <i class="far fa-file-alt"></i>
                            &nbsp; ข้อมูลการขาย
                            <span
                                class="todo-badge badge bg-light-info text-info rounded-pill px-3 font-weight-medium ms-auto"></span>
                        </a>

                    </li>
                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('payments', $quotationModel->quote_id) }}"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center"
                            id="current-task-important">
                            <i data-feather="star" class="feather-sm me-2"></i>
                            แจ้งชำระเงิน
                            <span
                                class="todo-badge badge rounded-pill px-3 bg-light-danger ms-auto text-danger font-weight-medium"></span>
                        </a>
                    </li>
                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('quotefile.index', $quotationModel->quote_id) }}"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center" id="current-task-done">
                            <i data-feather="send" class="feather-sm me-2"></i>
                            ไฟล์เอกสาร
                            <span
                                class="todo-badge badge rounded-pill px-3 text-success font-weight-medium bg-light-success ms-auto"></span>
                        </a>
                    </li>
                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('paymentWholesale.index', $quotationModel->quote_id) }}"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center" id="current-task-done">
                            <i data-feather="dollar-sign" class="feather-sm me-2"></i>
                            การชำระเงินโฮลเซลล์
                            <span
                                class="todo-badge badge rounded-pill px-3 text-success font-weight-medium bg-light-success ms-auto"></span>
                        </a>
                    </li>

                    <li class="list-group-item p-0 border-0">
                        <hr />
                    </li>
                </ul>


            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- Right Part -->
        <!-- -------------------------------------------------------------- -->

        <style>
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
        <br>
        <div class="right-part mail-list overflow-auto">
            <div id="todo-list-container">

                <div class="todo-listing ">
                    <div class="container border bg-white">
                        <h4 class="text-center my-4">สร้างใบแจ้งหนี้
                            <a target="_blank" href="{{ route('mpdf.quote', $quotationModel->quote_id) }}"
                                class="float-end">พิมพ์ <i class="text-danger fa fa-print"></i></a>
                        </h4>
                        <hr>
                        <form action="{{ route('invoice.store') }}" id="form-create" method="post">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="quote_id" value="{{$quotationModel->quote_id}}">
                            {{-- <div class="row table-custom ">
                                <div class="col-md-2 ms-auto">
                                    <label><b>เซลล์ผู้ขายแพคเกจ:</b> {{ $quotationModel->quote_sale }}</label>
                                    <select name="quote_sale" class="form-select">
                                        @forelse ($sales as $item)
                                            <option @if ($quotationModel->quote_sale === $item->id) selected @endif
                                                value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @empty
                                            <option value="">--Select Sale--</option>
                                        @endforelse
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>วันที่เสนอราคา</label>
                                    <input type="text" id="displayDatepickerQuoteDate"
                                          class="form-control" >

                                        <input type="hidden" id="submitDatepickerQuoteDate" name="quote_date"
                                        value="{{ $quotationModel->quote_date }}" class="form-control" >
                                </div>


                                <div class="col-md-2 ms-3">
                                    <label>วันที่สั่งซื้อ,จองแพคเกจ:</label>
                                    <input type="text" id="displayDatepicker" class="form-control">
                                    <input type="hidden" id="submitDatepicker" name="quote_booking_create" value="{{ date('Y-m-d', strtotime($quotationModel->quote_booking_create)) }}">
                                </div>
                                <div class="col-md-2">
                                    <label>เลขที่ใบจองทัวร์</label>
                                    <input type="text" name="quote_booking"
                                        value="{{ $quotationModel->quote_booking }}" class="form-control" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>รหัสทัวร์</label>
                                   @if ($quotationModel->quote_tour)
                                   <input type="text" name="quote_tour" value="{{ $quotationModel->quote_tour }}" class="form-control" readonly>
                                   
                                    @else
                                    <input type="text" name="quote_tour_code_old" value="{{ $quotationModel->quote_tour_code }}" class="form-control" readonly>
                                    @endif
                                </div>

                            </div>
                            <hr> --}}
                            {{-- <h5 class="text-danger">รายละเอียดแพคเกจทัวร์:</h5>

                            <div class="row table-custom">
                                <div class="col-md-6">
                                    <label>ชื่อแพคเกจทัวร์:</label>
                                    <input type="text" id="tourSearch" class="form-control" name="quote_tour_name"
                                        placeholder="ค้นหาแพคเกจทัวร์...ENTER เพื่อค้นหา"
                                        value="{{ $quotationModel->quote_tour_name }}">
                                    <div id="tourResults" class="list-group" style="">
                                    </div>
                                </div>

                                <input type="hidden" id="tour-code" name="quote_tour" value="{{ $quotationModel->quote_tour}}">


                                <div class="col-md-3">
                                    <label>ระยะเวลาทัวร์ (วัน/คืน): </label>
                                    <select name="quote_numday" class="form-select" id="numday">
                                        <option value="">--เลือกระยะเวลา--</option>
                                        @forelse ($numDays as $item)
                                            <option @if ($quotationModel->quote_numday === $item->num_day_total) selected @endif
                                                data-day="{{ $item->num_day_total }}" value="{{ $item->num_day_total }}">
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
                                            <option @if ($item->id === $quotationModel->quote_country) selected @endif
                                                value="{{ $item->id }}">
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
                                    <select name="quote_wholesale" class="form-select country-select select"
                                        style="width: 100%" id="wholesale">
                                        <option value="">--เลือกโฮลเซลล์--</option>
                                        @forelse ($wholesale as $item)
                                            <option @if ($quotationModel->quote_wholesale === $item->id) selected @endif
                                                value="{{ $item->id }}">
                                                {{ $item->code }}-{{ $item->wholesale_name_th }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>สายการบิน:</label>
                                    <select name="quote_airline" class="form-select country-select select"
                                        style="width: 100%">
                                        <option value="">--เลือกสายการบิน--</option>
                                        @forelse ($airline as $item)
                                            <option @if ($quotationModel->quote_airline === $item->id) selected @endif
                                                value="{{ $item->id }}">
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
                                        value="{{ date('Y-m-d', strtotime($quotationModel->quote_date_start)) }}">
                                </div>
                                <div class="col-md-3">
                                    <label>วันเดินทางกลับ: </label>
                                    <input type="text" class="form-control" id="date-end-display"
                                        placeholder="วันเดินทางกลับ...">
                                    <input type="hidden" id="date-end" name="quote_date_end"
                                        value="{{ date('Y-m-d', strtotime($quotationModel->quote_date_end)) }}">
                                </div>
                            </div>
                            <hr> --}}
                            <div class="row table-custom ">

                                <div class="col-md-2 ms-auto">
                                    <label for="">วันที่ออกใบแจ้งหนี้ : </label>
                                    <input type="date" class="form-control text-end" name="invoice_date"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="">เลขที่อ้างอิง : </label>
                                    <input style="background-color: #a3a3a32d" type="text" class="form-control text-end"
                                        name="invoice_quote" value="{{ $quotationModel->quote_number }}" readonly>
                                </div>

                                <div class="col-md-2">
                                    <label for="">รหัสทัวร์ : </label>
                                    @if ($quotationModel->quote_tour)
                                        <input style="background-color: #a3a3a32d" type="text"
                                            value="{{ $quotationModel->quote_tour }}" class="form-control text-end"
                                            readonly>
                                    @else
                                        <input style="background-color: #a3a3a32d" type="text"
                                            value="{{ $quotationModel->quote_tour_code }}" class="form-control text-end"
                                            readonly>
                                    @endif
                                </div>

                                <div class="col-md-2">
                                    <label for="">ใบจองทัวร์ : </label>
                                    <input style="background-color: #a3a3a32d" type="text" name="invoice_booking"
                                        class="form-control text-end" value="{{ $quotationModel->quote_booking }}"
                                        readonly>
                                </div>

                                <div class="col-md-2">
                                    <label><b>เซลล์ผู้ขายแพคเกจ:</b> {{ $quotationModel->quote_sale }}</label>
                                    <select style="background-color: #a3a3a32d" name="invoice_sale" class="form-select"
                                        @readonly(true)>
                                        <option value="{{ $sales->id }}" selected> {{ $sales->name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row table-custom">

                                <div class="col-md-2 ms-auto">
                                    <label for="">ยอด ใบเสนอราคา</label>
                                    <input type="text"  style="background-color: #2efc6c2d"
                                        class="form-control text-end"
                                        value="{{ number_format($quotationModel->quote_grand_total, 2, '.', ',') }}">

                                        <input type="hidden" id="total-quote" style="background-color: #2efc6c2d"
                                        class="form-control text-end"
                                        value="{{ $quotationModel->quote_grand_total}}">
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-danger">ข้อมูลลูกค้า:</h5>
                            <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
                            <div class="row table-custom">

                                <div class="col-md-3">
                                    <label class="">ชื่อลูกค้า:</label>
                                    <input type="text" class="form-control" name="customer_name"
                                        placeholder="ชื่อลูกค้า" value="{{ $customer->customer_name }}" required
                                        aria-describedby="basic-addon1">
                                </div>

                                <div class="col-md-3">
                                    <label>อีเมล์:</label>
                                    <input type="email" class="form-control" name="customer_email"
                                        value="{{ $customer->customer_email }}" placeholder="email@domail.com"
                                        aria-describedby="basic-addon1">
                                </div>

                                <div class="col-md-3">
                                    <label>เลขผู้เสียภาษี:</label>
                                    <input type="text" id="texid" class="form-control" name="customer_texid"
                                        mix="13" value="{{ $customer->customer_texid }}"
                                        placeholder="เลขประจำตัวผู้เสียภาษี" aria-describedby="basic-addon1">
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
                                                <input type="text" class="form-control" id="fax"
                                                    name="customer_fax" value="{{ $customer->customer_fax }}"
                                                    placeholder="เบอร์โทรศัพท์" aria-describedby="basic-addon1">
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

                                        </div>
                                        <div class="col-md-9">
                                            <label>ที่อยู่:</label>
                                            <textarea name="customer_address" id="address" class="form-control" cols="30" rows="4"
                                                placeholder="ที่อยู่">{{ $customer->customer_address }}</textarea>
                                        </div>
                                    </div>
                                </div>




                            </div>
                            <br>


                            <h5 style="background: #e0e0e0; padding: 5px">ข้อมูลค่าบริการ</h5>
                            <hr>
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

                                <div class="row item-row ">
                                    <div class="row ">
                                        <div class="col-md-1"><span class="row-number"> 1</span> <a
                                                href="javascript:void(0)" class="remove-row-btn text-danger"><span
                                                    class=" fa fa-trash"></span></a></div>
                                        <div class="col-md-4">
                                            <select name="product_id[]" class="form-select product-select"
                                                id="product-select" style="width: 100%;">
                                                <option value="">--เลือกสินค้า-- </option>
                                                @forelse ($products as  $product)
                                                    <option data-pax="{{ $product->product_pax }}"
                                                        value="{{ $product->id }}">
                                                        {{ $product->product_name }}
                                                        {{ $product->product_pax === 'Y' ? '(Pax)' : '' }}</option>
                                                @empty
                                                @endforelse
                                            </select>

                                        </div>
                                        <div class="col-md-1">

                                            <input type="checkbox" name="withholding_tax[]" class="vat-3"
                                                value="Y">
                                        </div>
                                        <div class="col-md-1" style="display: none">
                                            <select name="expense_type[]" class="form-select">
                                                <option selected value="income"> รายได้ </option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <select name="vat_status[]" class="vat-status form-select"
                                                style="width: 110%;">
                                                <option value="vat">Vat</option>
                                                <option value="nonvat">nonVat</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1"><input type="number" name="quantity[]" value="0.00"
                                                class="quantity form-control text-end" step="0.01"></div>
                                        <div class="col-md-2"><input type="number" name="price_per_unit[]"
                                                value="0.00" class="price-per-unit form-control text-end"
                                                step="0.01">
                                        </div>
                                        <div class="col-md-2"><input type="number" name="total_amount[]" value="0.00"
                                                class="total-amount form-control text-end" value="0" readonly>
                                        </div>
                                    </div>
                                </div>

                                {{-- เพิ่มรายการใหม่ --}}
                                <div class=" table-income">
                                    <div class="col-md-12 " style="text-align: left">

                                    </div>
                                </div>
                                <div class="add-row">
                                    <i class="fa fa-plus"></i><a id="add-row-service" href="javascript:void(0)"
                                        class="">
                                        เพิ่มรายการ</a>
                                </div>
                                <hr>

                                <div class="col-md-12 " style="text-align: left">
                                    <label class="text-danger">ส่วนลด</label>

                                </div>

                                {{-- ส่วนลด --}}

                                <div class="row item-row" data-row-id="1">
                                    <div class="col-md-1"><span class="row-number">2</span>
                                        <a href="javascript:void(0)" class="remove-row-btn text-danger"><span
                                                class=" fa fa-trash"></span></a>
                                    </div>
                                    <div class="col-md-4">
                                        <select name="product_id[]" class="form-select product-select"
                                            style="width: 100%;">
                                            <option value="">--เลือกส่วนลด--</option>
                                            @foreach ($productDiscount as $product)
                                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-1">
                                        <input type="checkbox" name="withholding_tax[]" class="vat-3" value="N" disabled>
                                    </div>
                                    <div class="col-md-1" style="display: none">
                                        <select name="expense_type[]" class="form-select">
                                            <option selected value="discount"> ส่วนลด </option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <select name="vat_status[]" class="vat-status form-select" style="width: 110%;">

                                            <option value="nonvat">nonVat</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1"><input type="number" name="quantity[]" value="0.00"
                                            class="quantity form-control text-end" step="0.01"></div>
                                    <div class="col-md-2"><input type="number" name="price_per_unit[]" value="0.00"
                                            class="price-per-unit form-control text-end" step="0.01">
                                    </div>
                                    <div class="col-md-2"><input type="number" name="total_amount[]" value="0.00"
                                            class="total-amount form-control text-end" value="0" readonly></div>
                                </div>



                                <div class="table-discount">
                                    <div class="">

                                    </div>
                                </div>

                                <div class="add-row">
                                    <i class="fa fa-plus"></i><a id="add-row-discount" href="javascript:void(0)"
                                        class="">
                                        เพิ่มส่วนลด</a>
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
                                                    <input type="radio" id="vat-include" name="vat_type"
                                                        @if ($quotationModel->vat_type === 'include') checked @endif value="include">
                                                    <label for="vat-include">คำนวณรวมกับราคาสินค้าและบริการ (VAT
                                                        Include)</label>
                                                </div>
                                                <div>
                                                    <input type="radio" id="vat-exclude" name="vat_type"
                                                        value="exclude" @if ($quotationModel->vat_type === 'exclude') checked @endif>
                                                    <label for="vat-exclude">คำนวณแยกกับราคาสินค้าและบริการ (VAT
                                                        Exclude)</label>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row summary-row">
                                                <div class="col-md-10">
                                                    <input type="checkbox" name="invoice_withholding_tax_status"
                                                        value="Y" id="withholding-tax"
                                                        @if ($quotationModel->quote_withholding_tax_status === 'Y') checked @endif>
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
                                            <textarea name="invoice_note" class="form-control" cols="30" rows="2">{{ $quotationModel->quote_note }}</textarea>
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
                                    {{-- <div class="row">
                                        <div class="col-md-12">
                                            <h5>เงือนไขการชำระเงิน</h5>
                                        </div>
                                        <div class="col-md-12 ">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="quote_payment_type" @if ($quotationModel->quote_payment_type === 'deposit') checked @endif
                                                        id="quote-payment-deposit" value="deposit"> <label
                                                        for="quote-payment-type"> เงินมัดจำ </label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="radio" name="quote_payment_type" @if ($quotationModel->quote_payment_type === 'full') checked @endif

                                                        id="quote-payment-full" value="full"> <label
                                                        for="quote-payment-type"> ชำระเต็มจำนวน </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4">
                                            <span for="">ภายในวันที่ </span>
                                            <input type="datetime-local" class="form-control" name="quote_payment_date" value="{{$quotationModel->quote_payment_date}}"
                                               >
                                        </div>
                                        <div class="col-md-4">
                                            <span for="">เรทเงินมัดจำ</span>
                                            <select name="quote_payment_price" class="form-select"
                                                id="quote-payment-price">
                                                <option  @if ($quotationModel->quote_payment_price == 0.0) selected @endif value="0.00">0.00</option>
                                                <option  @if ($quotationModel->quote_payment_price == 1000) selected @endif value="1000">1,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 1500) selected @endif value="1500">1,500</option>
                                                <option  @if ($quotationModel->quote_payment_price == 2000) selected @endif value="2000">2,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 3000) selected @endif value="3000">3,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 4000) selected @endif value="4000">4,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 5000) selected @endif value="5000" >5,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 6000) selected @endif value="6000">6,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 7000) selected @endif value="7000">7,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 8000) selected @endif value="8000">8,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 9000) selected @endif value="9000">9,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 10000) selected @endif value="10000">10,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 15000) selected @endif value="15000">15,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 20000) selected @endif value="20000">20,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 30000) selected @endif value="30000">30,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 24000) selected @endif value="24000">24,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 25000) selected @endif value="25000">25,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 28000) selected @endif value="28000">28,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 29000) selected @endif value="29000">29,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 34000) selected @endif value="34000">34,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 50000) selected @endif value="50000">50,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 70000) selected @endif value="70000">70,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 35000) selected @endif value="35000">35,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 40000) selected @endif value="40000">40,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 45000) selected @endif value="45000">45,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 80000) selected @endif value="80000">80,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 30500) selected @endif value="30500">30,500</option>
                                                <option  @if ($quotationModel->quote_payment_price == 35500) selected @endif value="35500">35,500</option>
                                                <option  @if ($quotationModel->quote_payment_price == 36000) selected @endif value="36000">36,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 38000) selected @endif value="38000">38,000</option>
                                                <option  @if ($quotationModel->quote_payment_price == 100000) selected @endif value="100000">100,000</option>
                                            </select>
                                        </div>
                                        <input type="hidden" id="booking-create-date" name="quote_booking_create"
                                            value="{{ date('Y-m-d', strtotime($quotationModel->quote_booking_create)) }}">
                                        <input type="hidden" id="booking-date"
                                            value="{{ date('Y-m-d', strtotime($quotationModel->quote_date_start)) }}">

                                        <div class="col-md-4 ">
                                            <span for="">จำนวนเงินที่ต้องชำระ</span>
                                            <input type="number" class="form-control pax-total"
                                                name="quote_payment_total" step="0.01" placeholder="0.00">
                                        </div>
                                    </div>
                                    <br>

                                    <span>วันที่จอง : <label class="text-info">
                                            {{ thaidate('j F Y', $quotationModel->quote_booking_create) }}</label></span>
                                    <span>วันที่เดินทาง <label class="text-info">
                                            {{ thaidate('j F Y', $quotationModel->quote_date_start) }}</label></span>
                                    </label></span>
                                    {{-- <input type="text" class="form-control pax-total" readonly
                                placeholder="ยอด Pax ที่คำนวณได้"> 
                                </div>
                            </div> --}}
                                    <div class="text-end mt-3">
                                        {{-- hidden --}}
                                        <input type="hidden" name="invoice_vat_exempted_amount">
                                        <input type="hidden" name="invoice_pre_tax_amount">
                                        <input type="hidden" name="invoice_discount">
                                        <input type="hidden" name="invoice_pre_vat_amount">
                                        <input type="hidden" name="invoice_vat">
                                        <input type="hidden" name="invoice_include_vat">
                                        <input type="hidden"   name="invoice_grand_total" id="invoice-grand-total">
                                        <input type="hidden" name="invoice_withholding_tax">
                                        <button type="submit" class="btn btn-primary btn-sm mx-3" form="form-create">
                                            <i class="fa fa-save"></i> สร้างใบแจ้งหนี้</button>
                                    </div>
                                    <br>
                                </div>

                                <br>
                            </div>



                        </form>
                    </div>
                </div>


            </div>

        </div>

      

        <script>
            $(document).ready(function() {
                // ใช้ฟังก์ชันนี้ถ้าคุณต้องการทำบางอย่างก่อน submit ฟอร์ม
                $('#form-create').on('submit', function(event) {
                    // ตรวจสอบหรือทำงานเพิ่มเติมก่อน submit
                    var isValid = false; // สมมติว่าคุณมีการตรวจสอบอะไรบางอย่าง
                    var quoteGrandTotal = $('#total-quote').val();
                    var quoteGrandTotalNew = $('#invoice-grand-total').val();

                    if (quoteGrandTotal === quoteGrandTotalNew) {
                        isValid = true;
                    }

                    if (!isValid) {
                        event.preventDefault(); // หยุดการ submit ฟอร์ม
                        Swal.fire({
                            title: "Oops...",
                            text: "ราคาใบแจ้งหนี้ ไม่ตรงกับ ใบเสนอราคา",
                            icon: "error"
                        });
                    }
                });
            });
        </script>



        <script>
            $(document).ready(function() {
                $('.country-select').select2();
                $('.product-select').select2();
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

                function updateRowNumbers(index) {
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

                    $('#quotation-table .item-row').each(function(index) {
                        const rowId = $(this).attr('data-row-id');
                        const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                        const pricePerUnit = parseFloat($(this).find('.price-per-unit').val()) || 0;
                        const vatStatus = $(this).find('.vat-status').val(); // ตรวจสอบค่าจาก select
                        const isVat3 = $(this).find('.vat-3').is(':checked'); // ตรวจสอบการติ๊ก checkbox
                        const expenseType = $(this).find('select[name="expense_type[]"]').val();
                        const vatMethod = $('input[name="vat_type"]:checked').val();

                        // คำนวณ total เบื้องต้น
                        let total = quantity * pricePerUnit;
                        let priceExcludingVat = total;

                        //console.log('data-row-id :' + rowId);

                        if (expenseType === 'discount') {
                            if (!rowId || rowId === 'undefined') {
                                console.log('Skipping row with undefined data-row-id');
                                return;
                            }
                            const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                            const pricePerUnit = parseFloat($(this).find('.price-per-unit').val()) || 0;

                            if (processedDiscountRows.includes(rowId)) {
                                console.log('Skipping duplicate discount for row: ' + rowId);
                                return;
                            }
                            let discountAmount = quantity * pricePerUnit; // คำนวณส่วนลดเฉพาะรายการ
                            sumDiscount += discountAmount; // เพิ่มค่าลงใน sumDiscount
                            processedDiscountRows.push(rowId);
                        }
                        // คำนวณ VAT 3% หากติ๊ก checkbox
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
                            sumPriceExcludingVatNonVat += total;
                        } else {
                            listVatTotal += total;
                            // คำนวณ VAT (Include หรือ Exclude)
                            if (vatMethod === 'include') {

                                const vatAmount = total - (total * 100 / 107);
                                priceExcludingVat = total - vatAmount;

                                sumPriceExcludingVat += priceExcludingVat;
                            } else {
                                sumPriceExcludingVat += total;
                            }

                        }
                        sumTotal += total;

                    });
                    // คำนวณยอดหลังส่วนลด
                    const afterDiscount = totalBeforeDiscount - sumDiscount;
                    let vatAmount = 0;
                    let preVatAmount = 0;
                    let grandTotal = 0;
                    let sumPreVat = 0;

                    if ($('input[name="vat_type"]:checked').val() === 'include') {
                        // VAT รวมอยู่ในยอดแล้ว

                        preVatAmount = sumPriceExcludingVat * 0.07;

                        sumPreVat = listVatTotal - (sumDiscount);
                        sumPreVat = sumPreVat * 100 / 107;
                        vatAmount = sumPreVat * 0.07;

                        grandTotal = sumPriceExcludingVatNonVat + sumPreVat + vatAmount;
                        // console.log('sumPreVat : '+sumPreVat);
                        // console.log('listVatTotal : '+listVatTotal);

                    } else {
                        // คำนวณ VAT 7% กรณี Exclude VAT


                        sumPreVat = listVatTotal - (sumDiscount);
                        vatAmount = sumPreVat * 0.07;
                        grandTotal = sumPriceExcludingVatNonVat + sumPreVat + vatAmount;
                    }

                    // คำนวณหักภาษี ณ ที่จ่าย (Withholding Tax)
                    const withholdingTax = $('#withholding-tax').is(':checked') ? (sumPreVat + vatAmount) * 0.03 : 0;

                    //quote_withholding_tax
                    $('input[name="quote_withholding_tax"]').val(withholdingTax.toFixed(2));

                    // อัปเดตค่าทั้งหมดที่จะแสดงในหน้าจอ
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

                    //ยอดรวมยกเว้นภาษี
                    $('#sum-total-nonvat').text(formatNumber((sumPriceExcludingVatNonVat - sumDiscount).toFixed(2)));
                    $('input[name="invoice_vat_exempted_amount"]').val((sumPriceExcludingVatNonVat - sumDiscount).toFixed(
                        2));

                    //ยอดรวมยกเว้นภาษี
                    $('#sum-total-vat').text(formatNumber(listVatTotal.toFixed(2)));
                    $('input[name="invoice_pre_tax_amount"]').val(listVatTotal.toFixed(2));

                    //ส่วนลด / Discount 
                    $('#sum-discount').text(formatNumber((sumDiscount).toFixed(2)));
                    $('input[name="invoice_discount"]').val(sumDiscount.toFixed(2));

                    //ราคาก่อนภาษีมูลค่าเพิ่ม
                    $('#sum-pre-vat').text(formatNumber(sumPreVat.toFixed(2)));
                    $('input[name="invoice_pre_vat_amount"]').val(sumPreVat.toFixed(2));

                    // VAT 7 %
                    $('#vat-amount').text(formatNumber(vatAmount.toFixed(2)));
                    $('input[name="invoice_vat"]').val(vatAmount.toFixed(2));


                    //ราคารวมภาษีมูลค่าเพิ่ม / Include VAT sum-include-vat
                    $('#sum-include-vat').text(formatNumber((sumPreVat + vatAmount).toFixed(2)));
                    $('input[name="invoice_include_vat"]').val((sumPreVat + vatAmount).toFixed(2));

                    //จำนวนเงินรวมทั้งสิ้น / Grand Total
                    $('#grand-total').text(formatNumber(grandTotal - sumDiscount.toFixed(2)));
                    $('input[name="invoice_grand_total"]').val((grandTotal - sumDiscount).toFixed(2));

                }

                // Initialize Select2 สำหรับทุก select element ที่มี class .product-select
                function initializeSelect2() {
                    $('#quotation-table .product-select').each(function() {
                        if (!$(this).hasClass("select2-hidden-accessible")) {
                            $(this).select2({
                                width: 'resolve' // ตั้งค่า width ให้กับ select2
                            });
                        }
                    });
                }

                // ฟังก์ชันเพิ่มแถวใหม่สำหรับค่าบริการ
                function addNewServiceRow() {
                    const newRow = `
            <div class="row item-row" >
                <div class="col-md-1"><span class="row-number"></span>
                    <a href="javascript:void(0)" class="remove-row-btn text-danger"><span class=" fa fa-trash"></span></a>
                 </div>
                <div class="col-md-4">
                    <select name="product_id[]" class="form-select product-select" style="width: 100%;">
                        <option value="">--เลือกสินค้า--</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-1">
                    <input type="checkbox" name="withholding_tax[]" class="vat-3" value="Y">
                </div>
                 <div class="col-md-1" style="display: none">
                                        <select name="expense_type[]" class="form-select">
                                            <option selected value="income"> รายได้ </option>
                                        </select>
                                    </div>
               <div class="col-md-1 text-center">
    <select name="vat_status[]" class="vat-status form-select" style="width: 110%;">
        <option value="vat">Vat</option>
                                                <option value="nonvat">nonVat</option>
    </select>
</div>
                <div class="col-md-1"><input type="number" name="quantity[]" class="quantity form-control text-end" value="1" step="0.01"></div>
                <div class="col-md-2"><input type="number" name="price_per_unit[]" class="price-per-unit form-control text-end" value="0" step="0.01"></div>
                <div class="col-md-2"><input type="number" name="total_amount[]" class="total-amount form-control text-end" value="0" readonly></div>
            </div>
            
            `;

                    // Append แถวใหม่ไปที่ table-income
                    $('.table-income').append(newRow);

                    // Reinitialize Select2 สำหรับทุก select element
                    initializeSelect2();

                    // อัปเดตลำดับแถว (ถ้ามีฟังก์ชันนี้อยู่)
                    updateRowNumbers();
                }

                // ฟังก์ชันเพิ่มแถวใหม่สำหรับส่วนลด
                let currentRowId = 0;

                function addNewDiscountRow() {
                    ++currentRowId;
                    const newRow = `
            <div class="row item-row" data-row-id="${currentRowId}">
                <div class="col-md-1"><span class="row-number"></span>
                    <a href="javascript:void(0)" class="remove-row-btn text-danger"><span class=" fa fa-trash"></span></a>
                 </div>
                <div class="col-md-4">
                    <select name="product_id[]" class="form-select product-select" style="width: 100%;">
                        <option value="">--เลือกส่วนลด--</option>
                        @foreach ($productDiscount as $product)
                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                            
                <div class="col-md-1">
                    <input type="checkbox" name="withholding_tax[]" class="vat-3" value="N" disabled>
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
                <div class="col-md-1"><input type="number" name="quantity[]" class="quantity form-control text-end" value="1" step="0.01"></div>
                <div class="col-md-2"><input type="number" name="price_per_unit[]" class="price-per-unit form-control text-end" value="0" step="0.01"></div>
                <div class="col-md-2"><input type="number" name="total_amount[]" class="total-amount form-control text-end" value="0" readonly></div>
            </div>`;

                    // Append แถวใหม่ไปที่ table-discount
                    $('.table-discount').append(newRow);

                    // Reinitialize Select2 สำหรับทุก select element
                    initializeSelect2();

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
                initializeSelect2();
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
                //checkPaymentCondition();
                // ตั้งค่าฟิลด์ "ภายในวันที่" เมื่อโหลดหน้าเว็บ
                //setPaymentDueDate();
                // ตรวจสอบเมื่อผู้ใช้เลือกชำระเงินเต็มจำนวน
                function checkedPaymentFull() {
                    var QuoteTotalGrand = $('#quote-grand-total').val();
                    console.log("QuoteTotalGrand : " + QuoteTotalGrand);
                    if ($('#quote-payment-full').is(':checked')) {
                        $('#quote-payment-price').prop('disabled', true); // ปิด dropdown เรทเงินมัดจำ
                        $('.pax-total').val(QuoteTotalGrand);
                    }
                }
                $('#quote-payment-full').on('change', function() {

                    checkedPaymentFull();
                });
                checkedPaymentFull();
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
                checkedPaymentFull()
                calculatePaxAndTotal();
            });
        </script>



        <script>
            // $(function() {
            //     // ตั้งค่าภาษาไทยให้กับ Datepicker
            //     $.datepicker.regional['th'] = {
            //         closeText: 'ปิด',
            //         prevText: 'ย้อน',
            //         nextText: 'ถัดไป',
            //         currentText: 'วันนี้',
            //         monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            //             'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            //         ],
            //         monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
            //             'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
            //         ],
            //         dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
            //         dayNamesShort: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
            //         dayNamesMin: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
            //         weekHeader: 'Wk',
            //         dateFormat: 'dd MM yy', // รูปแบบการแสดงผลเป็น วัน เดือน ปี
            //         firstDay: 0,
            //         isRTL: false,
            //         showMonthAfterYear: false,
            //         yearSuffix: ''
            //     };
            //     $.datepicker.setDefaults($.datepicker.regional['th']);

            //     // ฟังก์ชันแปลงวันที่จาก yyyy-mm-dd เป็นรูปแบบ dd MM yy
            //     function setThaiDate(inputSelector, date) {
            //         if (date) {
            //             var formattedDate = $.datepicker.formatDate('dd MM yy', new Date(date));
            //             $(inputSelector).datepicker('setDate', formattedDate); // แสดงผลใน input
            //         }
            //     }

            //     // ฟังก์ชันคำนวณวันกลับ
            //     function calculateEndDate() {
            //         var numDays = parseInt(document.querySelector('#numday option:checked').getAttribute('data-day')) ||
            //             0;
            //         var startDate = $('#date-start').val();

            //         if (numDays > 0 && startDate) {
            //             var start = new Date(startDate);
            //             var endDate = new Date(start);
            //             endDate.setDate(start.getDate() + numDays - 1); // คำนวณวันกลับตามจำนวนวันที่เลือก

            //             // แปลงวันกลับเป็นภาษาไทยและแสดงใน input
            //             $('#date-end-display').datepicker('setDate', endDate);
            //             $('#date-end').val($.datepicker.formatDate('yy-mm-dd', endDate)); // ส่งค่าแบบ yyyy-mm-dd
            //         }
            //     }

            //     // ตั้งค่า Datepicker สำหรับวันเริ่มต้น
            //     $('#date-start-display').datepicker({
            //         dateFormat: 'dd MM yy', // รูปแบบแสดงผลเป็น วัน เดือน ปี
            //         onSelect: function(dateText) {
            //             var isoDate = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker('getDate'));
            //             $('#date-start').val(isoDate); // เก็บค่าวันที่ในรูปแบบ yyyy-mm-dd
            //             calculateEndDate(); // คำนวณวันกลับทันทีเมื่อเลือกวันออกเดินทาง
            //         }
            //     });

            //     // ตั้งค่า Datepicker สำหรับวันกลับ (การแสดงผล)
            //     $('#date-end-display').datepicker({
            //         dateFormat: 'dd MM yy' // รูปแบบแสดงผลเป็น วัน เดือน ปี
            //     });

            //     // กำหนดให้คำนวณวันกลับเมื่อเปลี่ยนจำนวนวัน
            //     document.getElementById('numday').addEventListener('change', calculateEndDate);

            //     // ตรวจสอบและแสดงวันที่เริ่มต้นและวันกลับในรูปแบบภาษาไทยหากมีข้อมูล
            //     var startDate = $('#date-start').val();
            //     var endDate = $('#date-end').val();

            //     setThaiDate('#date-start-display', startDate);
            //     setThaiDate('#date-end-display', endDate);
            // });

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

                        // แสดงวันสิ้นสุดใน input
                        $('#date-end-display').datepicker('setDate', endDate);
                        $('#date-end').val($.datepicker.formatDate('yy-mm-dd', endDate)); // เก็บค่าแบบ yyyy-mm-dd
                    }
                }

                // ฟังก์ชันคำนวณวันเริ่มต้น
                function calculateStartDate() {
                    var numDays = parseInt($('#numday option:selected').data('day')) || 0;
                    var endDate = $('#date-end').val();

                    if (numDays > 0 && endDate) {
                        var end = new Date(endDate);
                        var startDate = new Date(end);
                        startDate.setDate(end.getDate() - numDays + 1); // คำนวณวันเริ่มต้น

                        // แสดงวันเริ่มต้นใน input
                        $('#date-start-display').datepicker('setDate', startDate);
                        $('#date-start').val($.datepicker.formatDate('yy-mm-dd', startDate)); // เก็บค่าแบบ yyyy-mm-dd
                    }
                }

                // ตั้งค่า Datepicker สำหรับวันเริ่มต้น
                $('#date-start-display').datepicker({
                    dateFormat: 'dd MM yy',
                    onSelect: function(dateText) {
                        var isoDate = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker('getDate'));
                        $('#date-start').val(isoDate);
                        calculateEndDate(); // คำนวณวันสิ้นสุดเมื่อเลือกวันเริ่มต้น
                    }
                });

                // ตั้งค่า Datepicker สำหรับวันสิ้นสุด
                $('#date-end-display').datepicker({
                    dateFormat: 'dd MM yy',
                    onSelect: function(dateText) {
                        var isoDate = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker('getDate'));
                        $('#date-end').val(isoDate);
                        calculateStartDate(); // คำนวณวันเริ่มต้นเมื่อเลือกวันสิ้นสุด
                    }
                });

                // กำหนดให้คำนวณวันสิ้นสุดเมื่อเปลี่ยนจำนวนวัน
                $('#numday').on('change', function() {
                    if ($('#date-start').val()) {
                        calculateEndDate();
                    } else if ($('#date-end').val()) {
                        calculateStartDate();
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
        </script>


        {{-- API TOUR --}}
        <script>
            $(document).ready(function() {

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
                                        $('#tourResults').append(`<a href="#" id="tour-select" class="list-group-item list-group-item-action"  data-wholesale="${item.wholesale_id}" data-code="${item.code}" data-name="${item.code} - ${item.name}">${item.code} - ${item.code1} - ${item.name}</a>
                            `);
                                    });
                                } else {
                                    // ถ้าไม่มีข้อมูล
                                    $('#tourResults').append(
                                        `<a href="#" class="list-group-item list-group-item-action" data-name="${searchTerm}">กำหนดเอง</a>`
                                    );
                                }
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
                    $('#tourSearch').val(selectedText); // แสดงชื่อแพคเกจที่เลือกใน input
                    $('#tour-code').val(selectedCode); // เก็บค่า code ใน hidden input หรือค่าว่าง
                    $('#tourResults').empty(); // ล้างผลลัพธ์การค้นหา
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
                    }
                });

                // กำหนดค่าเริ่มต้นให้กับ Datepicker (แสดงเป็นภาษาไทย) และ hidden input
                let defaultDate = '{{ $quotationModel->quote_booking_create }}';
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
                let defaultDateQuoteDate = '{{ $quotationModel->quote_date }}';
                $('#submitDatepickerQuoteDate').val(defaultDateQuoteDate);
                const thaiFormattedDateQuoteDate = formatDateToThai(defaultDateQuoteDate);
                $('#displayDatepickerQuoteDate').val(thaiFormattedDateQuoteDate);

            });
        </script>
    @endsection
