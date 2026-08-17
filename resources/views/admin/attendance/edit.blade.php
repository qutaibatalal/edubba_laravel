@extends('admin.layouts.app')

@section('title', __('attendance.edit.title'))
@section('page', __('attendance.edit.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('attendance.edit.title')</h1>
        <p class="text-muted mb-0">{{ $sheet->batch?->name }} — {{ \Carbon\Carbon::parse($sheet->date)->translatedFormat('l j F Y') }}</p>
    </div>
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-right me-1"></i> @lang('attendance.edit.back')</a>
</div>

<div class="card hoverable">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">@lang('attendance.edit.header', ['session' => $sheet->course?->name ?? $sheet->session?->subject?->name ?? __('attendance.session')])</span>
        <span class="badge badge-soft-{{ $sheet->state === 'done' ? 'success' : 'warning' }}">{{ $sheet->state === 'done' ? __('attendance.status.recorded') : __('attendance.status.draft') }}</span>
    </div>
    <form method="POST" action="{{ route('admin.attendance.mark', $sheet) }}">
        @csrf
        <div class="table-responsive">
            <table class="table table-edb mb-0">
                <thead><tr><th>@lang('attendance.student')</th><th>@lang('attendance.state')</th></tr></thead>
                <tbody>
                    @forelse ($sheet->lines as $line)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar grad-{{ ($line->id % 6) + 1 }} avatar-sm">{{ mb_substr($line->student?->full_name ?? '?', 0, 1) }}</span>
                                    <span class="fw-semibold">{{ $line->student?->full_name ?? '—' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @foreach (['present' => __('attendance.status.present'), 'absent' => __('attendance.status.absent'), 'late' => __('attendance.status.late'), 'leave' => __('attendance.status.leave')] as $val => $label)
                                        @php
                                            $checked = $line->status === $val;
                                            $btnClass = match ($val) {
                                                'present' => $checked ? 'btn-success' : 'btn-outline-success',
                                                'absent' => $checked ? 'btn-danger' : 'btn-outline-danger',
                                                'late' => $checked ? 'btn-warning' : 'btn-outline-warning',
                                                'leave' => $checked ? 'btn-info' : 'btn-outline-info',
                                            };
                                        @endphp
                                        <button type="button" class="btn {{ $btnClass }} status-btn" data-status="{{ $val }}">{{ $label }}</button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="statuses[{{ $line->student_id }}]" value="{{ $line->status }}">
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2"><div class="empty-state"><i class="bi bi-people"></i><p>@lang('attendance.edit.no_students')</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> @lang('attendance.edit.save')</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const group = btn.closest('.btn-group');
            const hidden = group.querySelector('input[type=hidden]');
            hidden.value = btn.dataset.status;
            group.querySelectorAll('.status-btn').forEach(b => {
                b.className = 'btn ' + ({
                    present: b.dataset.status === 'present' ? 'btn-success' : 'btn-outline-success',
                    absent: b.dataset.status === 'absent' ? 'btn-danger' : 'btn-outline-danger',
                    late: b.dataset.status === 'late' ? 'btn-warning' : 'btn-outline-warning',
                    leave: b.dataset.status === 'leave' ? 'btn-info' : 'btn-outline-info',
                })[b.dataset.status];
            });
        });
    });
</script>
@endpush
