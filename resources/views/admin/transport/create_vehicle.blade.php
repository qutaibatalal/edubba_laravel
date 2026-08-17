@extends('admin.layouts.app')

@section('title', __('transport.create.title'))
@section('page', __('transport.create.title'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('transport.create.title')</h1>
        <p>@lang('transport.create.subtitle')</p>
    </div>
    <div>
        <a href="{{ route('admin.transport.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> @lang('transport.create.back')
        </a>
    </div>
</div>

<div class="card hoverable mt-3">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.transport.store_vehicle') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">@lang('transport.vehicle.plate_number') <span class="text-danger">*</span></label>
                    <input type="text" name="plate_number" class="form-control" value="{{ old('plate_number') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('transport.vehicle.model')</label>
                    <input type="text" name="model" class="form-control" value="{{ old('model') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('transport.vehicle.capacity') <span class="text-danger">*</span></label>
                    <input type="number" name="capacity" class="form-control" min="1" value="{{ old('capacity', 1) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('transport.vehicle.driver_name')</label>
                    <input type="text" name="driver_name" class="form-control" value="{{ old('driver_name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('transport.vehicle.driver_phone')</label>
                    <input type="text" name="driver_phone" class="form-control" value="{{ old('driver_phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('transport.vehicle.stops')</label>
                    <input type="number" name="stops" class="form-control" min="0" value="{{ old('stops', 0) }}">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" name="active" id="active" value="1" checked>
                        <label class="form-check-label" for="active">@lang('transport.vehicle.active')</label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> @lang('transport.vehicle.save')
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
