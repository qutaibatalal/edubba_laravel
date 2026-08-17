<?php $__env->startSection('title', __('attendance.pdf.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-3 fw-bold"><?php echo app('translator')->get('attendance.pdf.heading'); ?></h1>
                <p class="mb-4 text-muted">
                    <?php echo app('translator')->get('attendance.pdf.month', ['month' => \Carbon\Carbon::parse($month.'-01')->translatedFormat('F Y')]); ?>
                    <?php echo e($batch ? ' - '.$batch->name : ' - '.__('attendance.pdf.all_batches')); ?>

                </p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" style="margin-bottom:20px;">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="batch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value=""><?php echo app('translator')->get('attendance.pdf.all_batches'); ?></option>
                                <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($b->id); ?>" <?php echo e($batchId == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="month" name="month" class="form-control form-control-sm" value="<?php echo e($month->format('m-Y')); ?>" onchange="this.form.submit()">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-filter me-1"></i> <?php echo app('translator')->get('attendance.pdf.filter'); ?>
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="<?php echo e(route('admin.attendance.pdf.download')); ?>" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-file-pdf me-1"></i> <?php echo app('translator')->get('attendance.pdf.download'); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if($data->isNotEmpty()): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-nowrap mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo app('translator')->get('attendance.student'); ?></th>
                        <th><?php echo app('translator')->get('attendance.batch'); ?></th>
                        <th class="text-center"><?php echo app('translator')->get('attendance.pdf.total_periods'); ?></th>
                        <th class="text-center"><?php echo app('translator')->get('attendance.status.present'); ?></th>
                        <th class="text-center"><?php echo app('translator')->get('attendance.status.late'); ?></th>
                        <th class="text-center"><?php echo app('translator')->get('attendance.status.absent'); ?></th>
                        <th class="text-center"><?php echo app('translator')->get('attendance.status.leave'); ?></th>
                        <th class="text-center"><?php echo app('translator')->get('attendance.pdf.attendance_rate'); ?></th>
                        <th><?php echo app('translator')->get('attendance.pdf.status'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($idx + 1); ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm" style="background:<?php echo e($r->student->color ?? '#007bff'); ?>;">
                                    <?php echo e(mb_substr($r->student->full_name ?? '?', 0, 1)); ?>

                                </span>
                                <span><?php echo e($r->student->full_name); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($r->batch ?? '—'); ?></td>
                        <td class="text-center"><?php echo e($r->total); ?></td>
                        <td class="text-success"><?php echo e($r->present); ?></td>
                        <td class="text-warning"><?php echo e($r->late); ?></td>
                        <td class="text-danger"><?php echo e($r->absent); ?></td>
                        <td class="text-info"><?php echo e($r->leave); ?></td>
                        <td class="text-center">
                            <div class="progress progress-sm mb-0" style="height: 8px;">
                                <div class="progress-bar rounded-pill" style="background:
                                    <?php if($r->percentage >= 90): ?> #198754
                                    <?php elseif($r->percentage >= 75): ?> #6c757d
                                    <?php elseif($r->percentage >= 60): ?> #ffc107
                                    <?php else: ?> #dc3545
                                    <?php endif; ?>
                                    ; width:<?php echo e(min($r->percentage, 100)); ?>%"></div>
                            </div>
                            <small class="d-block mt-1"><?php echo e($r->percentage); ?>%</small>
                        </td>
                        <td class="text-end">
                            <?php if($r->percentage >= 90): ?>
                                <span class="badge bg-success"><?php echo app('translator')->get('attendance.monthly.excellent'); ?></span>
                            <?php elseif($r->percentage >= 75): ?>
                                <span class="badge bg-primary"><?php echo app('translator')->get('attendance.monthly.good'); ?></span>
                            <?php elseif($r->percentage >= 60): ?>
                                <span class="badge bg-warning"><?php echo app('translator')->get('attendance.monthly.low'); ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger"><?php echo app('translator')->get('attendance.monthly.danger'); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td></td>
                        <td><strong><?php echo app('translator')->get('attendance.pdf.total'); ?></strong></td>
                        <td><strong><?php echo e($data->sum('present')); ?></strong></td>
                        <td></td>
                        <td><strong><?php echo e($data->sum('absent')); ?></strong></td>
                        <td></td>
                        <td><strong><?php echo app('translator')->get('attendance.pdf.avg_rate', ['rate' => $data->avg('percentage', 0)]); ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <?php echo app('translator')->get('attendance.pdf.no_data'); ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/attendance/pdf.blade.php ENDPATH**/ ?>