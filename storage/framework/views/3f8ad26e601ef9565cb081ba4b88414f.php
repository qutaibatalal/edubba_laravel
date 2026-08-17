<?php $__env->startSection('title', $exam->name); ?>
<?php $__env->startSection('page', __('exams.show.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo e($exam->name); ?></h1>
        <p class="text-secondary mb-0">
            <?php echo e($exam->examType?->name ?? __('exams.show.exam')); ?> ·
            <?php echo e($exam->batch?->name ?? __('exams.index.all_batches')); ?> ·
            <?php if($exam->date_start): ?> <?php echo e($exam->date_start->format('Y-m-d')); ?> → <?php echo e($exam->date_end?->format('Y-m-d')); ?> <?php endif; ?>
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?php echo e(route('admin.exams.marksheets', $exam)); ?>" class="btn btn-outline-primary"><i class="bi bi-card-checklist me-1"></i> <?php echo app('translator')->get('exams.show.marksheets'); ?></a>
        <a href="<?php echo e(route('admin.exams.results', $exam)); ?>" class="btn btn-outline-primary"><i class="bi bi-bar-chart me-1"></i> <?php echo app('translator')->get('exams.show.results'); ?></a>
        <a href="<?php echo e(route('admin.exams.seating.pdf', [$exam])); ?>" class="btn btn-outline-primary"><i class="bi bi-printer me-1"></i> <?php echo app('translator')->get('exams.show.seating_pdf'); ?></a>
        <a href="<?php echo e(route('admin.exams.index')); ?>" class="btn btn-light border"><i class="bi bi-arrow-right me-1"></i> <?php echo app('translator')->get('exams.show.back'); ?></a>
    </div>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-danger py-2">
        <ul class="mb-0 small">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($e); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="bento">
    <div class="b-8">
        <div class="card hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-calendar2-week me-2 text-primary"></i> <?php echo app('translator')->get('exams.show.sessions_title'); ?></span>
                <span class="badge badge-soft-primary"><?php echo app('translator')->get('exams.show.session_count', ['count' => $schedules->count()]); ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th><?php echo app('translator')->get('exams.show.col_subject'); ?></th><th><?php echo app('translator')->get('exams.show.col_date'); ?></th><th><?php echo app('translator')->get('exams.show.col_time'); ?></th><th><?php echo app('translator')->get('exams.show.col_marks'); ?></th><th><?php echo app('translator')->get('exams.show.col_distribution'); ?></th><th></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $allocCount = $exam->roomAllocations->where('exam_schedule_id', $schedule->id)->count();
                                $attended = $exam->roomAllocations->where('exam_schedule_id', $schedule->id)->whereNotNull('attended')->count();
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?php echo e($schedule->subject?->name ?? $schedule->course?->name ?? '—'); ?></div>
                                    <small class="text-secondary"><?php echo e($schedule->subject ? $schedule->course?->name : ''); ?></small>
                                </td>
                                <td class="num"><?php echo e($schedule->date->format('Y-m-d')); ?></td>
                                <td class="num"><?php echo e($schedule->start_time ? substr($schedule->start_time, 0, 5) : '—'); ?> → <?php echo e($schedule->end_time ? substr($schedule->end_time, 0, 5) : '—'); ?></td>
                                <td class="num"><?php echo e($schedule->max_marks); ?> <small class="text-secondary"><?php echo app('translator')->get('exams.show.pass_marks', ['marks' => $schedule->pass_marks]); ?></small></td>
                                <td>
                                    <?php if($allocCount): ?>
                                        <span class="badge badge-soft-success"><?php echo app('translator')->get('exams.show.student_count', ['count' => $allocCount]); ?></span>
                                        <span class="badge badge-soft-info"><?php echo app('translator')->get('exams.show.attended_count', ['count' => $attended]); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-soft-secondary"><?php echo app('translator')->get('exams.show.not_distributed'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <form method="POST" action="<?php echo e(route('admin.exams.distribute', $exam)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="exam_schedule_id" value="<?php echo e($schedule->id); ?>">
                                            <button class="btn btn-sm btn-outline-primary" <?php echo e($allocCount ? 'disabled' : ''); ?> title="<?php echo e($allocCount ? __('exams.show.distributed_already') : __('exams.show.distribute_title')); ?>"><i class="bi bi-grid-3x3-gap"></i> <?php echo app('translator')->get('exams.show.distribute'); ?></button>
                                        </form>
                                        <a class="btn btn-sm btn-light border" href="<?php echo e(route('admin.exams.seating.pdf', [$exam, $schedule->id])); ?>" title="<?php echo app('translator')->get('exams.show.seating_sheet'); ?>"><i class="bi bi-printer"></i></a>
                                        <form method="POST" action="<?php echo e(route('admin.exams.schedule.destroy', [$exam, $schedule])); ?>" onsubmit="return confirm('<?php echo e(__('exams.show.confirm_delete_session')); ?>')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6"><div class="empty-state"><i class="bi bi-calendar-x"></i><p><?php echo app('translator')->get('exams.show.empty_title'); ?></p><small><?php echo app('translator')->get('exams.show.empty_hint'); ?></small></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="b-4">
        <div class="card hoverable">
            <div class="card-header fw-semibold"><i class="bi bi-plus-circle me-2 text-primary"></i> <?php echo app('translator')->get('exams.show.add_session'); ?></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.exams.schedule.store', $exam)); ?>" class="row g-3">
                    <?php echo csrf_field(); ?>
                    <div class="col-12">
                        <label class="form-label"><?php echo app('translator')->get('exams.show.form_subject'); ?></label>
                        <select name="subject_id" class="form-select">
                            <option value="">—</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label"><?php echo app('translator')->get('exams.show.form_course'); ?></label>
                        <select name="course_id" class="form-select">
                            <option value="">—</option>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label"><?php echo app('translator')->get('exams.show.form_date'); ?></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="col-3">
                        <label class="form-label"><?php echo app('translator')->get('exams.show.form_from'); ?></label>
                        <input type="time" name="start_time" class="form-control">
                    </div>
                    <div class="col-3">
                        <label class="form-label"><?php echo app('translator')->get('exams.show.form_to'); ?></label>
                        <input type="time" name="end_time" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label"><?php echo app('translator')->get('exams.show.form_max_marks'); ?></label>
                        <input type="number" name="max_marks" class="form-control" min="0" step="0.5" value="100">
                    </div>
                    <div class="col-6">
                        <label class="form-label"><?php echo app('translator')->get('exams.show.form_pass_marks'); ?></label>
                        <input type="number" name="pass_marks" class="form-control" min="0" step="0.5" value="50">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary w-100"><?php echo app('translator')->get('exams.show.add_session'); ?></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card hoverable mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i> <?php echo app('translator')->get('exams.show.distribution_title'); ?></span>
                <?php if($exam->roomAllocations->isEmpty()): ?>
                    <form method="POST" action="<?php echo e(route('admin.exams.distribute', $exam)); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-sm btn-success"><i class="bi bi-magic me-1"></i> <?php echo app('translator')->get('exams.show.auto_distribute'); ?></button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php
                    $total = $exam->roomAllocations->count();
                    $present = $exam->roomAllocations->where('attended', true)->count();
                    $absent = $exam->roomAllocations->where('attended', false)->count();
                ?>
                <?php if($total): ?>
                    <div class="d-flex gap-3 flex-wrap">
                        <span class="badge badge-soft-primary"><?php echo app('translator')->get('exams.show.total', ['count' => $total]); ?></span>
                        <span class="badge badge-soft-success"><?php echo app('translator')->get('exams.show.present', ['count' => $present]); ?></span>
                        <span class="badge badge-soft-danger"><?php echo app('translator')->get('exams.show.absent', ['count' => $absent]); ?></span>
                        <span class="badge badge-soft-secondary"><?php echo app('translator')->get('exams.show.no_record', ['count' => $total - $present - $absent]); ?></span>
                    </div>
                    <div class="mt-3 progress" style="height:8px">
                        <div class="progress-bar bg-success" style="width: <?php echo e($total ? round($present / $total * 100) : 0); ?>%"></div>
                        <div class="progress-bar bg-danger" style="width: <?php echo e($total ? round($absent / $total * 100) : 0); ?>%"></div>
                    </div>
                <?php else: ?>
                    <p class="text-secondary small mb-0"><?php echo app('translator')->get('exams.show.no_distribution'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $groups = $distribution->get($schedule->id, collect());
        $allocations = $exam->roomAllocations->where('exam_schedule_id', $schedule->id);
    ?>
        <?php if($groups->isNotEmpty()): ?>
        <div class="card hoverable mt-4">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="fw-semibold"><i class="bi bi-person-vcard me-2 text-primary"></i> <?php echo app('translator')->get('exams.show.session_distribution', ['subject' => $schedule->subject?->name ?? $schedule->course?->name ?? '', 'date' => $schedule->date->format('Y-m-d')]); ?></span>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge badge-soft-primary"><?php echo app('translator')->get('exams.show.student_count', ['count' => $allocations->count()]); ?></span>
                    <?php if($allocations->whereNull('attended')->isNotEmpty()): ?>
                        <form method="POST" action="<?php echo e(route('admin.exams.held', $exam)); ?>" class="d-flex gap-2">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="exam_schedule_id" value="<?php echo e($schedule->id); ?>">
                            <button class="btn btn-sm btn-success" type="button" onclick="markAll(this, true)"><?php echo app('translator')->get('exams.show.all_present'); ?></button>
                            <button class="btn btn-sm btn-outline-danger" type="button" onclick="markAll(this, false)"><?php echo app('translator')->get('exams.show.all_absent'); ?></button>
                            <button class="btn btn-sm btn-primary"><i class="bi bi-check-lg"></i> <?php echo app('translator')->get('exams.show.record_attendance'); ?></button>
                        </form>
                    <?php else: ?>
                        <span class="badge badge-soft-success"><?php echo app('translator')->get('exams.show.attendance_recorded'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <?php if($allocations->whereNull('attended')->isNotEmpty()): ?>
                    <form method="POST" action="<?php echo e(route('admin.exams.held', $exam)); ?>" class="mb-3">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="exam_schedule_id" value="<?php echo e($schedule->id); ?>">
                        <div class="row g-2 align-items-center">
                            <?php $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4 col-lg-3">
                                    <div class="border rounded-3 p-2 d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="attended[<?php echo e($allocation->id); ?>]" value="1" checked>
                                        <span class="fw-semibold small"><?php echo e($allocation->student?->name); ?></span>
                                        <span class="badge badge-soft-secondary num ms-auto"><?php echo e($allocation->room?->code ?? $allocation->room?->name); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <button class="btn btn-primary mt-3"><i class="bi bi-check-lg me-1"></i> <?php echo app('translator')->get('exams.show.record_attendance'); ?></button>
                    </form>
                <?php endif; ?>

                <div class="row g-3">
                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomId => $students): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $room = $students->first()->examRoom; ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100">
                                <div class="card-header py-2 d-flex justify-content-between">
                                    <span class="small"><i class="bi bi-easel me-1 text-primary"></i> <?php echo e($room?->name ?? __('exams.show.room')); ?></span>
                                    <span class="badge badge-soft-info num"><?php echo e($students->count()); ?> / <?php echo e($room?->capacity); ?></span>
                                </div>
                                <div class="card-body py-2">
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="d-flex justify-content-between align-items-center border-bottom py-1" style="border-color: var(--edb-border) !important">
                                            <span class="small">
                                                <span class="badge badge-soft-secondary num me-1"><?php echo app('translator')->get('exams.show.seat', ['seat' => $s->seat_no]); ?></span>
                                                <?php echo e($s->student?->name); ?>

                                            </span>
                                            <?php
                                                $badge = $s->attended === null ? 'secondary' : ($s->attended ? 'success' : 'danger');
                                                $icon = $s->attended === null ? 'minus' : ($s->attended ? 'check-lg' : 'x-lg');
                                            ?>
                                            <span class="badge badge-soft-<?php echo e($badge); ?>"><i class="bi bi-<?php echo e($icon); ?>"></i></span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($distribution->has(0)): ?>
    <?php $groups = $distribution->get(0); ?>
    <div class="card hoverable mt-4">
        <div class="card-header fw-semibold"><span><i class="bi bi-grid-3x3-gap me-2 text-primary"></i> <?php echo app('translator')->get('exams.show.no_session_distribution'); ?></span></div>
        <div class="card-body">
            <div class="row g-3">
                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomId => $students): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $room = $students->first()->examRoom; ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-header py-2"><span class="small"><i class="bi bi-easel me-1 text-primary"></i> <?php echo e($room?->name); ?></span></div>
                            <div class="card-body py-2">
                                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-1" style="border-color: var(--edb-border) !important">
                                        <span class="small"><span class="badge badge-soft-secondary num me-1"><?php echo app('translator')->get('exams.show.seat', ['seat' => $s->seat_no]); ?></span><?php echo e($s->student?->name); ?></span>
                                        <span class="badge badge-soft-<?php echo e($s->attended === null ? 'secondary' : ($s->attended ? 'success' : 'danger')); ?>">
                                            <i class="bi bi-<?php echo e($s->attended === null ? 'minus' : ($s->attended ? 'check-lg' : 'x-lg')); ?>"></i>
                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function markAll(btn, present) {
        const form = btn.closest('form');
        form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = present);
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/exams/show.blade.php ENDPATH**/ ?>