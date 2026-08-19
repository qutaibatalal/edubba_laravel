@extends('admin.layouts.app')

@section('title', $year->name)
@section('page', __('years.show.page', ['name' => $year->name]))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $year->name }}</h1>
        <p class="text-secondary mb-0">
            {{ $year->date_start?->format('Y-m-d') ?? '—' }} → {{ $year->date_stop?->format('Y-m-d') ?? '—' }}
            @if ($year->current) <span class="badge badge-soft-success ms-2">@lang('years.show.current')</span> @endif
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.academic-years.edit', $year) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> @lang('years.show.edit')</a>
        <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('years.show.back_to_list')</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card hoverable">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> @lang('years.show.details')</h6>
                <div class="text-start small">
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('years.show.label_name')</span><b>{{ $year->name }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('years.show.label_start')</span><b class="num">{{ $year->date_start?->format('Y-m-d') ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('years.show.label_end')</span><b class="num">{{ $year->date_stop?->format('Y-m-d') ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('years.show.label_current')</span>
                        @if ($year->current)<span class="badge badge-soft-success">@lang('years.show.yes')</span>@else<span class="text-secondary">—</span>@endif
                    </div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('years.show.label_status')</span>
                        <span class="badge badge-soft-{{ $year->active ? 'success' : 'secondary' }}">{{ $year->active ? __('years.show.active') : __('years.show.inactive') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <ul class="nav nav-tabs edb-tabs mb-3" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-batches" type="button">@lang('years.show.tab_batches', ['count' => $year->batches->count()])</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-admissions" type="button">@lang('years.show.tab_admissions', ['count' => $year->admissions->count()])</button></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-batches">
                <div class="card hoverable"><div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-edb mb-0">
                            <thead><tr><th>@lang('years.show.col_name')</th><th>@lang('years.show.col_program')</th><th>@lang('years.show.col_teacher')</th><th>@lang('years.show.col_students')</th></tr></thead>
                            <tbody>
                                @forelse ($year->batches as $batch)
                                    <tr>
                                        <td>{{ $batch->name }}</td>
                                        <td>{{ $batch->program?->name ?? '—' }}</td>
                                        <td>{{ $batch->classTeacher?->name ?? '—' }}</td>
                                        <td><span class="badge badge-soft-primary">{{ $batch->students_count ?? $batch->students->count() }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-diagram-3"></i><p>@lang('years.show.no_batches')</p></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div></div>
            </div>

            <div class="tab-pane fade" id="tab-admissions">
                <div class="card hoverable"><div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-edb mb-0">
                            <thead><tr><th>@lang('years.show.col_student')</th><th>@lang('years.show.col_batch')</th><th>@lang('years.show.col_date')</th><th>@lang('years.show.col_status')</th></tr></thead>
                            <tbody>
                                @forelse ($year->admissions->take(30) as $admission)
                                    <tr>
                                        <td>{{ $admission->name ?? $admission->student?->name ?? '—' }}</td>
                                        <td>{{ $admission->batch?->name ?? '—' }}</td>
                                        <td class="num">{{ $admission->created_at?->format('Y-m-d') ?? '—' }}</td>
                                        <td><span class="badge badge-soft-{{ $admission->state === 'admitted' ? 'success' : 'warning' }}">{{ $admission->state ?? '—' }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-inbox"></i><p>@lang('years.show.no_admissions')</p></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div></div>
            </div>
        </div>
    </div>
</div>

<style>
.edb-tabs { border-bottom: 1px solid var(--edb-border); }
.edb-tabs .nav-link { border: 0; border-bottom: 2px solid transparent; font-weight: 700; color: var(--edb-text-2); padding: 10px 16px; border-radius: 0; }
.edb-tabs .nav-link.active { color: var(--edb-primary); border-bottom-color: var(--edb-primary); background: transparent; }
.edb-tabs .nav-link:hover { border-bottom-color: var(--edb-border-strong); }
</style>
@endsection