@extends('admin.layouts.app')

@section('title', __('hostel.index.title'))
@section('page', __('hostel.index.title'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('hostel.index.title')</h1>
        <p>@lang('hostel.index.subtitle')</p>
    </div>
    <a href="{{ route('admin.hostel.create') }}" class="btn btn-primary">
        <i class="bi bi-plus me-1"></i> @lang('hostel.create.title')
    </a>
</div>
</div>

@if (session('success'))
    <div class="alert alert-success mt-3">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
    </div>
@endif

<div class="card hoverable mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-edb mb-0 align-middle">
                <thead>
                    <tr>
                        <th>@lang('hostel.name')</th>
                        <th>@lang('hostel.warden')</th>
                        <th>@lang('hostel.index.total')</th>
                        <th>@lang('hostel.index.occupied')</th>
                        <th>@lang('hostel.status')</th>
                        <th class="text-end">@lang('hostel.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hostels as $h)
                    <tr>
                        <td>{{ $h->name }}</td>
                        <td>{{ $h->warden_name ?? '—' }}</td>
                        <td>{{ $h->rooms->count() }}</td>
                        <td>
                            @foreach ($h->rooms as $r)
                                <span class="badge bg-{{ $r->state === HostelRoom::STATE_AVAILABLE ? 'success' : ($r->state === HostelRoom::STATE_FULL ? 'danger' : 'warning') }}-soft">
                                    {{ $r->state }}
                                </span>
                            @endforeach
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection