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
    <title>@lang('two_factor') — {{ $schoolName }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/edubba_app_icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,200;14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Noto+Kufi+Arabic:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: {{ $primaryColor }};
            --primary-rgb: {{ $primaryRgb }};
            --bg: #08080d;
            --surface: rgba(255,255,255,0.03);
            --border: rgba(255,255,255,0.08);
            --border-focus: rgba(255,255,255,0.2);
            --text-primary: #f0f0f6;
            --text-secondary: rgba(255,255,255,0.55);
            --text-tertiary: rgba(255,255,255,0.32);
            --radius: 16px;
            --radius-sm: 12px;
            --ease: cubic-bezier(0.4,0,0.2,1);
        }
        html { height: 100%; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100%; display: flex; align-items: center; justify-content: center;
            overflow: hidden; background: var(--bg); color: var(--text-primary);
            -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;
            position: relative;
        }
        [dir="rtl"] body, [dir="rtl"] * { font-family: 'Noto Kufi Arabic', 'Inter', system-ui, sans-serif; }

        /* ── Atmosphere ── */
        .atmo { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; }
        .atmo-grad {
            position: absolute; width: 160%; height: 160%; top: -30%; left: -30%;
            background:
                radial-gradient(ellipse 900px 700px at 18% 28%, rgba(var(--primary-rgb), 0.15), transparent 70%),
                radial-gradient(ellipse 700px 900px at 82% 72%, rgba(139, 92, 246, 0.1), transparent 70%),
                radial-gradient(ellipse 600px 600px at 55% 45%, rgba(236, 72, 153, 0.06), transparent 60%);
            animation: atmoDrift 30s ease-in-out infinite alternate;
        }
        @keyframes atmoDrift {
            0% { transform: translate(0,0) rotate(0deg); }
            50% { transform: translate(2%,-1.5%) rotate(0.5deg); }
            100% { transform: translate(-1%,1%) rotate(-0.3deg); }
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
        .atmo-orb { position: absolute; border-radius: 50%; filter: blur(100px); will-change: transform; }
        .atmo-orb--a { width: 500px; height: 500px; top: -16%; left: -6%; background: rgba(var(--primary-rgb), 0.18); animation: orbA 22s ease-in-out infinite; }
        .atmo-orb--b { width: 400px; height: 400px; bottom: -10%; right: -3%; background: rgba(139, 92, 246, 0.14); animation: orbB 26s ease-in-out infinite; }
        .atmo-orb--c { width: 280px; height: 280px; top: 45%; left: 55%; background: rgba(236, 72, 153, 0.06); animation: orbC 20s ease-in-out infinite; }
        @keyframes orbA { 0%,100% { transform: translate(0,0); } 50% { transform: translate(30px,25px); } }
        @keyframes orbB { 0%,100% { transform: translate(0,0); } 50% { transform: translate(-25px,-35px); } }
        @keyframes orbC { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(-15px,20px) scale(1.08); } }

        /* ── Card ── */
        .card-2fa {
            position: relative; z-index: 10;
            width: min(440px, 92vw);
            background: rgba(14, 15, 22, 0.65);
            backdrop-filter: blur(40px); -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 28px; padding: 48px;
            box-shadow: 0 40px 100px -20px rgba(0,0,0,0.6);
            animation: cardReveal 0.9s cubic-bezier(0.22, 0.68, 0.31, 1) both;
        }
        @keyframes cardReveal {
            from { opacity: 0; transform: translateY(50px) scale(0.95); filter: blur(12px); }
            to { opacity: 1; transform: none; filter: blur(0); }
        }

        .shield-icon {
            width: 68px; height: 68px; border-radius: 18px;
            background: rgba(var(--primary-rgb), 0.12);
            display: inline-grid; place-items: center; font-size: 1.6rem;
            margin-bottom: 20px; color: rgba(var(--primary-rgb), 0.9);
            box-shadow: 0 8px 32px -8px rgba(var(--primary-rgb), 0.2);
        }
        .card-title {
            font-size: 1.5rem; font-weight: 800; letter-spacing: -0.03em;
            margin-bottom: 8px; color: var(--text-primary);
        }
        .card-desc {
            font-size: 0.86rem; font-weight: 300;
            color: var(--text-secondary); line-height: 1.6; margin-bottom: 0;
        }

        /* ── Alerts ── */
        .alert-2fa {
            padding: 13px 18px; border-radius: var(--radius-sm);
            font-size: 0.82rem; font-weight: 600; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
            animation: alertSlide 0.3s ease;
        }
        @keyframes alertSlide { from { opacity: 0; transform: translateY(-6px); } }
        .alert-2fa--success { background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.12); color: #34d399; }
        .alert-2fa--warning { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.12); color: #fbbf24; }
        .alert-2fa--danger { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.12); color: #f87171; }

        /* ── OTP Input ── */
        .otp-input {
            width: 100%; padding: 18px; text-align: center;
            letter-spacing: 0.5em; font-size: 1.8rem; font-weight: 800;
            font-family: inherit;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.025);
            color: var(--text-primary);
            transition: all 0.2s var(--ease);
            outline: none;
        }
        .otp-input::placeholder { color: var(--text-tertiary); font-weight: 300; letter-spacing: 0.3em; }
        .otp-input:hover {
            border-color: rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.035);
        }
        .otp-input:focus {
            border-color: var(--primary);
            background: rgba(255,255,255,0.04);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.12);
        }

        /* ── Button ── */
        .btn-otp {
            width: 100%; padding: 15px; border: 0;
            border-radius: var(--radius-sm);
            font-family: inherit; font-size: 0.9rem; font-weight: 700;
            letter-spacing: 0.02em; color: #fff; cursor: pointer;
            background: linear-gradient(135deg, var(--primary), rgba(139, 92, 246, 0.85));
            position: relative; overflow: hidden;
            transition: all 0.25s var(--ease);
            display: flex; align-items: center; justify-content: center; gap: 10px;
            box-shadow: 0 8px 32px -6px rgba(var(--primary-rgb), 0.3);
        }
        .btn-otp::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent 60%);
            opacity: 0; transition: opacity 0.25s var(--ease);
        }
        .btn-otp:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 48px -6px rgba(var(--primary-rgb), 0.4);
        }
        .btn-otp:hover::before { opacity: 1; }
        .btn-otp:active { transform: translateY(0); }

        /* ── Links ── */
        .link-resend {
            font-size: 0.82rem; font-weight: 600;
            color: var(--primary); text-decoration: none;
            background: none; border: none; cursor: pointer;
            font-family: inherit;
            transition: opacity 0.2s var(--ease);
        }
        .link-resend:hover { opacity: 0.75; }
        .link-back {
            font-size: 0.82rem; font-weight: 500;
            color: var(--text-tertiary); text-decoration: none;
            transition: color 0.2s var(--ease);
        }
        .link-back:hover { color: var(--text-secondary); }
    </style>
</head>
<body>
    <div class="atmo">
        <div class="atmo-grad"></div>
        <div class="atmo-grid"></div>
        <div class="atmo-orb atmo-orb--a"></div>
        <div class="atmo-orb atmo-orb--b"></div>
        <div class="atmo-orb atmo-orb--c"></div>
    </div>

    <div class="card-2fa">
        <div class="text-center mb-4">
            <div class="shield-icon mx-auto">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div class="card-title">@lang('two_factor')</div>
            <div class="card-desc">@lang('two_factor_desc')</div>
        </div>

        @if (session('status'))
            <div class="alert-2fa alert-2fa--success">
                <i class="bi bi-check-circle-fill"></i>{{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-2fa alert-2fa--warning">
                <i class="bi bi-exclamation-triangle-fill"></i>{{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-2fa alert-2fa--danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                @foreach ($errors->all() as $error)<span>{{ $error }}</span> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.2fa.verify') }}">
            @csrf
            <input type="text" name="code" inputmode="numeric" maxlength="6" autocomplete="one-time-code"
                class="form-control otp-input" placeholder="••••••" autofocus required>
            <button type="submit" class="btn-otp mt-4"><i class="bi bi-shield-check"></i>@lang('verify')</button>
        </form>

        <div class="text-center mt-4">
            <form method="POST" action="{{ route('admin.2fa.resend') }}" class="d-inline">
                @csrf
                <button type="submit" class="link-resend">
                    <i class="bi bi-arrow-clockwise me-1"></i>@lang('resend_code')
                </button>
            </form>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('admin.login') }}" class="link-back">@lang('back_to_login')</a>
        </div>
    </div>
</body>
</html>
