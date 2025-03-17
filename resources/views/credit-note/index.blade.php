@extends('layouts.template')

@section('content')
    <style>
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

        .select2-selection {
            height: 30px !important;
            text-align: left;
            z-index: 9999;
        }

        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .readonly {
            background-color: #e0e0e0;
        }
    </style>

    </style>
    <div class="container-fluid page-content email-app">
        <!-- Todo list-->
        {{-- <div class="email-app todo-box-container container-fluid"> --}}
        <div class="todo-listing ">
            <div class=" border bg-white">
                <h4 class="text-center my-4">ใบเพิ่มหนี้ Credit Note
                </h4>
                <hr>
               <div class="card">
                <div class="card-body">
                    <form action="#" method="get">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">เลขที่ใบเพิ่มหนี้</label>
                                <input type="text" name="creditnote_number" class="form-control" placeholder="DBNXX">
                            </div>

                            <div class="col-md-3">
                                <label for="">เลขที่ใบเสนอราคา</label>
                                <input type="text" name="creditnote_quote" class="form-control" placeholder="QTXX">
                            </div>
                            <div class="col-md-3">
                                <label for="">เลขที่ใบกำกับภาษี</label>
                                <input type="text" name="creditnote_tax" class="form-control" placeholder="RVXX">
                            </div>
                             <div class="col-md-3">
                                <label for="">ชื่อลูกค้า</label>
                                <select name="customer_id" class="form-select select2" style="width: 100%">
                                    <option value="">ไม่เลือก</option>
                                    @forelse ($customers as $item)
                                        <option value="{{$item->customer_id}}">{{$item->customer_name}}</option>
                                    @empty
                                        
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="">วันที่เริ่มต้น</label>
                                <input type="date" name="date_start" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="">วันที่สิ้นสุด</label>
                                <input type="date" name="date_end" class="form-control">
                            </div>

                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-info">ค้นหา</button>
                            </div>
                        </div>
                    </form>
                </div>
               </div>
              
            </div>
        </div>
        <div class="todo-listing ">
         <a href="{{route('credit-note.create')}}" class="btn btn-info mt-4">สร้างใบเพิ่มหนี้</a>
        </div>
        <br>

        <div class="todo-listing ">
            <div class=" border bg-white">
                <table class="table table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>วันที่</th>
                            <th>เลขที่ใบเพิ่มหนี้</th>
                            <th>Ref.Quote</th>
                            <th>Ref.Tax</th>
                            <th>ลูกค้า</th>
                            <th>มูลค่าเดิม</th>
                            <th>ผลต่าง</th>
                            <th>มูลค่าที่ถูกต้อง</th>
                            <th>จำนวนเงิน</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($creditNote as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->creditnote_date)) }}</td>
                                <td>{{ $item->creditnote_number }}</td>
                                <td><a href="{{ route('quote.editNew', $item->quote->quote_id) }}" target="_blank">
                                        {{ $item->quote->quote_number }}</a></td>
                                <td><a href="{{ route('mpdf.taxreceipt', $item->invoice->invoice_id) }}" target="_blank">
                                        {{ $item->taxinvoice->taxinvoice_number }}</a></td>
                                <td>{{ $item->quote->customer->customer_name }}</td>
                 
                                <td>{{ number_format($item->creditnote_total_old, 2) }}</td>
                                <td>{{ number_format($item->creditnote_difference, 2) }}</td>
                                <td>{{ number_format($item->creditnote_total_new, 2) }}</td>
                                <td>{{ number_format($item->creditnote_grand_total, 2) }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <div class="btn-group btn-group-sm" role="group">
                                            
                                            <button id="btnGroupDrop1" type="button" class="btn btn-light-success text-secondary font-weight-medium dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>

                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a href="{{route('credit-note.edit',$item->creditnote_id)}}" class=" dropdown-item text-info"> <i
                                                        class="fa fa-edit"></i> แก้ไข</a>
                                                <a class="dropdown-item" href="{{route('MPDF.credit-note.generatePDF',$item->creditnote_id)}}" target="_blink"><i
                                                        class="fa fa-print text-danger"></i> พิมพ์</a>
                                                <a class="dropdown-item mail-creditnote" href="{{route('mail.creditNoteModel.formMail',$item->creditnote_id)}}"><i
                                                        class="fas fa-envelope text-info"></i> ส่งเมล</a>
                                                <a class="dropdown-item" href="{{route('credit-note.copy',$item->creditnote_id)}}" target="_blink" ><i class="fas fa-share-square text-info"></i> สร้างซ้ำ</a>
                                                <a  onclick="return confirm('คุณต้องการลบ ใบเพิ่มหนี้ ใช่ หรือ ไม่')" class="dropdown-item" href="{{route('credit-note.delete',$item->creditnote_id)}}"><i class="fas fa-trash text-danger"></i> ลบ</a>

                                            </div>
                                        </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        <tr>
                            <td colspan="9"></td>
                            <td align="right" class="text-danger">จำนวนเงินทั้งหมด : {{number_format($creditNote->sum('creditnote_grand_total'),2)}} บาท</td>
  
                        </tr>
                    </tbody>
                </table>
                {!! $creditNote->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>

    </div>

    
{{-- mail form quote --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="modal-mail-creditnote" tabindex="-1" role="dialog"
aria-labelledby="mySmallModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        ...
    </div>
</div>
</div>

<script>
     $(document).ready(function() {
        // modal add payment wholesale quote
        $(".mail-creditnote").click("click", function(e) {
            e.preventDefault();
            $("#modal-mail-creditnote")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
    });
</script>


@endsection
