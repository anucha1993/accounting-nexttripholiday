<?php $__env->startSection('content'); ?>
<style>
    .table-sm td, .table-sm th {
        padding: 0.4rem;
        vertical-align: middle;
    }
    
    .badge-sm {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
    
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .table-dark th {
        border-color: #495057;
        font-size: 11px;
        font-weight: 600;
    }
    
    .text-truncate-custom {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 150px;
    }
    
    .status-badges .badge {
        margin: 1px;
        display: inline-block;
    }
    
    .quote-summary {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        border-radius: 8px;
        padding: 0.5rem;
    }
</style>

<div class="email-app todo-box-container container-fluid">
        <br>
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Success - </strong><?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Error - </strong><?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

       


        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice"></i> ใบเสนอราคา/ใบแจ้งหนี้
                    </h5>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-quote')): ?>
                        <a href="<?php echo e(route('quote.createNew')); ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> สร้างใบเสนอราคา
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="collapse" id="searchCollapse">
                    <form action="" class="border rounded p-3 bg-light mb-3">
                    <input type="hidden" name="search" value="Y">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>คีย์เวิร์ด</label>
                            <input type="hidden" name="check" value="Y">
                            <input type="text" class="form-control" name="search_keyword" value="<?php echo e($request->search_keyword); ?>" placeholder="คียร์เวิร์ด" data-bs-toggle="tooltip" data-bs-placement="top" title="ชื่อแพคเกจทัวร์,เลขที่ใบเสนอราคา,เลขที่ใบแจ้งหนี้,ชื่อลูกค้า,เลขที่ใบจองทัวร์,ใบกำกับภาษีของโฮลเซลล์,เลขที่ใบหัก ณ ที่จ่ายของลูกค้า"> 
                        </div>
                        <div class="col-md-2">
                            <label>Booking Date </label>
                            <input type="date" class="form-control" value="<?php echo e($request->search_booking_start); ?>" name="search_booking_start" >
                        </div>
                        <div class="col-md-2">
                            <label>ถึงวันที่ </label>
                            <input type="date" class="form-control" value="<?php echo e($request->search_booking_end); ?>" name="search_booking_end" >
                        </div>
                        <div class="col-md-2">
                            <label>ช่วงวันเดินทาง</label>
                            <input type="date" class="form-control" value="<?php echo e($request->search_period_start); ?>" name="search_period_start" >
                        </div>
                        <div class="col-md-2 ">
                            <label>ถึงวันที่</label>
                            <input type="date" class="form-control" value="<?php echo e($request->search_period_end); ?>" name="search_period_end" >
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>ประเทศ</label>
                            <select name="search_country" id="country" class="form-select select2" style="width: 100%">
                                <option value="all">ทั้งหมด</option>
                                <?php $__empty_1 = true; $__currentLoopData = $country; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option <?php echo e(request('search_country') == $item->id ? 'selected' : ''); ?> value="<?php echo e($item->id); ?>"><?php echo e($item->country_name_th); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="" disabled>ไม่มีข้อมูล</option>
                            <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>โฮลเซลล์:</label>
                            <select name="search_wholesale" class="form-select select2" style="width: 100%">
                                <option value="all">ทั้งหมด</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $wholesales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option  <?php echo e(request('search_wholesale') == $item->id ? 'selected' : ''); ?> value="<?php echo e($item->id); ?>">
                                            <?php echo e($item->wholesale_name_th); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <option value="" disabled>ไม่มีข้อมูล</option>
                                    <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>สถานะชำระโฮลเซลล์</label>
                            <select name="search_wholesale_payment" class="form-select">
                                <option value="all" <?php echo e(request('search_wholesale_payment') === 'all' ? 'selected' : ''); ?>>ทั้งหมด</option>
                                <option value="NULL" <?php echo e(request('search_wholesale_payment') === 'NULL' ? 'selected' : ''); ?>>รอชำระเงิน</option>
                                <option value="deposit" <?php echo e(request('search_wholesale_payment') == 'deposit' ? 'selected' : ''); ?>>รอชำระเงินเต็มจำนวน</option>
                                <option value="full" <?php echo e(request('search_wholesale_payment') == 'full' ? 'selected' : ''); ?>>ชำระเงินครบแล้ว</option>
                                <option value="wait-payment-wholesale" <?php echo e(request('search_wholesale_payment') == 'wait-payment-wholesale' ? 'selected' : ''); ?>>รอโฮลเซลล์คืนเงิน</option>
                            </select>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>สถานะการชำระของลูกค้า</label>
                            <select name="search_customer_payment" class="form-select" style="width: 100%">
                                <option <?php echo e(request('search_customer_payment') === 'all' ? 'selected' : ''); ?> value="all">ทั้งหมด</option>
                                <option <?php echo e(request('search_customer_payment') === 'รอคืนเงิน' ? 'selected' : ''); ?> value="รอคืนเงิน">รอคืนเงิน</option>
                                <option <?php echo e(request('search_customer_payment') === 'รอชำระเงินมัดจำ' ? 'selected' : ''); ?> value="รอชำระเงินมัดจำ">รอชำระเงินมัดจำ</option>
                                <option <?php echo e(request('search_customer_payment') === 'รอชำระเงินเต็มจำนวน' ? 'selected' : ''); ?> value="รอชำระเงินเต็มจำนวน">รอชำระเงินเต็มจำนวน</option>
                                <option <?php echo e(request('search_customer_payment') === 'ชำระเงินครบแล้ว' ? 'selected' : ''); ?> value="ชำระเงินครบแล้ว">ชำระเงินครบแล้ว</option>
                                <option <?php echo e(request('search_customer_payment') === 'เกินกำหนดชำระเงิน' ? 'selected' : ''); ?> value="เกินกำหนดชำระเงิน">เกินกำหนดชำระเงิน</option>
                                <option <?php echo e(request('search_customer_payment') === 'ยกเลิกการสั่งซื้อ' ? 'selected' : ''); ?> value="ยกเลิกการสั่งซื้อ">ยกเลิกการสั่งซื้อ</option>

                            </select>
                        </div>

                       
                        
                        <div class="col-md-2">
                            <label>เซลล์ผู้ขาย</label>
                            <select name="search_sale" class="form-select">
                                <option value="all">ทั้งหมด</option>
                                <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option  <?php echo e(request('search_sale') == $item->id  ? 'selected' : ''); ?> value="<?php echo e($item->id); ?>">
                                        <?php echo e($item->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <option value="" disabled>ไม่มีข้อมูล</option>
                                <?php endif; ?>
                            </select>

                        </div>
                    </div>
                        <div class="row mt-3">
                            
                            <div class="col-md-2">
                                <label for="">AIRLINE</label>
                               <select name="search_airline" class="form-select select2" style="width: 100%" >
                                <option value="all">ทั้งหมด</option>
                                <?php $__empty_1 = true; $__currentLoopData = $airlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option  <?php echo e(request('search_airline') == $airline->id  ? 'selected' : ''); ?> value="<?php echo e($airline->id); ?>"><?php echo e($airline->travel_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    
                                <?php endif; ?>
                               </select>
                            </div>
                            

                        

                        <div class="col-md-2">
                            <label for="">ยังไม่ได้ทำ Check List</label>
                            <select name="search_not_check_list" class="form-select" style="width: 100%">
                                <option <?php echo e(request('search_not_check_list') === 'all' ? 'selected' : ''); ?> value="all">ทั้งหมด</option>
                                <option <?php echo e(request('search_not_check_list') === 'booking_email_status' ? 'selected' : ''); ?> value="booking_email_status">ยังไม่ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์</option>
                                <option <?php echo e(request('search_not_check_list') === 'invoice_status' ? 'selected' : ''); ?> value="invoice_status">ยังไม่ได้อินวอยโฮลเซลล์</option>
                                <option <?php echo e(request('search_not_check_list') === 'slip_status' ? 'selected' : ''); ?> value="slip_status">ยังไม่ส่งสลิปให้โฮลเซลล์</option>
                                <option <?php echo e(request('search_not_check_list') === 'passport_status' ? 'selected' : ''); ?> value="passport_status">ยังไม่ส่งพาสปอตให้โฮลเซลล์</option>
                                <option <?php echo e(request('search_not_check_list') === 'appointment_status' ? 'selected' : ''); ?> value="appointment_status">ยังไม่ส่งใบนัดหมายให้ลูกค้า</option>
                                <option <?php echo e(request('search_not_check_list') === 'withholding_tax_status' ? 'selected' : ''); ?> value="withholding_tax_status">ยังไม่ออกใบหัก ณ ที่จ่าย</option>
                                <option <?php echo e(request('search_not_check_list') === 'wholesale_tax_status' ? 'selected' : ''); ?> value="wholesale_tax_status">ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์</option>
                                <option <?php echo e(request('search_not_check_list') === 'customer_refund_status' ? 'selected' : ''); ?> value="customer_refund_status">ยังไม่คืนเงินลูกค้า</option>
                                <option <?php echo e(request('search_not_check_list') === 'wholesale_refund_status' ? 'selected' : ''); ?> value="wholesale_refund_status">ยังไม่ได้รับเงินคืนจากโฮลเซลล์</option>
                            </select>
                        </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-primary btn-sm" type="submit">
                                        <i class="fas fa-search"></i> ค้นหา
                                    </button>
                                    <a href="<?php echo e(route('quote.index')); ?>" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times"></i> ล้างข้อมูล
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="mb-3">
                    <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#searchCollapse" aria-expanded="false">
                        <i class="fas fa-filter"></i> แสดง/ซ่อน ตัวกรอง
                    </button>
                </div>
            </div>
        </div>

        
           
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <form action="<?php echo e(route('export.quote')); ?>" id="export-excel" method="post" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('POST'); ?>
                                    <input type="hidden" name="quote_ids" value="<?php echo e($quotations->pluck('quote_id')); ?>">
                                    <button class="btn btn-success btn-sm" type="submit">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                </form>
                            </div>
                            <div class="text-muted">
                                <small>พบข้อมูล <?php echo e(number_format($quotations->count())); ?> รายการ | รวม <?php echo e(number_format($SumPax)); ?> PAX | มูลค่า <?php echo e(number_format($SumTotal,2)); ?> บาท</small>
                            </div>
                        </div>
                        
                        <table class="table table-sm table-hover table-striped table-bordered" id="quote-table" style="font-size: 11px;">
                            <thead class="table-dark sticky-top">
                                    <tr>
                                        <th style="width: 40px;" class="text-center">#</th>
                                        <th style="width: 120px;">ใบเสนอราคา</th>
                                        <th style="width: 100px;">เลขจองทัวร์</th>
                                        <th style="width: 200px;">โปรแกรมทัวร์</th>
                                        <th style="width: 80px;">วันที่จอง</th>
                                        <th style="width: 120px;">วันเดินทาง</th>
                                        <th style="width: 150px;">ลูกค้า</th>
                                        <th style="width: 50px;" class="text-center">PAX</th>
                                        <th style="width: 80px;">ประเทศ</th>
                                        <th style="width: 60px;">สายการบิน</th>
                                        <th style="width: 80px;">โฮลเซลล์</th>
                                        <th style="width: 120px;">สถานะลูกค้า</th>
                                        <th style="width: 100px;" class="text-end">ยอดเงิน</th>
                                        <th style="width: 120px;">สถานะโฮลเซลล์</th>
                                        <th style="width: 100px;">CheckList</th>
                                        <th style="width: 80px;">ผู้ขาย</th>
                                        <th style="width: 80px;" class="text-center">จัดการ</th>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $quotations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="align-middle">
                                        <td class="text-center fw-bold"><?php echo e($key + 1); ?></td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-primary"><?php echo e($item->quote_number); ?></span>
                                                <div>
                                                    <?php if($item->debitNote): ?>
                                                        <span class="badge bg-success badge-sm">DBN</span>
                                                    <?php endif; ?>
                                                    <?php if($item->creditNote): ?>
                                                        <span class="badge bg-danger badge-sm">CDN</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark"><?php echo e($item->quote_booking); ?></span>
                                        </td>
                                        <td>
                                            <div data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="<?php echo e($item->quote_tour_name ?: $item->quote_tour_name1); ?>">
                                                <?php echo e(mb_substr($item->quote_tour_name ?: $item->quote_tour_name1, 0, 25)); ?><?php echo e(strlen($item->quote_tour_name ?: $item->quote_tour_name1) > 25 ? '...' : ''); ?>

                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted"><?php echo e(date('d/m/y', strtotime($item->created_at))); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column">
                                                <small><?php echo e(date('d/m/y', strtotime($item->quote_date_start))); ?></small>
                                                <small class="text-muted"><?php echo e(date('d/m/y', strtotime($item->quote_date_end))); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div data-bs-toggle="tooltip" title="<?php echo e($item->quotecustomer->customer_name); ?>">
                                                <?php echo e(mb_substr($item->quotecustomer->customer_name, 0, 20)); ?><?php echo e(strlen($item->quotecustomer->customer_name) > 20 ? '...' : ''); ?>

                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?php echo e($item->quote_pax_total); ?></span>
                                        </td>
                                        <td>
                                            <small><?php echo e($item->quoteCountry->country_name_th); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary badge-sm"><?php echo e($item->airline->code); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark badge-sm"><?php echo e($item->quoteWholesale->code); ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php echo getQuoteStatusPayment($item); ?>

                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-success"><?php echo e(number_format($item->quote_grand_total, 0)); ?></strong>
                                            <small class="text-muted d-block">บาท</small>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php echo getStatusPaymentWhosale($item); ?>

                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1" style="max-width: 100px;">
                                                <?php echo getQuoteStatusQuotePayment($item); ?>

                                                <?php echo getStatusWithholdingTax($item->quoteInvoice); ?>

                                                <?php echo getQuoteStatusWithholdingTax($item->quoteLogStatus); ?>

                                                <?php echo getStatusWhosaleInputTax($item->checkfileInputtax); ?>

                                                <?php echo getStatusCustomerRefund($item->quoteLogStatus); ?>

                                                <?php echo getStatusWholesaleRefund($item->quoteLogStatus); ?>

                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <small><?php echo e($item->Salename->name); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('quote.editNew', $item->quote_id)); ?>" 
                                               class="btn btn-primary btn-sm" 
                                               data-bs-toggle="tooltip" 
                                               title="จัดการข้อมูล">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="17" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-search fa-2x mb-3"></i>
                                                <p>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="7" class="text-end fw-bold">สรุปรวม:</td>
                                    <td class="text-center fw-bold text-primary"><?php echo e(number_format($SumPax)); ?></td>
                                    <td colspan="4"></td>
                                    <td class="text-end fw-bold text-success"><?php echo e(number_format($SumTotal,2)); ?></td>
                                    <td colspan="4" class="text-muted"><small>บาท</small></td>
                                </tr>
                            </tfoot>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>


<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'เลือก...',
        allowClear: true,
        width: '100%'
    });
    
    // Auto-submit form on select change for better UX
    $('select[name^="search_"]').on('change', function() {
        if ($(this).val() !== 'all' && $(this).val() !== '') {
            $(this).closest('form').submit();
        }
    });
    
    // Highlight search keywords
    var searchKeyword = $('input[name="search_keyword"]').val();
    if (searchKeyword) {
        $('#quote-table tbody').highlight(searchKeyword, {className: 'bg-warning'});
    }
    
    // Enhanced table row click
    $('#quote-table tbody tr').on('click', function(e) {
        if (!$(e.target).closest('a, button').length) {
            var editLink = $(this).find('a[href*="editNew"]').attr('href');
            if (editLink) {
                window.location.href = editLink;
            }
        }
    });
    
    // Add loading state to form submission
    $('form').on('submit', function() {
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> กำลังค้นหา...');
        submitBtn.prop('disabled', true);
        
        setTimeout(function() {
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        }, 3000);
    });
});

// jQuery highlight plugin (lightweight version)
jQuery.fn.highlight = function(pat, options) {
    var opts = jQuery.extend({}, jQuery.fn.highlight.defaults, options);
    return this.each(function() {
        var regex = new RegExp('(' + pat + ')', 'gi');
        $(this).html($(this).html().replace(regex, '<span class="' + opts.className + '">$1</span>'));
    });
};
jQuery.fn.highlight.defaults = {
    className: 'highlight'
};
</script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'เลือก...',
        allowClear: true,
        width: '100%'
    });
    
    // Auto-submit form on select change for better UX
    $('select[name^="search_"]').on('change', function() {
        if ($(this).val() !== 'all' && $(this).val() !== '') {
            $(this).closest('form').submit();
        }
    });
    
    // Highlight search keywords
    var searchKeyword = $('input[name="search_keyword"]').val();
    if (searchKeyword) {
        $('#quote-table tbody').highlight(searchKeyword, {className: 'bg-warning'});
    }
    
    // Enhanced table row click
    $('#quote-table tbody tr').on('click', function(e) {
        if (!$(e.target).closest('a, button').length) {
            var editLink = $(this).find('a[href*="editNew"]').attr('href');
            if (editLink) {
                window.location.href = editLink;
            }
        }
    });
    
    // Add loading state to form submission
    $('form').on('submit', function() {
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> กำลังค้นหา...');
        submitBtn.prop('disabled', true);
        
        setTimeout(function() {
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        }, 3000);
    });
});

// jQuery highlight plugin (lightweight version)
jQuery.fn.highlight = function(pat, options) {
    var opts = jQuery.extend({}, jQuery.fn.highlight.defaults, options);
    return this.each(function() {
        var regex = new RegExp('(' + pat + ')', 'gi');
        $(this).html($(this).html().replace(regex, '<span class="' + opts.className + '">$1</span>'));
    });
};
jQuery.fn.highlight.defaults = {
    className: 'highlight'
};
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\accounting-nexttripholiday\resources\views/quotations/index.blade.php ENDPATH**/ ?>