<div class="col-md-12">
   
    

    <div class="card">
        <div class="card-header bg-success" >
         
            <h5 class="mb-0 text-white">
                <i class="fa fa-file"></i> รายละเอียดใบจองใบทัวร์ 
                <span class="float-end">
                    Booking No.: <?php echo e($quotationModel->quote_booking); ?>

                    &nbsp; <a href="javascript:void(0)" class="text-white" onclick="toggleAccordion('table-quote', 'toggle-arrow')">
                        <span class="fas fa-chevron-down" id="toggle-arrow"></span>
                    </a>
                </span>
            </h5>
        </div>

        <div class="card-body" id="table-quote" style="display: block;">
            <div class="table table-responsive">
                <table class="table product-overview">
                    <thead>
                        <tr>
                            <th style="width: 100px">ลำดับ</th>
                            <th>รายการ</th>
                            <th>จำนวน</th>
                            
                            <th style="text-align: center">ราคาต่อหน่วย/บาท	</th>
                            <th style="text-align: center"> 3%</th>
                            <th style="text-align: center">ราคารวม/บาท</th>
                           
                        </tr>
                    </thead>
                     
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $quoteProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(++$key); ?></td>
                                <td>
                                    <?php if($item->product_pax === 'Y'): ?>
                                    <?php echo e($item->product_name); ?> <i class="fa fa-user text-secondary"></i> <span class="text-secondary">(PAX)</span>
                                    <?php else: ?>
                                    <?php echo e($item->product_name); ?>

                                    <?php endif; ?>
                                    
                                </td>
                                <td><?php echo e($item->product_qty); ?></td>
                                <td align="center">
                                    <?php if($item->withholding_tax === 'N'): ?>
                                    <?php echo e(number_format( $item->product_price  , 2, '.', ',')); ?>

                                    <?php else: ?>
             
                                    <?php echo e(number_format( ($item->product_price * 0.03)+$item->product_price  , 2, '.', ',')); ?>

                                    <?php endif; ?>
                                </td>
                                <td align="center">
                                    <?php if($item->withholding_tax === 'N'): ?>
                                    <input type="checkbox" disabled>
                                    <?php else: ?>
        
                                     <input type="checkbox" checked disabled>
                                    <?php endif; ?>
                                </td>
                                <td align="center"><?php echo e(number_format($item->product_sum , 2, '.', ',')); ?></td>
                                
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            
                        <?php endif; ?>
                      
                        <?php if($quoteProductsDiscount->isNotEmpty()): ?>
                            <tr class="text-danger">
                                <td colspan="6">ส่วนลด</td>
                            </tr>
                        <?php else: ?>
                            
                        <?php endif; ?>

                        <?php $__empty_1 = true; $__currentLoopData = $quoteProductsDiscount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="text-danger">
                                <td><?php echo e(++$key); ?></td>
                                <td>
                                    <?php if($item->product_pax === 'Y'): ?>
                                    <?php echo e($item->product_name); ?> <i class="fa fa-user text-secondary"></i> <span class="text-secondary">(PAX)</span>
                                    <?php else: ?>
                                    <?php echo e($item->product_name); ?>

                                    <?php endif; ?>
                                    
                                </td>
                                <td><?php echo e($item->product_qty); ?></td>
                                <td align="center">
                                    <?php if($item->withholding_tax === 'N'): ?>
                                    <?php echo e(number_format( $item->product_price  , 2, '.', ',')); ?>

                                    <?php else: ?>
             
                                    <?php echo e(number_format( ($item->product_price * 0.03)+$item->product_price  , 2, '.', ',')); ?>

                                    <?php endif; ?>
                                </td>
                                <td align="center">
                                    <?php if($item->withholding_tax === 'N'): ?>
                                      <input type="checkbox" disabled>
                                    <?php else: ?>
             
                                    <?php echo e(number_format( ($item->product_price * 0.03)  , 2, '.', ',')); ?>

                                    <?php endif; ?>
                                </td>
                                <td align="center"><?php echo e(number_format($item->product_sum , 2, '.', ',')); ?></td>
                                
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            
                        <?php endif; ?>


                        <tr class="text-info">
                            <td align="right" colspan="5"><b>(<?php echo App\Helpers\BathTextHelper::convert($quotationModel->quote_grand_total); ?>)</b></td>
                            <td align="center" ><b><u><?php echo e(number_format($quotationModel->quote_grand_total , 2, '.', ',')); ?></u></b></td>
                        </tr>
                    </tbody>
                </table>
    </div>
     </div>
</div>


<div class="col-md-12">
    <div class="card">



        <div class="card-header bg-dark">
            <h5 class="mb-0 text-white"><i class="fa fa-file"></i>
                รายละเอียดใบแจ้งหนี้ 
                <span class="float-end">
                    invoice 
                    &nbsp; <a href="javascript:void(0)" class="text-white" onclick="toggleAccordion('table-invoices', 'toggle-arrow-invoices')">
                        <span class="fas fa-chevron-down" id="toggle-arrow-invoices"></span>
                    </a>
                </span>
            </h5>
        </div>
        <div class="card-body" id="table-invoices"style="display: block;">
            <div class="table table-responsive">
                <table class="table product-overview">
                    <thead>
                        <tr>
                            <th style="width: 100px">ปรเภท</th>
                            <th>วันที่</th>
                            <th>เลขที่เอกสาร</th>
                            
                            <th style="text-align: center">ยอดรวมสิทธิ์</th>
                            <th style="text-align: center">ยอดชำระแล้ว</th>
                            <th style="text-align: center">ยอดคงค้าง</th>
                            <th style="text-align: center">หัก ณ. ที่จ่าย</th>
                            <th style="text-align: left">Actions Report</th>

                            <th style="text-align: left">Actions</th>
                            <th style="text-align: left">Cancel</th>
                        </tr>
                    </thead>

                    <?php
                         $incomeTotal = 0;
                         $CreditNoteTotal = 0;
                    ?>
                     
                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $quotations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr <?php echo $quotationModel->quote_status === 'cancel' ? 'style="background-color: rgb(167, 167, 167)"' : ''; ?> >
                                <td>ใบเสนอราคา</td>
                                <td><?php echo e(date('d/m/Y', strtotime($quotationModel->created_at))); ?></td>
                                <td><span class="badge bg-dark"><?php echo e($quotationModel->quote_number); ?> </span>

                                </td>
                                
                                <td align="center">
                                    <?php
                                        $incomeTotal += $quotationModel->quote_grand_total
                                    ?>
                                    <?php echo e(number_format($quotationModel->quote_grand_total, 2, '.', ',')); ?></td>
                                <td align="center"><?php echo e(number_format($quotationModel->GetDeposit()- $quotationModel->Refund(), 2, '.', ',')); ?>

                                </td>
                                <td align="center">
                                    <?php echo e(number_format($quotationModel->quote_grand_total - $quotationModel->GetDeposit()+$quotationModel->Refund() , 2, '.', ',')); ?>

                                </td>
                                <td align="center">
                                    <?php if($item->quote_withholding_tax_status === 'Y'): ?>
                                        <?php echo e(number_format($item->quote_withholding_tax, 2, '.', ',')); ?>

                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a class="dropdown-item" target="_blank"
                                        href="<?php echo e(route('mpdf.quote', $quotationModel->quote_id)); ?>"
                                        onclick="openPdfPopup(this.href); return false;">
                                        <i class="fa fa-print text-danger "></i>
                                        พิมพ์ใบเสนอราคา
                                    </a>
                                    <a class="dropdown-item mail-quote"
                                        href="<?php echo e(route('mail.quote.formMail', $quotationModel->quote_id)); ?>">
                                        <i class="fas fa-envelope text-info"></i>
                                        ส่งเมล
                                    </a>
                                </td>


                                <td align="left" >

                                    <?php if($quotationModel->quote_status != 'cancel'): ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-quote')): ?>
                                            
                                                    <a class="dropdown-item modal-quote-edit"
                                                    href="<?php echo e(route('quote.modalEdit', ['quotationModel' => $quotationModel->quote_id, 'mode' => 'edit'])); ?>">
                                                    <i class="fa fa-edit text-info"></i> แก้ไข
                                                 </a>
                                                    
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-invoice')): ?>
                                        <?php if(empty($invoiceModel)): ?>
                                            <a class="dropdown-item modal-invoice"
                                               href="<?php echo e(route('invoice.create', $quotationModel->quote_id)); ?>"><i
                                               class="fas fa-file-alt"></i> ออกใบแจ้งหนี้</a>
                                   
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <a class="dropdown-item modal-quote-edit"
                                    href="<?php echo e(route('quote.modalEdit', ['quotationModel' => $quotationModel->quote_id, 'mode' => 'view'])); ?>">
                                    <i class="fa fa-eye text-info"></i> ดูรายละเอียด
                                 </a>
                                    

                                        <?php else: ?>
                                        <span class="dot-danger"></span>ใบงานถูกยกเลิก

                                        <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-quote')): ?>
                                      <?php if($quotationModel->quote_status === 'cancel'): ?>
                                      <a class="modal-quote-cancel" href="<?php echo e(route('quote.modalCancel', $quotationModel->quote_id)); ?>"><i
                                        class="fas fa-minus-circle text-danger"></i> เหตุผลยกเลิกใบงาน</a>
                                        <br>
                                        <a href="<?php echo e(route('quote.recancel',$quotationModel->quote_id)); ?>" class="text-white" onclick="return confirm('คุณต้องการนำใบเสนอราคากลับมาใช้ใหม่ใช่ไหม!');" > <i class=" far fa-share-square"></i> นำกลับมาใช้ใหม่</a>
                                      <?php else: ?>
                                      <a class="modal-quote-cancel" href="<?php echo e(route('quote.modalCancel', $quotationModel->quote_id)); ?>"><i
                                        class="fas fa-minus-circle text-danger"></i> ยกเลิกใบงาน</a>
                                      <?php endif; ?>
                                        
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <?php endif; ?>


                        

                        <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemInvoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr <?php echo $quotationModel->quote_status === 'cancel' ? 'style="background-color: rgb(167, 167, 167)"' : ''; ?> >
                                <td class="text-success">ใบแจ้งหนี้</td>
                                <td><?php echo e(date('d/m/Y', strtotime($itemInvoice->invoice_date))); ?></td>
                                <td><span class="badge bg-dark"><?php echo e($itemInvoice->invoice_number); ?></span>
                                </td>
                                <td align="center">
                                  

                                    <?php echo e(number_format($itemInvoice->invoice_grand_total, 2, '.', ',')); ?></td>
                                <td align="center">
                                    N/A
                                </td>
                                <td align="center">
                                    N/A
                                </td>
                                <td align="center">
                                    <?php if($itemInvoice->invoice_withholding_tax_status === 'Y'): ?>
                                        <?php echo e(number_format($itemInvoice->invoice_withholding_tax, 2, '.', ',')); ?>

                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="dropdown-item" onclick="openPdfPopup(this.href); return false;"
                                        href="<?php echo e(route('mpdf.invoice', $itemInvoice->invoice_id)); ?>"><i
                                            class="fa fa-print text-danger"></i>
                                        พิมพ์ใบแจ้งหนี้</a>

                                    <a class="dropdown-item mail-quote"
                                        href="<?php echo e(route('mail.invoice.formMail', $itemInvoice->invoice_id)); ?>"><i
                                            class="fas fa-envelope text-info"></i>
                                        ส่งเมล</a>
                                </td>
                              
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-invoice')): ?>
                                    <?php if($itemInvoice->invoice_status !== 'cancel'): ?>
                                    <a class="dropdown-item modal-invoice-edit"
                                    href="<?php echo e(route('invoice.edit', ['invoiceModel' => $itemInvoice->invoice_id, 'mode' => 'edit'])); ?>">
                                    <i class="fa fa-edit text-info"></i> แก้ไข</a>

                                <?php if($itemInvoice->invoice_status === 'wait' && $quotationModel->quote_payment_status === 'success'): ?>
                                    <a class="dropdown-item"
                                        href="<?php echo e(route('invoice.taxinvoice', $itemInvoice->invoice_id)); ?>"
                                        onclick="return confirm('ระบบจะอ้างอิงรายการสินค้าจากใบแจ้งหนี้');"><i
                                            class="fas fa-file-alt"></i> ออกใบกำกับภาษี</a>
                                <?php endif; ?>
                                    <?php else: ?>
                                    <span class="dot-danger"></span>ใบงานถูกยกเลิก
                                    <?php endif; ?>  
                                     <?php endif; ?>
                                    <a class="dropdown-item modal-invoice-edit"
                                    href="<?php echo e(route('invoice.edit', ['invoiceModel' => $itemInvoice->invoice_id, 'mode' => 'view'])); ?>">
                                    <i class="fa fa-eye text-info"></i> ดูรายละเอียด
                                 </a>
                                    
                                </td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cancel-invoice')): ?>
                                    <?php if($itemInvoice->invoice_status === 'cancel'): ?>
                                    <a class="modal-invoice-cancel" href="<?php echo e(route('invoice.modalCancel', $itemInvoice->invoice_id)); ?>"><i
                                        class="fas fa-minus-circle text-danger"></i>เหตุผลยกเลิกใบงาน</a>
                                        <br>
                                        <a href="<?php echo e(route('quote.recancel',$quotationModel->quote_id)); ?>" class="text-white" onclick="return confirm('คุณต้องการนำใบเสนอราคากลับมาใช้ใหม่ใช่ไหม!');" > <i class=" far fa-share-square"></i> นำกลับมาใช้ใหม่</a>
                                      <?php else: ?>
                                    
                                        <a class="modal-quote-cancel" href="<?php echo e(route('quote.modalCancel', $quotationModel->quote_id)); ?>"><i
                                            class="fas fa-minus-circle text-danger"></i> ยกเลิกใบงาน</a>
                                    <?php endif; ?>
                                        
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>

                        

                        <?php $__empty_1 = true; $__currentLoopData = $taxinvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr <?php echo $quotationModel->quote_status === 'cancel' ? 'style="background-color: rgb(167, 167, 167)"' : ''; ?> >
                                <td class="text-primary">ใบกำกับภาษี</td>
                                <td><?php echo e(date('d/m/Y', strtotime($item->taxinvoice_date))); ?></td>
                                <td><span class="badge bg-dark"><?php echo e($item->taxinvoice_number); ?></span>
                                </td>
                                <td align="center">
                                    <?php echo e(number_format($item->invoice_grand_total, 2, '.', ',')); ?></td>
                                <td align="center">
                                    N/A
                                </td>
                                <td align="center">
                                    N/A
                                </td>
                                
                                <td align="center">
                                    <?php if($item->invoice_withholding_tax_status === 'Y'): ?>
                                        <?php echo e(number_format($item->invoice_withholding_tax, 2, '.', ',')); ?> <br>
                                        
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a class="dropdown-item" onclick="openPdfPopup(this.href); return false;"
                                        href="<?php echo e(route('mpdf.taxreceipt', $item->invoice_id)); ?>"><i
                                            class="fa fa-print text-danger"></i>
                                        พิมพ์ใบกำกับภาษี</a>

                                       

                                    <a class="dropdown-item mail-quote"
                                        href="<?php echo e(route('mail.taxreceipt.formMail', $item->invoice_id)); ?>"><i
                                            class="fas fa-envelope text-info"></i>
                                        ส่งเมล</a>
                                </td>

                                
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-invoice')): ?>
                                    <?php if($item->taxinvoice_status !== 'cancel'): ?>
                                    <a class="dropdown-item modal-invoice-edit"
                                    href="<?php echo e(route('invoice.edit', ['invoiceModel' => $itemInvoice->invoice_id, 'mode' => 'edit'])); ?>">
                                    <i class="fa fa-edit text-info"></i> แก้ไข</a>
                               
                                    <?php else: ?>
                                    <span class="dot-danger"></span>ใบงานถูกยกเลิก
                                    <?php endif; ?>
                                     
                                    <?php endif; ?>
                                    <a class="dropdown-item modal-invoice-edit"
                                    href="<?php echo e(route('invoice.edit', ['invoiceModel' => $itemInvoice->invoice_id, 'mode' => 'view'])); ?>">
                                    <i class="fa fa-eye text-info"></i> ดูรายละเอียด
                                 </a>

                                </td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cancel-invoice')): ?>
                                    <?php if($item->taxinvoice_status === 'cancel'): ?>
                                    <a class="modal-taxinvoice-cancel"
                                    href="<?php echo e(route('taxinvoice.modalCancel', $item->taxinvoice_id)); ?>"><i
                                        class="fas fa-minus-circle text-danger"></i> เหตุผลยกเลิกใบงาน</a>
                                        <br>
                                        <a href="<?php echo e(route('quote.recancel',$quotationModel->quote_id)); ?>" class="text-white" onclick="return confirm('คุณต้องการนำใบเสนอราคากลับมาใช้ใหม่ใช่ไหม!');" > <i class=" far fa-share-square"></i> นำกลับมาใช้ใหม่</a>
                                      <?php else: ?>
                                    
                                        <a class="modal-quote-cancel" href="<?php echo e(route('quote.modalCancel', $quotationModel->quote_id)); ?>"><i
                                            class="fas fa-minus-circle text-danger"></i> ยกเลิกใบงาน</a>
                                    <?php endif; ?>
                                       
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>

                        

                        


                        

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="modal fade bd-example-modal-sm modal-lg" id="invoice-payment" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm modal-lg" id="debit-payment" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm modal-lg" id="credit-payment" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm modal-lg" id="quote-payment-wholesale" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm modal-lg" id="modal-mail-quote" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>



<div class="modal fade bd-example-modal-sm modal-xl" id="modal-invoice-create" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm modal-xl" id="modal-invoice-edit" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm modal-xl" id="modal-quote-edit" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>



<div class="modal fade bd-example-modal-sm modal-xl" id="modal-debit-create" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm modal-xl" id="modal-quote-cancel" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm modal-xl" id="modal-invoice-cancel" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm modal-xl" id="modal-taxinvoice-cancel" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>








<script>
    function openPdfPopup(url) {
        var width = 800; // กำหนดความกว้างของหน้าต่าง
        var height = 600; // กำหนดความสูงของหน้าต่าง
        var left = (window.innerWidth - width) / 2; // คำนวณตำแหน่งจากด้านซ้ายของหน้าจอ
        var top = (window.innerHeight - height) / 2; // คำนวณตำแหน่งจากด้านบนของหน้าจอ

        // เปิดหน้าต่างใหม่ด้วยการคำนวณตำแหน่งและขนาด
        window.open(url, 'PDFPopup', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
    }
    // เปิด modal แก้ไขใบเสนอราคา
    $(".modal-quote-edit").off("click").on("click", function(e) {
        e.preventDefault();
        var modal = $("#modal-quote-edit");

        // ล้างข้อมูลเก่าก่อนเปิด modal
        modal.find(".modal-content").html('');

        // โหลดเนื้อหาใหม่
        modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

        // เมื่อปิด modal, ล้างข้อมูล
        modal.on('hidden.bs.modal', function() {
            $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
        });
    });

    // เปิด modal แก้ไขใบแจ้งหนี้
    $(".modal-invoice").off("click").on("click", function(e) {
        e.preventDefault();
        var modal = $("#modal-invoice-create");

        // ล้างข้อมูลเก่าก่อนเปิด modal
        modal.find(".modal-content").html('');

        // โหลดเนื้อหาใหม่
        modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

        // เมื่อปิด modal, ล้างข้อมูล
        modal.on('hidden.bs.modal', function() {
            $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
        });
    });

    // เปิด modal แก้ไขใบแจ้งหนี้
    $(".modal-invoice-edit").off("click").on("click", function(e) {
        e.preventDefault();
        var modal = $("#modal-invoice-edit");

        // ล้างข้อมูลเก่าก่อนเปิด modal
        modal.find(".modal-content").html('');

        // โหลดเนื้อหาใหม่
        modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

        // เมื่อปิด modal, ล้างข้อมูล
        modal.on('hidden.bs.modal', function() {
            $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
        });
    });

    // เปิด modal เพิ่มใบเพิ่มหนี้
    $(".debit-create").off("click").on("click", function(e) {
        e.preventDefault();
        var modal = $("#modal-debit-create");

        // ล้างข้อมูลเก่าก่อนเปิด modal
        modal.find(".modal-content").html('');

        // โหลดเนื้อหาใหม่
        modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

        // เมื่อปิด modal, ล้างข้อมูล
        modal.on('hidden.bs.modal', function() {
            $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
        });
    });





    $(document).ready(function() {
        // modal add payment wholesale quote
        $(".mail-quote").click("click", function(e) {
            e.preventDefault();
            $("#modal-mail-quote")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });

        // modal add payment wholesale quote
        $(".payment-quote-wholesale").click("click", function(e) {
            e.preventDefault();
            $("#quote-payment-wholesale")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });



        // modal add payment invoice
        $(".invoice-modal").click("click", function(e) {
            e.preventDefault();
            $("#invoice-payment")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
        // modal add payment debit
        $(".debit-modal").click("click", function(e) {
            e.preventDefault();
            $("#debit-payment")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
        // modal add payment credit
        $(".credit-modal").click("click", function(e) {
            e.preventDefault();
            $("#credit-payment")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });

        $(".modal-quote-cancel").click("click", function(e) {
            e.preventDefault();
            $("#modal-quote-cancel")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
        $(".modal-invoice-cancel").click("click", function(e) {
            e.preventDefault();
            $("#modal-invoice-cancel")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
        $(".modal-taxinvoice-cancel").click("click", function(e) {
            e.preventDefault();
            $("#modal-taxinvoice-cancel")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });



    })
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/accounting-nexttripholiday/resources/views/quotations/quote-table.blade.php ENDPATH**/ ?>