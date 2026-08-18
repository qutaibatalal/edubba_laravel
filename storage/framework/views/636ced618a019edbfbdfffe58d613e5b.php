<?php $__env->startSection('title', __('settings.index.title')); ?>
<?php $__env->startSection('page', __('settings.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('settings.index.h1'); ?></h1>
        <p class="text-muted mb-0"><?php echo app('translator')->get('settings.index.subtitle'); ?></p>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>">
            <?php echo csrf_field(); ?>
            <h6 class="fw-bold mb-3"><i class="bi bi-building text-primary me-2"></i> <?php echo app('translator')->get('settings.index.school_info'); ?></h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?php echo app('translator')->get('settings.index.school_name_en'); ?></label>
                    <input type="text" name="school_name_en" class="form-control" value="<?php echo e($configs['school_name']?->value['en'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?php echo app('translator')->get('settings.index.school_name_ar'); ?></label>
                    <input type="text" name="school_name_ar" class="form-control" value="<?php echo e($configs['school_name']?->value['ar'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?php echo app('translator')->get('settings.index.primary_color'); ?></label>
                    <div class="input-group">
                        <input type="color" name="primary_color" class="form-control form-control-color" value="<?php echo e($configs['primary_color']?->value ?? '#1e40af'); ?>">
                        <input type="text" name="primary_color" class="form-control" value="<?php echo e($configs['primary_color']?->value ?? '#1e40af'); ?>">
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mt-4 mb-3"><i class="bi bi-toggle-on text-primary me-2"></i> <?php echo app('translator')->get('settings.index.enabled_features'); ?></h6>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="features[tutoring]" value="1" id="f1" <?php echo e(($configs['features']?->value['tutoring'] ?? true) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="f1"><?php echo app('translator')->get('settings.index.feature_tutoring'); ?></label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="features[training]" value="1" id="f2" <?php echo e(($configs['features']?->value['training'] ?? true) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="f2"><?php echo app('translator')->get('settings.index.feature_training'); ?></label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="features[library]" value="1" id="f3" <?php echo e(($configs['features']?->value['library'] ?? true) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="f3"><?php echo app('translator')->get('settings.index.feature_library'); ?></label>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> <?php echo app('translator')->get('settings.index.save_settings'); ?></button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4 hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-1"><i class="bi bi-shield-lock text-primary me-2"></i> <?php echo app('translator')->get('settings.index.two_factor'); ?></h6>
        <p class="text-muted small mb-3"><?php echo app('translator')->get('settings.index.two_factor_desc'); ?></p>

        <?php if(session('error')): ?>
            <div class="alert alert-warning py-2 small"><i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php if($twoFactorEnabled): ?>
            <div class="alert alert-success py-2 small d-flex justify-content-between align-items-center">
                <span><i class="bi bi-check-circle-fill me-1"></i> <?php echo app('translator')->get('settings.index.two_factor_enabled'); ?></span>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.2fa.disable')); ?>" class="mt-3">
                <?php echo csrf_field(); ?>
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <input type="password" name="password" class="form-control" placeholder="<?php echo app('translator')->get('settings.index.current_password'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-shield-slash me-1"></i> <?php echo app('translator')->get('settings.index.disable'); ?></button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-secondary py-2 small"><?php echo app('translator')->get('settings.index.two_factor_disabled'); ?></div>
            <form method="POST" action="<?php echo e(route('admin.settings.2fa.enable')); ?>" class="mt-3">
                <?php echo csrf_field(); ?>
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <input type="text" name="phone" class="form-control" dir="ltr" value="<?php echo e(auth()->user()->phone ?? ''); ?>" placeholder="07XXXXXXXXX" required>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-shield-check me-1"></i> <?php echo app('translator')->get('settings.index.enable'); ?></button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>