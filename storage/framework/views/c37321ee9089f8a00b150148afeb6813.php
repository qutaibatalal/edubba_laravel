<?php $__env->startSection('title', __('faculty.index.title')); ?>
<?php $__env->startSection('page', __('faculty.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('faculty.index.title'); ?></h1>
        <p><?php echo app('translator')->get('faculty.index.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.faculty.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('faculty.index.add_member'); ?></a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="<?php echo app('translator')->get('faculty.index.search_placeholder'); ?>">
            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search me-1"></i> <?php echo app('translator')->get('faculty.index.search'); ?></button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th><?php echo app('translator')->get('faculty.index.col_code'); ?></th><th><?php echo app('translator')->get('faculty.index.col_name'); ?></th><th><?php echo app('translator')->get('faculty.index.col_department'); ?></th><th><?php echo app('translator')->get('faculty.index.col_specialization'); ?></th><th><?php echo app('translator')->get('faculty.index.col_state'); ?></th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $faculty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge badge-soft-primary"><?php echo e($f->faculty_code); ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($f->full_name, 0, 1)); ?></span>
                                <a href="<?php echo e(route('admin.faculty.show', $f)); ?>" class="text-decoration-none fw-semibold"><?php echo e($f->full_name); ?></a>
                            </div>
                        </td>
                        <td><?php echo e($f->department?->name ?? '—'); ?></td>
                        <td><?php echo e($f->specialization ?? '—'); ?></td>
                        <td><span class="badge badge-soft-<?php echo e($f->state === 'joined' ? 'success' : ($f->state === 'left' ? 'danger' : 'secondary')); ?>"><?php echo e($f->state); ?></span></td>
                        <td class="text-start">
                            <a href="<?php echo e(route('admin.faculty.card', $f)); ?>" class="btn btn-sm btn-outline-secondary" title="<?php echo app('translator')->get('faculty.index.card'); ?>"><i class="bi bi-person-vcard"></i></a>
                            <a href="<?php echo e(route('admin.faculty.show', $f)); ?>" class="btn btn-sm btn-outline-primary" title="<?php echo app('translator')->get('faculty.index.view'); ?>"><i class="bi bi-eye"></i></a>
                            <a href="<?php echo e(route('admin.faculty.edit', $f)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="<?php echo e(route('admin.faculty.destroy', $f)); ?>" class="d-inline" onsubmit="return confirm('<?php echo e(__('faculty.index.confirm_delete')); ?>')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6"><div class="empty-state"><i class="bi bi-person-video3"></i><p><?php echo app('translator')->get('faculty.index.empty'); ?></p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($faculty->hasPages()): ?><div class="card-footer"><?php echo e($faculty->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/faculty/index.blade.php ENDPATH**/ ?>