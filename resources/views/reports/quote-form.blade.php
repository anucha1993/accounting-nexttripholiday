@extends('layouts.template')

@section('content')
    <!-- buttons -->
    <style>
        span[title]:hover::after {
            content: attr(title);
            background-color: #f0f0f0;
            padding: 5px;
            border: 1px solid #ccc;
            position: absolute;
            z-index: 1;
        }
    </style>



    <div class="email-app todo-box-container container-fluid">
        <div class="card">
            <div class="card-header">
                <h3>Report quotations</h3>
            </div>
            <div class="card-body">
                <table class="table table quote-table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ใบเสนอราคา</th>
                            <th>เลขที่ใบจองทัวร์</th>
                            <th>โปรแกรมทัวร์</th>
                            <th>Booking Date</th>
                            <th>วันที่เดินทาง</th>
                            <th>ชื่อลูกค้า</th>
                            <th>Pax</th>
                            <th>ประเทศ</th>
                            <th>สายการบิน</th>
                            <th>โฮลเซลล์</th>
                            <th>การชำระของลูกค้า</th>
                            <th>ยอดใบแจ้งหนี้</th>
                            <th>การชำระโฮลเซลล์</th>
                            <th>ผู้ขาย</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="10" style="text-align:right">Total:</th>
                            <th></th>
                            <th></th>

                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('fonts/vfs_fonts.js') }}"></script>

    <script>
        $(function() {
            var table = $('.quote-table').DataTable({
                autoWidth: false,
                columnDefs: [{
                        width: '20px',
                        targets: 0
                    },
                    {
                        width: '100px',
                        targets: 1
                    },

                ],
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: ['excel', 'csv', {
                    extend: 'pdf',
                    customize: function(doc) {
          
                        doc.styles = {
                            header: {
                                fontSize: 18,
                                bold: true,
                                alignment: 'center',
                                margin: [0, 0, 0, 10],
    
                            },
                            subheader: {
                                fontSize: 12,
                                alignment: 'center',
                                margin: [0, 0, 0, 10],
               
                            },
                          
                        };
                        doc.content[1].layout = "borders";


                        console.log(doc.content)
                        pdfMake.vfs = vfs;

                        pdfMake.fonts = {
                            THSarabun: {
                                normal: 'THSarabun.ttf',
                                bold: 'THSarabun Bold.ttf',
                                italics: 'THSarabun Italic.ttf',
                                bolditalics: 'THSarabun Bold Italic.ttf'
                            },
                        };
                        doc.pageSize = 'A4';
                        doc.pageOrientation = 'landscape';
                        doc.defaultStyle.font = 'THSarabun';



                        // เพิ่ม footer ลงใน doc.content
                        var footer = table.column(10).footer().innerHTML;
                        doc.content[1].table.body.push([{
                                text: 'Total:',
                                colSpan: 10,
                                alignment: 'right'
                            },
                            {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {},
                            {
                                text: footer,
                                alignment: 'right'
                            },
                            {}, {}
                        ]);
                       
  
                       
               
                        doc.content.unshift({
                            text: 'รายงานสรุปใบเสนอราคา',
                            style: 'header'
                        }, {
                            text: 'วันที่สร้าง: ' + new Date().toLocaleDateString(),
                            style: 'subheader'
                        });
                        
                        

                        
                    
            
                    },
                    

                }],
                ajax: "{{ route('report.quote.form') }}",
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'quote_number',
                        name: 'quote_number'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.quote_tour ? row.quote_tour : row.quote_tour_code;
                        },
                        name: 'quote_tour_or_code',
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            var word = row.quote_tour_name ? row.quote_tour_name : row
                                .quote_tour_name1;
                            var maxLength = 20; // กำหนดความยาวสูงสุด
                            var truncatedWord = word.length > maxLength ? word.substring(0,
                                maxLength) + '...' : word;
                            return '<span title="' + word + '">' + truncatedWord + '</span>';
                        },
                        name: 'quote_tour_name',
                    },

                    {
                        data: 'quote_booking_create',
                        name: 'quote_booking_create',
                        render: function(data, type, row) {
                            return formatDate(data);
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return formatDateRange(row.quote_date_start, row.quote_date_end);
                        },
                        name: 'quote_date_range',
                    },
                    {
                        data: 'customer_name',
                        render: function(data, type, row) {
                            return data || "";
                        },
                    },
                    {
                        data: 'quote_pax_total',
                        name: 'quote_pax_total'
                    },
                    {
                        data: 'country_name_th',
                        render: function(data, type, row) {
                            return data || "";
                        },
                    },
                    {
                        data: 'travel_name',
                        render: function(data, type, row) {
                            return data || "";
                        },
                    },
                    {
                        data: 'code',
                        render: function(data, type, row) {
                            return data || "";
                        },
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
                    },
                    {
                        data: 'quote_grand_total',
                        name: 'quote_grand_total',
                        render: function(data, type, row) {
                            return data ?
                                new Intl.NumberFormat('th-TH', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2,
                                }).format(data) :
                                "";
                        },
                    },
                    {
                        data: 'payment_wholesale',
                        name: 'payment_wholesale'
                    },
                    {
                        data: 'sale_name',
                        render: function(data, type, row) {
                            return data || "";
                        },
                    },
                ],
                order: [
                    [0, 'desc']
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var total = api
                        .column(12, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(acc, val) {
                            var numVal = parseFloat(val.replace(/,/g, '')) || 0;
                            return acc + numVal;
                        }, 0);
                    $(api.column(10).footer()).html(
                        new Intl.NumberFormat('th-TH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }).format(total)
                    );
                },
            });

            function formatDate(dateString) {
                return dateString ?
                    new Date(dateString).toLocaleDateString('th-TH') :
                    "";
            }

            function formatDateRange(startDateString, endDateString) {
                const startDate = formatDate(startDateString);
                const endDate = formatDate(endDateString);
                return startDate && endDate ? `${startDate} - ${endDate}` : "";
            }
        });
    </script>
@endsection
