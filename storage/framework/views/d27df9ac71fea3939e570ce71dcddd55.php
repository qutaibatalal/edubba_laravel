<?php $__env->startSection('title', __('exams.marksheet.title', ['name' => $marksheet->student?->full_name])); ?>
<?php $__env->startSection('page', __('exams.marksheet.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('exams.marksheet.heading', ['name' => $marksheet->student?->full_name]); ?></h1>
        <p class="text-secondary mb-0"><?php echo e($exam->name); ?> · <?php echo e($marksheet->student?->batch?->name ?? ''); ?></p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge badge-soft-<?php echo e($marksheet->is_finalized ? 'success' : 'warning'); ?> align-self-center"><?php echo e($marksheet->is_finalized ? __('exams.marksheets.approved') : __('exams.marksheets.draft')); ?></span>
        <a href="<?php echo e(route('admin.exams.marksheets', $exam)); ?>" class="btn btn-light border"><i class="bi bi-arrow-right me-1"></i> <?php echo app('translator')->get('exams.show.back'); ?></a>
    </div>
</div>

<?php if($marksheet->is_finalized): ?>
    <div class="alert alert-success py-2 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> <?php echo app('translator')->get('exams.marksheet.finalized_notice'); ?>
    </div>
<?php endif; ?>

<div class="bento">
    <div class="b-8">
        <div class="card hoverable">
            <div class="card-header fw-semibold"><span><i class="bi bi-journal-text me-2 text-primary"></i> <?php echo app('translator')->get('exams.marksheet.grades_title'); ?></span></div>
            <form method="POST" action="<?php echo e(route('admin.exams.marksheet.store', [$exam, $marksheet])); ?>">
                <?php echo csrf_field(); ?>
                <div class="table-responsive">
                    <table class="table table-edb mb-0 align-middle">
                        <thead><tr><th><?php echo app('translator')->get('exams.marksheet.col_subject'); ?></th><th class="num"><?php echo app('translator')->get('exams.marksheet.col_max'); ?></th><th class="num"><?php echo app('translator')->get('exams.marksheet.col_pass'); ?></th><th style="width:180px" class="num"><?php echo app('translator')->get('exams.marksheet.col_marks'); ?></th><th class="num"><?php echo app('translator')->get('exams.marksheet.col_percentage'); ?></th><th><?php echo app('translator')->get('exams.marksheet.col_grade'); ?></th><th><?php echo app('translator')->get('exams.marksheet.col_status'); ?></th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $marksheet->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($line->subject?->name ?? '—'); ?></div>
                                        <small class="text-secondary"><?php echo e($line->course?->name); ?></small>
                                    </td>
                                    <td class="num"><?php echo e($line->max_marks); ?></td>
                                    <td class="num"><?php echo e($line->pass_marks); ?></td>
                                    <td>
                                        <input type="number" name="lines[<?php echo e($line->id); ?>]" step="0.5" min="0" value="<?php echo e($line->marks); ?>" class="form-control num" <?php echo e($marksheet->is_finalized ? 'disabled' : ''); ?>>
                                    </td>
                                    <td class="num"><?php echo e($line->percentage); ?>%</td>
                                    <td><span class="badge badge-soft-primary"><?php echo e($line->grade ?: '—'); ?></span></td>
                                    <td>
                                        <span class="badge badge-soft-<?php echo e($line->passed ? 'success' : 'danger'); ?>"><?php echo e($line->passed ? __('exams.marksheets.pass') : __('exams.marksheets.fail')); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex gap-2">
                        <span class="badge badge-soft-primary num"><?php echo e($marksheet->obtained_marks); ?> / <?php echo e($marksheet->total_marks); ?></span>
                        <span class="badge badge-soft-info num"><?php echo e($marksheet->percentage); ?>%</span>
                        <span class="badge badge-soft-purple"><?php echo e($marksheet->grade ?: '—'); ?></span>
                        <?php if($marksheet->rank): ?> <span class="badge badge-soft-success num"><?php echo app('translator')->get('exams.marksheet.rank', ['rank' => $marksheet->rank]); ?></span> <?php endif; ?>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if(! $marksheet->is_finalized): ?>
                            <button class="btn btn-primary"><i class="bi bi-save me-1"></i> <?php echo app('translator')->get('exams.marksheet.save_marks'); ?></button>
                            <a href="<?php echo e(route('admin.exams.marksheet.finalize', [$exam, $marksheet])); ?>" class="btn btn-success" onclick="event.preventDefault(); if(confirm('<?php echo e(__('exams.marksheet.confirm_finalize')); ?>')) document.getElementById('finalize-form').submit();"><?php echo app('translator')->get('exams.marksheet.finalize'); ?> <i class="bi bi-check2-all"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
            <?php if(! $marksheet->is_finalized): ?>
                <form method="POST" action="<?php echo e(route('admin.exams.marksheet.finalize', [$exam, $marksheet])); ?>" id="finalize-form" class="d-none"><?php echo csrf_field(); ?></form>
            <?php endif; ?>
        </div>
    </div>

    <div class="b-4">
        <div class="card hoverable">
            <div class="card-header fw-semibold"><i class="bi bi-person-vcard me-2 text-primary"></i> <?php echo app('translator')->get('exams.marksheet.student_data'); ?></div>
            <div class="card-body small">
                <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('exams.marksheet.st_name'); ?></span><span class="fw-semibold"><?php echo e($marksheet->student?->full_name); ?></span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('exams.marksheet.st_number'); ?></span><span class="num"><?php echo e($marksheet->student?->roll_no ?? $marksheet->student?->student_code); ?></span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('exams.marksheet.st_batch'); ?></span><span><?php echo e($marksheet->student?->batch?->name); ?></span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('exams.marksheet.st_exam'); ?></span><span><?php echo e($exam->name); ?></span></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/exams/marksheet.blade.php ENDPATH**/ ?>