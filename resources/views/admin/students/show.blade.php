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
        <a href="{{ route('admin.students.card', $student) }}" class="btn btn-outline-primary" title="@lang('students.show.card_title')"><i class="bi bi-person-vcard me-1"></i> @lang('students.show.card')</a>
        <a href="{{ route('admin.students.certificate', $student) }}" class="btn btn-outline-primary" title="@lang('students.show.certificate_title')"><i class="bi bi-award me-1"></i> @lang('students.show.certificate')</a>
        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> @lang('students.show.edit')</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card hoverable">
            <div class="card-body text-center">
                @if ($student->photo)
                    <img src="{{ $student->photo }}" alt="" style="width:80px;height:80px;border-radius:14px;object-fit:cover" class="mx-auto mb-3 d-block">
                @else
                    <span class="avatar avatar-lg grad-1 mx-auto mb-3">{{ mb_substr($student->full_name, 0, 1) }}</span>
                @endif
                <h5 class="mb-1 fw-bold">{{ $student->full_name }}</h5>
                <div class="text-secondary small mb-3">{{ $student->student_code }}</div>
                <span class="badge badge-soft-{{ $student->state === 'admitted' ? 'success' : ($student->state === 'graduated' ? 'primary' : ($student->state === 'alumni' ? 'purple' : 'secondary')) }}">{{ $student->state }}</span>
                <hr>
                <div class="text-start small">
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('students.show.batch')</span><b>{{ $student->batch?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('students.show.program')</span><b>{{ $student->program?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('students.show.year')</span><b>{{ $student->academicYear?->name ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('students.show.gender')</span><b>{{ $student->gender }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('students.show.birth_date')</span><b>{{ $student->birth_date?->format('Y-m-d') ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('students.show.phone')</span><b>{{ $student->phone ?? '—' }}</b></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary">@lang('students.show.email')</span><b>{{ $student->email ?? '—' }}</b></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card hoverable">
            <div class="card-body text-center">
                @if ($student->apiUser)
                    <h6 class="fw-bold mb-3">@lang('students.show.api_credentials')</h6>
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-sm" value="{{ $student->apiUser->username }}" readonly>
                        <small class="text-muted">اسم المستخدم (اسم الطالب)</small>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control form-control-sm" value="{{ $student->apiUser->password }}" readonly>
                        <small class="text-muted">كلمة المرور</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('{{ $student->apiUser->username }}')" title="نسخ اسم المستخدم"><i class="bi bi-copy me-1"></i></button>
                        <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('{{ $student->apiUser->password }}')" title="نسخ كلمة المرور"><i class="bi bi-copy me-1"></i></button>
                        @if (auth()->user()->hasRole('admin'))
                            <button class="btn btn-sm btn-outline-danger" onclick="resetStudentPassword({{ $student->id }})" title="إعادة تعيين كلمة المرور"><i class="bi bi-arrow-counter-clockwise me-1"></i></button>
                        @endif
                    </div>
                    <small class="text-muted mt-2">يمكنك نسخ بيانات الدخول أو إعادة تعيين كلمة المرور من API</small>
                @else
                    <span class="text-secondary fw-bold">لا يوجد حساب API</span>
                    <p class="small text-muted">لتفعيل الحساب، عد إلى صفحة إنشاء الطالب وحدد "إنشاء حساب API"</p>
                @endif
    <div class="col-lg-8">
        <div class="card mb-3 hoverable">
            <div class="card-header fw-bold">@lang('students.show.parents_title')</div>
            <div class="card-body">
                @forelse ($student->parents as $p)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($p->name, 0, 1) }}</span>
                            <div><b>{{ $p->name }}</b><div class="small text-secondary">{{ $p->phone ?? $p->mobile }} — {{ $p->pivot->relation }}</div></div>
                        </div>
                        <span class="badge badge-soft-{{ $p->pivot->is_main ? 'primary' : 'secondary' }}">{{ $p->pivot->is_main ? __('students.show.main_guardian') : __('students.show.additional_guardian') }}</span>
                    </div>
                @empty
                    <div class="empty-state py-4"><i class="bi bi-person-hearts"></i><p>@lang('students.show.no_parents')</p></div>
                @endforelse
            </div>
        </div>

        <div class="card mb-3 hoverable">
            <div class="card-header fw-bold">@lang('students.show.courses_title')</div>
            <div class="card-body">
                @forelse ($student->courses as $c)
                    <span class="badge badge-soft-info me-1 mb-1">{{ $c->name }}</span>
                @empty
                    <div class="empty-state py-4"><i class="bi bi-book"></i><p>@lang('students.show.no_courses')</p></div>
                @endforelse
            </div>
        </div>

        <div class="card mb-3 hoverable">
            <div class="card-header fw-bold">@lang('students.show.invoices_title')</div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th>@lang('students.show.th_number')</th><th>@lang('students.show.th_date')</th><th>@lang('students.show.th_total')</th><th>@lang('students.show.th_balance')</th><th>@lang('students.show.th_state')</th></tr></thead>
                    <tbody>
                        @forelse ($student->invoices as $inv)
                            <tr>
                                <td>{{ $inv->number }}</td>
                                <td>{{ $inv->date?->format('Y-m-d') }}</td>
                                <td>{{ number_format($inv->total) }}</td>
                                <td>{{ number_format($inv->balance) }}</td>
                                <td><span class="badge badge-soft-{{ $inv->state === 'paid' ? 'success' : ($inv->state === 'open' ? 'warning' : 'secondary') }}">{{ $inv->state }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-receipt"></i><p>@lang('students.show.no_invoices')</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">@lang('students.show.attendance_title')</span>
                <span class="badge badge-soft-{{ $attendancePercentage >= 90 ? 'success' : ($attendancePercentage >= 75 ? 'primary' : 'danger') }}">
                    @lang('students.show.attendance_percentage', ['percent' => $attendancePercentage])
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th>@lang('students.show.th_date')</th><th>@lang('students.show.th_subject')</th><th>@lang('students.show.th_status')</th></tr></thead>
                    <tbody>
                        @forelse ($attendance as $line)
                            <tr>
                                <td>{{ $line->sheet?->date?->format('Y-m-d') }}</td>
                                <td>{{ $line->sheet?->course?->name ?? '—' }}</td>
                                <td>
                                    @php $st = ['present' => 'success', 'absent' => 'danger', 'late' => 'warning', 'leave' => 'info']; @endphp
                                    <span class="badge badge-soft-{{ $st[$line->status] ?? 'secondary' }}">{{ $line->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3"><div class="empty-state"><i class="bi bi-calendar-check"></i><p>@lang('students.show.no_attendance')</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        var btn = event.target;
        var originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check me-1"></i>已复制';
        setTimeout(function() { btn.innerHTML = originalText; }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
    });
}

function resetStudentPassword(studentId) {
    if (!confirm('هل أنت متأكد من إعادة تعيين كلمة المرور للطالب؟')) {
        return;
    }

    // Show loading state on the reset button
    var buttons = document.querySelectorAll('.btn-outline-danger[onclick*="resetStudentPassword"]');
    buttons.forEach(function(btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> جاري المعالجة...';
    });

    fetch('/api/v1/admin/students/' + studentId + '/reset-password', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Update the password field with the new password
            var passwordInput = document.querySelector('input[value*="password"]');
            if (passwordInput) {
                passwordInput.value = data.data.new_password;
            }
            // Show success feedback
            alert('تم إعادة تعيين كلمة المرور successfully');
            // Refresh the page to show new password
            location.reload();
        } else {
            alert('حدث خطأ: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال بالخادم');
    })
    .finally(function() {
        // Re-enable buttons
        var buttons = document.querySelectorAll('.btn-outline-danger[onclick*="resetStudentPassword"]');
        buttons.forEach(function(btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-counter-clockwise me-1"></i> إعادة تعيين كلمة المرور';
        });
    });
}
</script>
@endpush
