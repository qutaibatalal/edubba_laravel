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
            position: absolute; border-radius: 50%; filter: blur(100px); will-change: transform;
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

        /* ═══ Card ═══ */
        .auth-card {
            position: relative; z-index: 1; width: min(420px, 92vw);
            background: rgba(14,15,22,.8);
            backdrop-filter: blur(24px) saturate(1.2); -webkit-backdrop-filter: blur(24px) saturate(1.2);
            border: 1px solid rgba(255,255,255,0.08); border-radius: var(--radius-lg);
            box-shadow: 0 48px 96px -24px rgba(0,0,0,.5);
            padding: 44px;
            animation: rise .6s cubic-bezier(.22,.68,.31,1);
        }
        @keyframes rise { from { opacity: 0; transform: translateY(28px) scale(.97); } to { opacity: 1; transform: none; } }

        .shield-icon {
            width: 64px; height: 64px; border-radius: 18px;
            background: rgba(var(--primary-rgb), .12);
            display: inline-grid; place-items: center; font-size: 1.7rem;
            margin-bottom: 16px; color: var(--primary);
            box-shadow: 0 0 30px rgba(var(--primary-rgb), .15);
        }

        .auth-card h1 { font-size: 1.3rem; font-weight: 800; letter-spacing: -.02em; margin-bottom: 6px; color: var(--text-primary); }
        .auth-card p.sub { color: var(--text-secondary); font-size: .86rem; line-height: 1.5; margin-bottom: 0; }

        .otp-input {
            text-align: center; letter-spacing: .5em; font-size: 1.8rem; font-weight: 800;
            border-radius: var(--radius-sm) !important; border: 1px solid var(--border); padding: 16px;
            background: var(--surface); color: var(--text-primary);
            transition: all 200ms var(--ease);
        }
        .otp-input::placeholder { color: var(--text-tertiary); }
        .otp-input:focus {
            outline: none; border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), .12); background: rgba(255,255,255,0.04);
        }

        .btn-otp {
            width: 100%; padding: 14px; border: 0; border-radius: var(--radius-sm); font-weight: 800; color: #fff;
            background: linear-gradient(135deg, var(--primary), rgba(124,58,237,.85));
            box-shadow: 0 12px 28px -8px rgba(var(--primary-rgb), .4);
            transition: all 250ms var(--ease); font-size: .95rem; cursor: pointer;
        }
        .btn-otp:hover { filter: brightness(1.08); transform: translateY(-2px); box-shadow: 0 16px 36px -8px rgba(var(--primary-rgb), .5); }
        .btn-otp:active { transform: translateY(0); }

        .auth-link { color: var(--text-secondary); font-weight: 600; text-decoration: none; font-size: .84rem; transition: color 200ms var(--ease); }
        .auth-link:hover { color: var(--text-primary); }

        /* ═══ Alerts ═══ */
        .alert { border-radius: var(--radius-sm); font-weight: 600; font-size: .84rem; border: none; }
        .alert-success { background: rgba(16,185,129,.12); color: #34d399; }
        .alert-warning { background: rgba(245,158,11,.12); color: #fbbf24; }
        .alert-danger { background: rgba(239,68,68,.12); color: #f87171; }

        /* ═══ Reduced motion ═══ */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body>
    <div class="atmosphere">
        <div class="atmo-gradient"></div>
        <div class="atmo-grid"></div>
        <div class="atmo-orb atmo-orb--a"></div>
        <div class="atmo-orb atmo-orb--b"></div>
        <div class="atmo-orb atmo-orb--c"></div>
        <div class="atmo-noise"></div>
    </div>

    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="shield-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h1>@lang('two_factor')</h1>
            <p class="sub">@lang('two_factor_desc')</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success py-2 small"><i class="bi bi-check-circle-fill me-1"></i>{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-warning py-2 small"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                @foreach ($errors->all() as $error)<span>{{ $error }}</span> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.2fa.verify') }}">
            @csrf
            <input type="text" name="code" inputmode="numeric" maxlength="6" autocomplete="one-time-code"
                class="form-control otp-input" placeholder="••••••" autofocus required>
            <button type="submit" class="btn-otp mt-4"><i class="bi bi-shield-check me-2"></i>@lang('verify')</button>
        </form>

        <div class="text-center mt-3">
            <form method="POST" action="{{ route('admin.2fa.resend') }}" class="d-inline">
                @csrf
                <button type="submit" class="auth-link" style="background:none;border:none;cursor:pointer;font-size:.84rem">
                    <i class="bi bi-arrow-clockwise me-1"></i>@lang('resend_code')
                </button>
            </form>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('admin.login') }}" class="auth-link"><i class="bi bi-arrow-right me-1"></i>@lang('back_to_login')</a>
        </div>
    </div>
</body>
</html>
