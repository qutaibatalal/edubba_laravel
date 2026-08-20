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
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,200;14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Noto+Kufi+Arabic:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            --surface: rgba(255,255,255,0.04);
            --surface-hover: rgba(255,255,255,0.07);
            --border: rgba(255,255,255,0.08);
            --border-focus: rgba(255,255,255,0.2);
            --text-primary: #f1f1f6;
            --text-secondary: rgba(255,255,255,0.55);
            --text-tertiary: rgba(255,255,255,0.32);
            --radius-sm: 12px;
            --radius-md: 18px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
        }
        html { height: 100%; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100%; display: flex; align-items: center; justify-content: center;
            background: #08080d; color: var(--text-primary);
            -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;
            overflow: hidden; position: relative;
        }
        [dir="rtl"] body,
        [dir="rtl"] * { font-family: 'Noto Kufi Arabic', 'Inter', system-ui, sans-serif; }

        /* ═══ Ambient Atmosphere ═══ */
        .atmosphere {
            position: fixed; inset: 0; z-index: 0; overflow: hidden;
            background: #08080d;
        }
        .atmo-gradient {
            position: absolute; width: 160%; height: 160%;
            top: -30%; left: -30%;
            background:
                radial-gradient(ellipse 900px 700px at 18% 28%, rgba(var(--primary-rgb), 0.18), transparent 70%),
                radial-gradient(ellipse 700px 900px at 82% 72%, rgba(124, 58, 237, 0.12), transparent 70%),
                radial-gradient(ellipse 600px 600px at 55% 45%, rgba(236, 72, 153, 0.06), transparent 60%);
            animation: atmoDrift 30s ease-in-out infinite alternate;
        }
        @keyframes atmoDrift {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(2%, -1.5%) rotate(0.5deg); }
            100% { transform: translate(-1%, 1%) rotate(-0.3deg); }
        }
        .atmo-grid {
            position: absolute; inset: 0; opacity: 0.025;
            background-image:
                linear-gradient(rgba(255,255,255,0.4) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.4) 1px, transparent 1px);
            background-size: 72px 72px;
            mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, black, transparent);
            -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 50%, black, transparent);
        }
        .atmo-orb {
            position: absolute; border-radius: 50%;
            filter: blur(100px); will-change: transform;
        }
        .atmo-orb--a {
            width: 600px; height: 600px; top: -18%; left: -8%;
            background: rgba(var(--primary-rgb), 0.22);
            animation: orbA 22s ease-in-out infinite;
        }
        .atmo-orb--b {
            width: 480px; height: 480px; bottom: -12%; right: -4%;
            background: rgba(124, 58, 237, 0.18);
            animation: orbB 26s ease-in-out infinite;
        }
        .atmo-orb--c {
            width: 320px; height: 320px; top: 45%; left: 55%;
            background: rgba(236, 72, 153, 0.08);
            animation: orbC 20s ease-in-out infinite;
        }
        @keyframes orbA { 0%,100% { transform: translate(0,0); } 50% { transform: translate(30px,25px); } }
        @keyframes orbB { 0%,100% { transform: translate(0,0); } 50% { transform: translate(-25px,-35px); } }
        @keyframes orbC { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(-15px,20px) scale(1.08); } }
        .atmo-noise {
            position: absolute; inset: 0; opacity: 0.018; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 100px 100px;
        }
        #particleCanvas {
            position: absolute; inset: 0; width: 100%; height: 100%;
            z-index: 1; pointer-events: none;
        }

        /* ═══ Login Shell ═══ */
        .login-shell {
            position: relative; z-index: 10;
            width: min(1120px, 92vw); min-height: 640px;
            display: grid; grid-template-columns: 1.2fr 1fr;
            border-radius: var(--radius-xl); overflow: hidden;
            border: 1px solid rgba(255,255,255,0.06);
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.03),
                0 40px 100px -20px rgba(0,0,0,0.6),
                0 80px 160px -40px rgba(0,0,0,0.4);
            animation: shellReveal 0.9s cubic-bezier(0.22, 0.68, 0.31, 1) both;
        }
        @keyframes shellReveal {
            from { opacity: 0; transform: translateY(50px) scale(0.95); filter: blur(12px); }
            to { opacity: 1; transform: none; filter: blur(0); }
        }

        /* ═══ Brand Side ═══ */
        .side-brand {
            position: relative; padding: 60px;
            display: flex; flex-direction: column; justify-content: space-between;
            background:
                linear-gradient(170deg,
                    rgba(var(--primary-rgb), 0.1) 0%,
                    rgba(124, 58, 237, 0.06) 40%,
                    rgba(236, 72, 153, 0.03) 80%,
                    transparent 100%);
            border-right: 1px solid rgba(255,255,255,0.05);
            overflow: hidden;
        }
        .side-brand::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.2) 100%);
            pointer-events: none;
        }

        /* Decorative elements */
        .deco { position: absolute; pointer-events: none; }
        .deco-ring-1 {
            width: 280px; height: 280px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.04);
            bottom: -100px; right: -80px;
        }
        .deco-ring-2 {
            width: 160px; height: 160px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.03);
            top: 60px; left: -50px;
        }
        .deco-line-v {
            width: 1px; height: 180px;
            background: linear-gradient(180deg, rgba(255,255,255,0.08), transparent);
            top: 0; left: 60px;
        }
        .deco-dots {
            display: grid; grid-template-columns: repeat(5, 3px); gap: 14px;
            bottom: 120px; right: 48px; opacity: 0.15;
        }
        .deco-dots i {
            width: 3px; height: 3px; border-radius: 50%;
            background: rgba(255,255,255,0.8); display: block;
        }
        .deco-cross {
            width: 20px; height: 20px; top: 100px; right: 80px; opacity: 0.08;
        }
        .deco-cross::before, .deco-cross::after {
            content: ''; position: absolute; background: rgba(255,255,255,0.6);
        }
        .deco-cross::before { width: 100%; height: 1px; top: 50%; }
        .deco-cross::after { width: 1px; height: 100%; left: 50%; }

        .brand-content { position: relative; z-index: 2; display: flex; flex-direction: column; height: 100%; }
        .brand-top { flex: 1; }
        .brand-logo {
            width: min(220px, 80%); height: auto; display: block;
            filter: drop-shadow(0 6px 30px rgba(0,0,0,0.4));
            margin-bottom: 44px;
        }
        .brand-heading {
            font-size: 1.85rem; font-weight: 800; letter-spacing: -0.03em;
            line-height: 1.2; color: var(--text-primary); margin-bottom: 14px;
        }
        .brand-desc {
            font-size: 0.88rem; font-weight: 300; line-height: 1.75;
            color: var(--text-secondary); max-width: 340px;
        }

        .brand-features {
            display: flex; flex-direction: column; gap: 8px;
            margin-top: 44px;
        }
        .bf {
            display: flex; align-items: center; gap: 14px;
            padding: 11px 16px; border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.025);
            border: 1px solid rgba(255,255,255,0.04);
            transition: all 0.25s var(--ease);
        }
        .bf:hover {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.08);
            transform: translateX(4px);
        }
        [dir="rtl"] .bf:hover { transform: translateX(-4px); }
        .bf-icon {
            width: 34px; height: 34px; border-radius: 9px;
            background: rgba(var(--primary-rgb), 0.12);
            display: grid; place-items: center; flex-shrink: 0;
            font-size: 0.8rem; color: rgba(var(--primary-rgb), 0.85);
        }
        .bf-text { font-size: 0.8rem; font-weight: 500; color: var(--text-secondary); }

        .brand-foot {
            position: relative; z-index: 2;
            font-size: 0.7rem; font-weight: 400;
            color: var(--text-tertiary); letter-spacing: 0.06em;
            text-transform: uppercase; padding-top: 32px;
            border-top: 1px solid rgba(255,255,255,0.04);
        }

        /* ═══ Form Side ═══ */
        .side-form {
            padding: 60px; display: flex; flex-direction: column;
            justify-content: center; position: relative;
            background: rgba(12, 12, 20, 0.5);
        }
        .side-form::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.015), transparent 60%);
            pointer-events: none;
        }

        .form-head { margin-bottom: 36px; position: relative; z-index: 2; }
        .form-head-row {
            display: flex; justify-content: space-between; align-items: flex-start;
        }
        .form-greeting {
            font-size: 1.65rem; font-weight: 800; letter-spacing: -0.03em;
            color: var(--text-primary); margin-bottom: 8px;
        }
        .form-sub {
            font-size: 0.86rem; font-weight: 300;
            color: var(--text-secondary); line-height: 1.6;
        }

        /* Language */
        .lang-trigger {
            width: 38px; height: 38px; border-radius: var(--radius-sm);
            border: 1px solid var(--border); background: var(--surface);
            color: var(--text-secondary); display: grid; place-items: center;
            cursor: pointer; transition: all 0.2s var(--ease); font-size: 0.88rem;
            flex-shrink: 0;
        }
        .lang-trigger:hover {
            background: var(--surface-hover); border-color: var(--border-focus);
            color: var(--text-primary);
        }
        .lang-popup {
            display: none; position: fixed;
            background: rgba(18, 18, 28, 0.96);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: var(--radius-md); padding: 6px;
            min-width: 164px; z-index: 9999; list-style: none;
            box-shadow: 0 24px 64px rgba(0,0,0,0.55);
        }
        .lang-popup a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 10px;
            text-decoration: none; color: var(--text-secondary);
            font-size: 0.82rem; font-weight: 500;
            transition: all 0.15s var(--ease);
        }
        .lang-popup a:hover {
            background: rgba(255,255,255,0.06); color: var(--text-primary);
        }
        .lang-active { width: 18px; text-align: center; color: var(--primary); font-weight: 700; }

        /* Error */
        .err-alert {
            padding: 13px 18px; border-radius: var(--radius-sm);
            background: rgba(239, 68, 68, 0.07);
            border: 1px solid rgba(239, 68, 68, 0.12);
            color: #fca5a5; font-size: 0.8rem; font-weight: 500;
            margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
            animation: errSlide 0.3s ease;
        }
        @keyframes errSlide { from { opacity: 0; transform: translateY(-6px); } }

        /* Fields */
        .fld { margin-bottom: 22px; position: relative; z-index: 2; }
        .fld-label {
            display: block; font-size: 0.72rem; font-weight: 600;
            color: var(--text-secondary); letter-spacing: 0.07em;
            text-transform: uppercase; margin-bottom: 10px;
        }
        .fld-wrap { position: relative; }
        .fld-ico {
            position: absolute; top: 50%; transform: translateY(-50%);
            inset-inline-start: 16px;
            color: var(--text-tertiary); font-size: 0.82rem;
            transition: color 0.2s var(--ease); pointer-events: none;
        }
        .fld-input {
            width: 100%; padding: 14px 16px 14px 48px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.025);
            color: var(--text-primary);
            font-size: 0.88rem; font-weight: 400;
            font-family: inherit;
            transition: all 0.2s var(--ease); outline: none;
        }
        [dir="rtl"] .fld-input { padding: 14px 48px 14px 16px; }
        .fld-input::placeholder { color: var(--text-tertiary); font-weight: 300; }
        .fld-input:hover {
            border-color: rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.035);
        }
        .fld-input:focus {
            border-color: var(--primary);
            background: rgba(255,255,255,0.04);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
        }
        .fld-input:focus ~ .fld-ico { color: var(--primary); }

        /* Options row */
        .fld-opts {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 28px; position: relative; z-index: 2;
        }
        .chk { display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
        .chk input { display: none; }
        .chk-box {
            width: 18px; height: 18px; border-radius: 6px;
            border: 1.5px solid var(--border);
            background: rgba(255,255,255,0.025);
            display: grid; place-items: center; flex-shrink: 0;
            transition: all 0.2s var(--ease); position: relative;
        }
        .chk-box::after {
            content: ''; width: 8px; height: 5px;
            border-left: 1.5px solid #fff; border-bottom: 1.5px solid #fff;
            transform: rotate(-45deg) translateY(-1px);
            opacity: 0; transition: opacity 0.15s var(--ease);
        }
        .chk input:checked + .chk-box {
            background: var(--primary); border-color: var(--primary);
        }
        .chk input:checked + .chk-box::after { opacity: 1; }
        .chk-text { font-size: 0.82rem; font-weight: 500; color: var(--text-secondary); }
        .forgot {
            font-size: 0.82rem; font-weight: 500;
            color: var(--primary); text-decoration: none;
            transition: opacity 0.2s var(--ease);
        }
        .forgot:hover { opacity: 0.75; }

        /* Submit */
        .btn-go {
            width: 100%; padding: 15px 24px; border: none;
            border-radius: var(--radius-sm);
            font-family: inherit; font-size: 0.88rem; font-weight: 700;
            letter-spacing: 0.02em; color: #fff; cursor: pointer;
            background: linear-gradient(135deg, var(--primary), rgba(124, 58, 237, 0.85));
            position: relative; overflow: hidden;
            transition: all 0.25s var(--ease);
            display: flex; align-items: center; justify-content: center; gap: 10px;
            z-index: 2;
            box-shadow: 0 8px 32px -6px rgba(var(--primary-rgb), 0.3);
        }
        .btn-go::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent 60%);
            opacity: 0; transition: opacity 0.25s var(--ease);
        }
        .btn-go:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 48px -6px rgba(var(--primary-rgb), 0.4);
        }
        .btn-go:hover::before { opacity: 1; }
        .btn-go:active { transform: translateY(0); box-shadow: 0 6px 20px -4px rgba(var(--primary-rgb), 0.3); }
        .btn-arrow {
            transition: transform 0.25s var(--ease); font-size: 0.85rem;
        }
        .btn-go:hover .btn-arrow { transform: translateX(3px); }
        [dir="rtl"] .btn-go:hover .btn-arrow { transform: translateX(-3px); }
        .btn-go.loading { pointer-events: none; opacity: 0.7; }
        .btn-go.loading .btn-label { opacity: 0; }
        .btn-go.loading .btn-arrow { display: none; }
        .btn-go.loading .btn-spin {
            position: absolute; width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff; border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Demo */
        .demo-pill {
            margin-top: 24px; padding: 13px 18px;
            border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            font-size: 0.76rem; color: var(--text-tertiary);
            display: flex; align-items: center; gap: 8px;
            position: relative; z-index: 2;
        }
        .demo-pill i { font-size: 0.82rem; opacity: 0.5; }
        .demo-pill code {
            background: rgba(255,255,255,0.06);
            padding: 2px 8px; border-radius: 5px;
            font-weight: 600; font-size: 0.74rem;
            color: var(--text-secondary);
        }

        /* ═══ Responsive ═══ */
        @media (max-width: 860px) {
            .login-shell { grid-template-columns: 1fr; min-height: 0; border-radius: var(--radius-lg); }
            .side-brand { display: none; }
            .side-form { padding: 44px 36px; }
        }
        @media (max-width: 480px) {
            .side-form { padding: 36px 24px; }
            .form-greeting { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <!-- Atmosphere -->
    <div class="atmosphere">
        <canvas id="particleCanvas"></canvas>
        <div class="atmo-gradient"></div>
        <div class="atmo-grid"></div>
        <div class="atmo-orb atmo-orb--a"></div>
        <div class="atmo-orb atmo-orb--b"></div>
        <div class="atmo-orb atmo-orb--c"></div>
        <div class="atmo-noise"></div>
    </div>

    <!-- Login Shell -->
    <div class="login-shell">
        <!-- Brand Side -->
        <div class="side-brand">
            <div class="deco deco-ring-1"></div>
            <div class="deco deco-ring-2"></div>
            <div class="deco deco-line-v"></div>
            <div class="deco deco-dots">
                <i></i><i></i><i></i><i></i><i></i>
                <i></i><i></i><i></i><i></i><i></i>
                <i></i><i></i><i></i><i></i><i></i>
            </div>
            <div class="deco deco-cross"></div>

            <div class="brand-content">
                <div class="brand-top">
                    <img class="brand-logo" src="{{ asset('images/edubba_app.png') }}" alt="{{ $schoolName }}">
                    <div class="brand-heading">@lang('school_management_system')</div>
                    <div class="brand-desc">@lang('login_hero_desc')</div>

                    <div class="brand-features">
                        <div class="bf">
                            <div class="bf-icon"><i class="bi bi-people-fill"></i></div>
                            <div class="bf-text">@lang('login_feat_students')</div>
                        </div>
                        <div class="bf">
                            <div class="bf-icon"><i class="bi bi-cash-stack"></i></div>
                            <div class="bf-text">@lang('fees_invoices')</div>
                        </div>
                        <div class="bf">
                            <div class="bf-icon"><i class="bi bi-calendar2-week-fill"></i></div>
                            <div class="bf-text">@lang('smart_timetable')</div>
                        </div>
                        <div class="bf">
                            <div class="bf-icon"><i class="bi bi-graph-up-arrow"></i></div>
                            <div class="bf-text">@lang('ministry_reports')</div>
                        </div>
                    </div>
                </div>
                <div class="brand-foot">© {{ date('Y') }} {{ $schoolName }}</div>
            </div>
        </div>

        <!-- Form Side -->
        <div class="side-form">
            <div class="form-head">
                <div class="form-head-row">
                    <div>
                        <div class="form-greeting">@lang('welcome_back')</div>
                        <div class="form-sub">@lang('login_subtitle')</div>
                    </div>
                    <div>
                        <button class="lang-trigger" type="button" onclick="toggleLang(event)" title="@lang('language')">
                            <i class="bi bi-globe2"></i>
                        </button>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="err-alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    @foreach ($errors->all() as $error)<span>{{ $error }}</span> @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" id="loginForm">
                @csrf
                <div class="fld">
                    <label class="fld-label">@lang('email')</label>
                    <div class="fld-wrap">
                        <i class="fld-ico bi bi-envelope"></i>
                        <input type="email" name="email" class="fld-input" value="{{ old('email') }}" placeholder="admin@school.com" required autofocus>
                    </div>
                </div>
                <div class="fld">
                    <label class="fld-label">@lang('password')</label>
                    <div class="fld-wrap">
                        <i class="fld-ico bi bi-lock"></i>
                        <input type="password" name="password" class="fld-input" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="fld-opts">
                    <label class="chk">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="chk-box"></span>
                        <span class="chk-text">@lang('remember_me')</span>
                    </label>
                    <a href="#" class="forgot">@lang('forgot_password')</a>
                </div>

                <button type="submit" class="btn-go" id="submitBtn">
                    <span class="btn-label">@lang('login')</span>
                    <i class="bi bi-arrow-right btn-arrow"></i>
                    <span class="btn-spin" style="display:none"></span>
                </button>
            </form>

            <div class="demo-pill">
                <i class="bi bi-info-circle"></i>
                @lang('demo_account'): <code>admin@edubba.test</code> / <code>password</code>
            </div>
        </div>
    </div>

    <ul class="lang-popup" id="langPopup">
        <li>
            <a href="{{ route('language.switch', 'ar') }}">
                <span class="lang-active">{{ app()->getLocale() === 'ar' ? '✓' : '' }}</span>
                @lang('arabic')
            </a>
        </li>
        <li>
            <a href="{{ route('language.switch', 'en') }}">
                <span class="lang-active">{{ app()->getLocale() === 'en' ? '✓' : '' }}</span>
                @lang('english')
            </a>
        </li>
    </ul>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var langOpen = false;
        function toggleLang(e) {
            e.stopPropagation();
            var m = document.getElementById('langPopup');
            if (langOpen) {
                m.style.display = 'none';
                langOpen = false;
                document.removeEventListener('click', closeLang);
            } else {
                var r = e.currentTarget.getBoundingClientRect();
                m.style.display = 'flex';
                m.style.top = (r.bottom + 8) + 'px';
                m.style.right = '';
                m.style.left = r.left + 'px';
                langOpen = true;
                setTimeout(function() {
                    document.addEventListener('click', closeLang);
                }, 0);
            }
        }
        function closeLang(e) {
            var m = document.getElementById('langPopup');
            if (m) m.style.display = 'none';
            langOpen = false;
            document.removeEventListener('click', closeLang);
        }
        document.getElementById('loginForm').addEventListener('submit', function() {
            var b = document.getElementById('submitBtn');
            b.classList.add('loading');
            b.querySelector('.btn-label').style.opacity = '0';
            b.querySelector('.btn-arrow').style.display = 'none';
            b.querySelector('.btn-spin').style.display = 'block';
        });
    </script>
    <script>
    (function() {
        var canvas = document.getElementById('particleCanvas');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        var primaryRgb = [{{ $primaryRgb }}];
        var particles = [];
        var particleCount = 80;
        var connectionDist = 150;
        var mouse = { x: null, y: null };

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        document.addEventListener('mousemove', function(e) {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        function Particle() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.vx = (Math.random() - 0.5) * 0.5;
            this.vy = (Math.random() - 0.5) * 0.5;
            this.radius = Math.random() * 2 + 1;
        }
        Particle.prototype.update = function() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
            if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
            if (mouse.x !== null) {
                var dx = mouse.x - this.x;
                var dy = mouse.y - this.y;
                var dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 200) {
                    this.x -= dx * 0.002;
                    this.y -= dy * 0.002;
                }
            }
        };
        Particle.prototype.draw = function() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(' + primaryRgb[0] + ',' + primaryRgb[1] + ',' + primaryRgb[2] + ', 0.6)';
            ctx.fill();
        };

        for (var i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }

        function connectParticles() {
            for (var a = 0; a < particles.length; a++) {
                for (var b = a + 1; b < particles.length; b++) {
                    var dx = particles[a].x - particles[b].x;
                    var dy = particles[a].y - particles[b].y;
                    var dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < connectionDist) {
                        var opacity = 1 - (dist / connectionDist);
                        ctx.beginPath();
                        ctx.strokeStyle = 'rgba(' + primaryRgb[0] + ',' + primaryRgb[1] + ',' + primaryRgb[2] + ', ' + (opacity * 0.25) + ')';
                        ctx.lineWidth = 0.6;
                        ctx.moveTo(particles[a].x, particles[a].y);
                        ctx.lineTo(particles[b].x, particles[b].y);
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (var i = 0; i < particles.length; i++) {
                particles[i].update();
                particles[i].draw();
            }
            connectParticles();
            requestAnimationFrame(animate);
        }
        animate();
    })();
    </script>
</body>
</html>
