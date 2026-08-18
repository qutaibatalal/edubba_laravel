<?php $__env->startSection('title', __('tutoring.index.title')); ?>
<?php $__env->startSection('page', __('tutoring.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('tutoring.index.title'); ?></h1>
        <p><?php echo app('translator')->get('tutoring.index.subtitle'); ?></p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card hoverable">
            <div class="card-header fw-bold"><i class="bi bi-people-fill me-2 text-primary"></i> <?php echo app('translator')->get('tutoring.index.study_groups'); ?></div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th><?php echo app('translator')->get('tutoring.index.th_name'); ?></th><th><?php echo app('translator')->get('tutoring.index.th_subject'); ?></th><th><?php echo app('translator')->get('tutoring.index.th_tutor'); ?></th><th><?php echo app('translator')->get('tutoring.index.th_students'); ?></th><th><?php echo app('translator')->get('tutoring.index.th_state'); ?></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($g->name, 0, 1)); ?></span>
                                        <span><?php echo e($g->name); ?></span>
                                    </div>
                                </td>
                                <td><?php echo e($g->subject?->name ?? '—'); ?></td>
                                <td><?php echo e($g->tutor?->name ?? '—'); ?></td>
                                <td><span class="badge badge-soft-primary"><?php echo e($g->students_count); ?></span></td>
                                <td><span class="badge badge-soft-<?php echo e($g->state === 'active' ? 'success' : 'secondary'); ?>"><?php echo e($g->state); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-people"></i><p><?php echo app('translator')->get('tutoring.index.no_groups'); ?></p></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card hoverable">
            <div class="card-header fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i> <?php echo app('translator')->get('tutoring.index.packages'); ?></div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th><?php echo app('translator')->get('tutoring.index.th_name'); ?></th><th><?php echo app('translator')->get('tutoring.index.th_sessions'); ?></th><th class="text-end"><?php echo app('translator')->get('tutoring.index.th_price'); ?></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($p->name, 0, 1)); ?></span>
                                        <span><?php echo e($p->name); ?></span>
                                    </div>
                                </td>
                                <td><span class="badge badge-soft-info"><?php echo app('translator')->get('tutoring.index.session_count', ['count' => $p->sessions]); ?></span></td>
                                <td class="text-end fw-semibold"><?php echo e(number_format($p->price)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3"><div class="empty-state"><i class="bi bi-box"></i><p><?php echo app('translator')->get('tutoring.index.no_packages'); ?></p></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card hoverable mt-3">
            <div class="card-header fw-bold"><i class="bi bi-lightning-charge me-2 text-primary"></i> <?php echo app('translator')->get('tutoring.index.latest_subscriptions'); ?></div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th><?php echo app('translator')->get('tutoring.index.th_student'); ?></th><th><?php echo app('translator')->get('tutoring.index.th_tutor'); ?></th><th><?php echo app('translator')->get('tutoring.index.th_remaining'); ?></th><th><?php echo app('translator')->get('tutoring.index.th_state'); ?></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($s->student?->full_name ?? '—', 0, 1)); ?></span>
                                        <span><?php echo e($s->student?->full_name ?? '—'); ?></span>
                                    </div>
                                </td>
                                <td><?php echo e($s->tutor?->name ?? '—'); ?></td>
                                <td><span class="badge badge-soft-purple"><?php echo e(max(0, $s->sessions_count - $s->sessions_used)); ?></span></td>
                                <td><span class="badge badge-soft-<?php echo e($s->state === 'active' ? 'success' : 'warning'); ?>"><?php echo e($s->state); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="4"><div class="empty-state"><i class="bi bi-lightning"></i><p><?php echo app('translator')->get('tutoring.index.no_subscriptions'); ?></p></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/tutoring/index.blade.php ENDPATH**/ ?>