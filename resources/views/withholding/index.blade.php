@extends('layouts.template')

@section('content')
<div class="container-fluid page-content">
    <div class="card">

        <div class="card-body">
    <h3>รายการใบหัก ณ ที่จ่าย</h3>
    <a href="{{ route('withholding.create') }}" class="btn btn-primary mb-3">เพิ่มเอกสารใหม่</a>
    <table class="table table" id="table-withholding">
        <thead>
            <tr>
                <th>No.</th>
                <th>เลขที่เอกสาร</th>
                <th>Ref. Number</th>
                <th>ชื่อลูกค้า</th>
                <th>วันที่</th>
                <th>ยอดชำระ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $key => $document)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $document->document_number }}</td>
                <td>{{ $document->ref_number }}</td>
                <td>{{ $document->customer->customer_name }}</td>
                <td>{{ date('d/m/Y',strtotime($document->document_date)) }}</td>
                <td>{{ number_format($document->total_payable,2) }}</td>
                <td>
                   

                    <a href="{{route('MPDF.withholding',$document->id)}}" onclick="openPdfPopup(this.href); return false;" class="btn btn-info btn-sm">ดู</a>
                    <a href="{{ route('withholding.edit', $document->id) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                    <form action="{{ route('withholding.destroy', $document->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
