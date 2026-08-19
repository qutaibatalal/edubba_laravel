<?php $__env->startSection('title', __('admissions.index.title')); ?>
<?php $__env->startSection('page', __('admissions.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('admissions.index.heading'); ?></h1>
        <p><?php echo app('translator')->get('admissions.index.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.admissions.create')); ?>" class="btn btn-primary"><i class="bi bi-clipboard2-plus me-1"></i> <?php echo app('translator')->get('admissions.index.add_new'); ?></a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="state" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value=""><?php echo app('translator')->get('admissions.index.all_states'); ?></option>
                <?php $__currentLoopData = ['draft', 'submit', 'approve', 'reject', 'admitted']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st); ?>" <?php echo e(request('state') === $st ? 'selected' : ''); ?>><?php echo e($st); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
        <span class="badge badge-soft-primary"><?php echo app('translator')->get('admissions.index.total_count', ['count' => $admissions->total()]); ?></span>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th><?php echo app('translator')->get('admissions.index.th_number'); ?></th><th><?php echo app('translator')->get('admissions.index.th_name'); ?></th><th><?php echo app('translator')->get('admissions.index.th_batch'); ?></th><th><?php echo app('translator')->get('admissions.index.th_program'); ?></th><th><?php echo app('translator')->get('admissions.index.th_state'); ?></th><th class="text-start"><?php echo app('translator')->get('admissions.index.th_actions'); ?></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $admissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge badge-soft"><?php echo e($a->application_no); ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($a->name ?? '?', 0, 1)); ?></span>
                                <span class="fw-semibold"><?php echo e($a->full_name); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($a->batch?->name ?? '—'); ?></td>
                        <td><?php echo e($a->program?->name ?? '—'); ?></td>
                        <td>
                            <?php $b = ['draft' => 'secondary', 'submit' => 'info', 'approve' => 'success', 'reject' => 'danger', 'admitted' => 'primary']; ?>
                            <span class="badge badge-soft-<?php echo e($b[$a->state] ?? 'secondary'); ?>"><?php echo e($a->state); ?></span>
                        </td>
                        <td class="text-start">
                            <div class="d-inline-flex gap-1 flex-wrap">
                                <?php if($a->state === 'draft'): ?>
                                    <form method="POST" action="<?php echo e(route('admin.admissions.submit', $a)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-info" title="<?php echo app('translator')->get('admissions.index.submit'); ?>"><i class="bi bi-send"></i></button>
                                    </form>
                                <?php endif; ?>
                                <?php if(in_array($a->state, ['submit', 'draft'])): ?>
                                    <form method="POST" action="<?php echo e(route('admin.admissions.approve', $a)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-success" title="<?php echo app('translator')->get('admissions.index.approve'); ?>"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                <?php endif; ?>
                                <?php if(in_array($a->state, ['submit', 'approve'])): ?>
                                    <form method="POST" action="<?php echo e(route('admin.admissions.reject', $a)); ?>" class="d-inline" onsubmit="return confirm('<?php echo e(__('admissions.index.reject_confirm')); ?>')">
                                        <?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-danger" title="<?php echo app('translator')->get('admissions.index.reject'); ?>"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                <?php endif; ?>
                                <?php if($a->state === 'approve'): ?>
                                    <form method="POST" action="<?php echo e(route('admin.admissions.admit', $a)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-primary" title="<?php echo app('translator')->get('admissions.index.admit'); ?>"><i class="bi bi-mortarboard"></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6"><div class="empty-state"><i class="bi bi-clipboard2-x"></i><p><?php echo app('translator')->get('admissions.index.empty'); ?></p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($admissions->hasPages()): ?>
        <div class="card-footer"><?php echo e($admissions->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qutaiba\Desktop\edubba_laravel\resources\views/admin/admissions/index.blade.php ENDPATH**/ ?>