<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use App\Support\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $userId = $request->session()->get('2fa_pending');

        if (! $userId) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.two-factor');
    }

    public function verify(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('2fa_pending');

        if (! $userId) {
            return redirect()->route('admin.login');
        }

        $request->validate(['code' => 'required|digits:6']);

        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget('2fa_pending');

            return redirect()->route('admin.login');
        }

        if (! TwoFactorService::verify($user, $request->string('code'))) {
            return back()->withErrors(['code' => 'رمز التحقق غير صحيح أو منتهي الصلاحية.']);
        }

        Auth::login($user, false);
        $request->session()->regenerate();
        $request->session()->forget('2fa_pending');

        AuditService::log(null, 'login', "اكتمال التحقق الثنائي للأدمن {$user->email}");

        return redirect()->intended(route('admin.dashboard'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('2fa_pending');

        if (! $userId) {
            return redirect()->route('admin.login');
        }

        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('admin.login');
        }

        if ($request->session()->has('2fa_resend_at') && now()->lt($request->session()->get('2fa_resend_at'))) {
            return back()->with('error', 'انتظر 30 ثانية قبل إعادة إرسال الرمز.');
        }

        $code = TwoFactorService::issue($user);
        TwoFactorService::send($user, $code);
        $request->session()->put('2fa_resend_at', now()->addSeconds(30));

        return back()->with('status', 'تم إرسال رمز جديد.');
    }

    public function enable(Request $request): RedirectResponse
    {
        $request->validate(['phone' => 'nullable|string|max:20']);

        $user = $request->user();

        if ($request->filled('phone')) {
            $user->phone = $request->string('phone');
        }

        if (! $user->phone) {
            return back()->withErrors(['phone' => 'يرجى إدخال رقم هاتف لاستقبال الرمز.']);
        }

        $user->two_factor_enabled = true;
        $user->save();

        AuditService::log($user, 'update', 'تفعيل التحقق الثنائي (2FA)');

        return back()->with('status', 'تم تفعيل التحقق الثنائي.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate(['password' => 'required|string']);

        $user = $request->user();

        if (! Hash::check($request->string('password'), $user->password)) {
            return back()->withErrors(['password' => 'كلمة المرور غير صحيحة.']);
        }

        $user->two_factor_enabled = false;
        $user->save();

        AuditService::log($user, 'update', 'تعطيل التحقق الثنائي (2FA)');

        return back()->with('status', 'تم تعطيل التحقق الثنائي.');
    }
}
