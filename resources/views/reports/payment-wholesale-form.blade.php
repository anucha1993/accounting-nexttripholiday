@extends('layouts.template')

@section('content')
    <br>
    <div class="email-app todo-box-container container-fluid">
        <div class="card">
            <div class="card-header">
                <h3>ค้นหาข้อมูล</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('report.payment-wholesale') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="wholesale_name" class="form-label">ชื่อโฮลเซลล์</label>
                        <select name="wholesale_id" class="form-select">
                            <option value="">-- เลือกโฮลเซลล์ --</option>
                            @foreach ($wholesales as $wholesale)
                                <option value="{{ $wholesale->id }}"
                                    {{ request('wholesale_id') == $wholesale->id ? 'selected' : '' }}>
                                    {{ $wholesale->wholesale_name_th }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="quote_number" class="form-label">เลขที่ใบเสนอราคา</label>
                        <input type="text" id="quote_number" name="quote_number" class="form-control"
                            placeholder="เลขที่ใบเสนอราคา" value="{{ request('quote_number') }}">
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                        <a href="{{ route('report.payment-wholesale') }}" class="btn btn-secondary">รีเซ็ต</a>
                        <a href="{{ route('report.payment-wholesale.export', request()->all()) }}" class="btn btn-success">Export Excel</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3> Report Payment Wholesale</h3>
            </div>
            <div class="card-body">
                <table class="table table">
                    <thead>
                        <th>ลำดับ</th>
                        <th>Payment No.</th>
                        <th>วันที่ทำรายการ</th>
                        <th>วันที่ชำระ</th>
                        <th>จำนวนเงิน</th>
                        <th>ยอดคืน</th>
                        <th>สถานะกาคืน</th>
                        <th>ไฟล์แนบ</th>
                        <th>โฮลเซลล์</th>
                        <th>Quotation No.</th>
                        <th>ประเภทการ</th>
                    </thead>

                    <tbody>
                        @forelse ($paymentWholesale as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->payment_wholesale_number }}</td>
                                <td>{{ date('d/m/Y : H:m:s', strtotime($item->created_at)) }}</td>
                                <td>
                                    @if ($item->payment_wholesale_date)
                                        {{ date('d/m/Y : H:m:s', strtotime($item->payment_wholesale_date)) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ number_format($item->payment_wholesale_total, 2, '.', ',') }}</td>
                                <td>
                                    @if ($item->payment_wholesale_refund_type !== null)
                                        {!! '<span class="text-danger">' . number_format($item->payment_wholesale_refund_total, 2) . '</span>' !!}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{!! payment_refund_status_text(
                                    $item->payment_wholesale_refund_total,
                                    $item->payment_wholesale_refund_status,
                                    $item->payment_wholesale_refund_type,
                                ) !!}
                                </td>

                                <td>
                                    @if ($item->payment_wholesale_file_path !== null)
                                        สลิปชำระ : <a onclick="openPdfPopup(this.href); return false;"
                                            href="{{ asset($item->payment_wholesale_file_path) }}">{{ $item->payment_wholesale_file_name }}</a>
                                    @else
                                        <span class="text-info">รอยืนยันการชำระเงิน</span>
                                    @endif

                                    <br>
                                    @if ($item->payment_wholesale_refund_file_name !== null)
                                        สลิปคืนยอด :
                                        <a onclick="openPdfPopup(this.href); return false;" class="text-danger"
                                            href="{{ asset($item->payment_wholesale_refund_file_path) }}">{{ $item->payment_wholesale_refund_file_name }}</a><br>
                                        <a onclick="openPdfPopup(this.href); return false;" class="text-danger"
                                            href="{{ asset($item->payment_wholesale_refund_file_path1) }}">{{ $item->payment_wholesale_refund_file_name1 }}</a><br>
                                        <a onclick="openPdfPopup(this.href); return false;" class="text-danger"
                                            href="{{ asset($item->payment_wholesale_refund_file_path2) }}">{{ $item->payment_wholesale_refund_file_name2 }}</a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $item->quote?->quoteWholesale->wholesale_name_th }}
                                </td>
                                <td>{{ $item->quote?->quote_number }}</td>
                                <td>{{ $item->payment_wholesale_type === 'full' ? 'ชำระเงินเต็มจำนวน' : 'ชำระมัดจำ' }}</td>
                            </tr>
                        @empty
                        @endforelse
                        <tr>
                            <td  colspan="8"></td>
                            <td  align="right" colspan="2" class="float-end"><strong>รวมจำนวนเงิน:</strong> {{ number_format($sum_total, 2) }} บาท</td>
                            <td  align="right" colspan="2"><strong>รวมยอดคืน:</strong> {{ number_format($sum_refund, 2) }} บาท</td>
                        </tr>
                        {{-- <tr>
                            <div class="mb-3">
                                <strong>รวมจำนวนเงิน:</strong> {{ number_format($sum_total, 2) }} บาท |
                                <strong>รวมยอดคืน:</strong> {{ number_format($sum_refund, 2) }} บาท
                            </div>
                        </tr> --}}

                    </tbody>

                </table>
                {!! $paymentWholesale->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
@endsection
