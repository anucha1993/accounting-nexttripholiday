@extends('layouts.template')

@section('content')
    <br>
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

        <div class="card-body">

            <h4 class="card-title">ใบจองทัวร์ จากเว็บไซต์ <a href="{{ route('booking.create') }}"
                    class="btn btn-primary float-end">เพิ่มใบจองทัวร์</a></h4>

            <hr>

            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label>ค้นหา เลขที่ใบจองทัวร์/รหัสทัวร์/ลูกค้า(ชื่อ)/ลูกค้า(นามสกุล)</label>
                        <div class="input-group mb-3 pull-right">
                            <input type="text" class="form-control" placeholder="ค้นหา เลขที่ใบจองทัวร์/รหัสทัวร์/ลูกค้า(ชื่อ)/ลูกค้า(นามสกุล)"
                                name="search_name" value="{{ request('search_name') }}">

                        </div>
                    </div>

                    <div class="col-md-2">
                        <label>ช่วงวันเดินทาง</label>
                        <input type="text" class="form-control" id="search_tour_date_range" autocomplete="off"
                            placeholder="เลือกช่วงวันเดินทาง">
                        <input type="hidden" name="search_tour_date_start" id="search_tour_date_start"
                            value="{{ request('search_tour_date_start') }}">
                        <input type="hidden" name="search_tour_date_end" id="search_tour_date_end"
                            value="{{ request('search_tour_date_end') }}">
                    </div>
<div class="col-md-3">
    <label>ช่วงเวลาการจอง (Booking Date)</label>
    <input type="text" class="form-control" id="search_booking_date_range" autocomplete="off" placeholder="เลือกช่วงเวลาการจอง">
    <input type="hidden" name="search_tour_date_start_created" id="search_tour_date_start_created" value="{{ request('search_tour_date_start_created') }}">
    <input type="hidden" name="search_tour_date_end_created" id="search_tour_date_end_created" value="{{ request('search_tour_date_end_created') }}">
</div>

                    <div class="col-md-2">

                        <label>Sales</label>
                        <div class="input-group mb-3 pull-right">
                            <select name="search_sale" class="form-select">
                                <option value="all">ทั้งหมด</option>
                                @forelse ($sales as $item)
                                    <option @if ((string) $keyword_sale === (string) $item->id) selected @endif value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @empty
                                    <option value="" disabled>ไม่มีข้อมูล</option>
                                @endforelse
                            </select>


                        </div>
                    </div>


                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary float-end mx-3" type="submit">ค้นหา</button>
                        <a href="{{ route('booking.index') }}" class="btn btn-outline-secondary float-end mx-3"
                            type="submit">ล้างข้อมูล</a>

                    </div>
                </div>
            </form>

        </div>

    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table customize-table table-hover mb-0 v-middle table-striped" style="font-size: 14px">
                    <thead class="table-light">
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขที่ใบจองทัวร์</th>
                            <th>รหัสทัวร์</th>
                            <th>ลูกค้า</th>
                            <th>โปรแกรมทัวร์</th>
                            <th>วันที่เดินทาง</th>
                            <th>PAX</th>
                            <th>เซลล์</th>
                            <th>สถานะ</th>
                            <th>วันที่จอง</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @dd($booking); --}}
                        @forelse ($booking as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->tour_code }}</td>
                                <td>{{ 'คุณ' . $item->name . ' ' . $item->surname }}</td>
                                <td>{{ Str::limit($item->tour_name, 30) }}</td>
                                <td>{{ date('d-m-Y', strtotime($item->start_date)) }} ถึง {{ date('d-m-Y', strtotime($item->end_date)) }}</td>
                                <td>{{ $item->total_qty . ' คน' }}</td>
                                <td>{{ $item->sale_name }}</td>
                                <td>

                                    @if ($item->status === 'Success')
                                        <span class="badge rounded-pill bg-success">Success</span>
                                    @endif
                                   
                                </td>
                                <td>{{ date('d-m-Y H:m', strtotime($item->created_at)) }}</td>
                                <td>
                                   
                                    @can('edit-booking')
                                        <a href="{{ route('booking.convert', $item->id) }}" class="text-primary mx-3"><i
                                                class="fas fa-edit"></i> สร้างใบเสนอราคา</a>
                                    @endcan
                                 
                                </td>

                            </tr>
                        @empty
                            No found data
                        @endforelse
                    </tbody>
                </table>
                {!! $booking->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>

    <script>

        
       $(function() {
    $('#search_tour_date_range').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear',
            applyLabel: 'เลือก',
            daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
            monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
            firstDay: 0
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

    $('#search_tour_date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        $('#search_tour_date_start').val(picker.startDate.format('YYYY-MM-DD'));
        $('#search_tour_date_end').val(picker.endDate.format('YYYY-MM-DD'));
    });

    $('#search_tour_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#search_tour_date_start').val('');
        $('#search_tour_date_end').val('');
    });

    // กรณี reload หน้า ให้แสดงช่วงวันที่เดิม
    @if(request('search_tour_date_start') && request('search_tour_date_end'))
        $('#search_tour_date_range').val(
            moment("{{ request('search_tour_date_start') }}").format('DD/MM/YYYY') + ' - ' +
            moment("{{ request('search_tour_date_end') }}").format('DD/MM/YYYY')
        );
    @endif
});
    </script>

    <script>
$(function() {
    $('#search_booking_date_range').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear',
            applyLabel: 'เลือก',
            daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
            monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
            firstDay: 0
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

    $('#search_booking_date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        $('#search_tour_date_start_created').val(picker.startDate.format('YYYY-MM-DD'));
        $('#search_tour_date_end_created').val(picker.endDate.format('YYYY-MM-DD'));
    });

    $('#search_booking_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#search_tour_date_start_created').val('');
        $('#search_tour_date_end_created').val('');
    });

    // แสดงช่วงวันที่เดิมกรณี reload หน้า
    @if(request('search_tour_date_start_created') && request('search_tour_date_end_created'))
        $('#search_booking_date_range').val(
            moment("{{ request('search_tour_date_start_created') }}").format('DD/MM/YYYY') + ' - ' +
            moment("{{ request('search_tour_date_end_created') }}").format('DD/MM/YYYY')
        );
    @endif
});
</script>
@endsection
