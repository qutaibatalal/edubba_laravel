@extends('admin.layouts.app')

@section('title', __('tutoring.create.title'))
@section('page', __('tutoring.create.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('tutoring.create.heading')</h1>
        <p class="text-secondary mb-0">@lang('tutoring.create.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tutoring.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('tutoring.create.back_to_list')</a>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.tutoring.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">@lang('tutoring.create.form_name')</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('tutoring.create.form_subject')</label>
                    <select name="subject_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($subjects as $s)
                            <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('tutoring.create.form_tutor')</label>
                    <select name="tutor_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($tutors as $t)
                            <option value="{{ $t->id }}" {{ old('tutor_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('tutoring.create.form_max_students')</label>
                    <input type="number" name="max_students" class="form-control" value="{{ old('max_students') }}" min="1">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('tutoring.create.form_level')</label>
                    <input type="text" name="level" class="form-control" value="{{ old('level') }}">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> @lang('tutoring.create.create')</button>
                <a href="{{ route('admin.tutoring.index') }}" class="btn btn-outline-secondary">@lang('tutoring.create.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection