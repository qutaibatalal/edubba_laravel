@extends('admin.layouts.app')

@section('title', $course->name)
@section('page', __('courses.show.page', ['name' => $course->name]))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $course->name }}</h1>
        <p class="text-secondary mb-0">{{ $course->code ? __('courses.show.code', ['code' => $course->code]) : '' }} · {{ $course->subject?->name ?? '—' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> @lang('courses.show.edit')</a>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('courses.show.back_to_list')</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card hoverable">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> @lang('courses.show.details')</h6>
                <div class="text-start small">
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('courses.show.label_name')</span><b>{{ $course->name }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('courses.show.label_code')</span><b class="num">{{ $course->code ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('courses.show.label_subject')</span><b>{{ $course->subject?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('courses.show.label_batch')</span><b>{{ $course->batch?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('courses.show.label_program')</span><b>{{ $course->program?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('courses.show.label_academic_year')</span><b>{{ $course->academicYear?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('courses.show.label_teacher')</span><b>{{ $course->faculty?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('courses.show.label_credit_hours')</span><b class="num">{{ $course->credit_hours ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('courses.show.label_status')</span>
                        <span class="badge badge-soft-{{ $course->active ? 'success' : 'secondary' }}">{{ $course->active ? __('courses.show.active') : __('courses.show.inactive') }}</span>
                    </div>
                </div>
                @if ($course->syllabus)
                    <hr>
                    <div class="small">
                        <span class="text-secondary fw-semibold">@lang('courses.show.label_syllabus')</span>
                        <p class="mt-1 mb-0">{{ $course->syllabus }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card hoverable">
            <div class="card-header fw-bold"><i class="bi bi-people me-2 text-primary"></i> @lang('courses.show.enrolled_title', ['count' => $course->students->count()])</div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th>@lang('courses.show.col_name')</th><th>@lang('courses.show.col_code')</th><th>@lang('courses.show.col_status')</th></tr></thead>
                    <tbody>
                        @forelse ($course->students->take(30) as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($student->name, 0, 1) }}</span>
                                        <span>{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td class="num">{{ $student->student_code ?? '—' }}</td>
                                <td><span class="badge badge-soft-{{ $student->pivot->state === 'enrolled' ? 'success' : 'secondary' }}">{{ $student->pivot->state ?? '—' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3"><div class="empty-state"><i class="bi bi-people"></i><p>@lang('courses.show.no_students')</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection