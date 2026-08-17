<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobileAppConfig;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        return back()->with('success', 'تم حفظ الإعدادات.');
    }
}
