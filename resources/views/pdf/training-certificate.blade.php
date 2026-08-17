@php
    $primaryColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
    $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => App\Models\MobileAppConfig::configValue('school_name', 'Edubba School'));
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: 'Tajawal', sans-serif; box-sizing: border-box; }
        body { margin: 0; padding: 0; }
        .cert { width: 100%; min-height: 700px; padding: 60px 70px; border: 4px double #2c3e50; border-radius: 18px; text-align: center; position: relative; }
        .logo { width: 96px; height: 96px; margin: 0 auto 18px; border: 4px double #2c3e50; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 42px; color: {{ $primaryColor }}; }
        .school { font-size: 13px; color: #6b7280; margin-bottom: 4px; }
        h1 { font-size: 26px; font-weight: 900; color: #111827; margin: 6px 0 4px; }
        .sub { font-size: 13px; color: #6b7280; margin-bottom: 34px; }
        .dash { width: 160px; height: 3px; background: {{ $primaryColor }}; margin: 0 auto 34px; border-radius: 999px; }
        .row { display: flex; justify-content: center; gap: 60px; margin-bottom: 18px; }
        .field { text-align: center; }
        .field label { display: block; font-size: 11px; color: #6b7280; margin-bottom: 4px; }
        .field strong { font-size: 16px; font-weight: 800; color: #111827; border-bottom: 1px dashed #cbd5e1; padding: 0 10px 4px; }
        .body { margin: 34px 0 44px; font-size: 13px; color: #374151; line-height: 1.9; }
        .sign { margin-top: 60px; display: flex; justify-content: space-between; font-size: 12px; color: #111827; }
        .sign .line { border-top: 1px solid #9ca3af; width: 210px; padding-top: 6px; text-align: center; font-weight: 700; }
        .footer { margin-top: 34px; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="cert">
        <div class="logo"><span>&#127942;</span></div>
        <div class="school">{{ $schoolName }}</div>
        <h1>@lang('pdf.training.title')</h1>
        <div class="sub">@lang('pdf.training.title')</div>
        <div class="dash"></div>

        <div class="row">
            <div class="field">
                <label>@lang('pdf.training.student_name')</label>
                <strong>{{ $enrollment->student?->full_name }}</strong>
            </div>
            <div class="field">
                <label>@lang('pdf.training.course_name')</label>
                <strong>{{ $enrollment->trainingCourse?->name }}</strong>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label>@lang('pdf.training.cert_no')</label>
                <strong>{{ $certificate?->certificate_no }}</strong>
            </div>
            <div class="field">
                <label>@lang('pdf.training.issue_date')</label>
                <strong>{{ $certificate?->issued_date?->format('d/m/Y') }}</strong>
            </div>
        </div>

        <div class="body">
            @lang('pdf.training.body')
        </div>

        <div class="sign">
            <div class="line">@lang('pdf.training.principal_sign')</div>
            <div class="line">@lang('pdf.training.trainer_sign')</div>
        </div>

        <div class="footer">{{ $schoolName }} © {{ date('Y') }} — @lang('pdf.footer_system')</div>
    </div>
</body>
</html>
