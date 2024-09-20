@extends('layouts.template')

@section('content')
    <style>
        <style>.table-custom {
            border: 1px solid #e0e0e0;
            padding: 10px;
            margin-bottom: 20px;
        }


        .table-custom input,
        .table-custom select {
            width: 100%;
            padding: 3px;
            margin-bottom: 10px;
        }

        .add-row {
            margin: 10px 0;
            text-align: left;
        }
    </style>

    </style>
    <div class="container-fluid page-content">
        <!-- Todo list-->
        <div class="todo-listing ">
            <div class="container border bg-white">
                <h4 class="text-center my-4">สร้างใบเสนอราคา
                </h4>
                <hr>

                <form action="" method="post">
                    @csrf

                    <div class="row table-custom ">
                        <div class="col-md-3 ms-auto">
                            <label><b>เซลล์ผู้ขายแพคเกจ:</b></label>
                            <select name="quote_sale" class="form-select">
                                @forelse ($sales as $item)
                                    <option @if ($bookingModel->sale_id === $item->id) selected @endif value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @empty
                                    <option value="">--Select Sale--</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-3 ms-3">
                            <label>วันที่สั่งซื้อ,จองแพคเกจ:</label>
                            <input type="text" id="displayDatepicker" class="form-control">
                            <input type="hidden" id="submitDatepicker" name="date" name="quote_booking_date"
                                value="{{ date('Y-m-d', strtotime($bookingModel->created_at)) }}">
                        </div>
                    </div>
                    <hr>
                    <h5 class="text-danger">รายละเอียดแพคเกจทัวร์:</h5>

                    <div class="row table-custom">
                        <div class="col-md-6">
                            <label>ชื่อแพคเกจทัวร์:</label>
                            <input type="text" id="tourSearch" class="form-control" placeholder="ค้นหาแพคเกจทัวร์..." value="{{$tour->code}}-{{$tour->name}}">
                            
                            <div id="tourResults" class="list-group"
                                style="position: absolute; z-index: 1000; width: 100%;"></div>
                        </div>
                    </div>

                </form>
                <br>

            </div>
        </div>

    </div>
    <!-- This Page JS -->


    </div>

    <script>
        $(document).ready(function() {
            $('#tourSearch').on('input', function() {
                var searchTerm = $(this).val();
                if (searchTerm.length >= 2) {
                    $.ajax({
                        url: '{{route("api.tours")}}', // URL สำหรับดึงข้อมูลทัวร์
                        method: 'GET',
                        data: {
                            search: searchTerm
                        },
                        success: function(data) {
                            $('#tourResults').empty();
                            if (data.length > 0) {
                                $.each(data, function(index, item) {
                                    $('#tourResults').append(` <a href="#" class="list-group-item list-group-item-action" data-id="${item.id}">${item.code} - ${item.name}</a>`);
                                });
                            } else {
                                $('#tourResults').append(
                                    '<a href="#" class="list-group-item list-group-item-action disabled">ไม่พบข้อมูล</a>'
                                    );
                            }
                        }
                    });
                } else {
                    $('#tourResults').empty(); // ล้างผลลัพธ์เมื่อไม่มีคำค้นหา
                }
            });

            // เมื่อคลิกเลือกแพคเกจจากผลลัพธ์การค้นหา
            $(document).on('click', '#tourResults a', function(e) {
                e.preventDefault();
                var selectedId = $(this).data('id');
                var selectedText = $(this).text();

                $('#tourSearch').val(selectedText); // แสดงชื่อแพคเกจที่เลือกใน input
                $('#selectedTour').val(selectedId); // เก็บค่า id ใน hidden input
                $('#tourResults').empty(); // ล้างผลลัพธ์การค้นหา
            });
        });
    </script>


    <script>
        $(function() {
            // ตั้งค่าภาษาไทยให้กับ Datepicker
            $.datepicker.regional['th'] = {
                closeText: 'ปิด',
                prevText: 'ย้อน',
                nextText: 'ถัดไป',
                currentText: 'วันนี้',
                monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                    'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                ],
                monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
                    'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
                ],
                dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
                dayNamesShort: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
                dayNamesMin: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
                weekHeader: 'Wk',
                dateFormat: 'dd MM yy', // รูปแบบการแสดงผลเป็นวัน เดือน ปี
                firstDay: 0,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['th']);

            // แปลงวันที่จากรูปแบบ Y-m-d เป็นรูปแบบภาษาไทย
            function formatDateToThai(dateString) {
                const date = new Date(dateString);
                return $.datepicker.formatDate('dd MM yy', date, $.datepicker.regional['th']);
            }

            // ตั้งค่า Datepicker ให้แสดงผลภาษาไทยและจัดการเมื่อเลือกวันที่
            $('#displayDatepicker').datepicker({
                dateFormat: 'dd MM yy', // รูปแบบการแสดงผลใน Datepicker
                onSelect: function(dateText, inst) {
                    // แปลงวันที่ที่เลือกเป็นรูปแบบ Y-m-d และอัพเดต hidden input
                    const selectedDate = new Date(inst.selectedYear, inst.selectedMonth, inst
                        .selectedDay);
                    const isoDate = $.datepicker.formatDate('yy-mm-dd', selectedDate);
                    $('#submitDatepicker').val(isoDate);
                }
            });

            // กำหนดค่าเริ่มต้นให้กับ Datepicker (แสดงเป็นภาษาไทย) และ hidden input
            let defaultDate = '{{ date('Y-m-d', strtotime($bookingModel->created_at)) }}';
            $('#submitDatepicker').val(defaultDate);
            const thaiFormattedDate = formatDateToThai(defaultDate);
            $('#displayDatepicker').val(thaiFormattedDate);
        });
    </script>
@endsection
