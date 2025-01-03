@extends('layouts.template')

@section('content')
<div class="container-fluid page-content">

    @if (session('success'))
    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
    role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>Success - </strong>{{session('success')}}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
    role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>Error - </strong>{{session('error')}}
    </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Wholesales Edit</h4>
            <h6 class="card-subtitle lh-base">
                แก้ไขข้อมูลโฮลเซลล์
            </h6>
            <hr>

            <form action="{{route('wholesale.store')}}" method="post">
                @method('post')
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">รหัส-โฮลเซลล์ <span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="code" placeholder="รหัส-โฮลเซลล์"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">ชื่อภาษาไทย<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="wholesale_name_th" required
                                placeholder="ชื่อภาษาไทย">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">ชื่อภาษาอังกฤษ<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="wholesale_name_en" required
                               placeholder="ชื่อภาษาอังกฤษ">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">เบอร์โทรศัพท์ </label>
                            <input type="text" class="form-control" name="tel" placeholder="เบอร์โทรศัพท์">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">ชื่อผู้ติดต่อ </label>
                            <input type="text" class="form-control" name="contact_person"
                                placeholder="ชื่อผู้ติดต่อ">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Email </label>
                            <input type="Email" class="form-control" name="email"
                                placeholder="Email@Mail.com">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">เลขประจําตัวผู้เสียภาษีอากร </label>
                            <input type="number" class="form-control" name="textid"
                                placeholder="เลขประจําตัวผู้เสียภาษีอากร" >
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">ที่อยู่ </label>
                            <textarea name="address" class="form-control" cols="30" rows="5" placeholder="ที่อยู่"></textarea>
                        </div>
                        <br>

                        <div class="col-md-12 mb-3">
                            
                            <input type="checkbox" name="status" value="on"> 
                            <label class="form-label">เปิดใช้งาน </label>
                        </div>


                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success float-end"> <i class="fas fa-save"></i>
                            อัพเดทข้อมูล</button>
                    </div>

                </div>






        </div>



    </div>



    </form>

    </div>
    </div>
    </div>
@endsection
