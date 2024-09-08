@extends('layouts.template')

@section('content')
    <div class="container-fluid page-content">

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
                <h4>เพิ่มข้อมูลใบจองทัวร์</h4>
                <span>Ref.Booking : <b class="text-info">????</b></span>
                <span class="float-end">วันที่จอง : <b
                        class="text-info">{{ date('d/m/Y', strtotime(date(now()))) }}</b></span>
            </div>

            <div class="card-body">
                <form action="{{route('booking.store')}}" method="post">
                    @csrf
                    @method('post')
                    <div class="row">
                        <div class="col-md-6">
                            <label>Sale <span class="text-danger"> *</span></label>
                            <select name="sale_id" id="sale-name" class="form-select" placeholder="Sale Name" required>
                           <option value="">เลือกหนึ่งรายการ</option>
                                @forelse ($sales as $sale)
                                    <option 
                                        value="{{ $sale->id }}">{{ $sale->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="text-danger">สถานะ <span class="text-danger"> *</span></label>
                            
                            <select name="status" id="status" class="form-select" placeholder="status" required>
                                <option value="">เลือกหนึ่งรายการ</option>
                                <option  value="Booked">Booked</option>
                                <option  value="Wait List">Wait List</option>
                                <option  value="Success">Success</option>
                                <option  value="Cancel">Cancel</option>
                            </select>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
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
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label>ชื่อ <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="name" value=""
                                placeholder="ชื่อ" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>นามสกุล <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="surname" value=""
                                placeholder="นามสกุล" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>Email <span class="text-danger"> * </span></label>
                            <input type="email" class="form-control" name="email" placeholder="email"
                                value="" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>เบอร์โทรศัพท์ <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="phone" placeholder="+66"
                                value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label><b>ความต้องการพิเศษ</b></label>
                            <textarea name="detail" id="detail" cols="30" rows="4" class="form-control" ></textarea>
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
                                                 id="num-twin"
                                                name="num_twin"class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="price1" placeholder="0.00"
                                                id="price1"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price1" placeholder="0.00"
                                                id="sum-price1" 
                                                class="form-control text-end">
                                        </td>

                                    </tr>

                                    <tr>
                                        <td>2</td>
                                        <td>ผู้ใหญ่พักเดี่ยว</td>
                                        <td>
                                            <input type="number" min="0.00" name="num_single" placeholder="0.00"
                                                id="num-single" 
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="price2" placeholder="0.00"
                                                id="price2" 
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price2" placeholder="0.00"
                                                id="sum-price2" 
                                                class="form-control text-end">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>เด็กมีเตียง</td>
                                        <td>
                                            <input type="number" min="0.00" name="num_child" placeholder="0.00"
                                                id="num-child" 
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="price3" placeholder="0.00"
                                                id="price3" 
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price3" placeholder="0.00"
                                                id="sum-price3"
                                                class="form-control text-end">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>เด็กไม่มีเตียง</td>
                                        <td>
                                            <input type="number" min="0.00" name="num_childnb" placeholder="0.00"
                                                id="num-childnb" 
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="price4" placeholder="0.00"
                                                id="price4" 
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price4" placeholder="0.00"
                                                id="sum-price4" 
                                                class="form-control text-end">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-danger text-end"><b><u
                                                    style="border-bottom: 1px solid;">ราคารวม</u></b></td>
                                        <td class="text-end"> <input type="number" min="0.00" name="sum_price4"
                                                id="total_sum_price" placeholder="0.00" value=""
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
                            <textarea name="remark" id="remark" cols="30" rows="4" class="form-control"></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn btn-success float-end"><i class="fas fa-save"></i>
                                Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
