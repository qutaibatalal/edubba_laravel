<?php $__env->startSection('title', __('parents.index.title')); ?>
<?php $__env->startSection('page', __('parents.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('parents.index.heading'); ?></h1>
        <p class="text-secondary mb-0"><?php echo app('translator')->get('parents.index.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.parents.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('parents.index.add_new'); ?></a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="<?php echo app('translator')->get('parents.index.search_placeholder'); ?>">
            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search me-1"></i> <?php echo app('translator')->get('parents.index.search'); ?></button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th><?php echo app('translator')->get('parents.index.th_name'); ?></th><th><?php echo app('translator')->get('parents.index.th_national_id'); ?></th><th><?php echo app('translator')->get('parents.index.th_phone'); ?></th><th><?php echo app('translator')->get('parents.index.th_children_count'); ?></th><th><?php echo app('translator')->get('parents.index.th_status'); ?></th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($p->name, 0, 1)); ?></span>
                                <span><?php echo e($p->name); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($p->national_id ?? '—'); ?></td>
                        <td><?php echo e($p->phone ?? $p->mobile ?? '—'); ?></td>
                        <td><?php echo e($p->students_count); ?></td>
                        <td>
                            <?php if($p->students_count > 0): ?>
                                <span class="text-secondary small">
                                    <?php $__currentLoopData = $p->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e($s->name); ?><?php if(!$loop->last): ?>, <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-secondary small">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-start">
                            <a href="<?php echo e(route('admin.parents.edit', $p)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="<?php echo e(route('admin.parents.destroy', $p)); ?>" class="d-inline" onsubmit="return confirm('<?php echo e(__('parents.index.delete_confirm')); ?>')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6"><div class="empty-state"><i class="bi bi-people"></i><p><?php echo app('translator')->get('parents.index.empty'); ?></p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($parents->hasPages()): ?><div class="card-footer"><?php echo e($parents->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/parents/index.blade.php ENDPATH**/ ?>