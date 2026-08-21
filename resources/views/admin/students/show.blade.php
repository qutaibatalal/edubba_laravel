@extends('admin.layouts.app')

@section('title', __('students.show.title'))
@section('page', __('students.show.page', ['name' => $student->full_name]))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">{{ $student->full_name }}</h1>
        <p>@lang('students.show.subtitle', ['code' => $student->student_code])</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> @lang('students.show.edit')</a>
    </div>
</div>

<div class="card mb-3" style="max-width:820px">
    <div class="card-body p-4">

        <form>
            {{-- Student Photo --}}
            <div class="text-center mb-4">
                @if ($student->photo)
                    <img src="{{ $student->photo }}" alt="Student Photo" style="width:100px;height:100px;border-radius:14px;object-fit:cover;max-width:100%">
                @else
                    <div style="width:100px;height:100px;border-radius:14px;background:var(--edb-bg);border:2px dashed var(--edb-border-strong);display:grid;place-items:center;overflow:hidden;max-width:100%">
                        <i class="bi bi-person-fill text-muted" style="font-size:2.5rem"></i>
                    </div>
                @endif
            </div>

            {{-- Basic Information --}}
            <h6 class="text-primary fw-bold mb-3">@lang('students.show.basic_info')</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.name')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->full_name }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.student_code')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->student_code }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.state')</label>
                    <div class="form-control bg-transparent border-secondary text-white">
                        <span class="badge bg-{{ $student->state === 'admitted' ? 'success' : ($student->state === 'graduated' ? 'primary' : ($student->state === 'alumni' ? 'purple' : 'secondary')) }}">
                            {{ $student->state }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.gender')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->gender }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.birth_date')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->birth_date?->format('Y-m-d') ?? '—' }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.phone')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->phone ?? '—' }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.mobile')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->mobile ?? '—' }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.email')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->email ?? '—' }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.city')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->city ?? '—' }}</div>
                </div>
                <div class="col-12">
                    <label class="form-label">@lang('students.show.address')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->address ?? '—' }}</div>
                </div>
            </div>

            {{-- Academic Information --}}
            <h6 class="text-primary fw-bold mb-3">@lang('students.show.academic_info')</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.batch')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->batch?->name ?? '—' }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.program')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->program?->name ?? '—' }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">@lang('students.show.year')</label>
                    <div class="form-control bg-transparent border-secondary text-white">{{ $student->academicYear?->name ?? '—' }}</div>
                </div>
            </div>

            {{-- Parent Information --}}
            <h6 class="text-primary fw-bold mb-3">@lang('students.show.parent_info')</h6>
            <div class="review-box border rounded-3 p-3 mb-4" style="background:var(--edb-bg)">
                @if ($student->parent)
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ $student->parent->name }}</div>
                            <div class="text-secondary small">{{ $student->parent->phone ?? $student->parent->mobile ?? '—' }}</div>
                        </div>
                        <span class="badge badge-soft-primary">@lang('students.show.primary_guardian')</span>
                    </div>
                @else
                    <div class="text-secondary">@lang('students.show.no_parent')</div>
                @endif
            </div>

            {{-- Courses --}}
            <h6 class="text-primary fw-bold mb-3">@lang('students.show.courses')</h6>
            <div class="review-box border rounded-3 p-3 mb-4" style="background:var(--edb-bg)">
                @forelse ($student->courses as $c)
                    <div class="mb-2">
                        <span class="badge bg-primary bg-opacity-25 text-primary">{{ $c->name }}</span>
                    </div>
                @empty
                    <div class="text-secondary">@lang('students.show.no_courses')</div>
                @endforelse
            </div>

            {{-- API Account Information --}}
            <h6 class="text-primary fw-bold mb-3">@lang('students.show.api_info')</h6>
            <div class="review-box border rounded-3 p-3 mb-4" style="background:var(--edb-bg)">
                @if ($student->apiUser)
                    <div class="mb-2">
                        <div class="form-label">@lang('students.show.username')</div>
                        <div class="form-control form-control-sm bg-transparent border-secondary text-white">{{ $student->apiUser->username }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="form-label">@lang('students.show.password')</div>
                        <div class="form-control form-control-sm bg-transparent border-secondary text-white">{{ $student->apiUser->last_known_password }}</div>
                    </div>
                @else
                    <div class="text-secondary">@lang('students.show.no_api_account')</div>
                @endif
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('students.form.back_to_list')</a>
            </div>
        </form>
    </div>
</div>
@endsection('content')

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        var btn = event.target;
        var originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check me-1"></i>تم النسخ';
        setTimeout(function() { btn.innerHTML = originalText; }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
    });
}

function resetStudentPassword(studentId) {
    var newPassword = prompt('أدخل كلمة المرور الجديدة (6 أحرف على الأقل):');
    if (!newPassword || newPassword.length < 6) {
        alert('كلمة المرور يجب أن تكون 6 أحرف على الأقل');
        return;
    }
    if (!confirm('هل أنت متأكد من إعادة تعيين كلمة المرور؟')) {
        return;
    }

    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/users/reset-password/' + studentId;

    var tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = '{{ csrf_token() }}';
    form.appendChild(tokenInput);

    var pwInput = document.createElement('input');
    pwInput.type = 'hidden';
    pwInput.name = 'new_password';
    pwInput.value = newPassword;
    form.appendChild(pwInput);

    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush