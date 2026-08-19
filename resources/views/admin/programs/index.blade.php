@extends('admin.layouts.app')

@section('title', __('programs.index.title'))
@section('page', __('programs.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('programs.index.title')</h1>
        <p>@lang('programs.index.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.programs.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('programs.index.add_program')</a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-semibold">@lang('programs.index.header')</span>
        <a href="{{ route('admin.programs.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('programs.index.add_short')</a>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('programs.index.col_name')</th><th>@lang('programs.index.col_code')</th><th>@lang('programs.index.col_department')</th><th>@lang('programs.index.col_duration')</th><th>@lang('programs.index.col_batches')</th><th></th></tr></thead>
            <tbody>
                @forelse ($programs as $p)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($p->name, 0, 1) }}</span>
                                <span class="fw-semibold">{{ $p->name }}</span>
                            </div>
                        </td>
                        <td>{{ $p->code ?? '—' }}</td>
                        <td>{{ $p->department?->name ?? '—' }}</td>
                        <td>{{ $p->duration_years ?? '—' }}</td>
                        <td><span class="badge badge-soft">{{ $p->batches_count }}</span></td>
                        <td class="text-start">
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.programs.show', $p) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.programs.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.programs.destroy', $p) }}" class="d-inline" onsubmit="return confirm('{{ __('programs.index.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="bi bi-award"></i><p>@lang('programs.index.empty')</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
