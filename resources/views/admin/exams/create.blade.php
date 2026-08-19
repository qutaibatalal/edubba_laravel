@extends('admin.layouts.app')

@section('title', __('exams.create.title'))
@section('page', __('exams.create.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('exams.create.heading')</h1>
        <p class="text-secondary mb-0">@lang('exams.create.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('exams.create.back_to_list')</a>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.exams.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">@lang('exams.create.form_name')</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="@lang('exams.create.form_name_ph')">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('exams.create.form_type')</label>
                    <select name="exam_type_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($types as $t)
                            <option value="{{ $t->id }}" {{ old('exam_type_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('exams.create.form_batch')</label>
                    <select name="batch_id" class="form-select">
                        <option value="">@lang('exams.create.all_batches')</option>
                        @foreach ($batches as $b)
                            <option value="{{ $b->id }}" {{ old('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('exams.create.form_year')</label>
                    <select name="academic_year_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($years as $y)
                            <option value="{{ $y->id }}" {{ old('academic_year_id') == $y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('exams.create.form_term')</label>
                    <select name="term_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($terms as $term)
                            <option value="{{ $term->id }}" {{ old('term_id') == $term->id ? 'selected' : '' }}>{{ $term->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('exams.create.form_start')</label>
                    <input type="date" name="date_start" class="form-control" value="{{ old('date_start') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('exams.create.form_end')</label>
                    <input type="date" name="date_end" class="form-control" value="{{ old('date_end') }}">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> @lang('exams.create.create')</button>
                <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary">@lang('exams.create.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection