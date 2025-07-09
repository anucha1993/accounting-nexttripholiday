@extends('layouts.template')

@section('content')
<style>
    .form-group {
        margin-bottom: 1rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .btn-group .dropdown-toggle::after {
        margin-left: 0.5rem;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    
    .text-primary {
        color: #0d6efd !important;
    }
    
    .no-data-icon {
        opacity: 0.5;
    }
    
    /* Checkbox styling */
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .form-check-input:indeterminate {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    /* Table hover effects */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    /* Export button styling */
    .btn-group .dropdown-menu {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>

<br>
<div class="email-app todo-box-container container-fluid">


    <div class="card search-card">
        <div class="card-body">
            <h4 class="card-title mb-3">
                <i class="fas fa-search"></i> ค้นหา
            </h4>
            <script>
    function openPdfPopup(url) {
        var width = 950; // กำหนดความกว้างของหน้าต่าง
        var height = 800; // กำหนดความสูงของหน้าจอ
        var left = (window.innerWidth - width) / 2; // คำนวณตำแหน่งจากด้านซ้ายของหน้าจอ
        var top = (window.innerHeight - height) / 2; // คำนวณตำแหน่งจากด้านบนของหน้าจอ

        // เปิดหน้าต่างใหม่ด้วยการคำนวณตำแหน่งและขนาด
        window.open(url, 'PDFPopup', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
    }
</script>
            <form action="{{ route('withholding.index') }}" method="GET" id="search">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="document_number" class="form-label">เลขที่เอกสาร</label>
                            <input type="text" 
                                   id="document_number"
                                   name="document_number" 
                                   placeholder="ระบุเลขที่เอกสาร" 
                                   class="form-control" 
                                   value="{{ request('document_number') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ref_number" class="form-label">Ref.Number</label>
                            <input type="text" 
                                   id="ref_number"
                                   name="ref_number" 
                                   placeholder="ระบุเลขที่อ้างอิง" 
                                   class="form-control" 
                                   value="{{ request('ref_number') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="withholdingForm" class="form-label">แบบฟอร์ม</label>
                            <select id="withholdingForm" name="withholding_form" class="form-select">
                                <option value="">-- เลือกแบบฟอร์ม --</option>
                                <option value="ภ.ง.ด.53" {{ request('withholding_form') == 'ภ.ง.ด.53' ? 'selected' : '' }}>ภ.ง.ด.53</option>
                                <option value="ภ.ง.ด.3" {{ request('withholding_form') == 'ภ.ง.ด.3' ? 'selected' : '' }}>ภ.ง.ด.3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="customer" class="form-label">ชื่อผู้ถูกหัก</label>
                            <select name="customer" id="customer-select" class="form-select select2" style="width: 100%">
                                <option value="">-- เลือกผู้ถูกหัก --</option>
                                @forelse ($customerWithholding as $item)
                                    <option value="{{ $item->customer->customer_id ?? $item->wholesale_id }}" 
                                        {{ request('customer') == ($item->customer->customer_id ?? $item->wholesale_id) ? 'selected' : '' }}>
                                        {{ $item->customer->customer_name ?? $item->wholesale->wholesale_name_th }}
                                    </option>
                                @empty
                                    
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="document_date_start" class="form-label">วันที่ออกเอกสาร (เริ่มต้น)</label>
                            <input type="date" 
                                   id="document_date_start"
                                   name="document_date_start" 
                                   class="form-control" 
                                   value="{{ request('document_date_start') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="document_date_end" class="form-label">วันที่ออกเอกสาร (สิ้นสุด)</label>
                            <input type="date" 
                                   id="document_date_end"
                                   name="document_date_end" 
                                   class="form-control" 
                                   value="{{ request('document_date_end') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-search"></i> ค้นหา
                                </button>
                                <a href="{{ route('withholding.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh"></i> รีเซ็ต
                                </a>
                                @if(request()->hasAny(['document_number', 'ref_number', 'withholding_form', 'document_date_start', 'document_date_end', 'customer']))
                                    <span class="badge bg-info align-self-center ms-2">
                                        <i class="fas fa-info-circle"></i> พบ {{ number_format($documents->total()) }} รายการ
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card">

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="card-title mb-0">
                    <i class="fas fa-file-invoice"></i> รายการใบหัก ณ ที่จ่าย
                </h3>
                <div class="d-flex align-items-center gap-2">
                    @if($documents->total() > 0)
                        <span class="badge bg-info">
                            <i class="fas fa-list"></i> ทั้งหมด {{ number_format($documents->total()) }} รายการ
                        </span>
                    @endif
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" onclick="exportToExcel()" class="dropdown-item">
                                    <i class="fas fa-file-excel text-success"></i> Export Excel (ทั้งหมด)
                                </a>
                            </li>
                            <li>
                                <a href="#" onclick="exportSelectedToExcel()" class="dropdown-item">
                                    <i class="fas fa-file-excel text-success"></i> Export Excel (เลือก)
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('withholding.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มเอกสารใหม่
                    </a>
                </div>
            </div>
            @if($documents->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 40px;">
                                    <input type="checkbox" id="select-all" class="form-check-input" title="เลือกทั้งหมด">
                                </th>
                                <th class="text-center" style="width: 50px;">No.</th>
                                <th style="min-width: 120px;">เลขที่เอกสาร</th>
                                <th style="min-width: 120px;">Ref.Number</th>
                                <th class="text-center" style="width: 80px;">ภงด</th>
                                <th style="min-width: 120px;">Quote.Ref</th>
                                <th style="min-width: 150px;">ชื่อผู้จอง</th>
                                <th style="min-width: 150px;">ชื่อผู้ถูกหัก</th>
                                <th class="text-center" style="width: 120px;">วันที่ออกเอกสาร</th>
                                <th class="text-end" style="width: 120px;">ยอดชำระ</th>
                                <th class="text-end" style="width: 120px;">ยอดหัก</th>
                                <th class="text-center" style="width: 120px;">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $key => $document)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input document-checkbox" value="{{ $document->id }}" title="เลือกรายการนี้">
                                </td>
                                <td class="text-center">{{ $documents->firstItem() + $key }}</td>
                                <td>
                                    <strong class="text-primary">{{ $document->document_number }}</strong>
                                </td>
                                <td>
                                    @if($document->ref_number)
                                        <span class="badge bg-light text-dark">{{ $document->ref_number }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($document->withholding_form)
                                        <span class="badge bg-secondary">{{ $document->withholding_form }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($document->quote)
                                        <a href="{{route('quote.editNew', $document->quote->quote_id)}}" 
                                           target="_blank" 
                                           class="btn btn-link btn-sm p-0 text-decoration-none">
                                            {{ $document->quote->quote_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $document->customer->customer_name ?? '-' }}</td>
                                <td>
                                    @if(isset($document->quote) && isset($document->quote->wholesale))
                                        {{ $document->quote->wholesale->wholesale_name_th }}
                                    @elseif(isset($document->customer))
                                        {{ $document->customer->customer_name }}
                                    @elseif(isset($document->wholesale))
                                        {{ $document->wholesale->wholesale_name_th }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($document->document_doc_date)
                                        <span class="badge bg-light text-dark">
                                            {{ date('d/m/Y', strtotime($document->document_doc_date)) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="text-danger fw-bold">{{ number_format($document->total_payable, 2) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-success fw-bold">{{ number_format($document->total_withholding_tax, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{route('MPDF.generatePDFwithholding',$document->id)}}" 
                                                   onclick="openPdfPopup(this.href); return false;" 
                                                   class="dropdown-item">
                                                    <i class="fa fa-eye text-success"></i> ดู
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('withholding.edit', $document->id) }}" class="dropdown-item">
                                                    <i class="fa fa-edit text-info"></i> แก้ไข
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a href="{{route('MPDF.generatePDFwithholding',$document->id)}}" 
                                                   target="_blank" class="dropdown-item">
                                                    <i class="fa fa-print text-danger"></i> พิมพ์
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('MPDF.downloadPDFwithholding',$document->id)}}" class="dropdown-item">
                                                    <i class="fa fa-file-pdf text-danger"></i> ดาวน์โหลด
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('MPDF.printEnvelope',$document->id)}}" class="dropdown-item">
                                                    <i class="fas fa-envelope text-info"></i> พิมพ์หน้าซอง
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('withholding.editRepear',$document->id)}}" class="dropdown-item">
                                                    <i class="fas fa-copy text-info"></i> สร้างซ้ำ
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('withholding.destroy',$document->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fa fa-trash"></i> ลบ
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th></th>
                                <th colspan="8" class="text-end fw-bold">รวมทั้งหมด:</th>
                                <th class="text-end text-danger fw-bold">{{ number_format($documents->sum('total_payable'), 2) }}</th>
                                <th class="text-end text-success fw-bold">{{ number_format($documents->sum('total_withholding_tax'), 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                @if($documents->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $documents->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-4x text-muted mb-3 no-data-icon"></i>
                    <h5 class="text-muted">ไม่พบข้อมูลใบหัก ณ ที่จ่าย</h5>
                    <p class="text-muted">ลองปรับเปลี่ยนเงื่อนไขการค้นหา หรือเพิ่มเอกสารใหม่</p>
                    <a href="{{ route('withholding.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มเอกสารใหม่
                    </a>
                </div>
            @endif
</div>
</div>
</div>




<script>
$(document).ready(function() {
    // Initialize Select2 for customer dropdown
    if (typeof $.fn.select2 !== 'undefined') {
        $('#customer-select').select2({
            placeholder: '-- เลือกผู้ถูกหัก --',
            allowClear: true,
            width: '100%'
        });
    }
    
    // Select All Checkbox functionality
    $('#select-all').on('change', function() {
        $('.document-checkbox').prop('checked', $(this).is(':checked'));
        updateSelectedCount();
    });
    
    // Individual checkbox change
    $('.document-checkbox').on('change', function() {
        updateSelectedCount();
        
        // Update select all checkbox
        var totalCheckboxes = $('.document-checkbox').length;
        var checkedCheckboxes = $('.document-checkbox:checked').length;
        
        $('#select-all').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#select-all').prop('checked', checkedCheckboxes === totalCheckboxes);
    });
    
    // Auto-submit form when customer is selected
    $('#customer-select').on('change', function() {
        if ($(this).val()) {
            $('#search').submit();
        }
    });
    
    // Date range validation
    $('#document_date_start, #document_date_end').on('change', function() {
        var startDate = $('#document_date_start').val();
        var endDate = $('#document_date_end').val();
        
        if (startDate && endDate && startDate > endDate) {
            alert('วันที่เริ่มต้นต้องไม่มากกว่าวันที่สิ้นสุด');
            $(this).val('');
        }
    });
    
    // Auto-submit when date range is complete
    $('#document_date_end').on('change', function() {
        var startDate = $('#document_date_start').val();
        var endDate = $(this).val();
        
        if (startDate && endDate) {
            $('#search').submit();
        }
    });
    
    // Enhanced search on Enter key
    $('#document_number, #ref_number').on('keypress', function(e) {
        if (e.which == 13) { // Enter key
            $('#search').submit();
        }
    });
    
    // Show loading state on form submit
    $('#search').on('submit', function() {
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> กำลังค้นหา...');
        submitBtn.prop('disabled', true);
    });
});

function updateSelectedCount() {
    var checkedCount = $('.document-checkbox:checked').length;
    // You can add visual feedback here if needed
}

function exportToExcel() {
    // Get current search parameters
    var params = new URLSearchParams();
    
    // Add filter parameters
    if ($('#document_number').val()) params.append('document_number', $('#document_number').val());
    if ($('#ref_number').val()) params.append('ref_number', $('#ref_number').val());
    if ($('#withholdingForm').val()) params.append('withholding_form', $('#withholdingForm').val());
    if ($('#document_date_start').val()) params.append('document_date_start', $('#document_date_start').val());
    if ($('#document_date_end').val()) params.append('document_date_end', $('#document_date_end').val());
    if ($('#customer-select').val()) params.append('customer', $('#customer-select').val());
    
    // Redirect to export URL
    window.location.href = '{{ route("withholding.export.excel") }}?' + params.toString();
}

function exportSelectedToExcel() {
    var selectedIds = [];
    $('.document-checkbox:checked').each(function() {
        selectedIds.push($(this).val());
    });
    
    if (selectedIds.length === 0) {
        alert('กรุณาเลือกรายการที่ต้องการ Export');
        return;
    }
    
    // Get current search parameters
    var params = new URLSearchParams();
    params.append('selected_ids', selectedIds.join(','));
    
    // Redirect to export URL
    window.location.href = '{{ route("withholding.export.excel") }}?' + params.toString();
}

function openPdfPopup(url) {
    var width = 950;
    var height = 800;
    var left = (window.innerWidth - width) / 2;
    var top = (window.innerHeight - height) / 2;

    window.open(url, 'PDFPopup', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left + ',scrollbars=yes,resizable=yes');
}
</script>
@endsection
