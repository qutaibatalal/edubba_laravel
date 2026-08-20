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
    $single = isset($result);
    $cards = $single ? collect([$result]) : collect($results);
    $lookup = $single ? collect([$result->student_id => $marksheets]) : $marksheetsByStudent;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: 'xbriyaz', sans-serif; box-sizing: border-box; }
        body { margin: 0; padding: 26px 32px; color: #111827; font-size: 11px; }
        .card { page-break-after: always; }
        .card:last-child { page-break-after: auto; }
        .doc-head { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid {{ $primaryColor }}; padding-bottom: 12px; margin-bottom: 14px; }
        .school { display: flex; align-items: center; gap: 10px; }
        .logo { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; }
        .school h1 { margin: 0; font-size: 15px; font-weight: 800; }
        .school p { margin: 2px 0 0; color: #6b7280; font-size: 9px; }
        .doc-title { text-align: center; }
        .doc-title h2 { margin: 0; font-size: 15px; color: {{ $primaryColor }}; font-weight: 800; }
        .doc-title span { color: #6b7280; font-size: 9px; }
        .meta-row { display: flex; gap: 14px; margin-bottom: 12px; flex-wrap: wrap; }
        .meta-col { min-width: 110px; }
        .meta-col label { display: block; font-size: 8px; color: #6b7280; margin-bottom: 2px; }
        .meta-col strong { font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 7px 9px; text-align: right; }
        th { background: #f3f4f6; font-size: 9px; color: #374151; }
        td { font-size: 10px; }
        .num { font-variant-numeric: tabular-nums; }
        .summary { width: 70%; margin-inline-start: auto; }
        .summary td { border: 0; padding: 4px 8px; font-size: 10px; }
        .summary .grand { font-weight: 800; font-size: 12px; color: {{ $primaryColor }}; border-top: 2px solid {{ $primaryColor }}; }
        .scale { font-size: 8px; color: #6b7280; border: 1px dashed #d1d5db; padding: 8px 10px; border-radius: 8px; }
        .status-pass { display: inline-block; padding: 2px 12px; border-radius: 999px; background: rgba(34,197,94,.15); color: #15803d; font-weight: 800; font-size: 11px; }
        .status-fail { display: inline-block; padding: 2px 12px; border-radius: 999px; background: rgba(239,68,68,.12); color: #b91c1c; font-weight: 800; font-size: 11px; }
        .sig { margin-top: 26px; }
        .sig-row { display: flex; justify-content: space-between; gap: 30px; }
        .sig-box { flex: 1; text-align: center; }
        .sig-box .line { border-top: 1px solid #9ca3af; margin-top: 38px; padding-top: 6px; color: #6b7280; font-size: 9px; }
        .footer { position: fixed; bottom: -28px; right: 0; left: 0; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #f3f4f6; padding-top: 8px; }
    </style>
</head>
<body>
    @foreach ($cards as $card)
        @php $studentMarksheets = $lookup[$card->student_id] ?? collect(); @endphp
        <div class="card">
            <div class="doc-head">
                <div class="school">
                    <img class="logo" src="{{ $logoDataUri }}" alt="{{ $schoolName }}">
                    <div>
                        <h1>{{ $schoolName }}</h1>
                        <p>@lang('pdf.result_card.school_tagline')</p>
                    </div>
                </div>
                <div class="doc-title">
                    <h2>@lang('pdf.result_card.title')</h2>
                    <span>@lang('pdf.result_card.title')</span>
                </div>
            </div>

            <div class="meta-row">
                <div class="meta-col"><label>@lang('pdf.result_card.student_name')</label><strong>{{ $card->student?->full_name }}</strong></div>
                <div class="meta-col"><label>@lang('pdf.result_card.roll_no')</label><strong class="num">{{ $card->student?->roll_no ?? $card->student?->student_code }}</strong></div>
                <div class="meta-col"><label>@lang('pdf.result_card.batch')</label><strong>{{ $card->student?->batch?->name ?? '—' }}</strong></div>
                <div class="meta-col"><label>@lang('pdf.result_card.exam')</label><strong>{{ $exam->name }}</strong></div>
            </div>

            <table>
                <thead>
                    <tr><th style="width:8%" class="num">#</th><th>@lang('pdf.result_card.col_subject')</th><th style="width:11%" class="num">@lang('pdf.result_card.col_max')</th><th style="width:12%" class="num">@lang('pdf.result_card.col_marks')</th><th style="width:11%" class="num">@lang('pdf.result_card.col_pass')</th><th style="width:12%" class="num">@lang('pdf.result_card.col_percentage')</th><th style="width:10%">@lang('pdf.result_card.col_grade')</th><th style="width:10%">@lang('pdf.result_card.col_status')</th></tr>
                </thead>
                <tbody>
                    @forelse ($studentMarksheets->flatMap(fn ($m) => $m->lines) as $i => $line)
                        <tr>
                            <td class="num">{{ $i + 1 }}</td>
                            <td>{{ $line->subject?->name ?? '—' }}</td>
                            <td class="num">{{ $line->max_marks }}</td>
                            <td class="num">{{ $line->marks }}</td>
                            <td class="num">{{ $line->pass_marks }}</td>
                            <td class="num">{{ $line->percentage }}%</td>
                            <td><strong>{{ $line->grade ?: '—' }}</strong></td>
                            <td>{{ $line->passed ? __('pdf.result_card.pass') : __('pdf.result_card.fail') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align:center;color:#9ca3af">@lang('pdf.result_card.empty')</td></tr>
                    @endforelse
                </tbody>
            </table>

            <table class="summary">
                <tr><td>@lang('pdf.result_card.total')</td><td class="num">{{ $card->total }}</td></tr>
                <tr><td>@lang('pdf.result_card.average')</td><td class="num">{{ $card->average }}%</td></tr>
                <tr><td>@lang('pdf.result_card.grade')</td><td><strong>{{ $card->grade }}</strong></td></tr>
                <tr><td>@lang('pdf.result_card.rank')</td><td class="num">{{ $card->rank }}</td></tr>
                <tr class="grand"><td>@lang('pdf.result_card.final_result')</td><td><span class="status-{{ $card->result }}">{{ $card->result === 'pass' ? __('pdf.result_card.pass') : __('pdf.result_card.fail') }}</span></td></tr>
            </table>

            <div class="scale">@lang('pdf.result_card.scale')</div>

            <div class="sig">
                <div class="sig-row">
                    <div class="sig-box"><div class="line">@lang('pdf.result_card.sig_subject_teacher')</div></div>
                    <div class="sig-box"><div class="line">@lang('pdf.result_card.sig_principal')</div></div>
                    <div class="sig-box"><div class="line">@lang('pdf.result_card.sig_guardian')</div></div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="footer">{{ $schoolName }} © {{ date('Y') }} — @lang('pdf.footer_system')</div>
</body>
</html>
