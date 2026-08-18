<?php $__env->startSection('title', __('fees.structures.title')); ?>
<?php $__env->startSection('page', __('fees.structures.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('fees.structures.h1'); ?></h1>
        <p class="text-secondary mb-0"><?php echo app('translator')->get('fees.structures.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.fees.structures.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('fees.structures.new_structure'); ?></a>
    </div>
</div>

<?php $__empty_1 = true; $__currentLoopData = $structures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card hoverable mb-3">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-3">
                <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?>"><?php echo e(mb_substr($s->name, 0, 1)); ?></span>
                <div>
                    <div class="fw-bold"><?php echo e($s->name); ?></div>
                    <div class="text-secondary small">
                        <?php echo e($s->batch?->name ?? __('fees.structures.all_batches')); ?> ·
                        <?php echo e($s->program?->name ?? __('fees.structures.all_programs')); ?> ·
                        <?php echo e($s->academicYear?->name ?? '—'); ?>

                    </div>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('admin.fees.structures.generate', $s)); ?>">
                <?php echo csrf_field(); ?>
                <button class="btn btn-sm btn-success"><i class="bi bi-receipt me-1"></i> <?php echo app('translator')->get('fees.structures.generate_invoices'); ?></button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-edb mb-0 align-middle">
                <thead><tr><th><?php echo app('translator')->get('fees.structures.table_item'); ?></th><th><?php echo app('translator')->get('fees.structures.table_type'); ?></th><th class="text-end"><?php echo app('translator')->get('fees.structures.table_amount'); ?></th></tr></thead>
                <tbody>
                    <?php $__empty_2 = true; $__currentLoopData = $s->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                        <?php $typeColors = ['one_time' => 'info', 'recurring' => 'purple']; ?>
                        <tr>
                            <td><?php echo e($l->name); ?></td>
                            <td><span class="badge badge-soft-<?php echo e($typeColors[$l->type] ?? 'secondary'); ?>"><?php echo e($l->type ?? '—'); ?></span></td>
                            <td class="text-end fw-semibold"><?php echo e(number_format($l->amount)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <tr><td colspan="3"><div class="empty-state"><i class="bi bi-inbox"></i><p><?php echo app('translator')->get('fees.structures.empty_lines'); ?></p></div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card"><div class="card-body"><div class="empty-state"><i class="bi bi-cash-stack"></i><p><?php echo app('translator')->get('fees.structures.empty_structures'); ?></p></div></div></div>
<?php endif; ?>

<div class="card hoverable mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-receipt me-2"></i> <?php echo app('translator')->get('fees.structures.invoices_title'); ?></span>
        <a href="<?php echo e(route('admin.fees.invoices')); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left me-1"></i> <?php echo app('translator')->get('fees.structures.view_all'); ?></a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/fees/structures.blade.php ENDPATH**/ ?>