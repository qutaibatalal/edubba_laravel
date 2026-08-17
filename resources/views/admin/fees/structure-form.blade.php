@extends('admin.layouts.app')

@section('title', __('fees.structure_form.title'))
@section('page', __('fees.structure_form.page'))

@section('content')
<div class="page-header">
    <div>
        <h1 class="fw-bold">@lang('fees.structure_form.h1')</h1>
        <p class="text-secondary mb-0">@lang('fees.structure_form.subtitle')</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.fees.structures') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right me-1"></i> @lang('fees.structure_form.back')</a>
    </div>
</div>

<div class="card hoverable" style="max-width:820px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.fees.structures.store') }}" id="feeForm">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">@lang('fees.structure_form.name_label')</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('fees.structure_form.academic_year')</label>
                    <select name="academic_year_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($years as $y)
                            <option value="{{ $y->id }}">{{ $y->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('fees.structure_form.batch')</label>
                    <select name="batch_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($batches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang('fees.structure_form.program')</label>
                    <select name="program_id" class="form-select">
                        <option value="">—</option>
                        @foreach ($programs as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h6 class="fw-bold d-flex align-items-center gap-2 mb-3"><i class="bi bi-list-check text-primary"></i> @lang('fees.structure_form.fee_lines')</h6>
            <div id="lines">
                <div class="row g-2 mb-2 line-row">
                    <div class="col"><input type="text" name="lines[0][name]" class="form-control" placeholder="@lang('fees.structure_form.line_name_placeholder')" required></div>
                    <div class="col"><input type="number" step="0.01" name="lines[0][amount]" class="form-control" placeholder="@lang('fees.structure_form.amount_placeholder')" required></div>
                    <div class="col-auto" style="width:140px">
                        <select name="lines[0][type]" class="form-select">
                            <option value="">@lang('fees.structure_form.type_label')</option>
                            <option value="one_time">@lang('fees.structure_form.type_one_time')</option>
                            <option value="recurring">@lang('fees.structure_form.type_recurring')</option>
                        </select>
                    </div>
                    <div class="col-auto"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.line-row').remove()"><i class="bi bi-trash"></i></button></div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary mb-4" onclick="addLine()"><i class="bi bi-plus-lg me-1"></i> @lang('fees.structure_form.add_line')</button>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i> @lang('fees.structure_form.create')</button>
                <a href="{{ route('admin.fees.structures') }}" class="btn btn-outline-secondary">@lang('fees.structure_form.cancel')</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let idx = 1;
function addLine() {
    const div = document.createElement('div');
    div.className = 'row g-2 mb-2 line-row';
    div.innerHTML = `
        <div class="col"><input type="text" name="lines[`+idx+`][name]" class="form-control" placeholder="{{ __('fees.structure_form.line_name_placeholder') }}" required></div>
        <div class="col"><input type="number" step="0.01" name="lines[`+idx+`][amount]" class="form-control" placeholder="{{ __('fees.structure_form.amount_placeholder') }}" required></div>
        <div class="col-auto" style="width:140px"><select name="lines[`+idx+`][type]" class="form-select"><option value="">{{ __('fees.structure_form.type_label') }}</option><option value="one_time">{{ __('fees.structure_form.type_one_time') }}</option><option value="recurring">{{ __('fees.structure_form.type_recurring') }}</option></select></div>
        <div class="col-auto"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.line-row').remove()"><i class="bi bi-trash"></i></button></div>`;
    document.getElementById('lines').appendChild(div);
    idx++;
}
</script>
@endpush
@endsection
