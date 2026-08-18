<?php $__env->startSection('title', $parent ? __('parents.form.title_edit') : __('parents.form.title_new')); ?>
<?php $__env->startSection('page', $parent ? __('parents.form.page_edit') : __('parents.form.page_new')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo e($parent ? __('parents.form.title_edit') : __('parents.form.title_new')); ?></h1>
        <p class="text-secondary mb-0"><?php echo app('translator')->get('parents.form.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.parents.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> <?php echo app('translator')->get('parents.form.back_to_list'); ?></a>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e($parent ? route('admin.parents.update', $parent) : route('admin.parents.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if($parent): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('parents.form.full_name'); ?></label>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $parent?->name)); ?>" required>
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
                    <label class="form-label"><?php echo app('translator')->get('parents.form.national_id'); ?></label>
                    <input type="text" name="national_id" class="form-control" value="<?php echo e(old('national_id', $parent?->national_id)); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('parents.form.phone'); ?></label>
                    <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $parent?->phone)); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('parents.form.mobile'); ?></label>
                    <input type="text" name="mobile" class="form-control" value="<?php echo e(old('mobile', $parent?->mobile)); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('parents.form.email'); ?></label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $parent?->email)); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('parents.form.occupation'); ?></label>
                    <input type="text" name="occupation" class="form-control" value="<?php echo e(old('occupation', $parent?->occupation)); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('parents.form.relation'); ?></label>
                    <select name="relation" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = ['father','mother','guardian','other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($r); ?>" <?php echo e(old('relation', $parent?->relation) === $r ? 'selected' : ''); ?>><?php echo e($r); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('parents.form.address'); ?></label>
                    <input type="text" name="address" class="form-control" value="<?php echo e(old('address', $parent?->address)); ?>">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> <?php echo app('translator')->get('parents.form.save'); ?></button>
                <a href="<?php echo e(route('admin.parents.index')); ?>" class="btn btn-outline-secondary"><?php echo app('translator')->get('parents.form.cancel'); ?></a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/parents/form.blade.php ENDPATH**/ ?>