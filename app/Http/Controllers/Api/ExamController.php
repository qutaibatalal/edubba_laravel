<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarksheetResource;
use App\Models\ApiUser;
use App\Models\Course;
use App\Models\Marksheet;
use App\Services\ExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * POST /faculty/marksheet/{id}/line
     * Body: { subject_id?, course_id, max_marks, marks, pass_marks }
     */
    public function enterMarks(Request $request, int $marksheetId): JsonResponse
    {
        $marksheet = Marksheet::with('lines', 'exam')->findOrFail($marksheetId);

        if ($marksheet->state === Marksheet::STATE_DONE) {
            abort(422, 'Marksheet already finalized');
        }

        $faculty = $request->user()->faculty;
        if ($faculty && $marksheet->exam->batch_id) {
            $allowed = Course::where('faculty_id', $faculty->id)
                ->where('batch_id', $marksheet->exam->batch_id)
                ->exists();
            if (! $allowed && $request->user()->role !== ApiUser::ROLE_ADMIN) {
                abort(403, 'Not allowed');
            }
        }

        $validated = $request->validate([
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'course_id' => 'nullable|integer|exists:courses,id',
            'max_marks' => 'required|numeric|min:0',
            'marks' => 'required|numeric|min:0',
            'pass_marks' => 'nullable|numeric|min:0',
        ]);

        $line = ExamService::enterMarks($marksheet, $validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Marks saved',
            'data' => new MarksheetResource($marksheet->fresh(['lines', 'exam'])),
        ]);
    }

    /**
     * POST /faculty/marksheet/{id}/finalize
     */
    public function finalize(Request $request, int $marksheetId): JsonResponse
    {
        $marksheet = Marksheet::with('lines', 'exam')->findOrFail($marksheetId);

        if ($request->user()->role !== ApiUser::ROLE_ADMIN && $marksheet->lines()->count() === 0) {
            abort(422, 'No marks entered');
        }

        ExamService::finalize($marksheet);

        return response()->json([
            'status' => 'success',
            'message' => 'Marksheet finalized',
            'data' => new MarksheetResource($marksheet->fresh(['lines', 'exam'])),
        ]);
    }
}
