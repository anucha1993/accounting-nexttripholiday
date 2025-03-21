<?php $__env->startSection('content'); ?>
<div class="container-fluid page-content">

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
    role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>Success - </strong><?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
    role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>Error - </strong><?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Wholesales</h4>
            <h6 class="card-subtitle lh-base">
                รายชื่อโฮลเซลล์ทั้งหมด

                <a href="<?php echo e(route('wholesale.create')); ?>" class="btn btn-info btn-sm float-end mb-3"><i class="fas fa-plus"></i> เพิ่มข้อมูล</a>
            </h6>
            <form action="" method="GET">
               <div class="input-group mb-3 pull-right">
                   <input type="text" class="form-control" placeholder="ค้นหาข้อมูล..." name="search" value="<?php echo e(request('search')); ?>">
                   <div class="input-group-append">
                       <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
                   </div>
               </div>
           </form>
   
        </div>
        
        <div class="table-responsive">
            <table class="table customize-table table-hover mb-0 v-middle">
                <thead class="table-light">
                    <tr>
                        <th>ลำดับ</th>
                        <th>รหัส</th>
                        <th>ชื่อ โฮลเซลล์</th>
                        <th>เบอร์โทรศัพท์</th>
                        <th>ชื่อผู้ติดต่อ</th>
                        <th>สถานะ</th>
                        <th>วันที่อัพเดท</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $wholesales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e($item->code); ?></td>
                            <td><?php echo e($item->wholesale_name_th); ?></td>
                            <td><?php echo e($item->tel ?: '-'); ?></td>
                            <td><?php echo e($item->contact_person ?: '-'); ?></td>
                            <td>
                                <?php if($item->status === 'on'): ?>
                                    <span class="badge rounded-pill bg-success">เปิดใช้งาน</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-danger">ปิดใช้งาน</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(date('d-m-Y', strtotime($item->updated_at))); ?></td>
                            <td>
                              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['edit-wholesale'])): ?>
                              <a href="<?php echo e(route('wholesale.edit',$item->id)); ?>" class="ml-3"><i class=" fas fa-edit "> </i> แก้ไข</a>
                              <?php endif; ?>

                              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['delete-wholesale'])): ?>
                           
                              <a href="<?php echo e(route('wholesale.destroy', $item->id)); ?>" type="submit" class="text-danger mx-3" onclick="return confirm('Do you want to delete this Wholesale?');"><i class=" fas fa-trash"> </i> ลบ</a>
                             
                              <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        Not found Data Wholesale
                    <?php endif; ?>
                </tbody>
            </table>
            <br>
            <?php echo $wholesales->withQueryString()->links('pagination::bootstrap-5'); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/accounting-nexttripholiday/resources/views/wholesales/index.blade.php ENDPATH**/ ?>