@extends('admin.layouts.app')

@section('title', __('faculty.show.title'))
@section('page', __('faculty.show.page', ['name' => $member->full_name]))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $member->full_name }}</h1>
        <p>@lang('faculty.show.code_line', ['code' => $member->faculty_code, 'department' => $member->department?->name ?? '—'])</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.faculty.card', $member) }}" class="btn btn-outline-primary"><i class="bi bi-person-vcard me-1"></i> @lang('faculty.show.card')</a>
        <a href="{{ route('admin.faculty.edit', $member) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> @lang('faculty.show.edit')</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card hoverable">
            <div class="card-body text-center">
                <span class="avatar avatar-lg grad-1 mx-auto mb-3">{{ mb_substr($member->full_name, 0, 1) }}</span>
                <h5 class="mb-1 fw-bold">{{ $member->full_name }}</h5>
                <div class="text-secondary small mb-3">{{ $member->faculty_code }}</div>
                <span class="badge badge-soft-{{ $member->state === 'joined' ? 'success' : ($member->state === 'draft' ? 'warning' : 'secondary') }}">{{ $member->state }}</span>
                <hr>
                <div class="text-start small">
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('faculty.show.label_department')</span><b>{{ $member->department?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('faculty.show.label_specialization')</span><b>{{ $member->specialization ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('faculty.show.label_qualification')</span><b>{{ $member->qualification ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('faculty.show.label_phone')</span><b class="num">{{ $member->mobile ?? $member->phone ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('faculty.show.label_join_date')</span><b class="num">{{ $member->join_date?->format('Y/m/d') ?? '—' }}</b></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <ul class="nav nav-tabs edb-tabs mb-3" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-courses" type="button">@lang('faculty.show.tab_courses', ['count' => $member->courses->count()])</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-sessions" type="button">@lang('faculty.show.tab_sessions', ['count' => $member->classSessions->count()])</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-batches" type="button">@lang('faculty.show.tab_batches', ['count' => $member->batches->count()])</button></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-courses">
                <div class="card hoverable"><div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-edb mb-0">
                            <thead><tr><th>@lang('faculty.show.col_subject')</th><th>@lang('faculty.show.col_batch')</th><th>@lang('faculty.show.col_program')</th><th>@lang('faculty.show.col_year')</th></tr></thead>
                            <tbody>
                                @forelse ($member->courses as $course)
                                    <tr>
                                        <td>{{ $course->subject?->name }}</td>
                                        <td>{{ $course->batch?->name ?? '—' }}</td>
                                        <td>{{ $course->program?->name ?? '—' }}</td>
                                        <td>{{ $course->academicYear?->name ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-book"></i><p>@lang('faculty.show.empty_courses')</p></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div></div>
            </div>

            <div class="tab-pane fade" id="tab-sessions">
                <div class="card hoverable"><div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-edb mb-0">
                            <thead><tr><th>@lang('faculty.show.col_date')</th><th>@lang('faculty.show.col_subject')</th><th>@lang('faculty.show.col_batch')</th><th>@lang('faculty.show.col_state')</th></tr></thead>
                            <tbody>
                                @forelse ($member->classSessions->sortByDesc('date')->take(20) as $s)
                                    <tr>
                                        <td class="num">{{ $s->date?->format('Y/m/d') ?? '—' }}</td>
                                        <td>{{ $s->course?->subject?->name ?? $s->course?->name ?? '—' }}</td>
                                        <td>{{ $s->course?->batch?->name ?? '—' }}</td>
                                        <td><span class="badge badge-soft-{{ $s->state === 'done' ? 'success' : ($s->state === 'cancelled' ? 'danger' : 'warning') }}">{{ $s->state }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-calendar-x"></i><p>@lang('faculty.show.empty_sessions')</p></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div></div>
            </div>

            <div class="tab-pane fade" id="tab-batches">
                <div class="card hoverable"><div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-edb mb-0">
                            <thead><tr><th>@lang('faculty.show.col_batch')</th><th>@lang('faculty.show.col_program')</th><th>@lang('faculty.show.col_year')</th></tr></thead>
                            <tbody>
                                @forelse ($member->batches as $batch)
                                    <tr>
                                        <td>{{ $batch->name }}</td>
                                        <td>{{ $batch->program?->name ?? '—' }}</td>
                                        <td>{{ $batch->academicYear?->name ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3"><div class="empty-state"><i class="bi bi-diagram-3"></i><p>@lang('faculty.show.empty_batches')</p></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div></div>
            </div>
        </div>
    </div>
</div>

<style>
.edb-tabs { border-bottom: 1px solid var(--edb-border); }
.edb-tabs .nav-link { border: 0; border-bottom: 2px solid transparent; font-weight: 700; color: var(--edb-text-2); padding: 10px 16px; border-radius: 0; }
.edb-tabs .nav-link.active { color: var(--edb-primary); border-bottom-color: var(--edb-primary); background: transparent; }
.edb-tabs .nav-link:hover { border-bottom-color: var(--edb-border-strong); }
</style>
@endsection
