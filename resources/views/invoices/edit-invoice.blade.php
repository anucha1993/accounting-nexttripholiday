@extends('layouts.template')

@section('content')

<style>
    .page-invoice {
  padding: 30px;
  min-height: calc(100vh - 167px);
  max-width: 1800px;
  padding-top: 0px;
}

    </style>
    <br>
    <div class="container-fluid page-invoice">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Success - </strong>{{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Error - </strong>{{ session('error') }}
            </div>
        @endif


        <div class="card">
            <div class="card-header">
                <h3>Invoice:</h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card border">
                            <div class="card-header"> <i class="  fas fa-address-book" style="font-size: 16px"></i> <b>
                                    รายละเอียดลูกค้า (Customer)</b> </div>
                            <div class="card-body" style="font-size: 12px">

                                <div class="row align-items-center" style="margin-top: -13px;">
                                    <b for="example-text-input"
                                        class="col-sm-3 text-end control-label col-form-label">ชื่อลูกค้า:</b>
                                    <div class="col-md-8">
                                        <label class="form-control-plaintext text-start" style="margin-left: -25px">อนุชา
                                            โยธานันท์</label>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">ที่อยู่ : </b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">อีเมล์:</b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">เบอร์มือถือ:</b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">เบอร์โทรศัพท์ (02):</b>
                                </div>

                                <div class="row" style="margin-top: -13px;margin-bottom: -25px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">เบอร์โทรสาร (Fax):</b>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border">
                            <div class="card-header"><i class=" far fa-calendar-alt" style="font-size: 16px"></i>
                                รายละเอียดใบจองทัวร์ (Booking Form)</div>

                            <div class="card-body" style="font-size: 12px">

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">INV No : </b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">INV Date :</b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">Booking NO :</b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">Sale :</b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">Email:</b>
                                </div>
                                <div class="row" style="margin-top: -13px;margin-bottom: -25px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">Tour Code :</b>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border">
                            <div class="card-header"><i class=" fas fa-cart-plus" style="font-size: 16px"></i>
                                รายละเอียดแพคเกจที่ซื้อ/วันเดินทาง</div>
                            <div class="card-body" style="font-size: 12px">

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">ชื่อแพคเกจ : </b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">สายการบิน :</b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">ช่วงเวลาเดินทาง :</b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">ผู้เดินทาง (PAX) :</b>
                                </div>

                                <div class="row" style="margin-top: -13px;margin-bottom: -25px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">โฮลเซลล์ :</b>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border">
                            <div class="card-header"><i class=" fas fa-dollar-sign" style="font-size: 16px"></i>
                                ยอดรวมสุทธิและกำหนดชำระเงิน</div>
                            <div class="card-body" style="font-size: 12px">

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">ราคารวมสุทธิ : </b>

                                    <div class="col-md-8">
                                        <b class="form-control-plaintext text-start text-danger"
                                            style="margin-left: -25px;margin-top: -10px;font-size: 20px"><u>55,070.00.-</u></b>
                                    </div>

                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">จำนวนเงิน BathText :</b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">กำหนดชำระเต็ม :</b>
                                </div>

                                <div class="row" style="margin-top: -13px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">สถานะการชำระเงิน :</b>
                                </div>

                                <div class="row" style="margin-top: -13px;margin-bottom: -25px;">
                                    <b for="example-text-input text-right"
                                        class="col-sm-3 text-end control-label col-form-label">-</b>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2 ">
                    <div class="col-md-2 border">
                        <!-- Nav tabs -->
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            
                            <a class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home"
                                role="tab" aria-controls="v-pills-home" aria-selected="true">
                                <i class="fas fa-cart-plus"></i> รายละเอียดรายการสั่งซื้อ
                            </a>
                            <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile"
                                role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                <i class="far fa-credit-card"> </i> รายการชำระเงิน
                            </a>
                            <a class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" href="#v-pills-messages"
                                role="tab" aria-controls="v-pills-messages" aria-selected="false">
                               <i class="far fa-file-alt"></i> ใบแจ้งหนี้
                            </a>
                            <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings"
                                role="tab" aria-controls="v-pills-settings" aria-selected="false">
                              <i class="far fa-file-pdf"></i>  ใบกำกับภาษี
                            </a>
                            <a class="nav-link" id="v-pills-passport-tab" data-bs-toggle="pill" href="#v-pills-passport"
                                role="tab" aria-controls="v-pills-passport" aria-selected="false">
                               <i class=" far fa-address-book"></i> ไฟล์หนังสือเดินทาง
                            </a>
                            <a class="nav-link" id="v-pills-wholesale-tab" data-bs-toggle="pill" href="#v-pills-wholesale"
                                role="tab" aria-controls="v-pills-wholesale" aria-selected="false">
                               <i class="far fa-handshake"></i> รายการชำระเงินสำหรับโฮลเซลล์
                            </a>
                        </div>
                    </div>
                    <div class="col-md-10 ">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                                aria-labelledby="v-pills-home-tab">
                                <h4>รายละเอียดรายการสั่งซื้อ / Description</h4>
                                <hr>
                                <div class="col-md-10 ">
                                <form action="" class="">
                                        <table id="table-product" class="table customize-table mb-0 v-middle table-bordered" style="width: 100%;font-size: 12px">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="text-center">รายการ</th>
                                                    <th class="text-center">จำนวน</th>
                                                    <th>ราคาต่อหน่วย:บาท</th>
                                                    <th>ราคารวม:บาท</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="5" style="background-color: #aaaaaa27">ค่าทัวร์/ค่าบริการ</td>
                                                </tr>
                                                <tr>
                                                    <td><a href="" class="text-danger removeRow"><i class="fas fa-trash"></i> </a></td>
                                                    <td style="width: 400px">
                                                        <select name="product_id[]" class="form-select">
                                                            <option value="">รายการสินค้า1</option>
                                                            <option value="">รายการสินค้า1</option>
                                                            <option value="">รายการสินค้า2</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" name="product_qty[]" class="form-control text-end" placeholder="00.0" style="font-size: 12px;"></td>
                                                    <td><input type="number" name="product_price[]" class="form-control text-end" placeholder="00.0" style="font-size: 12px;"></td>
                                                    <td><input type="number" name="product_sum[]" class="form-control text-end" placeholder="00.0" readonly style="font-size: 12px;"></td>
                                                </tr>
                                                <tr id="productList" style="visibility: hidden; position: absolute;">
                                                    <td><a href="" class="text-danger removeRow"><i class="fas fa-trash"></i> </a></td>
                                                    <td style="width: 400px">
                                                        <select name="product_id[]" class="form-select">
                                                            <option value="">รายการสินค้า1</option>
                                                            <option value="">รายการสินค้า1</option>
                                                            <option value="">รายการสินค้า2</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" name="product_qty[]" class="form-control text-end" placeholder="00.0" style="font-size: 12px;"></td>
                                                    <td><input type="number" name="product_price[]" class="form-control text-end" placeholder="00.0" style="font-size: 12px;"></td>
                                                    <td><input type="number" name="product_sum[]" class="form-control text-end" placeholder="00.0" readonly style="font-size: 12px;"></td>
                                                </tr>
                                                <tr class="total-row" style="visibility: hidden; position: absolute;">
                                                    <td colspan="4" class="text-end">ยอดรวมสุทธิ:บาท</td>
                                                    <td><input type="number" class="form-control total-sum" placeholder="00.0" readonly></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                        <a href="" id="addRow"> <i class="fas fa-cart-plus"></i> เพิ่มรายการ</a>
                                        <hr>
                                        <table id="table-product2" class="table customize-table table-hover mb-0 v-middle table-bordered" style="width: 100%;font-size: 12px">
                                            <thead>
                                                <tr>
                                                    <td colspan="5" style="background-color: #aaaaaa27">ส่วนลด</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><a href="" class="text-danger removeRow"><i class="fas fa-trash"></i> </a></td>
                                                    <td style="width: 400px">
                                                        <select name="product_id[]" id="product-id" class="form-select">
                                                            <option value="">รายการสินค้า1</option>
                                                            <option value="">รายการสินค้า1</option>
                                                            <option value="">รายการสินค้า2</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" name="product_price[]" class="form-control text-end product-price" placeholder="00.0" style="font-size: 12px;"></td>
                                                    <td><input type="number" name="product_qty[]" class="form-control text-end product-qty" placeholder="00.0" style="font-size: 12px;"></td>
                                                    <td><input type="number" name="product_sum[]" class="form-control text-end product-sum" placeholder="00.0" readonly style="font-size: 12px;"></td>
                                                </tr>
                                                <tr id="productList2" style="visibility: hidden; position: absolute;" class="mt-0">
                                                    <td><a href="" class="text-danger removeRow"><i class="fas fa-trash"></i> </a></td>
                                                    <td style="width: 400px">
                                                        <select name="product_id[]" class="form-select product-id">
                                                            <option value="">รายการสินค้า1</option>
                                                            <option value="">รายการสินค้า1</option>
                                                            <option value="">รายการสินค้า2</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" name="product_price[]" class="form-control text-end product-price" placeholder="00.0" style="font-size: 12px;"></td>
                                                    <td><input type="number" name="product_qty[]" class="form-control text-end product-qty" placeholder="00.0" style="font-size: 12px;"></td>
                                                    <td><input type="number" name="product_sum[]" class="form-control text-end product-sum" placeholder="00.0" readonly style="font-size: 12px;"></td>
                                                </tr>
                                                <tr class="total-row">
                                                    <td colspan="4" class="text-end">ยอดรวมสุทธิ:บาท</td>
                                                    <td><input type="number" class="form-control total-sum" placeholder="00.0" readonly></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                        <br>
                                        <a href="" id="addRow2"> <i class="fas fa-cart-plus"></i> เพิ่มส่วนลด</a>
                                        <br>
                                        
                                     <hr>
                                  
                                    <button type="submit" class="btn btn-success mt-3 float-end btn-sm"><i
                                        class="fas fa-save"></i> บันทึก</button>
                                  </div>
                                </form>
                            </div>
                          

                            </div>
                            


              
                             
                              <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade  " id="v-pills-profile" role="tabpanel"
                                
                                    aria-labelledby="v-pills-profile-tab">
                                    <h4>รายการชำระเงิน / Payment information <a href="#" class="btn btn-info btn-sm ">เพิ่มรายการชำระเงิน</a></h4>
                                
                              <hr>

                              <div class="table-responsive m-t-400">
                              <table class="table customize-table table-hover mb-0 v-middle table-bordered" style="font-size: 12px">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>Payment No.</th>
                                        <th>รายละเอียดการชำระ</th>
                                        <th>จำนวนเงิน:บาท</th>
                                        <th>ไฟล์แนบ</th>
                                        <th>ประเภท</th>
                                        <th>ใบเสร็จรับเงิน</th>
                                        <th>จัดการ</th>
                                   
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>PM202407-0001</td>
                                        <td>วิธีที่ชำระ:โอนเงิน
                                            วันที่โอนเงิน:8 กรกฎาคม 2567 เวลา: 10.00 น.
                                            เข้าบัญชี:ธ.กสิกรไทย / ออมทรัพย์ /
                                        </td>
                                        <td>5,000.00</td>
                                        <td> <i class="fas fa-image text-info"></i> View</td>
                                        <td>ชำระเงินมัดจำ</td>
                                        <td><i class="fas fa-print text-danger"></i> พิมพ์</td>
                                        <td>
                                            <i class="fas fa-paper-plane mt-1 text-info"> </i> <a href="#" class="text-info"> ส่งอีเมล</a></br>
                                            <i class="fas fa-edit mt-1  text-primary"> </i> <a href="#" class="text-primary"> แก้ไข</a> </br>
                                            <i class="fas fa-trash mt-1 text-danger"> </i> <a href="#" class="text-danger"> ลบ</a></br>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 18px">
                                        <td colspan="7" class="text-end">(สามหมื่นสี่พันสามร้อยแปดสิบแปดบาทถ้วน) ยอดรวมใบแจ้งหนี้:	</td>
                                        <td class=""> <b><u style="border-bottom: 1px solid;" class="text-danger">34,388.00.-</u></b></td>
                                    </tr>
                                </tbody>
                              </table>
                              </div>
                            </div>


                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade " id="v-pills-messages" role="tabpanel"
                                
                                    aria-labelledby="v-pills-messages-tab">
                                    <h4>ใบแจ้งหนี้ / Invoice <a href="#" class="btn btn-info btn-sm ">เพิ่มใบแจ้งหนี้</a></h4>
                              
                              <hr>

                              <div class="table-responsive m-t-400">
                              <table class="table customize-table table-hover mb-0 v-middle table-bordered" style="font-size: 12px">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>เลขที่ใบแจ้งหนี้</th>
                                        <th>เลขที่อ้างอิง</th>
                                        <th>วันที่ออกใบแจ้งหนี้</th>
                                        <th>จำนวนเงินรวม:บาท</th>
                                        <th>ภาษีหัก ณ ที่จ่าย:บาท</th>
                                        <th>ผู้จัดทำ</th>
                                        <th>พิมพ์ / ออกใบกำกับฯ</th>
                                        <th>จัดการ</th>
                             
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>IVS202406-0225</td>
                                        <td>IV2406172874</td>
                                        <td>24 มิถุนายน 2567</td>
                                        <td>34,388.00	</td>
                                        <td>(3%) 224.30</td>
                                        <td>สุพรรษา ไกรดงพลอง</td>
                                        <td>
                                            <i class="fas fa-list-alt mt-2  text-primary"> </i> <a href="#" class="text-primary"> ดูรายละเอียด</a> </br>
                                            <i class="fas fa-print mt-1 text-danger"> </i> <a href="#" class="text-danger"> พิมพ์</a></br>
                                        </td>
                                        <td>
                                            <i class="fas fa-paper-plane mt-1 text-info"> </i> <a href="#" class="text-info"> ส่งอีเมล</a></br>
                                            <i class="fas fa-edit mt-2  text-primary"> </i> <a href="#" class="text-primary"> แก้ไข</a> </br>
                                            <i class="fas fa-trash mt-2 text-danger"> </i> <a href="#" class="text-danger"> ลบ</a></br>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 18px">
                                        <td colspan="8" class="text-end">(สามหมื่นสี่พันสามร้อยแปดสิบแปดบาทถ้วน) ยอดรวมใบแจ้งหนี้:	</td>
                                        <td class=""> <b><u style="border-bottom: 1px solid;" class="text-danger">34,388.00.-</u></b></td>
                                    </tr>

                                </tbody>
                              </table>
                              </div>
                            </div>





                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                                aria-labelledby="v-pills-settings-tab">
                                <h4>ใบกำกับภาษี / Receipt & Tax invoice <a href="#" class="btn btn-info btn-sm ">สร้างใบกำกับภาษี </a></h4>
                                
                                <hr>
  
                                <div class="table-responsive m-t-400">
                                <table class="table customize-table table-hover mb-0 v-middle table-bordered" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>ลำดับ</th>
                                            <th>เลขที่ใบแจ้งหนี้</th>
                                            <th>เลขที่อ้างอิง</th>
                                            <th>วันที่ออกใบแจ้งหนี้</th>
                                            <th>จำนวนเงินรวม:บาท</th>
                                            <th>ภาษีหัก ณ ที่จ่าย:บาท</th>
                                            <th>ผู้จัดทำ</th>
                                            <th>พิมพ์ / ออกใบกำกับฯ</th>
                                            <th>จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>IVS202406-0225</td>
                                            <td>IV2406172874</td>
                                            <td>24 มิถุนายน 2567</td>
                                            <td>34,388.00</td>
                                            <td>(3%) 224.30</td>
                                            <td>สุพรรษา ไกรดงพลอง</td>
                                            <td>
                                                <i class="fas fa-list-alt mt-2  text-primary"> </i> <a href="#" class="text-primary"> ดูรายละเอียด</a> </br>
                                                <i class="fas fa-print mt-1 text-danger"> </i> <a href="#" class="text-danger"> พิมพ์</a></br>
                                            </td>
                                            <td>
                                                <i class="fas fa-paper-plane mt-1 text-info"> </i> <a href="#" class="text-info"> ส่งอีเมล</a></br>
                                                <i class="fas fa-edit mt-2  text-primary"> </i> <a href="#" class="text-primary"> แก้ไข</a> </br>
                                                <i class="fas fa-trash mt-2 text-danger"> </i> <a href="#" class="text-danger"> ลบ</a></br>
                                            </td>
                                        </tr>
                                        <tr style="font-size: 18px">
                                            <td colspan="8" class="text-end">(สามหมื่นสี่พันสามร้อยแปดสิบแปดบาทถ้วน) ยอดรวมใบแจ้งหนี้:	</td>
                                            <td class=""> <b><u style="border-bottom: 1px solid;" class="text-danger">34,388.00.-</u></b></td>
                                        </tr>

                                   
                                    </tbody>
                                </table>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="v-pills-passport" role="tabpanel"
                            aria-labelledby="v-pills-passport-tab">
                            <h4>ไฟล์หนังสือเดินทาง / Passport Photo <a href="#" class="btn btn-info btn-sm ">อัพโหลดไฟล์ </a></h4>
                            <hr>
                              <ul>
                                <li class="mt-3"><i class="fas fa-image"></i><a href="#" > Passport.js</a>  &nbsp;<a href="" class="text-danger fas fa-trash"> ลบ</a></li>
                                <li class="mt-3"><i class="fas fa-image"></i><a href="#" > Passport.js</a>  &nbsp;<a href="" class="text-danger fas fa-trash"> ลบ</a></li>
                                <li class="mt-3"><i class="fas fa-image"></i><a href="#" > Passport.js</a>  &nbsp;<a href="" class="text-danger fas fa-trash"> ลบ</a></li>
                              </ul>
                            </div>


                            <div class="tab-pane fade" id="v-pills-wholesale" role="tabpanel"
                            aria-labelledby="v-pills-wholesale-tab">
                            <h4>รายการชำระเงินสำหรับโฮลเซลล์ / Wholesale peyment information <a href="#" class="btn btn-info btn-sm ">เพิ่มรายการชำระเงินสำหรับโฮลเซลล์ </a></h4>
                            <hr>
                            <div class="table-responsive m-t-400">
                                <table class="table customize-table table-hover mb-0 v-middle table-bordered" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>ลำดับ</th>
                                            <th>Payment No.</th>
                                            <th>วันที่ชำระเงิน</th>
                                            <th>จำนวนเงิน:บาท</th>
                                            <th>ไฟล์แนบ</th>
                                            <th>ประเภท</th>
                                            <th>จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>WS240630016</td>
                                            <td>24 มิถุนายน 2567</td>
                                            <td>26,359.96</td>
                                            <td>-</td>
                                            <td>ชำระเงินเต็มจำนวน</td>
                                            <td>
                                                <i class="fas fa-edit mt-2  text-primary"> </i> <a href="#" class="text-primary"> แก้ไข</a> </br>
                                                <i class="fas fa-trash mt-2 text-danger"> </i> <a href="#" class="text-danger"> ลบ</a></br>
                                            </td>

                                        </tr>
                                        <tr style="font-size: 18px">
                                            <td colspan="6" class="text-end">(สามหมื่นสี่พันสามร้อยแปดสิบแปดบาทถ้วน) ยอดรวมใบแจ้งหนี้:	</td>
                                            <td class=""> <b><u style="border-bottom: 1px solid;" class="text-danger">34,388.00.-</u></b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                         
                         

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>


    <script>
     $(document).ready(function() {
    // Function to calculate sum for each row and total sum
    function calculateSum() {
        let totalSum = 0;

        $('#table-product tbody tr:not(#productList, .total-row)').each(function() {
            let qty = parseFloat($(this).find('input[name="product_qty[]"]').val()) || 0;
            let price = parseFloat($(this).find('input[name="product_price[]"]').val()) || 0;
            let sum = qty * price;
            $(this).find('input[name="product_sum[]"]').val(sum.toFixed(2));
            totalSum += sum;
        });

        $('#table-product2 tbody tr:not(#productList2, .total-row)').each(function() {
            let qty = parseFloat($(this).find('input[name="product_qty[]"]').val()) || 0;
            let price = parseFloat($(this).find('input[name="product_price[]"]').val()) || 0;
            let sum = qty * price;
            $(this).find('input[name="product_sum[]"]').val(sum.toFixed(2));
            totalSum += sum;
        });

        $('.total-sum').val(totalSum.toFixed(2));
    }

    // Event listener to add a new row in table-product
    $('#addRow').click(function(event) {
        event.preventDefault();
        var newRow = $('#productList').clone().removeAttr('id').removeAttr('style');
        $('#table-product tbody .total-row').before(newRow);
    });

    // Event listener to add a new row in table-product2
    $('#addRow2').click(function(event) {
        event.preventDefault();
        var newRow = $('#productList2').clone().removeAttr('id').removeAttr('style');
        $('#table-product2 tbody .total-row').before(newRow);
    });

    // Event listener to remove a row
    $(document).on('click', '.removeRow', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        calculateSum();
    });

    // Event listener to recalculate the sum when qty or price changes
    $(document).on('input', 'input[name="product_qty[]"], input[name="product_price[]"]', function() {
        calculateSum();
    });

    // Initial calculation
    calculateSum();
});

    </script>
@endsection
