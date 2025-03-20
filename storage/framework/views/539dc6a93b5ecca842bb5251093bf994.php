<?php $__env->startSection('content'); ?>
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

<?php

use Carbon\Carbon;

if (!function_exists('getQuoteStatusPaymentReport')) {
    function getQuoteStatusPaymentReport($quotationModel)
    {
        $now = Carbon::now();
        $status = '';
        // ตรวจสอบ payment_status ผ่านความสัมพันธ์ quotePayment
        if ($quotationModel->quotePayment && $quotationModel->quotePayment->payment_status === 'refund') {

            $status = 'รอคืนเงิน';
        } elseif ($quotationModel->quote_status === 'cancel') {
            $status = 'ยกเลิกการสั่งซื้อ';
    
        } elseif ($quotationModel->quote_status === 'success') {
            $status = 'ชำระเงินครบแล้ว';
        } elseif ($quotationModel->payment > 0) {
            $status = 'รอชำระเงินเต็มจำนวน';
        } elseif ($quotationModel->quote_payment_type === 'deposit') {
            if ($now->gt(Carbon::parse($quotationModel->quote_payment_date))) {
                $status = 'เกินกำหนดชำระเงิน';
            } else {
                $status = 'รอชำระเงินมัดจำ';
            }
        } elseif ($quotationModel->quote_payment_type === 'full') {
            if ($now->gt(Carbon::parse($quotationModel->quote_payment_date_full))) {
                $status = 'เกินกำหนดชำระเงิน';
            } else {
                $status = 'รอชำระเงินเต็มจำนวน';
            }
        } else {
            $status = 'รอชำระเงิน';
        }
        return $status;
    }
}

?>


    <div class="email-app todo-box-container container-fluid">

        <div class="card">
            <div class="card-header">
                <h3>Report quotations</h3>
            </div>
            <div class="card-body">

                <form action="<?php echo e(route('report.quote.form')); ?>">
                    <input type="hidden" name="search" value="Y">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>คีย์เวิร์ด</label>
                            <input type="text" class="form-control" name="search_keyword"
                                value="<?php echo e($request->search_keyword); ?>" placeholder="คียร์เวิร์ด" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="ชื่อแพคเกจทัวร์,เลขที่ใบเสนอราคา,เลขที่ใบแจ้งหนี้,ชื่อลูกค้า,เลขที่ใบจองทัวร์,ใบกำกับภาษีของโฮลเซลล์,เลขที่ใบหัก ณ ที่จ่ายของลูกค้า">
                        </div>
                        <div class="col-md-2">
                            <label>Booking Date </label>
                            <input type="date" class="form-control" value="<?php echo e($request->search_booking_start); ?>"
                                name="search_booking_start">
                        </div>
                        <div class="col-md-2">
                            <label>ถึงวันที่ </label>
                            <input type="date" class="form-control" value="<?php echo e($request->search_booking_end); ?>"
                                name="search_booking_end">
                        </div>
                        <div class="col-md-2">
                            <label>ช่วงวันเดินทาง</label>
                            <input type="date" class="form-control" value="<?php echo e($request->search_period_start); ?>"
                                name="search_period_start">
                        </div>
                        <div class="col-md-2 ">
                            <label>ถึงวันที่</label>
                            <input type="date" class="form-control" value="<?php echo e($request->search_period_end); ?>"
                                name="search_period_end">
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>ประเทศ</label>
                            <select name="search_country" id="country" class="form-select select2" style="width: 100%">
                                <option value="all">ทั้งหมด</option>
                                <?php $__empty_1 = true; $__currentLoopData = $country; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option <?php echo e(request('search_country') == $item->id ? 'selected' : ''); ?>

                                        value="<?php echo e($item->id); ?>"><?php echo e($item->country_name_th); ?></option>
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
                                    <option <?php echo e(request('search_wholesale') == $item->id ? 'selected' : ''); ?>

                                        value="<?php echo e($item->id); ?>">
                                        <?php echo e($item->wholesale_name_th); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <option value="" disabled>ไม่มีข้อมูล</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>สถานะชำระโฮลเซลล์</label>
                            <select name="search_wholesale_payment" class="form-select">
                                <option value="all"
                                    <?php echo e(request('search_wholesale_payment') === 'all' ? 'selected' : ''); ?>>ทั้งหมด</option>
                                <option value="NULL"
                                    <?php echo e(request('search_wholesale_payment') === 'NULL' ? 'selected' : ''); ?>>รอชำระเงิน
                                </option>
                                <option value="deposit"
                                    <?php echo e(request('search_wholesale_payment') == 'deposit' ? 'selected' : ''); ?>>
                                    รอชำระเงินเต็มจำนวน</option>
                                <option value="full"
                                    <?php echo e(request('search_wholesale_payment') == 'full' ? 'selected' : ''); ?>>ชำระเงินครบแล้ว
                                </option>
                                <option value="wait-payment-wholesale"
                                    <?php echo e(request('search_wholesale_payment') == 'wait-payment-wholesale' ? 'selected' : ''); ?>>
                                    รอโฮลเซลล์คืนเงิน</option>
                            </select>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>สถานะการชำระของลูกค้า</label>
                            <select name="search_customer_payment" class="form-select" style="width: 100%">
                                <option <?php echo e(request('search_customer_payment') === 'all' ? 'selected' : ''); ?>

                                    value="all">ทั้งหมด</option>
                                <option <?php echo e(request('search_customer_payment') === 'รอคืนเงิน' ? 'selected' : ''); ?>

                                    value="รอคืนเงิน">รอคืนเงิน</option>
                                <option <?php echo e(request('search_customer_payment') === 'รอชำระเงินมัดจำ' ? 'selected' : ''); ?>

                                    value="รอชำระเงินมัดจำ">รอชำระเงินมัดจำ</option>
                                <option
                                    <?php echo e(request('search_customer_payment') === 'รอชำระเงินเต็มจำนวน' ? 'selected' : ''); ?>

                                    value="รอชำระเงินเต็มจำนวน">รอชำระเงินเต็มจำนวน</option>
                                <option <?php echo e(request('search_customer_payment') === 'ชำระเงินครบแล้ว' ? 'selected' : ''); ?>

                                    value="ชำระเงินครบแล้ว">ชำระเงินครบแล้ว</option>
                                <option <?php echo e(request('search_customer_payment') === 'เกินกำหนดชำระเงิน' ? 'selected' : ''); ?>

                                    value="เกินกำหนดชำระเงิน">เกินกำหนดชำระเงิน</option>
                                <option <?php echo e(request('search_customer_payment') === 'ยกเลิกการสั่งซื้อ' ? 'selected' : ''); ?>

                                    value="ยกเลิกการสั่งซื้อ">ยกเลิกการสั่งซื้อ</option>

                            </select>
                        </div>



                        <div class="col-md-2">
                            <label>เซลล์ผู้ขาย</label>
                            <select name="search_sale" class="form-select">
                                <option value="all">ทั้งหมด</option>
                                <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option <?php echo e(request('search_sale') == $item->id ? 'selected' : ''); ?>

                                        value="<?php echo e($item->id); ?>">
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
                            <select name="search_airline" class="form-select select2" style="width: 100%">
                                <option value="all">ทั้งหมด</option>
                                <?php $__empty_1 = true; $__currentLoopData = $airlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $airline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option <?php echo e(request('search_airline') == $airline->id ? 'selected' : ''); ?>

                                        value="<?php echo e($airline->id); ?>"><?php echo e($airline->travel_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        

                        <div class="col-md-2">
                            <label for="">Check List</label>
                            <select name="Search_check_list" class="form-select" style="width: 100%">
                                <option <?php echo e(request('Search_check_list') === 'all' ? 'selected' : ''); ?> value="all">
                                    ทั้งหมด</option>
                                <option <?php echo e(request('Search_check_list') === 'booking_email_status' ? 'selected' : ''); ?>

                                    value="booking_email_status">ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์</option>
                                <option <?php echo e(request('Search_check_list') === 'invoice_status' ? 'selected' : ''); ?>

                                    value="invoice_status">อินวอยโฮลเซลล์</option>
                                <option <?php echo e(request('Search_check_list') === 'slip_status' ? 'selected' : ''); ?>

                                    value="slip_status">ส่งสลิปให้โฮลเซลล์</option>
                                <option <?php echo e(request('Search_check_list') === 'passport_status' ? 'selected' : ''); ?>

                                    value="passport_status">ส่งพาสปอตให้โฮลเซลล์</option>
                                <option <?php echo e(request('Search_check_list') === 'appointment_status' ? 'selected' : ''); ?>

                                    value="appointment_status">ส่งใบนัดหมายให้ลูกค้า</option>
                                <option <?php echo e(request('Search_check_list') === 'withholding_tax_status' ? 'selected' : ''); ?>

                                    value="withholding_tax_status">ออกใบหัก ณ ที่จ่าย</option>
                                <option <?php echo e(request('Search_check_list') === 'wholesale_tax_status' ? 'selected' : ''); ?>

                                    value="wholesale_tax_status">ใบกำกับภาษีโฮลเซลล์</option>
                            </select>
                        </div>
                    </div>
                    <div class="row ">

                        <div class="input-group-append">
                            <button class="btn btn-outline-success float-end mx-3" type="submit">ค้นหา</button>
                            <a href="<?php echo e(route('report.quote.form')); ?>" class="btn btn-outline-danger float-end mx-3"
                                type="submit">ล้างข้อมูล</a>

                        </div>
                    </div>
            </div>
            </form>
        </div>


        <div class="card">
            <div class="card-header">
                <h3>Report quotations</h3>
            </div>
            <div class="card-body">

                <table class="table table quote-table " style="font-size: 12px; width: 100%">
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
                        <?php $__empty_1 = true; $__currentLoopData = $quotations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($key + 1); ?></td>
                                <td><?php echo e($item->quote_number); ?></td>
                                <td><?php echo e($item->quote_tour ? $item->quote_tour : $item->quote_tour_code); ?></td>
                                <td><span data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="<?php echo e($item->quote_tour_name ? $item->quote_tour_name : $item->quote_tour_name1); ?>"><?php echo e($item->quote_tour_name ? mb_substr($item->quote_tour_name, 0, 20) . '...' : mb_substr($item->quote_tour_name1, 0, 20) . '...'); ?></span>
                                </td>
                                <td><?php echo e(date('d/m/Y', strtotime($item->quote_date_start)).'-'.date('d/m/Y', strtotime($item->quote_date_end))); ?></td>
                                <td><?php echo e($item->quote_booking); ?></td>
                                <td><?php echo e($item->customer->customer_name); ?></td>
                                <td><?php echo e($item->quote_pax_total); ?></td>
                                <td><?php echo e($item->quoteCountry->country_name_th); ?></td>
                                <td><?php echo e($item->airline->code); ?></td>
                                <td><?php echo e($item->quoteWholesale->code); ?></td>
                                <td><?php echo getQuoteStatusPaymentReport($item); ?></td>
                                <td><?php echo e(number_format($item->quote_grand_total, 2)); ?></td>
                                <td>
                                    <?php
                                        $latestPayment = $item
                                            ->paymentWholesale()
                                            ->latest('payment_wholesale_id')
                                            ->first();
                                        if (!$latestPayment || $latestPayment->payment_wholesale_type === null) {
                                            $output = 'รอชำระเงิน';
                                        } elseif ($latestPayment->payment_wholesale_type === 'deposit') {
                                            $output = 'รอชำระเงินเต็มจำนวน';
                                        } elseif ($latestPayment->payment_wholesale_type === 'full') {
                                            $output = 'ชำระเงินแล้ว';
                                        }
                                        echo $output;
                                    ?>
                                <td><?php echo e($item->Salename->name); ?></td>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="12" style="text-align:right">Total:</th>
                            <th></th>
                            <th></th>
                            <th></th>
    
                       
                        </tr>
                    </tfoot>
                </table>
          
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('fonts/vfs_fonts.js')); ?>"></script>

    <script>
        $(function() {
            var table = $('.quote-table').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel', 'csv', {
                    extend: 'pdf',
                   
                    customize: function(doc) {
                       
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
                        doc.defaultStyle = {
                            font: 'THSarabun',
                            fontSize: 9
                        };
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

                        // เพิ่ม footer ลงใน doc.content
                        var footer = table.column(12).footer().innerHTML;
                        doc.content[1].table.body.push([{
                                text: 'Total:',
                                colSpan: 12,
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
                    }
                }],
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
                    $(api.column(12).footer()).html(
                        new Intl.NumberFormat('th-TH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }).format(total)
                    );
                },
                
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/accounting-nexttripholiday/resources/views/reports/quote-form.blade.php ENDPATH**/ ?>