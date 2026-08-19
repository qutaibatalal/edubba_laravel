@extends('admin.layouts.app')

@section('title', $program->name)
@section('page', __('programs.show.page', ['name' => $program->name]))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $program->name }}</h1>
        <p class="text-secondary mb-0">{{ $program->code ? __('programs.show.code', ['code' => $program->code]) : '' }} · {{ $program->department?->name ?? '—' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> @lang('programs.show.edit')</a>
        <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('programs.show.back_to_list')</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card hoverable">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> @lang('programs.show.details')</h6>
                <div class="text-start small">
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('programs.show.label_name')</span><b>{{ $program->name }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('programs.show.label_code')</span><b class="num">{{ $program->code ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('programs.show.label_department')</span><b>{{ $program->department?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('programs.show.label_duration')</span><b class="num">{{ $program->duration_years ? __('programs.show.years', ['count' => $program->duration_years]) : '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('programs.show.label_status')</span>
                        <span class="badge badge-soft-{{ $program->active ? 'success' : 'secondary' }}">{{ $program->active ? __('programs.show.active') : __('programs.show.inactive') }}</span>
                    </div>
                    @if ($program->description)
                        <div class="mt-2"><span class="text-secondary">@lang('programs.show.label_description')</span><p class="mt-1 mb-0">{{ $program->description }}</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <ul class="nav nav-tabs edb-tabs mb-3" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-batches" type="button">@lang('programs.show.tab_batches', ['count' => $program->batches->count()])</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-courses" type="button">@lang('programs.show.tab_courses', ['count' => $program->courses->count()])</button></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-batches">
                <div class="card hoverable"><div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-edb mb-0">
                            <thead><tr><th>@lang('programs.show.col_name')</th><th>@lang('programs.show.col_year')</th><th>@lang('programs.show.col_teacher')</th><th>@lang('programs.show.col_students')</th></tr></thead>
                            <tbody>
                                @forelse ($program->batches as $batch)
                                    <tr>
                                        <td>{{ $batch->name }}</td>
                                        <td>{{ $batch->academicYear?->name ?? '—' }}</td>
                                        <td>{{ $batch->classTeacher?->name ?? '—' }}</td>
                                        <td><span class="badge badge-soft-primary">{{ $batch->students->count() }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-diagram-3"></i><p>@lang('programs.show.no_batches')</p></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div></div>
            </div>

            <div class="tab-pane fade" id="tab-courses">
                <div class="card hoverable"><div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-edb mb-0">
                            <thead><tr><th>@lang('programs.show.col_name')</th><th>@lang('programs.show.col_code')</th><th>@lang('programs.show.col_subject')</th><th>@lang('programs.show.col_teacher')</th></tr></thead>
                            <tbody>
                                @forelse ($program->courses as $course)
                                    <tr>
                                        <td>{{ $course->name }}</td>
                                        <td class="num">{{ $course->code ?? '—' }}</td>
                                        <td>{{ $course->subject?->name ?? '—' }}</td>
                                        <td>{{ $course->faculty?->name ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-book"></i><p>@lang('programs.show.no_courses')</p></div></td></tr>
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