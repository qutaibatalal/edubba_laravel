@extends('admin.layouts.app')

@section('title', __('fees.invoices.title'))
@section('page', __('fees.invoices.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('fees.invoices.h1')</h1>
        <p class="text-secondary mb-0">@lang('fees.invoices.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.fees.structures') }}" class="btn btn-outline-primary"><i class="bi bi-cash-stack me-1"></i> @lang('fees.invoices.structures_link')</a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap align-items-center gap-2">
        <form method="GET" class="d-flex align-items-center gap-2">
            <span class="text-secondary small fw-semibold">@lang('fees.invoices.status_label')</span>
            <select name="state" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value="">@lang('fees.invoices.all_invoices')</option>
                @foreach (['draft','open','paid','cancel'] as $st)
                    <option value="{{ $st }}" {{ request('state') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('fees.invoices.table_number')</th><th>@lang('fees.invoices.table_student')</th><th>@lang('fees.invoices.table_date')</th><th>@lang('fees.invoices.table_total')</th><th>@lang('fees.invoices.table_paid')</th><th>@lang('fees.invoices.table_balance')</th><th>@lang('fees.invoices.table_status')</th><th class="text-end">@lang('fees.invoices.table_actions')</th></tr></thead>
            <tbody>
                @forelse ($invoices as $inv)
                    <tr>
                        <td><span class="badge badge-soft-primary">{{ $inv->number }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($inv->student?->full_name ?? $inv->parent?->name ?? '?', 0, 1) }}</span>
                                <span>{{ $inv->student?->full_name ?? $inv->parent?->name ?? '—' }}</span>
                            </div>
                        </td>
                        <td>{{ $inv->date?->format('Y-m-d') }}</td>
                        <td class="num">{{ number_format($inv->total) }}</td>
                        <td class="num text-success">{{ number_format($inv->paid) }}</td>
                        <td class="num {{ $inv->balance > 0 ? 'text-danger fw-bold' : '' }}">{{ number_format($inv->balance) }}</td>
                        <td><span class="badge badge-soft-{{ $inv->state === 'paid' ? 'success' : ($inv->state === 'open' ? 'warning' : 'secondary') }}">{{ $inv->state }}</span></td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                @if ($inv->state === 'open' && $inv->balance > 0)
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#payModal{{ $inv->id }}"><i class="bi bi-cash-coin me-1"></i> @lang('fees.invoices.pay_button')</button>
                                @endif
                                <a href="{{ route('admin.fees.invoices.pdf', $inv) }}" class="btn btn-sm btn-outline-primary" title="@lang('fees.invoices.download_pdf')"><i class="bi bi-file-earmark-pdf"></i></a>
                            </div>
                        </td>
                    </tr>

                    @if ($inv->state === 'open' && $inv->balance > 0)
                        <div class="modal fade" id="payModal{{ $inv->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('admin.fees.invoices.pay', $inv) }}" class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">@lang('fees.invoices.pay_modal_title', ['number' => $inv->number])</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">@lang('fees.invoices.student_label')</label>
                                            <input type="text" class="form-control" value="{{ $inv->student?->full_name ?? $inv->parent?->name }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">@lang('fees.invoices.remaining_amount')</label>
                                            <input type="text" class="form-control num" value="{{ number_format($inv->balance) }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">@lang('fees.invoices.paid_amount')</label>
                                            <input type="number" name="amount" class="form-control num" step="0.01" min="0.01" max="{{ $inv->balance }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">@lang('fees.invoices.payment_method')</label>
                                            <select name="method" class="form-select" required>
                                                @foreach (['cash' => 'method_cash', 'card' => 'method_card', 'transfer' => 'method_transfer', 'wallet' => 'method_wallet'] as $val => $label)
                                                    <option value="{{ $val }}">{{ __("fees.invoices.$label") }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">@lang('fees.invoices.date_label')</label>
                                            <input type="date" name="date" class="form-control" value="{{ today()->toDateString() }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">@lang('fees.invoices.cancel')</button>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> @lang('fees.invoices.confirm_payment')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @empty
                    <tr><td colspan="8"><div class="empty-state"><i class="bi bi-receipt"></i><p>@lang('fees.invoices.empty_title')</p><small>@lang('fees.invoices.empty_subtitle')</small></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($invoices->hasPages())<div class="card-footer">{{ $invoices->links() }}</div>@endif
</div>
@endsection
