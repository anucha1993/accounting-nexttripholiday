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
                <h4>แก้ไขข้อมูลใบจองทัวร์</h4>
                <span>Ref.Booking : <b class="text-info">{{ $bookingModel->code }}</b></span>
                <span class="float-end">วันที่จอง : <b
                        class="text-info">{{ date('d/m/Y', strtotime($bookingModel->created_at)) }}</b></span>
                        
             

            </div>

            <div class="card-body">
                <form id="form1" action="{{route('booking.update',$bookingModel->id)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-6">
                            <label>Sale <span class="text-danger"> *</span></label>
                            <select name="sale_id" id="sale-name" class="form-select" placeholder="Sale Name" required>
                          
                                @forelse ($sales as $sale)
                                    <option @if ($bookingModel->sale_id === $sale->id) selected @endif
                                        value="{{ $sale->id }}">{{ $sale->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="text-danger">สถานะ <span class="text-danger"> *</span></label>
                            <select name="status" id="status" class="form-select" placeholder="status" required>
                                <option @if ($bookingModel->status === 'Booked') selected @endif value="Booked">Booked</option>
                                <option @if ($bookingModel->status === 'Wait List') selected @endif value="Wait List">Wait List
                                </option>
                                <option @if ($bookingModel->status === 'Success') selected @endif value="Success">Success</option>
                                <option @if ($bookingModel->status === 'Cancel') selected @endif value="Cancel">Cancel</option>
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
                                    <option @if ($item->id === $bookingModel->tour_id) selected @endif value="{{ $item->id }}">
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

                                @forelse ($periods as $item)
                                    <option @if ($item->id === $bookingModel->period_id) selected @endif value="{{$item->id}}">
                                        {{ date('d/m/Y', strtotime($item->start_date)) . ' - ' . date('d/m/Y', strtotime($item->end_date)) }}
                                    </option>

                                @empty
                                    No found data
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label>ชื่อ <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="name" value="{{ $bookingModel->name }}"
                                placeholder="ชื่อ" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>นามสกุล <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="surname" value="{{ $bookingModel->surname }}"
                                placeholder="นามสกุล" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>Email <span class="text-danger"> * </span></label>
                            <input type="email" class="form-control" name="email" placeholder="email"
                                value="{{ $bookingModel->email }}" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>เบอร์โทรศัพท์ <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control" name="phone" placeholder="+66"
                                value="{{ $bookingModel->phone }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label><b>ความต้องการพิเศษ</b></label>
                            <textarea name="detail" id="detail" cols="30" rows="4" class="form-control" >{{ $bookingModel->detail }}</textarea>
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
                                                value="{{ $bookingModel->num_twin }}" id="num-twin"
                                                name="num_twin"class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="price1" placeholder="0.00"
                                                id="price1" value="{{ $bookingModel->price1 }}"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price1" placeholder="0.00"
                                                id="sum-price1" value="{{ $bookingModel->sum_price1 }}"
                                                class="form-control text-end">
                                        </td>

                                    </tr>

                                    <tr>
                                        <td>2</td>
                                        <td>ผู้ใหญ่พักเดี่ยว</td>
                                        <td>
                                            <input type="number" min="0.00" name="num_single" placeholder="0.00"
                                                id="num-single" value="{{ $bookingModel->num_single }}"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="price2" placeholder="0.00"
                                                id="price2" value="{{ $bookingModel->price2 }}"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price2" placeholder="0.00"
                                                id="sum-price2" value="{{ $bookingModel->sum_price2 }}"
                                                class="form-control text-end">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>เด็กมีเตียง</td>
                                        <td>
                                            <input type="number" min="0.00" name="num_child" placeholder="0.00"
                                                id="num-child" value="{{ $bookingModel->num_child }}"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="price3" placeholder="0.00"
                                                id="price3" value="{{ $bookingModel->price3 }}"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price3" placeholder="0.00"
                                                id="sum-price3" value="{{ $bookingModel->sum_price3 }}"
                                                class="form-control text-end">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>เด็กไม่มีเตียง</td>
                                        <td>
                                            <input type="number" min="0.00" name="num_childnb" placeholder="0.00"
                                                id="num-childnb" value="{{ $bookingModel->num_childnb }}"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="price4" placeholder="0.00"
                                                id="price4" value="{{ $bookingModel->price4 }}"
                                                class="form-control text-end">
                                        </td>
                                        <td>
                                            <input type="number" min="0.00" name="sum_price4" placeholder="0.00"
                                                id="sum-price4" value="{{ $bookingModel->sum_price4 }}"
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
                            <textarea name="remark" id="remark" cols="30" rows="4" class="form-control">{{$bookingModel->remark}}</textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" form="form1" class="btn btn btn-success float-end"><i class="fas fa-save"></i> อัพเดทข้อมูล</button>
                                <button type="submit" form="form2" class="mx-3 btn btn-sm btn-primary"><i class=" fas fa-redo "></i> สร้างใบเสนอราคา</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Convert --}}

 


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
