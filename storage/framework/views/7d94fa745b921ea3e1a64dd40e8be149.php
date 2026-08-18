<?php use App\Models\HostelRoom; ?>


<?php $__env->startSection('title', __('hostel.index.title')); ?>
<?php $__env->startSection('page', __('hostel.index.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('hostel.index.title'); ?></h1>
        <p><?php echo app('translator')->get('hostel.index.subtitle'); ?></p>
    </div>
    <a href="<?php echo e(route('admin.hostel.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus me-1"></i> <?php echo app('translator')->get('hostel.create.title'); ?>
    </a>
</div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success mt-3">
        <i class="bi bi-check-circle me-1"></i><?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div class="card hoverable mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-edb mb-0 align-middle">
                <thead>
                    <tr>
                        <th><?php echo app('translator')->get('hostel.name'); ?></th>
                        <th><?php echo app('translator')->get('hostel.warden'); ?></th>
                        <th><?php echo app('translator')->get('hostel.index.total'); ?></th>
                        <th><?php echo app('translator')->get('hostel.index.occupied'); ?></th>
                        <th><?php echo app('translator')->get('hostel.status'); ?></th>
                        <th class="text-end"><?php echo app('translator')->get('hostel.actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $hostels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($h->name); ?></td>
                        <td><?php echo e($h->warden_name ?? '—'); ?></td>
                        <td><?php echo e($h->rooms->count()); ?></td>
                        <td>
                            <?php $__currentLoopData = $h->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-<?php echo e($r->state === HostelRoom::STATE_AVAILABLE ? 'success' : ($r->state === HostelRoom::STATE_FULL ? 'danger' : 'warning')); ?>-soft">
                                    <?php echo e($r->state); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>

                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/hostel/index.blade.php ENDPATH**/ ?>