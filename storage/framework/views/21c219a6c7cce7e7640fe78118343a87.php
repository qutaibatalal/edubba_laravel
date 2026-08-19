<?php $__env->startSection('title', __('students.index.title')); ?>
<?php $__env->startSection('page', __('students.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('students.index.heading'); ?></h1>
        <p><?php echo app('translator')->get('students.index.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.students.create')); ?>" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i> <?php echo app('translator')->get('students.index.add_new'); ?></a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="<?php echo app('translator')->get('students.index.search_placeholder'); ?>" style="min-width:220px">
            <select name="batch_id" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value=""><?php echo app('translator')->get('students.index.all_batches'); ?></option>
                <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php echo e(request('batch_id') == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search me-1"></i> <?php echo app('translator')->get('students.index.search'); ?></button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th><?php echo app('translator')->get('students.index.th_code'); ?></th><th><?php echo app('translator')->get('students.index.th_name'); ?></th><th><?php echo app('translator')->get('students.index.th_batch'); ?></th><th><?php echo app('translator')->get('students.index.th_year'); ?></th><th><?php echo app('translator')->get('students.index.th_parent'); ?></th><th><?php echo app('translator')->get('students.index.th_state'); ?></th><th class="text-start"><?php echo app('translator')->get('students.index.th_actions'); ?></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge badge-soft"><?php echo e($s->student_code); ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($s->name ?? '?', 0, 1)); ?></span>
                                <span class="fw-semibold"><?php echo e($s->full_name); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($s->batch?->name ?? '—'); ?></td>
                        <td><?php echo e($s->academicYear?->name ?? '—'); ?></td>
                        <td><?php echo e($s->parent?->name ?? '—'); ?></td>
                        <td>
                            <?php $b = ['draft' => 'secondary', 'admitted' => 'success', 'graduated' => 'primary', 'alumni' => 'info']; ?>
                            <span class="badge badge-soft-<?php echo e($b[$s->state] ?? 'secondary'); ?>"><?php echo e($s->state); ?></span>
                        </td>
                        <td class="text-start">
                            <div class="d-inline-flex gap-1">
                                <a href="<?php echo e(route('admin.students.show', $s)); ?>" class="btn btn-sm btn-outline-secondary" title="<?php echo app('translator')->get('students.index.view'); ?>"><i class="bi bi-eye"></i></a>
                                <a href="<?php echo e(route('admin.students.edit', $s)); ?>" class="btn btn-sm btn-outline-primary" title="<?php echo app('translator')->get('students.index.edit'); ?>"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="<?php echo e(route('admin.students.destroy', $s)); ?>" class="d-inline" onsubmit="return confirm('<?php echo e(__('students.index.delete_confirm')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger" title="<?php echo app('translator')->get('students.index.delete'); ?>"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-people"></i><p><?php echo app('translator')->get('students.index.empty'); ?></p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($students->hasPages()): ?>
        <div class="card-footer"><?php echo e($students->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qutaiba\Desktop\edubba_laravel\resources\views/admin/students/index.blade.php ENDPATH**/ ?>