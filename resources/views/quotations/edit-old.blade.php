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
                        <a href="{{route('quotefile.index',$quotationModel->quote_id)}}" class="todo-link list-group-item-action p-3 d-flex align-items-center"
                            id="current-task-done">
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
            .table-custom {
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
        </style>
        <br>


        <form action="{{ route('quote.update', $quotationModel->quote_id) }}" id="form-update" method="post">
            @csrf
            @method('PUT')
            <div class="right-part mail-list overflow-auto">
                <div id="todo-list-container">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
                            role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>Success - </strong>{{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                            role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>Error - </strong>{{ session('error') }}
                        </div>
                    @endif

                    <!-- Todo list-->
                    <div class="todo-listing ">
                        <div class="container border bg-white">
                            <h4 class="text-center my-4">สร้างใบเสนอราคา / ใบจองทัวร์  
                                <a target="_blank" href="{{route('mpdf.quote',$quotationModel->quote_id)}}" class="float-end" >พิมพ์ <i class="text-danger fa fa-print"></i></a> </h4>
                           
                            <div class="row">
                                <div class="col-md-8 border" style="padding: 10px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b>Customer ID :</b> <span
                                                style="margin: 20px;">CUS-000{{ $customer->customer_id }}</span> <a
                                                href="javascript:void(0)" id="edit-customer"
                                                data-id="{{ $customer->customer_id }}" data-bs-toggle="modal"
                                                data-bs-target="#bs-example-modal-xlg">แก้ไขข้อมูลลูกค้า</a></br>
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
                                                {{ date('d-M-Y', strtotime($quotationModel->created_at)) }}</span></br>
                                            <b>Booking No :</b> <span style="margin: 5px;">
                                                {{ $quotationModel->quote_booking }}</span></br>
                                            <b>Sale :</b> <span style="margin: 53px;"> {{ $sale->name }}</span></br>
                                            <b>Email :</b> <span style="margin: 45px;"> {{ $sale->email }}</span></br>
                                            <b>Tour Code :</b> <span style="margin: 15px;"> {{ $tour->code }}</span></br>
                                            <b>Airline :</b> <span style="margin: 40px;">
                                                {{ $airline->travel_name }}</span></br>
                                            <b>Quote No :</b> <span style="margin: 20px;" class="text-white bg-dark">
                                                {{ $quotationModel->quote_number }}</span></br>

                                            <b>Status :</b>
                                            @if ($quotationModel->quote_status === 'wait')
                                                <span style="margin-left:40px"
                                                    class="badge rounded-pill bg-primary">รอชำระ</span>
                                            @endif
                                            @if ($quotationModel->quote_status === 'success')
                                                <span style="margin-left:40px"
                                                    class="badge rounded-pill bg-success">ชำระเงินครบแล้ว</span>
                                            @endif

                                            @if ($quotationModel->quote_status === 'payment')
                                                <span style="margin-left:40px"
                                                    class="badge rounded-pill bg-info">ชำระเงินบางจำนวน</span>
                                            @endif

                                            @if ($quotationModel->quote_status === 'cancel')
                                                <span style="margin-left:40px"
                                                    class="badge rounded-pill bg-info">ยกเลิก</span>
                                            @endif


                                            </br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>


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
                                    <div class="row item-row">
                                        <div class="col-md-1"><span class="row-number"> {{ $key + 1 }}</span> <a
                                                href="javascript:void(0)" class="remove-row-btn text-danger"><span
                                                    class=" fa fa-trash"></span></a></div>
                                        <div class="col-md-3">

                                            <select name="product_id[]" class="form-select product-select">
                                                <option value="">กรุณาเลือกรายการ</option>
                                                @forelse ($products as $product)
                                                    <option data-pax="{{ $product->product_pax }}"
                                                        @if ($item->product_id == $product->id) selected @endif
                                                        value="{{ $product->id }}">{{ $product->product_name }}
                                                        {{ $product->product_pax === 'Y' ? '(Pax)' : '' }}</option>
                                                @empty
                                                @endforelse
                                            </select>

                                        </div>
                                        <div class="col-md-2">


                                            <select name="expense_type[]" class="form-select">
                                                <option value="">กรุณาเลือกรายการ</option>
                                                <option @if ($item->expense_type === 'income') selected @endif value="income">
                                                    รายได้
                                                </option>
                                                <option @if ($item->expense_type === 'discount') selected @endif value="discount">
                                                    ส่วนลด</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 text-center"><input type="checkbox" name="non_vat[]"
                                                @if ($item->vat === 'Y') checked @endif class="non-vat">
                                        </div>
                                        <div class="col-md-1"><input type="number" name="quantity[]"
                                                class="quantity form-control text-end" value="{{ $item->product_qty }}"
                                                step="0.01"></div>
                                        <div class="col-md-2"><input type="number" name="price_per_unit[]"
                                                class="price-per-unit form-control text-end"
                                                value="{{ $item->product_price }}" step="0.01">
                                        </div>
                                        <div class="col-md-2"><input type="number" name="total_amount[]"
                                                class="total-amount form-control text-end" value="0" readonly></div>
                                    </div>



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
                                                    <input type="radio" id="vat-include" name="vat_type"
                                                        value="include" @if ($quotationModel->vat_type === 'include') checked @endif>
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
                                                    <input type="checkbox" name="vat3_status" value="Y"
                                                        id="withholding-tax"
                                                        @if ($quotationModel->vat_3_status === 'Y') checked @endif> <span
                                                        class="">
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
                                                    <input type="radio" name="quote_payment_type" @if($quotationModel->quote_payment_type === 'deposit') checked @endif
                                                        id="quote-payment-deposit" value="deposit"> <label
                                                        for="quote-payment-type"> เงินมัดจำ </label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="radio" name="quote_payment_type" @if($quotationModel->quote_payment_type === 'full') checked @endif
                                                        id="quote-payment-full" value="full"> <label
                                                        for="quote-payment-type"> ชำระเต็มจำนวน </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4">
                                            <span for="">ภายในวันที่</span>
                                            <input type="datetime-local" class="form-control" name="quote_payment_date" value="{{$quotationModel->quot_payment_date}}">
                                        </div>
                                        <div class="col-md-4">
                                            <span for="">เรทเงินมัดจำ</span>
                                            <select name="quote_payment_price" class="form-select" 
                                                id="quote-payment-price">
                                                <option  @if($quotationModel->quote_payment_price == 0.00) selected @endif value="0.00">0.00</option>
                                                <option  @if($quotationModel->quote_payment_price == 1000) selected @endif value="1000">1,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 1500) selected @endif value="1500">1,500</option>
                                                <option  @if($quotationModel->quote_payment_price == 2000) selected @endif value="2000">2,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 3000) selected @endif value="3000">3,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 4000) selected @endif value="4000">4,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 5000) selected @endif value="5000" >5,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 6000) selected @endif value="6000">6,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 7000) selected @endif value="7000">7,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 8000) selected @endif value="8000">8,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 9000) selected @endif value="9000">9,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 10000) selected @endif value="10000">10,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 15000) selected @endif value="15000">15,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 20000) selected @endif value="20000">20,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 30000) selected @endif value="30000">30,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 24000) selected @endif value="24000">24,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 25000) selected @endif value="25000">25,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 28000) selected @endif value="28000">28,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 29000) selected @endif value="29000">29,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 34000) selected @endif value="34000">34,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 50000) selected @endif value="50000">50,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 70000) selected @endif value="70000">70,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 35000) selected @endif value="35000">35,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 40000) selected @endif value="40000">40,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 45000) selected @endif value="45000">45,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 80000) selected @endif value="80000">80,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 30500) selected @endif value="30500">30,500</option>
                                                <option  @if($quotationModel->quote_payment_price == 35500) selected @endif value="35500">35,500</option>
                                                <option  @if($quotationModel->quote_payment_price == 36000) selected @endif value="36000">36,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 38000) selected @endif value="38000">38,000</option>
                                                <option  @if($quotationModel->quote_payment_price == 100000) selected @endif value="100000">100,000</option>
                                            </select>
                                        </div>
                                        <input type="hidden" id="booking-create-date"
                                            value="{{ date('Y-m-d', strtotime($bookingModel->created_at)) }}">
                                        <input type="hidden" id="booking-date"
                                            value="{{ date('Y-m-d', strtotime($bookingModel->created_at)) }}">
                                        <div class="col-md-4 ">
                                            <span for="">จำนวนเงินที่ต้องชำระ</span>
                                            <input type="number" class="form-control pax-total" value="{{$quotationModel->quote_payment_total}}"
                                                name="quote_payment_total" step="0.01" placeholder="0.00">
                                        </div>
                                    </div>
                                    <br>

                                    <span>วันที่จอง : <label class="text-info">
                                            {{ thaidate('j F Y', $bookingModel->created_at) }}</label></span>
                                    <span>วันที่เดินทาง <label class="text-info">
                                            {{ thaidate('j F Y', $bookingModel->start_date) }}</label></span>
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
                </div>
            </div>



        </form>
    </div>


    <!-- sample modal content -->
    <div class="modal fade" id="bs-example-modal-xlg" tabindex="-1" aria-labelledby="bs-example-modal-lg"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        แก้ไขข้อมูลลูกค้า : CUS-000<span id="customer-id"></span>
                    </h4>
                    <hr>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="" id="customer_id" value="{{ $customer->customer_id }}">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label>ชื่อลูกค้า :</label>
                            <input type="text" id="customer_name" class="form-control">
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>ที่อยู่ :</label>
                            <textarea id="customer_address" class="form-control" cols="30" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Tax ID :</label>
                            <input type="text" id="customer_texid" class="form-control">
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>เบอร์ติดต่อ</label>
                            <input type="text" id="customer_tel" class="form-control">
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Email : </label>
                            <input type="text" id="customer_email" class="form-control">
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Fax : </label>
                            <input type="text" id="customer_fax" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="update-customer"
                        class="
           btn btn-light-success

           font-weight-medium
           waves-effect
           text-start
         "
                        data-bs-dismiss="modal">
                        อัพเดท
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


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

                const withholdingTax = $('#withholding-tax').is(':checked') ? withholdingTaxTotal  * 0.03 : 0;
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
                const newRow = $('#quotation-table .item-row:first').clone();
                newRow.find('input').val(0);
                newRow.find('select').val('');
                newRow.find('input[type="checkbox"]').prop('checked', false);
                newRow.appendTo('#quotation-table');
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

            // Customer Edit
            $('#edit-customer').on('click', function(event) {
                event.preventDefault();
                var customerID = $(this).attr('data-id');

                $.ajax({
                    url: '{{ route('customer.ajaxEdit') }}',
                    method: 'POST',
                    data: {
                        customerID: customerID,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#customer-id').html(response.customer_id);
                        $('#customer_name').val(response.customer_name);
                        $('#customer_address').val(response.customer_address);
                        $('#customer_texid').val(response.customer_texid);
                        $('#customer_tel').val(response.customer_tel);
                        $('#customer_fax').val(response.customer_fax);
                        $('#customer_email').val(response.customer_email);
                    }
                })
            });

            //update Customer
            $('#update-customer').on('click', function(event) {
                event.preventDefault();
                var customer_id = $('#customer_id').val();
                var customer_name = $('#customer_name').val();
                var customer_address = $('#customer_address').val();
                var customer_texid = $('#customer_texid').val();
                var customer_tel = $('#customer_tel').val();
                var customer_fax = $('#customer_fax').val();
                var customer_email = $('#customer_email').val();

                console.log(customer_id);


                $.ajax({
                    url: '{{ route('customer.ajaxUpdate') }}',
                    method: 'POST',
                    data: {
                        customer_id: customer_id,
                        customer_name: customer_name,
                        customer_address: customer_address,
                        customer_texid: customer_texid,
                        customer_tel: customer_tel,
                        customer_fax: customer_fax,
                        customer_email: customer_email,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);

                        $('#customer-id-label').html(response.customer_id);
                        $('#customer_name-label').html(response.customer_name);
                        $('#customer_address-label').html(response.customer_address);
                        $('#customer_texid-label').html(response.customer_texid);
                        $('#customer_tel-label').html(response.customer_tel);
                        $('#customer_fax-label').html(response.customer_fax);
                        $('#customer_email-label').html(response.customer_email);
                    }
                })
            });

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
            calculatePaxAndTotal()

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


@endsection
