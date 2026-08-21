@extends('admin.layouts.app')

@section('title', __('hostel.create.title'))
@section('page', __('hostel.create.page'))

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card hoverable p-4">
            <h2 class="mb-3 fw-bold">@lang('hostel.create.title')</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.hostel.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">@lang('hostel.name')</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('hostel.address')</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('hostel.warden_name')</label>
                        <input type="text" name="warden_name" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('hostel.active')</label>
                        <input type="checkbox" name="active" class="form-check-input" value="1" checked>
                    </div>
                    <div class="col-12">
                        <label class="form-label">@lang('hostel.create.rooms_count')</label>
                        <input type="number" name="rooms" class="form-control" min="0" placeholder="@lang('hostel.create.rooms_placeholder')">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">@lang('hostel.create.save')</button>
                    <a href="{{ route('admin.hostels.index') }}" class="btn btn-outline-secondary">@lang('hostel.create.cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection