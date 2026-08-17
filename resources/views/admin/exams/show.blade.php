@extends('admin.layouts.app')

@section('title', $exam->name)
@section('page', __('exams.show.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $exam->name }}</h1>
        <p class="text-secondary mb-0">
            {{ $exam->examType?->name ?? __('exams.show.exam') }} ·
            {{ $exam->batch?->name ?? __('exams.index.all_batches') }} ·
            @if ($exam->date_start) {{ $exam->date_start->format('Y-m-d') }} → {{ $exam->date_end?->format('Y-m-d') }} @endif
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.exams.marksheets', $exam) }}" class="btn btn-outline-primary"><i class="bi bi-card-checklist me-1"></i> @lang('exams.show.marksheets')</a>
        <a href="{{ route('admin.exams.results', $exam) }}" class="btn btn-outline-primary"><i class="bi bi-bar-chart me-1"></i> @lang('exams.show.results')</a>
        <a href="{{ route('admin.exams.seating.pdf', [$exam]) }}" class="btn btn-outline-primary"><i class="bi bi-printer me-1"></i> @lang('exams.show.seating_pdf')</a>
        <a href="{{ route('admin.exams.index') }}" class="btn btn-light border"><i class="bi bi-arrow-right me-1"></i> @lang('exams.show.back')</a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger py-2">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
@endif

<div class="bento">
    <div class="b-8">
        <div class="card hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-calendar2-week me-2 text-primary"></i> @lang('exams.show.sessions_title')</span>
                <span class="badge badge-soft-primary">@lang('exams.show.session_count', ['count' => $schedules->count()])</span>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th>@lang('exams.show.col_subject')</th><th>@lang('exams.show.col_date')</th><th>@lang('exams.show.col_time')</th><th>@lang('exams.show.col_marks')</th><th>@lang('exams.show.col_distribution')</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($schedules as $schedule)
                            @php
                                $allocCount = $exam->roomAllocations->where('exam_schedule_id', $schedule->id)->count();
                                $attended = $exam->roomAllocations->where('exam_schedule_id', $schedule->id)->whereNotNull('attended')->count();
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $schedule->subject?->name ?? $schedule->course?->name ?? '—' }}</div>
                                    <small class="text-secondary">{{ $schedule->subject ? $schedule->course?->name : '' }}</small>
                                </td>
                                <td class="num">{{ $schedule->date->format('Y-m-d') }}</td>
                                <td class="num">{{ $schedule->start_time ? substr($schedule->start_time, 0, 5) : '—' }} → {{ $schedule->end_time ? substr($schedule->end_time, 0, 5) : '—' }}</td>
                                <td class="num">{{ $schedule->max_marks }} <small class="text-secondary">@lang('exams.show.pass_marks', ['marks' => $schedule->pass_marks])</small></td>
                                <td>
                                    @if ($allocCount)
                                        <span class="badge badge-soft-success">@lang('exams.show.student_count', ['count' => $allocCount])</span>
                                        <span class="badge badge-soft-info">@lang('exams.show.attended_count', ['count' => $attended])</span>
                                    @else
                                        <span class="badge badge-soft-secondary">@lang('exams.show.not_distributed')</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <form method="POST" action="{{ route('admin.exams.distribute', $exam) }}">
                                            @csrf
                                            <input type="hidden" name="exam_schedule_id" value="{{ $schedule->id }}">
                                            <button class="btn btn-sm btn-outline-primary" {{ $allocCount ? 'disabled' : '' }} title="{{ $allocCount ? __('exams.show.distributed_already') : __('exams.show.distribute_title') }}"><i class="bi bi-grid-3x3-gap"></i> @lang('exams.show.distribute')</button>
                                        </form>
                                        <a class="btn btn-sm btn-light border" href="{{ route('admin.exams.seating.pdf', [$exam, $schedule->id]) }}" title="@lang('exams.show.seating_sheet')"><i class="bi bi-printer"></i></a>
                                        <form method="POST" action="{{ route('admin.exams.schedule.destroy', [$exam, $schedule]) }}" onsubmit="return confirm('{{ __('exams.show.confirm_delete_session') }}')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><div class="empty-state"><i class="bi bi-calendar-x"></i><p>@lang('exams.show.empty_title')</p><small>@lang('exams.show.empty_hint')</small></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="b-4">
        <div class="card hoverable">
            <div class="card-header fw-semibold"><i class="bi bi-plus-circle me-2 text-primary"></i> @lang('exams.show.add_session')</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.exams.schedule.store', $exam) }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">@lang('exams.show.form_subject')</label>
                        <select name="subject_id" class="form-select">
                            <option value="">—</option>
                            @foreach ($subjects as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">@lang('exams.show.form_course')</label>
                        <select name="course_id" class="form-select">
                            <option value="">—</option>
                            @foreach ($courses as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">@lang('exams.show.form_date')</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="col-3">
                        <label class="form-label">@lang('exams.show.form_from')</label>
                        <input type="time" name="start_time" class="form-control">
                    </div>
                    <div class="col-3">
                        <label class="form-label">@lang('exams.show.form_to')</label>
                        <input type="time" name="end_time" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label">@lang('exams.show.form_max_marks')</label>
                        <input type="number" name="max_marks" class="form-control" min="0" step="0.5" value="100">
                    </div>
                    <div class="col-6">
                        <label class="form-label">@lang('exams.show.form_pass_marks')</label>
                        <input type="number" name="pass_marks" class="form-control" min="0" step="0.5" value="50">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary w-100">@lang('exams.show.add_session')</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card hoverable mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i> @lang('exams.show.distribution_title')</span>
                @if ($exam->roomAllocations->isEmpty())
                    <form method="POST" action="{{ route('admin.exams.distribute', $exam) }}">
                        @csrf
                        <button class="btn btn-sm btn-success"><i class="bi bi-magic me-1"></i> @lang('exams.show.auto_distribute')</button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                @php
                    $total = $exam->roomAllocations->count();
                    $present = $exam->roomAllocations->where('attended', true)->count();
                    $absent = $exam->roomAllocations->where('attended', false)->count();
                @endphp
                @if ($total)
                    <div class="d-flex gap-3 flex-wrap">
                        <span class="badge badge-soft-primary">@lang('exams.show.total', ['count' => $total])</span>
                        <span class="badge badge-soft-success">@lang('exams.show.present', ['count' => $present])</span>
                        <span class="badge badge-soft-danger">@lang('exams.show.absent', ['count' => $absent])</span>
                        <span class="badge badge-soft-secondary">@lang('exams.show.no_record', ['count' => $total - $present - $absent])</span>
                    </div>
                    <div class="mt-3 progress" style="height:8px">
                        <div class="progress-bar bg-success" style="width: {{ $total ? round($present / $total * 100) : 0 }}%"></div>
                        <div class="progress-bar bg-danger" style="width: {{ $total ? round($absent / $total * 100) : 0 }}%"></div>
                    </div>
                @else
                    <p class="text-secondary small mb-0">@lang('exams.show.no_distribution')</p>
                @endif
            </div>
        </div>
    </div>
</div>

@foreach ($schedules as $schedule)
    @php
        $groups = $distribution->get($schedule->id, collect());
        $allocations = $exam->roomAllocations->where('exam_schedule_id', $schedule->id);
    @endphp
        @if ($groups->isNotEmpty())
        <div class="card hoverable mt-4">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="fw-semibold"><i class="bi bi-person-vcard me-2 text-primary"></i> @lang('exams.show.session_distribution', ['subject' => $schedule->subject?->name ?? $schedule->course?->name ?? '', 'date' => $schedule->date->format('Y-m-d')])</span>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge badge-soft-primary">@lang('exams.show.student_count', ['count' => $allocations->count()])</span>
                    @if ($allocations->whereNull('attended')->isNotEmpty())
                        <form method="POST" action="{{ route('admin.exams.held', $exam) }}" class="d-flex gap-2">
                            @csrf
                            <input type="hidden" name="exam_schedule_id" value="{{ $schedule->id }}">
                            <button class="btn btn-sm btn-success" type="button" onclick="markAll(this, true)">@lang('exams.show.all_present')</button>
                            <button class="btn btn-sm btn-outline-danger" type="button" onclick="markAll(this, false)">@lang('exams.show.all_absent')</button>
                            <button class="btn btn-sm btn-primary"><i class="bi bi-check-lg"></i> @lang('exams.show.record_attendance')</button>
                        </form>
                    @else
                        <span class="badge badge-soft-success">@lang('exams.show.attendance_recorded')</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if ($allocations->whereNull('attended')->isNotEmpty())
                    <form method="POST" action="{{ route('admin.exams.held', $exam) }}" class="mb-3">
                        @csrf
                        <input type="hidden" name="exam_schedule_id" value="{{ $schedule->id }}">
                        <div class="row g-2 align-items-center">
                            @foreach ($allocations as $allocation)
                                <div class="col-md-4 col-lg-3">
                                    <div class="border rounded-3 p-2 d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="attended[{{ $allocation->id }}]" value="1" checked>
                                        <span class="fw-semibold small">{{ $allocation->student?->name }}</span>
                                        <span class="badge badge-soft-secondary num ms-auto">{{ $allocation->room?->code ?? $allocation->room?->name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="btn btn-primary mt-3"><i class="bi bi-check-lg me-1"></i> @lang('exams.show.record_attendance')</button>
                    </form>
                @endif

                <div class="row g-3">
                    @foreach ($groups as $roomId => $students)
                        @php $room = $students->first()->examRoom; @endphp
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100">
                                <div class="card-header py-2 d-flex justify-content-between">
                                    <span class="small"><i class="bi bi-easel me-1 text-primary"></i> {{ $room?->name ?? __('exams.show.room') }}</span>
                                    <span class="badge badge-soft-info num">{{ $students->count() }} / {{ $room?->capacity }}</span>
                                </div>
                                <div class="card-body py-2">
                                    @foreach ($students as $s)
                                        <div class="d-flex justify-content-between align-items-center border-bottom py-1" style="border-color: var(--edb-border) !important">
                                            <span class="small">
                                                <span class="badge badge-soft-secondary num me-1">@lang('exams.show.seat', ['seat' => $s->seat_no])</span>
                                                {{ $s->student?->name }}
                                            </span>
                                            @php
                                                $badge = $s->attended === null ? 'secondary' : ($s->attended ? 'success' : 'danger');
                                                $icon = $s->attended === null ? 'minus' : ($s->attended ? 'check-lg' : 'x-lg');
                                            @endphp
                                            <span class="badge badge-soft-{{ $badge }}"><i class="bi bi-{{ $icon }}"></i></span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach

@if ($distribution->has(0))
    @php $groups = $distribution->get(0); @endphp
    <div class="card hoverable mt-4">
        <div class="card-header fw-semibold"><span><i class="bi bi-grid-3x3-gap me-2 text-primary"></i> @lang('exams.show.no_session_distribution')</span></div>
        <div class="card-body">
            <div class="row g-3">
                @foreach ($groups as $roomId => $students)
                    @php $room = $students->first()->examRoom; @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-header py-2"><span class="small"><i class="bi bi-easel me-1 text-primary"></i> {{ $room?->name }}</span></div>
                            <div class="card-body py-2">
                                @foreach ($students as $s)
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-1" style="border-color: var(--edb-border) !important">
                                        <span class="small"><span class="badge badge-soft-secondary num me-1">@lang('exams.show.seat', ['seat' => $s->seat_no])</span>{{ $s->student?->name }}</span>
                                        <span class="badge badge-soft-{{ $s->attended === null ? 'secondary' : ($s->attended ? 'success' : 'danger') }}">
                                            <i class="bi bi-{{ $s->attended === null ? 'minus' : ($s->attended ? 'check-lg' : 'x-lg') }}"></i>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    function markAll(btn, present) {
        const form = btn.closest('form');
        form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = present);
    }
</script>
@endpush
