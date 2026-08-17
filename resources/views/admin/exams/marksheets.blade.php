@extends('admin.layouts.app')

@section('title', __('exams.marksheets.title', ['name' => $exam->name]))
@section('page', __('exams.marksheets.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('exams.marksheets.heading')</h1>
        <p class="text-secondary mb-0">{{ $exam->name }} · {{ $exam->batch?->name ?? __('exams.index.all_batches') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.exams.results', $exam) }}" class="btn btn-outline-primary"><i class="bi bi-bar-chart me-1"></i> @lang('exams.show.results')</a>
        <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-light border"><i class="bi bi-arrow-right me-1"></i> @lang('exams.show.back')</a>
    </div>
</div>

@php
    $draftCount = $marksheets->where('state', 'draft')->count();
    $doneCount = $marksheets->where('state', 'done')->count();
@endphp

<div class="card hoverable mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <span class="badge badge-soft-primary">@lang('exams.marksheets.marksheets_count', ['count' => $marksheets->count()])</span>
            </div>
            <div class="col-auto">
                <span class="badge badge-soft-warning">@lang('exams.marksheets.draft_count', ['count' => $draftCount])</span>
            </div>
            <div class="col-auto">
                <span class="badge badge-soft-success">@lang('exams.marksheets.approved_count', ['count' => $doneCount])</span>
            </div>
            <div class="col-auto">
                <span class="badge badge-soft-info">@lang('exams.marksheets.eligible_count', ['count' => $eligible->count()])</span>
            </div>
            <div class="ms-auto d-flex gap-2">
                <form method="POST" action="{{ route('admin.exams.marksheets.generate', $exam) }}">
                    @csrf
                    <button class="btn btn-primary" {{ $eligible->isEmpty() ? 'disabled' : '' }}><i class="bi bi-magic me-1"></i> @lang('exams.marksheets.generate')</button>
                </form>
                <form method="POST" action="{{ route('admin.exams.marksheets.finalize-all', $exam) }}" onsubmit="return confirm('{{ __('exams.marksheets.confirm_finalize_all') }}')">
                    @csrf
                    <button class="btn btn-success" {{ $draftCount === 0 ? 'disabled' : '' }}><i class="bi bi-check2-all me-1"></i> @lang('exams.marksheets.finalize_all')</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-card-checklist me-2 text-primary"></i> @lang('exams.marksheets.list_title', ['batch' => $exam->batch?->name ?? __('exams.show.exam')])</span>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('exams.marksheets.col_student')</th><th>@lang('exams.marksheets.col_number')</th><th class="num">@lang('exams.marksheets.col_marks')</th><th class="num">@lang('exams.marksheets.col_percentage')</th><th>@lang('exams.marksheets.col_grade')</th><th>@lang('exams.marksheets.col_result')</th><th>@lang('exams.marksheets.col_rank')</th><th>@lang('exams.marksheets.col_status')</th><th></th></tr></thead>
            <tbody>
                @forelse ($marksheets as $ms)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($ms->student?->name ?? '—', 0, 1) }}</span>
                                <span class="fw-semibold">{{ $ms->student?->full_name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="num">{{ $ms->student?->roll_no ?? $ms->student?->student_code ?? '—' }}</td>
                        <td class="num">{{ $ms->obtained_marks }} / {{ $ms->total_marks }}</td>
                        <td class="num">{{ $ms->percentage }}%</td>
                        <td><span class="badge badge-soft-primary">{{ $ms->grade ?: '—' }}</span></td>
                        <td>
                            <span class="badge badge-soft-{{ $ms->result === 'pass' ? 'success' : 'danger' }}">{{ $ms->result === 'pass' ? __('exams.marksheets.pass') : __('exams.marksheets.fail') }}</span>
                        </td>
                        <td class="num">{{ $ms->rank ?: '—' }}</td>
                        <td>
                            <span class="badge badge-soft-{{ $ms->is_finalized ? 'success' : 'warning' }}">{{ $ms->is_finalized ? __('exams.marksheets.approved') : __('exams.marksheets.draft') }}</span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.exams.marksheet', [$exam, $ms]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> @lang('exams.marksheets.marks')</a>
                                @if (! $ms->is_finalized)
                                    <form method="POST" action="{{ route('admin.exams.marksheet.finalize', [$exam, $ms]) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success"><i class="bi bi-check2"></i></button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.exams.result.card', [$exam, $ms->student_id]) }}" class="btn btn-sm btn-light border" title="@lang('exams.marksheets.result_card')"><i class="bi bi-printer"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9"><div class="empty-state"><i class="bi bi-card-checklist"></i><p>@lang('exams.marksheets.empty_title')</p><small>@lang('exams.marksheets.empty_hint')</small></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
