<?php $__env->startSection('content'); ?>


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

       


        <div class="card">
            <div class="card-body">
                <h4 class="card-title">ใบเสนอราคา/ใบแจ้งหนี้
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-quote')): ?>
                     <a href="<?php echo e(route('quote.createNew')); ?>"
                        class="btn btn-primary float-end">สร้างใบเสนอราคา</a></h4>
                        <?php endif; ?>
                <hr>

                <form action="">
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
                            </select>
                        </div>

                        </div>
                        <div class="row ">
                        
                            <div class="input-group-append">
                                <button class="btn btn-outline-success float-end mx-3" type="submit">ค้นหา</button>
                                <a href="<?php echo e(route('quote.index')); ?>" class="btn btn-outline-danger float-end mx-3"
                                    type="submit">ล้างข้อมูล</a>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        
           
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        

                        <form action="<?php echo e(route('export.quote')); ?>" id="export-excel" method="post">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('POST'); ?>
                            <input type="hidden" name="quote_ids" value="<?php echo e($quotations->pluck('quote_id')); ?>">
                            <button class="btn btn-success" type="submit">EXCEL</button>
                        </form>
                        <br>
                        <table class="table customize-table table-hover mb-0 v-middle table-striped table-bordered" id="quote-table"
                            style="font-size: 12px">
                            <thead class="table text-white bg-info">
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
                                        <th>CheckLists</th>
                                        <th>ผู้ขาย</th>
                                        <th>การจัดการ</th>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $quotations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td><?php echo e($item->quote_number); ?> <?php echo $item->debitNote ? '<span class="badge rounded-pill bg-success">DBN</span>' : ''; ?> <?php echo $item->creditNote ? '<span class="badge rounded-pill bg-danger">CDN</span>' : ''; ?> </td>
                                        <td><?php echo e($item->quote_booking); ?></td>
                                        <td><span data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="<?php echo e($item->quote_tour_name ? $item->quote_tour_name : $item->quote_tour_name1); ?>"><?php echo e($item->quote_tour_name ? mb_substr($item->quote_tour_name, 0, 20) . '...' : mb_substr($item->quote_tour_name1, 0, 20) . '...'); ?></span>
                                    </td>
                                        <td><?php echo e(date('d/m/Y', strtotime($item->created_at))); ?></td>
                                        <td><?php echo e(date('d/m/Y', strtotime($item->quote_date_start)) . '-' . date('d/m/Y', strtotime($item->quote_date_end))); ?>

                                        </td>
                                        <td><?php echo e($item->quotecustomer->customer_name); ?></td>
                                        <td><?php echo e($item->quote_pax_total); ?></td>
                                        <td><?php echo e($item->airline->code); ?></td>
                                        <td>
                                            <?php echo e($item->quoteCountry->country_name_th); ?>

                                        </td>
                                        <td><?php echo e($item->quoteWholesale->code); ?></td>
                                        
                                        <td>
                                            <?php echo getQuoteStatusPayment($item); ?>

                                        </td>
                                        

                                        <td><?php echo e(number_format($item->quote_grand_total, 2, '.', ',')); ?></td>

                                        <td>
                                            <?php
                                                // ดึงข้อมูลการชำระเงินล่าสุดจาก paymentWholesale
                                                $latestPayment = $item->paymentWholesale()->latest('payment_wholesale_id')->first();
                                            ?>
                                          <?php if(!$latestPayment || $latestPayment->payment_wholesale_type === null): ?>
                                                <!-- กรณีที่ไม่มีข้อมูลใน paymentWholesale หรือ payment_wholesale_type เป็น NULL -->
                                                <span class="badge rounded-pill bg-primary">รอชำระเงิน</span>
                                            <?php elseif($latestPayment->payment_wholesale_type === 'deposit'): ?>
                                                <!-- กรณีที่เป็น deposit -->
                                                <span class="badge rounded-pill bg-primary">รอชำระเงินเต็มจำนวน</span>
                                            <?php elseif($latestPayment->payment_wholesale_type === 'full'): ?>
                                                <!-- กรณีที่เป็น full -->
                                                <span class="badge rounded-pill bg-success">ชำระเงินแล้ว</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            
                                           <?php echo getQuoteStatusQuotePayment($item); ?>

                                           <?php echo getStatusPaymentWhosale($item); ?>

                                           
                                            
                                            
                                        </td>

                                        <td> <?php echo e($item->Salename->name); ?></td>
                                        <td><a href="<?php echo e(route('quote.editNew', $item->quote_id)); ?>"
                                                class="btn btn-info btn-sm">จัดการข้อมูล</a>
                                                
                                            </td>
                                    </tr>
                                    
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    No data
                                <?php endif; ?>
                                <tr>
                                    <td class="text-success">ข้อมูลผลรวม</td>
                                    <td class="text-danger" colspan="12" align="right"> จำนวน <?php echo e(number_format($SumPax)); ?> (PAX) | จำนวนมูลค่าใบเสนอราคา <?php echo e(number_format($SumTotal,2)); ?> บาท </td>
                                </tr>

                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>


    <?php $__env->stopSection(); ?>
    
    
<?php echo $__env->make('layouts.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\accounting-nexttripholiday\resources\views/quotations/index.blade.php ENDPATH**/ ?>