<?php
    $primaryColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
    $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => App\Models\MobileAppConfig::configValue('school_name', 'مدرسة إدبة'));
    $primaryRgb = sscanf($primaryColor, '#%02x%02x%02x');
    $primaryRgb = $primaryRgb[0] . ',' . $primaryRgb[1] . ',' . $primaryRgb[2];
?>
<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo app('translator')->get('two_factor'); ?> — <?php echo e($schoolName); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap<?php echo e(app()->getLocale() === 'ar' ? '.rtl' : ''); ?>.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', 'Tajawal', system-ui, sans-serif; }
        [dir="rtl"] * { font-family: 'Tajawal', 'Plus Jakarta Sans', system-ui, sans-serif; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; -webkit-font-smoothing: antialiased; }
        .bg-decor {
            position: fixed; inset: 0; z-index: -2;
            background: linear-gradient(135deg, <?php echo e($primaryColor); ?> 0%, #6d28d9 50%, #be185d 100%);
            background-size: 200% 200%; animation: loginGrad 18s ease infinite;
        }
        @keyframes loginGrad { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        .bg-decor::before, .bg-decor::after { content: ''; position: absolute; border-radius: 50%; filter: blur(80px); opacity: .45; }
        .bg-decor::before { width: 500px; height: 500px; background: #fff3; top: -140px; inset-inline-start: -100px; }
        .bg-decor::after { width: 400px; height: 400px; background: #fff2; bottom: -120px; inset-inline-end: -80px; }
        .grid-overlay {
            position: fixed; inset: 0; z-index: -1; opacity: .08;
            background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px);
            background-size: 48px 48px; mask-image: radial-gradient(circle at 50% 40%, #000, transparent 70%);
        }
        .card-2fa {
            width: min(440px, 94vw); background: #fff; border-radius: 24px;
            box-shadow: 0 48px 96px -24px rgba(0,0,0,.4); padding: 44px;
            animation: rise .6s cubic-bezier(.22,.68,.31,1);
            border: 1px solid rgba(255,255,255,.1);
        }
        @keyframes rise { from { opacity: 0; transform: translateY(28px) scale(.97); } to { opacity: 1; transform: none; } }
        .otp-input {
            text-align: center; letter-spacing: .5em; font-size: 1.8rem; font-weight: 800;
            border-radius: 14px !important; border: 1.5px solid #e2e8f0; padding: 16px;
            background: #f8f9fc; color: #0f172a;
        }
        .otp-input:focus { outline: none; border-color: <?php echo e($primaryColor); ?>; box-shadow: 0 0 0 4px rgba(<?php echo e($primaryRgb); ?>, .1); background: #fff; }
        .btn-otp {
            width: 100%; padding: 14px; border: 0; border-radius: 14px; font-weight: 800; color: #fff;
            background: linear-gradient(135deg, <?php echo e($primaryColor); ?>, #6d28d9);
            box-shadow: 0 12px 28px -8px <?php echo e($primaryColor); ?>; transition: all .25s ease; font-size: .95rem;
        }
        .btn-otp:hover { filter: brightness(1.08); transform: translateY(-2px); }
        .shield-icon { width: 64px; height: 64px; border-radius: 18px; background: rgba(<?php echo e($primaryRgb); ?>, .08); display: inline-grid; place-items: center; font-size: 1.7rem; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="bg-decor"></div>
    <div class="grid-overlay"></div>

    <div class="card-2fa">
        <div class="text-center mb-4">
            <div class="shield-icon" style="color:<?php echo e($primaryColor); ?>">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h1 class="h4 fw-bolder mb-1" style="letter-spacing:-.02em"><?php echo app('translator')->get('two_factor'); ?></h1>
            <p class="small mb-0" style="color:#64748b;line-height:1.5"><?php echo app('translator')->get('two_factor_desc'); ?></p>
        </div>

        <?php if(session('status')): ?>
            <div class="alert alert-success py-2 small" style="border-radius:12px;background:rgba(16,185,129,.08);color:#059669;border:none;font-weight:600"><i class="bi bi-check-circle-fill me-1"></i><?php echo e(session('status')); ?></div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-warning py-2 small" style="border-radius:12px;background:rgba(245,158,11,.1);color:#d97706;border:none;font-weight:600"><i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger py-2 small" style="border-radius:12px;background:rgba(239,68,68,.08);color:#dc2626;border:none;font-weight:600">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span><?php echo e($error); ?></span> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.2fa.verify')); ?>">
            <?php echo csrf_field(); ?>
            <input type="text" name="code" inputmode="numeric" maxlength="6" autocomplete="one-time-code"
                class="form-control otp-input" placeholder="••••••" autofocus required>
            <button type="submit" class="btn-otp mt-4"><i class="bi bi-shield-check me-2"></i><?php echo app('translator')->get('verify'); ?></button>
        </form>

        <div class="text-center mt-3">
            <form method="POST" action="<?php echo e(route('admin.2fa.resend')); ?>" class="d-inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-link btn-sm text-decoration-none fw-bold" style="color:<?php echo e($primaryColor); ?>">
                    <i class="bi bi-arrow-clockwise me-1"></i><?php echo app('translator')->get('resend_code'); ?>
                </button>
            </form>
        </div>

        <div class="text-center mt-2">
            <a href="<?php echo e(route('admin.login')); ?>" class="text-decoration-none small fw-bold" style="color:#64748b"><?php echo app('translator')->get('back_to_login'); ?></a>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/auth/two-factor.blade.php ENDPATH**/ ?>