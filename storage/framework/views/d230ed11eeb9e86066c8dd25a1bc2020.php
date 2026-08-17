<?php $__env->startSection('title', __('courses.index.title')); ?>
<?php $__env->startSection('page', __('courses.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('courses.index.title'); ?></h1>
        <p><?php echo app('translator')->get('courses.index.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('courses.index.add_course'); ?></a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <select name="batch_id" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value=""><?php echo app('translator')->get('courses.index.all_batches'); ?></option>
                <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php echo e(request('batch_id') == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
        <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('courses.index.add_short'); ?></a>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th><?php echo app('translator')->get('courses.index.col_name'); ?></th><th><?php echo app('translator')->get('courses.index.col_code'); ?></th><th><?php echo app('translator')->get('courses.index.col_subject'); ?></th><th><?php echo app('translator')->get('courses.index.col_batch'); ?></th><th><?php echo app('translator')->get('courses.index.col_year'); ?></th><th><?php echo app('translator')->get('courses.index.col_teacher'); ?></th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($c->name, 0, 1)); ?></span>
                                <span class="fw-semibold"><?php echo e($c->name); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($c->code ?? '—'); ?></td>
                        <td><?php echo e($c->subject?->name ?? '—'); ?></td>
                        <td><?php echo e($c->batch?->name ?? '—'); ?></td>
                        <td><?php echo e($c->academicYear?->name ?? '—'); ?></td>
                        <td><?php echo e($c->faculty?->full_name ?? '—'); ?></td>
                        <td class="text-start">
                            <div class="d-flex gap-1">
                                <a href="<?php echo e(route('admin.courses.edit', $c)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="<?php echo e(route('admin.courses.destroy', $c)); ?>" class="d-inline" onsubmit="return confirm('<?php echo e(__('courses.index.confirm_delete')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-book"></i><p><?php echo app('translator')->get('courses.index.empty'); ?></p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($courses->hasPages()): ?>
        <div class="card-footer"><?php echo e($courses->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/courses/index.blade.php ENDPATH**/ ?>