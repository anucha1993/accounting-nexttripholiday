@extends('layouts.template')

@section('content')
    <div class="container-fluid page-content">
        <div class="card">
            <form action="{{route('quote.store')}}" id="formQuote" method="post">
                @csrf

                {{-- ข้อมูลส่งไป quote --}}
                <input type="hidden" name="quote_booking" value="{{$request->booking_number}}">
                <input type="hidden" name="quote_sale" value="{{$request->sale_id}}">
                <input type="hidden" name="quote_tour_code" value="{{$request->booking_tour_number}}">
                <input type="hidden"  name="quote_date" value="{{date('Y-m-d',strtotime(now()))}}">
                <input type="hidden"  name="tour_id" value="{{$request->tour_id}}">
                <input type="hidden"  name="country_id" value="{{$request->country_id}}">
                <input type="hidden"  name="travel_type" value="{{$request->travel_type}}">
                <input type="hidden"  name="wholesale_id" value="{{$request->wholesale_id}}">
                <input type="hidden"  name="total_qty" value="{{$request->total_qty}}">


            <div class="card-header" style="background-color: #ffff">
                <h4 class="card-title">Convert ใบจองทัวร์</h4>
                <h6 class="card-subtitle lh-base">
                    อ้างอิงใบจองทัวร์เลขที่ : {{$request->booking_number}}

                    <div class="float-end">
                     @if($checkCustomer)
                     <input type="hidden" name="customer_id" value="{{$checkCustomer->customer_id ? : NULL}}">
                     <div class="form-check form-check-inline">
                              <input class="form-check-input success" type="radio" name="customer_type_new" id="success-radio-old" value="customerold" checked>
                              <label class="form-check-label" for="success-radio-old">อัพเดทข้อมูลเดิม</label>
                          </div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input success" type="radio" name="customer_type_new" id="success-radio-new" value="customerNew">
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

                            <div class="mb-1 row">
                                <label for="example-text-input text-right" class="col-sm-4 text-end control-label col-form-label">ชื่อลูกค้า:</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="customer_name" placeholder="ชื่อลูกค้า"
                                    value="{{$request->customer_name }}"
                                        required aria-describedby="basic-addon1">
                                        @if ($checkCustomer && $checkCustomer->customer_name !== $request->customer_name)
                                        <small  class="form-text text-muted text-danger check-customer">{{$checkCustomer->customer_name}}</small>  
                                        @endif
                                      
                                </div>
                            </div>

                            <div class="mb-1 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">อีเมล์:</label>
                                <div class="col-md-8">
                                    <input type="email" class="form-control" name="customer_email"
                                     value="{{$request->customer_email}}"
                                        placeholder="email@domail.com" required aria-describedby="basic-addon1">
                                        @if ($checkCustomer && $checkCustomer->customer_email !== $request->customer_email)
                                        <small id="name" class="form-text  check-customer text-danger">ข้อมูลในระบบ : {{$checkCustomer->customer_email}}</small>  
                                        @endif
                                </div>
                            </div>

                            <div class="mb-1 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">ที่อยู่:</label>
                                <div class="col-md-8">
                                    <textarea name="customer_address" id="address" class="form-control" cols="30" rows="4"
                                        placeholder="ที่อยู่" required>{{$checkCustomer? $checkCustomer->customer_address : ''}}</textarea>
                                </div>
                            </div>

                            <div class="mb-1 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">เลขผู้เสียภาษี:</label>
                                <div class="col-md-8">
                                    <input type="text" id="texid" class="form-control" name="customer_texid" mix="13"
                                    value="{{$checkCustomer? $checkCustomer->customer_texid : ''}}"
                                        placeholder="เลขประจำตัวผู้เสียภาษี" required aria-describedby="basic-addon1">
                                </div>
                            </div>

                            <div class="mb-1 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">เบอร์โทรศัพท์ :</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="customer_tel"
                                    value="{{$request->customer_tel }}"
                                        placeholder="เบอร์โทรศัพท์" required aria-describedby="basic-addon1">
                                        @if ($checkCustomer && $checkCustomer->customer_tel !== $request->customer_tel)
                                        <small id="name" class="form-text check-customer  text-danger">ข้อมูลในระบบ : {{$checkCustomer->customer_tel}}</small>  
                                        @endif
                                </div>
                            </div>
                            <div class="mb-1 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">เบอร์โทรสาร :</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="fax" name="customer_fax"
                                       value="{{$checkCustomer? $checkCustomer->customer_fax : ''}}"
                                        placeholder="เบอร์โทรศัพท์"  aria-describedby="basic-addon1">
                                </div>
                            </div>


                        </div>

                        <div class="col-md-6">

                            <div class="mb-1 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">วันที่ออกใบแจ้งหนี้ : </label>

                                    <div class="col-md-8">
                                             <input type="text" class="form-control" name="date" placeholder="Pending" readonly
                                             value="{{date('d/m/Y',strtotime(now()))}}">

                                         </div>

                            </div>
                            <div class="mb-1 row">
                                <label for="example-text-input"
                                    class="col-sm-4 text-end control-label col-form-label">วันที่จองแพคเกจ :</label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control" name="customer_date" value="{{date('Y-m-d',strtotime(now()))}}"
                                        required aria-describedby="basic-addon1">
                                </div>

                            </div>
                          

                            <div class="mb-1 row">
                              <label for="example-text-input"
                                  class="col-sm-4 text-end control-label col-form-label">ชื่อแพคเกจทัวร์ :</label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="booking_tour_code" placeholder="ชื่อแพคเกจทัวร์" readonly
                                  value="{{$request->booking_tour_name}}"
                                      required aria-describedby="basic-addon1">
                              </div>

                          </div>

                          <div class="mb-1 row">
                              <label for="example-text-input"
                                  class="col-sm-4 text-end control-label col-form-label">ระยะเวลาทัวร์ (วัน/คืน) : </label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="booking_tour_number" placeholder="ระยะเวลาทัวร์" readonly
                                  value="{{$request->num_day}}"
                                      required aria-describedby="basic-addon1">
                              </div>

                          </div>

                          <div class="mb-1 row">
                              <label for="example-text-input"
                                  class="col-sm-4 text-end control-label col-form-label">ประเทศที่เดินทาง : </label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="booking_number" placeholder="เลขที่จองทัวร์" readonly
                                  value="{{$country_name}}"
                                      required aria-describedby="basic-addon1">
                              </div>
                          </div>

                          <div class="mb-1 row">
                              <label for="example-text-input"
                                  class="col-sm-4 text-end control-label col-form-label">โฮลเซลล์: </label>
                              <div class="col-md-8">
                                  <input type="text" class="form-control" name="booking_sale" placeholder="พนักงานขาย" readonly
                                  value="{{$request->wholesale_name_th}}"
                                      required aria-describedby="basic-addon1">
                              </div>

                          </div>

                          <div class="mb-1 row">
                            <label for="example-text-input"
                                class="col-sm-4 text-end control-label col-form-label">สายการบิน : </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="booking_sale" placeholder="สายการบิน" readonly
                                value="{{$request->airline_name}}"
                                    required aria-describedby="basic-addon1">
                            </div>

                        </div>


                        <div class="mb-1 row">
                            <label for="example-text-input"
                                class="col-sm-4 text-end control-label col-form-label">วันที่ออกเดินทาง : </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="booking_sale" placeholder="วันที่ออกเดินทาง" readonly
                                value="{{date('d/m/Y',strtotime($request->start_date)).' - '.date('d/m/Y',strtotime($request->end_date))}}"
                                    required aria-describedby="basic-addon1">
                            </div>

                        </div>




                        </div>
                    </div>
                    <br>

                
                    <div class="row" style="background-color: #cfcfcf23; padding: 20px; border-radius: 50px;">
                        
                        <h4>รายการสินค้าและบริการ</h4>
                       <hr>
                       <table class="table customize-table table-hover mb-0 v-middle table-striped">
                        <thead class="table-based border  ">
                            <tr>

                                <th>ลำดับ  </br> No.</th>
                                <th>รายละเอียด  </br> Description</th>
                                <th>จำนวน </br>Quantit</th>
                                <th>ราคา:หน่วย </br>Unit Price</th>
                                <th>ยอดรวม</br>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @forelse ($request->products as $key => $item)
                            @if ($item['qty'] > 0)
                             
                              @php
                                  $total += $item['sum']
                              @endphp
                            <tr>
                                <td> <input type="hidden" name=""> {{ $key + 1 }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['qty'] }}</td>
                                <td>{{ number_format($item['price'], 2, '.', ',');  }}</td>
                                <td>{{ number_format($item['sum'], 2, '.', ',');  }}</td>

                                <input type="hidden" name="product_id[]" value="{{$item['id']}}">
                                <input type="hidden" name="product_name[]" value="{{$item['name']}}">
                                <input type="hidden" name="product_qty[]" value="{{$item['qty']}}">
                                <input type="hidden" name="product_price[]" value="{{$item['price']}}">
                                <input type="hidden" name="product_sum[]" value="{{$item['sum']}}">
                                <input type="hidden" name="expense_type[]" value="income">
                            </tr>
                            @endif

                          
                           
                        @empty

                            <tr class="">
                                <td colspan="3">No found product</td>
                            </tr>
                        @endforelse
                        <tr >
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-danger"><b><u style="border-bottom: 1px solid;">ราคารวม</u></b></td>
                            <td>{{ number_format($total, 2, '.', ','); }}</td>
                        </tr>
                        </tbody>
                       </table>
                      

                       
                    </div>
                    <br>

                    <button type="submit" class="btn btn-success float-end" form="formQuote">Convert</button>
                    <br>
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
                   $('input[name="customer_type_new"]').change(function() {
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
                   if ($('input[name="customer_type_new"]:checked').val() === 'customerNew') {
                       $('#address').val('');
                       $('#textid').val('');
                       $('#fax').val('');
                       $('.check-customer').css('display', 'none');
                   } else if ($('input[name="customer_type_new"]:checked').val() === 'customerold') {
                       $('#address').val(originalAddress);
                       $('#texid').val(originalTexid);
                       $('#fax').val(originalFax);
                       $('.check-customer').css('display', 'block');
                   }
               });

           </script>
       

@endsection
