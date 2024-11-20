@extends('layouts.template')

@section('content')
<div class="container">
    <h1>รายการใบหัก ณ ที่จ่าย</h1>
    <a href="{{ route('withholding.create') }}" class="btn btn-primary mb-3">เพิ่มเอกสารใหม่</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>เลขที่เอกสาร</th>
                <th>ชื่อลูกค้า</th>
                <th>วันที่</th>
                <th>ยอดชำระ</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $document)
            <tr>
                <td>{{ $document->document_number }}</td>
                <td>{{ $document->customer->customer_name }}</td>
                <td>{{ $document->document_date }}</td>
                <td>{{ $document->total_payable }}</td>
                <td>
                    <a href="{{ route('withholding.show', $document->id) }}" class="btn btn-info">ดู</a>
                    <a href="{{ route('withholding.edit', $document->id) }}" class="btn btn-warning">แก้ไข</a>
                    <form action="{{ route('withholding.destroy', $document->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">ลบ</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
