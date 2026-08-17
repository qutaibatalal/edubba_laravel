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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', 'Tajawal', system-ui, sans-serif; }
        [dir="rtl"] * { font-family: 'Tajawal', 'Plus Jakarta Sans', system-ui, sans-serif; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8f9fc; -webkit-font-smoothing: antialiased; }

        .bg-decor {
            position: fixed; inset: 0; z-index: -2;
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, #6d28d9 50%, #be185d 100%);
            background-size: 200% 200%;
            animation: loginGrad 18s ease infinite;
        }
        @keyframes loginGrad { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        .bg-decor::before, .bg-decor::after {
            content: ''; position: absolute; border-radius: 50%; filter: blur(80px); opacity: .45;
        }
        @keyframes orbDrift { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(20px, -24px); } }
        .bg-decor::before { width: 500px; height: 500px; background: #fff3; top: -140px; inset-inline-start: -100px; animation: orbDrift 12s ease-in-out infinite; }
        .bg-decor::after { width: 400px; height: 400px; background: #fff2; bottom: -120px; inset-inline-end: -80px; animation: orbDrift 16s ease-in-out infinite reverse; }

        .grid-overlay {
            position: fixed; inset: 0; z-index: -1; opacity: .08;
            background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px);
            background-size: 48px 48px; mask-image: radial-gradient(circle at 50% 40%, #000, transparent 70%);
        }

        .login-shell {
            width: min(1000px, 94vw); display: grid; grid-template-columns: 1.15fr 1fr; min-height: 560px;
            border-radius: 28px; overflow: hidden; box-shadow: 0 48px 96px -24px rgba(0,0,0,.4);
            animation: rise .6s cubic-bezier(.22,.68,.31,1);
            border: 1px solid rgba(255,255,255,.1);
        }
        @keyframes rise { from { opacity: 0; transform: translateY(28px) scale(.97); } to { opacity: 1; transform: none; } }

        .login-brand {
            position: relative; background: linear-gradient(160deg, {{ $primaryColor }}, #6d28d9);
            color: #fff; padding: 52px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden;
        }
        .login-brand::before { content: ''; position: absolute; width: 340px; height: 340px; border-radius: 50%; border: 44px solid #ffffff12; bottom: -100px; inset-inline-end: -80px; }
        .login-brand::after { content: ''; position: absolute; width: 160px; height: 160px; border-radius: 50%; border: 28px solid #ffffff12; top: 80px; inset-inline-start: -60px; }

        .login-brand .login-logo { width: min(260px, 90%); height: auto; display: block; filter: drop-shadow(0 8px 24px rgba(0,0,0,.3)); transition: transform .3s ease; }
        .login-brand:hover .login-logo { transform: scale(1.02); }
        .login-brand h2 { font-weight: 900; font-size: 1.9rem; margin-top: 28px; letter-spacing: -.02em; }
        .login-brand p { opacity: .85; font-size: .95rem; line-height: 1.6; }
        .login-brand .feat { display: flex; align-items: center; gap: 12px; font-size: .86rem; margin-top: 16px; opacity: .9; }
        .login-brand .feat i { width: 34px; height: 34px; border-radius: 11px; background: #ffffff18; display: grid; place-items: center; font-size: .88rem; flex-shrink: 0; backdrop-filter: blur(8px); }
        .login-brand .feat + .feat { margin-top: 8px; }

        .login-form-side {
            background: #fff; padding: 52px; display: flex; flex-direction: column; justify-content: center; position: relative;
        }
        .login-form-side h1 { font-size: 1.6rem; font-weight: 800; letter-spacing: -.02em; }
        .login-form-side .sub { color: #64748b; font-size: .92rem; line-height: 1.5; }

        .input-group-form { position: relative; }
        .input-group-form .ic { position: absolute; top: 50%; inset-inline-start: 16px; transform: translateY(-50%); color: #94a3b8; z-index: 5; font-size: .9rem; }
        .input-group-form input {
            padding: 13px 48px 13px 16px; border-radius: 14px !important; border: 1.5px solid #e2e8f0;
            font-size: .92rem; transition: all .2s ease; background: #f8f9fc; width: 100%; color: #0f172a;
        }
        .input-group-form input::placeholder { color: #94a3b8; }
        .input-group-form input:focus { outline: none; border-color: {{ $primaryColor }}; box-shadow: 0 0 0 4px rgba({{ $primaryRgb }}, .1); background: #fff; }

        .btn-login {
            width: 100%; padding: 14px; border: 0; border-radius: 14px; font-weight: 800; color: #fff;
            background: linear-gradient(135deg, {{ $primaryColor }}, #6d28d9);
            box-shadow: 0 12px 28px -8px {{ $primaryColor }}; transition: all .25s ease; font-size: .95rem; letter-spacing: -.01em;
        }
        .btn-login:hover { filter: brightness(1.08); transform: translateY(-2px); box-shadow: 0 16px 36px -8px {{ $primaryColor }}; }
        .btn-login:active { transform: translateY(0); }

        .demo-box { background: #f8f9fc; border: 1.5px dashed #cbd5e1; border-radius: 14px; padding: 14px 16px; font-size: .82rem; color: #475569; }
        .demo-box code { background: #e2e8f0; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: .8rem; }

        @media (max-width: 760px) {
            .login-brand { display: none; }
            .login-shell { grid-template-columns: 1fr; min-height: 0; border-radius: 20px; }
            .login-form-side { padding: 36px 28px; }
        }
    </style>
</head>
<body>
    <div class="bg-decor"></div>
    <div class="grid-overlay"></div>

    <div class="login-shell">
        <div class="login-brand">
            <img class="login-logo" src="{{ asset('images/edubba_app.png') }}" alt="{{ $schoolName }}">
            <div>
                <h2>@lang('school_management_system')</h2>
                <p>@lang('login_hero_desc')</p>
                <div class="feat"><i class="bi bi-people-fill"></i> @lang('login_feat_students')</div>
                <div class="feat"><i class="bi bi-cash-stack"></i> @lang('fees_invoices')</div>
                <div class="feat"><i class="bi bi-calendar2-week-fill"></i> @lang('smart_timetable')</div>
                <div class="feat"><i class="bi bi-graph-up-arrow"></i> @lang('ministry_reports')</div>
            </div>
            <div style="font-size:.78rem;opacity:.65;font-weight:500">© {{ date('Y') }} {{ $schoolName }}</div>
        </div>

        <div class="login-form-side">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1>@lang('welcome_back')</h1>
                    <p class="sub mb-4">@lang('login_subtitle')</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm border" type="button" data-bs-toggle="dropdown" title="@lang('language')" style="border-radius:10px;color:#475569;background:#f8f9fc;border-color:#e2e8f0 !important">
                        <i class="bi bi-globe2"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('language.switch', 'ar') }}"><span class="me-2">{{ app()->getLocale() === 'ar' ? '✓' : '' }}</span>@lang('arabic')</a></li>
                        <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}"><span class="me-2">{{ app()->getLocale() === 'en' ? '✓' : '' }}</span>@lang('english')</a></li>
                    </ul>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small" style="border-radius:12px;background:rgba(239,68,68,.08);color:#dc2626;border:none;font-weight:600">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    @foreach ($errors->all() as $error)<span>{{ $error }}</span> @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small" style="font-size:.82rem;color:#475569">@lang('email')</label>
                    <div class="input-group-form">
                        <i class="ic bi bi-envelope-fill"></i>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@school.com" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small" style="font-size:.82rem;color:#475569">@lang('password')</label>
                    <div class="input-group-form">
                        <i class="ic bi bi-shield-lock-fill"></i>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small" for="remember" style="font-weight:600;color:#475569">@lang('remember_me')</label>
                    </div>
                    <a href="#" class="small text-decoration-none fw-bold" style="color:{{ $primaryColor }};font-weight:700">@lang('forgot_password')</a>
                </div>
                <button type="submit" class="btn-login mb-3"><i class="bi bi-box-arrow-in-left me-2"></i>@lang('login')</button>
            </form>

            <div class="demo-box text-center mt-2">
                <i class="bi bi-info-circle me-1"></i>
                @lang('demo_account'): <code>admin@edubba.test</code> / <code>password</code>
            </div>
        </div>
    </div>
</body>
</html>
