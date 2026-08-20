@extends('admin.layouts.app')

@section('title', $student ? __('students.form.title_edit') : __('students.form.title_new'))
@section('page', $student ? __('students.form.page_edit', ['name' => $student->full_name]) : __('students.form.page_new'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $student ? __('students.form.title_edit') : __('students.form.title_new') }}</h1>
        <p>@lang('students.form.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('students.form.back_to_list')</a>
    </div>
</div>

<div class="card" style="max-width:820px">
    <div class="card-body p-4">

        {{-- Stepper --}}
        <div class="stepper mb-4">
            <div class="stepper-item active" data-step="0"><span class="stepper-num">1</span><span class="stepper-label">@lang('students.form.step_personal')</span></div>
            <div class="stepper-line"></div>
            <div class="stepper-item" data-step="1"><span class="stepper-num">2</span><span class="stepper-label">@lang('students.form.step_academic')</span></div>
            <div class="stepper-line"></div>
            <div class="stepper-item" data-step="2"><span class="stepper-num">3</span><span class="stepper-label">@lang('students.form.step_parent')</span></div>
            <div class="stepper-line"></div>
            <div class="stepper-item" data-step="3"><span class="stepper-num">4</span><span class="stepper-label">@lang('students.form.step_review')</span></div>
        </div>

        <form method="POST" action="{{ $student ? route('admin.students.update', $student) : route('admin.students.store') }}" id="studentWizard" enctype="multipart/form-data">
            @csrf
            @if ($student) @method('PUT') @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- STEP 1: Personal --}}
            <div class="step-pane">
                <h6 class="text-primary fw-bold mb-3">@lang('students.form.step_personal')</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">@lang('students.form.photo')</label>
                        <div class="d-flex align-items-center gap-3">
                            <div id="photoPreview" style="width:80px;height:80px;border-radius:14px;background:var(--edb-bg);border:2px dashed var(--edb-border-strong);display:grid;place-items:center;overflow:hidden;flex-shrink:0">
                                @if ($student?->photo)
                                    <img src="{{ $student->photo }}" alt="" style="width:100%;height:100%;object-fit:cover">
                                @else
                                    <i class="bi bi-person-fill text-muted" style="font-size:1.6rem"></i>
                                @endif
                            </div>
                            <div>
                                <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png" class="d-none" onchange="previewPhoto(this)">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('photoInput').click()"><i class="bi bi-camera me-1"></i> @lang('students.form.choose_photo')</button>
                                <div class="text-muted small mt-1">JPEG/PNG, max 5MB</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">@lang('students.form.first_name')</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $student?->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">@lang('students.form.father_name')</label>
                        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $student?->middle_name) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">@lang('students.form.family_name')</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $student?->last_name) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('students.form.gender')</label>
                        <select name="gender" class="form-select">
                            <option value="male" {{ old('gender', $student?->gender) === 'male' ? 'selected' : '' }}>@lang('students.form.gender_male')</option>
                            <option value="female" {{ old('gender', $student?->gender) === 'female' ? 'selected' : '' }}>@lang('students.form.gender_female')</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('students.form.birth_date')</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $student?->birth_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('students.form.national_id')</label>
                        <input type="text" name="national_id" class="form-control" value="{{ old('national_id', $student?->national_id) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('students.form.phone')</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $student?->phone) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">@lang('students.form.mobile')</label>
                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $student?->mobile) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">@lang('students.form.email')</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $student?->email) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">@lang('students.form.city')</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $student?->city) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">@lang('students.form.address')</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $student?->address) }}">
                    </div>
                </div>
            </div>

            {{-- STEP 2: Academic --}}
            <div class="step-pane d-none">
                <h6 class="text-primary fw-bold mb-3">@lang('students.form.step_academic')</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">@lang('students.form.batch')</label>
                        <select name="batch_id" class="form-select">
                            <option value="">—</option>
                            @foreach ($batches as $b)
                                <option value="{{ $b->id }}" {{ old('batch_id', $student?->batch_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">@lang('students.form.program')</label>
                        <select name="program_id" class="form-select">
                            <option value="">—</option>
                            @foreach ($programs as $p)
                                <option value="{{ $p->id }}" {{ old('program_id', $student?->program_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">@lang('students.form.academic_year')</label>
                        <select name="academic_year_id" class="form-select">
                            <option value="">—</option>
                            @foreach ($years as $y)
                                <option value="{{ $y->id }}" {{ old('academic_year_id', $student?->academic_year_id) == $y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('students.form.state')</label>
                        <select name="state" class="form-select">
                            @foreach (['draft','admitted','graduated','alumni'] as $st)
                                <option value="{{ $st }}" {{ old('state', $student?->state) === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('students.form.admission_date')</label>
                        <input type="date" name="admission_date" class="form-control" value="{{ old('admission_date', $student?->admission_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('students.form.student_code')</label>
                        <input type="text" name="student_code" class="form-control" placeholder="@lang('students.form.student_code_placeholder')" value="{{ old('student_code', $student?->student_code) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('students.form.roll_no')</label>
                        <input type="text" name="roll_no" class="form-control" placeholder="@lang('students.form.student_code_placeholder')" value="{{ old('roll_no', $student?->roll_no) }}">
                    </div>
                </div>
            </div>

            {{-- STEP 3: Parent --}}
            <div class="step-pane d-none">
                <h6 class="text-primary fw-bold mb-3">@lang('students.form.step_parent')</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">@lang('students.form.parent_from_list')</label>
                        <select name="parent_id" class="form-select">
                            <option value="">@lang('students.form.parent_select_placeholder')</option>
                            @foreach ($parents as $p)
                                <option value="{{ $p->id }}" {{ old('parent_id', $student?->parent_id) == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->mobile ?? '—' }})</option>
                            @endforeach
                        </select>
                        <div class="small text-secondary mt-1">@lang('students.form.add_parent_hint', ['link' => '<a href="' . route('admin.parents.create') . '">' . __('students.form.parents_page') . '</a>'])</div>
                    </div>
                </div>
            </div>

            {{-- STEP 4: Review --}}
            <div class="step-pane d-none">
                <h6 class="text-primary fw-bold mb-3">@lang('students.form.review_title')</h6>
                <div class="review-box border rounded-3 p-3" style="background:var(--edb-bg)">
                    <div class="row g-2" id="reviewSummary">
                        <div class="col-12 text-secondary">@lang('students.form.review_hint')</div>
                    </div>
                </div>
                <div class="form-check mt-3">
                    <input type="checkbox" class="form-check-input" id="createApiAccount" name="create_api_account" value="1" checked>
                    <label class="form-check-label" for="createApiAccount">@lang('students.form.create_api_account')</label>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2 justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-secondary" id="wizPrev" onclick="wizardPrev()" style="display:none"><i class="bi bi-arrow-right me-1"></i> @lang('students.form.previous')</button>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">@lang('students.form.cancel')</a>
                    <button type="button" class="btn btn-primary" id="wizNext" onclick="wizardNext()">@lang('students.form.next') <i class="bi bi-arrow-left me-1"></i></button>
                    <button type="submit" class="btn btn-success px-4" id="wizSubmit" style="display:none"><i class="bi bi-check-lg me-1"></i> @lang('students.form.save_student')</button>
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

@push('scripts')
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
            wizLabel('{{ __('students.form.review_name') }}', [g('name'), g('middle_name'), g('last_name')].filter(Boolean).join(' ')) +
            wizLabel('{{ __('students.form.review_gender') }}', g('gender') === 'male' ? '{{ __('students.form.gender_male') }}' : g('gender') === 'female' ? '{{ __('students.form.gender_female') }}' : '') +
            wizLabel('{{ __('students.form.review_birth_date') }}', g('birth_date')) +
            wizLabel('{{ __('students.form.review_mobile') }}', g('mobile') || g('phone')) +
            wizLabel('{{ __('students.form.review_batch') }}', sel('batch_id')) +
            wizLabel('{{ __('students.form.review_program') }}', sel('program_id')) +
            wizLabel('{{ __('students.form.review_year') }}', sel('academic_year_id')) +
            wizLabel('{{ __('students.form.review_parent') }}', sel('parent_id')) +
            wizLabel('{{ __('students.form.review_state') }}', g('state'));
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
        @if ($errors->any())
            var errFields = @json(array_keys($errors->toArray()));
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
        @endif
    })();
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            var r = new FileReader();
            r.onload = function(e) {
                document.getElementById('photoPreview').innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover">';
            };
            r.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
