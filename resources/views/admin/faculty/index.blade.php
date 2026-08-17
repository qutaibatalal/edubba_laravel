@extends('admin.layouts.app')

@section('title', __('faculty.index.title'))
@section('page', __('faculty.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('faculty.index.title')</h1>
        <p>@lang('faculty.index.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.faculty.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('faculty.index.add_member')</a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="@lang('faculty.index.search_placeholder')">
            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search me-1"></i> @lang('faculty.index.search')</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('faculty.index.col_code')</th><th>@lang('faculty.index.col_name')</th><th>@lang('faculty.index.col_department')</th><th>@lang('faculty.index.col_specialization')</th><th>@lang('faculty.index.col_state')</th><th></th></tr></thead>
            <tbody>
                @forelse ($faculty as $f)
                    <tr>
                        <td><span class="badge badge-soft-primary">{{ $f->faculty_code }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($f->full_name, 0, 1) }}</span>
                                <a href="{{ route('admin.faculty.show', $f) }}" class="text-decoration-none fw-semibold">{{ $f->full_name }}</a>
                            </div>
                        </td>
                        <td>{{ $f->department?->name ?? '—' }}</td>
                        <td>{{ $f->specialization ?? '—' }}</td>
                        <td><span class="badge badge-soft-{{ $f->state === 'joined' ? 'success' : ($f->state === 'left' ? 'danger' : 'secondary') }}">{{ $f->state }}</span></td>
                        <td class="text-start">
                            <a href="{{ route('admin.faculty.card', $f) }}" class="btn btn-sm btn-outline-secondary" title="@lang('faculty.index.card')"><i class="bi bi-person-vcard"></i></a>
                            <a href="{{ route('admin.faculty.show', $f) }}" class="btn btn-sm btn-outline-primary" title="@lang('faculty.index.view')"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.faculty.edit', $f) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.faculty.destroy', $f) }}" class="d-inline" onsubmit="return confirm('{{ __('faculty.index.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="bi bi-person-video3"></i><p>@lang('faculty.index.empty')</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($faculty->hasPages())<div class="card-footer">{{ $faculty->links() }}</div>@endif
</div>
@endsection
