@extends('layouts.template')

@section('content')
    <div class="container-fluid page-content">
        <div class="card">
            <div class="card-header">
                <h4>แก้ไขข้อมูลใบจองทัวร์</h4>
                <span>Ref.Booking : <b class="text-info">{{$bookingModel->code}}</b></span>
                <span class="float-end">วันที่จอง : <b class="text-info">{{date('d/m/Y',strtotime($bookingModel->created_at))}}</b></span>
            </div>

            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Sale <span class="text-danger"> *</span></label>
                            <select name="sale_id" id="sale-name" class="form-select" placeholder="Sale Name" required>
                                <option value="">เลือกหนึ่งรายการ</option>
                                @forelse ($sales as $sale)
                                <option @if($bookingModel->sale_id === $sale->id) selected @endif value="{{$bookingModel->id}}">{{$sale->name}}</option>
                                @empty
                                    
                                @endforelse
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="text-danger">สถานะ <span class="text-danger"> *</span></label>
                            <select name="status" id="status" class="form-select" placeholder="status" required>
                                <option value="Booked">Booked</option>
                                <option value="Wait List">Wait List</option>
                                <option value="Success">Success</option>
                                <option value="Success">Cancel</option>
                            </select>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>รายการทัวร์ <span class="text-danger"> *</span></label>

                            <select name="tour_id" id="tour-id" class="form-select" required>
                                <option value="">เลือกหนึ่งรายการ</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>วันที่เดินทาง <span class="text-danger"> *</span></label>
                            <select name="date_tour" id="date-tour" class="form-select" required>
                                <option value="">เลือกหนึ่งรายการ</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label>ชื่อ <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="name" placeholder="ชื่อ" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>นามสกุล <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="surname" placeholder="นามสกุล" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>Email <span class="text-danger"> * </span></label>
                            <input type="email" class="form-control" name="email" placeholder="email" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>เบอร์โทรทรัพท์ <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="phone" placeholder="+66" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label><b>ความต้องการพิเศษ</b></label>
                            <textarea name="detail" id="detail" cols="30" rows="4" class="form-control"></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>รายการสินค้า และบริการ</h4>
                            <br>
                            <table
                                class="table customize-table table-hover table-bordered table-border mb-0 v-middle table-striped">
                                <thead>
                                    <tr>
                                        <th>ลำดับ </br> No.</th>
                                        <th>รายละเอียด </br> Description</th>
                                        <th class="text-end">จำนวน </br>Quantit</th>
                                        <th class="text-end">ราคา:หน่วย </br>Unit Price</th>
                                        <th class="text-end">ยอดรวม</br>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>ผู้ใหญ่พักคู่</td>
                                        <td> <input type="number" min="0.00" placeholder="0.00"
                                                name="num_twin"class="form-control text-end"></td>
                                        <td>
                                            <input type="number" min="0.00" name="price1" placeholder="0.00"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price1" placeholder="0.00"
                                                class="form-control text-end">
                                        </td>

                                    </tr>

                                    <tr>
                                        <td>2</td>
                                        <td>ผู้ใหญ่พักเดี่ยว</td>
                                        <td>
                                            <input type="number" min="0.00" name="num_single" placeholder="0.00"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="price2" placeholder="0.00"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price2" placeholder="0.00"
                                                class="form-control text-end">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>เด็กมีเตียง</td>
                                        <td>
                                            <input type="number" min="0.00" name="num_child" placeholder="0.00"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="price3" placeholder="0.00"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price3" placeholder="0.00"
                                                class="form-control text-end">
                                        </td>
                                    </tr>
                                    <tr>
                                             <td>4</td>
                                             <td>เด็กไม่มีเตียง</td>
                                             <td>
                                                 <input type="number" min="0.00" name="num_childnb" placeholder="0.00"
                                                     class="form-control text-end">
                                             </td>
                                             <td>
                                                 <input type="number" min="0.00" name="price4" placeholder="0.00"
                                                     class="form-control text-end">
                                             </td>
                                             <td>
                                                 <input type="number" min="0.00" name="sum_price4" placeholder="0.00"
                                                     class="form-control text-end">
                                             </td>
                                         </tr>

                                         <tr>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td class="text-danger text-end"><b><u style="border-bottom: 1px solid;">ราคารวม</u></b></td>
                                             <td class="text-end"> <input type="number" min="0.00" name="sum_price4" placeholder="0.00"
                                                            value=""
                                                            class="form-control text-end"></td>
                                         </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Remark</label>
                            <textarea name="remark" id="detail" cols="30" rows="4" class="form-control"></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn btn-success float-end"><i class="fas fa-save"></i>
                                อัพเดทข้อมูล</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
