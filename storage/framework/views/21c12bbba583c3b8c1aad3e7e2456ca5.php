<?php
    $primaryColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
    $appName = config('app.name', 'إدبة');
    $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => App\Models\MobileAppConfig::configValue('school_name', 'مدرسة إدبة'));
    $locale = Session::has('locale') ? session('locale') : 'ar';
    $primaryRgb = sscanf($primaryColor, '#%02x%02x%02x');
    $primaryRgb = $primaryRgb[0] . ',' . $primaryRgb[1] . ',' . $primaryRgb[2];
?>
<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', __('dashboard')); ?> — <?php echo e($appName); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/edubba_app_icon.png')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@0.344.0/font/lucide.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --edb-primary: <?php echo e($primaryColor); ?>;
            --edb-primary-rgb: <?php echo e($primaryRgb); ?>;
            --edb-sidebar-w: 272px;
            --edb-sidebar-collapsed-w: 78px;
            --edb-radius: 16px;
            --edb-radius-sm: 10px;
            --edb-radius-xs: 8px;
            --edb-bg: #f8f9fc;
            --edb-bg-elevated: #ffffff;
            --edb-border: rgba(0, 0, 0, .06);
            --edb-border-strong: rgba(0, 0, 0, .10);
            --edb-text-1: #0f1729;
            --edb-text-2: #475569;
            --edb-text-3: #94a3b8;
            --edb-shadow-sm: 0 1px 2px rgba(0,0,0,.03), 0 1px 3px rgba(0,0,0,.04);
            --edb-shadow: 0 1px 3px rgba(0,0,0,.04), 0 2px 8px rgba(0,0,0,.04);
            --edb-shadow-md: 0 4px 12px rgba(0,0,0,.06), 0 1px 3px rgba(0,0,0,.04);
            --edb-shadow-lg: 0 12px 40px -8px rgba(0,0,0,.12), 0 4px 12px rgba(0,0,0,.04);
            --edb-shadow-xl: 0 24px 64px -16px rgba(0,0,0,.18);
            --edb-glass: rgba(255,255,255,.72);
            --edb-glass-border: rgba(255,255,255,.5);
            --transition-fast: 150ms cubic-bezier(.4,0,.2,1);
            --transition-base: 200ms cubic-bezier(.4,0,.2,1);
            --transition-smooth: 300ms cubic-bezier(.4,0,.2,1);
            --transition-spring: 500ms cubic-bezier(.22,.68,.31,1);
        }
        [data-bs-theme="dark"] {
            --edb-bg: #0c0e14;
            --edb-bg-elevated: #13161f;
            --edb-border: rgba(255,255,255,.06);
            --edb-border-strong: rgba(255,255,255,.10);
            --edb-text-1: #e2e8f0;
            --edb-text-2: #94a3b8;
            --edb-text-3: #64748b;
            --edb-shadow-sm: 0 1px 2px rgba(0,0,0,.2);
            --edb-shadow: 0 1px 3px rgba(0,0,0,.3), 0 2px 8px rgba(0,0,0,.2);
            --edb-shadow-md: 0 4px 12px rgba(0,0,0,.4);
            --edb-shadow-lg: 0 12px 40px -8px rgba(0,0,0,.6);
            --edb-shadow-xl: 0 24px 64px -16px rgba(0,0,0,.7);
            --edb-glass: rgba(12,14,20,.82);
            --edb-glass-border: rgba(255,255,255,.06);
        }

        * { font-family: 'Plus Jakarta Sans', 'Tajawal', system-ui, sans-serif; }
        [dir="rtl"] * { font-family: 'Tajawal', 'Plus Jakarta Sans', system-ui, sans-serif; }
        body { background: var(--edb-bg); min-height: 100vh; transition: background var(--transition-smooth); color: var(--edb-text-1); -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(120,130,150,.22); border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(120,130,150,.35); }
        .num { font-variant-numeric: tabular-nums; }
        @media (prefers-reduced-motion: reduce) { * { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; } }

        /* ============ Sidebar — refined, modern ============ */
        .edb-sidebar {
            position: fixed; top: 0; right: 0; bottom: 0; z-index: 1045;
            width: var(--edb-sidebar-w); display: flex; flex-direction: column;
            background: #0c0e14;
            border-inline-start: 1px solid rgba(255,255,255,.04);
            transition: width var(--transition-smooth), transform var(--transition-smooth);
            overflow: hidden;
        }
        [data-bs-theme="dark"] .edb-sidebar { background: #080a0f; border-inline-start-color: rgba(255,255,255,.03); }
        .edb-sidebar .brand { display: flex; align-items: center; gap: 14px; padding: 24px 22px 20px; color: #fff; text-decoration: none; white-space: nowrap; }
        .edb-sidebar .brand .brand-logo { height: 30px; width: auto; max-width: 150px; object-fit: contain; flex-shrink: 0; transition: transform var(--transition-smooth); }
        .edb-sidebar .brand:hover .brand-logo { transform: scale(1.03); }
        .edb-sidebar .brand .brand-logo-mini { display: none; width: 38px; height: 38px; flex-shrink: 0; border-radius: 12px; object-fit: cover; }
        .edb-sidebar .brand .brand-name { font-weight: 800; font-size: 1.02rem; line-height: 1.25; min-width: 0; overflow: hidden; text-overflow: ellipsis; }
        .edb-sidebar .brand .brand-sub { font-size: .62rem; color: #525f7f; font-weight: 600; letter-spacing: .06em; display: block; margin-top: 2px; }
        .edb-sidebar .nav-scroll { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 6px 14px 20px; }
        .edb-sidebar .nav-scroll::-webkit-scrollbar { width: 3px; }
        .edb-sidebar .nav-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); }
        .edb-sidebar .nav-section { padding: 22px 12px 8px; font-size: .6rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #3d4559; white-space: nowrap; }
        .edb-sidebar .nav-link {
            display: flex; align-items: center; gap: 13px; padding: 10px 13px; margin: 1px 0;
            color: #7a849b; border-radius: var(--edb-radius-xs); font-weight: 600; font-size: .84rem;
            transition: all var(--transition-fast); position: relative; white-space: nowrap; text-decoration: none;
        }
        .edb-sidebar .nav-link i { font-size: 1.05rem; min-width: 20px; text-align: center; opacity: .75; transition: opacity var(--transition-fast); }
        .edb-sidebar .nav-link:hover { background: rgba(255,255,255,.06); color: #c8cfd9; }
        .edb-sidebar .nav-link:hover i { opacity: 1; }
        .edb-sidebar .nav-link.active { background: var(--edb-primary); color: #fff; font-weight: 700; box-shadow: 0 4px 16px -4px rgba(var(--edb-primary-rgb), .5); }
        .edb-sidebar .nav-link.active i { opacity: 1; }
        .edb-sidebar .nav-footer { padding: 14px 14px; border-top: 1px solid rgba(255,255,255,.05); }
        .edb-sidebar .nav-footer .user-chip { display: flex; align-items: center; gap: 11px; color: #c8cfd9; white-space: nowrap; padding: 6px 8px; border-radius: var(--edb-radius-xs); transition: background var(--transition-fast); }
        .edb-sidebar .nav-footer .user-chip:hover { background: rgba(255,255,255,.05); }
        .edb-sidebar .nav-footer .avatar { width: 36px; height: 36px; flex-shrink: 0; border-radius: 11px; background: var(--edb-primary); color: #fff; display: grid; place-items: center; font-weight: 800; font-size: .85rem; box-shadow: 0 2px 8px rgba(var(--edb-primary-rgb), .3); }

        body.sidebar-collapsed .edb-sidebar { width: var(--edb-sidebar-collapsed-w); }
        body.sidebar-collapsed .edb-sidebar .brand { padding: 24px 18px; justify-content: center; }
        body.sidebar-collapsed .edb-sidebar .brand-name,
        body.sidebar-collapsed .edb-sidebar .brand-sub,
        body.sidebar-collapsed .edb-sidebar .brand .brand-logo,
        body.sidebar-collapsed .edb-sidebar .nav-section,
        body.sidebar-collapsed .edb-sidebar .nav-link span,
        body.sidebar-collapsed .edb-sidebar .nav-footer .user-chip .u-txt { display: none; }
        body.sidebar-collapsed .edb-sidebar .brand .brand-logo-mini { display: block; }
        body.sidebar-collapsed .edb-sidebar .nav-link { justify-content: center; padding: 12px; }
        body.sidebar-collapsed .edb-sidebar .nav-footer .user-chip { justify-content: center; }

        /* ============ Main / topbar ============ */
        .edb-main { margin-inline-start: var(--edb-sidebar-w); margin-inline-end: 0; transition: margin var(--transition-smooth); min-height: 100vh; display: flex; flex-direction: column; }
        body.sidebar-collapsed .edb-main { margin-inline-start: var(--edb-sidebar-collapsed-w); }

        .edb-topbar {
            position: sticky; top: 0; z-index: 1030; display: flex; align-items: center; gap: 14px;
            padding: 14px 32px; min-height: 68px;
            background: var(--edb-glass);
            backdrop-filter: blur(20px) saturate(1.4); -webkit-backdrop-filter: blur(20px) saturate(1.4);
            border-bottom: 1px solid var(--edb-border);
        }
        .edb-topbar .breadcrumb { margin: 0; font-size: .8rem; }
        .edb-topbar .breadcrumb-item a { color: var(--edb-text-3); text-decoration: none; font-weight: 500; transition: color var(--transition-fast); }
        .edb-topbar .breadcrumb-item a:hover { color: var(--edb-text-1); }
        .edb-topbar .breadcrumb-item.active { color: var(--edb-text-2); font-weight: 600; }

        .edb-icon-btn {
            width: 40px; height: 40px; border-radius: 12px; border: 1px solid var(--edb-border-strong);
            background: var(--edb-bg-elevated); color: var(--edb-text-2); display: grid; place-items: center; font-size: 1rem;
            transition: all var(--transition-fast); position: relative; cursor: pointer;
        }
        .edb-icon-btn:hover { background: var(--edb-bg); border-color: var(--edb-border-strong); color: var(--edb-text-1); transform: translateY(-1px); box-shadow: var(--edb-shadow-sm); }
        .edb-icon-btn .dot { position: absolute; top: 8px; inset-inline-end: 9px; width: 8px; height: 8px; border-radius: 50%; background: #ef4444; border: 2px solid var(--edb-bg-elevated); animation: edbPulse 2.2s ease-in-out infinite; }

        .edb-search { position: relative; }
        .edb-search input {
            width: min(320px, 36vw); border-radius: 12px; border: 1px solid var(--edb-border-strong);
            background: var(--edb-bg-elevated); padding: 9px 16px 9px 40px; font-size: .84rem; color: var(--edb-text-1);
            transition: all var(--transition-fast);
        }
        .edb-search input::placeholder { color: var(--edb-text-3); }
        .edb-search input:focus { outline: none; border-color: var(--edb-primary); box-shadow: 0 0 0 3px rgba(var(--edb-primary-rgb), .12); }
        .edb-search i { position: absolute; inset-inline-start: 14px; top: 50%; transform: translateY(-50%); color: var(--edb-text-3); font-size: .88rem; }

        .edb-content { flex: 1; padding: 30px 32px; animation: edbFade .4s var(--transition-spring); }
        @keyframes edbFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
        @media (max-width: 1199px) { .edb-content { padding: 20px; } }

        /* ============ Components — modern, refined ============ */
        .card {
            border: 1px solid var(--edb-border); border-radius: var(--edb-radius);
            box-shadow: var(--edb-shadow); background: var(--edb-bg-elevated);
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast), transform var(--transition-fast);
        }
        .card.hoverable:hover { box-shadow: var(--edb-shadow-lg); transform: translateY(-3px); border-color: var(--edb-border-strong); }
        .card-header {
            background: transparent; border-bottom: 1px solid var(--edb-border); padding: 18px 24px; font-weight: 700; font-size: .92rem;
            display: flex; align-items: center; gap: 8px;
        }
        .card-body { padding: 24px; }
        .card-footer { background: transparent; border-top: 1px solid var(--edb-border); padding: 14px 24px; }

        .page-header { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px; margin-bottom: 28px; }
        .page-header h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; margin: 0; color: var(--edb-text-1); }
        .page-header p { margin: 5px 0 0; color: var(--edb-text-2); font-size: .88rem; font-weight: 500; }

        .btn { border-radius: 12px; font-weight: 700; transition: all var(--transition-fast); font-size: .88rem; letter-spacing: -.01em; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary { background: var(--edb-primary); border-color: var(--edb-primary); box-shadow: 0 2px 8px -2px rgba(var(--edb-primary-rgb), .4); }
        .btn-primary:hover { background: var(--edb-primary); filter: brightness(1.08); box-shadow: 0 4px 16px -4px rgba(var(--edb-primary-rgb), .5); }
        .btn-outline-primary { color: var(--edb-primary); border-color: rgba(var(--edb-primary-rgb), .25); }
        .btn-outline-primary:hover { background: var(--edb-primary); border-color: var(--edb-primary); color: #fff; }
        .btn-outline-secondary { color: var(--edb-text-2); border-color: var(--edb-border-strong); }
        .btn-outline-secondary:hover { background: var(--edb-bg); color: var(--edb-text-1); border-color: var(--edb-border-strong); }
        .btn-outline-danger { color: #ef4444; border-color: rgba(239,68,68,.25); }
        .btn-outline-danger:hover { background: #ef4444; border-color: #ef4444; color: #fff; }
        .btn-outline-success { color: #10b981; border-color: rgba(16,185,129,.25); }
        .btn-outline-success:hover { background: #10b981; border-color: #10b981; color: #fff; }
        .btn-outline-info { color: #0ea5e9; border-color: rgba(14,165,233,.25); }
        .btn-outline-info:hover { background: #0ea5e9; border-color: #0ea5e9; color: #fff; }
        .btn-outline-warning { color: #f59e0b; border-color: rgba(245,158,11,.25); }
        .btn-outline-warning:hover { background: #f59e0b; border-color: #f59e0b; color: #fff; }
        .btn-sm { border-radius: 10px; font-size: .8rem; padding: .35rem .85rem; }

        .form-control, .form-select { border-radius: 12px; padding: 10px 16px; font-size: .88rem; border-color: var(--edb-border-strong); background: var(--edb-bg-elevated); color: var(--edb-text-1); transition: all var(--transition-fast); }
        .form-control:focus, .form-select:focus { border-color: var(--edb-primary); box-shadow: 0 0 0 3px rgba(var(--edb-primary-rgb), .12); background: var(--edb-bg-elevated); }
        .form-control::placeholder { color: var(--edb-text-3); }
        .form-label { font-weight: 700; font-size: .82rem; margin-bottom: 7px; color: var(--edb-text-2); letter-spacing: -.01em; }
        .form-check-input:checked { background-color: var(--edb-primary); border-color: var(--edb-primary); }
        .form-check-input:focus { box-shadow: 0 0 0 3px rgba(var(--edb-primary-rgb), .12); }

        /* Tables — Stripe/Linear style, refined */
        .table-edb { border-collapse: separate; border-spacing: 0; }
        .table-edb thead th {
            font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
            color: var(--edb-text-3); border-bottom: 1px solid var(--edb-border-strong); padding: 12px 18px; white-space: nowrap; background: transparent;
        }
        .table-edb td { vertical-align: middle; padding: 14px 18px; font-size: .86rem; border-color: var(--edb-border); color: var(--edb-text-1); }
        .table-edb tbody tr { transition: background var(--transition-fast); }
        .table-edb tbody tr:hover { background: rgba(var(--edb-primary-rgb), .03); }
        .table-edb tbody tr + tr { border-top: 1px solid var(--edb-border); }

        /* Badges — softer, more refined */
        .badge { border-radius: 999px; font-weight: 700; padding: .32em .8em; font-size: .7rem; letter-spacing: .01em; }
        .badge-soft { background: rgba(100,116,139,.10); color: var(--edb-text-2); }
        .badge-soft-success { background: rgba(16,185,129,.12); color: #059669; }
        .badge-soft-danger { background: rgba(239,68,68,.12); color: #dc2626; }
        .badge-soft-warning { background: rgba(245,158,11,.14); color: #d97706; }
        .badge-soft-info { background: rgba(14,165,233,.12); color: #0284c7; }
        .badge-soft-primary { background: rgba(var(--edb-primary-rgb), .12); color: var(--edb-primary); }
        .badge-soft-purple { background: rgba(139,92,246,.12); color: #7c3aed; }
        [data-bs-theme="dark"] .badge-soft { background: rgba(255,255,255,.08); color: #cbd5e1; }
        [data-bs-theme="dark"] .badge-soft-success { background: rgba(16,185,129,.15); color: #34d399; }
        [data-bs-theme="dark"] .badge-soft-danger { background: rgba(239,68,68,.15); color: #f87171; }
        [data-bs-theme="dark"] .badge-soft-warning { background: rgba(245,158,11,.15); color: #fbbf24; }
        [data-bs-theme="dark"] .badge-soft-info { background: rgba(14,165,233,.15); color: #38bdf8; }
        [data-bs-theme="dark"] .badge-soft-primary { background: rgba(var(--edb-primary-rgb), .18); color: #a5b4fc; }
        [data-bs-theme="dark"] .badge-soft-purple { background: rgba(139,92,246,.15); color: #c4b5fd; }

        /* Avatars — soft tinted */
        .avatar { width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0; display: grid; place-items: center; font-weight: 800; font-size: .78rem; }
        .avatar-sm { width: 30px; height: 30px; border-radius: 9px; font-size: .68rem; }
        .avatar-lg { width: 68px; height: 68px; border-radius: 18px; font-size: 1.5rem; }
        .grad-1 { background: rgba(79,70,229,.10); color: #4f46e5; }
        .grad-2 { background: rgba(16,185,129,.10); color: #0d9488; }
        .grad-3 { background: rgba(14,165,233,.10); color: #0284c7; }
        .grad-4 { background: rgba(245,158,11,.12); color: #d97706; }
        .grad-5 { background: rgba(239,68,68,.10); color: #dc2626; }
        .grad-6 { background: rgba(139,92,246,.10); color: #7c3aed; }
        [data-bs-theme="dark"] .grad-1 { background: rgba(129,122,255,.14); color: #a5b4fc; }
        [data-bs-theme="dark"] .grad-2 { background: rgba(45,212,191,.12); color: #2dd4bf; }
        [data-bs-theme="dark"] .grad-3 { background: rgba(56,189,248,.12); color: #38bdf8; }
        [data-bs-theme="dark"] .grad-4 { background: rgba(251,191,36,.12); color: #fbbf24; }
        [data-bs-theme="dark"] .grad-5 { background: rgba(248,113,113,.12); color: #f87171; }
        [data-bs-theme="dark"] .grad-6 { background: rgba(196,181,253,.12); color: #c4b5fd; }

        /* KPI cards — modern, refined */
        .stat-card { position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; inset-inline-start: 0; top: 0; bottom: 0; width: 3px; border-radius: 0 3px 3px 0; }
        .stat-card.st-1::before { background: #4f46e5; } .stat-card.st-2::before { background: #0d9488; }
        .stat-card.st-3::before { background: #0284c7; } .stat-card.st-4::before { background: #d97706; }
        .stat-card.st-5::before { background: #dc2626; } .stat-card.st-6::before { background: #7c3aed; }
        .stat-card .stat-body { display: flex; align-items: center; gap: 16px; padding: 20px 24px 20px 26px; }
        .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 14px; display: grid; place-items: center; font-size: 1.2rem; flex-shrink: 0; }
        .stat-card .stat-value { font-size: 1.55rem; font-weight: 800; letter-spacing: -.03em; line-height: 1.05; }
        .stat-card .stat-label { font-size: .78rem; color: var(--edb-text-2); font-weight: 600; margin-top: 3px; }
        .stat-card.st-1 .stat-icon { background: rgba(79,70,229,.10); color: #4f46e5; }
        .stat-card.st-2 .stat-icon { background: rgba(16,185,129,.10); color: #0d9488; }
        .stat-card.st-3 .stat-icon { background: rgba(14,165,233,.10); color: #0284c7; }
        .stat-card.st-4 .stat-icon { background: rgba(245,158,11,.12); color: #d97706; }
        .stat-card.st-5 .stat-icon { background: rgba(239,68,68,.10); color: #dc2626; }
        .stat-card.st-6 .stat-icon { background: rgba(139,92,246,.10); color: #7c3aed; }

        /* Bento tiles */
        .bento { display: grid; gap: 20px; grid-template-columns: repeat(12, 1fr); }
        .bento .b-4 { grid-column: span 4; } .bento .b-5 { grid-column: span 5; } .bento .b-6 { grid-column: span 6; }
        .bento .b-7 { grid-column: span 7; } .bento .b-8 { grid-column: span 8; }
        @media (max-width: 1199px) { .bento .b-4, .bento .b-5, .bento .b-6, .bento .b-7, .bento .b-8 { grid-column: span 12; } }
        @media (min-width: 1200px) { .bento .b-sm { grid-column: span 4; } }

        .empty-state { text-align: center; padding: 48px 24px; color: var(--edb-text-2); }
        .empty-state i { font-size: 2.4rem; color: var(--edb-text-3); display: block; margin-bottom: 14px; opacity: .6; }
        .empty-state p { margin: 0 0 5px; font-weight: 700; color: var(--edb-text-2); }
        .empty-state small { color: var(--edb-text-3); display: block; margin-bottom: 16px; }

        /* Pagination */
        .pagination { margin: 14px 0; display: flex; gap: 6px; flex-wrap: wrap; }
        .pagination .page-link {
            border-radius: var(--edb-radius-xs) !important; padding: 7px 14px !important; margin: 0;
            font-weight: 700 !important; font-size: .82rem !important; color: var(--edb-text-2) !important;
            background: var(--edb-bg-elevated) !important; border: 1px solid var(--edb-border) !important;
            transition: all var(--transition-fast) !important; min-width: 38px !important; height: 38px !important;
            display: inline-flex !important; align-items: center !important; justify-content: center !important; text-decoration: none !important; line-height: 1 !important;
        }
        .pagination .page-link:hover {
            color: var(--edb-primary) !important; border-color: var(--edb-primary) !important;
            background: rgba(var(--edb-primary-rgb), .06) !important; transform: translateY(-1px); box-shadow: var(--edb-shadow-sm);
        }
        .pagination .page-link.active {
            color: #fff !important; background: var(--edb-primary) !important; border-color: var(--edb-primary) !important;
            box-shadow: 0 4px 12px -4px rgba(var(--edb-primary-rgb), .4) !important;
        }

        /* Dropdowns */
        .dropdown-menu { border-radius: 14px; border: 1px solid var(--edb-border-strong); box-shadow: var(--edb-shadow-lg); background: var(--edb-bg-elevated); padding: 6px; z-index: 1050; }
        .dropdown-item { border-radius: 10px; margin: 1px 4px; padding: 9px 14px; font-size: .84rem; font-weight: 600; color: var(--edb-text-2); transition: all var(--transition-fast); }
        .dropdown-item:hover { background: rgba(var(--edb-primary-rgb), .06); color: var(--edb-text-1); }
        .dropdown-item i { width: 20px; }

        /* Skeleton shimmer */
        .skeleton-line { position: relative; overflow: hidden; border-radius: 6px; }
        .skeleton-line::after { content: ''; position: absolute; inset: 0; transform: translateX(-100%); background: linear-gradient(90deg, transparent, rgba(255,255,255,.4), transparent); animation: edbShine 1.4s ease infinite; }

        /* Command palette (Ctrl+K search) */
        .edb-palette-overlay { position: fixed; inset: 0; z-index: 1090; background: rgba(0,0,0,.5); backdrop-filter: blur(8px); display: none; }
        .edb-palette { position: fixed; top: 14%; inset-inline: 0; max-width: 580px; margin: 0 auto; z-index: 1091; display: none; }
        .edb-palette.open, .edb-palette-overlay.open { display: block; animation: edbFade .2s ease; }
        .edb-palette .palette-input { width: 100%; padding: 18px 20px; font-size: 1rem; border: 0; outline: none; border-bottom: 1px solid var(--edb-border); border-radius: 18px 18px 0 0; background: var(--edb-bg-elevated); color: var(--edb-text-1); }
        .edb-palette .palette-results { max-height: 400px; overflow-y: auto; border-radius: 0 0 18px 18px; background: var(--edb-bg-elevated); }
        .edb-palette .palette-item { display: flex; align-items: center; gap: 12px; padding: 12px 20px; cursor: pointer; transition: background var(--transition-fast); color: var(--edb-text-2); }
        .edb-palette .palette-item:hover, .edb-palette .palette-item.selected { background: rgba(var(--edb-primary-rgb), .06); color: var(--edb-text-1); }
        .edb-palette .palette-item i { color: var(--edb-text-3); }

        /* Quick action FAB */
        .edb-fab { position: fixed; bottom: 24px; inset-inline-end: 24px; z-index: 1035; }
        .edb-fab-main { width: 54px; height: 54px; border-radius: 16px; border: 0; background: var(--edb-primary); color: #fff; font-size: 1.4rem; box-shadow: 0 8px 24px -6px rgba(var(--edb-primary-rgb), .5); display: grid; place-items: center; transition: all var(--transition-fast); cursor: pointer; }
        .edb-fab-main:hover { transform: scale(1.05) translateY(-2px); box-shadow: 0 12px 32px -8px rgba(var(--edb-primary-rgb), .6); }
        .edb-fab-menu { position: absolute; bottom: 68px; inset-inline-end: 0; min-width: 220px; background: var(--edb-bg-elevated); border: 1px solid var(--edb-border-strong); border-radius: 16px; box-shadow: var(--edb-shadow-xl); padding: 8px; display: none; }
        .edb-fab-menu.open { display: block; animation: edbPop .3s var(--transition-spring); }
        .edb-fab-menu a { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 10px; text-decoration: none; color: var(--edb-text-2); font-size: .84rem; font-weight: 600; transition: all var(--transition-fast); }
        .edb-fab-menu a:hover { background: rgba(var(--edb-primary-rgb), .06); color: var(--edb-text-1); }
        .edb-fab-menu a i { width: 20px; color: var(--edb-text-3); }

        /* ============ Motion & life ============ */
        @keyframes edbRise { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }
        @keyframes edbPop { 0% { transform: scale(.6); opacity: 0; } 60% { transform: scale(1.06); opacity: 1; } 100% { transform: scale(1); opacity: 1; } }
        @keyframes edbFloat { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(8px, -12px); } }
        @keyframes edbOrbDrift { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(20px, 14px) scale(1.08); } }
        @keyframes edbShine { from { transform: translateX(-130%) skewX(-20deg); } to { transform: translateX(230%) skewX(-20deg); } }
        @keyframes edbPulse { 0%, 100% { opacity: .4; transform: scale(1); } 50% { opacity: 1; transform: scale(1.4); } }
        @keyframes edbSlideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: none; } }
        @keyframes edbScaleIn { from { opacity: 0; transform: scale(.92); } to { opacity: 1; transform: none; } }
        @keyframes edbGlow { 0%, 100% { box-shadow: 0 0 20px rgba(var(--edb-primary-rgb), .1); } 50% { box-shadow: 0 0 30px rgba(var(--edb-primary-rgb), .2); } }

        .edb-bg-orbs { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; }
        .edb-bg-orbs .orb { position: absolute; border-radius: 50%; filter: blur(100px); will-change: transform; }
        .edb-bg-orbs .orb-1 { width: 500px; height: 500px; top: -160px; inset-inline-end: -120px; background: radial-gradient(circle, rgba(var(--edb-primary-rgb), .22), transparent 70%); animation: edbFloat 18s ease-in-out infinite; }
        .edb-bg-orbs .orb-2 { width: 400px; height: 400px; bottom: -140px; inset-inline-start: -100px; background: radial-gradient(circle, rgba(14,165,233,.12), transparent 70%); animation: edbOrbDrift 24s ease-in-out infinite; }
        .edb-bg-orbs .orb-3 { width: 300px; height: 300px; top: 40%; inset-inline-start: 35%; background: radial-gradient(circle, rgba(124,58,237,.09), transparent 70%); animation: edbFloat 28s ease-in-out infinite reverse; }
        [data-bs-theme="dark"] .edb-bg-orbs .orb-1 { background: radial-gradient(circle, rgba(var(--edb-primary-rgb), .16), transparent 70%); }
        [data-bs-theme="dark"] .edb-bg-orbs .orb-2 { background: radial-gradient(circle, rgba(14,165,233,.07), transparent 70%); }

        .edb-content { position: relative; z-index: 1; }
        .edb-content > * { animation: edbRise .5s cubic-bezier(.22,.68,.31,1) both; }
        .edb-content > *:nth-child(2) { animation-delay: .05s; }
        .edb-content > *:nth-child(3) { animation-delay: .1s; }
        .edb-content > *:nth-child(4) { animation-delay: .15s; }
        .edb-content > *:nth-child(5) { animation-delay: .2s; }
        .edb-content > *:nth-child(6) { animation-delay: .25s; }

        .reveal { opacity: 0; transform: translateY(20px); transition: opacity .5s cubic-bezier(.22,.68,.31,1), transform .5s cubic-bezier(.22,.68,.31,1); }
        .reveal.is-visible { opacity: 1; transform: none; }

        .stat-card { position: relative; }
        .stat-card .stat-icon { animation: edbPop .6s cubic-bezier(.22,.68,.31,1) both; }
        .stat-card::after {
            content: ''; position: absolute; inset: 0; pointer-events: none; opacity: 0; transition: opacity .5s ease; border-radius: inherit;
            background: radial-gradient(300px circle at var(--mx, 50%) var(--my, 50%), rgba(255,255,255,.3), transparent 65%);
        }
        [data-bs-theme="dark"] .stat-card::after {
            background: radial-gradient(300px circle at var(--mx, 50%) var(--my, 50%), rgba(255,255,255,.06), transparent 65%);
        }
        .stat-card:hover::after { opacity: 1; }

        .btn-primary, .btn-outline-primary { position: relative; overflow: hidden; }
        .btn-primary::after, .btn-outline-primary::after {
            content: ''; position: absolute; top: 0; bottom: 0; width: 45%; left: 0; opacity: 0; pointer-events: none;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.28), transparent);
        }
        .btn-primary:hover::after, .btn-outline-primary:hover::after { animation: edbShine .85s ease forwards; }

        .edb-sidebar .brand .brand-logo, .edb-sidebar .brand .brand-logo-mini { animation: edbFloat 8s ease-in-out infinite; }

        /* Alert refinements */
        .alert { border-radius: var(--edb-radius); font-weight: 600; font-size: .86rem; border: none; }
        .alert-success { background: rgba(16,185,129,.10); color: #059669; }
        .alert-danger { background: rgba(239,68,68,.10); color: #dc2626; }
        .alert-warning { background: rgba(245,158,11,.12); color: #d97706; }
        .alert-info { background: rgba(14,165,233,.10); color: #0284c7; }
        .alert-secondary { background: rgba(100,116,139,.08); color: var(--edb-text-2); }
        [data-bs-theme="dark"] .alert-success { background: rgba(16,185,129,.12); color: #34d399; }
        [data-bs-theme="dark"] .alert-danger { background: rgba(239,68,68,.12); color: #f87171; }
        [data-bs-theme="dark"] .alert-warning { background: rgba(245,158,11,.12); color: #fbbf24; }
        [data-bs-theme="dark"] .alert-info { background: rgba(14,165,233,.12); color: #38bdf8; }

        /* List group refinements */
        .list-group-item { border-color: var(--edb-border); transition: background var(--transition-fast); }
        .list-group-item:hover { background: rgba(var(--edb-primary-rgb), .02); }

        /* Toast refinements */
        .toast { border-radius: var(--edb-radius) !important; border: 1px solid var(--edb-border-strong) !important; }

        /* Switch styling */
        .form-check-input { width: 2.5em; height: 1.3em; border-radius: 2em; transition: all var(--transition-fast); }
        .form-check-input:checked { background-color: var(--edb-primary); border-color: var(--edb-primary); }

        /* Modal refinements */
        .modal-content { border-radius: var(--edb-radius); border: 1px solid var(--edb-border-strong); box-shadow: var(--edb-shadow-xl); }
        .modal-header { border-bottom: 1px solid var(--edb-border); padding: 18px 24px; }
        .modal-footer { border-top: 1px solid var(--edb-border); padding: 14px 24px; }

        /* Progress bar */
        .progress { border-radius: 8px; background: var(--edb-border); }
        .progress-bar { border-radius: 8px; }

        /* Table-striped override */
        .table-striped > tbody > tr:nth-of-type(odd) > td { background: rgba(var(--edb-primary-rgb), .015); }

        @media (max-width: 991px) {
            .edb-sidebar { transform: translateX(100%); width: var(--edb-sidebar-w) !important; }
            body.sidebar-mobile-open .edb-sidebar { transform: translateX(0); box-shadow: var(--edb-shadow-xl); }
            body.sidebar-mobile-open::after { content: ''; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 1044; backdrop-filter: blur(4px); }
            .edb-main, body.sidebar-collapsed .edb-main { margin-inline-start: 0; }
            body.sidebar-collapsed .edb-sidebar .brand-name, body.sidebar-collapsed .edb-sidebar .nav-section, body.sidebar-collapsed .edb-sidebar .nav-link span { display: initial; }
            body.sidebar-collapsed .edb-sidebar .brand .brand-logo { display: block; }
            body.sidebar-collapsed .edb-sidebar .brand .brand-logo-mini { display: none; }
            body.sidebar-collapsed .edb-sidebar .brand { justify-content: flex-start; padding: 24px 22px 20px; }
            .edb-sidebar .nav-link { justify-content: flex-start; padding: 10px 13px; }
            .edb-search input { width: 150px; }
        }
    </style>
</head>
<body>
<div class="edb-bg-orbs" aria-hidden="true">
    <span class="orb orb-1"></span>
    <span class="orb orb-2"></span>
    <span class="orb orb-3"></span>
</div>
<div class="edb-sidebar">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand">
        <img class="brand-logo" src="<?php echo e(asset('images/edubba_app.png')); ?>" alt="<?php echo e($schoolName); ?>">
        <img class="brand-logo-mini" src="<?php echo e(asset('images/edubba_app_icon.png')); ?>" alt="<?php echo e($schoolName); ?>">
        <span class="brand-name"><?php echo e($schoolName); ?><span class="brand-sub"><?php echo $__env->yieldContent('title', __('dashboard')); ?></span></span>
    </a>

    <div class="nav-scroll">
        <div class="nav-section"><?php echo app('translator')->get('main'); ?></div>
        <a class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>"><i class="bi bi-grid-1x2-fill"></i><span><?php echo app('translator')->get('stats_dashboard'); ?></span></a>

        <div class="nav-section"><?php echo app('translator')->get('school_management'); ?></div>
        <a class="nav-link <?php echo e(request()->routeIs('admin.students.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.students.index')); ?>"><i class="bi bi-people-fill"></i><span><?php echo app('translator')->get('students'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.admissions.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.admissions.index')); ?>"><i class="bi bi-clipboard2-check-fill"></i><span><?php echo app('translator')->get('admissions'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.parents.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.parents.index')); ?>"><i class="bi bi-person-hearts"></i><span><?php echo app('translator')->get('parents'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.faculty.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.faculty.index')); ?>"><i class="bi bi-person-video3"></i><span><?php echo app('translator')->get('teaching_staff'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.courses.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.courses.index')); ?>"><i class="bi bi-book-fill"></i><span><?php echo app('translator')->get('subjects'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.batches.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.batches.index')); ?>"><i class="bi bi-diagram-3-fill"></i><span><?php echo app('translator')->get('classes'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.programs.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.programs.index')); ?>"><i class="bi bi-award-fill"></i><span><?php echo app('translator')->get('programs'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.academic-years.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.academic-years.index')); ?>"><i class="bi bi-calendar-range-fill"></i><span><?php echo app('translator')->get('academic_years'); ?></span></a>

        <div class="nav-section"><?php echo app('translator')->get('operations'); ?></div>
        <a class="nav-link <?php echo e(request()->routeIs('admin.fees.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.fees.structures')); ?>"><i class="bi bi-cash-stack"></i><span><?php echo app('translator')->get('fees_invoices'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.attendance.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.attendance.index')); ?>"><i class="bi bi-clipboard2-check-fill"></i><span><?php echo app('translator')->get('attendance'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.timetable.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.timetable.index')); ?>"><i class="bi bi-calendar2-week-fill"></i><span><?php echo app('translator')->get('timetable'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.calendar.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.calendar.index')); ?>"><i class="bi bi-calendar-heart-fill"></i><span><?php echo app('translator')->get('calendar_holidays'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.tutoring.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.tutoring.index')); ?>"><i class="bi bi-lightning-charge-fill"></i><span><?php echo app('translator')->get('private_tutoring'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.exams.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.exams.index')); ?>"><i class="bi bi-journal-bookmark-fill"></i><span><?php echo app('translator')->get('exams'); ?></span></a>
        <a class="nav-link <?php echo e(request()->routeIs('admin.reports.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.reports.index')); ?>"><i class="bi bi-graph-up-arrow"></i><span><?php echo app('translator')->get('ministry_reports'); ?></span></a>

        <div class="nav-section"><?php echo app('translator')->get('system'); ?></div>
        <a class="nav-link <?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.settings.index')); ?>"><i class="bi bi-gear-fill"></i><span><?php echo app('translator')->get('settings'); ?></span></a>
    </div>

    <div class="nav-footer">
        <div class="user-chip">
            <span class="avatar"><?php echo e(mb_substr(Auth::user()->name, 0, 1)); ?></span>
            <span class="u-txt">
                <span class="d-block fw-bold small"><?php echo e(Auth::user()->name); ?></span>
                <span class="d-block" style="font-size:.62rem;color:#525f7f"><?php echo app('translator')->get('system_admin'); ?></span>
            </span>
        </div>
    </div>
</div>

<div class="edb-main">
    <nav class="edb-topbar">
        <button class="edb-icon-btn d-lg-none" type="button" onclick="toggleMobileSidebar(true)"><i class="bi bi-list"></i></button>
        <button class="edb-icon-btn d-none d-lg-grid" type="button" onclick="toggleCollapse()" title="<?php echo app('translator')->get('collapse'); ?>"><i class="bi bi-layout-sidebar-inset"></i></button>

        <div class="d-none d-md-flex align-items-center gap-2" style="flex:1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="bi bi-house-door-fill me-1"></i><?php echo app('translator')->get('home'); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $__env->yieldContent('page', __('dashboard')); ?></li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto d-flex align-items-center gap-2">
            <div class="edb-search d-none d-lg-block">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="<?php echo app('translator')->get('search_placeholder'); ?>">
            </div>

            <div class="dropdown">
                <button class="edb-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo app('translator')->get('language'); ?>">
                    <i class="bi bi-globe2"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="<?php echo e(route('language.switch', 'ar')); ?>"><span class="me-2"><?php echo e(app()->getLocale() === 'ar' ? '✓' : ''); ?></span><?php echo app('translator')->get('arabic'); ?></a>
                    <a class="dropdown-item" href="<?php echo e(route('language.switch', 'en')); ?>"><span class="me-2"><?php echo e(app()->getLocale() === 'en' ? '✓' : ''); ?></span><?php echo app('translator')->get('english'); ?></a>
                </div>
            </div>

            <button class="edb-icon-btn" type="button" onclick="toggleTheme()" id="themeBtn" title="<?php echo app('translator')->get('dark_mode'); ?>"><i class="bi bi-moon-stars-fill"></i></button>

            <div class="dropdown">
                <button class="edb-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo app('translator')->get('notifications'); ?>">
                    <i class="bi bi-bell-fill"></i><span class="dot"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" style="width:320px">
                    <div class="px-3 py-2 fw-bold small border-bottom" style="border-color:var(--edb-border) !important"><?php echo app('translator')->get('notifications'); ?></div>
                    <div class="empty-state py-4"><i class="bi bi-bell-slash"></i><p><?php echo app('translator')->get('no_notifications'); ?></p></div>
                </div>
            </div>

            <div class="dropdown">
                <button class="edb-icon-btn rounded-3 p-0 border-0" style="width:42px;height:42px;background:transparent;border:none !important" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar" style="width:42px;height:42px;border-radius:12px"><?php echo e(mb_substr(Auth::user()->name, 0, 1)); ?></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" style="min-width:220px">
                    <div class="px-3 py-2 border-bottom mb-1" style="border-color:var(--edb-border) !important">
                        <span class="d-block fw-bold small"><?php echo e(Auth::user()->name); ?></span>
                        <span class="d-block text-secondary" style="font-size:.72rem">admin@edubba.test</span>
                    </div>
                    <a class="dropdown-item" href="<?php echo e(route('admin.settings.index')); ?>"><i class="bi bi-gear"></i> <?php echo app('translator')->get('settings'); ?></a>
                    <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right"></i> <?php echo app('translator')->get('logout'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="edb-content">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <footer class="text-center py-4" style="color:var(--edb-text-3);font-size:.74rem;font-weight:500">
        <?php echo e($schoolName); ?> © <?php echo e(date('Y')); ?> — <?php echo app('translator')->get('school_management_system'); ?>
    </footer>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

<div class="edb-palette-overlay" id="edbPaletteOverlay"></div>
<div class="edb-palette" id="edbPalette">
    <div class="bg-body rounded-5 border" style="border-color:var(--edb-border-strong);overflow:hidden">
        <div class="position-relative">
            <i class="bi bi-search position-absolute" style="top:50%;transform:translateY(-50%);inset-inline-start:18px;color:var(--edb-text-3)"></i>
            <input class="palette-input" id="edbPaletteInput" placeholder="<?php echo app('translator')->get('search_placeholder'); ?>  <?php echo app('translator')->get('esc_close'); ?>">
        </div>
        <div class="palette-results" id="paletteResults"></div>
    </div>
</div>

<div class="edb-fab">
    <div class="edb-fab-menu" id="edbFabMenu">
        <a href="<?php echo e(route('admin.students.create')); ?>"><i class="bi bi-person-plus-fill"></i> <?php echo app('translator')->get('add_student'); ?></a>
        <a href="<?php echo e(route('admin.fees.invoices')); ?>"><i class="bi bi-cash-coin"></i> <?php echo app('translator')->get('register_payment'); ?></a>
        <a href="<?php echo e(route('admin.admissions.create')); ?>"><i class="bi bi-journal-plus"></i> <?php echo app('translator')->get('admission_request'); ?></a>
        <a href="<?php echo e(route('admin.exams.index')); ?>"><i class="bi bi-journal-bookmark"></i> <?php echo app('translator')->get('new_exam'); ?></a>
    </div>
    <button class="edb-fab-main" type="button" onclick="toggleFab()" title="<?php echo app('translator')->get('quick_actions'); ?>"><i class="bi bi-plus-lg"></i></button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const root = document.documentElement;
    const mqDark = window.matchMedia('(prefers-color-scheme: dark)');

    function applyTheme(t) { root.setAttribute('data-bs-theme', t); updateThemeIcon(); }
    function toggleTheme() {
        const dark = root.getAttribute('data-bs-theme') === 'dark';
        const next = dark ? 'light' : 'dark';
        localStorage.setItem('edubba_theme', next);
        applyTheme(next);
    }
    function updateThemeIcon() {
        const dark = root.getAttribute('data-bs-theme') === 'dark';
        const btn = document.getElementById('themeBtn');
        if (btn) btn.innerHTML = '<i class="bi ' + (dark ? 'bi-sun-fill' : 'bi-moon-stars-fill') + '"></i>';
    }
    (function initTheme() {
        const saved = localStorage.getItem('edubba_theme');
        applyTheme(saved || (mqDark.matches ? 'dark' : 'light'));
        if (!saved) {
            mqDark.addEventListener('change', e => applyTheme(e.matches ? 'dark' : 'light'));
        }
    })();

    function toggleCollapse() {
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('edubba_collapsed', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
    }
    function toggleMobileSidebar(open) {
        document.body.classList.toggle('sidebar-mobile-open', open);
        if (open) document.addEventListener('click', closeMobileOnOutside, { once: true });
    }
    function closeMobileOnOutside(e) {
        if (!e.target.closest('.edb-sidebar') && !e.target.closest('.edb-icon-btn')) {
            document.body.classList.remove('sidebar-mobile-open');
        }
    }
    (function () {
        if (localStorage.getItem('edubba_collapsed') === '1' && window.innerWidth > 991) document.body.classList.add('sidebar-collapsed');
    })();

    function showToast(msg, type) {
        type = type || 'success';
        const icons = { success: 'bi-check-circle-fill', error: 'bi-x-circle-fill', info: 'bi-info-circle-fill' };
        const el = document.createElement('div');
        el.className = 'toast align-items-center border-0 text-bg-' + (type === 'info' ? 'primary' : type === 'error' ? 'danger' : 'success');
        el.setAttribute('role', 'alert');
        el.innerHTML = '<div class="d-flex"><div class="toast-body d-flex align-items-center gap-2"><i class="bi ' + (icons[type] || icons.info) + '"></i>' + msg + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
        document.getElementById('toastContainer').appendChild(el);
        const toast = new bootstrap.Toast(el, { delay: 4000 });
        toast.show();
        el.addEventListener('hidden.bs.toast', () => el.remove());
    }
    <?php if(session('success')): ?>
        document.addEventListener('DOMContentLoaded', () => showToast(<?php echo e(Js::from(session('success'))); ?>, 'success'));
    <?php endif; ?>
    <?php if(session('error')): ?>
        document.addEventListener('DOMContentLoaded', () => showToast(<?php echo e(Js::from(session('error'))); ?>, 'error'));
    <?php endif; ?>

    const paletteItems = [
        { label: '<?php echo app('translator')->get('stats_dashboard'); ?>', icon: 'bi-grid-1x2-fill', url: '<?php echo e(route('admin.dashboard')); ?>' },
        { label: '<?php echo app('translator')->get('students'); ?>', icon: 'bi-people-fill', url: '<?php echo e(route('admin.students.index')); ?>' },
        { label: '<?php echo app('translator')->get('admissions'); ?>', icon: 'bi-clipboard2-check-fill', url: '<?php echo e(route('admin.admissions.index')); ?>' },
        { label: '<?php echo app('translator')->get('teaching_staff'); ?>', icon: 'bi-person-video3', url: '<?php echo e(route('admin.faculty.index')); ?>' },
        { label: '<?php echo app('translator')->get('fees_invoices'); ?>', icon: 'bi-cash-stack', url: '<?php echo e(route('admin.fees.structures')); ?>' },
        { label: '<?php echo app('translator')->get('attendance'); ?>', icon: 'bi-clipboard2-check-fill', url: '<?php echo e(route('admin.attendance.index')); ?>' },
        { label: '<?php echo app('translator')->get('timetable'); ?>', icon: 'bi-calendar2-week-fill', url: '<?php echo e(route('admin.timetable.index')); ?>' },
        { label: '<?php echo app('translator')->get('calendar_holidays'); ?>', icon: 'bi-calendar-heart-fill', url: '<?php echo e(route('admin.calendar.index')); ?>' },
        { label: '<?php echo app('translator')->get('exams'); ?>', icon: 'bi-journal-bookmark-fill', url: '<?php echo e(route('admin.exams.index')); ?>' },
        { label: '<?php echo app('translator')->get('settings'); ?>', icon: 'bi-gear-fill', url: '<?php echo e(route('admin.settings.index')); ?>' },
    ];
    function openPalette() {
        const p = document.getElementById('edbPalette');
        p.classList.add('open');
        document.getElementById('edbPaletteOverlay').classList.add('open');
        const inp = p.querySelector('.palette-input');
        inp.value = '';
        renderPalette(paletteItems);
        inp.focus();
    }
    function closePalette() {
        document.getElementById('edbPalette').classList.remove('open');
        document.getElementById('edbPaletteOverlay').classList.remove('open');
    }
    function renderPalette(items) {
        const box = document.getElementById('paletteResults');
        box.innerHTML = items.length
            ? items.map(it => '<div class="palette-item" onclick="location.href=\'' + it.url + '\'"><i class="bi ' + it.icon + '"></i><span>' + it.label + '</span></div>').join('')
            : '<div class="empty-state py-3"><i class="bi bi-search"></i><p><?php echo app('translator')->get('no_results'); ?></p></div>';
    }
    document.getElementById('edbPaletteInput').addEventListener('input', e => {
        const q = e.target.value.trim().toLowerCase();
        renderPalette(q ? paletteItems.filter(it => it.label.includes(q)) : paletteItems);
    });
    document.getElementById('edbPaletteOverlay').addEventListener('click', closePalette);
    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); openPalette(); }
        if (e.key === 'Escape') closePalette();
        if (e.key === '?') { openPalette(); }
    });

    function toggleFab() { document.getElementById('edbFabMenu').classList.toggle('open'); }
    document.addEventListener('click', e => {
        if (!e.target.closest('.edb-fab')) document.getElementById('edbFabMenu').classList.remove('open');
    });

    function confirmAction(message, cb) {
        if (window.confirm(message)) cb();
    }

    (function initMotion() {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduced) return;

        const io = new IntersectionObserver((entries) => {
            entries.forEach(en => {
                if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
            });
        }, { threshold: 0.06, rootMargin: '0px 0px -40px 0px' });
        document.querySelectorAll('.edb-content .card').forEach(el => {
            el.classList.add('reveal');
            io.observe(el);
        });

        function animateCount(el) {
            const target = parseFloat(el.dataset.count);
            if (isNaN(target)) return;
            const dur = 1200;
            const start = performance.now();
            function tick(now) {
                const p = Math.min((now - start) / dur, 1);
                const eased = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(target * eased).toLocaleString('en-US');
                if (p < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        }
        document.querySelectorAll('.stat-value.num').forEach(animateCount);

        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mousemove', e => {
                const r = card.getBoundingClientRect();
                card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
                card.style.setProperty('--my', (e.clientY - r.top) + 'px');
            });
        });
    })();
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>