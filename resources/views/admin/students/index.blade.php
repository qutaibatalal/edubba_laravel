@extends('admin.layouts.app')

@section('title', __('students.index.title'))
@section('page', __('students.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('students.index.heading')</h1>
        <p>@lang('students.index.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i> @lang('students.index.add_new')</a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="@lang('students.index.search_placeholder')" style="min-width:220px">
            <select name="batch_id" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value="">@lang('students.index.all_batches')</option>
                @foreach ($batches as $b)
                    <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-search me-1"></i> @lang('students.index.search')</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('students.index.th_code')</th><th>@lang('students.index.th_name')</th><th>@lang('students.index.th_batch')</th><th>@lang('students.index.th_year')</th><th>@lang('students.index.th_parent')</th><th>@lang('students.index.th_state')</th><th class="text-start">@lang('students.index.th_actions')</th></tr></thead>
            <tbody>
                @forelse ($students as $s)
                    <tr>
                        <td><span class="badge badge-soft">{{ $s->student_code }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($s->name ?? '?', 0, 1) }}</span>
                                <span class="fw-semibold">{{ $s->full_name }}</span>
                            </div>
                        </td>
                        <td>{{ $s->batch?->name ?? '—' }}</td>
                        <td>{{ $s->academicYear?->name ?? '—' }}</td>
                        <td>{{ $s->parent?->name ?? '—' }}</td>
                        <td>
                            @php $b = ['draft' => 'secondary', 'admitted' => 'success', 'graduated' => 'primary', 'alumni' => 'info']; @endphp
                            <span class="badge badge-soft-{{ $b[$s->state] ?? 'secondary' }}">{{ $s->state }}</span>
                        </td>
                        <td class="text-start">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.students.show', $s) }}" class="btn btn-sm btn-outline-secondary" title="@lang('students.index.view')"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.students.edit', $s) }}" class="btn btn-sm btn-outline-primary" title="@lang('students.index.edit')"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.students.destroy', $s) }}" class="d-inline" onsubmit="return confirm('{{ __('students.index.delete_confirm') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="@lang('students.index.delete')"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-people"></i><p>@lang('students.index.empty')</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($students->hasPages())
        <div class="card-footer">{{ $students->links() }}</div>
    @endif
</div>
@endsection
