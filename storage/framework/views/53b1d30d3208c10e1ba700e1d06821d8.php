<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-danger">
            <h5 class="mb-0 text-white"><i class="fas fa-file-alt"></i>
                ไฟล์หนังสือเดินทาง / Passport Photo 
                &nbsp; <a href="javascript:void(0)" class="text-white float-end" onclick="toggleAccordion('table-files', 'toggle-arrow-files')">
                    <span class="fas fa-chevron-down" id="toggle-arrow-files"></span>
                </a>
            </h5>
        </div>
        <div class="card-body" id="table-files" style="display: block">
               <button class="btn btn-danger float-end" data-bs-toggle="modal" data-bs-target="#bs-example-modal-xlg">
                              <i class="fa fa-file"></i>  เพิ่มไฟล์เอกสาร
                            </button>
            <div class="table table-responsive">
                <table class="table product-overview">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ตำแหน่งไฟล์</th>
                            <th>วันที่</th>
                            <th>ไฟล์</th>
                            <th >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $quoteFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($key + 1); ?></td>
                                <td>
                                    <?php echo e($item->quote_file_path); ?>

                                </td>
                                <td><?php echo e(date('d-m-Y H:m:s',strtotime($item->created_at))); ?></td>
                                <td>
                                    <a  onclick="openPdfPopup(this.href); return false;"
                                        href="<?php echo e(asset($item->quote_file_path)); ?>"><?php echo e($item->quote_file_name); ?></a>
                                </td>
                                <td>
                                    <a href="" class="text-info py-3"><i  class="fa fa-edit mt-3"></i> แก้ไข</a> &nbsp;
                                    <a class="mt-3 modal-mail-file" href="<?php echo e(route('quotefile.modalMail',$item->quote_file_id)); ?>"><i class="fas fa-envelope text-info"></i> ส่งเมล</a> &nbsp; &nbsp;
                                    <a href="<?php echo e(route('quotefile.delete', $item->quote_file_id)); ?>" onclick="return confirm('ยืนยันการลบ');" class="text-danger"><i class="fa fa-trash"></i> Delete</a>
                                    
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


  <!-- sample modal content -->
  <div class="modal fade" id="bs-example-modal-xlg" tabindex="-1" aria-labelledby="bs-example-modal-lg"
  aria-hidden="true">
  <div class="modal-dialog modal-xl">
      <div class="modal-content">
          <div class="modal-header d-flex align-items-center">
              <h4 class="modal-title" id="myLargeModalLabel">
                  แนบเอกสาร
              </h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
             <form action="<?php echo e(route('quotefile.upload')); ?>" enctype="multipart/form-data" method="post" id="form-upload">
              <?php echo csrf_field(); ?>
              <?php echo method_field('POST'); ?>
              <input type="hidden" name="quote_number" value="<?php echo e($quotationModel->quote_number); ?>">
              <div class="row">
                  <div class="col-md-3">
                      <input type="file" name="file" required>
                  </div>
                  <div class="col-md-3">
                      <input type="text" class="form-control" name="file_name" placeholder="ชื่อไฟล์เอกสาร" required>
                  </div>
              </div>
             </form>
          </div>
          <div class="modal-footer">
             <button class="btn btn-success" form="form-upload" type="submit"> <i class="fa fa-save"></i> Upload</button>
          </div>
      </div>
      <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div class="modal fade bd-example-modal-sm modal-xl" id="modal-mail-file" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


<script>
    $(document).ready( function (){
          // modal add payment invoice
          $(".modal-mail-file").click("click", function(e) {
            e.preventDefault();
            $("#modal-mail-file")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
    })
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/accounting-nexttripholiday/resources/views/quoteFiles/files-table.blade.php ENDPATH**/ ?>