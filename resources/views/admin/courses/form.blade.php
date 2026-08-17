@extends('admin.layouts.app')

@section('title', $course ? __('courses.form.edit_course') : __('courses.form.new_course'))
@section('page', $course ? __('courses.form.page_edit', ['name' => $course->name]) : __('courses.form.page_create'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $course ? __('courses.form.page_edit', ['name' => $course->name]) : __('courses.form.page_create') }}</h1>
        <p>@lang('courses.form.subtitle')</p>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="{{ $course ? route('admin.courses.update', $course) : route('admin.courses.store') }}">
            @csrf
            @if ($course) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">@lang('courses.form.label_name')</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $course?->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('courses.form.label_code')</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $course?->code) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('courses.form.label_subject')</label>
                    <select name="subject_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($subjects as $s)
                            <option value="{{ $s->id }}" {{ old('subject_id', $course?->subject_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('courses.form.label_batch')</label>
                    <select name="batch_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($batches as $b)
                            <option value="{{ $b->id }}" {{ old('batch_id', $course?->batch_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('courses.form.label_program')</label>
                    <select name="program_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($programs as $p)
                            <option value="{{ $p->id }}" {{ old('program_id', $course?->program_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('courses.form.label_academic_year')</label>
                    <select name="academic_year_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($years as $y)
                            <option value="{{ $y->id }}" {{ old('academic_year_id', $course?->academic_year_id) == $y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('courses.form.label_teacher')</label>
                    <select name="faculty_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($faculty as $f)
                            <option value="{{ $f->id }}" {{ old('faculty_id', $course?->faculty_id) == $f->id ? 'selected' : '' }}>{{ $f->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('courses.form.label_credit_hours')</label>
                    <input type="number" name="credit_hours" class="form-control" value="{{ old('credit_hours', $course?->credit_hours) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">@lang('courses.form.label_syllabus')</label>
                    <textarea name="syllabus" class="form-control" rows="3">{{ old('syllabus', $course?->syllabus) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> @lang('courses.form.save')</button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">@lang('courses.form.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
