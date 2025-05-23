@extends('layouts.template')

@section('content')
    <div class="email-app todo-box-container">
        <!-- -------------------------------------------------------------- -->
        <!-- Left Part -->
        <!-- -------------------------------------------------------------- -->




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


        <form action="" id="form-update" method="post">
            @csrf
            @method('PUT')
            <div class="mail-list overflow-auto">
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
                            <h4 class="text-center my-4">สร้างใบเสนอราคา / ใบจองทัวร์</h4>
                            <div class="row">

                                {{-- <div class="col-md-4">
                                    <label>ลูกค้า</label>
                                    <select name="customer_id" class="form-select" >
                                        <option value="">--Select Customer--</option>
                                        @forelse ($customers as $item)
                                            <option value="{{$item->customer_id}}">{{$item->customer_name}}</option>
                                        @empty
                                            
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Sale</label>
                                    <select name="quote_sale" class="form-select" >
                                        <option value="">--Select Sale--</option>
                                        @forelse ($sales as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @empty
                                            
                                        @endforelse
                                    </select>
                                </div> --}}

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label>ลูกค้า</label>
                                        <select name="customer_id" class="form-select customer" >
                                            <option value="">--Select Customer--</option>
                                            <option value="new"><b>เพิ่มใหม่</b></option>
                                            @forelse ($customers as $item)
                                                <option value="{{$item->customer_id}}">{{$item->customer_name}}</option>
                                            @empty
                                                
                                            @endforelse
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Sale <span class="text-danger"> *</span></label>
                                        <select name="quote_sale" id="sale-name" class="form-select" placeholder="Sale Name" required>
                                       <option value="">เลือกหนึ่งรายการ</option>
                                            @forelse ($sales as $sale)
                                                <option 
                                                    value="{{ $sale->id }}">{{ $sale->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>

                                    
            
                                    {{-- <div class="col-md-6">
                                        <label class="text-danger">สถานะ <span class="text-danger"> *</span></label>
                                        
                                        <select name="status" id="status" class="form-select" placeholder="status" required>
                                            <option value="">เลือกหนึ่งรายการ</option>
                                            <option  value="Booked">Booked</option>
                                            <option  value="Wait List">Wait List</option>
                                            <option  value="Success">Success</option>
                                            <option  value="Cancel">Cancel</option>
                                        </select>
                                    </div> --}}

                                </div>
                           
                                <div class="row mt-3">
                                    <div class="col-md-6 mb-2">
                                        <label>รายการทัวร์ <span class="text-danger"> *</span></label>
            
                                        <select name="tour_id" id="tour-id" class="form-select" style="width: 100%" required>
                                            <option value="">เลือกหนึ่งรายการ</option>
            
                                            @forelse ($tours as $item)
                                                <option  value="{{ $item->id }}">
                                                    [{{ $item->code }}] {{ $item->name }}</option>
                                            @empty
                                                No found data
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>วันที่เดินทาง <span class="text-danger"> *</span></label>
                                        <select name="period_id" id="date-tour" class="form-select" required>
                                            <option value="">เลือกหนึ่งรายการ</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row customer-new" style="display: none">
                                    <div class="col-md-3 mb-2">
                                        <label>ชื่อ <span class="text-danger"> * </span></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="ชื่อ" >
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label>นามสกุล <span class="text-danger"> * </span></label>
                                        <input type="text" class="form-control" name="surname" 
                                            placeholder="นามสกุล" >
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label>Email <span class="text-danger"> * </span></label>
                                        <input type="email" class="form-control" name="customer_email" placeholder="email" 
                                            >
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label>เบอร์โทรศัพท์ <span class="text-danger"> * </span></label>
                                        <input type="text" class="form-control" name="customer_tel" placeholder="+66"
                                           >
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label>Fax <span class="text-danger"> * </span></label>
                                        <input type="text" class="form-control" name="customer_fax" placeholder="+66"
                                          >
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label>เลขภาษี <span class="text-danger"> * </span></label>
                                        <input type="text" class="form-control" name="customer_texid" placeholder="Texid"
                                          >
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>ที่อยู่</label>
                                        <textarea name="customer_address" class="form-control" cols="30" rows="1" placeholder="Address"></textarea>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><b>ความต้องการพิเศษ</b></label>
                                        <textarea name="detail" id="detail" cols="30" rows="4" class="form-control" ></textarea>
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
                               
                                    <div class="row item-row">
                                        <div class="col-md-1"><span class="row-number"> </span> <a
                                                href="javascript:void(0)" class="remove-row-btn text-danger"><span
                                                    class=" fa fa-trash"></span></a></div>
                                        <div class="col-md-3">

                                            <select name="product_id[]" class="form-select">
                                                <option value="">กรุณาเลือกรายการ</option>
                                                @forelse ($products as $product)
                                                    <option>{{ $product->product_pax === 'Y' ? '(Pax)' : '' }}</option>
                                                @empty
                                                @endforelse
                                            </select>

                                        </div>
                                        <div class="col-md-2">
                                            <select name="expense_type[]" class="form-select">
                                                <option value="">กรุณาเลือกรายการ</option>
                                                <option value="income">รายได้</option>
                                                <option value="discount">ส่วนลด</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 text-center"><input type="checkbox" name="non_vat[]"class="non-vat">
                                        </div>
                                        <div class="col-md-1"><input type="number" name="quantity[]"
                                                class="quantity form-control text-end"  value="0"
                                                step="0.01"></div>
                                        <div class="col-md-2"><input type="number" name="price_per_unit[]"
                                                class="price-per-unit form-control text-end" value="0"
                                                step="0.01">
                                        </div>
                                        <div class="col-md-2"><input type="number" name="total_amount[]"
                                                class="total-amount form-control text-end" value="0" readonly></div>
                                    </div>
                                

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
                                                        value="include" >
                                                    <label for="vat-include">คำนวณรวมกับราคาสินค้าและบริการ (VAT
                                                        Include)</label>
                                                </div>
                                                <div>
                                                    <input type="radio" id="vat-exclude" name="vat_type"
                                                        value="exclude">
                                                    <label for="vat-exclude">คำนวณแยกกับราคาสินค้าและบริการ (VAT
                                                        Exclude)</label>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row summary-row">
                                                <div class="col-md-10">
                                                    <input type="checkbox" name="vat3_status" value="Y" id="withholding-tax" > <span class="">
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
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>



    <script>
        $(document).ready(function() {

           // Customer New
           $('.customer').on('change', function () {
            var customer = $(this).val();
            if(customer === 'new') {
                $('.customer-new').show();
            }else{
                $('.customer-new').hide();
            }
           });

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

                const withholdingTax = $('#withholding-tax').is(':checked') ? grandTotal * 0.03 : 0;


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
                $('#quote-withholding-amount').val(withholdingTax.toFixed(2));
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
        //total sum 
        function calculateSum() {
            var sum_price1 = $('#num-twin').val() * $('#price1').val();
            var sum_price2 = $('#num-single').val() * $('#price2').val();
            var sum_price3 = $('#num-child').val() * $('#price3').val();
            var sum_price4 = $('#num-childnb').val() * $('#price4').val();

            $('#sum-price1').val(sum_price1);
            $('#sum-price2').val(sum_price2);
            $('#sum-price3').val(sum_price3);
            $('#sum-price4').val(sum_price4);

            var total_sum_price = sum_price1 + sum_price2 + sum_price3 + sum_price4;
            $('#total_sum_price').val(total_sum_price);
        }
        $('input').on('input', function() {
            calculateSum();
        });
        // Initialize calculation on page load
        calculateSum();



        //Selecy tour id
        $('#tour-id').select2();
        $('#tour-id').on('change', function() {
            var tour = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: '{{ route('select.period') }}',
                method: 'GET',
                data: {
                    tour: tour,
                    _token: _token
                },
                success: function(result) {
                    $('#date-tour').html(result);
                }
            })
        });
    });
</script>

@endsection
