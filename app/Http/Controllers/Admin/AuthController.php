<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobileAppConfig;
use App\Services\TwoFactorService;
use App\Support\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginForm(): View
    {
        $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => MobileAppConfig::configValue('school_name', 'مدرسة إدبة'));

        return view('admin.auth.login', compact('schoolName'));
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (! $user->hasRole('admin')) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['email' => 'هذا الحساب غير مصرح بالوصول للوحة التحكم.']);
            }

            $request->session()->regenerate();

            if (TwoFactorService::isEnabled($user)) {
                $code = TwoFactorService::issue($user);
                TwoFactorService::send($user, $code);
                $request->session()->put('2fa_pending', $user->getKey());
                $request->session()->put('2fa_resend_at', now()->addSeconds(30));

                AuditService::log(null, 'login', "محاولة دخول 2FA للأدمن {$user->email}");

                return redirect()->route('admin.2fa.form');
            }

            AuditService::log(null, 'login', "تسجيل دخول الأدمن {$user->email}");

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        AuditService::log(null, 'logout', 'تسجيل خروج الأدمن '.(Auth::user()?->email ?? '—'));

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
