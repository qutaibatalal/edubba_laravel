@php
    $primaryColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
    $appName = config('app.name', 'إدبة');
    $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => App\Models\MobileAppConfig::configValue('school_name', 'مدرسة إدبة'));
    $locale = Session::has('locale') ? session('locale') : 'ar';
    $primaryRgb = sscanf($primaryColor, '#%02x%02x%02x');
    $primaryRgb = $primaryRgb[0] . ',' . $primaryRgb[1] . ',' . $primaryRgb[2];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('dashboard')) — {{ $appName }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/edubba_app_icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Kufi+Arabic:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --p: {{ $primaryColor }};
            --p-rgb: {{ $primaryRgb }};
            --edb-sidebar-w: 272px;
            --edb-sidebar-collapsed-w: 78px;
            --edb-radius: 16px;
            --edb-radius-sm: 10px;
            --edb-radius-xs: 8px;
            --bg-0: #08080d;
            --bg-1: #0e0f16;
            --bg-2: rgba(255,255,255,0.03);
            --bg-3: rgba(255,255,255,0.05);
            --border: rgba(255,255,255,0.06);
            --border-h: rgba(255,255,255,0.1);
            --t1: #f0f0f6;
            --t2: rgba(255,255,255,0.55);
            --t3: rgba(255,255,255,0.3);
            --glass: rgba(12,13,20,0.75);
            --glass-border: rgba(255,255,255,0.06);
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.4);
            --shadow: 0 1px 3px rgba(0,0,0,0.5), 0 2px 8px rgba(0,0,0,0.4);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.6);
            --shadow-lg: 0 12px 40px -8px rgba(0,0,0,0.8);
            --shadow-xl: 0 24px 64px -16px rgba(0,0,0,0.9);
            --transition-fast: 150ms cubic-bezier(.4,0,.2,1);
            --transition-base: 200ms cubic-bezier(.4,0,.2,1);
            --transition-smooth: 300ms cubic-bezier(.4,0,.2,1);
            --transition-spring: 500ms cubic-bezier(.22,.68,.31,1);
            /* Keep old var names mapped for child views */
            --edb-primary: {{ $primaryColor }};
            --edb-primary-rgb: {{ $primaryRgb }};
            --edb-bg: var(--bg-0);
            --edb-bg-elevated: var(--bg-1);
            --edb-border: var(--border);
            --edb-border-strong: var(--border-h);
            --edb-text-1: var(--t1);
            --edb-text-2: var(--t2);
            --edb-text-3: var(--t3);
            --edb-shadow-sm: var(--shadow-sm);
            --edb-shadow: var(--shadow);
            --edb-shadow-md: var(--shadow-md);
            --edb-shadow-lg: var(--shadow-lg);
            --edb-shadow-xl: var(--shadow-xl);
            --edb-glass: var(--glass);
            --edb-glass-border: var(--glass-border);
        }

        * { font-family: 'Inter', 'Noto Kufi Arabic', system-ui, sans-serif; }
        [dir="rtl"] * { font-family: 'Noto Kufi Arabic', 'Inter', system-ui, sans-serif; }
        body { background: var(--bg-0); min-height: 100vh; color: var(--t1); -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; overflow-x: hidden; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.14); }
        .num { font-variant-numeric: tabular-nums; }
        @media (prefers-reduced-motion: reduce) { * { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; } }

        /* ============ Atmosphere layer ============ */
        .atmo {
            position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden;
        }
        .atmo-grad {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 10%, rgba(var(--p-rgb),0.10) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 80% 80%, rgba(139,92,246,0.07) 0%, transparent 60%),
                radial-gradient(ellipse 40% 35% at 50% 50%, rgba(236,72,153,0.04) 0%, transparent 60%);
            animation: atmoGradDrift 30s ease-in-out infinite alternate;
        }
        @keyframes atmoGradDrift {
            0% { transform: translate(0,0) scale(1); }
            33% { transform: translate(15px,-10px) scale(1.02); }
            66% { transform: translate(-10px,12px) scale(0.98); }
            100% { transform: translate(5px,-5px) scale(1.01); }
        }
        .atmo-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 60px 60px;
            opacity: 0.5;
        }
        .atmo-orb {
            position: absolute; border-radius: 50%; filter: blur(80px); will-change: transform;
        }
        .atmo-orb--a {
            width: 400px; height: 400px; top: -100px; inset-inline-end: -80px;
            background: radial-gradient(circle, rgba(var(--p-rgb),0.18), transparent 70%);
            animation: orbFloat 20s ease-in-out infinite;
        }
        .atmo-orb--b {
            width: 320px; height: 320px; bottom: -80px; inset-inline-start: -60px;
            background: radial-gradient(circle, rgba(139,92,246,0.10), transparent 70%);
            animation: orbDrift 28s ease-in-out infinite;
        }
        .atmo-orb--c {
            width: 260px; height: 260px; top: 45%; inset-inline-start: 40%;
            background: radial-gradient(circle, rgba(236,72,153,0.06), transparent 70%);
            animation: orbFloat 34s ease-in-out infinite reverse;
        }
        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(12px, -18px); }
        }
        @keyframes orbDrift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(25px, 18px) scale(1.06); }
        }

        /* ============ Sidebar ============ */
        .edb-sidebar {
            position: fixed; top: 0; bottom: 0; inset-inline-start: 0; z-index: 1045;
            width: var(--edb-sidebar-w); display: flex; flex-direction: column;
            background: rgba(10,11,18,0.92);
            backdrop-filter: blur(24px) saturate(1.3); -webkit-backdrop-filter: blur(24px) saturate(1.3);
            border-inline-start: 1px solid rgba(255,255,255,0.06);
            transition: width var(--transition-smooth), transform var(--transition-smooth);
            overflow: hidden;
        }
        .edb-sidebar .brand {
            display: flex; align-items: center; gap: 14px; padding: 24px 22px 20px;
            color: #fff; text-decoration: none; white-space: nowrap;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .edb-sidebar .brand .brand-logo { height: 30px; width: auto; max-width: 150px; object-fit: contain; flex-shrink: 0; transition: transform var(--transition-smooth); filter: brightness(1.1); }
        .edb-sidebar .brand:hover .brand-logo { transform: scale(1.03); }
        .edb-sidebar .brand .brand-logo-mini { display: none; width: 38px; height: 38px; flex-shrink: 0; border-radius: 12px; object-fit: cover; }
        .edb-sidebar .brand .brand-name { font-weight: 800; font-size: 1.02rem; line-height: 1.25; min-width: 0; overflow: hidden; text-overflow: ellipsis; color: var(--t1); }
        .edb-sidebar .brand .brand-sub { font-size: .62rem; color: var(--t3); font-weight: 600; letter-spacing: .06em; display: block; margin-top: 2px; }
        .edb-sidebar .nav-scroll { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 6px 14px 20px; }
        .edb-sidebar .nav-scroll::-webkit-scrollbar { width: 3px; }
        .edb-sidebar .nav-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.06); }
        .edb-sidebar .nav-section {
            padding: 22px 12px 8px; font-size: .58rem; font-weight: 700; letter-spacing: .16em;
            text-transform: uppercase; color: rgba(255,255,255,0.18); white-space: nowrap;
        }
        .edb-sidebar .nav-link {
            display: flex; align-items: center; gap: 13px; padding: 10px 13px; margin: 1px 0;
            color: rgba(255,255,255,0.4); border-radius: var(--edb-radius-xs); font-weight: 600; font-size: .84rem;
            transition: all var(--transition-fast); position: relative; white-space: nowrap; text-decoration: none;
        }
        .edb-sidebar .nav-link i { font-size: 1.05rem; min-width: 20px; text-align: center; opacity: .7; transition: opacity var(--transition-fast); }
        .edb-sidebar .nav-link:hover { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.75); }
        .edb-sidebar .nav-link:hover i { opacity: 1; }
        .edb-sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--p), #7c3aed);
            color: #fff; font-weight: 700;
            box-shadow: 0 4px 20px -4px rgba(var(--p-rgb),0.45), 0 0 30px -10px rgba(124,58,237,0.3);
        }
        .edb-sidebar .nav-link.active i { opacity: 1; }
        .edb-sidebar .nav-footer {
            padding: 14px; border-top: 1px solid rgba(255,255,255,0.06);
        }
        .edb-sidebar .nav-footer .user-chip {
            display: flex; align-items: center; gap: 11px; color: rgba(255,255,255,0.7);
            white-space: nowrap; padding: 8px 10px; border-radius: var(--edb-radius-xs);
            transition: background var(--transition-fast);
            background: rgba(255,255,255,0.03);
        }
        .edb-sidebar .nav-footer .user-chip:hover { background: rgba(255,255,255,0.06); }
        .edb-sidebar .nav-footer .avatar {
            width: 36px; height: 36px; flex-shrink: 0; border-radius: 11px;
            background: linear-gradient(135deg, var(--p), #7c3aed); color: #fff;
            display: grid; place-items: center; font-weight: 800; font-size: .85rem;
            box-shadow: 0 2px 10px rgba(var(--p-rgb),0.35);
        }

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
        .edb-main {
            margin-inline-start: var(--edb-sidebar-w); margin-inline-end: 0;
            transition: margin var(--transition-smooth); min-height: 100vh; display: flex; flex-direction: column;
        }
        body.sidebar-collapsed .edb-main { margin-inline-start: var(--edb-sidebar-collapsed-w); }

        .edb-topbar {
            position: sticky; top: 0; z-index: 1030; display: flex; align-items: center; gap: 14px;
            padding: 14px 32px; min-height: 68px;
            background: rgba(12,13,20,0.75);
            backdrop-filter: blur(24px) saturate(1.4); -webkit-backdrop-filter: blur(24px) saturate(1.4);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .edb-topbar .breadcrumb { margin: 0; font-size: .8rem; }
        .edb-topbar .breadcrumb-item a { color: var(--t3); text-decoration: none; font-weight: 500; transition: color var(--transition-fast); }
        .edb-topbar .breadcrumb-item a:hover { color: var(--t1); }
        .edb-topbar .breadcrumb-item.active { color: var(--t2); font-weight: 600; }
        .edb-topbar .breadcrumb-item + .breadcrumb-item::before { color: var(--t3); }

        .edb-icon-btn {
            width: 40px; height: 40px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.04); color: var(--t2); display: grid; place-items: center; font-size: 1rem;
            transition: all var(--transition-fast); position: relative; cursor: pointer;
        }
        .edb-icon-btn:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.1); color: var(--t1); transform: translateY(-1px); box-shadow: var(--shadow-sm); }
        .edb-icon-btn .dot {
            position: absolute; top: 8px; inset-inline-end: 9px; width: 8px; height: 8px;
            border-radius: 50%; background: #ef4444;
            border: 2px solid rgba(12,13,20,0.9); animation: edbPulse 2.2s ease-in-out infinite;
        }

        .edb-search { position: relative; }
        .edb-search input {
            width: min(320px, 36vw); border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.04); padding-block: 9px; padding-inline: 40px 16px;
            font-size: .84rem; color: var(--t1); transition: all var(--transition-fast);
        }
        .edb-search input::placeholder { color: var(--t3); }
        .edb-search input:focus {
            outline: none; border-color: var(--p);
            box-shadow: 0 0 0 3px rgba(var(--p-rgb),0.15), 0 0 20px -4px rgba(var(--p-rgb),0.15);
            background: rgba(255,255,255,0.06);
        }
        .edb-search i { position: absolute; inset-inline-start: 14px; top: 50%; transform: translateY(-50%); color: var(--t3); font-size: .88rem; }

        .edb-content {
            flex: 1; padding: 30px 32px; position: relative; z-index: 1;
            animation: edbFade .4s var(--transition-spring);
        }
        @keyframes edbFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
        @media (max-width: 1199px) { .edb-content { padding: 20px; } }

        .edb-content > * { animation: edbRise .5s cubic-bezier(.22,.68,.31,1) both; }
        .edb-content > *:nth-child(2) { animation-delay: .05s; }
        .edb-content > *:nth-child(3) { animation-delay: .1s; }
        .edb-content > *:nth-child(4) { animation-delay: .15s; }
        .edb-content > *:nth-child(5) { animation-delay: .2s; }
        .edb-content > *:nth-child(6) { animation-delay: .25s; }

        /* ============ Cards ============ */
        .card {
            border: 1px solid rgba(255,255,255,0.06); border-radius: var(--edb-radius);
            box-shadow: var(--shadow); background: #0e0f16;
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast), transform var(--transition-fast);
        }
        .card.hoverable:hover { box-shadow: var(--shadow-lg); transform: translateY(-3px); border-color: rgba(255,255,255,0.1); }
        .card-header {
            background: transparent; border-bottom: 1px solid rgba(255,255,255,0.06); padding: 18px 24px;
            font-weight: 700; font-size: .92rem; color: var(--t1);
            display: flex; align-items: center; gap: 8px;
        }
        .card-body { padding: 24px; color: var(--t1); }
        .card-footer { background: transparent; border-top: 1px solid rgba(255,255,255,0.06); padding: 14px 24px; }

        .page-header { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px; margin-bottom: 28px; }
        .page-header h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; margin: 0; color: var(--t1); }
        .page-header p { margin: 5px 0 0; color: var(--t2); font-size: .88rem; font-weight: 500; }

        /* ============ Buttons ============ */
        .btn { border-radius: 12px; font-weight: 700; transition: all var(--transition-fast); font-size: .88rem; letter-spacing: -.01em; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary {
            background: linear-gradient(135deg, var(--p), #7c3aed); border-color: transparent;
            box-shadow: 0 2px 12px -2px rgba(var(--p-rgb),0.45), 0 0 20px -6px rgba(124,58,237,0.25);
            color: #fff;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--p), #7c3aed); filter: brightness(1.1);
            box-shadow: 0 4px 20px -4px rgba(var(--p-rgb),0.55), 0 0 30px -8px rgba(124,58,237,0.35);
            color: #fff;
        }
        .btn-outline-primary { color: var(--p); border-color: rgba(var(--p-rgb),0.3); background: transparent; }
        .btn-outline-primary:hover { background: var(--p); border-color: var(--p); color: #fff; }
        .btn-outline-secondary { color: var(--t2); border-color: rgba(255,255,255,0.1); background: transparent; }
        .btn-outline-secondary:hover { background: rgba(255,255,255,0.06); color: var(--t1); border-color: rgba(255,255,255,0.15); }
        .btn-outline-danger { color: #ef4444; border-color: rgba(239,68,68,0.3); background: transparent; }
        .btn-outline-danger:hover { background: #ef4444; border-color: #ef4444; color: #fff; }
        .btn-outline-success { color: #10b981; border-color: rgba(16,185,129,0.3); background: transparent; }
        .btn-outline-success:hover { background: #10b981; border-color: #10b981; color: #fff; }
        .btn-outline-info { color: #0ea5e9; border-color: rgba(14,165,233,0.3); background: transparent; }
        .btn-outline-info:hover { background: #0ea5e9; border-color: #0ea5e9; color: #fff; }
        .btn-outline-warning { color: #f59e0b; border-color: rgba(245,158,11,0.3); background: transparent; }
        .btn-outline-warning:hover { background: #f59e0b; border-color: #f59e0b; color: #fff; }
        .btn-sm { border-radius: 10px; font-size: .8rem; padding: .35rem .85rem; }
        .btn-primary, .btn-outline-primary { position: relative; overflow: hidden; }
        .btn-primary::after, .btn-outline-primary::after {
            content: ''; position: absolute; top: 0; bottom: 0; width: 45%; left: 0; opacity: 0; pointer-events: none;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        }
        .btn-primary:hover::after, .btn-outline-primary:hover::after { animation: edbShine .85s ease forwards; }

        /* ============ Forms ============ */
        .form-control, .form-select {
            border-radius: 12px; padding: 10px 16px; font-size: .88rem;
            border-color: rgba(255,255,255,0.08); background: rgba(255,255,255,0.04);
            color: var(--t1); transition: all var(--transition-fast);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--p); background: rgba(255,255,255,0.06); color: var(--t1);
            box-shadow: 0 0 0 3px rgba(var(--p-rgb),0.15), 0 0 20px -4px rgba(var(--p-rgb),0.12);
        }
        .form-control::placeholder { color: var(--t3); }
        .form-label { font-weight: 700; font-size: .82rem; margin-bottom: 7px; color: var(--t2); letter-spacing: -.01em; }
        .form-check-input { background-color: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.15); }
        .form-check-input:checked { background-color: var(--p); border-color: var(--p); }
        .form-check-input:focus { box-shadow: 0 0 0 3px rgba(var(--p-rgb),0.15); }
        .form-check-label { color: var(--t1); }
        .form-text { color: var(--t3); }
        textarea.form-control { background: rgba(255,255,255,0.04); }

        /* ============ Tables ============ */
        .table-edb { border-collapse: separate; border-spacing: 0; color: var(--t1); }
        .table-edb thead th {
            font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
            color: var(--t3); border-bottom: 1px solid rgba(255,255,255,0.08); padding: 12px 18px;
            white-space: nowrap; background: transparent;
        }
        .table-edb td { vertical-align: middle; padding: 14px 18px; font-size: .86rem; border-color: rgba(255,255,255,0.04); color: var(--t1); }
        .table-edb tbody tr { transition: background var(--transition-fast); }
        .table-edb tbody tr:hover { background: rgba(255,255,255,0.03); }
        .table-edb tbody tr + tr { border-top: 1px solid rgba(255,255,255,0.04); }
        .table { color: var(--t1); }
        .table > :not(caption) > * > * { background-color: transparent; color: var(--t1); }
        .table-striped > tbody > tr:nth-of-type(odd) > td { background: rgba(255,255,255,0.015); }
        .table-bordered { border-color: rgba(255,255,255,0.06); }
        .table-bordered > :not(caption) > * > * { border-color: rgba(255,255,255,0.06); }

        /* ============ Badges ============ */
        .badge { border-radius: 999px; font-weight: 700; padding: .32em .8em; font-size: .7rem; letter-spacing: .01em; }
        .badge-soft { background: rgba(255,255,255,0.08); color: #cbd5e1; }
        .badge-soft-success { background: rgba(16,185,129,0.15); color: #34d399; }
        .badge-soft-danger { background: rgba(239,68,68,0.15); color: #f87171; }
        .badge-soft-warning { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .badge-soft-info { background: rgba(14,165,233,0.15); color: #38bdf8; }
        .badge-soft-primary { background: rgba(var(--p-rgb),0.18); color: #a5b4fc; }
        .badge-soft-purple { background: rgba(139,92,246,0.15); color: #c4b5fd; }

        /* ============ Avatars ============ */
        .avatar { width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0; display: grid; place-items: center; font-weight: 800; font-size: .78rem; }
        .avatar-sm { width: 30px; height: 30px; border-radius: 9px; font-size: .68rem; }
        .avatar-lg { width: 68px; height: 68px; border-radius: 18px; font-size: 1.5rem; }
        .grad-1 { background: rgba(129,122,255,0.14); color: #a5b4fc; }
        .grad-2 { background: rgba(45,212,191,0.12); color: #2dd4bf; }
        .grad-3 { background: rgba(56,189,248,0.12); color: #38bdf8; }
        .grad-4 { background: rgba(251,191,36,0.12); color: #fbbf24; }
        .grad-5 { background: rgba(248,113,113,0.12); color: #f87171; }
        .grad-6 { background: rgba(196,181,253,0.12); color: #c4b5fd; }

        /* ============ KPI stat cards ============ */
        .stat-card { position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; inset-inline-start: 0; top: 0; bottom: 0; width: 3px; border-radius: 0 3px 3px 0; }
        .stat-card.st-1::before { background: linear-gradient(180deg, #6366f1, #4f46e5); }
        .stat-card.st-2::before { background: linear-gradient(180deg, #2dd4bf, #0d9488); }
        .stat-card.st-3::before { background: linear-gradient(180deg, #38bdf8, #0284c7); }
        .stat-card.st-4::before { background: linear-gradient(180deg, #fbbf24, #d97706); }
        .stat-card.st-5::before { background: linear-gradient(180deg, #f87171, #dc2626); }
        .stat-card.st-6::before { background: linear-gradient(180deg, #c4b5fd, #7c3aed); }
        .stat-card .stat-body { display: flex; align-items: center; gap: 16px; padding: 20px 24px 20px 26px; }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 14px; display: grid; place-items: center;
            font-size: 1.2rem; flex-shrink: 0;
        }
        .stat-card .stat-value { font-size: 1.55rem; font-weight: 800; letter-spacing: -.03em; line-height: 1.05; color: var(--t1); }
        .stat-card .stat-label { font-size: .78rem; color: var(--t2); font-weight: 600; margin-top: 3px; }
        .stat-card.st-1 .stat-icon { background: rgba(99,102,241,0.12); color: #818cf8; }
        .stat-card.st-2 .stat-icon { background: rgba(45,212,191,0.12); color: #2dd4bf; }
        .stat-card.st-3 .stat-icon { background: rgba(56,189,248,0.12); color: #38bdf8; }
        .stat-card.st-4 .stat-icon { background: rgba(251,191,36,0.12); color: #fbbf24; }
        .stat-card.st-5 .stat-icon { background: rgba(248,113,113,0.12); color: #f87171; }
        .stat-card.st-6 .stat-icon { background: rgba(196,181,253,0.12); color: #c4b5fd; }
        .stat-card .stat-icon { animation: edbPop .6s cubic-bezier(.22,.68,.31,1) both; }
        .stat-card::after {
            content: ''; position: absolute; inset: 0; pointer-events: none; opacity: 0;
            transition: opacity .5s ease; border-radius: inherit;
            background: radial-gradient(300px circle at var(--mx, 50%) var(--my, 50%), rgba(255,255,255,0.06), transparent 65%);
        }
        .stat-card:hover::after { opacity: 1; }

        /* Bento tiles */
        .bento { display: grid; gap: 20px; grid-template-columns: repeat(12, 1fr); }
        .bento .b-4 { grid-column: span 4; } .bento .b-5 { grid-column: span 5; } .bento .b-6 { grid-column: span 6; }
        .bento .b-7 { grid-column: span 7; } .bento .b-8 { grid-column: span 8; }
        @media (max-width: 1199px) { .bento .b-4, .bento .b-5, .bento .b-6, .bento .b-7, .bento .b-8 { grid-column: span 12; } }
        @media (min-width: 1200px) { .bento .b-sm { grid-column: span 4; } }

        .empty-state { text-align: center; padding: 48px 24px; color: var(--t2); }
        .empty-state i { font-size: 2.4rem; color: var(--t3); display: block; margin-bottom: 14px; opacity: .6; }
        .empty-state p { margin: 0 0 5px; font-weight: 700; color: var(--t2); }
        .empty-state small { color: var(--t3); display: block; margin-bottom: 16px; }

        /* ============ Pagination ============ */
        .pagination { margin: 14px 0; display: flex; gap: 6px; flex-wrap: wrap; }
        .pagination .page-link {
            border-radius: var(--edb-radius-xs) !important; padding: 7px 14px !important; margin: 0;
            font-weight: 700 !important; font-size: .82rem !important; color: var(--t2) !important;
            background: rgba(255,255,255,0.04) !important; border: 1px solid rgba(255,255,255,0.06) !important;
            transition: all var(--transition-fast) !important; min-width: 38px !important; height: 38px !important;
            display: inline-flex !important; align-items: center !important; justify-content: center !important;
            text-decoration: none !important; line-height: 1 !important;
        }
        .pagination .page-link:hover {
            color: var(--p) !important; border-color: var(--p) !important;
            background: rgba(var(--p-rgb),0.1) !important; transform: translateY(-1px); box-shadow: var(--shadow-sm);
        }
        .pagination .page-link.active {
            color: #fff !important; background: linear-gradient(135deg, var(--p), #7c3aed) !important; border-color: transparent !important;
            box-shadow: 0 4px 16px -4px rgba(var(--p-rgb),0.45) !important;
        }

        /* ============ Dropdowns ============ */
        .dropdown-menu {
            border-radius: 14px; border: 1px solid rgba(255,255,255,0.08); box-shadow: var(--shadow-lg);
            background: rgba(14,15,22,0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            padding: 6px; z-index: 1050;
        }
        .dropdown-item {
            border-radius: 10px; margin: 1px 4px; padding: 9px 14px; font-size: .84rem; font-weight: 600;
            color: var(--t2); transition: all var(--transition-fast);
        }
        .dropdown-item:hover { background: rgba(var(--p-rgb),0.1); color: var(--t1); }
        .dropdown-item i { width: 20px; }
        .dropdown-item.text-danger { color: #f87171 !important; }
        .dropdown-item.text-danger:hover { background: rgba(239,68,68,0.12); color: #f87171 !important; }

        /* ============ Skeleton shimmer ============ */
        .skeleton-line { position: relative; overflow: hidden; border-radius: 6px; background: rgba(255,255,255,0.04); }
        .skeleton-line::after { content: ''; position: absolute; inset: 0; transform: translateX(-100%); background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent); animation: edbShine 1.4s ease infinite; }

        /* ============ Command palette ============ */
        .edb-palette-overlay {
            position: fixed; inset: 0; z-index: 1090; background: rgba(0,0,0,0.6);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); display: none;
        }
        .edb-palette {
            position: fixed; top: 14%; inset-inline: 0; max-width: 580px; margin: 0 auto;
            z-index: 1091; display: none;
        }
        .edb-palette.open, .edb-palette-overlay.open { display: block; animation: edbFade .2s ease; }
        .edb-palette .palette-input {
            width: 100%; padding: 18px 20px; font-size: 1rem; border: 0; outline: none;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            border-radius: 18px 18px 0 0; background: #0e0f16; color: var(--t1);
        }
        .edb-palette .palette-input::placeholder { color: var(--t3); }
        .edb-palette .palette-results {
            max-height: 400px; overflow-y: auto; border-radius: 0 0 18px 18px; background: #0e0f16;
            border: 1px solid rgba(255,255,255,0.06); border-top: none;
        }
        .edb-palette .palette-item {
            display: flex; align-items: center; gap: 12px; padding: 12px 20px; cursor: pointer;
            transition: background var(--transition-fast); color: var(--t2);
        }
        .edb-palette .palette-item:hover, .edb-palette .palette-item.selected {
            background: rgba(var(--p-rgb),0.1); color: var(--t1);
        }
        .edb-palette .palette-item i { color: var(--t3); }

        /* ============ FAB ============ */
        .edb-fab { position: fixed; bottom: 24px; inset-inline-end: 24px; z-index: 1035; }
        .edb-fab-main {
            width: 54px; height: 54px; border-radius: 16px; border: 0;
            background: linear-gradient(135deg, var(--p), #7c3aed); color: #fff; font-size: 1.4rem;
            box-shadow: 0 8px 28px -6px rgba(var(--p-rgb),0.55), 0 0 30px -8px rgba(124,58,237,0.3);
            display: grid; place-items: center; transition: all var(--transition-fast); cursor: pointer;
        }
        .edb-fab-main:hover { transform: scale(1.05) translateY(-2px); box-shadow: 0 12px 36px -8px rgba(var(--p-rgb),0.65), 0 0 40px -10px rgba(124,58,237,0.4); }
        .edb-fab-menu {
            position: absolute; bottom: 68px; inset-inline-end: 0; min-width: 220px;
            background: rgba(14,15,22,0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08); border-radius: 16px;
            box-shadow: var(--shadow-xl); padding: 8px; display: none;
        }
        .edb-fab-menu.open { display: block; animation: edbPop .3s var(--transition-spring); }
        .edb-fab-menu a {
            display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 10px;
            text-decoration: none; color: var(--t2); font-size: .84rem; font-weight: 600;
            transition: all var(--transition-fast);
        }
        .edb-fab-menu a:hover { background: rgba(var(--p-rgb),0.1); color: var(--t1); }
        .edb-fab-menu a i { width: 20px; color: var(--t3); }

        /* ============ Motion & animations ============ */
        @keyframes edbRise { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }
        @keyframes edbPop { 0% { transform: scale(.6); opacity: 0; } 60% { transform: scale(1.06); opacity: 1; } 100% { transform: scale(1); opacity: 1; } }
        @keyframes edbShine { from { transform: translateX(-130%) skewX(-20deg); } to { transform: translateX(230%) skewX(-20deg); } }
        @keyframes edbPulse { 0%, 100% { opacity: .4; transform: scale(1); } 50% { opacity: 1; transform: scale(1.4); } }

        .reveal { opacity: 0; transform: translateY(20px); transition: opacity .5s cubic-bezier(.22,.68,.31,1), transform .5s cubic-bezier(.22,.68,.31,1); }
        .reveal.is-visible { opacity: 1; transform: none; }

        .edb-sidebar .brand .brand-logo, .edb-sidebar .brand .brand-logo-mini { animation: orbFloat 8s ease-in-out infinite; }

        /* ============ Alerts ============ */
        .alert { border-radius: var(--edb-radius); font-weight: 600; font-size: .86rem; border: 1px solid transparent; }
        .alert-success { background: rgba(16,185,129,0.1); color: #34d399; border-color: rgba(16,185,129,0.15); }
        .alert-danger { background: rgba(239,68,68,0.1); color: #f87171; border-color: rgba(239,68,68,0.15); }
        .alert-warning { background: rgba(245,158,11,0.1); color: #fbbf24; border-color: rgba(245,158,11,0.15); }
        .alert-info { background: rgba(14,165,233,0.1); color: #38bdf8; border-color: rgba(14,165,233,0.15); }
        .alert-secondary { background: rgba(255,255,255,0.05); color: var(--t2); border-color: rgba(255,255,255,0.06); }

        /* ============ List group ============ */
        .list-group-item { border-color: rgba(255,255,255,0.06); background: transparent; color: var(--t1); transition: background var(--transition-fast); }
        .list-group-item:hover { background: rgba(255,255,255,0.03); }

        /* ============ Toast ============ */
        .toast { border-radius: var(--edb-radius) !important; border: 1px solid rgba(255,255,255,0.08) !important; background: rgba(14,15,22,0.95) !important; backdrop-filter: blur(16px); color: var(--t1) !important; }
        .toast .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        /* ============ Switch ============ */
        .form-check-input { width: 2.5em; height: 1.3em; border-radius: 2em; transition: all var(--transition-fast); }

        /* ============ Modal ============ */
        .modal-content { border-radius: var(--edb-radius); border: 1px solid rgba(255,255,255,0.08); box-shadow: var(--shadow-xl); background: #0e0f16; color: var(--t1); }
        .modal-header { border-bottom: 1px solid rgba(255,255,255,0.06); padding: 18px 24px; }
        .modal-header .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
        .modal-body { padding: 24px; }
        .modal-footer { border-top: 1px solid rgba(255,255,255,0.06); padding: 14px 24px; }
        .modal-backdrop.show { background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); }

        /* ============ Progress ============ */
        .progress { border-radius: 8px; background: rgba(255,255,255,0.06); }
        .progress-bar { border-radius: 8px; }

        /* ============ Accordion ============ */
        .accordion { --bs-accordion-bg: #0e0f16; --bs-accordion-border-color: rgba(255,255,255,0.06); --bs-accordion-btn-color: var(--t1); --bs-accordion-btn-bg: #0e0f16; --bs-accordion-active-bg: #0e0f16; }
        .accordion-item { background: #0e0f16; border-color: rgba(255,255,255,0.06); }

        /* ============ Text utilities ============ */
        .text-secondary { color: var(--t2) !important; }
        .text-body-secondary { color: var(--t2) !important; }
        .text-muted { color: var(--t3) !important; }

        /* ============ Responsive ============ */
        @media (max-width: 991px) {
            [dir="rtl"] .edb-sidebar { transform: translateX(100%); }
            [dir="ltr"] .edb-sidebar { transform: translateX(-100%); }
            .edb-sidebar { width: var(--edb-sidebar-w) !important; }
            body.sidebar-mobile-open .edb-sidebar { transform: translateX(0) !important; box-shadow: var(--shadow-xl); }
            body.sidebar-mobile-open::after { content: ''; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1044; backdrop-filter: blur(6px); }
            .edb-main, body.sidebar-collapsed .edb-main { margin-inline-start: 0; margin-inline-end: 0; }
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
<div class="atmo" aria-hidden="true">
    <div class="atmo-grad"></div>
    <div class="atmo-grid"></div>
    <div class="atmo-orb atmo-orb--a"></div>
    <div class="atmo-orb atmo-orb--b"></div>
    <div class="atmo-orb atmo-orb--c"></div>
</div>
<div class="edb-sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand">
        <img class="brand-logo" src="{{ asset('images/edubba_app.png') }}" alt="{{ $schoolName }}">
        <img class="brand-logo-mini" src="{{ asset('images/edubba_app_icon.png') }}" alt="{{ $schoolName }}">
        <span class="brand-name">{{ $schoolName }}<span class="brand-sub">@yield('title', __('dashboard'))</span></span>
    </a>

    <div class="nav-scroll">
        <div class="nav-section">@lang('main')</div>
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i><span>@lang('stats_dashboard')</span></a>

        <div class="nav-section">@lang('school_management')</div>
        <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}"><i class="bi bi-people-fill"></i><span>@lang('students')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.admissions.*') ? 'active' : '' }}" href="{{ route('admin.admissions.index') }}"><i class="bi bi-clipboard2-check-fill"></i><span>@lang('admissions')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.parents.*') ? 'active' : '' }}" href="{{ route('admin.parents.index') }}"><i class="bi bi-person-hearts"></i><span>@lang('parents')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.faculty.*') ? 'active' : '' }}" href="{{ route('admin.faculty.index') }}"><i class="bi bi-person-video3"></i><span>@lang('teaching_staff')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}"><i class="bi bi-book-fill"></i><span>@lang('subjects')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.batches.*') ? 'active' : '' }}" href="{{ route('admin.batches.index') }}"><i class="bi bi-diagram-3-fill"></i><span>@lang('classes')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}" href="{{ route('admin.programs.index') }}"><i class="bi bi-award-fill"></i><span>@lang('programs')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.academic-years.*') ? 'active' : '' }}" href="{{ route('admin.academic-years.index') }}"><i class="bi bi-calendar-range-fill"></i><span>@lang('academic_years')</span></a>

        <div class="nav-section">@lang('operations')</div>
        <a class="nav-link {{ request()->routeIs('admin.fees.*') ? 'active' : '' }}" href="{{ route('admin.fees.structures') }}"><i class="bi bi-cash-stack"></i><span>@lang('fees_invoices')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}" href="{{ route('admin.attendance.index') }}"><i class="bi bi-clipboard2-check-fill"></i><span>@lang('attendance')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.timetable.*') ? 'active' : '' }}" href="{{ route('admin.timetable.index') }}"><i class="bi bi-calendar2-week-fill"></i><span>@lang('timetable')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}" href="{{ route('admin.calendar.index') }}"><i class="bi bi-calendar-heart-fill"></i><span>@lang('calendar_holidays')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.tutoring.*') ? 'active' : '' }}" href="{{ route('admin.tutoring.index') }}"><i class="bi bi-lightning-charge-fill"></i><span>@lang('private_tutoring')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.exams.*') ? 'active' : '' }}" href="{{ route('admin.exams.index') }}"><i class="bi bi-journal-bookmark-fill"></i><span>@lang('exams')</span></a>
        <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}"><i class="bi bi-graph-up-arrow"></i><span>@lang('ministry_reports')</span></a>

        <div class="nav-section">@lang('system')</div>
        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear-fill"></i><span>@lang('settings')</span></a>
    </div>

    <div class="nav-footer">
        <div class="user-chip">
            <span class="avatar">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
            <span class="u-txt">
                <span class="d-block fw-bold small">{{ Auth::user()->name }}</span>
                <span class="d-block" style="font-size:.62rem;color:var(--t3)">@lang('system_admin')</span>
            </span>
        </div>
    </div>
</div>

<div class="edb-main">
    <nav class="edb-topbar">
        <button class="edb-icon-btn d-lg-none" type="button" onclick="toggleMobileSidebar(true)"><i class="bi bi-list"></i></button>
        <button class="edb-icon-btn d-none d-lg-grid" type="button" onclick="toggleCollapse()" title="@lang('collapse')"><i class="bi bi-layout-sidebar-inset"></i></button>

        <div class="d-none d-md-flex align-items-center gap-2" style="flex:1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>@lang('home')</a></li>
                    <li class="breadcrumb-item active">@yield('page', __('dashboard'))</li>
                </ol>
            </nav>
        </div>

        <div class="ms-auto d-flex align-items-center gap-2">
            <div class="edb-search d-none d-lg-block">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="@lang('search_placeholder')">
            </div>

            <div class="dropdown">
                <button class="edb-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="@lang('language')">
                    <i class="bi bi-globe2"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('language.switch', 'ar') }}"><span class="me-2">{{ app()->getLocale() === 'ar' ? '✓' : '' }}</span>@lang('arabic')</a>
                    <a class="dropdown-item" href="{{ route('language.switch', 'en') }}"><span class="me-2">{{ app()->getLocale() === 'en' ? '✓' : '' }}</span>@lang('english')</a>
                </div>
            </div>

            <button class="edb-icon-btn" type="button" onclick="toggleTheme()" id="themeBtn" title="@lang('dark_mode')"><i class="bi bi-moon-stars-fill"></i></button>

            <div class="dropdown">
                <button class="edb-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="@lang('notifications')">
                    <i class="bi bi-bell-fill"></i><span class="dot"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" style="width:320px">
                    <div class="px-3 py-2 fw-bold small" style="border-bottom:1px solid rgba(255,255,255,0.06)">@lang('notifications')</div>
                    <div class="empty-state py-4"><i class="bi bi-bell-slash"></i><p>@lang('no_notifications')</p></div>
                </div>
            </div>

            <div class="dropdown">
                <button class="edb-icon-btn rounded-3 p-0 border-0" style="width:42px;height:42px;background:transparent;border:none !important" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar" style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--p),#7c3aed)">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" style="min-width:220px">
                    <div class="px-3 py-2 mb-1" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                        <span class="d-block fw-bold small" style="color:var(--t1)">{{ Auth::user()->name }}</span>
                        <span class="d-block" style="font-size:.72rem;color:var(--t3)">admin@edubba.test</span>
                    </div>
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear"></i> @lang('settings')</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right"></i> @lang('logout')</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="edb-content">
        @yield('content')
    </div>

    <footer class="text-center py-4" style="color:var(--t3);font-size:.74rem;font-weight:500">
        {{ $schoolName }} © {{ date('Y') }} — @lang('school_management_system')
    </footer>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

<div class="edb-palette-overlay" id="edbPaletteOverlay"></div>
<div class="edb-palette" id="edbPalette">
    <div style="border-radius:20px;overflow:hidden;box-shadow:var(--shadow-xl);border:1px solid rgba(255,255,255,0.08)">
        <div class="position-relative">
            <i class="bi bi-search position-absolute" style="top:50%;transform:translateY(-50%);inset-inline-start:18px;color:var(--t3)"></i>
            <input class="palette-input" id="edbPaletteInput" placeholder="@lang('search_placeholder')  @lang('esc_close')">
        </div>
        <div class="palette-results" id="paletteResults"></div>
    </div>
</div>

<div class="edb-fab">
    <div class="edb-fab-menu" id="edbFabMenu">
        <a href="{{ route('admin.students.create') }}"><i class="bi bi-person-plus-fill"></i> @lang('add_student')</a>
        <a href="{{ route('admin.fees.invoices') }}"><i class="bi bi-cash-coin"></i> @lang('register_payment')</a>
        <a href="{{ route('admin.admissions.create') }}"><i class="bi bi-journal-plus"></i> @lang('admission_request')</a>
        <a href="{{ route('admin.exams.index') }}"><i class="bi bi-journal-bookmark"></i> @lang('new_exam')</a>
    </div>
    <button class="edb-fab-main" type="button" onclick="toggleFab()" title="@lang('quick_actions')"><i class="bi bi-plus-lg"></i></button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const root = document.documentElement;

    function applyTheme(t) { root.setAttribute('data-bs-theme', 'dark'); }
    function toggleTheme() { /* always dark */ }
    function updateThemeIcon() {
        const btn = document.getElementById('themeBtn');
        if (btn) btn.innerHTML = '<i class="bi bi-moon-stars-fill"></i>';
    }
    (function initTheme() {
        applyTheme('dark');
        updateThemeIcon();
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
    @if (session('success'))
        document.addEventListener('DOMContentLoaded', () => showToast({{ Js::from(session('success')) }}, 'success'));
    @endif
    @if (session('error'))
        document.addEventListener('DOMContentLoaded', () => showToast({{ Js::from(session('error')) }}, 'error'));
    @endif

    const paletteItems = [
        { label: '@lang('stats_dashboard')', icon: 'bi-grid-1x2-fill', url: '{{ route('admin.dashboard') }}' },
        { label: '@lang('students')', icon: 'bi-people-fill', url: '{{ route('admin.students.index') }}' },
        { label: '@lang('admissions')', icon: 'bi-clipboard2-check-fill', url: '{{ route('admin.admissions.index') }}' },
        { label: '@lang('teaching_staff')', icon: 'bi-person-video3', url: '{{ route('admin.faculty.index') }}' },
        { label: '@lang('fees_invoices')', icon: 'bi-cash-stack', url: '{{ route('admin.fees.structures') }}' },
        { label: '@lang('attendance')', icon: 'bi-clipboard2-check-fill', url: '{{ route('admin.attendance.index') }}' },
        { label: '@lang('timetable')', icon: 'bi-calendar2-week-fill', url: '{{ route('admin.timetable.index') }}' },
        { label: '@lang('calendar_holidays')', icon: 'bi-calendar-heart-fill', url: '{{ route('admin.calendar.index') }}' },
        { label: '@lang('exams')', icon: 'bi-journal-bookmark-fill', url: '{{ route('admin.exams.index') }}' },
        { label: '@lang('settings')', icon: 'bi-gear-fill', url: '{{ route('admin.settings.index') }}' },
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
            : '<div class="empty-state py-3"><i class="bi bi-search"></i><p>@lang('no_results')</p></div>';
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
@stack('scripts')
</body>
</html>
