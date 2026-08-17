@extends('admin.layouts.app')

@section('title', $program ? __('programs.form.edit_program') : __('programs.form.new_program'))
@section('page', $program ? __('programs.form.page_edit') : __('programs.form.page_create'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $program ? __('programs.form.h1_edit', ['name' => $program->name]) : __('programs.form.h1_create') }}</h1>
        <p>@lang('programs.form.subtitle')</p>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="{{ $program ? route('admin.programs.update', $program) : route('admin.programs.store') }}">
            @csrf
            @if ($program) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">@lang('programs.form.label_name')</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $program?->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('programs.form.label_code')</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $program?->code) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('programs.form.label_department')</label>
                    <select name="department_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($departments as $d)
                            <option value="{{ $d->id }}" {{ old('department_id', $program?->department_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('programs.form.label_duration')</label>
                    <input type="number" name="duration_years" class="form-control" value="{{ old('duration_years', $program?->duration_years) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">@lang('programs.form.label_description')</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $program?->description) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> @lang('programs.form.save')</button>
                <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary">@lang('programs.form.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
