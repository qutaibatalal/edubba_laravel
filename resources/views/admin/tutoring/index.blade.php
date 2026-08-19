@extends('admin.layouts.app')

@section('title', __('tutoring.index.title'))
@section('page', __('tutoring.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('tutoring.index.title')</h1>
        <p>@lang('tutoring.index.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tutoring.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('tutoring.create.create')</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card hoverable">
            <div class="card-header fw-bold"><i class="bi bi-people-fill me-2 text-primary"></i> @lang('tutoring.index.study_groups')</div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th>@lang('tutoring.index.th_name')</th><th>@lang('tutoring.index.th_subject')</th><th>@lang('tutoring.index.th_tutor')</th><th>@lang('tutoring.index.th_students')</th><th>@lang('tutoring.index.th_state')</th></tr></thead>
                    <tbody>
                        @forelse ($groups as $g)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($g->name, 0, 1) }}</span>
                                        <span>{{ $g->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $g->subject?->name ?? '—' }}</td>
                                <td>{{ $g->tutor?->name ?? '—' }}</td>
                                <td><span class="badge badge-soft-primary">{{ $g->students_count }}</span></td>
                                <td><span class="badge badge-soft-{{ $g->state === 'active' ? 'success' : 'secondary' }}">{{ $g->state }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-people"></i><p>@lang('tutoring.index.no_groups')</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card hoverable">
            <div class="card-header fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i> @lang('tutoring.index.packages')</div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th>@lang('tutoring.index.th_name')</th><th>@lang('tutoring.index.th_sessions')</th><th class="text-end">@lang('tutoring.index.th_price')</th></tr></thead>
                    <tbody>
                        @forelse ($packages as $p)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($p->name, 0, 1) }}</span>
                                        <span>{{ $p->name }}</span>
                                    </div>
                                </td>
                                <td><span class="badge badge-soft-info">@lang('tutoring.index.session_count', ['count' => $p->sessions])</span></td>
                                <td class="text-end fw-semibold">{{ number_format($p->price) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3"><div class="empty-state"><i class="bi bi-box"></i><p>@lang('tutoring.index.no_packages')</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card hoverable mt-3">
            <div class="card-header fw-bold"><i class="bi bi-lightning-charge me-2 text-primary"></i> @lang('tutoring.index.latest_subscriptions')</div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th>@lang('tutoring.index.th_student')</th><th>@lang('tutoring.index.th_tutor')</th><th>@lang('tutoring.index.th_remaining')</th><th>@lang('tutoring.index.th_state')</th></tr></thead>
                    <tbody>
                        @forelse ($subscriptions as $s)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-{{ $loop->index % 6 + 1 }} avatar-sm">{{ mb_substr($s->student?->full_name ?? '—', 0, 1) }}</span>
                                        <span>{{ $s->student?->full_name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td>{{ $s->tutor?->name ?? '—' }}</td>
                                <td><span class="badge badge-soft-purple">{{ max(0, $s->sessions_count - $s->sessions_used) }}</span></td>
                                <td><span class="badge badge-soft-{{ $s->state === 'active' ? 'success' : 'warning' }}">{{ $s->state }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="empty-state"><i class="bi bi-lightning"></i><p>@lang('tutoring.index.no_subscriptions')</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
