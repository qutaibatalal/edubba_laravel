@extends('admin.layouts.app')

@section('title', $member ? __('faculty.form.edit_member') : __('faculty.form.new_member'))
@section('page', $member ? __('faculty.form.page_edit', ['name' => $member->full_name]) : __('faculty.form.page_create'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $member ? __('faculty.form.edit_member') : __('faculty.form.new_member') }}</h1>
        <p>@lang('faculty.form.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.faculty.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('faculty.form.back')</a>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="{{ $member ? route('admin.faculty.update', $member) : route('admin.faculty.store') }}">
            @csrf
            @if ($member) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">@lang('faculty.form.label_first_name')</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $member?->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('faculty.form.label_middle_name')</label>
                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $member?->middle_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('faculty.form.label_last_name')</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $member?->last_name) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('faculty.form.label_code')</label>
                    <input type="text" name="faculty_code" class="form-control" placeholder="@lang('faculty.form.code_placeholder')" value="{{ old('faculty_code', $member?->faculty_code) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('faculty.form.label_gender')</label>
                    <select name="gender" class="form-select">
                        <option value="male" {{ old('gender', $member?->gender) === 'male' ? 'selected' : '' }}>@lang('faculty.form.male')</option>
                        <option value="female" {{ old('gender', $member?->gender) === 'female' ? 'selected' : '' }}>@lang('faculty.form.female')</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('faculty.form.label_department')</label>
                    <select name="department_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($departments as $d)
                            <option value="{{ $d->id }}" {{ old('department_id', $member?->department_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('faculty.form.label_birth_date')</label>
                    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $member?->birth_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('faculty.form.label_qualification')</label>
                    <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $member?->qualification) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('faculty.form.label_specialization')</label>
                    <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $member?->specialization) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('faculty.form.label_join_date')</label>
                    <input type="date" name="join_date" class="form-control" value="{{ old('join_date', $member?->join_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('faculty.form.label_phone')</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $member?->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('faculty.form.label_mobile')</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $member?->mobile) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('faculty.form.label_email')</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $member?->email) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('faculty.form.label_state')</label>
                    <select name="state" class="form-select">
                        @foreach (['draft','joined','left'] as $st)
                            <option value="{{ $st }}" {{ old('state', $member?->state) === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> @lang('faculty.form.save')</button>
                <a href="{{ route('admin.faculty.index') }}" class="btn btn-outline-secondary">@lang('faculty.form.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
