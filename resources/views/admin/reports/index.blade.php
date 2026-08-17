@extends('admin.layouts.app')

@section('title', __('reports.index.title'))
@section('page', __('reports.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('reports.index.h1')</h1>
        <p>@lang('reports.index.subtitle')</p>
    </div>
</div>

<form method="POST" class="card hoverable mb-4" action="{{ route('admin.reports.generate') }}">
    @csrf
    <div class="card-body p-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">@lang('reports.index.academic_year')</label>
                <select name="academic_year_id" class="form-select" required>
                    <option value="">@lang('reports.index.select')</option>
                    @foreach ($years as $y)
                        <option value="{{ $y->id }}">{{ $y->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">@lang('reports.index.report_type')</label>
                <select name="report_type" class="form-select" required>
                    <option value="student_counts">@lang('reports.index.type_student_counts')</option>
                    <option value="staff_counts">@lang('reports.index.type_staff_counts')</option>
                    <option value="pass_rates">@lang('reports.index.type_pass_rates')</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-file-earmark-bar-graph me-1"></i> @lang('reports.index.generate')</button>
            </div>
        </div>
    </div>
</form>

<div class="card hoverable">
    <div class="card-header fw-bold"><i class="bi bi-graph-up-arrow me-2 text-primary"></i> @lang('reports.index.previous_reports')</div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th>@lang('reports.index.table_year')</th><th>@lang('reports.index.table_type')</th><th>@lang('reports.index.table_data')</th><th>@lang('reports.index.table_generated_at')</th></tr></thead>
            <tbody>
                @forelse ($reports as $r)
                    @php $typeColors = ['student_counts' => 'info', 'staff_counts' => 'purple', 'pass_rates' => 'success']; @endphp
                    <tr>
                        <td><span class="badge badge-soft-primary">{{ $r->academicYear?->name ?? '—' }}</span></td>
                        <td><span class="badge badge-soft-{{ $typeColors[$r->report_type] ?? 'secondary' }}">{{ $r->report_type }}</span></td>
                        <td>
                            @if ($r->data)
                                @foreach (($r->data['rows'] ?? $r->data) as $k => $v)
                                    <span class="badge badge-soft me-1">{{ $k }}: {{ is_array($v) ? count($v) : $v }}</span>
                                @endforeach
                            @else
                                <span class="text-secondary">—</span>
                            @endif
                        </td>
                        <td>{{ $r->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-clipboard-x"></i><p>@lang('reports.index.empty')</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
