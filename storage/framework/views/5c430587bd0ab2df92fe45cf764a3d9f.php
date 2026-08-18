<?php $__env->startSection('title', $batch ? __('batches.form.edit_batch') : __('batches.form.new_batch')); ?>
<?php $__env->startSection('page', $batch ? __('batches.form.page_edit') : __('batches.form.page_create')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo e($batch ? __('batches.form.h1_edit', ['name' => $batch->name]) : __('batches.form.h1_create')); ?></h1>
        <p><?php echo app('translator')->get('batches.form.subtitle'); ?></p>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e($batch ? route('admin.batches.update', $batch) : route('admin.batches.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if($batch): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('batches.form.label_name'); ?></label>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $batch?->name)); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('batches.form.label_capacity'); ?></label>
                    <input type="number" name="capacity" class="form-control" value="<?php echo e(old('capacity', $batch?->capacity)); ?>" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('batches.form.label_program'); ?></label>
                    <select name="program_id" class="form-select <?php $__errorArgs = ['program_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">—</option>
                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($program->id); ?>" <?php echo e(old('program_id', $batch?->program_id) == $program->id ? 'selected' : ''); ?>><?php echo e($program->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['program_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('batches.form.label_academic_year'); ?></label>
                    <select name="academic_year_id" class="form-select <?php $__errorArgs = ['academic_year_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">—</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>" <?php echo e(old('academic_year_id', $batch?->academic_year_id) == $year->id ? 'selected' : ''); ?>><?php echo e($year->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['academic_year_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('batches.form.label_class_teacher'); ?></label>
                    <select name="class_teacher_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $faculty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($member->id); ?>" <?php echo e(old('class_teacher_id', $batch?->class_teacher_id) == $member->id ? 'selected' : ''); ?>><?php echo e($member->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="activeChk" <?php echo e(old('active', $batch?->active ?? true) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="activeChk"><?php echo app('translator')->get('batches.form.active'); ?></label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> <?php echo app('translator')->get('batches.form.save'); ?></button>
                <a href="<?php echo e(route('admin.batches.index')); ?>" class="btn btn-outline-secondary"><?php echo app('translator')->get('batches.form.cancel'); ?></a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/batches/form.blade.php ENDPATH**/ ?>