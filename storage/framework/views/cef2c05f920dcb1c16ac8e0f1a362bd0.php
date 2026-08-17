<?php $__env->startSection('title', __('attendance.index.title')); ?>
<?php $__env->startSection('page', __('attendance.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('attendance.index.title'); ?></h1>
        <p class="text-muted mb-0"><?php echo app('translator')->get('attendance.index.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.attendance.monthly')); ?>" class="btn btn-outline-primary"><i class="bi bi-calendar-month me-1"></i> <?php echo app('translator')->get('attendance.index.monthly_report'); ?></a>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-2 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
                <div><div class="stat-value num"><?php echo e(number_format($summary['present_today'])); ?></div><div class="stat-label"><?php echo app('translator')->get('attendance.index.present_today'); ?></div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-5 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
                <div><div class="stat-value num"><?php echo e(number_format($summary['absent_today'])); ?></div><div class="stat-label"><?php echo app('translator')->get('attendance.index.absent_today'); ?></div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-4 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
                <div><div class="stat-value num"><?php echo e(number_format($summary['late_today'])); ?></div><div class="stat-label"><?php echo app('translator')->get('attendance.index.late_today'); ?></div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-1 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-clipboard-check"></i></div>
                <div><div class="stat-value num"><?php echo e(number_format($summary['sheets_today'])); ?></div><div class="stat-label"><?php echo app('translator')->get('attendance.index.sheets_today'); ?></div></div>
            </div>
        </div>
    </div>
</div>


<div class="card mb-4 hoverable">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.attendance.index')); ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold"><?php echo app('translator')->get('attendance.index.batch_label'); ?></label>
                <select name="batch_id" class="form-select" onchange="this.form.submit()">
                    <option value=""><?php echo app('translator')->get('attendance.index.all_batches'); ?></option>
                    <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($batch->id); ?>" <?php if($batchId == $batch->id): echo 'selected'; endif; ?>><?php echo e($batch->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold"><?php echo app('translator')->get('attendance.index.date_label'); ?></label>
                <input type="date" name="date" class="form-control" value="<?php echo e($date); ?>" onchange="this.form.submit()">
            </div>
        </form>
    </div>
</div>


<div class="row g-3">
    <div class="col-xl-5">
        <div class="card hoverable">
            <div class="card-header fw-bold"><?php echo app('translator')->get('attendance.index.sessions_day', ['date' => \Carbon\Carbon::parse($date)->translatedFormat('l j F Y')]); ?></div>
            <div class="table-responsive">
                <table class="table table-edb mb-0">
                    <thead><tr><th><?php echo app('translator')->get('attendance.index.time'); ?></th><th><?php echo app('translator')->get('attendance.batch'); ?></th><th><?php echo app('translator')->get('attendance.index.subject'); ?></th><th><?php echo app('translator')->get('attendance.state'); ?></th><th></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="num" style="white-space:nowrap">
                                    <?php echo e($s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('g:i A') : '—'); ?>

                                </td>
                                <td class="fw-semibold"><?php echo e($s->batch?->name ?? '—'); ?></td>
                                <td><?php echo e($s->subject?->name ?? $s->course?->name ?? '—'); ?></td>
                                <td>
                                    <?php $st = ['planned' => 'info', 'done' => 'success', 'cancelled' => 'danger']; ?>
                                    <span class="badge badge-soft-<?php echo e($st[$s->state] ?? 'secondary'); ?>"><?php echo e($s->state); ?></span>
                                </td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('admin.attendance.index', ['session_id' => $s->id, 'batch_id' => $batchId, 'date' => $date])); ?>"
                                       class="btn btn-sm btn-outline-primary"><?php echo app('translator')->get('attendance.index.record'); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-calendar-x"></i><p><?php echo app('translator')->get('attendance.index.no_sessions'); ?></p><small><?php echo app('translator')->get('attendance.index.no_sessions_hint'); ?></small></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <?php if($sheet): ?>
            <div class="card hoverable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><?php echo app('translator')->get('attendance.index.record_header', ['session' => $session?->subject?->name ?? $session?->course?->name ?? __('attendance.session')]); ?>
                        <span class="badge badge-soft ms-2"><?php echo e($sheet->batch?->name); ?></span>
                    </span>
                    <span class="badge badge-soft-<?php echo e($sheet->state === 'done' ? 'success' : 'warning'); ?>"><?php echo e($sheet->state === 'done' ? __('attendance.status.recorded') : __('attendance.status.draft')); ?></span>
                </div>
                <form method="POST" action="<?php echo e(route('admin.attendance.mark', $sheet)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="table-responsive">
                        <table class="table table-edb mb-0">
                            <thead><tr><th><?php echo app('translator')->get('attendance.student'); ?></th><th><?php echo app('translator')->get('attendance.state'); ?></th></tr></thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $sheet->lines()->with('student')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="avatar grad-<?php echo e(($line->id % 6) + 1); ?> avatar-sm"><?php echo e(mb_substr($line->student?->full_name ?? '?', 0, 1)); ?></span>
                                                <span class="fw-semibold"><?php echo e($line->student?->full_name ?? '—'); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <?php $__currentLoopData = ['present' => __('attendance.status.present'), 'absent' => __('attendance.status.absent'), 'late' => __('attendance.status.late'), 'leave' => __('attendance.status.leave')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $checked = old("statuses.{$line->student_id}", $line->status) === $val;
                                                        $btnClass = match ($val) {
                                                            'present' => $checked ? 'btn-success' : 'btn-outline-success',
                                                            'absent' => $checked ? 'btn-danger' : 'btn-outline-danger',
                                                            'late' => $checked ? 'btn-warning' : 'btn-outline-warning',
                                                            'leave' => $checked ? 'btn-info' : 'btn-outline-info',
                                                        };
                                                    ?>
                                                    <button type="button" class="btn <?php echo e($btnClass); ?> status-btn"
                                                            data-status="<?php echo e($val); ?>"><?php echo e($label); ?></button>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <input type="hidden" name="statuses[<?php echo e($line->student_id); ?>]"
                                                   value="<?php echo e(old("statuses.{$line->student_id}", $line->status)); ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="2"><div class="empty-state"><i class="bi bi-people"></i><p><?php echo app('translator')->get('attendance.index.no_students'); ?></p></div></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary" <?php echo e($sheet->lines->isEmpty() ? 'disabled' : ''); ?>>
                            <i class="bi bi-check2-circle me-1"></i> <?php echo app('translator')->get('attendance.index.save'); ?>
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="card hoverable">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="bi bi-clipboard-check"></i>
                        <p><?php echo app('translator')->get('attendance.index.select_session'); ?></p>
                        <small><?php echo app('translator')->get('attendance.index.select_session_hint'); ?></small>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            const group = btn.closest('.btn-group');
            const hidden = group.querySelector('input[type=hidden]');
            const status = btn.dataset.status;

            hidden.value = status;
            group.querySelectorAll('.status-btn').forEach(b => {
                b.className = 'btn ' + ({
                    present: b.dataset.status === 'present' ? 'btn-success' : 'btn-outline-success',
                    absent: b.dataset.status === 'absent' ? 'btn-danger' : 'btn-outline-danger',
                    late: b.dataset.status === 'late' ? 'btn-warning' : 'btn-outline-warning',
                    leave: b.dataset.status === 'leave' ? 'btn-info' : 'btn-outline-info',
                })[b.dataset.status];
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/attendance/index.blade.php ENDPATH**/ ?>