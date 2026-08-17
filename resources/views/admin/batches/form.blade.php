@extends('admin.layouts.app')

@section('title', $batch ? __('batches.form.edit_batch') : __('batches.form.new_batch'))
@section('page', $batch ? __('batches.form.page_edit') : __('batches.form.page_create'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $batch ? __('batches.form.h1_edit', ['name' => $batch->name]) : __('batches.form.h1_create') }}</h1>
        <p>@lang('batches.form.subtitle')</p>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="{{ $batch ? route('admin.batches.update', $batch) : route('admin.batches.store') }}">
            @csrf
            @if ($batch) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">@lang('batches.form.label_name')</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $batch?->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('batches.form.label_capacity')</label>
                    <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $batch?->capacity) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('batches.form.label_program')</label>
                    <select name="program_id" class="form-select @error('program_id') is-invalid @enderror" required>
                        <option value="">—</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}" {{ old('program_id', $batch?->program_id) == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                        @endforeach
                    </select>
                    @error('program_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('batches.form.label_academic_year')</label>
                    <select name="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror" required>
                        <option value="">—</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $batch?->academic_year_id) == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                        @endforeach
                    </select>
                    @error('academic_year_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('batches.form.label_class_teacher')</label>
                    <select name="class_teacher_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($faculty as $member)
                            <option value="{{ $member->id }}" {{ old('class_teacher_id', $batch?->class_teacher_id) == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="activeChk" {{ old('active', $batch?->active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activeChk">@lang('batches.form.active')</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> @lang('batches.form.save')</button>
                <a href="{{ route('admin.batches.index') }}" class="btn btn-outline-secondary">@lang('batches.form.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
