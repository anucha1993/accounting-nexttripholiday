@extends('layouts.template')

@section('content')
    <div class="container-fluid page-content">
        <div class="card">
            <div class="card-header" style="background-color: #ffff">
                <h4 class="card-title">Convert ใบจองทัวร์</h4>
                <h6 class="card-subtitle lh-base">
                    อ้างอิงใบจองทัวร์เลขที่ :

                    <div class="float-end">
                     @if($checkCustomer)
                      
                     <div class="form-check form-check-inline">
                              <input class="form-check-input success" type="radio" name="radio-solid-success" id="success-radio-old" value="customerold" checked>
                              <label class="form-check-label" for="success-radio-old">อัพเดทข้อมูลเดิม</label>
                          </div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input success" type="radio" name="radio-solid-success" id="success-radio-new" value="customerNew">
                              <label class="form-check-label" for="success-radio-new">สร้างลูกค้าใหม่</label>
                          </div>

                            <span class="badge rounded-pill bg-success">ลูกค้าเก่า</span>
                 
                      @else
                      <span class="badge rounded-pill bg-primary">ลูกค้าใหม่</span>
                     @endif
                    </div>
                </h6>
               
                <hr>
              
            </div>
            <div class="card-body">


                <form action="">
                    <div class="row" style="background-color: #cfcfcf23; padding: 20px; border-radius: 50px;">
                        <div class="col-md-6">

                            <div class="mb-3 row">
                                <label for="example-text-input text-right"
                                    class="col-sm-4 text-end control-label col-form-label">ชื่อลูกค้า /
                                    FullName:</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="customer_name" placeholder="ชื่อลูกค้า"
                                    value="{{$request->customer_name }}"
                                        required aria-describedby="basic-addon1">
                                        @if ($checkCustomer && $checkCustomer->customer_name !== $request->customer_name)
                                        <small  class="form-text text-muted text-danger check-customer">{{$checkCustomer->customer_name}}</small>  
                                        @endif
                                      
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">อีเมล์ / Email:</label>
                                <div class="col-md-8">
                                    <input type="email" class="form-control" name="customer_email"
                                     value="{{$request->customer_email}}"
                                        placeholder="email@domail.com" required aria-describedby="basic-addon1">
                                        @if ($checkCustomer && $checkCustomer->customer_email !== $request->customer_email)
                                        <small id="name" class="form-text  check-customer text-danger">ข้อมูลในระบบ : {{$checkCustomer->customer_email}}</small>  
                                        @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">ที่อยู่ / Address:</label>
                                <div class="col-md-8">
                                    <textarea name="customer_address" id="address" class="form-control" cols="30" rows="2"
                                        placeholder="ที่อยู่" required>{{$checkCustomer? $checkCustomer->customer_address : ''}}</textarea>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">เลขผู้เสียภาษี / TaxID:</label>
                                <div class="col-md-8">
                                    <input type="text" id="texid" class="form-control" name="customer_texid" mix="13"
                                    value="{{$checkCustomer? $checkCustomer->customer_texid : ''}}"
                                        placeholder="เลขประจำตัวผู้เสียภาษี" required aria-describedby="basic-addon1">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">เบอร์โทรศัพท์ / Phone No:</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="customer_tel"
                                    value="{{$request->customer_tel }}"
                                        placeholder="เบอร์โทรศัพท์" required aria-describedby="basic-addon1">
                                        @if ($checkCustomer && $checkCustomer->customer_tel !== $request->customer_tel)
                                        <small id="name" class="form-text check-customer  text-danger">ข้อมูลในระบบ : {{$checkCustomer->customer_tel}}</small>  
                                        @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">เบอร์โทรสาร / FAX:</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="fax" name="customer_fax"
                                       value="{{$checkCustomer? $checkCustomer->customer_fax : ''}}"
                                        placeholder="เบอร์โทรศัพท์" required aria-describedby="basic-addon1">
                                </div>
                            </div>


                        </div>

                        <div class="col-md-6">

                            <div class="mb-3 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">เลขที่ / IVS No : </label>

                                    <div class="col-md-8">
                                             <input type="text" class="form-control" name="invoice_number" placeholder="Pending" readonly
                                             value="IVS{{date('Yd',strtotime(now())).'-????'}}">
                                         </div>

                            </div>
                            <div class="mb-3 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">วันที่ / IVS Date:</label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control" name="customer_date" value="{{date('Y-m-d',strtotime(now()))}}"
                                        required aria-describedby="basic-addon1">
                                </div>

                            </div>

                            <div class="mb-3 row">
                              <label for="example-text-input"
                                  class="col-sm-4 text-end control-label col-form-label">เลขที่อ้างอิง / IV Ref:</label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="booking_tour_code" placeholder="เลขที่อ้างอิงใบจองทัวร์" readonly
                                      required aria-describedby="basic-addon1">
                              </div>

                          </div>

                          <div class="mb-3 row">
                              <label for="example-text-input"
                                  class="col-sm-4 text-end control-label col-form-label">รหัสทัวร์ / Tore Code : </label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="booking_tour_number" placeholder="เลขที่อ้างอิงใบจองทัวร์" readonly
                                      required aria-describedby="basic-addon1">
                              </div>

                          </div>

                          <div class="mb-3 row">
                              <label for="example-text-input"
                                  class="col-sm-4 text-end control-label col-form-label">เลขที่จอง / Booking Code: </label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="booking_number" placeholder="เลขที่จองทัวร์" readonly
                                      required aria-describedby="basic-addon1">
                              </div>

                          </div>

                          <div class="mb-3 row">
                              <label for="example-text-input"
                                  class="col-sm-4 text-end control-label col-form-label">พนักงานขาย / Saleman: </label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="booking_sale" placeholder="พนักงานขาย" readonly
                                      required aria-describedby="basic-addon1">
                              </div>

                          </div>




                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
               $(document).ready(function() {
                   // เก็บค่าเดิมของ textarea
                   var originalAddress = $('#address').val().trim();
                   var originalTexid = $('#texid').val().trim();
                   var originalFax = $('#fax').val().trim();
       
                   // ฟังก์ชั่นตรวจสอบสถานะของ radio button เมื่อมีการเปลี่ยนแปลง
                   $('input[name="radio-solid-success"]').change(function() {
                       if ($(this).val() === 'customerNew') {
                           // ถ้า radio button customerNew ถูกเลือก, เซ็ตค่า textarea ให้เป็นค่าว่าง
                           $('#address').val('');
                           $('#texid').val('');
                           $('#fax').val('');
                           $('.check-customer').css('display', 'none');
                       } else if ($(this).val() === 'customerold') {
                           // ถ้า radio button customerold ถูกเลือก, คืนค่าเดิมของ textarea
                           $('#address').val(originalAddress);
                           $('#texid').val(originalTexid);
                           $('#fax').val(originalFax);
                           $('.check-customer').css('display', 'block');
                       }
                   });
       
                   // ตรวจสอบสถานะของ radio button ที่ถูกเลือกเมื่อโหลดหน้าเว็บ
                   if ($('input[name="radio-solid-success"]:checked').val() === 'customerNew') {
                       $('#address').val('');
                       $('#textid').val('');
                       $('#fax').val('');
                       $('.check-customer').css('display', 'none');
                   } else if ($('input[name="radio-solid-success"]:checked').val() === 'customerold') {
                       $('#address').val(originalAddress);
                       $('#texid').val(originalTexid);
                       $('#fax').val(originalFax);
                       $('.check-customer').css('display', 'block');
                   }
               });

           </script>
       

@endsection
