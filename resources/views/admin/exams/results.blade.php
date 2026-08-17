@extends('admin.layouts.app')

@section('title', __('exams.results.title', ['name' => $exam->name]))
@section('page', __('exams.results.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('exams.results.heading')</h1>
        <p class="text-secondary mb-0">{{ $exam->name }} · {{ $exam->batch?->name ?? __('exams.index.all_batches') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.exams.marksheets', $exam) }}" class="btn btn-outline-primary"><i class="bi bi-card-checklist me-1"></i> @lang('exams.show.marksheets')</a>
        <a href="{{ route('admin.exams.results.pdf', $exam) }}" class="btn btn-outline-primary"><i class="bi bi-printer me-1"></i> @lang('exams.results.result_cards_pdf')</a>
        <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-light border"><i class="bi bi-arrow-right me-1"></i> @lang('exams.show.back')</a>
    </div>
</div>

@php
    $published = $exam->results->whereNotNull('published_at')->count();
@endphp

<div class="card hoverable mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-auto"><span class="badge badge-soft-primary">@lang('exams.results.results_count', ['count' => $exam->results->count()])</span></div>
            <div class="col-auto"><span class="badge badge-soft-success">@lang('exams.results.published_count', ['count' => $published])</span></div>
            <div class="col-auto"><span class="badge badge-soft-warning">@lang('exams.results.unpublished_count', ['count' => $exam->results->count() - $published])</span></div>
            <div class="ms-auto d-flex gap-2">
                <form method="POST" action="{{ route('admin.exams.results.share', $exam) }}">
                    @csrf
                    <button class="btn btn-success" {{ $published === 0 ? 'disabled' : '' }}><i class="bi bi-whatsapp me-1"></i> @lang('exams.results.share')</button>
                </form>
                <form method="POST" action="{{ route('admin.exams.results.publish', $exam) }}">
                    @csrf
                    <button class="btn btn-primary" {{ $exam->results->count() === 0 || $published === $exam->results->count() ? 'disabled' : '' }}><i class="bi bi-megaphone me-1"></i> @lang('exams.results.publish')</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-bar-chart me-2 text-primary"></i> @lang('exams.results.table_title')</span>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('exams.results.col_rank')</th><th>@lang('exams.results.col_student')</th><th class="num">@lang('exams.results.col_total')</th><th class="num">@lang('exams.results.col_percentage')</th><th>@lang('exams.results.col_grade')</th><th>@lang('exams.results.col_result')</th><th>@lang('exams.results.col_publish')</th><th></th></tr></thead>
            <tbody>
                @forelse ($exam->results->sortBy('rank') as $result)
                    <tr>
                        <td class="num">
                            @if ($result->rank === 1)
                                <span class="badge badge-soft-warning"><i class="bi bi-trophy-fill"></i></span>
                            @else
                                {{ $result->rank }}
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($result->student?->name ?? '—', 0, 1) }}</span>
                                <span class="fw-semibold">{{ $result->student?->full_name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="num">{{ $result->total }}</td>
                        <td class="num">{{ $result->average }}%</td>
                        <td><span class="badge badge-soft-primary">{{ $result->grade ?: '—' }}</span></td>
                        <td>
                            <span class="badge badge-soft-{{ $result->result === 'pass' ? 'success' : 'danger' }}">{{ $result->result === 'pass' ? __('exams.marksheets.pass') : __('exams.marksheets.fail') }}</span>
                        </td>
                        <td>
                            <span class="badge badge-soft-{{ $result->published_at ? 'success' : 'secondary' }}">
                                {{ $result->published_at ? $result->published_at->format('Y-m-d') : __('exams.results.unpublished') }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.exams.result.card', [$exam, $result->student_id]) }}" class="btn btn-sm btn-outline-primary" title="@lang('exams.marksheets.result_card')"><i class="bi bi-printer"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8"><div class="empty-state"><i class="bi bi-bar-chart"></i><p>@lang('exams.results.empty_title')</p><small>@lang('exams.results.empty_hint')</small></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
