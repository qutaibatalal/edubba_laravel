<?php $__env->startSection('title', __('batches.index.title')); ?>
<?php $__env->startSection('page', __('batches.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('batches.index.title'); ?></h1>
        <p><?php echo app('translator')->get('batches.index.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.batches.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('batches.index.add_batch'); ?></a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-semibold"><?php echo app('translator')->get('batches.index.header'); ?></span>
        <a href="<?php echo e(route('admin.batches.create')); ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('batches.index.add_short'); ?></a>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th><?php echo app('translator')->get('batches.index.col_name'); ?></th><th><?php echo app('translator')->get('batches.index.col_program'); ?></th><th><?php echo app('translator')->get('batches.index.col_year'); ?></th><th><?php echo app('translator')->get('batches.index.col_class_teacher'); ?></th><th><?php echo app('translator')->get('batches.index.col_capacity'); ?></th><th><?php echo app('translator')->get('batches.index.col_students'); ?></th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($b->name, 0, 1)); ?></span>
                                <span class="fw-semibold"><?php echo e($b->name); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($b->program?->name ?? '—'); ?></td>
                        <td><?php echo e($b->academicYear?->name ?? '—'); ?></td>
                        <td><?php echo e($b->classTeacher?->full_name ?? '—'); ?></td>
                        <td><?php echo e($b->capacity ?? '—'); ?></td>
                        <td><span class="badge badge-soft"><?php echo e($b->students_count); ?></span></td>
                        <td class="text-start">
                            <div class="d-flex gap-1">
                                <a href="<?php echo e(route('admin.batches.edit', $b)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="<?php echo e(route('admin.batches.destroy', $b)); ?>" class="d-inline" onsubmit="return confirm('<?php echo e(__('batches.index.confirm_delete')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-diagram-3"></i><p><?php echo app('translator')->get('batches.index.empty'); ?></p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/batches/index.blade.php ENDPATH**/ ?>