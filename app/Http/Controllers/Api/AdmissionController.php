<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdmissionResource;
use App\Models\Admission;
use App\Services\AdmissionService;
use App\Services\SequenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    /**
     * GET /admin/admissions
     */
    public function index(Request $request): JsonResponse
    {
        $admissions = Admission::with('student', 'batch', 'program', 'register')
            ->orderByDesc('id')
            ->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => AdmissionResource::collection($admissions),
            'pagination' => [
                'total' => $admissions->total(),
                'per_page' => $admissions->perPage(),
                'current_page' => $admissions->currentPage(),
                'last_page' => $admissions->lastPage(),
            ],
        ]);
    }

    /**
     * POST /admin/admissions
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'gender' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'national_id' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'previous_school' => 'nullable|string',
            'fees_amount' => 'nullable|numeric|min:0',
            'academic_year_id' => 'nullable|integer|exists:academic_years,id',
            'batch_id' => 'nullable|integer|exists:batches,id',
            'program_id' => 'nullable|integer|exists:programs,id',
            'register_id' => 'nullable|integer|exists:admission_registers,id',
        ]);

        $validated['state'] = Admission::STATE_DRAFT;
        $validated['application_no'] = SequenceService::next('admission', 'ADM');

        $admission = Admission::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Admission created',
            'data' => new AdmissionResource($admission),
        ], 201);
    }

    /**
     * POST /admin/admissions/{id}/submit
     */
    public function submit(Request $request, int $id): JsonResponse
    {
        $admission = Admission::findOrFail($id);
        AdmissionService::submit($admission);

        return response()->json([
            'status' => 'success',
            'message' => 'Admission submitted',
            'data' => new AdmissionResource($admission->fresh()),
        ]);
    }

    /**
     * POST /admin/admissions/{id}/approve
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        $admission = Admission::findOrFail($id);
        AdmissionService::approve($admission);

        return response()->json([
            'status' => 'success',
            'message' => 'Admission approved',
            'data' => new AdmissionResource($admission->fresh()),
        ]);
    }

    /**
     * POST /admin/admissions/{id}/reject
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $admission = Admission::findOrFail($id);
        AdmissionService::reject($admission);

        return response()->json([
            'status' => 'success',
            'message' => 'Admission rejected',
            'data' => new AdmissionResource($admission->fresh()),
        ]);
    }

    /**
     * POST /admin/admissions/{id}/admit
     */
    public function admit(Request $request, int $id): JsonResponse
    {
        $admission = Admission::findOrFail($id);
        $student = AdmissionService::admit($admission);

        return response()->json([
            'status' => 'success',
            'message' => 'Student admitted',
            'data' => [
                'student_id' => $student->id,
                'student_code' => $student->student_code,
                'admission' => new AdmissionResource($admission->fresh()),
            ],
        ]);
    }
}
