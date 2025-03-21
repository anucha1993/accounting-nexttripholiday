<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-primary">
            <h5 class="mb-0 text-white"><i class="fas fa-dollar-sign"></i>
                รายการชำระเงิน / Payment information 

                &nbsp; <a href="javascript:void(0)" class="text-white float-end" onclick="toggleAccordion('table-payment', 'toggle-arrow-payment')">
                    <span class="fas fa-chevron-down" id="toggle-arrow-payment"></span>
                </a>
            
            </h5>
        </div>
        <div class="card-body" id="table-payment" style="display: block">
            <div class="table table-responsive">
                <table class="table product-overview">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขที่ชำระ</th>
                            <th>วันที่ชำระ</th>
                            <th>รายละเอียดการชำระเงิน</th>
                            <th>จำนวนเงิน</th>
                            <th>ไฟล์แนบ</th>
                            <th>ประเภท</th>
                            <th>ใบเสร็จรับเงิน</th>
                            <th>Status</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <?php
                        $paymentTotal = 0;
                        $paymentDebitTotal = 0;
                    ?>
                    <tbody>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="<?php echo e($item->payment_type === 'refund' ? "background-color: rgb(250, 163, 163)" :''); ?>">
                                <td><?php echo e(++$key); ?></td>
                                <td>
                                    <?php echo e($item->payment_number); ?>

                                </td>
                                <td>
                                    <?php echo e(date('d-m-Y H:m:s', strtotime($item->payment_in_date))); ?>

                                </td>
                                <td>

                                    <?php if($item->payment_method === 'cash'): ?>
                                        เงินสด </br>
                                    <?php endif; ?>
                                    <?php if($item->payment_method === 'transfer-money'): ?>
                                        โอนเงิน</br>
                                        
                                    <?php endif; ?>
                                    <?php if($item->payment_method === 'check'): ?>
                                        เช็ค</br>
                                        
                                    <?php endif; ?>

                                    <?php if($item->payment_method === 'credit'): ?>
                                        บัตรเครดิต </br>
                                        
                                    <?php endif; ?>

                                </td>
                                <td>

                                    <?php if($item->payment_status === 'cancel'): ?>
                                        0
                                    <?php else: ?>
                                         <?php
                                         
                                             $paymentTotal += $item->payment_total - $item->payment_refund_total;
                                         ?>
                                        <?php echo e(number_format($item->payment_total - $item->payment_refund_total , 2, '.', ',')); ?>

                                    <?php endif; ?>

                                </td>
                                <td>
                                    <?php if($item->payment_file_path): ?>
                                    <a href="<?php echo e(asset('storage/' . $item->payment_file_path)); ?>" class="dropdown-item"
                                        onclick="openPdfPopup(this.href); return false;"><i
                                            class="fa fa-file text-danger"></i> สลิปโอน</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>

                                    

                                </td>
                                <td>

                                    <?php if($item->payment_status === 'cancel'): ?>
                                        -
                                    <?php else: ?>
                                        <?php if($item->payment_type === 'deposit'): ?>
                                            ชำระมัดจำ
                                        <?php elseif($item->payment_type === 'full'): ?>
                                            ชำระเงินเต็มจำนวน
                                        <?php elseif($item->payment_type === 'refund'): ?>
                                             คืนเงิน
                                        <?php endif; ?>
                                    <?php endif; ?>


                                </td>
                                <td>
                                    <?php if($item->payment_type !== 'refund'): ?>
                                    <a href="<?php echo e(route('mpdf.payment', $item->payment_id)); ?>" onclick="openPdfPopup(this.href); return false;"><i
                                        class="fa fa-print text-danger"></i> พิมพ์</a>
                                    <?php endif; ?>
                                    <a class="dropdown-item " href=""><i class="fas fa-envelope text-info"></i>ส่งเมล</a>
                                </td>
                                <td>
                                    <?php if($item->payment_status === 'cancel'): ?>
                                    <span class="badge rounded-pill bg-danger">Cancel</span>
                                    <?php else: ?>
                                    <?php if($item->payment_status === 'success' && $item->payment_type !== 'refund'): ?>
                                        <span class="badge rounded-pill bg-success">Success</span>
                                    <?php endif; ?>
                                    <?php if($item->payment_type === 'refund'): ?>
                                    <?php if($item->payment_file_path !== NULL): ?>
                                      <span class="badge rounded-pill bg-success">คืนเงินแล้ว</span>
                                    <?php else: ?>
                                    <span class="badge rounded-pill bg-warning">รอคืนเงิน</span>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if($item->payment_status === null): ?>
                                        <span class="badge rounded-pill bg-warning">NULL</span>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($item->payment_status !== 'cancel'): ?>
                                        <a class="dropdown-item payment-modal"
                                            href="<?php echo e(route('payment.edit', $item->payment_id)); ?>"><i
                                                class="fa fa-edit text-info"></i>
                                            แก้ไข</a>

                                     <a class="dropdown-item text-danger payment-modal-cancel" href="<?php echo e(route('payment.cancelModal', $item->payment_id)); ?>"><i class=" fas fa-minus-circle"></i> ยกเลิก</a>
                                    <?php else: ?>
                                    <?php echo e($item->payment_cancel_note); ?>


                                    <a href="<?php echo e(route('payment.RefreshCancel',$item->payment_id)); ?>" class="dropdown-item text-primary" onclick="return confirm('ยืนยันการคืนสถานะ');"> <i class="fas fa-recycle"></i> นำกลับมาใช้ใหม่ </a>
                                    <?php endif; ?>

                                    <a href="<?php echo e(route('payment.delete',$item->payment_id)); ?>" onclick="return confirm('ยืนยันการลบ');"><i class="fa fa-trash text-danger"></i> ลบ</a>



                                </td>
                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <?php endif; ?>

                    
                        <tr>
                             <?php echo e($quotation->GetDeposit()); ?>

                             <?php echo e($quotation->Refund()); ?>


                            <td align="right" class="text-success" colspan="7"><b>(<?php echo App\Helpers\BathTextHelper::convert($quotation->GetDeposit()- $quotation->Refund()); ?>)</b></td>
                            <td align="center" class="text-success" ><b><?php echo e(number_format($quotation->GetDeposit()- $quotation->Refund(),2)); ?></b></td>
                            <td align="center" class="text-danger" colspan="2"><b>( ยอดค้างชำระ : <?php echo e(number_format($quotation->quote_grand_total - $quotation->GetDeposit()+$quotation->Refund() , 2, '.', ',')); ?> )</b></td>
                        </tr>



                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>




<div class="modal fade bd-example-modal-sm modal-xl" id="modal-payment-edit" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm modal-lg" id="modal-payment-cancel" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>


<script>
  $(document).ready( function() {
      // modal   payment-modal
      $(".payment-modal").click("click", function(e) {
        e.preventDefault();
        $("#modal-payment-edit")
            .modal("show")
            .addClass("modal-lg")
            .find(".modal-content")
            .load($(this).attr("href"));
    });
  // modal   payment-modal camcel
  $(".payment-modal-cancel").click("click", function(e) {
        e.preventDefault();
        $("#modal-payment-cancel")
            .modal("show")
            .addClass("modal-lg")
            .find(".modal-content")
            .load($(this).attr("href"));
    });

    
  })
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/accounting-nexttripholiday/resources/views/payments/payment-table.blade.php ENDPATH**/ ?>