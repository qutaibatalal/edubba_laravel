@extends('admin.layouts.app')

@section('title', __('attendance.pdf.title'))

@section('content')
<div class="page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-3 fw-bold">@lang('attendance.pdf.heading')</h1>
                <p class="mb-4 text-muted">
                    @lang('attendance.pdf.month', ['month' => \Carbon\Carbon::parse($month.'-01')->translatedFormat('F Y')])
                    {{ $batch ? ' - '.$batch->name : ' - '.__('attendance.pdf.all_batches') }}
                </p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" style="margin-bottom:20px;">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="batch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">@lang('attendance.pdf.all_batches')</option>
                                @foreach ($batches as $b)
                                    <option value="{{ $b->id }}" {{ $batchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="month" name="month" class="form-control form-control-sm" value="{{ $month->format('m-Y') }}" onchange="this.form.submit()">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-filter me-1"></i> @lang('attendance.pdf.filter')
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.attendance.pdf.download') }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-file-pdf me-1"></i> @lang('attendance.pdf.download')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($data->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-bordered table-nowrap mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('attendance.student')</th>
                        <th>@lang('attendance.batch')</th>
                        <th class="text-center">@lang('attendance.pdf.total_periods')</th>
                        <th class="text-center">@lang('attendance.status.present')</th>
                        <th class="text-center">@lang('attendance.status.late')</th>
                        <th class="text-center">@lang('attendance.status.absent')</th>
                        <th class="text-center">@lang('attendance.status.leave')</th>
                        <th class="text-center">@lang('attendance.pdf.attendance_rate')</th>
                        <th>@lang('attendance.pdf.status')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $idx => $r)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm" style="background:{{ $r->student->color ?? '#007bff' }};">
                                    {{ mb_substr($r->student->full_name ?? '?', 0, 1) }}
                                </span>
                                <span>{{ $r->student->full_name }}</span>
                            </div>
                        </td>
                        <td>{{ $r->batch ?? '—' }}</td>
                        <td class="text-center">{{ $r->total }}</td>
                        <td class="text-success">{{ $r->present }}</td>
                        <td class="text-warning">{{ $r->late }}</td>
                        <td class="text-danger">{{ $r->absent }}</td>
                        <td class="text-info">{{ $r->leave }}</td>
                        <td class="text-center">
                            <div class="progress progress-sm mb-0" style="height: 8px;">
                                <div class="progress-bar rounded-pill" style="background:
                                    @if ($r->percentage >= 90) #198754
                                    @elseif ($r->percentage >= 75) #6c757d
                                    @elseif ($r->percentage >= 60) #ffc107
                                    @else #dc3545
                                    @endif
                                    ; width:{{ min($r->percentage, 100) }}%"></div>
                            </div>
                            <small class="d-block mt-1">{{ $r->percentage }}%</small>
                        </td>
                        <td class="text-end">
                            @if ($r->percentage >= 90)
                                <span class="badge bg-success">@lang('attendance.monthly.excellent')</span>
                            @elseif ($r->percentage >= 75)
                                <span class="badge bg-primary">@lang('attendance.monthly.good')</span>
                            @elseif ($r->percentage >= 60)
                                <span class="badge bg-warning">@lang('attendance.monthly.low')</span>
                            @else
                                <span class="badge bg-danger">@lang('attendance.monthly.danger')</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td></td>
                        <td><strong>@lang('attendance.pdf.total')</strong></td>
                        <td><strong>{{$data->sum('present')}}</strong></td>
                        <td></td>
                        <td><strong>{{$data->sum('absent')}}</strong></td>
                        <td></td>
                        <td><strong>@lang('attendance.pdf.avg_rate', ['rate' => $data->avg('percentage', 0)])</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            @lang('attendance.pdf.no_data')
        </div>
        @endif
    </div>
</div>
@endsection
