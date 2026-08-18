<?php $__env->startSection('title', $member ? __('faculty.form.edit_member') : __('faculty.form.new_member')); ?>
<?php $__env->startSection('page', $member ? __('faculty.form.page_edit', ['name' => $member->full_name]) : __('faculty.form.page_create')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo e($member ? __('faculty.form.edit_member') : __('faculty.form.new_member')); ?></h1>
        <p><?php echo app('translator')->get('faculty.form.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.faculty.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> <?php echo app('translator')->get('faculty.form.back'); ?></a>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e($member ? route('admin.faculty.update', $member) : route('admin.faculty.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if($member): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_first_name'); ?></label>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $member?->name)); ?>" required>
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
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_middle_name'); ?></label>
                    <input type="text" name="middle_name" class="form-control" value="<?php echo e(old('middle_name', $member?->middle_name)); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_last_name'); ?></label>
                    <input type="text" name="last_name" class="form-control" value="<?php echo e(old('last_name', $member?->last_name)); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_code'); ?></label>
                    <input type="text" name="faculty_code" class="form-control" placeholder="<?php echo app('translator')->get('faculty.form.code_placeholder'); ?>" value="<?php echo e(old('faculty_code', $member?->faculty_code)); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_gender'); ?></label>
                    <select name="gender" class="form-select">
                        <option value="male" <?php echo e(old('gender', $member?->gender) === 'male' ? 'selected' : ''); ?>><?php echo app('translator')->get('faculty.form.male'); ?></option>
                        <option value="female" <?php echo e(old('gender', $member?->gender) === 'female' ? 'selected' : ''); ?>><?php echo app('translator')->get('faculty.form.female'); ?></option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_department'); ?></label>
                    <select name="department_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d->id); ?>" <?php echo e(old('department_id', $member?->department_id) == $d->id ? 'selected' : ''); ?>><?php echo e($d->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_birth_date'); ?></label>
                    <input type="date" name="birth_date" class="form-control" value="<?php echo e(old('birth_date', $member?->birth_date?->format('Y-m-d'))); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_qualification'); ?></label>
                    <input type="text" name="qualification" class="form-control" value="<?php echo e(old('qualification', $member?->qualification)); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_specialization'); ?></label>
                    <input type="text" name="specialization" class="form-control" value="<?php echo e(old('specialization', $member?->specialization)); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_join_date'); ?></label>
                    <input type="date" name="join_date" class="form-control" value="<?php echo e(old('join_date', $member?->join_date?->format('Y-m-d'))); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_phone'); ?></label>
                    <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $member?->phone)); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_mobile'); ?></label>
                    <input type="text" name="mobile" class="form-control" value="<?php echo e(old('mobile', $member?->mobile)); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_email'); ?></label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $member?->email)); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?php echo app('translator')->get('faculty.form.label_state'); ?></label>
                    <select name="state" class="form-select">
                        <?php $__currentLoopData = ['draft','joined','left']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($st); ?>" <?php echo e(old('state', $member?->state) === $st ? 'selected' : ''); ?>><?php echo e($st); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> <?php echo app('translator')->get('faculty.form.save'); ?></button>
                <a href="<?php echo e(route('admin.faculty.index')); ?>" class="btn btn-outline-secondary"><?php echo app('translator')->get('faculty.form.cancel'); ?></a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/faculty/form.blade.php ENDPATH**/ ?>