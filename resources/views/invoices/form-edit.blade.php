@extends('layouts.template')

@section('content')
    <div class="email-app todo-box-container">
        <!-- -------------------------------------------------------------- -->
        <!-- Left Part -->
        <!-- -------------------------------------------------------------- -->


        <form action="{{ route('invoice.update',$invoiceModel->invoice_id) }}" id="form-create" method="post">
            @csrf
            @method('put')


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
                            <a href="javascript:void(0)" id="invoice-dashboard"
                                class="todo-link list-group-item-action p-3 d-flex align-items-center">
                                <i class="far fa-file-alt"></i>
                                &nbsp; รายละเอียดรวม
                                <span
                                    class="todo-badge badge bg-light-info text-info rounded-pill px-3 font-weight-medium ms-auto"></span>
                            </a>

                        </li>

                        <li class="list-group-item p-0 border-0">
                            <a href="{{ route('saleInfo.index', $quotationModel->quote_id) }}"
                                class="todo-link list-group-item-action p-3 d-flex align-items-center btn-booking">
                                <i class="far fa-file-alt"></i>
                                &nbsp; ข้อมูลการขาย
                                <span
                                    class="todo-badge badge bg-light-info text-info rounded-pill px-3 font-weight-medium ms-auto"></span>
                            </a>

                        </li>
                        <li class="list-group-item p-0 border-0">
                            <a href="javascript:void(0)"
                                class="todo-link list-group-item-action p-3 d-flex align-items-center"
                                id="current-task-important">
                                <i data-feather="star" class="feather-sm me-2"></i>
                                Important
                                <span
                                    class="todo-badge badge rounded-pill px-3 bg-light-danger ms-auto text-danger font-weight-medium"></span>
                            </a>
                        </li>
                        <li class="list-group-item p-0 border-0">
                            <a href="javascript:void(0)"
                                class="todo-link list-group-item-action p-3 d-flex align-items-center"
                                id="current-task-done">
                                <i data-feather="send" class="feather-sm me-2"></i>
                                Complete
                                <span
                                    class="todo-badge badge rounded-pill px-3 text-success font-weight-medium bg-light-success ms-auto"></span>
                            </a>
                        </li>
                        <li class="list-group-item p-0 border-0">
                            <hr />
                        </li>


                        <li class="list-group-item p-0 border-0">
                            <a href="javascript:void(0)" class="list-group-item-action p-3 d-flex align-items-center"
                                id="current-todo-delete">
                                <i data-feather="trash-2" class="feather-sm me-2"></i>
                                Trash
                            </a>
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

                .date-create {
                    max-width: 190px;
                    padding: 2px;
                    border: none;
                    border-radius: 4px;
                    box-shadow: 2px 2px 7px 0 rgb(0, 0, 0, 0.2);
                    outline: none;
                    color: dimgray;

                }
            </style>
            <br>


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
                            <h4 class="text-center my-4">แก้ไขเแจ้งหนี้ </h4>
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
                                        <div class="col-md-12 ">
                                            <b>Date :</b> <span style="margin: 50px;">
                                                <input type="date" class="date-create" name="invoice_date" value="{{$invoiceModel->invoice_date}}"></span></br>
                                            <b>Booking No :</b> <span style="margin: 5px;">
                                                {{ $invoiceModel->invoice_booking }}</span></br>
                                            <b>Sale :</b> <span style="margin: 53px;"> {{ $sale->name }}</span></br>
                                            <b>Email :</b> <span style="margin: 45px;"> {{ $sale->email }}</span></br>
                                            <b>Tour Code :</b> <span style="margin: 15px;"> {{ $tour->code }}</span></br>
                                            <b>Airline :</b> <span style="margin: 40px;">
                                                {{ $airline->travel_name }}</span></br>
                                            <b>Invoice No :</b> <span style="margin: 12px;" class="text-white bg-dark">
                                                {{ $invoiceModel->invoice_number }}</span></br>

                                            <b>ยอดยกมา : <span style="margin: 15px;"
                                                    class="bg-primary text-white">{{ number_format($invoiceModel->invoice_grand_total, 2, '.', ',') }}</span></b>


                                            </br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $invoiceModel->invoice_grand_total }}" id="quoteGrandTotal">
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
                                @forelse ($invoiceProducts as $key => $item)
    
                                        <div class="row item-row">
                                            <div class="col-md-1"><span class="row-number"> {{ $key + 1 }}</span> <a
                                                    href="javascript:void(0)" class="remove-row-btn text-danger"><span
                                                        class=" fa fa-trash"></span></a></div>
                                            <div class="col-md-3">

                                                <select name="product_id[]" class="form-select">
                                                    <option value="">กรุณาเลือกรายการ</option>
                                                    @forelse ($products as $product)
                                                        <option @if ($item->product_id == $product->id) selected @endif
                                                            value="{{ $product->id }}">{{ $product->product_name }}
                                                            {{ $product->product_pax === 'Y' ? '(Pax)' : '' }}</option>
                                                    @empty
                                                    @endforelse
                                                </select>

                                            </div>
                                            <div class="col-md-2">


                                                <select name="expense_type[]" class="form-select">
                                                    <option value="">กรุณาเลือกรายการ</option>
                                                    <option @if ($item->expense_type === 'income') selected @endif
                                                        value="income">
                                                        รายได้
                                                    </option>
                                                    <option @if ($item->expense_type === 'discount') selected @endif
                                                        value="discount">
                                                        ส่วนลด</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 text-center">
                                              

                                                <input type="checkbox" name="non_vat[]" 
                                                    @if ($item->vat === 'Y') checked @endif class="non-vat" >
                                            </div>
                                            <div class="col-md-1"><input type="number" name="quantity[]"
                                                    class="quantity form-control text-end"
                                                    value="{{ $item->product_qty }}" step="0.01"></div>
                                            <div class="col-md-2"><input type="number" name="price_per_unit[]"
                                                    class="price-per-unit form-control text-end"
                                                    value="{{ $item->product_price }}" step="0.01">
                                            </div>
                                            <div class="col-md-2"><input type="number" name="total_amount[]"
                                                    class="total-amount form-control text-end" value="0" readonly>
                                            </div>
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
                                                        value="include" @if ($invoiceModel->vat_type === 'include') checked @endif>
                                                    <label for="vat-include">คำนวณรวมกับราคาสินค้าและบริการ (VAT
                                                        Include)</label>
                                                </div>
                                                <div>
                                                    <input type="radio" id="vat-exclude" name="vat_type"
                                                        value="exclude" @if ($invoiceModel->vat_type === 'exclude') checked @endif>
                                                    <label for="vat-exclude">คำนวณแยกกับราคาสินค้าและบริการ (VAT
                                                        Exclude)</label>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row summary-row">
                                                <div class="col-md-10">
                                                    <input type="checkbox" name="vat_3_status" value="Y"
                                                        id="withholding-tax"
                                                        @if ($invoiceModel->vat_3_status === 'Y') checked @endif> <span
                                                        class="">
                                                        คิดภาษีหัก ณ ที่จ่าย 3% (คำนวณจากยอด ราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT
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
                                            <textarea name="invoice_note" class="form-control" cols="30" rows="2">{{ $invoiceModel->invoice_note }}</textarea>
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


                                </div>
                            </div>
                            <div class="text-end mt-3">
                                {{-- hidden --}}
                                <input type="hidden" name="invoice_total" id="quote-total">
                                <input type="hidden" name="invoice_discount" id="quote-discount">
                                <input type="hidden" name="invoice_after_discount" id="quote-after-discount">
                                <input type="hidden" name="invoice_price_excluding_vat" id="quote-price-excluding-vat">
                                <input type="hidden" name="invoice_grand_total" id="quote-grand-total">
                                <input type="text"   name="vat_3_total" id="quote-withholding-amount">
                                <input type="hidden" name="invoice_vat_7" id="quote-vat-7">




                                <button type="submit" class="btn btn-success btn-sm  mx-3" form="form-create">
                                    <i class="fa fa-save"></i> อัพเดท</button>
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
            // ใช้ฟังก์ชันนี้ถ้าคุณต้องการทำบางอย่างก่อน submit ฟอร์ม
            $('#form-create').on('submit', function(event) {
                // ตรวจสอบหรือทำงานเพิ่มเติมก่อน submit
                var isValid = false; // สมมติว่าคุณมีการตรวจสอบอะไรบางอย่าง
                var quoteGrandTotal = $('#quoteGrandTotal').val();
                var quoteGrandTotalNew = $('#quote-grand-total').val();

                if (quoteGrandTotal === quoteGrandTotalNew) {
                    isValid = true;;
                }

                if (!isValid) {
                    event.preventDefault(); // หยุดการ submit ฟอร์ม
                    alert('ยอดไม่ตรงกันกับใบเสนอราคากรุณาตรวจสอบ!');
                }
            });
        });

        $(document).ready(function() {

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
                         // คำนวณหักภาษี ณ ที่จ่ายเฉพาะรายการที่ไม่ใช่ NonVat
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
                const grandTotalNew = GrandTotalOld + sumPriceExcludingVat + sumPriceExcludingVatNonVat;
                $('#grand-total-new').text(formatNumber(grandTotalNew.toFixed(2)));
                $('#grand-total-new-val').val(grandTotalNew);
                // ส่วนต่าง (Difference)
                $('#difference').text(formatNumber((grandTotalNew - GrandTotalOld).toFixed(2)));
                $('#difference-val').val(grandTotalNew - GrandTotalOld);

            }

            $('#add-row').click(function() {
                const newRow = $('#quotation-table .item-row:first').clone();
                newRow.find('input').val(0);
                newRow.find('select').val('');
                newRow.find('input[type="checkbox"]').prop('checked', false);
                newRow.appendTo('#quotation-table');
                updateRowNumbers();
                calculateTotals();
            });

            $('#quotation-table').on('click', '.remove-row-btn', function() {
                $(this).closest('.item-row').remove();
                updateRowNumbers();
                calculateTotals();
            });

            $('#quotation-table').on('input', '.quantity, .price-per-unit', calculateTotals);
            $('#quotation-table').on('change', '.non-vat, select[name="expense_type[]"]', calculateTotals);
            $('input[name="vat_type"]').change(calculateTotals);
            $('#withholding-tax').change(calculateTotals);

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
@endsection
