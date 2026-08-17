@extends('admin.layouts.app')

@section('title', $parent ? __('parents.form.title_edit') : __('parents.form.title_new'))
@section('page', $parent ? __('parents.form.page_edit') : __('parents.form.page_new'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $parent ? __('parents.form.title_edit') : __('parents.form.title_new') }}</h1>
        <p class="text-secondary mb-0">@lang('parents.form.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.parents.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('parents.form.back_to_list')</a>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="{{ $parent ? route('admin.parents.update', $parent) : route('admin.parents.store') }}">
            @csrf
            @if ($parent) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">@lang('parents.form.full_name')</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $parent?->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('parents.form.national_id')</label>
                    <input type="text" name="national_id" class="form-control" value="{{ old('national_id', $parent?->national_id) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('parents.form.phone')</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $parent?->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('parents.form.mobile')</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $parent?->mobile) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('parents.form.email')</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $parent?->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('parents.form.occupation')</label>
                    <input type="text" name="occupation" class="form-control" value="{{ old('occupation', $parent?->occupation) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('parents.form.relation')</label>
                    <select name="relation" class="form-select">
                        <option value="">—</option>
                        @foreach (['father','mother','guardian','other'] as $r)
                            <option value="{{ $r }}" {{ old('relation', $parent?->relation) === $r ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('parents.form.address')</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $parent?->address) }}">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> @lang('parents.form.save')</button>
                <a href="{{ route('admin.parents.index') }}" class="btn btn-outline-secondary">@lang('parents.form.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
