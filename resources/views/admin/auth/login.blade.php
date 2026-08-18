@php
    $primaryColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
    $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => App\Models\MobileAppConfig::configValue('school_name', 'مدرسة إدبة'));
    $primaryRgb = sscanf($primaryColor, '#%02x%02x%02x');
    $primaryRgb = $primaryRgb[0] . ',' . $primaryRgb[1] . ',' . $primaryRgb[2];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('login') — {{ $schoolName }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/edubba_app_icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&family=Noto+Kufi+Arabic:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    @if(app()->getLocale() === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: {{ $primaryColor }};
            --primary-rgb: {{ $primaryRgb }};
            --surface: rgba(255,255,255,0.03);
            --surface-hover: rgba(255,255,255,0.06);
            --border: rgba(255,255,255,0.08);
            --border-focus: rgba(255,255,255,0.2);
            --text-primary: #f0f0f5;
            --text-secondary: rgba(255,255,255,0.55);
            --text-tertiary: rgba(255,255,255,0.35);
            --radius-sm: 10px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --transition-fast: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-spring: 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            [dir="rtl"] & { font-family: 'Noto Kufi Arabic', 'Inter', system-ui, sans-serif; }
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: #0a0a0f; color: var(--text-primary);
            -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;
            overflow: hidden; position: relative;
        }

        /* ── Ambient Background ── */
        .ambient-bg {
            position: fixed; inset: 0; z-index: 0; overflow: hidden;
        }
        .ambient-bg::before {
            content: ''; position: absolute; width: 140%; height: 140%;
            top: -20%; left: -20%;
            background:
                radial-gradient(ellipse 800px 600px at 20% 30%, rgba(var(--primary-rgb), 0.15), transparent),
                radial-gradient(ellipse 600px 800px at 80% 70%, rgba(139, 92, 246, 0.1), transparent),
                radial-gradient(ellipse 500px 500px at 50% 50%, rgba(236, 72, 153, 0.06), transparent);
            animation: ambientShift 25s ease-in-out infinite alternate;
        }
        @keyframes ambientShift {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(3%, -2%) scale(1.02); }
            66% { transform: translate(-2%, 3%) scale(0.98); }
            100% { transform: translate(1%, -1%) scale(1.01); }
        }

        /* ── Grid Texture ── */
        .grid-texture {
            position: fixed; inset: 0; z-index: 1; opacity: 0.03;
            background-image:
                linear-gradient(rgba(255,255,255,0.5) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.5) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse at 50% 50%, black 30%, transparent 70%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 50%, black 30%, transparent 70%);
        }

        /* ── Floating Orbs ── */
        .orb {
            position: fixed; border-radius: 50%; z-index: 1;
            filter: blur(80px); opacity: 0.4;
        }
        .orb-1 {
            width: 500px; height: 500px; top: -15%; left: -10%;
            background: rgba(var(--primary-rgb), 0.25);
            animation: orbFloat1 20s ease-in-out infinite;
        }
        .orb-2 {
            width: 400px; height: 400px; bottom: -10%; right: -5%;
            background: rgba(139, 92, 246, 0.2);
            animation: orbFloat2 24s ease-in-out infinite;
        }
        .orb-3 {
            width: 300px; height: 300px; top: 50%; left: 60%;
            background: rgba(236, 72, 153, 0.12);
            animation: orbFloat3 18s ease-in-out infinite;
        }
        @keyframes orbFloat1 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(40px, 30px); }
        }
        @keyframes orbFloat2 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-30px, -40px); }
        }
        @keyframes orbFloat3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-20px, 20px) scale(1.1); }
        }

        /* ── Noise Overlay ── */
        .noise-overlay {
            position: fixed; inset: 0; z-index: 2; opacity: 0.015;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 128px 128px; pointer-events: none;
        }

        /* ── Main Layout ── */
        .login-portal {
            position: relative; z-index: 10;
            width: min(1080px, 94vw); min-height: 620px;
            display: grid; grid-template-columns: 1fr 1fr;
            border-radius: var(--radius-xl); overflow: hidden;
            border: 1px solid var(--border);
            animation: portalReveal 0.8s cubic-bezier(0.22, 0.68, 0.31, 1);
            backdrop-filter: blur(40px); -webkit-backdrop-filter: blur(40px);
        }
        @keyframes portalReveal {
            from { opacity: 0; transform: translateY(40px) scale(0.96); filter: blur(8px); }
            to { opacity: 1; transform: none; filter: blur(0); }
        }

        /* ── Brand Panel ── */
        .brand-panel {
            position: relative; padding: 56px; display: flex;
            flex-direction: column; justify-content: space-between;
            background: linear-gradient(165deg,
                rgba(var(--primary-rgb), 0.12) 0%,
                rgba(139, 92, 246, 0.08) 50%,
                rgba(236, 72, 153, 0.05) 100%);
            border-right: 1px solid var(--border);
            overflow: hidden;
        }
        .brand-panel::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.3) 100%);
        }
        .brand-panel::after {
            content: ''; position: absolute;
            width: 500px; height: 500px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.04);
            bottom: -180px; right: -120px;
        }
        .brand-content { position: relative; z-index: 2; }

        /* ── Decorative Geometry ── */
        .brand-deco {
            position: absolute; z-index: 1; opacity: 0.06;
        }
        .brand-deco-circle-1 {
            width: 200px; height: 200px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.3);
            top: 40px; right: -60px;
        }
        .brand-deco-circle-2 {
            width: 120px; height: 120px; border-radius: 50%;
            background: rgba(255,255,255,0.05);
            bottom: 80px; left: -30px;
        }
        .brand-deco-line {
            width: 1px; height: 160px;
            background: linear-gradient(180deg, rgba(255,255,255,0.15), transparent);
            top: 20px; left: 56px;
        }
        .brand-deco-dots {
            display: grid; grid-template-columns: repeat(4, 4px); gap: 12px;
            bottom: 140px; right: 40px;
        }
        .brand-deco-dots span {
            width: 4px; height: 4px; border-radius: 50%;
            background: rgba(255,255,255,0.2);
        }

        /* ── Brand Typography ── */
        .brand-logo-wrap {
            margin-bottom: 48px;
        }
        .brand-logo {
            width: min(240px, 85%); height: auto; display: block;
            filter: drop-shadow(0 4px 20px rgba(0,0,0,0.3));
            transition: transform var(--transition-smooth);
        }
        .brand-panel:hover .brand-logo {
            transform: scale(1.03) translateY(-2px);
        }
        .brand-title {
            font-size: 2rem; font-weight: 800; letter-spacing: -0.03em;
            line-height: 1.15; color: var(--text-primary);
            margin-bottom: 12px;
        }
        .brand-subtitle {
            font-size: 0.9rem; font-weight: 300; line-height: 1.7;
            color: var(--text-secondary); max-width: 320px;
        }

        /* ── Feature Cards ── */
        .brand-features {
            display: flex; flex-direction: column; gap: 10px;
            margin-top: 40px;
        }
        .brand-feat {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 16px; border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
            transition: all var(--transition-fast);
            cursor: default;
        }
        .brand-feat:hover {
            background: rgba(255,255,255,0.06);
            border-color: rgba(255,255,255,0.1);
            transform: translateX(4px);
        }
        [dir="rtl"] .brand-feat:hover { transform: translateX(-4px); }
        .brand-feat-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(var(--primary-rgb), 0.15);
            display: grid; place-items: center; flex-shrink: 0;
            color: rgba(var(--primary-rgb), 0.9); font-size: 0.85rem;
        }
        .brand-feat-text {
            font-size: 0.82rem; font-weight: 500;
            color: var(--text-secondary); letter-spacing: 0.01em;
        }

        .brand-footer {
            position: relative; z-index: 2;
            font-size: 0.72rem; font-weight: 400;
            color: var(--text-tertiary); letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* ── Form Panel ── */
        .form-panel {
            padding: 56px; display: flex; flex-direction: column;
            justify-content: center; position: relative;
            background: rgba(10, 10, 18, 0.6);
        }
        .form-panel::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.02), transparent);
            pointer-events: none;
        }

        .form-header { margin-bottom: 40px; position: relative; }
        .form-header-top {
            display: flex; justify-content: space-between; align-items: flex-start;
        }
        .form-title {
            font-size: 1.75rem; font-weight: 800; letter-spacing: -0.03em;
            color: var(--text-primary); margin-bottom: 8px;
        }
        .form-subtitle {
            font-size: 0.88rem; font-weight: 300;
            color: var(--text-secondary); line-height: 1.6;
        }

        /* ── Language Switcher ── */
        .lang-btn {
            width: 40px; height: 40px; border-radius: var(--radius-sm);
            border: 1px solid var(--border); background: var(--surface);
            color: var(--text-secondary); display: grid; place-items: center;
            cursor: pointer; transition: all var(--transition-fast);
            font-size: 0.9rem; flex-shrink: 0;
        }
        .lang-btn:hover {
            background: var(--surface-hover);
            border-color: var(--border-focus);
            color: var(--text-primary);
        }
        .lang-dropdown {
            display: none; position: fixed;
            background: rgba(20, 20, 30, 0.95);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 6px;
            min-width: 160px; z-index: 9999;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            list-style: none; margin: 0;
        }
        .lang-dropdown a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: var(--radius-sm);
            text-decoration: none; color: var(--text-secondary);
            font-size: 0.82rem; font-weight: 500;
            transition: all var(--transition-fast);
        }
        .lang-dropdown a:hover {
            background: rgba(255,255,255,0.06);
            color: var(--text-primary);
        }
        .lang-dropdown .active-check {
            width: 18px; text-align: center;
            color: var(--primary); font-weight: 700;
        }

        /* ── Error Alert ── */
        .alert-refined {
            padding: 14px 18px; border-radius: var(--radius-sm);
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
            color: #fca5a5; font-size: 0.82rem; font-weight: 500;
            margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
            animation: alertSlide 0.3s ease;
        }
        @keyframes alertSlide {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: none; }
        }

        /* ── Form Fields ── */
        .field-group { margin-bottom: 20px; }
        .field-label {
            display: block; font-size: 0.75rem; font-weight: 600;
            color: var(--text-secondary); letter-spacing: 0.06em;
            text-transform: uppercase; margin-bottom: 10px;
        }
        .field-input-wrap {
            position: relative;
        }
        .field-icon {
            position: absolute; top: 50%; transform: translateY(-50%);
            inset-inline-start: 16px;
            color: var(--text-tertiary); font-size: 0.85rem;
            transition: color var(--transition-fast);
            pointer-events: none;
        }
        .field-input {
            width: 100%; padding: 14px 16px 14px 48px;
            [dir="rtl"] & { padding: 14px 48px 14px 16px; }
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.03);
            color: var(--text-primary);
            font-size: 0.88rem; font-weight: 400;
            font-family: inherit;
            transition: all var(--transition-fast);
            outline: none;
        }
        .field-input::placeholder {
            color: var(--text-tertiary); font-weight: 300;
        }
        .field-input:hover {
            border-color: rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.04);
        }
        .field-input:focus {
            border-color: var(--primary);
            background: rgba(255,255,255,0.05);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.12);
        }
        .field-input:focus ~ .field-icon {
            color: var(--primary);
        }

        /* ── Remember & Forgot ── */
        .form-options {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 28px;
        }
        .remember-check {
            display: flex; align-items: center; gap: 10px;
            cursor: pointer; user-select: none;
        }
        .remember-check input[type="checkbox"] {
            display: none;
        }
        .remember-check .check-visual {
            width: 18px; height: 18px; border-radius: 5px;
            border: 1.5px solid var(--border);
            background: rgba(255,255,255,0.03);
            display: grid; place-items: center;
            transition: all var(--transition-fast);
            flex-shrink: 0;
        }
        .remember-check .check-visual::after {
            content: ''; width: 8px; height: 5px;
            border-left: 1.5px solid #fff; border-bottom: 1.5px solid #fff;
            transform: rotate(-45deg) translateY(-1px);
            opacity: 0; transition: opacity var(--transition-fast);
        }
        .remember-check input:checked + .check-visual {
            background: var(--primary); border-color: var(--primary);
        }
        .remember-check input:checked + .check-visual::after {
            opacity: 1;
        }
        .remember-check .check-label {
            font-size: 0.82rem; font-weight: 500; color: var(--text-secondary);
        }
        .forgot-link {
            font-size: 0.82rem; font-weight: 500;
            color: var(--primary); text-decoration: none;
            transition: all var(--transition-fast);
            position: relative;
        }
        .forgot-link::after {
            content: ''; position: absolute; bottom: -2px;
            inset-inline-start: 0; width: 0; height: 1px;
            background: var(--primary);
            transition: width var(--transition-fast);
        }
        .forgot-link:hover::after { width: 100%; }
        .forgot-link:hover { color: rgba(var(--primary-rgb), 0.8); }

        /* ── Submit Button ── */
        .btn-submit {
            width: 100%; padding: 15px 24px;
            border: none; border-radius: var(--radius-sm);
            font-family: inherit; font-size: 0.88rem; font-weight: 700;
            letter-spacing: 0.02em; color: #fff;
            background: linear-gradient(135deg, var(--primary), rgba(139, 92, 246, 0.9));
            cursor: pointer; position: relative; overflow: hidden;
            transition: all var(--transition-fast);
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-submit::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0; transition: opacity var(--transition-fast);
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 40px -8px rgba(var(--primary-rgb), 0.4);
        }
        .btn-submit:hover::before { opacity: 1; }
        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 4px 16px -4px rgba(var(--primary-rgb), 0.3);
        }
        .btn-submit .btn-arrow {
            transition: transform var(--transition-fast);
        }
        .btn-submit:hover .btn-arrow {
            transform: translateX(3px);
        }
        [dir="rtl"] .btn-submit:hover .btn-arrow {
            transform: translateX(-3px);
        }

        /* ── Demo Box ── */
        .demo-info {
            margin-top: 24px; padding: 14px 18px;
            border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--border);
            font-size: 0.78rem; color: var(--text-tertiary);
            display: flex; align-items: center; gap: 8px;
        }
        .demo-info i { font-size: 0.85rem; opacity: 0.6; }
        .demo-info code {
            background: rgba(255,255,255,0.06);
            padding: 2px 8px; border-radius: 5px;
            font-weight: 600; font-size: 0.76rem;
            color: var(--text-secondary);
        }

        /* ── Loading State ── */
        .btn-submit.loading {
            pointer-events: none; opacity: 0.7;
        }
        .btn-submit.loading .btn-text { opacity: 0; }
        .btn-submit.loading .btn-spinner {
            position: absolute; width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff; border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Mobile ── */
        @media (max-width: 820px) {
            .login-portal {
                grid-template-columns: 1fr; min-height: 0;
                border-radius: var(--radius-lg);
            }
            .brand-panel { display: none; }
            .form-panel { padding: 40px 32px; }
            .form-title { font-size: 1.5rem; }
        }
        @media (max-width: 480px) {
            .form-panel { padding: 32px 24px; }
            .form-title { font-size: 1.3rem; }
        }
    </style>
</head>
<body>
    <!-- Ambient Background -->
    <div class="ambient-bg"></div>
    <div class="grid-texture"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="noise-overlay"></div>

    <!-- Login Portal -->
    <div class="login-portal">
        <!-- Brand Panel -->
        <div class="brand-panel">
            <div class="brand-deco brand-deco-circle-1"></div>
            <div class="brand-deco brand-deco-circle-2"></div>
            <div class="brand-deco brand-deco-line"></div>
            <div class="brand-deco brand-deco-dots">
                <span></span><span></span><span></span><span></span>
                <span></span><span></span><span></span><span></span>
            </div>

            <div class="brand-content">
                <div class="brand-logo-wrap">
                    <img class="brand-logo" src="{{ asset('images/edubba_app.png') }}" alt="{{ $schoolName }}">
                </div>
                <div class="brand-title">@lang('school_management_system')</div>
                <div class="brand-subtitle">@lang('login_hero_desc')</div>

                <div class="brand-features">
                    <div class="brand-feat">
                        <div class="brand-feat-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="brand-feat-text">@lang('login_feat_students')</div>
                    </div>
                    <div class="brand-feat">
                        <div class="brand-feat-icon"><i class="bi bi-cash-stack"></i></div>
                        <div class="brand-feat-text">@lang('fees_invoices')</div>
                    </div>
                    <div class="brand-feat">
                        <div class="brand-feat-icon"><i class="bi bi-calendar2-week-fill"></i></div>
                        <div class="brand-feat-text">@lang('smart_timetable')</div>
                    </div>
                    <div class="brand-feat">
                        <div class="brand-feat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="brand-feat-text">@lang('ministry_reports')</div>
                    </div>
                </div>
            </div>

            <div class="brand-footer">© {{ date('Y') }} {{ $schoolName }}</div>
        </div>

        <!-- Form Panel -->
        <div class="form-panel">
            <div class="form-header">
                <div class="form-header-top">
                    <div>
                        <div class="form-title">@lang('welcome_back')</div>
                        <div class="form-subtitle">@lang('login_subtitle')</div>
                    </div>
                    <div style="position:relative">
                        <button class="lang-btn" type="button" onclick="toggleLangMenu(event)" title="@lang('language')">
                            <i class="bi bi-globe2"></i>
                        </button>
                        <ul class="lang-dropdown" id="langMenu">
                            <li>
                                <a href="{{ route('language.switch', 'ar') }}">
                                    <span class="active-check">{{ app()->getLocale() === 'ar' ? '✓' : '' }}</span>
                                    @lang('arabic')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('language.switch', 'en') }}">
                                    <span class="active-check">{{ app()->getLocale() === 'en' ? '✓' : '' }}</span>
                                    @lang('english')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert-refined">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    @foreach ($errors->all() as $error)<span>{{ $error }}</span> @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" id="loginForm">
                @csrf
                <div class="field-group">
                    <label class="field-label">@lang('email')</label>
                    <div class="field-input-wrap">
                        <i class="field-icon bi bi-envelope"></i>
                        <input type="email" name="email" class="field-input" value="{{ old('email') }}" placeholder="admin@school.com" required autofocus>
                    </div>
                </div>
                <div class="field-group">
                    <label class="field-label">@lang('password')</label>
                    <div class="field-input-wrap">
                        <i class="field-icon bi bi-lock"></i>
                        <input type="password" name="password" class="field-input" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-check">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="check-visual"></span>
                        <span class="check-label">@lang('remember_me')</span>
                    </label>
                    <a href="#" class="forgot-link">@lang('forgot_password')</a>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="btn-text">@lang('login')</span>
                    <i class="bi bi-arrow-right btn-arrow"></i>
                    <span class="btn-spinner" style="display:none"></span>
                </button>
            </form>

            <div class="demo-info">
                <i class="bi bi-info-circle"></i>
                @lang('demo_account'): <code>admin@edubba.test</code> / <code>password</code>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleLangMenu(e) {
            e.stopPropagation();
            var menu = document.getElementById('langMenu');
            if (menu.style.display === 'none' || !menu.style.display) {
                var btn = e.currentTarget;
                var rect = btn.getBoundingClientRect();
                menu.style.display = 'block';
                menu.style.top = (rect.bottom + 8) + 'px';
                menu.style.insetInlineEnd = (window.innerWidth - rect.right) + 'px';
                document.addEventListener('click', closeLangMenu, { once: true });
            } else {
                menu.style.display = 'none';
            }
        }
        function closeLangMenu() {
            var menu = document.getElementById('langMenu');
            if (menu) menu.style.display = 'none';
        }

        // Submit loading state
        document.getElementById('loginForm').addEventListener('submit', function() {
            var btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.querySelector('.btn-text').style.opacity = '0';
            btn.querySelector('.btn-arrow').style.display = 'none';
            btn.querySelector('.btn-spinner').style.display = 'block';
        });
    </script>
</body>
</html>
