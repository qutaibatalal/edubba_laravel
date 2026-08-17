@php
    $primaryColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
    $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => App\Models\MobileAppConfig::configValue('school_name', 'Edubba School'));
    $logoDataUri = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/edubba_app_icon.png')));
    $fullName = $student->full_name ?? trim(implode(' ', array_filter([$student->name, $student->middle_name, $student->last_name])));
    $regNo = $registerNo ?? $student->student_code;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: 'Tajawal', sans-serif; box-sizing: border-box; }
        body { margin: 0; padding: 40px 46px; color: #111827; font-size: 12px; }
        .doc-head { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid {{ $primaryColor }}; padding-bottom: 14px; margin-bottom: 30px; }
        .school { display: flex; align-items: center; gap: 12px; }
        .logo { width: 44px; height: 44px; border-radius: 12px; object-fit: cover; }
        .school h1 { margin: 0; font-size: 16px; font-weight: 800; }
        .school p { margin: 2px 0 0; color: #6b7280; font-size: 10px; }
        .doc-title { text-align: center; }
        .doc-title h2 { margin: 0; font-size: 15px; color: {{ $primaryColor }}; font-weight: 800; }
        .doc-title span { color: #6b7280; font-size: 10px; }
        .cert-body { text-align: center; padding: 10px 20px; }
        .cert-body h3 { font-size: 22px; color: {{ $primaryColor }}; margin: 0 0 6px; font-weight: 900; }
        .cert-body .sub { color: #6b7280; font-size: 11px; margin-bottom: 28px; }
        .cert-body p { font-size: 13px; line-height: 2.1; margin: 0 0 12px; }
        .cert-body .name-line { font-size: 16px; font-weight: 800; }
        .details { display: flex; gap: 24px; justify-content: center; margin: 26px 0 10px; flex-wrap: wrap; }
        .details .box { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 18px; min-width: 150px; background: #fafafa; }
        .details .box label { display: block; font-size: 9px; color: #6b7280; margin-bottom: 4px; }
        .details .box strong { font-size: 12px; }
        .stamp { margin-top: 56px; }
        .stamp-row { display: flex; justify-content: space-between; gap: 40px; }
        .stamp-box { flex: 1; text-align: center; }
        .stamp-box .line { border-top: 1px solid #9ca3af; margin-top: 46px; padding-top: 6px; color: #6b7280; font-size: 10px; }
        .footer { position: fixed; bottom: -30px; right: 0; left: 0; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #f3f4f6; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="doc-head">
        <div class="school">
            <img class="logo" src="{{ $logoDataUri }}" alt="{{ $schoolName }}">
            <div>
                <h1>{{ $schoolName }}</h1>
                <p>@lang('pdf.enrollment.school_tagline')</p>
            </div>
        </div>
        <div class="doc-title">
            <h2>@lang('pdf.enrollment.title')</h2>
            <span>@lang('pdf.enrollment.title')</span>
        </div>
    </div>

    <div class="cert-body">
        <h3>@lang('pdf.enrollment.title')</h3>
        <div class="sub">@lang('pdf.enrollment.granted_body')</div>

        <p>
            @lang('pdf.enrollment.certifies', ['school' => $schoolName])
            <span class="name-line">{{ $fullName }}</span><br>
            @lang('pdf.enrollment.born', ['date' => $student->birth_date?->format('Y/m/d') ?? '—', 'code' => $student->student_code])
            @lang('pdf.enrollment.enrolled_in')
            <span class="name-line">{{ $student->batch?->name ?? '—' }}</span>
            @if ($student->program?->name)
                ({{ $student->program->name }})
            @endif
            @lang('pdf.enrollment.academic_year')
            <span class="name-line">{{ $student->academicYear?->name ?? now()->year }}</span>.
        </p>

        <div class="details">
            <div class="box"><label>@lang('pdf.enrollment.cert_no')</label><strong>{{ $regNo }}</strong></div>
            <div class="box"><label>@lang('pdf.enrollment.student_no')</label><strong>{{ $student->student_code }}</strong></div>
            <div class="box"><label>@lang('pdf.enrollment.issue_date')</label><strong>{{ now()->format('Y/m/d') }}</strong></div>
        </div>

        <div class="stamp">
            <div class="stamp-row">
                <div class="stamp-box"><div class="line">@lang('pdf.enrollment.principal_sign')</div></div>
                <div class="stamp-box"><div class="line">@lang('pdf.enrollment.school_stamp')</div></div>
            </div>
        </div>
    </div>

    <div class="footer">{{ $schoolName }} © {{ date('Y') }} — @lang('pdf.footer_system')</div>
</body>
</html>
