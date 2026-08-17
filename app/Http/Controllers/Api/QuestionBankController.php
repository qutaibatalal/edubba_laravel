<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MinistryQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionBankController extends Controller
{
    /**
     * GET /v1/question-bank?subject_id=X&grade=6
     *
     * grade: 3 => الثالث المتوسط (3rd intermediate), 6 => السادس الإعدادي.
     */
    public function index(Request $request): JsonResponse
    {
        $stage = $this->stageFromGrade($request->integer('grade'));

        if (! $stage) {
            return response()->json([
                'status' => 'error',
                'message' => 'يرجى تحديد الصف (grade) الصحيح — 3 أو 6',
            ], 422);
        }

        $query = MinistryQuestion::with('subject')->where('stage', $stage);

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        $questions = $query->orderByRaw('RANDOM()')->paginate(20);

        $data = collect($questions->items())->map(fn (MinistryQuestion $q) => [
            'id' => $q->id,
            'subject' => $q->subject?->name,
            'question' => $q->question,
            'type' => $q->question_type,
            'options' => $q->options,
            'marks' => (int) $q->marks,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'pagination' => [
                'total' => $questions->total(),
                'per_page' => $questions->perPage(),
                'current_page' => $questions->currentPage(),
                'last_page' => $questions->lastPage(),
            ],
        ]);
    }

    /**
     * POST /v1/question-bank/practice
     *
     * Body: { question_id, answer } where answer is the option letter.
     * Instant grading with optional explanation.
     */
    public function practice(Request $request): JsonResponse
    {
        $request->validate([
            'question_id' => 'required|exists:ministry_questions,id',
            'answer' => 'required|string',
        ]);

        $q = MinistryQuestion::findOrFail($request->question_id);
        $correct = $q->answer;
        $isCorrect = mb_strtolower(trim($request->string('answer'))) ===
            mb_strtolower(trim($correct));

        return response()->json([
            'status' => 'success',
            'data' => [
                'correct' => $isCorrect,
                'correct_answer' => $correct,
                'explanation' => $isCorrect
                    ? 'إجابة صحيحة، أحسنت!'
                    : 'إجابة خاطئة. الإجابة الصحيحة: '.$correct.'. راجع المفهوم وحاول مرة أخرى.',
                'question' => $q->question,
            ],
        ]);
    }

    private function stageFromGrade(int $grade): ?string
    {
        return match ($grade) {
            3 => 'الثالث المتوسط',
            6 => 'السادس الإعدادي',
            default => null,
        };
    }
}
