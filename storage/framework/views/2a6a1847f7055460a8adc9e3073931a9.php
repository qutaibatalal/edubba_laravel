<?php $__env->startSection('title', __('dashboard.title')); ?>
<?php $__env->startSection('page', __('dashboard.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('dashboard.title'); ?></h1>
        <p><?php echo app('translator')->get('dashboard.welcome_line', ['name' => Auth::user()->name]); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.admissions.create')); ?>" class="btn btn-outline-primary"><i class="bi bi-clipboard2-plus me-1"></i> <?php echo app('translator')->get('dashboard.new_admission'); ?></a>
        <a href="<?php echo e(route('admin.students.create')); ?>" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i> <?php echo app('translator')->get('dashboard.new_student'); ?></a>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-1 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-value num" data-count="<?php echo e($stats['students']); ?>"><?php echo e(number_format($stats['students'])); ?></div>
                    <div class="stat-label"><?php echo app('translator')->get('dashboard.enrolled_students'); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-2 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <div class="stat-value num" data-count="<?php echo e($stats['faculty']); ?>"><?php echo e(number_format($stats['faculty'])); ?></div>
                    <div class="stat-label"><?php echo app('translator')->get('dashboard.teaching_staff'); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-4 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
                <div>
                    <div class="stat-value num" data-count="<?php echo e($stats['invoices_balance']); ?>"><?php echo e(number_format($stats['invoices_balance'])); ?></div>
                    <div class="stat-label"><?php echo app('translator')->get('dashboard.total_balance'); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card st-5 hoverable">
            <div class="stat-body">
                <div class="stat-icon"><i class="bi bi-clipboard-check-fill"></i></div>
                <div>
                    <div class="stat-value num" data-count="<?php echo e($stats['admissions_pending']); ?>"><?php echo e(number_format($stats['admissions_pending'])); ?></div>
                    <div class="stat-label"><?php echo app('translator')->get('dashboard.pending_admissions'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="bento mb-4">
    <div class="b-8">
        <div class="card h-100 hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><?php echo app('translator')->get('dashboard.batch_distribution'); ?></span>
                <span class="badge badge-soft-primary"><?php echo app('translator')->get('dashboard.batches_count', ['count' => count($perBatch)]); ?></span>
            </div>
            <div class="card-body">
                <canvas id="batchChart" height="240"></canvas>
            </div>
        </div>
    </div>
    <div class="b-4">
        <div class="card h-100 hoverable">
            <div class="card-header"><span class="fw-bold"><?php echo app('translator')->get('dashboard.system_summary'); ?></span></div>
            <div class="card-body d-flex flex-column justify-content-center">
                <canvas id="summaryChart" height="240"></canvas>
            </div>
        </div>
    </div>
    <div class="b-4">
        <div class="card h-100 hoverable">
            <div class="card-header"><span class="fw-bold"><?php echo app('translator')->get('dashboard.quick_actions'); ?></span></div>
            <div class="card-body d-grid gap-2" style="grid-template-columns:1fr 1fr">
                <a href="<?php echo e(route('admin.students.create')); ?>" class="btn btn-outline-primary btn-sm text-start"><i class="bi bi-person-plus me-1"></i> <?php echo app('translator')->get('dashboard.student_short'); ?></a>
                <a href="<?php echo e(route('admin.admissions.create')); ?>" class="btn btn-outline-primary btn-sm text-start"><i class="bi bi-clipboard2-plus me-1"></i> <?php echo app('translator')->get('dashboard.admission_short'); ?></a>
                <a href="<?php echo e(route('admin.fees.structures.create')); ?>" class="btn btn-outline-primary btn-sm text-start"><i class="bi bi-cash-stack me-1"></i> <?php echo app('translator')->get('dashboard.fee_structure'); ?></a>
                <a href="<?php echo e(route('admin.reports.index')); ?>" class="btn btn-outline-primary btn-sm text-start"><i class="bi bi-graph-up-arrow me-1"></i> <?php echo app('translator')->get('dashboard.report_short'); ?></a>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-xl-6">
        <div class="card h-100 hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-bell-fill me-1 text-warning"></i> <?php echo app('translator')->get('dashboard.needs_attention'); ?></span>
                <span class="badge badge-soft-<?php echo e($alerts->contains(fn ($a) => $a->level === 'danger') ? 'danger' : 'secondary'); ?>"><?php echo e($alerts->count()); ?></span>
            </div>
            <div class="list-group list-group-flush">
                <?php $__empty_1 = true; $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e($a->href); ?>" class="list-group-item list-group-item-action d-flex gap-3 align-items-start py-3">
                        <span class="edb-icon-btn flex-shrink-0">
                            <i class="bi <?php echo e($a->icon); ?> text-<?php echo e($a->level); ?>"></i>
                        </span>
                        <div class="flex-grow-1">
                            <span class="d-block fw-bold small"><?php echo e($a->title); ?></span>
                            <span class="d-block small text-secondary"><?php echo e($a->text); ?></span>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state py-5"><i class="bi bi-check2-circle text-success"></i><p><?php echo app('translator')->get('dashboard.all_good'); ?></p><small><?php echo app('translator')->get('dashboard.no_alerts_now'); ?></small></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card h-100 hoverable">
            <div class="card-header"><span class="fw-bold"><i class="bi bi-activity me-1"></i> <?php echo app('translator')->get('dashboard.today_activity'); ?></span></div>
            <div class="list-group list-group-flush">
                <?php $__empty_1 = true; $__currentLoopData = $activity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="list-group-item d-flex gap-3 align-items-start py-3">
                        <span class="edb-icon-btn flex-shrink-0"><i class="bi <?php echo e($act->icon); ?> text-<?php echo e($act->color); ?>"></i></span>
                        <div class="flex-grow-1">
                            <span class="d-block small"><?php echo e($act->text); ?></span>
                            <span class="d-block text-secondary" style="font-size:.72rem"><?php echo e($act->time->diffForHumans()); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state py-5"><i class="bi bi-clock-history"></i><p><?php echo app('translator')->get('dashboard.no_activity_today'); ?></p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="row g-3">
    <div class="col-xl-7">
        <div class="card hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><?php echo app('translator')->get('dashboard.recent_invoices'); ?></span>
                <a href="<?php echo e(route('admin.fees.structures')); ?>" class="btn btn-sm btn-outline-primary"><?php echo app('translator')->get('dashboard.manage_fees'); ?></a>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0">
                    <thead><tr><th><?php echo app('translator')->get('dashboard.invoice_number'); ?></th><th><?php echo app('translator')->get('dashboard.student'); ?></th><th class="text-start"><?php echo app('translator')->get('dashboard.total'); ?></th><th class="text-start"><?php echo app('translator')->get('dashboard.remaining'); ?></th><th><?php echo app('translator')->get('dashboard.status'); ?></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><span class="badge badge-soft"><?php echo e($inv->number); ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-1 avatar-sm"><?php echo e(mb_substr($inv->student?->name ?? '?', 0, 1)); ?></span>
                                        <span class="fw-semibold"><?php echo e($inv->student?->full_name ?? '—'); ?></span>
                                    </div>
                                </td>
                                <td class="text-start num fw-semibold"><?php echo e(number_format($inv->total)); ?></td>
                                <td class="text-start num"><?php echo e(number_format($inv->balance)); ?></td>
                                <td>
                                    <?php $b = ['open' => 'warning', 'paid' => 'success', 'draft' => 'secondary', 'cancel' => 'danger']; ?>
                                    <span class="badge badge-soft-<?php echo e($b[$inv->state] ?? 'secondary'); ?>"><?php echo e($inv->state); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-receipt"></i><p><?php echo app('translator')->get('dashboard.no_invoices'); ?></p><small><?php echo app('translator')->get('dashboard.no_invoices_hint'); ?></small><a href="<?php echo e(route('admin.fees.structures.create')); ?>" class="btn btn-sm btn-primary"><?php echo app('translator')->get('dashboard.create_fee_structure'); ?></a></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><?php echo app('translator')->get('dashboard.recent_admissions'); ?></span>
                <a href="<?php echo e(route('admin.admissions.index')); ?>" class="btn btn-sm btn-outline-primary"><?php echo app('translator')->get('dashboard.all'); ?></a>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0">
                    <thead><tr><th><?php echo app('translator')->get('dashboard.number'); ?></th><th><?php echo app('translator')->get('dashboard.name'); ?></th><th><?php echo app('translator')->get('dashboard.batch'); ?></th><th><?php echo app('translator')->get('dashboard.status'); ?></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentAdmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><span class="badge badge-soft"><?php echo e($ad->application_no); ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-3 avatar-sm"><?php echo e(mb_substr($ad->name ?? '?', 0, 1)); ?></span>
                                        <span class="fw-semibold"><?php echo e($ad->full_name); ?></span>
                                    </div>
                                </td>
                                <td><?php echo e($ad->batch?->name ?? '—'); ?></td>
                                <td>
                                    <?php $b = ['draft' => 'secondary', 'submit' => 'info', 'approve' => 'success', 'reject' => 'danger', 'admitted' => 'primary']; ?>
                                    <span class="badge badge-soft-<?php echo e($b[$ad->state] ?? 'secondary'); ?>"><?php echo e($ad->state); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="4"><div class="empty-state"><i class="bi bi-clipboard2-x"></i><p><?php echo app('translator')->get('dashboard.no_admissions'); ?></p><small><?php echo app('translator')->get('dashboard.no_admissions_hint'); ?></small><a href="<?php echo e(route('admin.admissions.create')); ?>" class="btn btn-sm btn-primary"><?php echo app('translator')->get('dashboard.new_request'); ?></a></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php
    $chartColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const primaryColor = <?php echo json_encode($chartColor, 15, 512) ?>;
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    Chart.defaults.font.family = "'Tajawal', sans-serif";
    Chart.defaults.color = isDark ? '#a1a9b8' : '#98a1b0';
    const grid = isDark ? 'rgba(255,255,255,.06)' : 'rgba(16,24,40,.06)';

    const perBatch = <?php echo json_encode($perBatch, 15, 512) ?>;

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
        admitted: <?php echo json_encode($stats['students'], 15, 512) ?>,
        pending: <?php echo json_encode($stats['admissions_pending'], 15, 512) ?>,
        openInvoices: <?php echo json_encode($stats['invoices_open'], 15, 512) ?>,
    };
    if (document.getElementById('summaryChart')) {
        new Chart(document.getElementById('summaryChart'), {
            type: 'doughnut',
            data: {
                labels: ['<?php echo app('translator')->get('dashboard.chart_enrolled'); ?>', '<?php echo app('translator')->get('dashboard.chart_pending'); ?>', '<?php echo app('translator')->get('dashboard.chart_open_invoices'); ?>'],
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qutaiba\Desktop\edubba_laravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>