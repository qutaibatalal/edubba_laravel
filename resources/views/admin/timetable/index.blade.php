@extends('admin.layouts.app')

@section('title', __('timetable.index.title'))
@section('page', __('timetable.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('timetable.index.title')</h1>
        <p class="text-muted mb-0">@lang('timetable.index.subtitle')</p>
    </div>
</div>

{{-- Conflicts alert --}}
@if ($conflicts->isNotEmpty())
    <div class="card mb-4 border-danger hoverable">
        <div class="card-header d-flex justify-content-between align-items-center text-danger">
            <span class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> @lang('timetable.index.conflicts', ['count' => $conflicts->count()])</span>
        </div>
        <div class="table-responsive">
            <table class="table table-edb mb-0">
                <thead><tr><th>@lang('timetable.index.th_type')</th><th>@lang('timetable.index.th_item')</th><th>@lang('timetable.index.th_day')</th><th>@lang('timetable.index.th_time')</th><th>@lang('timetable.index.th_conflicting_sessions')</th></tr></thead>
                <tbody>
                    @foreach ($conflicts as $c)
                        <tr>
                            <td>
                                <span class="badge badge-soft-{{ $c->type === 'faculty' ? 'danger' : ($c->type === 'classroom' ? 'warning' : 'info') }}">
                                    {{ match ($c->type) { 'faculty' => __('timetable.index.type_faculty'), 'classroom' => __('timetable.index.type_classroom'), 'batch' => __('timetable.index.type_batch'), default => $c->type } }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $c->label }}</td>
                            <td>{{ $c->day }}</td>
                            <td class="num">{{ $c->timing }}</td>
                            <td class="small text-muted num">{{ $c->lines }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="alert alert-success py-2 small">
        <i class="bi bi-check-circle-fill me-1"></i> @lang('timetable.index.no_conflicts')
    </div>
@endif

{{-- Week filter --}}
<div class="card mb-4 hoverable">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">@lang('timetable.index.week_starts')</label>
                <input type="date" name="week_start" value="{{ $weekStart }}" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-auto pb-1">
                <span class="badge badge-soft-primary"><i class="bi bi-mortarboard me-1"></i> @lang('timetable.index.week_sessions_count', ['count' => $sessions->count()])</span>
            </div>
        </form>
    </div>
</div>

{{-- Weekly calendar grid --}}
<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-bold"><i class="bi bi-calendar2-week me-2 text-primary"></i> {{ $weekStart }} — {{ $weekEnd }}</span>
        <form method="POST" action="{{ route('admin.timetable.generate') }}" class="d-flex align-items-center gap-2">
            @csrf
            <input type="date" name="date" value="{{ $weekStart }}" class="form-control form-control-sm" style="width:auto">
            <button class="btn btn-sm btn-success"><i class="bi bi-calendar-plus me-1"></i> @lang('timetable.index.generate_day')</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead>
                <tr>
                    <th style="min-width:130px">@lang('timetable.index.th_day')</th>
                    <th>@lang('timetable.index.th_sessions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($weekDays as $dayIndex => $day)
                    <tr>
                        <td class="fw-bold">
                            {{ \Carbon\Carbon::parse($weekStart)->addDays($dayIndex)->translatedFormat('l') }}
                            <div class="small text-secondary num">{{ \Carbon\Carbon::parse($weekStart)->addDays($dayIndex)->format('d/m') }}</div>
                        </td>
                        <td>
                            @php $daySessions = $sessions->where('date', \Carbon\Carbon::parse($weekStart)->addDays($dayIndex)->toDateString()); @endphp
                            @forelse ($daySessions as $s)
                                <span class="badge badge-soft-info me-1 mb-1 d-inline-flex align-items-center gap-1" style="font-size:.8rem;font-weight:600">
                                    {{ $s->start_time ? \Carbon\Carbon::parse($s->start_time)->format('g:i A') : '' }}
                                    {{ $s->batch?->name }} — {{ $s->subject?->name ?? $s->course?->name }}
                                </span>
                            @empty
                                <span class="small text-secondary">@lang('timetable.index.no_sessions')</span>
                            @endforelse
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
