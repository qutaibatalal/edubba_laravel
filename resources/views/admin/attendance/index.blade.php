@extends('admin.layouts.app')

@section('title', __('attendance.index.title'))
@section('page', __('attendance.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('attendance.index.title')</h1>
        <p class="text-muted mb-0">@lang('attendance.index.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.attendance.monthly') }}" class="btn btn-outline-primary"><i class="bi bi-calendar-month me-1"></i> @lang('attendance.index.monthly_report')</a>
    </div>
</div>

{{-- KPI strip --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-2 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
                <div><div class="stat-value num">{{ number_format($summary['present_today']) }}</div><div class="stat-label">@lang('attendance.index.present_today')</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-5 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
                <div><div class="stat-value num">{{ number_format($summary['absent_today']) }}</div><div class="stat-label">@lang('attendance.index.absent_today')</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-4 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
                <div><div class="stat-value num">{{ number_format($summary['late_today']) }}</div><div class="stat-label">@lang('attendance.index.late_today')</div></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-1 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-clipboard-check"></i></div>
                <div><div class="stat-value num">{{ number_format($summary['sheets_today']) }}</div><div class="stat-label">@lang('attendance.index.sheets_today')</div></div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4 hoverable">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">@lang('attendance.index.batch_label')</label>
                <select name="batch_id" class="form-select" onchange="this.form.submit()">
                    <option value="">@lang('attendance.index.all_batches')</option>
                    @foreach ($batches as $batch)
                        <option value="{{ $batch->id }}" @selected($batchId == $batch->id)>{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">@lang('attendance.index.date_label')</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
            </div>
        </form>
    </div>
</div>

{{-- Sessions of the day --}}
<div class="row g-3">
    <div class="col-xl-5">
        <div class="card hoverable">
            <div class="card-header fw-bold">@lang('attendance.index.sessions_day', ['date' => \Carbon\Carbon::parse($date)->translatedFormat('l j F Y')])</div>
            <div class="table-responsive">
                <table class="table table-edb mb-0">
                    <thead><tr><th>@lang('attendance.index.time')</th><th>@lang('attendance.batch')</th><th>@lang('attendance.index.subject')</th><th>@lang('attendance.state')</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($sessions as $s)
                            <tr>
                                <td class="num" style="white-space:nowrap">
                                    {{ $s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('g:i A') : '—' }}
                                </td>
                                <td class="fw-semibold">{{ $s->batch?->name ?? '—' }}</td>
                                <td>{{ $s->subject?->name ?? $s->course?->name ?? '—' }}</td>
                                <td>
                                    @php $st = ['planned' => 'info', 'done' => 'success', 'cancelled' => 'danger']; @endphp
                                    <span class="badge badge-soft-{{ $st[$s->state] ?? 'secondary' }}">{{ $s->state }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.attendance.index', ['session_id' => $s->id, 'batch_id' => $batchId, 'date' => $date]) }}"
                                       class="btn btn-sm btn-outline-primary">@lang('attendance.index.record')</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-calendar-x"></i><p>@lang('attendance.index.no_sessions')</p><small>@lang('attendance.index.no_sessions_hint')</small></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        @if ($sheet)
            <div class="card hoverable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">@lang('attendance.index.record_header', ['session' => $session?->subject?->name ?? $session?->course?->name ?? __('attendance.session')])
                        <span class="badge badge-soft ms-2">{{ $sheet->batch?->name }}</span>
                    </span>
                    <span class="badge badge-soft-{{ $sheet->state === 'done' ? 'success' : 'warning' }}">{{ $sheet->state === 'done' ? __('attendance.status.recorded') : __('attendance.status.draft') }}</span>
                </div>
                <form method="POST" action="{{ route('admin.attendance.mark', $sheet) }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-edb mb-0">
                            <thead><tr><th>@lang('attendance.student')</th><th>@lang('attendance.state')</th></tr></thead>
                            <tbody>
                                @forelse ($sheet->lines()->with('student')->get() as $line)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="avatar grad-{{ ($line->id % 6) + 1 }} avatar-sm">{{ mb_substr($line->student?->full_name ?? '?', 0, 1) }}</span>
                                                <span class="fw-semibold">{{ $line->student?->full_name ?? '—' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @foreach (['present' => __('attendance.status.present'), 'absent' => __('attendance.status.absent'), 'late' => __('attendance.status.late'), 'leave' => __('attendance.status.leave')] as $val => $label)
                                                    @php
                                                        $checked = old("statuses.{$line->student_id}", $line->status) === $val;
                                                        $btnClass = match ($val) {
                                                            'present' => $checked ? 'btn-success' : 'btn-outline-success',
                                                            'absent' => $checked ? 'btn-danger' : 'btn-outline-danger',
                                                            'late' => $checked ? 'btn-warning' : 'btn-outline-warning',
                                                            'leave' => $checked ? 'btn-info' : 'btn-outline-info',
                                                        };
                                                    @endphp
                                                    <button type="button" class="btn {{ $btnClass }} status-btn"
                                                            data-status="{{ $val }}">{{ $label }}</button>
                                                @endforeach
                                            </div>
                                            <input type="hidden" name="statuses[{{ $line->student_id }}]"
                                                   value="{{ old("statuses.{$line->student_id}", $line->status) }}">
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2"><div class="empty-state"><i class="bi bi-people"></i><p>@lang('attendance.index.no_students')</p></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary" {{ $sheet->lines->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-check2-circle me-1"></i> @lang('attendance.index.save')
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="card hoverable">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="bi bi-clipboard-check"></i>
                        <p>@lang('attendance.index.select_session')</p>
                        <small>@lang('attendance.index.select_session_hint')</small>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            const group = btn.closest('.btn-group');
            const hidden = group.querySelector('input[type=hidden]');
            const status = btn.dataset.status;

            hidden.value = status;
            group.querySelectorAll('.status-btn').forEach(b => {
                b.className = 'btn ' + ({
                    present: b.dataset.status === 'present' ? 'btn-success' : 'btn-outline-success',
                    absent: b.dataset.status === 'absent' ? 'btn-danger' : 'btn-outline-danger',
                    late: b.dataset.status === 'late' ? 'btn-warning' : 'btn-outline-warning',
                    leave: b.dataset.status === 'leave' ? 'btn-info' : 'btn-outline-info',
                })[b.dataset.status];
            });
        });
    });
</script>
@endpush
