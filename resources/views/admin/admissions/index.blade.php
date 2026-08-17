@extends('admin.layouts.app')

@section('title', __('admissions.index.title'))
@section('page', __('admissions.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('admissions.index.heading')</h1>
        <p>@lang('admissions.index.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.admissions.create') }}" class="btn btn-primary"><i class="bi bi-clipboard2-plus me-1"></i> @lang('admissions.index.add_new')</a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="state" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value="">@lang('admissions.index.all_states')</option>
                @foreach (['draft', 'submit', 'approve', 'reject', 'admitted'] as $st)
                    <option value="{{ $st }}" {{ request('state') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </form>
        <span class="badge badge-soft-primary">@lang('admissions.index.total_count', ['count' => $admissions->total()])</span>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('admissions.index.th_number')</th><th>@lang('admissions.index.th_name')</th><th>@lang('admissions.index.th_batch')</th><th>@lang('admissions.index.th_program')</th><th>@lang('admissions.index.th_state')</th><th class="text-start">@lang('admissions.index.th_actions')</th></tr></thead>
            <tbody>
                @forelse ($admissions as $a)
                    <tr>
                        <td><span class="badge badge-soft">{{ $a->application_no }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($a->name ?? '?', 0, 1) }}</span>
                                <span class="fw-semibold">{{ $a->full_name }}</span>
                            </div>
                        </td>
                        <td>{{ $a->batch?->name ?? '—' }}</td>
                        <td>{{ $a->program?->name ?? '—' }}</td>
                        <td>
                            @php $b = ['draft' => 'secondary', 'submit' => 'info', 'approve' => 'success', 'reject' => 'danger', 'admitted' => 'primary']; @endphp
                            <span class="badge badge-soft-{{ $b[$a->state] ?? 'secondary' }}">{{ $a->state }}</span>
                        </td>
                        <td class="text-start">
                            <div class="d-inline-flex gap-1 flex-wrap">
                                @if ($a->state === 'draft')
                                    <form method="POST" action="{{ route('admin.admissions.submit', $a) }}" class="d-inline">
                                        @csrf<button class="btn btn-sm btn-outline-info" title="@lang('admissions.index.submit')"><i class="bi bi-send"></i></button>
                                    </form>
                                @endif
                                @if (in_array($a->state, ['submit', 'draft']))
                                    <form method="POST" action="{{ route('admin.admissions.approve', $a) }}" class="d-inline">
                                        @csrf<button class="btn btn-sm btn-outline-success" title="@lang('admissions.index.approve')"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                @endif
                                @if (in_array($a->state, ['submit', 'approve']))
                                    <form method="POST" action="{{ route('admin.admissions.reject', $a) }}" class="d-inline" onsubmit="return confirm('{{ __('admissions.index.reject_confirm') }}')">
                                        @csrf<button class="btn btn-sm btn-outline-danger" title="@lang('admissions.index.reject')"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                @endif
                                @if ($a->state === 'approve')
                                    <form method="POST" action="{{ route('admin.admissions.admit', $a) }}" class="d-inline">
                                        @csrf<button class="btn btn-sm btn-outline-primary" title="@lang('admissions.index.admit')"><i class="bi bi-mortarboard"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="bi bi-clipboard2-x"></i><p>@lang('admissions.index.empty')</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($admissions->hasPages())
        <div class="card-footer">{{ $admissions->links() }}</div>
    @endif
</div>
@endsection
