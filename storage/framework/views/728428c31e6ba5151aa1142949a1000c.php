<?php $__env->startSection('title', __('students.show.title')); ?>
<?php $__env->startSection('page', __('students.show.page', ['name' => $student->full_name])); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo e($student->full_name); ?></h1>
        <p><?php echo app('translator')->get('students.show.subtitle', ['code' => $student->student_code]); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.students.card', $student)); ?>" class="btn btn-outline-primary" title="<?php echo app('translator')->get('students.show.card_title'); ?>"><i class="bi bi-person-vcard me-1"></i> <?php echo app('translator')->get('students.show.card'); ?></a>
        <a href="<?php echo e(route('admin.students.certificate', $student)); ?>" class="btn btn-outline-primary" title="<?php echo app('translator')->get('students.show.certificate_title'); ?>"><i class="bi bi-award me-1"></i> <?php echo app('translator')->get('students.show.certificate'); ?></a>
        <a href="<?php echo e(route('admin.students.edit', $student)); ?>" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> <?php echo app('translator')->get('students.show.edit'); ?></a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card hoverable">
            <div class="card-body text-center">
                <span class="avatar avatar-lg grad-1 mx-auto mb-3"><?php echo e(mb_substr($student->full_name, 0, 1)); ?></span>
                <h5 class="mb-1 fw-bold"><?php echo e($student->full_name); ?></h5>
                <div class="text-secondary small mb-3"><?php echo e($student->student_code); ?></div>
                <span class="badge badge-soft-<?php echo e($student->state === 'admitted' ? 'success' : ($student->state === 'graduated' ? 'primary' : ($student->state === 'alumni' ? 'purple' : 'secondary'))); ?>"><?php echo e($student->state); ?></span>
                <hr>
                <div class="text-start small">
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('students.show.batch'); ?></span><b><?php echo e($student->batch?->name ?? '—'); ?></b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('students.show.program'); ?></span><b><?php echo e($student->program?->name ?? '—'); ?></b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('students.show.year'); ?></span><b><?php echo e($student->academicYear?->name ?? '—'); ?></b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('students.show.gender'); ?></span><b><?php echo e($student->gender); ?></b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('students.show.birth_date'); ?></span><b><?php echo e($student->birth_date?->format('Y-m-d') ?? '—'); ?></b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('students.show.phone'); ?></span><b><?php echo e($student->phone ?? '—'); ?></b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary"><?php echo app('translator')->get('students.show.email'); ?></span><b><?php echo e($student->email ?? '—'); ?></b></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3 hoverable">
            <div class="card-header fw-bold"><?php echo app('translator')->get('students.show.parents_title'); ?></div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $student->parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($p->name, 0, 1)); ?></span>
                            <div><b><?php echo e($p->name); ?></b><div class="small text-secondary"><?php echo e($p->phone ?? $p->mobile); ?> — <?php echo e($p->pivot->relation); ?></div></div>
                        </div>
                        <span class="badge badge-soft-<?php echo e($p->pivot->is_main ? 'primary' : 'secondary'); ?>"><?php echo e($p->pivot->is_main ? __('students.show.main_guardian') : __('students.show.additional_guardian')); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state py-4"><i class="bi bi-person-hearts"></i><p><?php echo app('translator')->get('students.show.no_parents'); ?></p></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3 hoverable">
            <div class="card-header fw-bold"><?php echo app('translator')->get('students.show.courses_title'); ?></div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $student->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <span class="badge badge-soft-info me-1 mb-1"><?php echo e($c->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state py-4"><i class="bi bi-book"></i><p><?php echo app('translator')->get('students.show.no_courses'); ?></p></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3 hoverable">
            <div class="card-header fw-bold"><?php echo app('translator')->get('students.show.invoices_title'); ?></div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th><?php echo app('translator')->get('students.show.th_number'); ?></th><th><?php echo app('translator')->get('students.show.th_date'); ?></th><th><?php echo app('translator')->get('students.show.th_total'); ?></th><th><?php echo app('translator')->get('students.show.th_balance'); ?></th><th><?php echo app('translator')->get('students.show.th_state'); ?></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $student->invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($inv->number); ?></td>
                                <td><?php echo e($inv->date?->format('Y-m-d')); ?></td>
                                <td><?php echo e(number_format($inv->total)); ?></td>
                                <td><?php echo e(number_format($inv->balance)); ?></td>
                                <td><span class="badge badge-soft-<?php echo e($inv->state === 'paid' ? 'success' : ($inv->state === 'open' ? 'warning' : 'secondary')); ?>"><?php echo e($inv->state); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-receipt"></i><p><?php echo app('translator')->get('students.show.no_invoices'); ?></p></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><?php echo app('translator')->get('students.show.attendance_title'); ?></span>
                <span class="badge badge-soft-<?php echo e($attendancePercentage >= 90 ? 'success' : ($attendancePercentage >= 75 ? 'primary' : 'danger')); ?>">
                    <?php echo app('translator')->get('students.show.attendance_percentage', ['percent' => $attendancePercentage]); ?>
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th><?php echo app('translator')->get('students.show.th_date'); ?></th><th><?php echo app('translator')->get('students.show.th_subject'); ?></th><th><?php echo app('translator')->get('students.show.th_status'); ?></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $attendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($line->sheet?->date?->format('Y-m-d')); ?></td>
                                <td><?php echo e($line->sheet?->course?->name ?? '—'); ?></td>
                                <td>
                                    <?php $st = ['present' => 'success', 'absent' => 'danger', 'late' => 'warning', 'leave' => 'info']; ?>
                                    <span class="badge badge-soft-<?php echo e($st[$line->status] ?? 'secondary'); ?>"><?php echo e($line->status); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3"><div class="empty-state"><i class="bi bi-calendar-check"></i><p><?php echo app('translator')->get('students.show.no_attendance'); ?></p></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/students/show.blade.php ENDPATH**/ ?>