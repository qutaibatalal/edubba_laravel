@extends('admin.layouts.app')

@section('title', __('transport.index.title'))
@section('page', __('transport.index.title'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('transport.index.title')</h1>
        <p>@lang('transport.index.subtitle')</p>
    </div>
    <div class="row mx-0">
        <div class="col-6">
            <a href="{{ route('admin.transport.create_vehicle') }}" class="btn btn-primary w-100 mb-2">
                <i class="bi bi-bus me-1"></i> @lang('transport.index.add_vehicle')
            </a>
        </div>
        <div class="col-6">
            <a href="javascript:void(0)" class="btn btn-outline-primary w-100 mb-2" onclick="showRouteForm()">
                <i class="bi bi-road me-1"></i> @lang('transport.index.add_route')
            </a>
        </div>
    </div>
</div>
</div>

<div class="card hoverable mt-3">
    <div class="card-body">
        <h4 class="mb-3 fw-bold">@lang('transport.index.vehicles_title')</h4>
        <div class="table-responsive">
            <table class="table table-edb mb-0 align-middle">
                <thead>
                    <tr>
                        <th>@lang('transport.vehicle.plate_number')</th>
                        <th>@lang('transport.vehicle.model')</th>
                        <th>@lang('transport.vehicle.capacity')</th>
                        <th>@lang('transport.vehicle.driver')</th>
                        <th>@lang('transport.index.phone')</th>
                        <th class="text-end">@lang('transport.status')</th>
                        <th class="text-end">@lang('transport.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vehicles as $v)
                    <tr>
                        <td>{{ $v->plate_number }}</td>
                        <td>{{ $v->model ?? '—' }}</td>
                        <td>{{ $v->capacity }}</td>
                        <td>{{ $v->driver_name ?? '—' }}</td>
                        <td>{{ $v->driver_phone ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $v->active ? 'success' : 'danger' }}-soft">
                                {{ $v->active ? __('transport.active') : __('transport.inactive') }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="javascript:void(0)" class="btn btn-link btn-sm"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card hoverable mt-3">
    <div class="card-body">
        <h4 class="mb-3 fw-bold">@lang('transport.index.routes_title')</h4>
        <div class="table-responsive">
            <table class="table table-edb mb-0 align-middle">
                <thead>
                    <tr>
                        <th>@lang('transport.route.name')</th>
                        <th>@lang('transport.route.vehicle')</th>
                        <th>@lang('transport.route.description')</th>
                        <th class="text-end">@lang('transport.status')</th>
                        <th class="text-end">@lang('transport.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($routes as $r)
                    <tr>
                        <td>{{ $r->name }}</td>
                        <td>{{ $r->vehicle?->plate_number ?? '—' }}</td>
                        <td>{{ $r->description ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $r->active ? 'success' : 'danger' }}-soft">
                                {{ $r->active ? __('transport.active') : __('transport.inactive') }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="javascript:void(0)" class="btn btn-link btn-sm"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showRouteForm() {
    // Simple alert for now - can be expanded with a modal
    alert('{{ __('transport.index.route_soon') }}');
}
</script>
@endsection