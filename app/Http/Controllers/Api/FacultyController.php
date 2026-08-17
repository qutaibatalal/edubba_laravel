<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassSessionResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ExamResource;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\StudentResource;
use App\Models\Assignment;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Marksheet;
use App\Models\NotificationLog;
use App\Models\Student;
use App\Services\ExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    protected function resolveFaculty(Request $request)
    {
        $faculty = $request->user()->faculty;

        if (! $faculty) {
            abort(404, 'Faculty profile not found');
        }

        return $faculty;
    }

    /**
     * GET /faculty/dashboard
     */
    public function dashboard(Request $request): JsonResponse
    {
        $faculty = $this->resolveFaculty($request);

        $today = now()->toDateString();

        return response()->json([
            'status' => 'success',
            'data' => [
                'faculty' => new FacultyResource($faculty->load('department')),
                'summary' => [
                    'courses' => $faculty->courses()->count(),
                    'sessions_today' => ClassSession::where('faculty_id', $faculty->id)
                        ->where('date', $today)
                        ->count(),
                    'sessions_done' => ClassSession::where('faculty_id', $faculty->id)
                        ->where('state', 'done')
                        ->count(),
                    'batches' => $faculty->batches()->count(),
                ],
            ],
        ]);
    }

    /**
     * GET /faculty/courses
     */
    public function courses(Request $request): JsonResponse
    {
        $faculty = $this->resolveFaculty($request);

        return response()->json([
            'status' => 'success',
            'data' => CourseResource::collection($faculty->courses()->with('subject', 'batch', 'program')->get()),
        ]);
    }

    /**
     * GET /faculty/batch/{id}/students
     */
    public function batchStudents(Request $request, int $batchId): JsonResponse
    {
        $faculty = $this->resolveFaculty($request);

        if (! $faculty->batches()->where('batches.id', $batchId)->exists() && ! $faculty->courses()->where('batch_id', $batchId)->exists()) {
            abort(403, 'Not allowed');
        }

        $students = Student::where('batch_id', $batchId)
            ->where('state', Student::STATE_ADMITTED)
            ->with('batch')
            ->orderBy('roll_no')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => StudentResource::collection($students),
        ]);
    }

    /**
     * GET /faculty/timetable
     */
    public function timetable(Request $request): JsonResponse
    {
        $faculty = $this->resolveFaculty($request);

        $sessions = ClassSession::with('course', 'subject', 'batch', 'classroom')
            ->where('faculty_id', $faculty->id)
            ->whereDate('date', '>=', now()->startOfWeek())
            ->whereDate('date', '<=', now()->endOfWeek())
            ->orderBy('date')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ClassSessionResource::collection($sessions),
        ]);
    }

    /**
     * GET /faculty/exams
     */
    public function exams(Request $request): JsonResponse
    {
        $faculty = $this->resolveFaculty($request);

        $batchIds = $faculty->batches()->pluck('batches.id')
            ->merge($faculty->courses()->pluck('batch_id')->filter())
            ->unique();

        $exams = Exam::with('examType', 'schedules')
            ->whereIn('batch_id', $batchIds)
            ->where('state', '!=', 'cancel')
            ->orderByDesc('date_start')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ExamResource::collection($exams),
        ]);
    }

    /**
     * GET /faculty/grade-entry?exam_id=X
     */
    public function gradeEntry(Request $request): JsonResponse
    {
        $faculty = $this->resolveFaculty($request);

        $request->validate(['exam_id' => 'required|integer|exists:exams,id']);

        $exam = Exam::findOrFail($request->exam_id);

        $courseIds = $faculty->courses()->where('batch_id', $exam->batch_id)->pluck('courses.id');
        if ($courseIds->isEmpty()) {
            abort(403, 'Not allowed for this exam');
        }

        $students = Student::where('batch_id', $exam->batch_id)
            ->where('state', Student::STATE_ADMITTED)
            ->with(['marksheets' => fn ($q) => $q->where('exam_id', $exam->id), 'marksheets.lines'])
            ->orderBy('roll_no')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'exam' => [
                    'id' => $exam->id,
                    'name' => $exam->name,
                    'batch_id' => $exam->batch_id,
                ],
                'courses' => CourseResource::collection($faculty->courses()->where('batch_id', $exam->batch_id)->with('subject')->get()),
                'students' => $students->map(function ($student) {
                    $marksheet = $student->marksheets->first();

                    return [
                        'student_id' => $student->id,
                        'student_name' => $student->full_name,
                        'roll_no' => $student->roll_no,
                        'marksheet_id' => $marksheet?->id,
                        'state' => $marksheet?->state ?? 'draft',
                        'lines' => $marksheet?->lines->map(fn ($l) => [
                            'subject_id' => $l->subject_id,
                            'course_id' => $l->course_id,
                            'marks' => $l->marks,
                            'max_marks' => $l->max_marks,
                            'pass_marks' => $l->pass_marks,
                        ]),
                    ];
                }),
            ],
        ]);
    }

    /**
     * POST /faculty/grade-entry/save
     * Body: { exam_id, course_id, lines: [{ student_id, marks, max_marks, pass_marks }] }
     */
    public function gradeEntrySave(Request $request): JsonResponse
    {
        $faculty = $this->resolveFaculty($request);

        $validated = $request->validate([
            'exam_id' => 'required|integer|exists:exams,id',
            'course_id' => 'required|integer|exists:courses,id',
            'lines' => 'required|array|min:1',
            'lines.*.student_id' => 'required|integer|exists:students,id',
            'lines.*.marks' => 'required|numeric|min:0',
            'lines.*.max_marks' => 'required|numeric|min:0',
            'lines.*.pass_marks' => 'nullable|numeric|min:0',
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $course = Course::findOrFail($validated['course_id']);

        if ($course->faculty_id !== $faculty->id) {
            abort(403, 'Not allowed');
        }

        $saved = 0;
        foreach ($validated['lines'] as $line) {
            $marksheet = Marksheet::firstOrCreate(
                ['exam_id' => $exam->id, 'student_id' => $line['student_id'], 'batch_id' => $exam->batch_id],
                ['state' => Marksheet::STATE_DRAFT]
            );

            if ($marksheet->state === Marksheet::STATE_DONE) {
                continue;
            }

            ExamService::enterMarks($marksheet, [
                'course_id' => $course->id,
                'subject_id' => $course->subject_id,
                'marks' => $line['marks'],
                'max_marks' => $line['max_marks'],
                'pass_marks' => $line['pass_marks'] ?? 0,
            ]);

            $saved++;
        }

        return response()->json([
            'status' => 'success',
            'message' => "Marks saved for {$saved} student(s)",
            'data' => ['saved' => $saved],
        ]);
    }

    /**
     * GET /faculty/timetable/conflicts
     */
    public function timetableConflicts(Request $request): JsonResponse
    {
        $faculty = $this->resolveFaculty($request);

        $sessions = ClassSession::with('course', 'batch', 'classroom')
            ->where('faculty_id', $faculty->id)
            ->get();

        $conflicts = [];
        foreach ($sessions as $a) {
            foreach ($sessions as $b) {
                if ($a->id >= $b->id) {
                    continue;
                }
                if ($a->date != $b->date) {
                    continue;
                }
                if ($a->end_time <= $b->start_time || $b->end_time <= $a->start_time) {
                    continue;
                }
                $conflicts[] = [
                    'date' => $a->date?->toDateString(),
                    'first' => [
                        'course' => $a->course?->name,
                        'batch' => $a->batch?->name,
                        'time' => $a->start_time.'-'.$a->end_time,
                    ],
                    'second' => [
                        'course' => $b->course?->name,
                        'batch' => $b->batch?->name,
                        'time' => $b->start_time.'-'.$b->end_time,
                    ],
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => ['conflicts' => array_values($conflicts), 'total' => count($conflicts)],
        ]);
    }

    /**
     * POST /faculty/assignments/create
     * Body: { course_id, title, description?, due_date?, state? }
     */
    public function createAssignment(Request $request): JsonResponse
    {
        $faculty = $this->resolveFaculty($request);

        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:5000',
            'due_date' => 'nullable|date',
            'state' => 'nullable|in:draft,published',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        if ($course->faculty_id !== $faculty->id) {
            abort(403, 'Not allowed');
        }

        $assignment = Assignment::create([
            'course_id' => $course->id,
            'faculty_id' => $faculty->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'state' => $validated['state'] ?? Assignment::STATE_PUBLISHED,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment created',
            'data' => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'due_date' => $assignment->due_date?->toDateString(),
                'state' => $assignment->state,
            ],
        ], 201);
    }

    /**
     * POST /faculty/notifications/send
     * Body: { body, channel? }
     */
    public function sendNotification(Request $request): JsonResponse
    {
        $faculty = $this->resolveFaculty($request);

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'channel' => 'nullable|in:whatsapp,sms,push',
        ]);

        $students = Student::whereIn('batch_id', $faculty->batches()->pluck('batches.id'))
            ->where('state', Student::STATE_ADMITTED)
            ->get();

        $channel = $validated['channel'] ?? 'push';
        $count = 0;

        foreach ($students as $student) {
            NotificationLog::create([
                'channel' => $channel,
                'recipient' => $student->mobile ?? $student->phone,
                'body' => $validated['body'],
                'state' => 'pending',
                'student_id' => $student->id,
            ]);
            $count++;
        }

        return response()->json([
            'status' => 'success',
            'message' => "Notification queued for {$count} student(s)",
            'data' => ['recipients' => $count],
        ], 201);
    }
}
