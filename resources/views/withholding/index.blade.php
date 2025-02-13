@extends('layouts.template')

@section('content')
<br>
<div class="email-app todo-box-container container-fluid">


    <div class="card">
        <div class="card-body">
            <h4 class="card-title">ค้นหา</h4>
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

                    <div class="col-md-2">
                        <label for="">ชื่อผู้ถูกหัก</label>
                        <select name="customer" id="customer-select" class="form-select select2" style="width: 100%">
                            <option value="all">all</option>
                            @forelse ($customerWithholding as $item)
                                <option value="{{ $item->customer->customer_id ?? $item->wholesale_id  }}">{{ $item->customer->customer_name ?? $item->wholesale->wholesale_name_th  }}</option>
                            @empty
                                
                            @endforelse
                        </select>
                    </div>

                </div>
            </form>
            <br>
            <button type="submit" form="search" class="btn btn-success">ค้นหา</button>
        </div>
    </div>


    <div class="card">

        <div class="card-body">
    <h3>รายการใบหัก ณ ที่จ่าย</h3>
    <a href="{{ route('withholding.create') }}" class="btn btn-primary mb-3">เพิ่มเอกสารใหม่</a>
    <div class="table-responsive">
    <table class="table m-auto " id="table-withholding"  style = "width: 1000px'">
        <thead>
            <tr>
                <th >No.</th>
                <th>เลขที่เอกสาร</th>
                <th>Ref.Number</th>
                <th>ภงด</th>
                <th>Quote.Ref</th>
                <th >ชื่อผู้จอง</th>
                <th >ชื่อผู้ถูกหัก</th>
                <th >วันที่ออกเอกสาร</th>
                <th>ยอดชำระ</th>
                <th>ยอดหัก</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $key => $document)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $document->document_number }}</td>
                <td>{{ $document->ref_number ?? 'N/A' }}</td>
                <td>{{ $document->withholding_form ?? 'N/A' }}</td>
                <td>{{ $document->quote->quote_number ?? 'N/A' }}</td>
                <td>{{ $document->customer->customer_name ?? 'N/A' }}</td>
                <td>{{ $document->customer->customer_name ?? $document->wholesale->wholesale_name_th  }}</td>
                <td>{{ date('d/m/Y',strtotime($document->document_doc_date)) }}</td>
                <td>{{ number_format($document->total_payable,2) }}</td>
                <td>{{ number_format($document->total_withholding_tax,2) }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                
                        <div class="btn-group btn-group-sm" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-light-success text-secondary font-weight-medium dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                            </button>
                            
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a href="{{route('MPDF.generatePDFwithholding',$document->id)}}" onclick="openPdfPopup(this.href); return false;" class="dropdown-item text-success"> <i class="fa fa-eye"></i> ดู</a>
                                <a href="{{ route('withholding.edit', $document->id) }}" class=" dropdown-item text-info"> <i class="fa fa-edit"></i> แก้ไข</a>
                                <a class="dropdown-item" href="{{route('MPDF.generatePDFwithholding',$document->id)}}" target="_blink"><i class="fa fa-print text-danger"></i> พิมพ์</a>
                                <a class="dropdown-item" href="{{route('MPDF.downloadPDFwithholding',$document->id)}}"><i class="fa fa-file-pdf text-danger"></i> ดาวน์โหลด</a>
                                <a class="dropdown-item" href="{{route('MPDF.printEnvelope',$document->id)}}"><i class="fas fa-envelope text-info"></i> พิมพ์หน้าซอง</a>
                                <a class="dropdown-item" href="{{route('withholding.editRepear',$document->id)}}"><i class="fas fa-share-square text-info"></i> สร้างซ้ำ</a>
                             
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('withholding.destroy',$document->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> ลบ</button>
                    </form>

                </td>

            </tr>
            @endforeach
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="5" align="right" class="text-danger">จำนวนยอดชำระทั้งหมด : {{number_format($documents->sum('total_amount'),2)}}</td>
                    <td colspan="4" align="" class="text-success">จำนวนยอดหักทั้งหมด : {{number_format($documents->sum('total_withholding_tax'),2)}}</td>
                </tr>
            </tfoot>
        </tbody>
    </table>
</div>
</div>
</div>
</div>




<script>
   $(document).ready(function() {
        $('#table-withholding').dataTable();
    });

    function openPdfPopup(url) {
        var width = 950; // กำหนดความกว้างของหน้าต่าง
        var height = 800; // กำหนดความสูงของหน้าต่าง
        var left = (window.innerWidth - width) / 2; // คำนวณตำแหน่งจากด้านซ้ายของหน้าจอ
        var top = (window.innerHeight - height) / 2; // คำนวณตำแหน่งจากด้านบนของหน้าจอ

        // เปิดหน้าต่างใหม่ด้วยการคำนวณตำแหน่งและขนาด
        window.open(url, 'PDFPopup', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
    }

</script>
@endsection
