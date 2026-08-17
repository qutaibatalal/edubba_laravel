@extends('admin.layouts.app')

@section('title', __('dashboard.title'))
@section('page', __('dashboard.title'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('dashboard.title')</h1>
        <p>@lang('dashboard.welcome_line', ['name' => Auth::user()->name])</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.admissions.create') }}" class="btn btn-outline-primary"><i class="bi bi-clipboard2-plus me-1"></i> @lang('dashboard.new_admission')</a>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i> @lang('dashboard.new_student')</a>
    </div>
</div>

{{-- KPI strip — 5 primary indicators (2026: restraint, hierarchy) --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-1 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-value num" data-count="{{ $stats['students'] }}">{{ number_format($stats['students']) }}</div>
                    <div class="stat-label">@lang('dashboard.enrolled_students')</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-2 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <div class="stat-value num" data-count="{{ $stats['faculty'] }}">{{ number_format($stats['faculty']) }}</div>
                    <div class="stat-label">@lang('dashboard.teaching_staff')</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-4 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
                <div>
                    <div class="stat-value num" data-count="{{ $stats['invoices_balance'] }}">{{ number_format($stats['invoices_balance']) }}</div>
                    <div class="stat-label">@lang('dashboard.total_balance')</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-5 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-clipboard-check-fill"></i></div>
                <div>
                    <div class="stat-value num" data-count="{{ $stats['admissions_pending'] }}">{{ number_format($stats['admissions_pending']) }}</div>
                    <div class="stat-label">@lang('dashboard.pending_admissions')</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bento grid: analytics + quick actions --}}
<div class="bento mb-4">
    <div class="b-8">
        <div class="card h-100 hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">@lang('dashboard.batch_distribution')</span>
                <span class="badge badge-soft-primary">@lang('dashboard.batches_count', ['count' => count($perBatch)])</span>
            </div>
            <div class="card-body">
                <canvas id="batchChart" height="240"></canvas>
            </div>
        </div>
    </div>
    <div class="b-4">
        <div class="card h-100 hoverable">
            <div class="card-header"><span class="fw-bold">@lang('dashboard.system_summary')</span></div>
            <div class="card-body d-flex flex-column justify-content-center">
                <canvas id="summaryChart" height="240"></canvas>
            </div>
        </div>
    </div>
    <div class="b-4">
        <div class="card h-100 hoverable">
            <div class="card-header"><span class="fw-bold">@lang('dashboard.quick_actions')</span></div>
            <div class="card-body d-grid gap-2" style="grid-template-columns:1fr 1fr">
                <a href="{{ route('admin.students.create') }}" class="btn btn-outline-primary btn-sm text-start"><i class="bi bi-person-plus me-1"></i> @lang('dashboard.student_short')</a>
                <a href="{{ route('admin.admissions.create') }}" class="btn btn-outline-primary btn-sm text-start"><i class="bi bi-clipboard2-plus me-1"></i> @lang('dashboard.admission_short')</a>
                <a href="{{ route('admin.fees.structures.create') }}" class="btn btn-outline-primary btn-sm text-start"><i class="bi bi-cash-stack me-1"></i> @lang('dashboard.fee_structure')</a>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary btn-sm text-start"><i class="bi bi-graph-up-arrow me-1"></i> @lang('dashboard.report_short')</a>
            </div>
        </div>
    </div>
</div>

{{-- Alerts + activity (2026: alerts first, table second) --}}
<div class="row g-3 mb-4">
    <div class="col-xl-6">
        <div class="card h-100 hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-bell-fill me-1 text-warning"></i> @lang('dashboard.needs_attention')</span>
                <span class="badge badge-soft-{{ $alerts->contains(fn ($a) => $a->level === 'danger') ? 'danger' : 'secondary' }}">{{ $alerts->count() }}</span>
            </div>
            <div class="list-group list-group-flush">
                @forelse ($alerts as $a)
                    <a href="{{ $a->href }}" class="list-group-item list-group-item-action d-flex gap-3 align-items-start py-3">
                        <span class="edb-icon-btn flex-shrink-0">
                            <i class="bi {{ $a->icon }} text-{{ $a->level }}"></i>
                        </span>
                        <div class="flex-grow-1">
                            <span class="d-block fw-bold small">{{ $a->title }}</span>
                            <span class="d-block small text-secondary">{{ $a->text }}</span>
                        </div>
                    </a>
                @empty
                    <div class="empty-state py-5"><i class="bi bi-check2-circle text-success"></i><p>@lang('dashboard.all_good')</p><small>@lang('dashboard.no_alerts_now')</small></div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card h-100 hoverable">
            <div class="card-header"><span class="fw-bold"><i class="bi bi-activity me-1"></i> @lang('dashboard.today_activity')</span></div>
            <div class="list-group list-group-flush">
                @forelse ($activity as $act)
                    <div class="list-group-item d-flex gap-3 align-items-start py-3">
                        <span class="edb-icon-btn flex-shrink-0"><i class="bi {{ $act->icon }} text-{{ $act->color }}"></i></span>
                        <div class="flex-grow-1">
                            <span class="d-block small">{{ $act->text }}</span>
                            <span class="d-block text-secondary" style="font-size:.72rem">{{ $act->time->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state py-5"><i class="bi bi-clock-history"></i><p>@lang('dashboard.no_activity_today')</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Tables first: the product (2026 pattern) --}}
<div class="row g-3">
    <div class="col-xl-7">
        <div class="card hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">@lang('dashboard.recent_invoices')</span>
                <a href="{{ route('admin.fees.structures') }}" class="btn btn-sm btn-outline-primary">@lang('dashboard.manage_fees')</a>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0">
                    <thead><tr><th>@lang('dashboard.invoice_number')</th><th>@lang('dashboard.student')</th><th class="text-start">@lang('dashboard.total')</th><th class="text-start">@lang('dashboard.remaining')</th><th>@lang('dashboard.status')</th></tr></thead>
                    <tbody>
                        @forelse ($recentInvoices as $inv)
                            <tr>
                                <td><span class="badge badge-soft">{{ $inv->number }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-1 avatar-sm">{{ mb_substr($inv->student?->name ?? '?', 0, 1) }}</span>
                                        <span class="fw-semibold">{{ $inv->student?->full_name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="text-start num fw-semibold">{{ number_format($inv->total) }}</td>
                                <td class="text-start num">{{ number_format($inv->balance) }}</td>
                                <td>
                                    @php $b = ['open' => 'warning', 'paid' => 'success', 'draft' => 'secondary', 'cancel' => 'danger']; @endphp
                                    <span class="badge badge-soft-{{ $b[$inv->state] ?? 'secondary' }}">{{ $inv->state }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-receipt"></i><p>@lang('dashboard.no_invoices')</p><small>@lang('dashboard.no_invoices_hint')</small><a href="{{ route('admin.fees.structures.create') }}" class="btn btn-sm btn-primary">@lang('dashboard.create_fee_structure')</a></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">@lang('dashboard.recent_admissions')</span>
                <a href="{{ route('admin.admissions.index') }}" class="btn btn-sm btn-outline-primary">@lang('dashboard.all')</a>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0">
                    <thead><tr><th>@lang('dashboard.number')</th><th>@lang('dashboard.name')</th><th>@lang('dashboard.batch')</th><th>@lang('dashboard.status')</th></tr></thead>
                    <tbody>
                        @forelse ($recentAdmissions as $ad)
                            <tr>
                                <td><span class="badge badge-soft">{{ $ad->application_no }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-3 avatar-sm">{{ mb_substr($ad->name ?? '?', 0, 1) }}</span>
                                        <span class="fw-semibold">{{ $ad->full_name }}</span>
                                    </div>
                                </td>
                                <td>{{ $ad->batch?->name ?? '—' }}</td>
                                <td>
                                    @php $b = ['draft' => 'secondary', 'submit' => 'info', 'approve' => 'success', 'reject' => 'danger', 'admitted' => 'primary']; @endphp
                                    <span class="badge badge-soft-{{ $b[$ad->state] ?? 'secondary' }}">{{ $ad->state }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="empty-state"><i class="bi bi-clipboard2-x"></i><p>@lang('dashboard.no_admissions')</p><small>@lang('dashboard.no_admissions_hint')</small><a href="{{ route('admin.admissions.create') }}" class="btn btn-sm btn-primary">@lang('dashboard.new_request')</a></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@php
    $chartColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
@endphp
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const primaryColor = @json($chartColor);
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    Chart.defaults.font.family = "'Tajawal', sans-serif";
    Chart.defaults.color = isDark ? '#a1a9b8' : '#98a1b0';
    const grid = isDark ? 'rgba(255,255,255,.06)' : 'rgba(16,24,40,.06)';

    const perBatch = @json($perBatch);

    if (document.getElementById('batchChart')) {
        new Chart(document.getElementById('batchChart'), {
            type: 'bar',
            data: {
                labels: perBatch.map(r => r.batch),
                datasets: [{
                    data: perBatch.map(r => r.total),
                    backgroundColor: primaryColor,
                    hoverBackgroundColor: primaryColor + 'cc',
                    borderRadius: 8, borderSkipped: false, maxBarThickness: 42,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0e1017', padding: 10, cornerRadius: 8, titleFont: { family: "'Tajawal'" } } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { weight: 600 } } },
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: grid }, border: { display: false } }
                }
            }
        });
    }

    const summary = {
        admitted: @json($stats['students']),
        pending: @json($stats['admissions_pending']),
        openInvoices: @json($stats['invoices_open']),
    };
    if (document.getElementById('summaryChart')) {
        new Chart(document.getElementById('summaryChart'), {
            type: 'doughnut',
            data: {
                labels: ['@lang('dashboard.chart_enrolled')', '@lang('dashboard.chart_pending')', '@lang('dashboard.chart_open_invoices')'],
                datasets: [{
                    data: [summary.admitted, summary.pending, summary.openInvoices],
                    backgroundColor: ['#4f46e5', '#d97706', '#dc2626'],
                    borderWidth: 0, hoverOffset: 6,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 14, font: { size: 11, weight: 600 } } },
                    tooltip: { backgroundColor: '#0e1017', padding: 10, cornerRadius: 8 }
                }
            }
        });
    }
});
</script>
@endpush
