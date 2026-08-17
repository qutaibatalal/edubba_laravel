@extends('admin.layouts.app')

@section('title', __('batches.index.title'))
@section('page', __('batches.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('batches.index.title')</h1>
        <p>@lang('batches.index.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.batches.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('batches.index.add_batch')</a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-semibold">@lang('batches.index.header')</span>
        <a href="{{ route('admin.batches.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('batches.index.add_short')</a>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('batches.index.col_name')</th><th>@lang('batches.index.col_program')</th><th>@lang('batches.index.col_year')</th><th>@lang('batches.index.col_class_teacher')</th><th>@lang('batches.index.col_capacity')</th><th>@lang('batches.index.col_students')</th><th></th></tr></thead>
            <tbody>
                @forelse ($batches as $b)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($b->name, 0, 1) }}</span>
                                <span class="fw-semibold">{{ $b->name }}</span>
                            </div>
                        </td>
                        <td>{{ $b->program?->name ?? '—' }}</td>
                        <td>{{ $b->academicYear?->name ?? '—' }}</td>
                        <td>{{ $b->classTeacher?->full_name ?? '—' }}</td>
                        <td>{{ $b->capacity ?? '—' }}</td>
                        <td><span class="badge badge-soft">{{ $b->students_count }}</span></td>
                        <td class="text-start">
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.batches.edit', $b) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.batches.destroy', $b) }}" class="d-inline" onsubmit="return confirm('{{ __('batches.index.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-diagram-3"></i><p>@lang('batches.index.empty')</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
