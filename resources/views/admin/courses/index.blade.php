@extends('admin.layouts.app')

@section('title', __('courses.index.title'))
@section('page', __('courses.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('courses.index.title')</h1>
        <p>@lang('courses.index.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('courses.index.add_course')</a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <select name="batch_id" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value="">@lang('courses.index.all_batches')</option>
                @foreach ($batches as $b)
                    <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('courses.index.add_short')</a>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('courses.index.col_name')</th><th>@lang('courses.index.col_code')</th><th>@lang('courses.index.col_subject')</th><th>@lang('courses.index.col_batch')</th><th>@lang('courses.index.col_year')</th><th>@lang('courses.index.col_teacher')</th><th></th></tr></thead>
            <tbody>
                @forelse ($courses as $c)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($c->name, 0, 1) }}</span>
                                <span class="fw-semibold">{{ $c->name }}</span>
                            </div>
                        </td>
                        <td>{{ $c->code ?? '—' }}</td>
                        <td>{{ $c->subject?->name ?? '—' }}</td>
                        <td>{{ $c->batch?->name ?? '—' }}</td>
                        <td>{{ $c->academicYear?->name ?? '—' }}</td>
                        <td>{{ $c->faculty?->full_name ?? '—' }}</td>
                        <td class="text-start">
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.courses.show', $c) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.courses.edit', $c) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.courses.destroy', $c) }}" class="d-inline" onsubmit="return confirm('{{ __('courses.index.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-book"></i><p>@lang('courses.index.empty')</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($courses->hasPages())
        <div class="card-footer">{{ $courses->links() }}</div>
    @endif
</div>
@endsection
