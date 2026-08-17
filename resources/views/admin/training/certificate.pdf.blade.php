@extends('admin.layouts.app')

@section('title', __('training.certificate.title'))

@section('content')
<div class="page">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="card hoverable p-4" style="max-width:600px;margin-top:40px;">
                    <div class="text-center mb-4">
                        <div style="width:100px;height:100px;border:4px double #2c3e50;border-radius:10px;margin:0 auto;">
                            <i class="bi bi-trophy bi-3x text-primary" style="line-height:100px;"></i>
                        </div>
                    </div>
                    <h2 class="text-center fw-bold mb-3" style="font-family: 'Tajawal', serif;">@lang('training.certificate.heading')</h2>
                    <hr class="my-4" style="border-color:#2c3e50;">
                    
                    <div class="mb-3">
                        <p class="fw-bold">@lang('training.certificate.student_name') <span style="font-size:1.2em;">{{ $enrollment->student?->full_name }}</span></p>
                        <p class="fw-bold">@lang('training.certificate.course_name') <span style="font-size:1.2em;">{{ $enrollment->trainingCourse?->name }}</span></p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="fw-bold">@lang('training.certificate.number') <span style="font-size:1.2em;">{{ $certificate?->certificate_no }}</span></p>
                        <p class="fw-bold">@lang('training.certificate.issue_date') <span style="font-size:1.2em;">{{ $certificate?->issued_date?->format('d/m/Y') }}</span></p>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t" style="border-color:#2c3e50;">
                        <p class="mb-0">@lang('training.certificate.statement')</p>
                        <p class="mb-0">@lang('training.certificate.achievement')</p>
                    </div>
                    
                    <div class="mt-5 text-center">
                        <p>_______________________</p>
                        <p>@lang('training.certificate.trainer')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection