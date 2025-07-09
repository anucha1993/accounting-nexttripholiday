@extends('layouts.template')

@section('content')
<style>
    .card {
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
    }
    
    .form-control, .form-select {
        border-radius: 4px;
        border: 1px solid #ced4da;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    .btn {
        border-radius: 4px;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 14px;
    }
    
    .table td {
        font-size: 14px;
        vertical-align: middle;
    }
    
    /* Select2 Styles - ปรับให้ดูเหมือน dropdown ปกติของ Bootstrap */
    .select2-container {
        width: 100% !important;
        font-size: 14px;
    }

    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        height: 38px;
        display: flex;
        align-items: center;
        padding: 0;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        font-family: inherit;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #495057;
        line-height: 1.5;
        padding-left: 12px;
        padding-right: 30px;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 0;
        margin-bottom: 0;
        font-size: 14px;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
        font-size: 14px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        position: absolute;
        top: 1px;
        right: 1px;
        width: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #6c757d transparent transparent transparent;
        border-style: solid;
        border-width: 5px 4px 0 4px;
        height: 0;
        left: 50%;
        margin-left: -4px;
        margin-top: -2px;
        position: absolute;
        top: 50%;
        width: 0;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #6c757d transparent;
        border-width: 0 4px 5px 4px;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .select2-dropdown {
        background-color: white;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin-top: 2px;
        font-size: 14px;
    }

    .select2-container--default .select2-results__option {
        padding: 8px 12px;
        font-size: 14px;
        color: #495057;
        cursor: pointer;
    }

    .select2-container--default .select2-results__option--highlighted {
        background-color: #007bff;
        color: white;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #e9ecef;
        color: #495057;
    }

    .select2-container--default .select2-results__option[aria-selected=true].select2-results__option--highlighted {
        background-color: #007bff;
        color: white;
    }

    .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 4px 8px;
        margin: 8px;
        width: calc(100% - 16px);
        font-size: 14px;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        color: #6c757d;
        cursor: pointer;
        float: right;
        font-weight: bold;
        margin-right: 25px;
        padding: 0 2px;
        font-size: 18px;
        line-height: 1;
        display: none; /* ซ่อนปุ่ม clear เพื่อให้ดูเหมือน dropdown ปกติ */
    }

    .select2-container--default .select2-selection--single .select2-selection__clear:hover {
        color: #495057;
    }

    /* ปรับให้ดูเหมือน form-control ของ Bootstrap */
    .select2-container--default .select2-selection--single:hover {
        border-color: #adb5bd;
    }

    .select2-container--default.select2-container--disabled .select2-selection--single {
        background-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
        opacity: 1;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        outline: 0;
    }

    .select2-dropdown {
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: #fff;
    }

    .select2-container--default .select2-results__option {
        padding: 8px 12px;
        font-size: 14px;
    }

    .select2-container--default .select2-results__option--highlighted {
        background-color: #007bff;
        color: white;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #e9ecef;
        color: #495057;
    }

    .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 4px 6px;
        margin: 4px;
        width: calc(100% - 8px);
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        color: #999;
        cursor: pointer;
        float: right;
        font-weight: bold;
        margin-right: 10px;
        position: relative;
    }
</style>

<div class="container-fluid mt-3">
        <div class="card">
            <div class="card-header">
                <h5>ค้นหาข้อมูล Payment Wholesale</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('report.payment-wholesale') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="daterange" class="form-label">ช่วงเวลา</label>
                        <input type="text" name="daterange" id="rangDate" class="form-control rangDate"
                            autocomplete="off" value="{{ request('daterange') }}" placeholder="เลือกช่วงวันที่" />
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="wholesale_name" class="form-label">ชื่อโฮลเซลล์</label>
                        <select name="wholesale_id" class="form-select select2-dropdown" data-placeholder="เลือกโฮลเซลล์">
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
                        <label for="quote_number" class="form-label">Quotation No.</label>
                        <input type="text" id="quote_number" name="quote_number" class="form-control"
                            placeholder="Quotation No." value="{{ request('quote_number') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="payment_type" class="form-label">ประเภทการชำระ</label>
                        <select name="payment_type" id="payment_type" class="form-select">
                            <option value="">-- เลือกประเภท --</option>
                            <option value="full" {{ request('payment_type') == 'full' ? 'selected' : '' }}>ชำระเงินเต็มจำนวน</option>
                            <option value="deposit" {{ request('payment_type') == 'deposit' ? 'selected' : '' }}>ชำระมัดจำ</option>
                        </select>
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
                <h5>Report Payment Wholesale</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>Payment No.</th>
                                <th>วันที่ทำรายการ</th>
                                <th>วันที่ชำระ</th>
                                <th>จำนวนเงิน</th>
                                <th>ยอดคืน</th>
                                <th>สถานะการคืน</th>
                                <th>ไฟล์แนบ</th>
                                <th>โฮลเซลล์</th>
                                <th>Quotation No.</th>
                                <th>ประเภทการชำระ</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($paymentWholesale as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->payment_wholesale_number }}</td>
                                    <td>{{ date('d/m/Y H:i:s', strtotime($item->created_at)) }}</td>
                                    <td>
                                        @if ($item->payment_wholesale_date)
                                            {{ date('d/m/Y H:i:s', strtotime($item->payment_wholesale_date)) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->payment_wholesale_total, 2) }}</td>
                                    <td>
                                        @if ($item->payment_wholesale_refund_type !== null)
                                            <span class="text-danger">{{ number_format($item->payment_wholesale_refund_total, 2) }}</span>
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
                                            <small class="text-muted">สลิปชำระ:</small><br>
                                            <a onclick="openPdfPopup(this.href); return false;"
                                                href="{{ asset($item->payment_wholesale_file_path) }}">{{ $item->payment_wholesale_file_name }}</a>
                                        @else
                                            <span class="text-info">รอยืนยันการชำระเงิน</span>
                                        @endif

                                        <br>
                                        @if ($item->payment_wholesale_refund_file_name !== null)
                                            <small class="text-muted">สลิปคืนยอด:</small><br>
                                            @if($item->payment_wholesale_refund_file_name)
                                                <a onclick="openPdfPopup(this.href); return false;" class="text-danger"
                                                    href="{{ asset($item->payment_wholesale_refund_file_path) }}">{{ $item->payment_wholesale_refund_file_name }}</a><br>
                                            @endif
                                            @if($item->payment_wholesale_refund_file_name1)
                                                <a onclick="openPdfPopup(this.href); return false;" class="text-danger"
                                                    href="{{ asset($item->payment_wholesale_refund_file_path1) }}">{{ $item->payment_wholesale_refund_file_name1 }}</a><br>
                                            @endif
                                            @if($item->payment_wholesale_refund_file_name2)
                                                <a onclick="openPdfPopup(this.href); return false;" class="text-danger"
                                                    href="{{ asset($item->payment_wholesale_refund_file_path2) }}">{{ $item->payment_wholesale_refund_file_name2 }}</a>
                                            @endif
                                        @endif
                                    </td>

                                    <td>{{ $item->quote?->quoteWholesale->wholesale_name_th }}</td>
                                    <td>{{ $item->quote?->quote_number }}</td>
                                    <td>{{ $item->payment_wholesale_type === 'full' ? 'ชำระเงินเต็มจำนวน' : 'ชำระมัดจำ' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted">ไม่พบข้อมูล Payment Wholesale</td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if($paymentWholesale->count() > 0)
                            <tfoot>
                                <tr class="table-secondary">
                                    <th colspan="4" class="text-end">รวมทั้งสิ้น:</th>
                                    <th>{{ number_format($sum_total, 2) }} บาท</th>
                                    <th>{{ number_format($sum_refund, 2) }} บาท</th>
                                    <th colspan="5"></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
                
                @if($paymentWholesale->hasPages())
                    <div class="mt-3">
                        {!! $paymentWholesale->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2-dropdown').select2({
        placeholder: function() {
            return $(this).data('placeholder') || 'เลือก...';
        },
        allowClear: true,
        language: {
            noResults: function() {
                return "ไม่พบผลลัพธ์";
            },
            searching: function() {
                return "กำลังค้นหา...";
            }
        }
    });

    // Daterangepicker แบบ preset เหมือน input-tax-form
    $(".rangDate").daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: "DD/MM/YYYY",
            separator: " - ",
            applyLabel: "ตกลง",
            cancelLabel: "ยกเลิก",
            fromLabel: "จาก",
            toLabel: "ถึง",
            customRangeLabel: "กำหนดเอง",
            daysOfWeek: ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"],
            monthNames: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                        "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
            firstDay: 1
        },
        ranges: {
            'วันนี้': [moment(), moment()],
            'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 วันที่แล้ว': [moment().subtract(6, 'days'), moment()],
            '30 วันที่แล้ว': [moment().subtract(29, 'days'), moment()],
            'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
            'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $(".rangDate").on("apply.daterangepicker", function(ev, picker) {
        $(this).val(
            picker.startDate.format("DD/MM/YYYY") +
            " - " +
            picker.endDate.format("DD/MM/YYYY")
        );
        $("input[name='start_date']").val(picker.startDate.format("YYYY-MM-DD"));
        $("input[name='end_date']").val(picker.endDate.format("YYYY-MM-DD"));
    });

    $(".rangDate").on("cancel.daterangepicker", function(ev, picker) {
        $(this).val("");
        $("input[name='start_date']").val("");
        $("input[name='end_date']").val("");
    });
});
</script>
@endsection
