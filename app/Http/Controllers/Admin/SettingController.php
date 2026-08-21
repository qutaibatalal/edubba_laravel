<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\MobileAppConfig;
use App\Services\TwoFactorService;
use App\Support\UploadPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $configs = MobileAppConfig::all()->keyBy('config_key');
        $twoFactorEnabled = TwoFactorService::isEnabled(request()->user());

        return view('admin.settings.index', compact('configs', 'twoFactorEnabled'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'school_name_en' => 'nullable|string|max:100',
            'school_name_ar' => 'nullable|string|max:100',
            'primary_color' => 'nullable|string|max:20',
            'features.tutoring' => 'nullable|boolean',
            'features.training' => 'nullable|boolean',
            'features.library' => 'nullable|boolean',
            'logo' => 'nullable|file|image|mimes:jpeg,png|max:5120',
        ]);

        $name = MobileAppConfig::where('config_key', 'school_name')->first();
        MobileAppConfig::updateOrCreate(
            ['config_key' => 'school_name'],
            [
                'label' => 'School Name',
                'value' => [
                    'en' => $request->input('school_name_en', $name?->value['en'] ?? 'Edubba School'),
                    'ar' => $request->input('school_name_ar', $name?->value['ar'] ?? 'مدرسة إدبة'),
                ],
                'active' => true,
            ]
        );

        MobileAppConfig::updateOrCreate(
            ['config_key' => 'primary_color'],
            ['label' => 'Primary Color', 'value' => $request->input('primary_color', '#1e40af'), 'active' => true]
        );

        MobileAppConfig::updateOrCreate(
            ['config_key' => 'features'],
            [
                'label' => 'Feature Toggles',
                'value' => [
                    'tutoring' => $request->boolean('features.tutoring'),
                    'training' => $request->boolean('features.training'),
                    'library' => $request->boolean('features.library'),
                ],
                'active' => true,
            ]
        );

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            UploadPolicy::validate($file, 'image');
            $name = Str::random(24).'.'.$file->getClientOriginalExtension();
            $file->storeAs('logos', $name, 'public');
            $url = asset('storage/logos/'.$name);
            MobileAppConfig::updateOrCreate(
                ['config_key' => 'logo_url'],
                ['label' => 'School Logo', 'value' => $url, 'active' => true]
            );
            cache()->forget('edubba_admin_logo');
        }

        return back()->with('success', 'تم حفظ الإعدادات.');
    }

    public function resetPassword(Request $request, $userId): RedirectResponse
    {
        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        // Try as ApiUser ID first, then as Student ID
        $user = ApiUser::find($userId);
        if (! $user) {
            $user = ApiUser::where('student_id', $userId)->first();
        }
        if (! $user) {
            return back()->withErrors(['error' => 'المستخدم غير موجود']);
        }

        $user->password = $request->new_password;
        $user->save();

        return back()->with('success', "تم إعادة تعيين كلمة المرور للمستخدم {$user->username} بنجاح.");
    }

    public function searchUsers(Request $request)
    {
        $request->validate(['q' => 'required|string|min:1']);

        $users = ApiUser::where('username', 'like', '%'.$request->q.'%')
            ->select('id', 'username', 'role', 'active')
            ->limit(20)
            ->get();

        return response()->json($users);
    }
}
