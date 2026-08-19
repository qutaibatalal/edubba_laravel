@extends('admin.layouts.app')

@section('title', $batch->name)
@section('page', __('batches.show.page', ['name' => $batch->name]))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $batch->name }}</h1>
        <p class="text-secondary mb-0">{{ $batch->program?->name ?? '—' }} · {{ $batch->academicYear?->name ?? '—' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.batches.edit', $batch) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> @lang('batches.show.edit')</a>
        <a href="{{ route('admin.batches.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('batches.show.back_to_list')</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card hoverable">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> @lang('batches.show.details')</h6>
                <div class="text-start small">
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('batches.show.label_name')</span><b>{{ $batch->name }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('batches.show.label_program')</span><b>{{ $batch->program?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('batches.show.label_academic_year')</span><b>{{ $batch->academicYear?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('batches.show.label_class_teacher')</span><b>{{ $batch->classTeacher?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('batches.show.label_capacity')</span><b class="num">{{ $batch->capacity ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('batches.show.label_status')</span>
                        <span class="badge badge-soft-{{ $batch->active ? 'success' : 'secondary' }}">{{ $batch->active ? __('batches.show.active') : __('batches.show.inactive') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <ul class="nav nav-tabs edb-tabs mb-3" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-students" type="button">@lang('batches.show.tab_students', ['count' => $batch->students->count()])</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-courses" type="button">@lang('batches.show.tab_courses', ['count' => $batch->courses->count()])</button></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-students">
                <div class="card hoverable"><div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-edb mb-0">
                            <thead><tr><th>@lang('batches.show.col_name')</th><th>@lang('batches.show.col_code')</th><th>@lang('batches.show.col_status')</th></tr></thead>
                            <tbody>
                                @forelse ($batch->students->take(30) as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($student->name, 0, 1) }}</span>
                                                <span>{{ $student->name }}</span>
                                            </div>
                                        </td>
                                        <td class="num">{{ $student->student_code ?? '—' }}</td>
                                        <td><span class="badge badge-soft-{{ $student->state === 'admitted' ? 'success' : 'secondary' }}">{{ $student->state ?? '—' }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3"><div class="empty-state"><i class="bi bi-people"></i><p>@lang('batches.show.no_students')</p></div></td></tr>
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
                            <thead><tr><th>@lang('batches.show.col_name')</th><th>@lang('batches.show.col_subject')</th><th>@lang('batches.show.col_teacher')</th></tr></thead>
                            <tbody>
                                @forelse ($batch->courses as $course)
                                    <tr>
                                        <td>{{ $course->name }}</td>
                                        <td>{{ $course->subject?->name ?? '—' }}</td>
                                        <td>{{ $course->faculty?->name ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3"><div class="empty-state"><i class="bi bi-book"></i><p>@lang('batches.show.no_courses')</p></div></td></tr>
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