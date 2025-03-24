<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-warning">
            <h5 class="mb-0 text-white"><i class="fa fa-file"></i>
                รายการต้นทุนโฮลเซลล์ <span class="float-end"></span>
                &nbsp; <a href="javascript:void(0)" class="text-white float-end" onclick="toggleAccordion('table-inputtax', 'toggle-arrow-inputtax')">
                    <span class="fas fa-chevron-down" id="toggle-arrow-inputtax"></span>
                </a>
            </h5>
        </div>

        <div class="card-body" id="table-inputtax" style="display: block">
            <div class="table table-responsive">
                <table class="table product-overview">
                    <thead>
                        <tr>
                            <th style="width: 100px">ลำดับ</th>
                            <th>ประเภท</th>
                            <th>วันที่</th>
                            <th>ไฟล์แนบ</th>
                            <th>ยอดทั้งสิ้น</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <?php
                        $key = 0;
                        $inputTaxTotal = 0;
                    ?>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $inputTax; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                        if ($item->input_tax_status === 'success') {
                        $inputTaxTotal += $item->input_tax_grand_total;
                        }
                        ?>
                            <tr class="<?php if($item->input_tax_status === 'cancel'): ?> text-danger <?php endif; ?>">
                                <td><?php echo e(++$key); ?></td>
                                <td>
                                    
                                    <?php if($item->input_tax_type === 0): ?>
                                    ภาษีซื้อ
                                    <?php elseif($item->input_tax_type === 1): ?>
                                    ต้นทุนอื่นๆ
                                    <?php elseif($item->input_tax_type === 2): ?>
                                    ต้นทุนโฮลเซลล์
                                    <?php elseif($item->input_tax_type === 4): ?>
                                    ค่าทัวร์รวมทั้งหมด
                                   
                                    <?php elseif($item->input_tax_type === 5): ?>
                                    ค่าอาหาร
                                    <?php elseif($item->input_tax_type === 6): ?>
                                    ค่าตั๋วเครื่องบิน
                                    <?php elseif($item->input_tax_type === 7): ?>
                                    อื่นๆ
                                    <?php endif; ?>
                                   
                                </td>
                                <td>
                                    <?php echo e(date('d/m/Y : H:m:s',strtotime($item->created_at))); ?>

                                </td>

                                <td>
                                    <?php if($item->input_tax_file): ?>
                                    <a href="<?php echo e(asset('storage/' . $item->input_tax_file)); ?>"
                                        onclick="openPdfPopup(this.href); return false;"><i
                                            class="fa fa-file text-danger"></i> ไฟล์แนบ</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                    
                                </td>
                                
                                <td><?php echo e(number_format($item->input_tax_grand_total,2)); ?></td>

                                <td>
                                    <?php if($item->input_tax_status === 'success'): ?>
                                    <?php if($item->input_tax_type === 2): ?>
                                    <a href="<?php echo e(route('inputtax.inputtaxEditWholesale',$item->input_tax_id)); ?>" class="input-tax-edit"> <i class="fa fa-edit"> แก้ไข</i></a>
                                    
                                    <a href="<?php echo e(route('inputtax.delete',$item->input_tax_id)); ?>" class="text-danger input-tax-cancel" onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"> ลบ</i></a>
                                    <?php else: ?>
                                    <a href="<?php echo e(route('inputtax.inputtaxEditWholesale',$item->input_tax_id)); ?>" class="input-tax-edit" > <i class="fa fa-edit"> แก้ไข</i></a>
                                    
                                    <a href="<?php echo e(route('inputtax.delete',$item->input_tax_id)); ?>" class="text-danger input-tax-cancel" onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"> ลบ</i></a>
                                    <?php endif; ?>
                                    <?php else: ?>
                                        <?php echo e($item->input_tax_cancel); ?>

                                    <?php endif; ?>
                                    
                                </td>
                            </tr>
                            
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            
                        <?php endif; ?>

                        <tr>
                            <tr>
 
                                <td align="right" class="text-success"  colspan="5"><b>(<?php echo App\Helpers\BathTextHelper::convert($quotationModel->inputtaxTotalWholesale()); ?>)</b></td>
                                <td align="left" class="text-danger" colspan="1"><b><?php echo e(number_format($quotationModel->inputtaxTotalWholesale(),2)); ?></b></td>
                            </tr>
                        </tr>

                      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="modal fade bd-example-modal-sm modal-lg" id="input-tax-edit" tabindex="-1" role="dialog"
aria-labelledby="mySmallModalLabel" aria-hidden="true">
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        ...
    </div>
</div>
</div>


<div class="modal fade bd-example-modal-sm modal-lg" id="input-tax-cancel" tabindex="-1" role="dialog"
aria-labelledby="mySmallModalLabel" aria-hidden="true">
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        ...
    </div>
</div>
</div>

<script>

      $(".input-tax-edit").click("click", function(e) {
                    e.preventDefault();
                    $("#input-tax-edit")
                        .modal("show")
                        .addClass("modal-lg")
                        .find(".modal-content")
                        .load($(this).attr("href"));
                });

 
     $(".input-tax-cancel").click("click", function(e) {
                    e.preventDefault();
                    $("#input-tax-cancel")
                        .modal("show")
                        .addClass("modal-lg")
                        .find(".modal-content")
                        .load($(this).attr("href"));
                });

</script><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/accounting-nexttripholiday/resources/views/inputTax/inputtax-wholesale-table.blade.php ENDPATH**/ ?>