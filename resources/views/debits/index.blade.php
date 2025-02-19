@extends('layouts.template')

@section('content')
<br>
<div class="email-app todo-box-container container-fluid">


    <div class="card">
        <div class="card-body">
            {{-- <h4 class="card-title">ค้นหา</h4>
            <form action="#" method="get" id="search">
                @csrf
                @method('get')
                <div class="row">
                    <div class="col-md-2">
                        <label for="">เลขที่เอกสาร</label>
                        <input type="text" name="document_number" placeholder="เลขที่เอกสาร" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="">Ref.Number</label>
                        <input type="text" name="ref_number" placeholder="เลขที่เอกสารอ้างอิง" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="withholdingForm" class="form-label">แบบฟอร์ม</label>
                        <select id="withholdingForm" name="withholding_form" class="form-select">
                            <option value="all">ทั้งหมด</option>
                            <option value="ภ.ง.ด.53">ภ.ง.ด.53</option>
                            <option value="ภ.ง.ด.3">ภ.ง.ด.3</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="">วันที่ออกเอกสาร เริ่มต้น</label>
                         <input type="date" name="document_date_start" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="">วันที่ออกเอกสาร สิ้นสุด</label>
                         <input type="date" name="document_date_end" class="form-control">
                    </div>

                    

                </div>
            </form>
            <br>
            <button type="submit" form="search" class="btn btn-success">ค้นหา</button>
        </div> --}}
    </div>


    <div class="card">

        <div class="card-body">
    <h3>ใบลดหนี้</h3>
    <a href="{{ route('debit.create') }}" class="btn btn-primary mb-3">เพิ่มเอกสารใหม่</a>
    <div class="table-responsive">
    <table class="table m-auto " id="table-withholding"  style = "width: 1000px'">
        <thead>
            <tr>
                <th >No.</th>
                {{-- <th>เลขที่เอกสาร</th>
                <th>Ref.Number</th>
                <th>ภงด</th>
                <th>Quote.Ref</th>
                <th >ชื่อผู้จอง</th>
                <th >ชื่อผู้ถูกหัก</th>
                <th >วันที่ออกเอกสาร</th>
                <th>ยอดชำระ</th>
                <th>ยอดหัก</th>
                <th>การจัดการ</th> --}}
            </tr>
        </thead>
        <tbody>
            
    </table>
</div>
</div>
</div>
</div>





@endsection
