<?php $__env->startSection('title', __('exams.index.title')); ?>
<?php $__env->startSection('page', __('exams.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('exams.index.heading'); ?></h1>
        <p class="text-secondary mb-0"><?php echo app('translator')->get('exams.index.subtitle'); ?></p>
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
    <div class="b-7">
        <div class="card hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-journal-bookmark me-2 text-primary"></i> <?php echo app('translator')->get('exams.index.list_title'); ?></span>
                <span class="badge badge-soft-primary"><?php echo app('translator')->get('exams.index.exam_count', ['count' => $exams->count()]); ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th><?php echo app('translator')->get('exams.index.col_exam'); ?></th><th><?php echo app('translator')->get('exams.index.col_type'); ?></th><th><?php echo app('translator')->get('exams.index.col_batch'); ?></th><th><?php echo app('translator')->get('exams.index.col_duration'); ?></th><th><?php echo app('translator')->get('exams.index.col_status'); ?></th><th></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?>"><?php echo e(mb_substr($exam->name, 0, 1)); ?></span>
                                        <span class="fw-semibold"><?php echo e($exam->name); ?></span>
                                    </div>
                                </td>
                                <td><?php echo e($exam->examType?->name ?? '—'); ?></td>
                                <td><?php echo e($exam->batch?->name ?? '—'); ?></td>
                                <td class="num">
                                    <?php echo e($exam->date_start ? $exam->date_start->format('Y-m-d') : '—'); ?>

                                    <?php if($exam->date_end && $exam->date_end != $exam->date_start): ?>
                                        <span class="text-secondary">→ <?php echo e($exam->date_end->format('Y-m-d')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-soft-<?php echo e($exam->state === 'published' ? 'success' : 'warning'); ?>"><?php echo e($exam->state); ?></span>
                                </td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('admin.exams.show', $exam)); ?>" class="btn btn-sm btn-outline-primary"><?php echo app('translator')->get('exams.index.manage'); ?> <i class="bi bi-arrow-left"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6"><div class="empty-state"><i class="bi bi-journal-x"></i><p><?php echo app('translator')->get('exams.index.empty_title'); ?></p><small><?php echo app('translator')->get('exams.index.empty_hint'); ?></small></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="b-5">
        <div class="card hoverable">
            <div class="card-header fw-semibold"><i class="bi bi-plus-circle me-2 text-primary"></i> <?php echo app('translator')->get('exams.index.new_title'); ?></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.exams.store')); ?>" class="row g-3">
                    <?php echo csrf_field(); ?>
                    <div class="col-12">
                        <label class="form-label"><?php echo app('translator')->get('exams.index.form_name'); ?></label>
                        <input type="text" name="name" class="form-control" required placeholder="<?php echo app('translator')->get('exams.index.form_name_ph'); ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label"><?php echo app('translator')->get('exams.index.form_type'); ?></label>
                        <select name="exam_type_id" class="form-select">
                            <option value="">—</option>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label"><?php echo app('translator')->get('exams.index.form_batch'); ?></label>
                        <select name="batch_id" class="form-select">
                            <option value=""><?php echo app('translator')->get('exams.index.all_batches'); ?></option>
                            <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($b->id); ?>"><?php echo e($b->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label"><?php echo app('translator')->get('exams.index.form_year'); ?></label>
                        <select name="academic_year_id" class="form-select">
                            <option value="">—</option>
                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($y->id); ?>"><?php echo e($y->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label"><?php echo app('translator')->get('exams.index.form_term'); ?></label>
                        <select name="term_id" class="form-select">
                            <option value="">—</option>
                            <?php $__currentLoopData = $terms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($term->id); ?>"><?php echo e($term->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label"><?php echo app('translator')->get('exams.index.form_start'); ?></label>
                        <input type="date" name="date_start" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label"><?php echo app('translator')->get('exams.index.form_end'); ?></label>
                        <input type="date" name="date_end" class="form-control">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary w-100"><?php echo app('translator')->get('exams.index.create'); ?> <i class="bi bi-check-lg"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card hoverable mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-diagram-3 me-2 text-primary"></i> <?php echo app('translator')->get('exams.index.rooms_title'); ?></span>
        <span class="badge badge-soft-primary"><?php echo app('translator')->get('exams.index.room_count', ['count' => $rooms->count()]); ?></span>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.exams.rooms.store')); ?>" class="row g-2 align-items-end mb-3">
            <?php echo csrf_field(); ?>
            <div class="col-md-3">
                <label class="form-label"><?php echo app('translator')->get('exams.index.form_room_name'); ?></label>
                <input type="text" name="name" class="form-control" required placeholder="<?php echo app('translator')->get('exams.index.form_room_name_ph'); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label"><?php echo app('translator')->get('exams.index.form_code'); ?></label>
                <input type="text" name="code" class="form-control" placeholder="A1">
            </div>
            <div class="col-md-3">
                <label class="form-label"><?php echo app('translator')->get('exams.index.form_capacity'); ?></label>
                <input type="number" name="capacity" class="form-control" required min="1" max="500" value="30">
            </div>
            <div class="col-md-2">
                <label class="form-label"><?php echo app('translator')->get('exams.index.form_location'); ?></label>
                <input type="text" name="location" class="form-control" placeholder="<?php echo app('translator')->get('exams.index.form_location_ph'); ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> <?php echo app('translator')->get('exams.index.add'); ?></button>
            </div>
        </form>

        <?php if($rooms->count()): ?>
            <div class="row g-3">
                <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 <?php echo e($room->active ? '' : 'opacity-50'); ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold"><?php echo e($room->name); ?></div>
                                        <small class="text-secondary"><?php echo e($room->location ?: '—'); ?></small>
                                    </div>
                                    <span class="badge badge-soft-<?php echo e($room->active ? 'success' : 'secondary'); ?>"><?php echo e($room->active ? __('exams.index.room_active') : __('exams.index.room_inactive')); ?></span>
                                </div>
                                <div class="mt-3 d-flex align-items-center justify-content-between">
                                    <span class="stat-value num" style="font-size:1.2rem"><?php echo e($room->capacity); ?></span>
                                    <span class="text-secondary small"><?php echo app('translator')->get('exams.index.seat'); ?></span>
                                </div>
                                <div class="mt-2 d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-secondary flex-fill" data-bs-toggle="modal" data-bs-target="#roomEdit<?php echo e($room->id); ?>"><?php echo app('translator')->get('exams.index.edit'); ?></button>
                                    <form method="POST" action="<?php echo e(route('admin.exams.rooms.destroy', $room)); ?>" onsubmit="return confirm('<?php echo e(__('exams.index.confirm_delete_room')); ?>')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="roomEdit<?php echo e($room->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="<?php echo e(route('admin.exams.rooms.update', $room)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <div class="modal-header"><h5 class="modal-title"><?php echo app('translator')->get('exams.index.edit_room_title'); ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <div class="modal-body row g-3">
                                    <div class="col-6"><label class="form-label"><?php echo app('translator')->get('exams.index.name'); ?></label><input name="name" value="<?php echo e($room->name); ?>" class="form-control" required></div>
                                    <div class="col-6"><label class="form-label"><?php echo app('translator')->get('exams.index.form_code'); ?></label><input name="code" value="<?php echo e($room->code); ?>" class="form-control"></div>
                                    <div class="col-6"><label class="form-label"><?php echo app('translator')->get('exams.index.form_capacity'); ?></label><input type="number" name="capacity" value="<?php echo e($room->capacity); ?>" class="form-control" min="1" required></div>
                                    <div class="col-6"><label class="form-label"><?php echo app('translator')->get('exams.index.form_location'); ?></label><input name="location" value="<?php echo e($room->location); ?>" class="form-control"></div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="active" value="1" id="roomActive<?php echo e($room->id); ?>" <?php echo e($room->active ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="roomActive<?php echo e($room->id); ?>"><?php echo app('translator')->get('exams.index.room_active_label'); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer"><button class="btn btn-primary"><?php echo app('translator')->get('exams.index.save_changes'); ?></button></div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty-state"><i class="bi bi-easel"></i><p><?php echo app('translator')->get('exams.index.no_rooms_title'); ?></p><small><?php echo app('translator')->get('exams.index.no_rooms_hint'); ?></small></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/exams/index.blade.php ENDPATH**/ ?>