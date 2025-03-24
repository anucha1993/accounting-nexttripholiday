<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-success">
            <h5 class="mb-0 text-white"><i class="fa fa-file"></i>
                รายการต้นทุน<span class="float-end"></span>
                &nbsp; <a href="javascript:void(0)" class="text-white float-end"
                    onclick="toggleAccordion('table-inputtax', 'toggle-arrow-inputtax')">
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
                            <th>เลขที่เอกสารอ้างอิง</th>
                            <th>ไฟล์แนบ</th>
                            <th>ยอดค่าบริการ</th>
                            <th>ใบหัก ณ ที่จ่าย</th>
                            <th>จำนวน:ภาษีหัก</th>
                            <th>จำนวน:ภาษี 7%</th>
                            <th>ยอดทั้งสิ้น</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <?php
                        $key = 0;
                        $inputTaxTotal = 0;
                    ?>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $invoiceModel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $inputTaxTotal += $invoice->getWithholdingTaxAmountAttribute();
                                $inputTaxTotal += $item->invoice_vat;
                            ?>

                            <tr>
                                <td><?php echo e(++$key); ?></td>
                                <td>ภาษีขาย</td>
                                <td><?php echo e($item->invoice_number); ?></td>
                                <td>
                                    <div id="invoice-file-container-<?php echo e($item->invoice_id); ?>">
                                        <!-- กรณีไม่มีไฟล์ ให้แสดง input สำหรับอัปโหลด -->
                                        <?php if(empty($item->invoice_image)): ?>
                                            <input type="file" name="invoice_file" data-id="<?php echo e($item->invoice_id); ?>"
                                                onchange="uploadInvoiceImage(this)">
                                        <?php else: ?>
                                            <!-- กรณีมีไฟล์ ให้แสดงปุ่มดูไฟล์และลบไฟล์ -->

                                            <a class="btn btn-sm btn-info"
                                                href="<?php echo e(asset('storage/' . $item->invoice_image)); ?>"
                                                onclick="openPdfPopup(this.href); return false;">เปิดดูไฟล์</a>

                                            <button class="btn btn-sm btn-danger"
                                                onclick="deleteInvoiceImage(<?php echo e($item->invoice_id); ?>)">ลบไฟล์</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php echo e(number_format($item->invoice_pre_vat_amount, 2)); ?>

                                </td>
                                <td>N/A</td>
                                <td><?php echo e(number_format($item->invoice_withholding_tax, 2)); ?></td>
                                <td><?php echo e(number_format($item->invoice_vat, 2)); ?></td>
                                <td><?php echo e(number_format($invoice->getWithholdingTaxAmountAttribute(), 2)); ?></td>
                                <td>N/A</td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                        </tr>


                        <?php $__empty_1 = true; $__currentLoopData = $inputTax; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <?php

                                if ($item->input_tax_status === 'success') {
                                    // $inputTaxTotal += $item->input_tax_withholding;
                                    // $inputTaxTotal += $item->input_tax_vat;
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
                                    <?php elseif($item->input_tax_type === 3): ?>
                                        ค่าธรรมเนียมรูดบัตร
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
                                    <?php if($item->input_tax_ref): ?>
                                        <?php echo e($item->input_tax_ref); ?>

                                </td>
                            <?php else: ?>
                                N/A
                        <?php endif; ?>
                        <td>
                          <?php if($item->input_tax_file): ?>
                            <a href="<?php echo e(asset('storage/' . $item->input_tax_file)); ?>" class="btn btn-info btn-sm" onclick="openPdfPopup(this.href); return false;">
                             เปิดดูไฟล์</a>

                             <a href="<?php echo e(route('inputtax.deletefile',$item->input_tax_id)); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete file?');"> ลบไฟล์</a>
                            <?php else: ?>

                            <form action="<?php echo e(route('inputtax.update',$item->input_tax_id)); ?>" method="POST" enctype="multipart/form-data"  id="upload-file-<?php echo e($item->input_tax_id); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="customer_id" value="<?php echo e($quotationModel->customer_id); ?>">
                                <input type="hidden" name="input_tax_quote_number" value="<?php echo e($quotationModel->quote_number); ?>">
                                <input type="file"   name="file" id="input-file-<?php echo e($item->input_tax_id); ?>">
                            </form>
                        
                            <script>
                                $(document).ready(function() {
                                    $("#input-file-<?php echo e($item->input_tax_id); ?>").change(function() {
                                        $(this).closest("form").submit();
                                    });
                                });
                            </script>
                            <?php endif; ?> 
                           



                        </td>
                        <td>
                            <?php echo e(number_format($item->input_tax_service_total, 2)); ?>


                        </td>
                        <td>
                            <?php if($item->input_tax_withholding_status === 'Y' && ($document)): ?>
                                <a href="<?php echo e(route('MPDF.generatePDFwithholding', $document->id)); ?>"
                                    onclick="openPdfPopup(this.href); return false;"> <i
                                        class="fa fa-file-pdf text-danger"></i> ปริ้นใบหัก ณ ที่จ่าย</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>

                        </td>

                        <td><?php echo e(number_format($item->input_tax_withholding, 2)); ?> </td>
                        <td><?php echo e(number_format($item->input_tax_vat, 2)); ?></td>

                        <td>
                            
                            <?php echo e($item->input_tax_grand_total); ?>

                        </td>

                        <td>
                            <?php if($item->input_tax_withholding_status === 'Y' && ($document)): ?>
                            
                                <a href="<?php echo e(route('withholding.modalEdit', $document->id)); ?>" class="input-tax-edit text-primary">
                                    <i class="fa fa-edit text-primary "></i>แก้ไขใบหัก ณ ที่จ่าย</a>
                            <?php else: ?>
                                
                            <?php endif; ?>
                            <br>
                            <?php if($item->input_tax_status === 'success'): ?>
                                <?php if($item->input_tax_wholesale_type === 'Y'): ?>
                                    <a href="<?php echo e(route('inputtax.inputtaxEditWholesale', $item->input_tax_id)); ?>"
                                        class="input-tax-edit"> <i class="fa fa-edit"> แก้ไข</i></a>


                                    
                                <?php else: ?>

                                <?php if($item->input_tax_withholding_status !== 'Y'): ?>
                                  <a href="<?php echo e(route('inputtax.editWholesale', $item->input_tax_id)); ?>" class="input-tax-edit"> <i class="fa fa-edit"> แก้ไข</i></a>
                                  <?php endif; ?>
                                  
                                <a href="<?php echo e(route('inputtax.delete', $item->input_tax_id)); ?>" class="text-danger"
                                   onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"></i>
                                     ลบ</a>
                                <?php endif; ?>
                            <?php else: ?>

                            <?php endif; ?>

                        </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                        <tr>
                        <tr>

                            <?php
                                $withholdingTaxAmount = $invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                $getTotalInputTaxVat = $quotationModel?->getTotalInputTaxVat() ?? 0;
                                // echo $withholdingTaxAmount."</br>";
                                // echo $getTotalInputTaxVat."</br>";

                                // ตรวจสอบว่า input_tax_file === NULL หรือไม่
                                $hasInputTaxFile = $quotationModel
                                    ->InputTaxVat()
                                    ->whereNotNull('input_tax_file')
                                    ->exists();

                                if ($hasInputTaxFile) {
                                    // กรณี input_tax_file !== NULL
                                    $paymentInputtaxTotal = $withholdingTaxAmount - $getTotalInputTaxVat;
                                } else {
                                    // กรณี input_tax_file === NULL
                                    $paymentInputtaxTotal = $withholdingTaxAmount + $getTotalInputTaxVat;
                                }
                            ?>
                            <td align="right" class="text-success" colspan="7">
                                <b>(<?php echo App\Helpers\BathTextHelper::convert($paymentInputtaxTotal+$quotationModel->getTotalInputTaxVatType()); ?>)</b>
                            </td>
                            <td align="center" class="text-danger" colspan="1">
                                <b>
                                    <?php echo e(number_format($paymentInputtaxTotal+$quotationModel->getTotalInputTaxVatType(), 2)); ?>

                                </b>
                            </td>
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
    function uploadInvoiceImage(input) {
        let invoiceId = $(input).data('id');
        let file = input.files[0];

        let reader = new FileReader();
        reader.onload = function(e) {
            let base64File = e.target.result;

            // ตรวจสอบข้อมูล Base64 ก่อนที่จะส่งไปยังเซิร์ฟเวอร์
            console.log("Base64 File Data:", base64File);

            $.ajax({
                url: '<?php echo e(route('uploadInvoiceImage')); ?>',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                data: JSON.stringify({
                    invoice_id: invoiceId,
                    invoice_file: base64File
                }),
                success: function(response) {
                    alert(response.message);
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.log("Error response:", xhr.responseText);
                    alert('File upload failed');
                }
            });
        };

        reader.readAsDataURL(file);
    }

    function deleteInvoiceImage(invoiceId) {
        $.ajax({
            url: '<?php echo e(route('deleteInvoiceImage')); ?>',
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            data: JSON.stringify({
                invoice_id: invoiceId
            }),
            success: function(response) {
                alert(response.message);

                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.log("Error response:", xhr.responseText);
                alert('File deletion failed');
            }
        });
    }

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
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/accounting-nexttripholiday/resources/views/inputTax/inputtax-table.blade.php ENDPATH**/ ?>