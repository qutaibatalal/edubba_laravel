@extends('admin.layouts.app')

@section('title', __('exams.marksheet.title', ['name' => $marksheet->student?->full_name]))
@section('page', __('exams.marksheet.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('exams.marksheet.heading', ['name' => $marksheet->student?->full_name])</h1>
        <p class="text-secondary mb-0">{{ $exam->name }} · {{ $marksheet->student?->batch?->name ?? '' }}</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge badge-soft-{{ $marksheet->is_finalized ? 'success' : 'warning' }} align-self-center">{{ $marksheet->is_finalized ? __('exams.marksheets.approved') : __('exams.marksheets.draft') }}</span>
        <a href="{{ route('admin.exams.marksheets', $exam) }}" class="btn btn-light border"><i class="bi bi-arrow-right me-1"></i> @lang('exams.show.back')</a>
    </div>
</div>

@if ($marksheet->is_finalized)
    <div class="alert alert-success py-2 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> @lang('exams.marksheet.finalized_notice')
    </div>
@endif

<div class="bento">
    <div class="b-8">
        <div class="card hoverable">
            <div class="card-header fw-semibold"><span><i class="bi bi-journal-text me-2 text-primary"></i> @lang('exams.marksheet.grades_title')</span></div>
            <form method="POST" action="{{ route('admin.exams.marksheet.store', [$exam, $marksheet]) }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-edb mb-0 align-middle">
                        <thead><tr><th>@lang('exams.marksheet.col_subject')</th><th class="num">@lang('exams.marksheet.col_max')</th><th class="num">@lang('exams.marksheet.col_pass')</th><th style="width:180px" class="num">@lang('exams.marksheet.col_marks')</th><th class="num">@lang('exams.marksheet.col_percentage')</th><th>@lang('exams.marksheet.col_grade')</th><th>@lang('exams.marksheet.col_status')</th></tr></thead>
                        <tbody>
                            @foreach ($marksheet->lines as $line)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $line->subject?->name ?? '—' }}</div>
                                        <small class="text-secondary">{{ $line->course?->name }}</small>
                                    </td>
                                    <td class="num">{{ $line->max_marks }}</td>
                                    <td class="num">{{ $line->pass_marks }}</td>
                                    <td>
                                        <input type="number" name="lines[{{ $line->id }}]" step="0.5" min="0" value="{{ $line->marks }}" class="form-control num" {{ $marksheet->is_finalized ? 'disabled' : '' }}>
                                    </td>
                                    <td class="num">{{ $line->percentage }}%</td>
                                    <td><span class="badge badge-soft-primary">{{ $line->grade ?: '—' }}</span></td>
                                    <td>
                                        <span class="badge badge-soft-{{ $line->passed ? 'success' : 'danger' }}">{{ $line->passed ? __('exams.marksheets.pass') : __('exams.marksheets.fail') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex gap-2">
                        <span class="badge badge-soft-primary num">{{ $marksheet->obtained_marks }} / {{ $marksheet->total_marks }}</span>
                        <span class="badge badge-soft-info num">{{ $marksheet->percentage }}%</span>
                        <span class="badge badge-soft-purple">{{ $marksheet->grade ?: '—' }}</span>
                        @if ($marksheet->rank) <span class="badge badge-soft-success num">@lang('exams.marksheet.rank', ['rank' => $marksheet->rank])</span> @endif
                    </div>
                    <div class="d-flex gap-2">
                        @if (! $marksheet->is_finalized)
                            <button class="btn btn-primary"><i class="bi bi-save me-1"></i> @lang('exams.marksheet.save_marks')</button>
                            <a href="{{ route('admin.exams.marksheet.finalize', [$exam, $marksheet]) }}" class="btn btn-success" onclick="event.preventDefault(); if(confirm('{{ __('exams.marksheet.confirm_finalize') }}')) document.getElementById('finalize-form').submit();">@lang('exams.marksheet.finalize') <i class="bi bi-check2-all"></i></a>
                        @endif
                    </div>
                </div>
            </form>
            @if (! $marksheet->is_finalized)
                <form method="POST" action="{{ route('admin.exams.marksheet.finalize', [$exam, $marksheet]) }}" id="finalize-form" class="d-none">@csrf</form>
            @endif
        </div>
    </div>

    <div class="b-4">
        <div class="card hoverable">
            <div class="card-header fw-semibold"><i class="bi bi-person-vcard me-2 text-primary"></i> @lang('exams.marksheet.student_data')</div>
            <div class="card-body small">
                <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('exams.marksheet.st_name')</span><span class="fw-semibold">{{ $marksheet->student?->full_name }}</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('exams.marksheet.st_number')</span><span class="num">{{ $marksheet->student?->roll_no ?? $marksheet->student?->student_code }}</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('exams.marksheet.st_batch')</span><span>{{ $marksheet->student?->batch?->name }}</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('exams.marksheet.st_exam')</span><span>{{ $exam->name }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
