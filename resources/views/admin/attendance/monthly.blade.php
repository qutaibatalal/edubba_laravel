@extends('admin.layouts.app')

@section('title', __('attendance.monthly.title'))
@section('page', __('attendance.monthly.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('attendance.monthly.heading')</h1>
        <p class="text-muted mb-0">@lang('attendance.monthly.subtitle')</p>
    </div>
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-right me-1"></i> @lang('attendance.monthly.daily_link')</a>
</div>

<div class="card mb-4 hoverable">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">@lang('attendance.monthly.batch_label')</label>
                <select name="batch_id" class="form-select" onchange="this.form.submit()">
                    <option value="">@lang('attendance.monthly.all_batches')</option>
                    @foreach ($batches as $batch)
                        <option value="{{ $batch->id }}" @selected($batchId == $batch->id)>{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">@lang('attendance.monthly.month_label')</label>
                <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
            </div>
        </form>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">@lang('attendance.monthly.summary_month', ['month' => \Carbon\Carbon::parse($month.'-01')->translatedFormat('F Y')])</span>
        <span class="badge badge-soft-primary">@lang('attendance.monthly.student_count', ['count' => $rows->count()])</span>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0">
            <thead>
                <tr>
                    <th>@lang('attendance.student')</th>
                    <th>@lang('attendance.batch')</th>
                    <th class="text-center">@lang('attendance.monthly.days')</th>
                    <th class="text-center">@lang('attendance.status.present')</th>
                    <th class="text-center">@lang('attendance.status.late')</th>
                    <th class="text-center">@lang('attendance.status.absent')</th>
                    <th class="text-center">@lang('attendance.status.leave')</th>
                    <th>@lang('attendance.monthly.percentage')</th>
                    <th class="text-end">@lang('attendance.monthly.status')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $r)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ ($r->student->id % 6) + 1 }} avatar-sm">{{ mb_substr($r->student->full_name ?? '?', 0, 1) }}</span>
                                <span class="fw-semibold">{{ $r->student->full_name }}</span>
                            </div>
                        </td>
                        <td>{{ $r->student->batch?->name ?? '—' }}</td>
                        <td class="text-center num">{{ $r->total }}</td>
                        <td class="text-center num text-success">{{ $r->present }}</td>
                        <td class="text-center num text-warning">{{ $r->late }}</td>
                        <td class="text-center num text-danger">{{ $r->absent }}</td>
                        <td class="text-center num text-info">{{ $r->leave }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:7px;border-radius:999px">
                                    <div class="progress-bar rounded-pill
                                        @if ($r->percentage >= 90) bg-success
                                        @elseif ($r->percentage >= 75) bg-primary
                                        @elseif ($r->percentage >= 60) bg-warning
                                        @else bg-danger @endif"
                                         style="width:{{ min($r->percentage, 100) }}%"></div>
                                </div>
                                <span class="num small fw-bold">{{ $r->percentage }}%</span>
                            </div>
                        </td>
                        <td class="text-end">
                            @if ($r->percentage >= 90)
                                <span class="badge badge-soft-success">@lang('attendance.monthly.excellent')</span>
                            @elseif ($r->percentage >= 75)
                                <span class="badge badge-soft-primary">@lang('attendance.monthly.good')</span>
                            @elseif ($r->percentage >= 60)
                                <span class="badge badge-soft-warning">@lang('attendance.monthly.low')</span>
                            @else
                                <span class="badge badge-soft-danger">@lang('attendance.monthly.danger')</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9"><div class="empty-state"><i class="bi bi-calendar-x"></i><p>@lang('attendance.monthly.no_data')</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
