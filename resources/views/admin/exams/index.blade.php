@extends('admin.layouts.app')

@section('title', __('exams.index.title'))
@section('page', __('exams.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('exams.index.heading')</h1>
        <p class="text-secondary mb-0">@lang('exams.index.subtitle')</p>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger py-2">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
@endif

<div class="bento">
    <div class="b-7">
        <div class="card hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-journal-bookmark me-2 text-primary"></i> @lang('exams.index.list_title')</span>
                <span class="badge badge-soft-primary">@lang('exams.index.exam_count', ['count' => $exams->count()])</span>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0 align-middle">
                    <thead><tr><th>@lang('exams.index.col_exam')</th><th>@lang('exams.index.col_type')</th><th>@lang('exams.index.col_batch')</th><th>@lang('exams.index.col_duration')</th><th>@lang('exams.index.col_status')</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($exams as $exam)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar grad-{{ $loop->index % 6 + 1 }}">{{ mb_substr($exam->name, 0, 1) }}</span>
                                        <span class="fw-semibold">{{ $exam->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $exam->examType?->name ?? '—' }}</td>
                                <td>{{ $exam->batch?->name ?? '—' }}</td>
                                <td class="num">
                                    {{ $exam->date_start ? $exam->date_start->format('Y-m-d') : '—' }}
                                    @if ($exam->date_end && $exam->date_end != $exam->date_start)
                                        <span class="text-secondary">→ {{ $exam->date_end->format('Y-m-d') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-soft-{{ $exam->state === 'published' ? 'success' : 'warning' }}">{{ $exam->state }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-sm btn-outline-primary">@lang('exams.index.manage') <i class="bi bi-arrow-left"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><div class="empty-state"><i class="bi bi-journal-x"></i><p>@lang('exams.index.empty_title')</p><small>@lang('exams.index.empty_hint')</small></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="b-5">
        <div class="card hoverable">
            <div class="card-header fw-semibold"><i class="bi bi-plus-circle me-2 text-primary"></i> @lang('exams.index.new_title')</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.exams.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">@lang('exams.index.form_name')</label>
                        <input type="text" name="name" class="form-control" required placeholder="@lang('exams.index.form_name_ph')">
                    </div>
                    <div class="col-6">
                        <label class="form-label">@lang('exams.index.form_type')</label>
                        <select name="exam_type_id" class="form-select">
                            <option value="">—</option>
                            @foreach ($types as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">@lang('exams.index.form_batch')</label>
                        <select name="batch_id" class="form-select">
                            <option value="">@lang('exams.index.all_batches')</option>
                            @foreach ($batches as $b) <option value="{{ $b->id }}">{{ $b->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">@lang('exams.index.form_year')</label>
                        <select name="academic_year_id" class="form-select">
                            <option value="">—</option>
                            @foreach ($years as $y) <option value="{{ $y->id }}">{{ $y->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">@lang('exams.index.form_term')</label>
                        <select name="term_id" class="form-select">
                            <option value="">—</option>
                            @foreach ($terms as $term) <option value="{{ $term->id }}">{{ $term->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">@lang('exams.index.form_start')</label>
                        <input type="date" name="date_start" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label">@lang('exams.index.form_end')</label>
                        <input type="date" name="date_end" class="form-control">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary w-100">@lang('exams.index.create') <i class="bi bi-check-lg"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card hoverable mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-diagram-3 me-2 text-primary"></i> @lang('exams.index.rooms_title')</span>
        <span class="badge badge-soft-primary">@lang('exams.index.room_count', ['count' => $rooms->count()])</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.exams.rooms.store') }}" class="row g-2 align-items-end mb-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label">@lang('exams.index.form_room_name')</label>
                <input type="text" name="name" class="form-control" required placeholder="@lang('exams.index.form_room_name_ph')">
            </div>
            <div class="col-md-2">
                <label class="form-label">@lang('exams.index.form_code')</label>
                <input type="text" name="code" class="form-control" placeholder="A1">
            </div>
            <div class="col-md-3">
                <label class="form-label">@lang('exams.index.form_capacity')</label>
                <input type="number" name="capacity" class="form-control" required min="1" max="500" value="30">
            </div>
            <div class="col-md-2">
                <label class="form-label">@lang('exams.index.form_location')</label>
                <input type="text" name="location" class="form-control" placeholder="@lang('exams.index.form_location_ph')">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> @lang('exams.index.add')</button>
            </div>
        </form>

        @if ($rooms->count())
            <div class="row g-3">
                @foreach ($rooms as $room)
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 {{ $room->active ? '' : 'opacity-50' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold">{{ $room->name }}</div>
                                        <small class="text-secondary">{{ $room->location ?: '—' }}</small>
                                    </div>
                                    <span class="badge badge-soft-{{ $room->active ? 'success' : 'secondary' }}">{{ $room->active ? __('exams.index.room_active') : __('exams.index.room_inactive') }}</span>
                                </div>
                                <div class="mt-3 d-flex align-items-center justify-content-between">
                                    <span class="stat-value num" style="font-size:1.2rem">{{ $room->capacity }}</span>
                                    <span class="text-secondary small">@lang('exams.index.seat')</span>
                                </div>
                                <div class="mt-2 d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-secondary flex-fill" data-bs-toggle="modal" data-bs-target="#roomEdit{{ $room->id }}">@lang('exams.index.edit')</button>
                                    <form method="POST" action="{{ route('admin.exams.rooms.destroy', $room) }}" onsubmit="return confirm('{{ __('exams.index.confirm_delete_room') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="roomEdit{{ $room->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="{{ route('admin.exams.rooms.update', $room) }}">
                                @csrf @method('PUT')
                                <div class="modal-header"><h5 class="modal-title">@lang('exams.index.edit_room_title')</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <div class="modal-body row g-3">
                                    <div class="col-6"><label class="form-label">@lang('exams.index.name')</label><input name="name" value="{{ $room->name }}" class="form-control" required></div>
                                    <div class="col-6"><label class="form-label">@lang('exams.index.form_code')</label><input name="code" value="{{ $room->code }}" class="form-control"></div>
                                    <div class="col-6"><label class="form-label">@lang('exams.index.form_capacity')</label><input type="number" name="capacity" value="{{ $room->capacity }}" class="form-control" min="1" required></div>
                                    <div class="col-6"><label class="form-label">@lang('exams.index.form_location')</label><input name="location" value="{{ $room->location }}" class="form-control"></div>
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="active" value="1" id="roomActive{{ $room->id }}" {{ $room->active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="roomActive{{ $room->id }}">@lang('exams.index.room_active_label')</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer"><button class="btn btn-primary">@lang('exams.index.save_changes')</button></div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state"><i class="bi bi-easel"></i><p>@lang('exams.index.no_rooms_title')</p><small>@lang('exams.index.no_rooms_hint')</small></div>
        @endif
    </div>
</div>
@endsection
