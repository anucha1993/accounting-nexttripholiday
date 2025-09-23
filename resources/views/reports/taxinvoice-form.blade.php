@extends('layouts.template')

@section('content')
    <!-- buttons -->
    <style>
        span[titlespan]:hover::after {
            content: attr(titlespan);
            background-color: #f0f0f0;
            padding: 5px;
            border: 1px solid #ccc;
            position: absolute;
            z-index: 1;
        }
    </style>

    <div class="email-app todo-box-container container-fluid">

        <div class="card">
            <div class="card-header mt-2">
                <h4 class="text-info">รายงานใบกำกับภาษีตามเอกสาร </h4>
            </div>
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-2">
                                    <label for="">ช่วงเวลา</label>
                                    <input type="text" name="daterange" id="rangDate" class="form-control rangDate" autocomplete="off" value="{{ request('daterange') }}" placeholder="เลือกช่วงวันที่" />

                                    <input type="hidden" name="date_start" value="{{ request('date_start') }}">
                                    <input type="hidden" name="date_end" value="{{ request('date_end') }}">
                                </div>

                            <div class="col-md-2">
                                <label for="">สถานะ</label>
                                <select name="status" id="" class="form-select" >
                                    <option value="">---กรุณาเลือก---</option>
                                    <option value="success">สำเร็จ</option>
                                    <option value="cancel">ยกเลิก</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">เงือนไข</label>
                                <select name="column_name" class="form-select">
                                    <option @if($request->column_name === 'all') selected @endif value="all">ทั้งหมด</option>
                                    <option @if($request->column_name === 'taxinvoice_number') selected @endif value="taxinvoice_number">เลขที่ใบกำกับภาษี</option>
                                    <option @if($request->column_name === 'invoice_number') selected @endif value="invoice_number">เลขที่ใบแจ้งหนี้</option>
                                    <option @if($request->column_name === 'invoice_booking') selected @endif value="invoice_booking">เลขที่ใบจองทัวร์</option>
                                    <option @if($request->column_name === 'customer_name') selected @endif value="customer_name">ชื่อลูกค้า</option>
                                    <option @if($request->column_name === 'customer_texid') selected @endif value="customer_texid">เลขประจำตัวผู้เสียภาษี</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">คียร์เวิร์ด</label>
                                <input type="text" name="keyword" class="form-control" placeholder="เลขที่ใบกำกับภาษี/เลขที่ใบแจ้งหนี้/เลขที่ใบเสนอราคา/ชื่อลูกค้า" value="{{$request->keyword}}">
                            </div>

                            <div class="col-md-2">
                               <br>
                                <button type="submit" class="btn  btn-info float-end ml-2">แสดงรายงาน</button>
                            </div>
                            <div class="col-md-1">
                                <br>
                                 <a href="{{route('report.taxinvoice')}}"  class="btn  btn-danger float-end ml-2">ล้างการค้นหา</a>
                             </div>
                        </div>
                     
                        </div>
                       
                    </div>
                </form>
            </div>
        </div>





        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-info mb-0">รายงานใบกำกับภาษี</h3>
                @canany(['report.taxinvoice.export'])
                <form action="{{route('export.taxinvoice')}}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="taxinvoice_ids" value="{{$taxinvoices->pluck('taxinvoice_id')}}">
                    <input type="hidden" name="export_all" value="1">
                    @if(request()->filled(['date_start', 'date_end']))
                    <input type="hidden" name="date_start" value="{{ request()->date_start }}">
                    <input type="hidden" name="date_end" value="{{ request()->date_end }}">
                    @endif
                    @if(request()->filled('status'))
                    <input type="hidden" name="status" value="{{ request()->status }}">
                    @endif
                    @if(request()->filled(['column_name', 'keyword']))
                    <input type="hidden" name="column_name" value="{{ request()->column_name }}">
                    <input type="hidden" name="keyword" value="{{ request()->keyword }}">
                    @endif
                    <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export To Excel</button>
                </form>
                @endcanany
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    {{ $taxinvoices->links('pagination::bootstrap-5') }}
                <table class="table table quote-table " style="font-size: 12px; width: 100%">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขที่ใบกำกับภาษี</th>
                            <th>เลขที่ใบแจ้งหนี้</th>
                            <th>วันที่ออกใบกำกับภาษี</th>
                            <th>ชื่อลูกค้า</th>
                            <th>Quotations</th>
                            <th>จำนวนเงิน:บาท</th>
                            <th>ภาษีหัก ณ ที่จ่าย:บาท</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>


                        
                    <tbody>
                        @forelse ($taxinvoices as $key => $item)
                        <tr>
                         
                            <td>{{++$key}}</td>
                            <td> 
                                @canany(['report.receipt.view'])
                                <a href="{{route('mpdf.taxreceipt',$item->invoice_id)}}" target="_blank">{{$item->taxinvoice_number}}</a>
                                @endcanany
                            </td>
                            <td> 
                                @canany(['invoice.view','invoice.edit','report.invoice.view'])
                                <a target="_blank" href="{{route('mpdf.invoice',$item->invoice_id)}}">{{$item->invoice_number}}</a> 
                                @endcanany
                            </td>
                            <td>{{date('d/m/Y',strtotime($item->taxinvoice_date))}}</td>
                            <td>{{$item->invoice->customer->customer_name}}</td>
                            <td> 
                                @canany(['quote.view','quote.edit'])
                                <a target="_blank" href="{{route('quote.editNew',$item->invoice->quote->quote_id)}}">{{$item->invoice->quote->quote_number ? $item->invoice->quote->quote_number : 'ใบเสนอราคาถูกลบ'}}</a>
                                @endcanany
                            </td>
                            <td>{{number_format($item->invoice->invoice_grand_total,2)}}</td>
                            <td>{{number_format($item->invoice->invoice_withholding_tax,2)}}</td>
                            <td>{{$item->taxinvoice_status === 'success' ? 'สำเร็จ' : 'ยกเลิก'  }}</td>
                           
                   
                        </tr>
                            
                        @empty
                            
                        @endforelse
                       
                    </tbody>
                    <tfoot>
                        <!-- ยอดรวมในหน้าปัจจุบัน -->
                        {{-- <tr class="text-primary">
                            <th colspan="5" style="text-align:left">รวมในหน้านี้:</th>
                            <th style="text-align:right">{{ number_format($currentPageTotals['grand_total'], 2) }}</th>
                            <th style="text-align:right">{{ number_format($currentPageTotals['withholding_tax'], 2) }}</th>
                            <th></th>
                        </tr> --}}
                        <!-- ยอดรวมทั้งหมด -->
                        <tr class="text-danger">
                            <th colspan="5" style="text-align:left">รวมทั้งหมด:</th>
                            <th style="text-align:right">{{ number_format($allTotals['grand_total'], 2) }}</th>
                            <th style="text-align:right">{{ number_format($allTotals['withholding_tax'], 2) }}</th>
                            <th></th>
                        </tr>
                        <!-- ภาษีมูลค่าเพิ่ม -->
                        <tr class="text-success">
                            <th colspan="5" style="text-align:left">ภาษีมูลค่าเพิ่ม (7%):</th>
                            <th style="text-align:right">หน้านี้: {{ number_format($currentPageTotals['vat'], 2) }}</th>
                            <th style="text-align:right">ทั้งหมด: {{ number_format($allTotals['vat'], 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                   {{ $taxinvoices->links('pagination::bootstrap-5') }}
          
            </div>
        </div>
    </div>
    

    
    <script>
        $(function() {
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
    
                $("input[name='date_start']").val(picker.startDate.format("YYYY-MM-DD"));
                $("input[name='date_end']").val(picker.endDate.format("YYYY-MM-DD"));
            });
    
            $(".rangDate").on("cancel.daterangepicker", function(ev, picker) {
                $(this).val("");
                $("input[name='date_start']").val("");
                $("input[name='date_end']").val("");
            });
        });
        </script>

@endsection