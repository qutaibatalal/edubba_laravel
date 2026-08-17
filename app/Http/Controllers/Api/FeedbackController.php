<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\Feedback;
use App\Models\FeedbackForm;
use App\Models\FeedbackResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * GET /feedback/forms
     */
    public function forms(Request $request): JsonResponse
    {
        $forms = FeedbackForm::where('active', true)->get();

        return response()->json([
            'status' => 'success',
            'data' => $forms->map(fn ($f) => [
                'id' => $f->id,
                'name' => $f->name,
                'type' => $f->type,
                'questions' => $f->questions,
            ]),
        ]);
    }

    /**
     * POST /feedback/submit
     */
    public function submit(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'form_id' => 'nullable|integer|exists:feedback_forms,id',
            'rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'answers' => 'nullable|array',
        ]);

        $studentId = $user->role === ApiUser::ROLE_STUDENT ? $user->student_id : null;

        if (isset($validated['answers']) && isset($validated['form_id'])) {
            FeedbackResponse::create([
                'feedback_form_id' => $validated['form_id'],
                'student_id' => $studentId,
                'answers' => $validated['answers'],
            ]);
        }

        $feedback = Feedback::create([
            'form_id' => $validated['form_id'] ?? null,
            'student_id' => $studentId,
            'rating' => $validated['rating'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'state' => Feedback::STATE_SUBMITTED,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Feedback submitted',
            'data' => ['id' => $feedback->id],
        ], 201);
    }
}
