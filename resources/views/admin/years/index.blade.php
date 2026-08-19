@extends('admin.layouts.app')

@section('title', __('years.index.title'))
@section('page', __('years.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('years.index.title')</h1>
        <p>@lang('years.index.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.academic-years.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('years.index.add_year')</a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-semibold">@lang('years.index.header')</span>
        <a href="{{ route('admin.academic-years.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('years.index.add_short')</a>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('years.index.col_name')</th><th>@lang('years.index.col_start')</th><th>@lang('years.index.col_end')</th><th>@lang('years.index.col_batches')</th><th>@lang('years.index.col_admissions')</th><th>@lang('years.index.col_current')</th><th></th></tr></thead>
            <tbody>
                @forelse ($years as $y)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($y->name, 0, 1) }}</span>
                                <span class="fw-semibold">{{ $y->name }}</span>
                            </div>
                        </td>
                        <td>{{ $y->date_start?->format('Y-m-d') }}</td>
                        <td>{{ $y->date_stop?->format('Y-m-d') }}</td>
                        <td><span class="badge badge-soft">{{ $y->batches_count }}</span></td>
                        <td><span class="badge badge-soft">{{ $y->admissions_count }}</span></td>
                        <td>
                            @if ($y->current)<span class="badge badge-soft-success">@lang('years.index.yes')</span>@else<span class="text-secondary">—</span>@endif
                        </td>
                        <td class="text-start">
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.academic-years.show', $y) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.academic-years.edit', $y) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.academic-years.destroy', $y) }}" class="d-inline" onsubmit="return confirm('{{ __('years.index.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-calendar-range"></i><p>@lang('years.index.empty')</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
