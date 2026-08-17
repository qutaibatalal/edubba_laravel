@extends('admin.layouts.app')

@section('title', __('fees.structures.title'))
@section('page', __('fees.structures.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('fees.structures.h1')</h1>
        <p class="text-secondary mb-0">@lang('fees.structures.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.fees.structures.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('fees.structures.new_structure')</a>
    </div>
</div>

@forelse ($structures as $s)
    <div class="card hoverable mb-3">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-3">
                <span class="avatar grad-{{ $loop->index % 6 + 1 }}">{{ mb_substr($s->name, 0, 1) }}</span>
                <div>
                    <div class="fw-bold">{{ $s->name }}</div>
                    <div class="text-secondary small">
                        {{ $s->batch?->name ?? __('fees.structures.all_batches') }} ·
                        {{ $s->program?->name ?? __('fees.structures.all_programs') }} ·
                        {{ $s->academicYear?->name ?? '—' }}
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.fees.structures.generate', $s) }}">
                @csrf
                <button class="btn btn-sm btn-success"><i class="bi bi-receipt me-1"></i> @lang('fees.structures.generate_invoices')</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-edb mb-0 align-middle">
                <thead><tr><th>@lang('fees.structures.table_item')</th><th>@lang('fees.structures.table_type')</th><th class="text-end">@lang('fees.structures.table_amount')</th></tr></thead>
                <tbody>
                    @forelse ($s->lines as $l)
                        @php $typeColors = ['one_time' => 'info', 'recurring' => 'purple']; @endphp
                        <tr>
                            <td>{{ $l->name }}</td>
                            <td><span class="badge badge-soft-{{ $typeColors[$l->type] ?? 'secondary' }}">{{ $l->type ?? '—' }}</span></td>
                            <td class="text-end fw-semibold">{{ number_format($l->amount) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3"><div class="empty-state"><i class="bi bi-inbox"></i><p>@lang('fees.structures.empty_lines')</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card"><div class="card-body"><div class="empty-state"><i class="bi bi-cash-stack"></i><p>@lang('fees.structures.empty_structures')</p></div></div></div>
@endforelse

<div class="card hoverable mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-receipt me-2"></i> @lang('fees.structures.invoices_title')</span>
        <a href="{{ route('admin.fees.invoices') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-left me-1"></i> @lang('fees.structures.view_all')</a>
    </div>
</div>
@endsection
