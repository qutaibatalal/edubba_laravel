@extends('admin.layouts.app')

@section('title', __('parents.index.title'))
@section('page', __('parents.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('parents.index.heading')</h1>
        <p class="text-secondary mb-0">@lang('parents.index.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.parents.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('parents.index.add_new')</a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="@lang('parents.index.search_placeholder')">
            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search me-1"></i> @lang('parents.index.search')</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('parents.index.th_name')</th><th>@lang('parents.index.th_national_id')</th><th>@lang('parents.index.th_phone')</th><th>@lang('parents.index.th_children_count')</th><th>@lang('parents.index.th_status')</th><th></th></tr></thead>
            <tbody>
                @forelse ($parents as $p)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($p->name, 0, 1) }}</span>
                                <span>{{ $p->name }}</span>
                            </div>
                        </td>
                        <td>{{ $p->national_id ?? '—' }}</td>
                        <td>{{ $p->phone ?? $p->mobile ?? '—' }}</td>
                        <td>{{ $p->students_count }}</td>
                        <td>
                            @if ($p->students_count > 0)
                                <span class="text-secondary small">
                                    @foreach ($p->students as $s) {{ $s->name }}@if (!$loop->last), @endif @endforeach
                                </span>
                            @else
                                <span class="text-secondary small">—</span>
                            @endif
                        </td>
                        <td class="text-start">
                            <a href="{{ route('admin.parents.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.parents.destroy', $p) }}" class="d-inline" onsubmit="return confirm('{{ __('parents.index.delete_confirm') }}')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="bi bi-people"></i><p>@lang('parents.index.empty')</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($parents->hasPages())<div class="card-footer">{{ $parents->links() }}</div>@endif
</div>
@endsection
