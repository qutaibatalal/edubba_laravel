<?php $__env->startSection('title', __('reports.index.title')); ?>
<?php $__env->startSection('page', __('reports.index.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('reports.index.h1'); ?></h1>
        <p><?php echo app('translator')->get('reports.index.subtitle'); ?></p>
    </div>
</div>

<form method="POST" class="card hoverable mb-4" action="<?php echo e(route('admin.reports.generate')); ?>">
    <?php echo csrf_field(); ?>
    <div class="card-body p-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label"><?php echo app('translator')->get('reports.index.academic_year'); ?></label>
                <select name="academic_year_id" class="form-select" required>
                    <option value=""><?php echo app('translator')->get('reports.index.select'); ?></option>
                    <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($y->id); ?>"><?php echo e($y->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label"><?php echo app('translator')->get('reports.index.report_type'); ?></label>
                <select name="report_type" class="form-select" required>
                    <option value="student_counts"><?php echo app('translator')->get('reports.index.type_student_counts'); ?></option>
                    <option value="staff_counts"><?php echo app('translator')->get('reports.index.type_staff_counts'); ?></option>
                    <option value="pass_rates"><?php echo app('translator')->get('reports.index.type_pass_rates'); ?></option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-file-earmark-bar-graph me-1"></i> <?php echo app('translator')->get('reports.index.generate'); ?></button>
            </div>
        </div>
    </div>
</form>

<div class="card hoverable">
    <div class="card-header fw-bold"><i class="bi bi-graph-up-arrow me-2 text-primary"></i> <?php echo app('translator')->get('reports.index.previous_reports'); ?></div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th><?php echo app('translator')->get('reports.index.table_year'); ?></th><th><?php echo app('translator')->get('reports.index.table_type'); ?></th><th><?php echo app('translator')->get('reports.index.table_data'); ?></th><th><?php echo app('translator')->get('reports.index.table_generated_at'); ?></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $typeColors = ['student_counts' => 'info', 'staff_counts' => 'purple', 'pass_rates' => 'success']; ?>
                    <tr>
                        <td><span class="badge badge-soft-primary"><?php echo e($r->academicYear?->name ?? '—'); ?></span></td>
                        <td><span class="badge badge-soft-<?php echo e($typeColors[$r->report_type] ?? 'secondary'); ?>"><?php echo e($r->report_type); ?></span></td>
                        <td>
                            <?php if($r->data): ?>
                                <?php $__currentLoopData = ($r->data['rows'] ?? $r->data); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge badge-soft me-1"><?php echo e($k); ?>: <?php echo e(is_array($v) ? count($v) : $v); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <span class="text-secondary">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($r->created_at?->format('Y-m-d H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-clipboard-x"></i><p><?php echo app('translator')->get('reports.index.empty'); ?></p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>