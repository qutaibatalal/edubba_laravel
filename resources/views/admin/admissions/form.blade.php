@extends('admin.layouts.app')

@section('title', __('admissions.form.title'))
@section('page', __('admissions.form.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('admissions.form.heading')</h1>
        <p>@lang('admissions.form.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.admissions.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('admissions.form.back_to_list')</a>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.admissions.store') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">@lang('admissions.form.first_name')</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('admissions.form.father_name')</label>
                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('admissions.form.family_name')</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('admissions.form.gender')</label>
                    <select name="gender" class="form-select">
                        <option value="male" {{ old('gender') === 'female' ? '' : 'selected' }}>@lang('admissions.form.gender_male')</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>@lang('admissions.form.gender_female')</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('admissions.form.birth_date')</label>
                    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('admissions.form.national_id')</label>
                    <input type="text" name="national_id" class="form-control" value="{{ old('national_id') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('admissions.form.phone')</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('admissions.form.email')</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label">@lang('admissions.form.address')</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('admissions.form.previous_school')</label>
                    <input type="text" name="previous_school" class="form-control" value="{{ old('previous_school') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('admissions.form.fees_amount')</label>
                    <input type="number" step="0.01" name="fees_amount" class="form-control" value="{{ old('fees_amount') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('admissions.form.academic_year')</label>
                    <select name="academic_year_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($years as $y)
                            <option value="{{ $y->id }}" {{ old('academic_year_id') == $y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('admissions.form.batch')</label>
                    <select name="batch_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($batches as $b)
                            <option value="{{ $b->id }}" {{ old('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('admissions.form.program')</label>
                    <select name="program_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($programs as $p)
                            <option value="{{ $p->id }}" {{ old('program_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> @lang('admissions.form.create_request')</button>
                <a href="{{ route('admin.admissions.index') }}" class="btn btn-outline-secondary">@lang('admissions.form.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
