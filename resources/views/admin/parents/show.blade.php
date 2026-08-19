@extends('admin.layouts.app')

@section('title', $parent->name)
@section('page', __('parents.show.page', ['name' => $parent->name]))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $parent->name }}</h1>
        <p class="text-secondary mb-0">@lang('parents.show.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.parents.edit', $parent) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> @lang('parents.show.edit')</a>
        <a href="{{ route('admin.parents.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('parents.show.back_to_list')</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card hoverable">
            <div class="card-body text-center">
                <span class="avatar avatar-lg grad-1 mx-auto mb-3">{{ mb_substr($parent->name, 0, 1) }}</span>
                <h5 class="mb-1 fw-bold">{{ $parent->name }}</h5>
                <div class="text-secondary small mb-3">{{ $parent->national_id ?? '—' }}</div>
                <span class="badge badge-soft-{{ $parent->active ? 'success' : 'secondary' }}">{{ $parent->active ? __('parents.show.active') : __('parents.show.inactive') }}</span>
                <hr>
                <div class="text-start small">
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('parents.show.label_phone')</span><b class="num">{{ $parent->phone ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('parents.show.label_mobile')</span><b class="num">{{ $parent->mobile ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('parents.show.label_email')</span><b>{{ $parent->email ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('parents.show.label_occupation')</span><b>{{ $parent->occupation ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('parents.show.label_relation')</span><b>{{ $parent->relation ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('parents.show.label_address')</span><b>{{ $parent->address ?? '—' }}</b></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card hoverable">
            <div class="card-header fw-bold"><i class="bi bi-people me-2 text-primary"></i> @lang('parents.show.children_title', ['count' => $parent->students->count()])</div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th>@lang('parents.show.col_name')</th><th>@lang('parents.show.col_code')</th><th>@lang('parents.show.col_batch')</th><th>@lang('parents.show.col_status')</th></tr></thead>
                    <tbody>
                        @forelse ($parent->students as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($student->name, 0, 1) }}</span>
                                        <span>{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td class="num">{{ $student->student_code ?? '—' }}</td>
                                <td>{{ $student->batch?->name ?? '—' }}</td>
                                <td><span class="badge badge-soft-{{ $student->state === 'admitted' ? 'success' : 'secondary' }}">{{ $student->state ?? '—' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="empty-state"><i class="bi bi-person-x"></i><p>@lang('parents.show.no_children')</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection