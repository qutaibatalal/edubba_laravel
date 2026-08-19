@php
    $primaryColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
    $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => App\Models\MobileAppConfig::configValue('school_name', 'Edubba School'));
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: 'xbriyaz', sans-serif; box-sizing: border-box; }
        body { margin: 0; padding: 30px 34px; color: #111827; font-size: 11px; }
        .doc-head { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid {{ $primaryColor }}; padding-bottom: 12px; margin-bottom: 18px; }
        .doc-head h1 { margin: 0; font-size: 16px; font-weight: 800; }
        .doc-head p { margin: 3px 0 0; color: #6b7280; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        th, td { border: 1px solid #e5e7eb; padding: 7px 9px; text-align: right; }
        th { background: #f3f4f6; font-size: 10px; color: #374151; }
        td { font-size: 10px; }
        .num { font-variant-numeric: tabular-nums; text-align: center; }
        .badge-good { color: #15803d; font-weight: 700; }
        .badge-mid { color: #b45309; font-weight: 700; }
        .badge-low { color: #b91c1c; font-weight: 700; }
        tfoot td { font-weight: 800; background: #f9fafb; }
        .footer { position: fixed; bottom: -30px; right: 0; left: 0; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #f3f4f6; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="doc-head">
        <div>
            <h1>{{ $schoolName }}</h1>
            <p>@lang('pdf.attendance.monthly')</p>
        </div>
        <div style="text-align:center">
            <h1>@lang('pdf.attendance.title')</h1>
            <p>{{ \Carbon\Carbon::parse($month->format('Y-m').'-01')->translatedFormat('F Y') }}
               {{ ! empty($batch) && $batch ? ' - '.$batch->name : ' - '. __('pdf.attendance.all_batches') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th>@lang('pdf.attendance.student')</th>
                <th>@lang('pdf.attendance.batch')</th>
                <th class="num" style="width:9%">@lang('pdf.attendance.total_periods')</th>
                <th class="num" style="width:7%">@lang('pdf.attendance.present')</th>
                <th class="num" style="width:7%">@lang('pdf.attendance.late')</th>
                <th class="num" style="width:7%">@lang('pdf.attendance.absent')</th>
                <th class="num" style="width:7%">@lang('pdf.attendance.leave')</th>
                <th class="num" style="width:9%">@lang('pdf.attendance.rate')</th>
                <th class="num" style="width:8%">@lang('pdf.attendance.status')</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $idx => $r)
            <tr>
                <td class="num">{{ $idx + 1 }}</td>
                <td>{{ $r->student->full_name }}</td>
                <td>{{ $r->batch ?? '—' }}</td>
                <td class="num">{{ $r->total }}</td>
                <td class="num">{{ $r->present }}</td>
                <td class="num">{{ $r->late }}</td>
                <td class="num">{{ $r->absent }}</td>
                <td class="num">{{ $r->leave }}</td>
                <td class="num">{{ $r->percentage }}%</td>
                <td class="num @if ($r->percentage >= 90) badge-good @elseif ($r->percentage >= 75) badge-mid @elseif ($r->percentage >= 60) badge-low @else badge-low @endif">
                    @if ($r->percentage >= 90) @lang('pdf.attendance.status_excellent')
                    @elseif ($r->percentage >= 75) @lang('pdf.attendance.status_good')
                    @elseif ($r->percentage >= 60) @lang('pdf.attendance.status_low')
                    @else @lang('pdf.attendance.status_critical')
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="10" style="text-align:center;color:#9ca3af">@lang('pdf.attendance.empty')</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">@lang('pdf.attendance.total')</td>
                <td class="num">{{ $data->sum('total') }}</td>
                <td class="num">{{ $data->sum('present') }}</td>
                <td class="num">{{ $data->sum('late') }}</td>
                <td class="num">{{ $data->sum('absent') }}</td>
                <td class="num">{{ $data->sum('leave') }}</td>
                <td colspan="2">@lang('pdf.attendance.avg_rate', ['rate' => number_format($data->avg('percentage') ?: 0, 1)])</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">{{ $schoolName }} © {{ date('Y') }} — @lang('pdf.footer_system')</div>
</body>
</html>
