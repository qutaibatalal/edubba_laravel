<?php $__env->startSection('title', __('admissions.form.title')); ?>
<?php $__env->startSection('page', __('admissions.form.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('admissions.form.heading'); ?></h1>
        <p><?php echo app('translator')->get('admissions.form.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.admissions.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> <?php echo app('translator')->get('admissions.form.back_to_list'); ?></a>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e(route('admin.admissions.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.first_name'); ?></label>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.father_name'); ?></label>
                    <input type="text" name="middle_name" class="form-control" value="<?php echo e(old('middle_name')); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.family_name'); ?></label>
                    <input type="text" name="last_name" class="form-control" value="<?php echo e(old('last_name')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.gender'); ?></label>
                    <select name="gender" class="form-select">
                        <option value="male" <?php echo e(old('gender') === 'female' ? '' : 'selected'); ?>><?php echo app('translator')->get('admissions.form.gender_male'); ?></option>
                        <option value="female" <?php echo e(old('gender') === 'female' ? 'selected' : ''); ?>><?php echo app('translator')->get('admissions.form.gender_female'); ?></option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.birth_date'); ?></label>
                    <input type="date" name="birth_date" class="form-control" value="<?php echo e(old('birth_date')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.national_id'); ?></label>
                    <input type="text" name="national_id" class="form-control" value="<?php echo e(old('national_id')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.phone'); ?></label>
                    <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone')); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.email'); ?></label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>">
                </div>
                <div class="col-md-8">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.address'); ?></label>
                    <input type="text" name="address" class="form-control" value="<?php echo e(old('address')); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.previous_school'); ?></label>
                    <input type="text" name="previous_school" class="form-control" value="<?php echo e(old('previous_school')); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.fees_amount'); ?></label>
                    <input type="number" step="0.01" name="fees_amount" class="form-control" value="<?php echo e(old('fees_amount')); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.academic_year'); ?></label>
                    <select name="academic_year_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($y->id); ?>" <?php echo e(old('academic_year_id') == $y->id ? 'selected' : ''); ?>><?php echo e($y->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.batch'); ?></label>
                    <select name="batch_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>" <?php echo e(old('batch_id') == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('admissions.form.program'); ?></label>
                    <select name="program_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>" <?php echo e(old('program_id') == $p->id ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> <?php echo app('translator')->get('admissions.form.create_request'); ?></button>
                <a href="<?php echo e(route('admin.admissions.index')); ?>" class="btn btn-outline-secondary"><?php echo app('translator')->get('admissions.form.cancel'); ?></a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/admissions/form.blade.php ENDPATH**/ ?>