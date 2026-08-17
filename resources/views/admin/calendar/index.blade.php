@extends('admin.layouts.app')

@section('title', __('calendar.index.title'))
@section('page', __('calendar.index.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('calendar.index.heading')</h1>
        <p class="text-muted mb-0">@lang('calendar.index.subtitle')</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card mb-3 hoverable">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">@lang('calendar.index.official_holidays')</span>
                <form method="GET" class="d-flex align-items-center gap-2">
                    <input type="month" name="month" class="form-control form-control-sm" style="width:auto" value="{{ $month }}" onchange="this.form.submit()">
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-edb mb-0">
                    <thead><tr><th>@lang('calendar.index.th_date')</th><th>@lang('calendar.index.th_name')</th><th>@lang('calendar.index.th_hijri')</th><th>@lang('calendar.index.th_holiday')</th></tr></thead>
                    <tbody>
                        @forelse ($iraqiDays as $d)
                            <tr>
                                <td class="num">{{ $d->gregorian_date->format('Y-m-d') }}</td>
                                <td>{{ $d->iraqi_name ?? '—' }}</td>
                                <td class="num">{{ $d->hijri_date ?? '—' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.calendar.store-iraqi') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="gregorian_date" value="{{ $d->gregorian_date->format('Y-m-d') }}">
                                        <input type="hidden" name="iraqi_name" value="{{ $d->iraqi_name }}">
                                        <input type="hidden" name="hijri_date" value="{{ $d->hijri_date }}">
                                        <input type="hidden" name="is_holiday" value="{{ $d->is_holiday ? 0 : 1 }}">
                                        <button class="btn btn-sm {{ $d->is_holiday ? 'btn-success' : 'btn-outline-secondary' }}">
                                            {{ $d->is_holiday ? __('calendar.index.holiday') : __('calendar.index.regular_day') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="empty-state"><i class="bi bi-calendar-x"></i><p>@lang('calendar.index.no_data')</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card hoverable">
            <div class="card-header fw-bold">@lang('calendar.index.add_iraqi_day')</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.calendar.store-iraqi') }}" class="row g-3">
                    @csrf
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">@lang('calendar.index.th_date')</label>
                        <input type="date" name="gregorian_date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">@lang('calendar.index.th_name')</label>
                        <input type="text" name="iraqi_name" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">@lang('calendar.index.hijri')</label>
                        <input type="text" name="hijri_date" class="form-control" placeholder="@lang('calendar.index.hijri_placeholder')">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_holiday" value="1" id="holidayChk" checked>
                            <label class="form-check-label" for="holidayChk">@lang('calendar.index.holiday')</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> @lang('calendar.index.add')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card hoverable">
            <div class="card-header fw-bold">@lang('calendar.index.school_holidays')</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.calendar.store-holiday') }}" class="row g-3 mb-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label fw-semibold">@lang('calendar.index.holiday_name')</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">@lang('calendar.index.from')</label>
                        <input type="date" name="date_start" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">@lang('calendar.index.to')</label>
                        <input type="date" name="date_stop" class="form-control" required>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i> @lang('calendar.index.add_holiday')</button>
                    </div>
                </form>
                <hr>
                <div class="list-group">
                    @forelse ($schoolHolidays as $h)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <b>{{ $h->name }}</b>
                                <div class="small text-secondary num">{{ $h->date_start->format('Y-m-d') }} ← {{ $h->date_stop->format('Y-m-d') }}</div>
                            </div>
                            <form method="POST" action="{{ route('admin.calendar.destroy-holiday', $h) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    @empty
                        <div class="empty-state py-4"><i class="bi bi-calendar2-x"></i><p>@lang('calendar.index.no_school_holidays')</p></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
