<?php $__env->startSection('title', __('fees.structure_form.title')); ?>
<?php $__env->startSection('page', __('fees.structure_form.page')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo app('translator')->get('fees.structure_form.h1'); ?></h1>
        <p class="text-secondary mb-0"><?php echo app('translator')->get('fees.structure_form.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.fees.structures')); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> <?php echo app('translator')->get('fees.structure_form.back'); ?></a>
    </div>
</div>

<div class="card hoverable" style="max-width:820px">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e(route('admin.fees.structures.store')); ?>" id="feeForm">
            <?php echo csrf_field(); ?>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('fees.structure_form.name_label'); ?></label>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('fees.structure_form.academic_year'); ?></label>
                    <select name="academic_year_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($y->id); ?>"><?php echo e($y->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('fees.structure_form.batch'); ?></label>
                    <select name="batch_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>"><?php echo e($b->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('fees.structure_form.program'); ?></label>
                    <select name="program_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <h6 class="fw-bold d-flex align-items-center gap-2 mb-3"><i class="bi bi-list-check text-primary"></i> <?php echo app('translator')->get('fees.structure_form.fee_lines'); ?></h6>
            <div id="lines">
                <div class="row g-2 mb-2 line-row">
                    <div class="col"><input type="text" name="lines[0][name]" class="form-control" placeholder="<?php echo app('translator')->get('fees.structure_form.line_name_placeholder'); ?>" required></div>
                    <div class="col"><input type="number" step="0.01" name="lines[0][amount]" class="form-control" placeholder="<?php echo app('translator')->get('fees.structure_form.amount_placeholder'); ?>" required></div>
                    <div class="col-auto" style="width:140px">
                        <select name="lines[0][type]" class="form-select">
                            <option value=""><?php echo app('translator')->get('fees.structure_form.type_label'); ?></option>
                            <option value="one_time"><?php echo app('translator')->get('fees.structure_form.type_one_time'); ?></option>
                            <option value="recurring"><?php echo app('translator')->get('fees.structure_form.type_recurring'); ?></option>
                        </select>
                    </div>
                    <div class="col-auto"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.line-row').remove()"><i class="bi bi-trash"></i></button></div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary mb-4" onclick="addLine()"><i class="bi bi-plus-lg me-1"></i> <?php echo app('translator')->get('fees.structure_form.add_line'); ?></button>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> <?php echo app('translator')->get('fees.structure_form.create'); ?></button>
                <a href="<?php echo e(route('admin.fees.structures')); ?>" class="btn btn-outline-secondary"><?php echo app('translator')->get('fees.structure_form.cancel'); ?></a>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let idx = 1;
function addLine() {
    const div = document.createElement('div');
    div.className = 'row g-2 mb-2 line-row';
    div.innerHTML = `
        <div class="col"><input type="text" name="lines[`+idx+`][name]" class="form-control" placeholder="<?php echo e(__('fees.structure_form.line_name_placeholder')); ?>" required></div>
        <div class="col"><input type="number" step="0.01" name="lines[`+idx+`][amount]" class="form-control" placeholder="<?php echo e(__('fees.structure_form.amount_placeholder')); ?>" required></div>
        <div class="col-auto" style="width:140px"><select name="lines[`+idx+`][type]" class="form-select"><option value=""><?php echo e(__('fees.structure_form.type_label')); ?></option><option value="one_time"><?php echo e(__('fees.structure_form.type_one_time')); ?></option><option value="recurring"><?php echo e(__('fees.structure_form.type_recurring')); ?></option></select></div>
        <div class="col-auto"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.line-row').remove()"><i class="bi bi-trash"></i></button></div>`;
    document.getElementById('lines').appendChild(div);
    idx++;
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/fees/structure-form.blade.php ENDPATH**/ ?>