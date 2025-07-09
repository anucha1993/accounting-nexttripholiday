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
            height: 38px !important;
            text-align: left;
            z-index: 9999;
        }

        .select2-selection__rendered {
            line-height: 36px !important;
        }

        .readonly {
            background-color: #e0e0e0;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .badge {
            font-size: 0.75em;
        }

        .btn-group .dropdown-toggle::after {
            margin-left: 0.5em;
        }

        .filter-badge {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .table-responsive {
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .text-positive {
            color: #198754 !important;
        }

        .text-negative {
            color: #dc3545 !important;
        }
    </style>

    </style>
    <div class="container-fluid page-content email-app">
        <!-- Todo list-->
        {{-- <div class="email-app todo-box-container container-fluid"> --}}
        <div class="todo-listing ">
            <div class=" border bg-white">
                <h4 class="text-center my-4">ใบเพิ่มหนี้ Debit Note
                </h4>
                <hr>
               <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">ตัวกรองการค้นหา</h6>
                    @if(request()->hasAny(['debitnote_number', 'debitnote_quote', 'debitnote_tax', 'customer_id', 'date_start', 'date_end', 'status']))
                        <span class="badge bg-info">พบ {{ $debitNote->total() }} รายการ</span>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('debit-note.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">เลขที่ใบเพิ่มหนี้</label>
                                <input type="text" name="debitnote_number" class="form-control" placeholder="DBNXX" value="{{ request('debitnote_number') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="">เลขที่ใบเสนอราคา</label>
                                <input type="text" name="debitnote_quote" class="form-control" placeholder="QTXX" value="{{ request('debitnote_quote') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="">เลขที่ใบกำกับภาษี</label>
                                <input type="text" name="debitnote_tax" class="form-control" placeholder="RVXX" value="{{ request('debitnote_tax') }}">
                            </div>
                             <div class="col-md-3">
                                <label for="">ชื่อลูกค้า</label>
                                <select name="customer_id" class="form-select select2" style="width: 100%">
                                    <option value="">ไม่เลือก</option>
                                    @forelse ($customers as $item)
                                        <option value="{{$item->customer_id}}" {{ request('customer_id') == $item->customer_id ? 'selected' : '' }}>{{$item->customer_name}}</option>
                                    @empty
                                        
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="">วันที่เริ่มต้น</label>
                                <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="">วันที่สิ้นสุด</label>
                                <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="">สถานะ</label>
                                <select name="status" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                    <option value="Y" {{ request('status') == 'Y' ? 'selected' : '' }}>ใช้งาน</option>
                                    <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>ยกเลิก</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-info me-2">ค้นหา</button>
                                <a href="{{ route('debit-note.index') }}" class="btn btn-secondary">รีเซ็ต</a>
                            </div>
                        </div>
                    </form>
                </div>
               </div>
              
            </div>
        </div>
        <div class="todo-listing ">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{route('debit-note.create')}}" class="btn btn-info">สร้างใบเพิ่มหนี้</a>
                @if($debitNote->count() > 0)
                    <div class="text-muted">
                        แสดงรายการที่ {{ $debitNote->firstItem() }}-{{ $debitNote->lastItem() }} จากทั้งหมด {{ $debitNote->total() }} รายการ
                    </div>
                @endif
            </div>
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
                        @forelse ($debitNote as $key => $item)
                            <tr>
                                <td>{{ $debitNote->firstItem() + $key }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->debitnote_date)) }}</td>
                                <td>
                                    {{ $item->debitnote_number }}
                                    @if(isset($item->debitnote_status) && $item->debitnote_status == 'cancel')
                                        <span class="badge bg-danger ms-1">ยกเลิก</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->quote)
                                        <a href="{{ route('quote.editNew', $item->quote->quote_id) }}" target="_blank" class="text-decoration-none">
                                            {{ $item->quote->quote_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->taxinvoice)
                                        <a href="{{ route('mpdf.taxreceipt', $item->invoice->invoice_id) }}" target="_blank" class="text-decoration-none">
                                            {{ $item->taxinvoice->taxinvoice_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->quote && $item->quote->customer)
                                        {{ $item->quote->customer->customer_name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($item->debitnote_total_old, 2) }}</td>
                                <td class="text-end text-{{ $item->debitnote_difference >= 0 ? 'success' : 'danger' }}">
                                    {{ number_format($item->debitnote_difference, 2) }}
                                </td>
                                <td class="text-end">{{ number_format($item->debitnote_total_new, 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($item->debitnote_grand_total, 2) }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <div class="btn-group btn-group-sm" role="group">
                                            
                                            <button id="btnGroupDrop1" type="button" class="btn btn-light-success text-secondary font-weight-medium dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>

                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a href="{{route('debit-note.edit',$item->debitnote_id)}}" class=" dropdown-item text-info"> <i
                                                        class="fa fa-edit"></i> แก้ไข</a>
                                                <a class="dropdown-item" href="{{route('MPDF.debit-note.generatePDF',$item->debitnote_id)}}" target="_blink"><i
                                                        class="fa fa-print text-danger"></i> พิมพ์</a>
                                                <a class="dropdown-item mail-debitnote" href="{{route('mail.debitNoteModel.formMail',$item->debitnote_id)}}"><i
                                                        class="fas fa-envelope text-info"></i> ส่งเมล</a>
                                                <a class="dropdown-item" href="{{route('debit-note.copy',$item->debitnote_id)}}" target="_blink" ><i class="fas fa-share-square text-info"></i> สร้างซ้ำ</a>
                                                <a  onclick="return confirm('คุณต้องการลบ ใบเพิ่มหนี้ ใช่ หรือ ไม่')" class="dropdown-item" href="{{route('debit-note.delete',$item->debitnote_id)}}"><i class="fas fa-trash text-danger"></i> ลบ</a>

                                            </div>
                                        </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="fas fa-search mb-2"></i><br>
                                    ไม่พบข้อมูลใบเพิ่มหนี้ตามเงื่อนไขที่ระบุ
                                </td>
                            </tr>
                        @endforelse

                        @if($debitNote->count() > 0)
                        <tr class="table-info">
                            <td colspan="9" class="text-end fw-bold">จำนวนเงินทั้งหมด :</td>
                            <td class="text-end fw-bold text-danger">
                                {{ number_format($debitNote->sum('debitnote_grand_total'), 2) }} บาท
                            </td>
                            <td></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                {!! $debitNote->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>

    </div>

    
{{-- mail form quote --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="modal-mail-debitnote" tabindex="-1" role="dialog"
aria-labelledby="mySmallModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        ...
    </div>
</div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: "เลือกลูกค้า",
            allowClear: true,
            width: '100%'
        });

        // Auto submit form when date is selected
        $('input[name="date_start"], input[name="date_end"]').on('change', function() {
            if ($('input[name="date_start"]').val() && $('input[name="date_end"]').val()) {
                $(this).closest('form').submit();
            }
        });

        // Modal for mail debit note
        $(".mail-debitnote").click("click", function(e) {
            e.preventDefault();
            $("#modal-mail-debitnote")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });

        // Add loading state to search button
        $('form').on('submit', function() {
            $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> กำลังค้นหา...').prop('disabled', true);
        });

        // Clear individual filter
        $('.clear-filter').on('click', function() {
            const target = $(this).data('target');
            $(target).val('').trigger('change');
        });

        // Confirm delete with better styling
        $('a[href*="delete"]').on('click', function(e) {
            if (!confirm('คุณต้องการลบใบเพิ่มหนี้นี้ใช่หรือไม่?\nการดำเนินการนี้ไม่สามารถยกเลิกได้')) {
                e.preventDefault();
            }
        });
    });
</script>


@endsection
