<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info">
            <h5 class="mb-0 text-white"><i class="fas fa-dollar-sign"></i>
                รายการชำระเงินโฮลเซลล์ / Payment Wholesale 

                &nbsp; <a href="javascript:void(0)" class="text-white float-end" onclick="toggleAccordion('table-payment-wholesale', 'toggle-arrow-payment-wholesale')">
                    <span class="fas fa-chevron-down" id="toggle-arrow-payment-wholesale"></span>
                </a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table table-responsive" id="table-payment-wholesale" style="display: block">
                <table class="table product-overview">
                    <thead>
                        <tr class="custom-row-height" style="line-height: -500px;">
                            <th>ลำดับ</th>
                            <th>Payment No.</th>
                            <th>วันที่ทำรายการ</th>
                            <th>วันที่ชำระ</th>
                            <th>จำนวนเงิน</th>
                            <th>ยอดคืน</th>
                            <th>สถานะการคืน</th>
                            <th>ไฟล์แนบ</th>
                            <th>ประเภทการชำระเงิน</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <?php
                        $paymentTotal = 0;
                    ?>
                    <tbody>

                        <?php $__currentLoopData = $paymentWholesale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($key + 1); ?></td>

                                <td><?php echo e($item->payment_wholesale_number); ?></td>
                                <td><?php echo e(date('d/m/Y : H:m:s', strtotime($item->created_at))); ?></td>
                                <td>
                                    <?php if($item->payment_wholesale_date): ?>
                                    <?php echo e(date('d/m/Y ', strtotime($item->payment_wholesale_date))); ?>

                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo e(number_format($item->payment_wholesale_total, 2, '.', ',')); ?> 
                                </td>
                                <td>
                                    <?php if($item->payment_wholesale_refund_type !== NULL): ?>
                                    <?php echo '<span class="text-danger">'.number_format($item->payment_wholesale_refund_total,2).'</span>'; ?>

                                    <?php else: ?>
                                    
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($item->payment_wholesale_refund_total > 0): ?>

                                    <?php if($item->payment_wholesale_refund_status === 'success'): ?>
                                            <?php if($item->payment_wholesale_refund_type !== NULL && $item->payment_wholesale_refund_type === 'some'): ?>
                                            <span class="text-success">(คืนยอดบางส่วนแล้ว)</span>
                                            <?php elseif($item->payment_wholesale_refund_type !== NULL && $item->payment_wholesale_refund_type === 'full'): ?>
                                            <span class="text-success">(คืนยอดเต็มจำนวนแล้ว)</span>
                                            <?php endif; ?>
                                    <?php else: ?>
                                           <?php if($item->payment_wholesale_refund_type !== NULL && $item->payment_wholesale_refund_type === 'some'): ?>
                                           <span class="text-danger">(รอคืนยอดบางส่วน)</span>
                                           <?php elseif($item->payment_wholesale_refund_type !== NULL && $item->payment_wholesale_refund_type === 'full'): ?>
                                           <span class="text-danger">(รอคืนยอดเต็มจำนวน)</span>
                                           <?php endif; ?>
                                    <?php endif; ?>

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if($item->payment_wholesale_file_path !== NULL ): ?>
                                        สลิปชำระ :  <a onclick="openPdfPopup(this.href); return false;"
                                        href="<?php echo e(asset($item->payment_wholesale_file_path)); ?>"><?php echo e($item->payment_wholesale_file_name); ?></a>
                                    <?php else: ?>
                                    <span class="text-info">รอยืนยันการชำระเงิน</span>
                                    <?php endif; ?>
                                    <br>
                                    <?php if($item->payment_wholesale_refund_file_name !== NULL ): ?>
                                        สลิปคืนยอด :  
                                        <a onclick="openPdfPopup(this.href); return false;" class="text-danger" href="<?php echo e(asset($item->payment_wholesale_refund_file_path)); ?>"><?php echo e($item->payment_wholesale_refund_file_name); ?></a><br>
                                        <a onclick="openPdfPopup(this.href); return false;" class="text-danger" href="<?php echo e(asset($item->payment_wholesale_refund_file_path1)); ?>"><?php echo e($item->payment_wholesale_refund_file_name1); ?></a><br>
                                        <a onclick="openPdfPopup(this.href); return false;" class="text-danger" href="<?php echo e(asset($item->payment_wholesale_refund_file_path2)); ?>"><?php echo e($item->payment_wholesale_refund_file_name2); ?></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($item->payment_wholesale_refund_file_name): ?>
                                        <?php if($item->payment_wholesale_type === 'full'): ?>
                                            ชำระเต็มจำนวน 
                                        <?php else: ?>
                                            ชำระมัดจำ 
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if($item->payment_wholesale_type === 'full'): ?>
                                            ชำระเต็มจำนวน 
                                        <?php else: ?>
                                            ชำระมัดจำ 
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="<?php echo e(route('paymentWholesale.edit', $item->payment_wholesale_id)); ?>"
                                        class=" text-info payment-wholesale-edit"><i class="fa fa-edit"></i> แก้ไข</a>
                                        &nbsp;
                                        <a class="wholesale-mail" href="<?php echo e(route('paymentWholesale.modalMailWholesale',$item->payment_wholesale_id)); ?>"><i class="fas fa-envelope text-info"></i>ส่งเมล</a>
                                    &nbsp;
                                  
                                    <a href="<?php echo e(route('paymentWholesale.editRefund', $item->payment_wholesale_id)); ?>"
                                        class="text-primary edit-refund"><i
                                            class="fa fas fa-edit"></i>การคืนยอด</a>
                                    &nbsp;
 
                                    <a href="<?php echo e(route('paymentWholesale.delete', $item->payment_wholesale_id)); ?>"
                                        onclick="return confirm('คุณต้องการลบข้อมูลใช่ไหม');" class="text-danger"><i
                                            class="fa fas fa-trash"></i> ลบ</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <tr>
                            <td align="right" class="text-success" colspan="8"><b>(<?php echo App\Helpers\BathTextHelper::convert($quotationModel->GetDepositWholesale() - $quotationModel->GetDepositWholesaleRefund()); ?>)</b></td>
                            <td align="center" class="text-success">
                                <b><?php echo e(number_format($quotationModel->GetDepositWholesale() - $quotationModel->GetDepositWholesaleRefund(), 2)); ?></b>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm modal-lg" id="payment-wholesale-edit" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm modal-lg" id="refund" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm modal-lg" id="edit-refund" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm modal-lg" id="wholesale-mail" tabindex="-1" role="dialog"
aria-labelledby="mySmallModalLabel" aria-hidden="true">
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        ...
    </div>
</div>
</div>

<script>
    $(document).ready(function() {

         // modal 
         $(".wholesale-mail").click("click", function(e) {
            e.preventDefault();
            $("#wholesale-mail")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });


         // modal Payment Refund
         $(".edit-refund").click("click", function(e) {
            e.preventDefault();
            $("#edit-refund")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });


        // modal Payment Refund
        $(".refund").click("click", function(e) {
            e.preventDefault();
            $("#refund")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });

        // modal Payment Wholesale
        $(".payment-wholesale-edit").click("click", function(e) {
            e.preventDefault();
            $("#payment-wholesale-edit")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
    });
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/accounting-nexttripholiday/resources/views/paymentWholesale/wholesale-table.blade.php ENDPATH**/ ?>