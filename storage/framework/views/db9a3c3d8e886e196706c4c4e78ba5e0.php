<?php $__env->startSection('title', $course ? __('courses.form.edit_course') : __('courses.form.new_course')); ?>
<?php $__env->startSection('page', $course ? __('courses.form.page_edit', ['name' => $course->name]) : __('courses.form.page_create')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo e($course ? __('courses.form.page_edit', ['name' => $course->name]) : __('courses.form.page_create')); ?></h1>
        <p><?php echo app('translator')->get('courses.form.subtitle'); ?></p>
    </div>
</div>

<div class="card hoverable" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e($course ? route('admin.courses.update', $course) : route('admin.courses.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if($course): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('courses.form.label_name'); ?></label>
                    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $course?->name)); ?>" required>
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
                    <label class="form-label"><?php echo app('translator')->get('courses.form.label_code'); ?></label>
                    <input type="text" name="code" class="form-control" value="<?php echo e(old('code', $course?->code)); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('courses.form.label_subject'); ?></label>
                    <select name="subject_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s->id); ?>" <?php echo e(old('subject_id', $course?->subject_id) == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('courses.form.label_batch'); ?></label>
                    <select name="batch_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>" <?php echo e(old('batch_id', $course?->batch_id) == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('courses.form.label_program'); ?></label>
                    <select name="program_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>" <?php echo e(old('program_id', $course?->program_id) == $p->id ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('courses.form.label_academic_year'); ?></label>
                    <select name="academic_year_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($y->id); ?>" <?php echo e(old('academic_year_id', $course?->academic_year_id) == $y->id ? 'selected' : ''); ?>><?php echo e($y->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('courses.form.label_teacher'); ?></label>
                    <select name="faculty_id" class="form-select">
                        <option value="">—</option>
                        <?php $__currentLoopData = $faculty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($f->id); ?>" <?php echo e(old('faculty_id', $course?->faculty_id) == $f->id ? 'selected' : ''); ?>><?php echo e($f->full_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo app('translator')->get('courses.form.label_credit_hours'); ?></label>
                    <input type="number" name="credit_hours" class="form-control" value="<?php echo e(old('credit_hours', $course?->credit_hours)); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo app('translator')->get('courses.form.label_syllabus'); ?></label>
                    <textarea name="syllabus" class="form-control" rows="3"><?php echo e(old('syllabus', $course?->syllabus)); ?></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> <?php echo app('translator')->get('courses.form.save'); ?></button>
                <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn btn-outline-secondary"><?php echo app('translator')->get('courses.form.cancel'); ?></a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/courses/form.blade.php ENDPATH**/ ?>