<?php $__env->startSection('title', __('exams.marksheets.title', ['name' => $exam->name])); ?>
<?php $__env->startSection('page', __('exams.marksheets.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('exams.marksheets.heading'); ?></h1>
        <p class="text-secondary mb-0"><?php echo e($exam->name); ?> · <?php echo e($exam->batch?->name ?? __('exams.index.all_batches')); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.exams.results', $exam)); ?>" class="btn btn-outline-primary"><i class="bi bi-bar-chart me-1"></i> <?php echo app('translator')->get('exams.show.results'); ?></a>
        <a href="<?php echo e(route('admin.exams.show', $exam)); ?>" class="btn btn-light border"><i class="bi bi-arrow-right me-1"></i> <?php echo app('translator')->get('exams.show.back'); ?></a>
    </div>
</div>

<?php
    $draftCount = $marksheets->where('state', 'draft')->count();
    $doneCount = $marksheets->where('state', 'done')->count();
?>

<div class="card hoverable mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <span class="badge badge-soft-primary"><?php echo app('translator')->get('exams.marksheets.marksheets_count', ['count' => $marksheets->count()]); ?></span>
            </div>
            <div class="col-auto">
                <span class="badge badge-soft-warning"><?php echo app('translator')->get('exams.marksheets.draft_count', ['count' => $draftCount]); ?></span>
            </div>
            <div class="col-auto">
                <span class="badge badge-soft-success"><?php echo app('translator')->get('exams.marksheets.approved_count', ['count' => $doneCount]); ?></span>
            </div>
            <div class="col-auto">
                <span class="badge badge-soft-info"><?php echo app('translator')->get('exams.marksheets.eligible_count', ['count' => $eligible->count()]); ?></span>
            </div>
            <div class="ms-auto d-flex gap-2">
                <form method="POST" action="<?php echo e(route('admin.exams.marksheets.generate', $exam)); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-primary" <?php echo e($eligible->isEmpty() ? 'disabled' : ''); ?>><i class="bi bi-magic me-1"></i> <?php echo app('translator')->get('exams.marksheets.generate'); ?></button>
                </form>
                <form method="POST" action="<?php echo e(route('admin.exams.marksheets.finalize-all', $exam)); ?>" onsubmit="return confirm('<?php echo e(__('exams.marksheets.confirm_finalize_all')); ?>')">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-success" <?php echo e($draftCount === 0 ? 'disabled' : ''); ?>><i class="bi bi-check2-all me-1"></i> <?php echo app('translator')->get('exams.marksheets.finalize_all'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-card-checklist me-2 text-primary"></i> <?php echo app('translator')->get('exams.marksheets.list_title', ['batch' => $exam->batch?->name ?? __('exams.show.exam')]); ?></span>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th><?php echo app('translator')->get('exams.marksheets.col_student'); ?></th><th><?php echo app('translator')->get('exams.marksheets.col_number'); ?></th><th class="num"><?php echo app('translator')->get('exams.marksheets.col_marks'); ?></th><th class="num"><?php echo app('translator')->get('exams.marksheets.col_percentage'); ?></th><th><?php echo app('translator')->get('exams.marksheets.col_grade'); ?></th><th><?php echo app('translator')->get('exams.marksheets.col_result'); ?></th><th><?php echo app('translator')->get('exams.marksheets.col_rank'); ?></th><th><?php echo app('translator')->get('exams.marksheets.col_status'); ?></th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $marksheets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($ms->student?->name ?? '—', 0, 1)); ?></span>
                                <span class="fw-semibold"><?php echo e($ms->student?->full_name ?? '—'); ?></span>
                            </div>
                        </td>
                        <td class="num"><?php echo e($ms->student?->roll_no ?? $ms->student?->student_code ?? '—'); ?></td>
                        <td class="num"><?php echo e($ms->obtained_marks); ?> / <?php echo e($ms->total_marks); ?></td>
                        <td class="num"><?php echo e($ms->percentage); ?>%</td>
                        <td><span class="badge badge-soft-primary"><?php echo e($ms->grade ?: '—'); ?></span></td>
                        <td>
                            <span class="badge badge-soft-<?php echo e($ms->result === 'pass' ? 'success' : 'danger'); ?>"><?php echo e($ms->result === 'pass' ? __('exams.marksheets.pass') : __('exams.marksheets.fail')); ?></span>
                        </td>
                        <td class="num"><?php echo e($ms->rank ?: '—'); ?></td>
                        <td>
                            <span class="badge badge-soft-<?php echo e($ms->is_finalized ? 'success' : 'warning'); ?>"><?php echo e($ms->is_finalized ? __('exams.marksheets.approved') : __('exams.marksheets.draft')); ?></span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="<?php echo e(route('admin.exams.marksheet', [$exam, $ms])); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> <?php echo app('translator')->get('exams.marksheets.marks'); ?></a>
                                <?php if(! $ms->is_finalized): ?>
                                    <form method="POST" action="<?php echo e(route('admin.exams.marksheet.finalize', [$exam, $ms])); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-sm btn-outline-success"><i class="bi bi-check2"></i></button>
                                    </form>
                                <?php else: ?>
                                    <a href="<?php echo e(route('admin.exams.result.card', [$exam, $ms->student_id])); ?>" class="btn btn-sm btn-light border" title="<?php echo app('translator')->get('exams.marksheets.result_card'); ?>"><i class="bi bi-printer"></i></a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="9"><div class="empty-state"><i class="bi bi-card-checklist"></i><p><?php echo app('translator')->get('exams.marksheets.empty_title'); ?></p><small><?php echo app('translator')->get('exams.marksheets.empty_hint'); ?></small></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/exams/marksheets.blade.php ENDPATH**/ ?>