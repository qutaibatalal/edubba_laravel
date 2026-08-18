<?php $__env->startSection('title', __('fees.invoices.title')); ?>
<?php $__env->startSection('page', __('fees.invoices.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('fees.invoices.h1'); ?></h1>
        <p class="text-secondary mb-0"><?php echo app('translator')->get('fees.invoices.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.fees.structures')); ?>" class="btn btn-outline-primary"><i class="bi bi-cash-stack me-1"></i> <?php echo app('translator')->get('fees.invoices.structures_link'); ?></a>
    </div>
</div>

<div class="card hoverable">
    <div class="card-header d-flex flex-wrap align-items-center gap-2">
        <form method="GET" class="d-flex align-items-center gap-2">
            <span class="text-secondary small fw-semibold"><?php echo app('translator')->get('fees.invoices.status_label'); ?></span>
            <select name="state" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value=""><?php echo app('translator')->get('fees.invoices.all_invoices'); ?></option>
                <?php $__currentLoopData = ['draft','open','paid','cancel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st); ?>" <?php echo e(request('state') === $st ? 'selected' : ''); ?>><?php echo e($st); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-edb mb-0 align-middle">
            <thead><tr><th><?php echo app('translator')->get('fees.invoices.table_number'); ?></th><th><?php echo app('translator')->get('fees.invoices.table_student'); ?></th><th><?php echo app('translator')->get('fees.invoices.table_date'); ?></th><th><?php echo app('translator')->get('fees.invoices.table_total'); ?></th><th><?php echo app('translator')->get('fees.invoices.table_paid'); ?></th><th><?php echo app('translator')->get('fees.invoices.table_balance'); ?></th><th><?php echo app('translator')->get('fees.invoices.table_status'); ?></th><th class="text-end"><?php echo app('translator')->get('fees.invoices.table_actions'); ?></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge badge-soft-primary"><?php echo e($inv->number); ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar grad-<?php echo e($loop->index % 6 + 1); ?> avatar-sm"><?php echo e(mb_substr($inv->student?->full_name ?? $inv->parent?->name ?? '?', 0, 1)); ?></span>
                                <span><?php echo e($inv->student?->full_name ?? $inv->parent?->name ?? '—'); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($inv->date?->format('Y-m-d')); ?></td>
                        <td class="num"><?php echo e(number_format($inv->total)); ?></td>
                        <td class="num text-success"><?php echo e(number_format($inv->paid)); ?></td>
                        <td class="num <?php echo e($inv->balance > 0 ? 'text-danger fw-bold' : ''); ?>"><?php echo e(number_format($inv->balance)); ?></td>
                        <td><span class="badge badge-soft-<?php echo e($inv->state === 'paid' ? 'success' : ($inv->state === 'open' ? 'warning' : 'secondary')); ?>"><?php echo e($inv->state); ?></span></td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <?php if($inv->state === 'open' && $inv->balance > 0): ?>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#payModal<?php echo e($inv->id); ?>"><i class="bi bi-cash-coin me-1"></i> <?php echo app('translator')->get('fees.invoices.pay_button'); ?></button>
                                <?php endif; ?>
                                <a href="<?php echo e(route('admin.fees.invoices.pdf', $inv)); ?>" class="btn btn-sm btn-outline-primary" title="<?php echo app('translator')->get('fees.invoices.download_pdf'); ?>"><i class="bi bi-file-earmark-pdf"></i></a>
                            </div>
                        </td>
                    </tr>

                    <?php if($inv->state === 'open' && $inv->balance > 0): ?>
                        <div class="modal fade" id="payModal<?php echo e($inv->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="<?php echo e(route('admin.fees.invoices.pay', $inv)); ?>" class="modal-content">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title"><?php echo app('translator')->get('fees.invoices.pay_modal_title', ['number' => $inv->number]); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label"><?php echo app('translator')->get('fees.invoices.student_label'); ?></label>
                                            <input type="text" class="form-control" value="<?php echo e($inv->student?->full_name ?? $inv->parent?->name); ?>" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label"><?php echo app('translator')->get('fees.invoices.remaining_amount'); ?></label>
                                            <input type="text" class="form-control num" value="<?php echo e(number_format($inv->balance)); ?>" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label"><?php echo app('translator')->get('fees.invoices.paid_amount'); ?></label>
                                            <input type="number" name="amount" class="form-control num" step="0.01" min="0.01" max="<?php echo e($inv->balance); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label"><?php echo app('translator')->get('fees.invoices.payment_method'); ?></label>
                                            <select name="method" class="form-select" required>
                                                <?php $__currentLoopData = ['cash' => 'method_cash', 'card' => 'method_card', 'transfer' => 'method_transfer', 'wallet' => 'method_wallet']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($val); ?>"><?php echo e(__("fees.invoices.$label")); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label"><?php echo app('translator')->get('fees.invoices.date_label'); ?></label>
                                            <input type="date" name="date" class="form-control" value="<?php echo e(today()->toDateString()); ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo app('translator')->get('fees.invoices.cancel'); ?></button>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> <?php echo app('translator')->get('fees.invoices.confirm_payment'); ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8"><div class="empty-state"><i class="bi bi-receipt"></i><p><?php echo app('translator')->get('fees.invoices.empty_title'); ?></p><small><?php echo app('translator')->get('fees.invoices.empty_subtitle'); ?></small></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($invoices->hasPages()): ?><div class="card-footer"><?php echo e($invoices->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/fees/invoices.blade.php ENDPATH**/ ?>