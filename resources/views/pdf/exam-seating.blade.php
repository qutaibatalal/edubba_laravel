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
    $title = $schedule ? __('pdf.seating.title_with', ['name' => $schedule->subject?->name ?? $schedule->course?->name ?? '']) : __('pdf.seating.title');
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: 'xbriyaz', sans-serif; box-sizing: border-box; }
        body { margin: 0; padding: 24px 30px; color: #111827; font-size: 11px; }
        .doc-head { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid {{ $primaryColor }}; padding-bottom: 12px; margin-bottom: 16px; }
        .school { display: flex; align-items: center; gap: 10px; }
        .logo { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; }
        .school h1 { margin: 0; font-size: 15px; font-weight: 800; }
        .school p { margin: 2px 0 0; color: #6b7280; font-size: 9px; }
        .doc-title { text-align: center; }
        .doc-title h2 { margin: 0; font-size: 15px; color: {{ $primaryColor }}; font-weight: 800; }
        .doc-title span { color: #6b7280; font-size: 9px; }
        .meta-row { display: flex; gap: 16px; margin-bottom: 14px; flex-wrap: wrap; }
        .meta-col { min-width: 120px; }
        .meta-col label { display: block; font-size: 8px; color: #6b7280; margin-bottom: 2px; }
        .meta-col strong { font-size: 11px; }
        .room-block { page-break-inside: avoid; margin-bottom: 14px; }
        .room-head { background: #f3f4f6; border: 1px solid #e5e7eb; border-bottom: 0; padding: 8px 12px; font-weight: 800; font-size: 11px; color: #374151; }
        .room-head .cap { font-weight: 600; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 7px 9px; text-align: right; }
        th { background: #f3f4f6; font-size: 9px; color: #374151; }
        td { font-size: 10px; }
        .num { font-variant-numeric: tabular-nums; }
        .sig { margin-top: 30px; }
        .sig-row { display: flex; justify-content: space-between; gap: 30px; }
        .sig-box { flex: 1; text-align: center; }
        .sig-box .line { border-top: 1px solid #9ca3af; margin-top: 40px; padding-top: 6px; color: #6b7280; font-size: 9px; }
        .footer { position: fixed; bottom: -30px; right: 0; left: 0; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #f3f4f6; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="doc-head">
        <div class="school">
            <img class="logo" src="{{ $logoDataUri }}" alt="{{ $schoolName }}">
            <div>
                <h1>{{ $schoolName }}</h1>
                <p>{{ $title }}</p>
            </div>
        </div>
        <div class="doc-title">
            <h2>@lang('pdf.seating.title')</h2>
            <span>@lang('pdf.seating.title')</span>
        </div>
    </div>

    <div class="meta-row">
        <div class="meta-col">
            <label>@lang('pdf.seating.exam')</label>
            <strong>{{ $exam->name }}</strong>
        </div>
        <div class="meta-col">
            <label>@lang('pdf.seating.batch')</label>
            <strong>{{ $exam->batch?->name ?? __('pdf.seating.all') }}</strong>
        </div>
        @if ($schedule)
            <div class="meta-col">
                <label>@lang('pdf.seating.date')</label>
                <strong>{{ $schedule->date?->format('Y/m/d') }}</strong>
            </div>
            <div class="meta-col">
                <label>@lang('pdf.seating.time')</label>
                <strong>{{ $schedule->start_time ? substr($schedule->start_time, 0, 5) : '—' }} → {{ $schedule->end_time ? substr($schedule->end_time, 0, 5) : '—' }}</strong>
            </div>
        @endif
        <div class="meta-col">
            <label>@lang('pdf.seating.total_students')</label>
            <strong class="num">{{ $grouped->flatten()->count() }}</strong>
        </div>
    </div>

    @forelse ($grouped as $roomId => $students)
        @php $room = $students->first()->examRoom; @endphp
        <div class="room-block">
            <div class="room-head">
                @lang('pdf.seating.room_name', ['name' => $room?->name ?? '—'])
                @if ($room?->location) · {{ $room->location }} @endif
                <span class="cap">@lang('pdf.seating.capacity', ['count' => $students->count(), 'capacity' => $room?->capacity])</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width:12%" class="num">@lang('pdf.seating.col_no')</th>
                        <th style="width:12%" class="num">@lang('pdf.seating.col_seat')</th>
                        <th>@lang('pdf.seating.col_student')</th>
                        <th style="width:18%" class="num">@lang('pdf.seating.col_univ_no')</th>
                        <th style="width:16%">@lang('pdf.seating.col_signature')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students->sortBy('seat_no') as $s)
                        <tr>
                            <td class="num">{{ $loop->iteration }}</td>
                            <td class="num">{{ $s->seat_no }}</td>
                            <td>{{ $s->student?->full_name ?? $s->student?->name ?? '—' }}</td>
                            <td class="num">{{ $s->student?->student_code ?? '—' }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p style="text-align:center;color:#9ca3af;padding:40px">@lang('pdf.seating.empty')</p>
    @endforelse

    <div class="sig">
        <div class="sig-row">
            <div class="sig-box"><div class="line">@lang('pdf.seating.sig_invigilator')</div></div>
            <div class="sig-box"><div class="line">@lang('pdf.seating.sig_officer')</div></div>
        </div>
    </div>

    <div class="footer">{{ $schoolName }} © {{ date('Y') }} — @lang('pdf.footer_system')</div>
</body>
</html>
