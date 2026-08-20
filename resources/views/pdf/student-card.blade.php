@php
    $primaryColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
    $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => App\Models\MobileAppConfig::configValue('school_name', 'Edubba School'));
    $logoUrl = App\Models\MobileAppConfig::configValue('logo_url', '');
    if ($logoUrl) {
        $logoContent = @file_get_contents($logoUrl);
        $logoDataUri = $logoContent ? 'data:image/png;base64,' . base64_encode($logoContent) : 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/edubba_app_icon.png')));
    } else {
        $logoDataUri = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/edubba_app_icon.png')));
    }
    $fullName = $student->full_name ?? trim(implode(' ', array_filter([$student->name, $student->middle_name, $student->last_name])));
    $genderText = $student->gender === 'male' ? __('pdf.student_card.gender_male') : ($student->gender === 'female' ? __('pdf.student_card.gender_female') : '—');
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: 'xbriyaz', sans-serif; box-sizing: border-box; }
        body { margin: 0; padding: 30px 34px; color: #111827; font-size: 12px; }
        .doc-head { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid {{ $primaryColor }}; padding-bottom: 14px; margin-bottom: 22px; }
        .school { display: flex; align-items: center; gap: 12px; }
        .logo { width: 44px; height: 44px; border-radius: 12px; object-fit: cover; }
        .school h1 { margin: 0; font-size: 16px; font-weight: 800; }
        .school p { margin: 2px 0 0; color: #6b7280; font-size: 10px; }
        .doc-title { text-align: center; }
        .doc-title h2 { margin: 0; font-size: 15px; color: {{ $primaryColor }}; font-weight: 800; }
        .doc-title span { color: #6b7280; font-size: 10px; }
        .id-card { border: 2px solid {{ $primaryColor }}; border-radius: 14px; padding: 20px 24px; max-width: 430px; margin: 0 auto; text-align: center; }
        .id-card .avatar { width: 92px; height: 92px; border-radius: 50%; background: {{ $primaryColor }}; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 34px; font-weight: 900; margin: 0 auto 12px; }
        .id-card h3 { margin: 0 0 2px; font-size: 17px; font-weight: 800; }
        .id-card .role { color: #6b7280; font-size: 10px; margin-bottom: 14px; }
        .id-card table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .id-card td { padding: 6px 8px; font-size: 11px; border-top: 1px solid #f1f2f4; }
        .id-card td:first-child { color: #6b7280; font-size: 10px; text-align: right; width: 40%; }
        .id-card td:last-child { font-weight: 700; text-align: left; }
        .badge { display: inline-block; margin-top: 10px; padding: 4px 14px; border-radius: 999px; background: rgba(34,197,94,.15); color: #15803d; font-size: 10px; font-weight: 800; }
        .footer { position: fixed; bottom: -30px; right: 0; left: 0; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #f3f4f6; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="doc-head">
        <div class="school">
            <img class="logo" src="{{ $logoDataUri }}" alt="{{ $schoolName }}">
            <div>
                <h1>{{ $schoolName }}</h1>
                <p>@lang('pdf.student_card.school_tagline')</p>
            </div>
        </div>
        <div class="doc-title">
            <h2>@lang('pdf.student_card.title')</h2>
            <span>@lang('pdf.student_card.title')</span>
        </div>
    </div>

    <div class="id-card">
        <div class="avatar">{{ mb_substr($fullName, 0, 1) }}</div>
        <h3>{{ $fullName }}</h3>
        <div class="role">{{ $student->batch?->name ?? '—' }} · {{ $student->program?->name ?? '' }}</div>

        <table>
            <tr><td>@lang('pdf.student_card.col_univ_no')</td><td>{{ $student->student_code }}</td></tr>
            <tr><td>@lang('pdf.student_card.col_roll_no')</td><td>{{ $student->roll_no ?? '—' }}</td></tr>
            <tr><td>@lang('pdf.student_card.col_gender')</td><td>{{ $genderText }}</td></tr>
            <tr><td>@lang('pdf.student_card.col_birth_date')</td><td>{{ $student->birth_date?->format('Y/m/d') ?? '—' }}</td></tr>
            <tr><td>@lang('pdf.student_card.col_academic_year')</td><td>{{ $student->academicYear?->name ?? '—' }}</td></tr>
            <tr><td>@lang('pdf.student_card.col_phone')</td><td>{{ $student->mobile ?? $student->phone ?? '—' }}</td></tr>
        </table>

        @if (!empty($validUntil))
            <div class="badge">@lang('pdf.student_card.valid_until', ['date' => $validUntil->format('Y/m/d')])</div>
        @else
            <div class="badge">@lang('pdf.student_card.registered') — {{ $student->academicYear?->name ?? __('pdf.student_card.current_year') }}</div>
        @endif
    </div>

    <div class="footer">{{ $schoolName }} © {{ date('Y') }} — @lang('pdf.footer_system')</div>
</body>
</html>
