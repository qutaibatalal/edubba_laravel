<?php $__env->startSection('title', __('timetable.index.title')); ?>
<?php $__env->startSection('page', __('timetable.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('timetable.index.title'); ?></h1>
        <p class="text-muted mb-0"><?php echo app('translator')->get('timetable.index.subtitle'); ?></p>
    </div>
</div>


<?php if($conflicts->isNotEmpty()): ?>
    <div class="card mb-4 border-danger hoverable">
        <div class="card-header d-flex justify-content-between align-items-center text-danger">
            <span class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo app('translator')->get('timetable.index.conflicts', ['count' => $conflicts->count()]); ?></span>
        </div>
        <div class="table-responsive">
            <table class="table table-edb mb-0">
                <thead><tr><th><?php echo app('translator')->get('timetable.index.th_type'); ?></th><th><?php echo app('translator')->get('timetable.index.th_item'); ?></th><th><?php echo app('translator')->get('timetable.index.th_day'); ?></th><th><?php echo app('translator')->get('timetable.index.th_time'); ?></th><th><?php echo app('translator')->get('timetable.index.th_conflicting_sessions'); ?></th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $conflicts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <span class="badge badge-soft-<?php echo e($c->type === 'faculty' ? 'danger' : ($c->type === 'classroom' ? 'warning' : 'info')); ?>">
                                    <?php echo e(match ($c->type) { 'faculty' => __('timetable.index.type_faculty'), 'classroom' => __('timetable.index.type_classroom'), 'batch' => __('timetable.index.type_batch'), default => $c->type }); ?>

                                </span>
                            </td>
                            <td class="fw-semibold"><?php echo e($c->label); ?></td>
                            <td><?php echo e($c->day); ?></td>
                            <td class="num"><?php echo e($c->timing); ?></td>
                            <td class="small text-muted num"><?php echo e($c->lines); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-success py-2 small">
        <i class="bi bi-check-circle-fill me-1"></i> <?php echo app('translator')->get('timetable.index.no_conflicts'); ?>
    </div>
<?php endif; ?>


<div class="card mb-4 hoverable">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold"><?php echo app('translator')->get('timetable.index.week_starts'); ?></label>
                <input type="date" name="week_start" value="<?php echo e($weekStart); ?>" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-auto pb-1">
                <span class="badge badge-soft-primary"><i class="bi bi-mortarboard me-1"></i> <?php echo app('translator')->get('timetable.index.week_sessions_count', ['count' => $sessions->count()]); ?></span>
            </div>
        </form>
    </div>
</div>


<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-bold"><i class="bi bi-calendar2-week me-2 text-primary"></i> <?php echo e($weekStart); ?> — <?php echo e($weekEnd); ?></span>
        <form method="POST" action="<?php echo e(route('admin.timetable.generate')); ?>" class="d-flex align-items-center gap-2">
            <?php echo csrf_field(); ?>
            <input type="date" name="date" value="<?php echo e($weekStart); ?>" class="form-control form-control-sm" style="width:auto">
            <button class="btn btn-sm btn-success"><i class="bi bi-calendar-plus me-1"></i> <?php echo app('translator')->get('timetable.index.generate_day'); ?></button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead>
                <tr>
                    <th style="min-width:130px"><?php echo app('translator')->get('timetable.index.th_day'); ?></th>
                    <th><?php echo app('translator')->get('timetable.index.th_sessions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $weekDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayIndex => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="fw-bold">
                            <?php echo e(\Carbon\Carbon::parse($weekStart)->addDays($dayIndex)->translatedFormat('l')); ?>

                            <div class="small text-secondary num"><?php echo e(\Carbon\Carbon::parse($weekStart)->addDays($dayIndex)->format('d/m')); ?></div>
                        </td>
                        <td>
                            <?php $daySessions = $sessions->where('date', \Carbon\Carbon::parse($weekStart)->addDays($dayIndex)->toDateString()); ?>
                            <?php $__empty_1 = true; $__currentLoopData = $daySessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <span class="badge badge-soft-info me-1 mb-1 d-inline-flex align-items-center gap-1" style="font-size:.8rem;font-weight:600">
                                    <?php echo e($s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('g:i A') : ''); ?>

                                    <?php echo e($s->batch?->name); ?> — <?php echo e($s->subject?->name ?? $s->course?->name); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="small text-secondary"><?php echo app('translator')->get('timetable.index.no_sessions'); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/timetable/index.blade.php ENDPATH**/ ?>