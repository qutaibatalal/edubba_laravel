<?php $__env->startSection('title', __('calendar.index.title')); ?>
<?php $__env->startSection('page', __('calendar.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('calendar.index.heading'); ?></h1>
        <p class="text-muted mb-0"><?php echo app('translator')->get('calendar.index.subtitle'); ?></p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card mb-3 hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><?php echo app('translator')->get('calendar.index.official_holidays'); ?></span>
                <form method="GET" class="d-flex align-items-center gap-2">
                    <input type="month" name="month" class="form-control form-control-sm" style="width:auto" value="<?php echo e($month); ?>" onchange="this.form.submit()">
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0">
                    <thead><tr><th><?php echo app('translator')->get('calendar.index.th_date'); ?></th><th><?php echo app('translator')->get('calendar.index.th_name'); ?></th><th><?php echo app('translator')->get('calendar.index.th_hijri'); ?></th><th><?php echo app('translator')->get('calendar.index.th_holiday'); ?></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $iraqiDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="num"><?php echo e($d->gregorian_date->format('Y-m-d')); ?></td>
                                <td><?php echo e($d->iraqi_name ?? '—'); ?></td>
                                <td class="num"><?php echo e($d->hijri_date ?? '—'); ?></td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('admin.calendar.store-iraqi')); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="gregorian_date" value="<?php echo e($d->gregorian_date->format('Y-m-d')); ?>">
                                        <input type="hidden" name="iraqi_name" value="<?php echo e($d->iraqi_name); ?>">
                                        <input type="hidden" name="hijri_date" value="<?php echo e($d->hijri_date); ?>">
                                        <input type="hidden" name="is_holiday" value="<?php echo e($d->is_holiday ? 0 : 1); ?>">
                                        <button class="btn btn-sm <?php echo e($d->is_holiday ? 'btn-success' : 'btn-outline-secondary'); ?>">
                                            <?php echo e($d->is_holiday ? __('calendar.index.holiday') : __('calendar.index.regular_day')); ?>

                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="4"><div class="empty-state"><i class="bi bi-calendar-x"></i><p><?php echo app('translator')->get('calendar.index.no_data'); ?></p></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card hoverable">
            <div class="card-header fw-bold"><?php echo app('translator')->get('calendar.index.add_iraqi_day'); ?></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.calendar.store-iraqi')); ?>" class="row g-3">
                    <?php echo csrf_field(); ?>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold"><?php echo app('translator')->get('calendar.index.th_date'); ?></label>
                        <input type="date" name="gregorian_date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><?php echo app('translator')->get('calendar.index.th_name'); ?></label>
                        <input type="text" name="iraqi_name" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold"><?php echo app('translator')->get('calendar.index.hijri'); ?></label>
                        <input type="text" name="hijri_date" class="form-control" placeholder="<?php echo app('translator')->get('calendar.index.hijri_placeholder'); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_holiday" value="1" id="holidayChk" checked>
                            <label class="form-check-label" for="holidayChk"><?php echo app('translator')->get('calendar.index.holiday'); ?></label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('calendar.index.add'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card hoverable">
            <div class="card-header fw-bold"><?php echo app('translator')->get('calendar.index.school_holidays'); ?></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.calendar.store-holiday')); ?>" class="row g-3 mb-3">
                    <?php echo csrf_field(); ?>
                    <div class="col-12">
                        <label class="form-label fw-semibold"><?php echo app('translator')->get('calendar.index.holiday_name'); ?></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold"><?php echo app('translator')->get('calendar.index.from'); ?></label>
                        <input type="date" name="date_start" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold"><?php echo app('translator')->get('calendar.index.to'); ?></label>
                        <input type="date" name="date_stop" class="form-control" required>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('calendar.index.add_holiday'); ?></button>
                    </div>
                </form>
                <hr>
                <div class="list-group">
                    <?php $__empty_1 = true; $__currentLoopData = $schoolHolidays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <b><?php echo e($h->name); ?></b>
                                <div class="small text-secondary num"><?php echo e($h->date_start->format('Y-m-d')); ?> ← <?php echo e($h->date_stop->format('Y-m-d')); ?></div>
                            </div>
                            <form method="POST" action="<?php echo e(route('admin.calendar.destroy-holiday', $h)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state py-4"><i class="bi bi-calendar2-x"></i><p><?php echo app('translator')->get('calendar.index.no_school_holidays'); ?></p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/calendar/index.blade.php ENDPATH**/ ?>