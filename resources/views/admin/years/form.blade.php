@extends('admin.layouts.app')

@section('title', $year ? __('years.form.edit_year') : __('years.form.new_year'))
@section('page', $year ? __('years.form.page_edit', ['name' => $year->name]) : __('years.form.page_create'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $year ? __('years.form.page_edit', ['name' => $year->name]) : __('years.form.page_create') }}</h1>
        <p>@lang('years.form.subtitle')</p>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="{{ $year ? route('admin.academic-years.update', $year) : route('admin.academic-years.store') }}">
            @csrf
            @if ($year) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">@lang('years.form.label_name')</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $year?->name) }}" placeholder="2026-2027" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('years.form.label_start_date')</label>
                    <input type="date" name="date_start" class="form-control" value="{{ old('date_start', $year?->date_start?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('years.form.label_end_date')</label>
                    <input type="date" name="date_stop" class="form-control" value="{{ old('date_stop', $year?->date_stop?->format('Y-m-d')) }}">
                </div>
            </div>
            <div class="form-check mt-3">
                <input type="checkbox" name="current" value="1" class="form-check-input" id="current" {{ old('current', $year?->current) ? 'checked' : '' }}>
                <label class="form-check-label" for="current">@lang('years.form.current')</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="active" value="1" class="form-check-input" id="active" {{ old('active', $year?->active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="active">@lang('years.form.active')</label>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> @lang('years.form.save')</button>
                <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary">@lang('years.form.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
