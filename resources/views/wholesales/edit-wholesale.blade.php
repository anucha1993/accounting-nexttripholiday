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

            <form action="{{route('wholesale.update',$wholesaleModel->id)}}" method="post">
                @method('put')
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">รหัส-โฮลเซลล์ <span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="code" value="{{ $wholesaleModel->code }}"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">ชื่อภาษาไทย<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="wholesale_name_th" required
                                value="{{ $wholesaleModel->wholesale_name_th }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">ชื่อภาษาอังกฤษ<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="wholesale_name_en" required
                                value="{{ $wholesaleModel->wholesale_name_en }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">เบอร์โทรศัพท์ </label>
                            <input type="text" class="form-control" name="tel" value="{{ $wholesaleModel->tel }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">ชื่อผู้ติดต่อ </label>
                            <input type="text" class="form-control" name="contact_person"
                                value="{{ $wholesaleModel->contact_person }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Email </label>
                            <input type="Email" class="form-control" name="email"
                                placeholder="Email@Mail.com" value="{{ $wholesaleModel->email }}">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">เลขประจําตัวผู้เสียภาษีอากร </label>
                            <input type="number" class="form-control" name="textid"
                                placeholder="เลขประจําตัวผู้เสียภาษีอากร" value="{{ $wholesaleModel->textid }}">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">ที่อยู่ </label>
                            <textarea name="address" class="form-control" cols="30" rows="5" placeholder="ที่อยู่">{{$wholesaleModel->address}}</textarea>
                        </div>
                        <br>

                        <div class="col-md-12 mb-3">
                            
                           
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input success" type="radio" {{$wholesaleModel->status === 'on'? 'checked' : ''}} name="status" id="success-radio" value="on">
                                <label class="form-check-label" for="success-radio">เปิดใช้งาน</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input success" type="radio" {{$wholesaleModel->status === 'off'? 'checked' : ''}} name="status" id="success2-radio" value="off">
                                <label class="form-check-label" for="success2-radio">ปิดใช้งาน</label>
                              </div>
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
