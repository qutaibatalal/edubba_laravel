<?php $__env->startSection('title', __('programs.index.title')); ?>
<?php $__env->startSection('page', __('programs.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('programs.index.title'); ?></h1>
        <p><?php echo app('translator')->get('programs.index.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.programs.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('programs.index.add_program'); ?></a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-semibold"><?php echo app('translator')->get('programs.index.header'); ?></span>
        <a href="<?php echo e(route('admin.programs.create')); ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('programs.index.add_short'); ?></a>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th><?php echo app('translator')->get('programs.index.col_name'); ?></th><th><?php echo app('translator')->get('programs.index.col_code'); ?></th><th><?php echo app('translator')->get('programs.index.col_department'); ?></th><th><?php echo app('translator')->get('programs.index.col_duration'); ?></th><th><?php echo app('translator')->get('programs.index.col_batches'); ?></th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($p->name, 0, 1)); ?></span>
                                <span class="fw-semibold"><?php echo e($p->name); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($p->code ?? '—'); ?></td>
                        <td><?php echo e($p->department?->name ?? '—'); ?></td>
                        <td><?php echo e($p->duration_years ?? '—'); ?></td>
                        <td><span class="badge badge-soft"><?php echo e($p->batches_count); ?></span></td>
                        <td class="text-start">
                            <div class="d-flex gap-1">
                                <a href="<?php echo e(route('admin.programs.edit', $p)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="<?php echo e(route('admin.programs.destroy', $p)); ?>" class="d-inline" onsubmit="return confirm('<?php echo e(__('programs.index.confirm_delete')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6"><div class="empty-state"><i class="bi bi-award"></i><p><?php echo app('translator')->get('programs.index.empty'); ?></p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/programs/index.blade.php ENDPATH**/ ?>