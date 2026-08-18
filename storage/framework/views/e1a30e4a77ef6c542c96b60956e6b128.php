<?php $__env->startSection('title', $student ? __('students.form.title_edit') : __('students.form.title_new')); ?>
<?php $__env->startSection('page', $student ? __('students.form.page_edit', ['name' => $student->full_name]) : __('students.form.page_new')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1 class="fw-bold"><?php echo e($student ? __('students.form.title_edit') : __('students.form.title_new')); ?></h1>
        <p><?php echo app('translator')->get('students.form.subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> <?php echo app('translator')->get('students.form.back_to_list'); ?></a>
    </div>
</div>

<div class="card" style="max-width:820px">
    <div class="card-body p-4">

        
        <div class="stepper mb-4">
            <div class="stepper-item active" data-step="0"><span class="stepper-num">1</span><span class="stepper-label"><?php echo app('translator')->get('students.form.step_personal'); ?></span></div>
            <div class="stepper-line"></div>
            <div class="stepper-item" data-step="1"><span class="stepper-num">2</span><span class="stepper-label"><?php echo app('translator')->get('students.form.step_academic'); ?></span></div>
            <div class="stepper-line"></div>
            <div class="stepper-item" data-step="2"><span class="stepper-num">3</span><span class="stepper-label"><?php echo app('translator')->get('students.form.step_parent'); ?></span></div>
            <div class="stepper-line"></div>
            <div class="stepper-item" data-step="3"><span class="stepper-num">4</span><span class="stepper-label"><?php echo app('translator')->get('students.form.step_review'); ?></span></div>
        </div>

        <form method="POST" action="<?php echo e($student ? route('admin.students.update', $student) : route('admin.students.store')); ?>" id="studentWizard">
            <?php echo csrf_field(); ?>
            <?php if($student): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            
            <div class="step-pane">
                <h6 class="text-primary fw-bold mb-3"><?php echo app('translator')->get('students.form.step_personal'); ?></h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label"><?php echo app('translator')->get('students.form.first_name'); ?></label>
                        <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $student?->name)); ?>" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?php echo app('translator')->get('students.form.father_name'); ?></label>
                        <input type="text" name="middle_name" class="form-control" value="<?php echo e(old('middle_name', $student?->middle_name)); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?php echo app('translator')->get('students.form.family_name'); ?></label>
                        <input type="text" name="last_name" class="form-control" value="<?php echo e(old('last_name', $student?->last_name)); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><?php echo app('translator')->get('students.form.gender'); ?></label>
                        <select name="gender" class="form-select">
                            <option value="male" <?php echo e(old('gender', $student?->gender) === 'male' ? 'selected' : ''); ?>><?php echo app('translator')->get('students.form.gender_male'); ?></option>
                            <option value="female" <?php echo e(old('gender', $student?->gender) === 'female' ? 'selected' : ''); ?>><?php echo app('translator')->get('students.form.gender_female'); ?></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><?php echo app('translator')->get('students.form.birth_date'); ?></label>
                        <input type="date" name="birth_date" class="form-control" value="<?php echo e(old('birth_date', $student?->birth_date?->format('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><?php echo app('translator')->get('students.form.national_id'); ?></label>
                        <input type="text" name="national_id" class="form-control" value="<?php echo e(old('national_id', $student?->national_id)); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><?php echo app('translator')->get('students.form.phone'); ?></label>
                        <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $student?->phone)); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?php echo app('translator')->get('students.form.mobile'); ?></label>
                        <input type="text" name="mobile" class="form-control" value="<?php echo e(old('mobile', $student?->mobile)); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?php echo app('translator')->get('students.form.email'); ?></label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $student?->email)); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?php echo app('translator')->get('students.form.city'); ?></label>
                        <input type="text" name="city" class="form-control" value="<?php echo e(old('city', $student?->city)); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label"><?php echo app('translator')->get('students.form.address'); ?></label>
                        <input type="text" name="address" class="form-control" value="<?php echo e(old('address', $student?->address)); ?>">
                    </div>
                </div>
            </div>

            
            <div class="step-pane d-none">
                <h6 class="text-primary fw-bold mb-3"><?php echo app('translator')->get('students.form.step_academic'); ?></h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label"><?php echo app('translator')->get('students.form.batch'); ?></label>
                        <select name="batch_id" class="form-select">
                            <option value="">—</option>
                            <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($b->id); ?>" <?php echo e(old('batch_id', $student?->batch_id) == $b->id ? 'selected' : ''); ?>><?php echo e($b->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?php echo app('translator')->get('students.form.program'); ?></label>
                        <select name="program_id" class="form-select">
                            <option value="">—</option>
                            <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>" <?php echo e(old('program_id', $student?->program_id) == $p->id ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><?php echo app('translator')->get('students.form.academic_year'); ?></label>
                        <select name="academic_year_id" class="form-select">
                            <option value="">—</option>
                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($y->id); ?>" <?php echo e(old('academic_year_id', $student?->academic_year_id) == $y->id ? 'selected' : ''); ?>><?php echo e($y->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><?php echo app('translator')->get('students.form.state'); ?></label>
                        <select name="state" class="form-select">
                            <?php $__currentLoopData = ['draft','admitted','graduated','alumni']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($st); ?>" <?php echo e(old('state', $student?->state) === $st ? 'selected' : ''); ?>><?php echo e($st); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><?php echo app('translator')->get('students.form.admission_date'); ?></label>
                        <input type="date" name="admission_date" class="form-control" value="<?php echo e(old('admission_date', $student?->admission_date?->format('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><?php echo app('translator')->get('students.form.student_code'); ?></label>
                        <input type="text" name="student_code" class="form-control" placeholder="<?php echo app('translator')->get('students.form.student_code_placeholder'); ?>" value="<?php echo e(old('student_code', $student?->student_code)); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><?php echo app('translator')->get('students.form.roll_no'); ?></label>
                        <input type="text" name="roll_no" class="form-control" placeholder="<?php echo app('translator')->get('students.form.student_code_placeholder'); ?>" value="<?php echo e(old('roll_no', $student?->roll_no)); ?>">
                    </div>
                </div>
            </div>

            
            <div class="step-pane d-none">
                <h6 class="text-primary fw-bold mb-3"><?php echo app('translator')->get('students.form.step_parent'); ?></h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label"><?php echo app('translator')->get('students.form.parent_from_list'); ?></label>
                        <select name="parent_id" class="form-select">
                            <option value=""><?php echo app('translator')->get('students.form.parent_select_placeholder'); ?></option>
                            <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>" <?php echo e(old('parent_id', $student?->parent_id) == $p->id ? 'selected' : ''); ?>><?php echo e($p->name); ?> (<?php echo e($p->mobile ?? '—'); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="small text-secondary mt-1"><?php echo app('translator')->get('students.form.add_parent_hint', ['link' => '<a href="' . route('admin.parents.create') . '">' . __('students.form.parents_page') . '</a>']); ?></div>
                    </div>
                </div>
            </div>

            
            <div class="step-pane d-none">
                <h6 class="text-primary fw-bold mb-3"><?php echo app('translator')->get('students.form.review_title'); ?></h6>
                <div class="review-box border rounded-3 p-3" style="background:var(--edb-bg)">
                    <div class="row g-2" id="reviewSummary">
                        <div class="col-12 text-secondary"><?php echo app('translator')->get('students.form.review_hint'); ?></div>
                    </div>
                </div>
                <div class="form-check mt-3">
                    <input type="checkbox" class="form-check-input" id="createApiAccount" name="create_api_account" value="1" checked>
                    <label class="form-check-label" for="createApiAccount"><?php echo app('translator')->get('students.form.create_api_account'); ?></label>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2 justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-secondary" id="wizPrev" onclick="wizardPrev()" style="display:none"><i class="bi bi-arrow-right me-1"></i> <?php echo app('translator')->get('students.form.previous'); ?></button>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-outline-secondary"><?php echo app('translator')->get('students.form.cancel'); ?></a>
                    <button type="button" class="btn btn-primary" id="wizNext" onclick="wizardNext()"><?php echo app('translator')->get('students.form.next'); ?> <i class="bi bi-arrow-left me-1"></i></button>
                    <button type="submit" class="btn btn-success px-4" id="wizSubmit" style="display:none"><i class="bi bi-check-lg me-1"></i> <?php echo app('translator')->get('students.form.save_student'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.stepper { display: flex; align-items: center; gap: 8px; }
.stepper-item { display: flex; align-items: center; gap: 8px; color: var(--edb-text-3); font-weight: 600; font-size: .82rem; white-space: nowrap; }
.stepper-item.active { color: var(--edb-primary); }
.stepper-item.active .stepper-num { background: var(--edb-primary); border-color: var(--edb-primary); color: #fff; }
.stepper-num { width: 28px; height: 28px; border-radius: 50%; display: grid; place-items: center; border: 2px solid var(--edb-border-strong); font-size: .78rem; font-weight: 800; }
.stepper-line { flex: 1; height: 2px; background: var(--edb-border-strong); border-radius: 2px; }
@media (max-width: 700px) { .stepper-label { display: none; } }
.review-box .row > div { font-size: .85rem; }
</style>

<?php $__env->startPush('scripts'); ?>
<script>
    let wizStep = 0;
    const wizPanes = document.querySelectorAll('.step-pane');
    const wizItems = document.querySelectorAll('.stepper-item');

    function wizLabel(name, value) {
        return '<div class="col-md-6"><div class="d-flex justify-content-between border-bottom pb-1"><span class="text-secondary">' + name + '</span><b>' + (value || '—') + '</b></div></div>';
    }
    function renderReview() {
        const f = document.getElementById('studentWizard');
        const g = (n) => f.elements[n] ? f.elements[n].value : '';
        const sel = (n) => f.elements[n] ? (f.elements[n].options[f.elements[n].selectedIndex]?.text || '') : '';
        document.getElementById('reviewSummary').innerHTML =
            wizLabel('<?php echo e(__('students.form.review_name')); ?>', [g('name'), g('middle_name'), g('last_name')].filter(Boolean).join(' ')) +
            wizLabel('<?php echo e(__('students.form.review_gender')); ?>', g('gender') === 'male' ? '<?php echo e(__('students.form.gender_male')); ?>' : g('gender') === 'female' ? '<?php echo e(__('students.form.gender_female')); ?>' : '') +
            wizLabel('<?php echo e(__('students.form.review_birth_date')); ?>', g('birth_date')) +
            wizLabel('<?php echo e(__('students.form.review_mobile')); ?>', g('mobile') || g('phone')) +
            wizLabel('<?php echo e(__('students.form.review_batch')); ?>', sel('batch_id')) +
            wizLabel('<?php echo e(__('students.form.review_program')); ?>', sel('program_id')) +
            wizLabel('<?php echo e(__('students.form.review_year')); ?>', sel('academic_year_id')) +
            wizLabel('<?php echo e(__('students.form.review_parent')); ?>', sel('parent_id')) +
            wizLabel('<?php echo e(__('students.form.review_state')); ?>', g('state'));
    }
    function wizardShow(step) {
        wizPanes.forEach((p, i) => p.classList.toggle('d-none', i !== step));
        wizItems.forEach((it, i) => it.classList.toggle('active', i <= step));
        document.getElementById('wizPrev').style.display = step === 0 ? 'none' : '';
        document.getElementById('wizNext').style.display = step === 3 ? 'none' : '';
        document.getElementById('wizSubmit').style.display = step === 3 ? '' : 'none';
        wizStep = step;
        if (step === 3) renderReview();
    }
    function wizardNext() { wizardShow(Math.min(wizStep + 1, 3)); }
    function wizardPrev() { wizardShow(Math.max(wizStep - 1, 0)); }

    (function () {
        <?php if($errors->any()): ?>
            var errFields = <?php echo json_encode(array_keys($errors->toArray()), 15, 512) ?>;
            var stepMap = {
                'name': 0, 'middle_name': 0, 'last_name': 0, 'gender': 0,
                'birth_date': 0, 'national_id': 0, 'phone': 0, 'mobile': 0,
                'email': 0, 'address': 0, 'city': 0,
                'batch_id': 1, 'program_id': 1, 'academic_year_id': 1,
                'state': 1, 'admission_date': 1, 'student_code': 1, 'roll_no': 1,
                'parent_id': 2
            };
            var minStep = 3;
            errFields.forEach(function (f) { if (stepMap[f] !== undefined && stepMap[f] < minStep) minStep = stepMap[f]; });
            wizardShow(minStep);
        <?php endif; ?>
    })();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\qut10\Desktop\edubba_laravel\resources\views/admin/students/form.blade.php ENDPATH**/ ?>