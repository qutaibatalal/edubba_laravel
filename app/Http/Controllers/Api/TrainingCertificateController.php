<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingCertificate;
use App\Models\TrainingEnrollment;
use App\Services\PdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrainingCertificateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = TrainingCertificate::with(['enrollment.student', 'enrollment.trainingCourse']);

        if ($request->student_id) {
            $query->where('enrollment.student_id', $request->student_id);
        }

        if ($request->course_id) {
            $query->where('enrollment.training_course_id', $request->course_id);
        }

        $certificates = $query->latest('issued_date')->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $certificates->getCollection()->map(fn ($c) => [
                'id' => $c->id,
                'student' => $c->enrollment->student?->full_name,
                'course' => $c->enrollment->trainingCourse?->name,
                'certificate_no' => $c->certificate_no,
                'issued_date' => $c->issued_date?->format('d/m/Y'),
            ]),
            'pagination' => [
                'total' => $certificates->total(),
                'per_page' => $certificates->perPage(),
                'current_page' => $certificates->currentPage(),
                'last_page' => $certificates->lastPage(),
            ],
        ]);
    }

    public function show(Request $request, int $enrollmentId): JsonResponse
    {
        $enrollment = TrainingEnrollment::with('student', 'trainingCourse')->findOrFail($enrollmentId);

        $certificate = $enrollment->certificate;

        return response()->json([
            'status' => 'success',
            'data' => [
                'enrollment_id' => $enrollmentId,
                'student_name' => $enrollment->student?->full_name,
                'course_name' => $enrollment->trainingCourse?->name,
                'certificate_no' => $certificate?->certificate_no,
                'issued_date' => $certificate?->issued_date?->format('d/m/Y'),
                'has_certificate' => ! is_null($certificate),
            ],
        ]);
    }

    public function download(Request $request, int $enrollmentId): Response
    {
        $enrollment = TrainingEnrollment::with('student', 'trainingCourse')->findOrFail($enrollmentId);
        $certificate = $enrollment->certificate;

        if (is_null($certificate)) {
            return back()->with('error', 'لا توجد شهادة مسجلة لهذا التدريب.');
        }

        return PdfService::download('pdf.training-certificate', [
            'enrollment' => $enrollment,
            'certificate' => $certificate,
        ], 'certificate-'.$enrollment->student->user_id.'-'.$certificate->certificate_no.'.pdf');
    }
}
