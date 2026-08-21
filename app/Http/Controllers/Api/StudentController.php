<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\ClassSessionResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ExamResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\MarksheetResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\StudentExcuseResource;
use App\Http\Resources\StudentResource;
use App\Models\ApiUser;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\ChatMessage;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\Feedback;
use App\Models\Marksheet;
use App\Models\MarksheetLine;
use App\Models\NotificationLog;
use App\Models\StudentExcuse;
use App\Services\AttendanceService;
use App\Services\GamificationService;
use App\Support\UploadPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected function resolveStudent(Request $request)
    {
        $user = $request->user();

        $student = match ($user->role) {
            ApiUser::ROLE_STUDENT => $user->student,
            default => $user->student,
        };

        if (! $student) {
            abort(404, 'Student profile not found');
        }

        return $student;
    }

    /**
     * GET /student/dashboard — rich response for mobile app.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);
        $student->load('batch', 'program', 'academicYear', 'invoices', 'courses.subject');

        $attendance = AttendanceService::attendancePercentage($student);

        $todaySchedule = ClassSession::with('course', 'subject', 'faculty', 'classroom')
            ->where('batch_id', $student->batch_id)
            ->whereDate('date', now()->toDateString())
            ->orderBy('start_time')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'subject' => $s->subject?->name ?? '—',
                'faculty' => $s->faculty?->full_name ?? '—',
                'time' => $s->start_time.'-'.$s->end_time,
                'classroom' => $s->classroom?->name ?? '—',
            ]);

        $recentGrades = MarksheetLine::with('subject', 'marksheet.exam')
            ->whereHas('marksheet', fn ($q) => $q->where('student_id', $student->id)->where('state', 'done'))
            ->orderByDesc('id')
            ->limit(3)
            ->get()
            ->map(fn ($l) => [
                'subject' => $l->subject?->name ?? '—',
                'marks' => $l->marks,
                'max_marks' => $l->max_marks,
                'percentage' => $l->percentage,
                'exam' => $l->marksheet?->exam?->name ?? '—',
            ]);

        $upcomingExams = Exam::with('examType')
            ->where('batch_id', $student->batch_id)
            ->where('state', '!=', 'cancel')
            ->where('date_start', '>=', now()->toDateString())
            ->orderBy('date_start')
            ->limit(3)
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'type' => $e->examType?->name ?? '—',
                'date_start' => $e->date_start?->toDateString(),
                'date_end' => $e->date_end?->toDateString(),
            ]);

        $totalFees = (float) $student->invoices->sum('amount');
        $paidFees = (float) $student->invoices->sum('amount') - (float) $student->invoices->sum('balance');

        return response()->json([
            'status' => 'success',
            'data' => [
                'student' => new StudentResource($student),
                'stats' => [
                    'attendance_percentage' => $attendance,
                    'total_subjects' => $student->courses->count(),
                    'pending_assignments' => $student->assignments()->where('state', 'published')->count(),
                    'unread_notifications' => NotificationLog::where('student_id', $student->id)->whereNull('read_at')->count(),
                ],
                'today_schedule' => $todaySchedule,
                'recent_grades' => $recentGrades,
                'fee_status' => [
                    'total' => $totalFees,
                    'paid' => $paidFees,
                    'remaining' => $totalFees - $paidFees,
                    'status' => $totalFees - $paidFees > 0 ? 'unpaid' : 'paid',
                ],
                'upcoming_exams' => $upcomingExams,
            ],
        ]);
    }

    /**
     * GET /student/profile
     */
    public function profile(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        return response()->json([
            'status' => 'success',
            'data' => new StudentResource($student->load('batch', 'program', 'academicYear', 'parents')),
        ]);
    }

    /**
     * GET /student/courses — eager loaded to prevent N+1.
     */
    public function courses(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $courses = $student->courses()->with(['subject', 'faculty.department'])->get();

        return response()->json([
            'status' => 'success',
            'data' => CourseResource::collection($courses),
        ]);
    }

    /**
     * GET /student/timetable
     */
    public function timetable(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);
        $batchId = $student->batch_id;

        $sessions = ClassSession::with('course', 'subject', 'faculty', 'classroom')
            ->where('batch_id', $batchId)
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
     * GET /student/attendance — eager loaded.
     */
    public function attendance(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $lines = $student->attendances()
            ->with(['sheet.course.subject', 'sheet.faculty'])
            ->latest('id')
            ->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => [
                'percentage' => AttendanceService::attendancePercentage($student),
                'records' => AttendanceResource::collection($lines),
                'pagination' => [
                    'total' => $lines->total(),
                    'per_page' => $lines->perPage(),
                    'current_page' => $lines->currentPage(),
                    'last_page' => $lines->lastPage(),
                ],
            ],
        ]);
    }

    /**
     * GET /student/exams
     */
    public function exams(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $exams = Exam::with('examType', 'schedules')
            ->where('batch_id', $student->batch_id)
            ->where('state', '!=', 'cancel')
            ->orderByDesc('date_start')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ExamResource::collection($exams),
        ]);
    }

    /**
     * GET /student/results
     */
    public function results(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $marksheets = $student->marksheets()
            ->with(['lines', 'exam'])
            ->where('state', 'done')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => MarksheetResource::collection($marksheets),
        ]);
    }

    /**
     * GET /student/fees — eager loaded.
     */
    public function fees(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $invoices = $student->invoices()->with(['lines', 'payments'])->orderByDesc('date')->get();

        return response()->json([
            'status' => 'success',
            'data' => InvoiceResource::collection($invoices),
        ]);
    }

    /**
     * GET /student/payments
     */
    public function payments(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $payments = $student->payments()->orderByDesc('date')->get();

        return response()->json([
            'status' => 'success',
            'data' => PaymentResource::collection($payments),
        ]);
    }

    /**
     * GET /student/excuses
     */
    public function excuses(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        return response()->json([
            'status' => 'success',
            'data' => StudentExcuseResource::collection($student->excuses()->orderByDesc('date')->get()),
        ]);
    }

    /**
     * POST /student/excuse/request
     */
    public function requestExcuse(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $validated = $request->validate([
            'date' => 'required|date',
            'reason' => 'required|string',
            'document' => 'nullable|string',
        ]);

        $excuse = $student->excuses()->create([
            'date' => $validated['date'],
            'reason' => $validated['reason'],
            'document' => $validated['document'] ?? null,
            'state' => StudentExcuse::STATE_PENDING,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Excuse submitted',
            'data' => new StudentExcuseResource($excuse),
        ], 201);
    }

    /**
     * GET /student/grades
     */
    public function grades(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $lines = MarksheetLine::with('subject', 'course', 'marksheet.exam')
            ->whereHas('marksheet', function ($q) use ($student) {
                $q->where('student_id', $student->id)->where('state', Marksheet::STATE_DONE);
            })
            ->orderByDesc('id')
            ->get();

        $subjects = $lines->groupBy(fn ($l) => $l->subject_id)->map(function ($group, $subjectId) {
            $subject = $group->first()->subject;

            return [
                'subject_id' => $subjectId,
                'subject' => $subject?->name ?? '—',
                'marks' => round($group->avg('marks'), 2),
                'max_marks' => round($group->avg('max_marks'), 2),
                'percentage' => round($group->avg('percentage'), 2),
                'grade' => $group->last()->grade,
            ];
        })->values();

        $avgPercentage = $lines->count() ? round($lines->avg('percentage'), 2) : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'gpa' => $avgPercentage,
                'gpa_letter' => $this->gpaLetter($avgPercentage),
                'subjects' => $subjects,
            ],
        ]);
    }

    /**
     * GET /student/exam-conflicts
     */
    public function examConflicts(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $schedules = ExamSchedule::with('exam', 'subject', 'course')
            ->whereHas('exam', function ($q) use ($student) {
                $q->where('batch_id', $student->batch_id)->where('state', '!=', 'cancel');
            })
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $conflicts = [];

        foreach ($schedules as $a) {
            foreach ($schedules as $b) {
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
                        'exam' => $a->exam?->name,
                        'subject' => $a->subject?->name ?? '—',
                        'time' => $a->start_time.'-'.$a->end_time,
                    ],
                    'second' => [
                        'exam' => $b->exam?->name,
                        'subject' => $b->subject?->name ?? '—',
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
     * POST /student/assignments/submit
     */
    public function submitAssignment(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $validated = $request->validate([
            'assignment_id' => 'required|integer|exists:assignments,id',
            'file' => ['required', 'file'],
            'note' => 'nullable|string|max:2000',
        ]);

        UploadPolicy::validate($request->file('file'), 'document');

        $assignment = Assignment::findOrFail($validated['assignment_id']);

        if (! $assignment->course->students()->where('student_id', $student->id)->exists()) {
            abort(403, 'Student is not enrolled in this course');
        }

        $path = $request->file('file')->store('assignments/'.$assignment->id, 'public');

        $submission = AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $student->id],
            [
                'file' => $path,
                'note' => $validated['note'] ?? null,
                'submitted_at' => now(),
                'state' => AssignmentSubmission::STATE_SUBMITTED,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment submitted',
            'data' => [
                'id' => $submission->id,
                'file_url' => asset('storage/'.$path),
                'submitted_at' => $submission->submitted_at?->toDateTimeString(),
            ],
        ], 201);
    }

    /**
     * GET /student/id-card
     */
    public function idCard(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);
        $student->load('batch', 'program', 'academicYear');

        $payload = $student->student_code.'|'.$student->full_name;
        $qr = base64_encode(hash('sha256', $payload, true));

        $validUntil = $student->academicYear?->end_date ?? now()->addYear();

        return response()->json([
            'status' => 'success',
            'data' => [
                'student_name' => $student->full_name,
                'student_code' => $student->student_code,
                'batch' => $student->batch?->name,
                'program' => $student->program?->name,
                'photo_url' => $student->photo,
                'qr_code' => $qr,
                'academic_year' => $student->academicYear?->name,
                'valid_until' => $validUntil?->toDateString(),
            ],
        ]);
    }

    /**
     * GET /student/certificate
     */
    public function certificate(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $marksheets = $student->marksheets()->with('lines.subject', 'exam')->where('state', 'done')->get();

        $subjects = $marksheets->flatMap->lines->map(function ($line) {
            return [
                'subject' => $line->subject?->name ?? '—',
                'marks' => $line->marks,
                'max_marks' => $line->max_marks,
                'percentage' => $line->percentage,
                'grade' => $line->grade,
            ];
        });

        $all = $marksheets->flatMap->lines;
        $avg = $all->count() ? round($all->avg('percentage'), 2) : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'student_name' => $student->full_name,
                'student_code' => $student->student_code,
                'batch' => $student->batch?->name,
                'gpa' => $avg,
                'gpa_letter' => $this->gpaLetter($avg),
                'subjects' => $subjects,
            ],
        ]);
    }

    /**
     * GET /student/syllabus
     */
    public function syllabus(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $courses = $student->courses()->with('subject', 'faculty')->get();

        $result = $courses->map(function ($course) use ($student) {
            $totalSessions = ClassSession::where('course_id', $course->id)
                ->where('batch_id', $student->batch_id)
                ->count();
            $attended = $student->attendances()
                ->whereHas('sheet', fn ($q) => $q->where('state', 'done')->where('course_id', $course->id))
                ->whereIn('status', ['present', 'late'])
                ->count();

            return [
                'course_id' => $course->id,
                'subject' => $course->subject?->name ?? $course->name,
                'faculty' => $course->faculty?->full_name ?? '—',
                'syllabus' => $course->syllabus,
                'progress_percent' => $totalSessions ? round(($attended / $totalSessions) * 100, 2) : 0,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => ['subjects' => $result],
        ]);
    }

    /**
     * GET /student/chat
     */
    public function chatList(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $conversations = $student->chatMessages()
            ->with('faculty')
            ->orderByDesc('id')
            ->get()
            ->groupBy('faculty_id')
            ->map(function ($messages) {
                $last = $messages->first();
                $unread = $messages->where('sender', 'faculty')->whereNull('read_at')->count();

                return [
                    'faculty_id' => $last->faculty_id,
                    'faculty_name' => $last->faculty?->full_name ?? '—',
                    'last_message' => $last->body,
                    'last_message_at' => $last->created_at?->toDateTimeString(),
                    'unread' => $unread,
                ];
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'data' => $conversations,
        ]);
    }

    /**
     * GET /student/chat/{facultyId}
     */
    public function chatShow(Request $request, int $facultyId): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $messages = $student->chatMessages()
            ->where('faculty_id', $facultyId)
            ->orderByDesc('id')
            ->limit(100)
            ->get()
            ->reverse()
            ->values()
            ->map(fn ($m) => [
                'id' => $m->id,
                'sender' => $m->sender,
                'body' => $m->body,
                'created_at' => $m->created_at?->toDateTimeString(),
            ]);

        ChatMessage::where('student_id', $student->id)
            ->where('faculty_id', $facultyId)
            ->where('sender', 'faculty')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'data' => $messages,
        ]);
    }

    /**
     * POST /student/chat/send
     */
    public function chatSend(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $validated = $request->validate([
            'faculty_id' => 'required|integer|exists:faculties,id',
            'message' => 'required|string|max:5000',
        ]);

        $hasCourse = Course::where('faculty_id', $validated['faculty_id'])
            ->whereHas('students', fn ($q) => $q->where('student_id', $student->id))
            ->exists();

        if (! $hasCourse) {
            abort(403, 'No course with this faculty member');
        }

        $message = $student->chatMessages()->create([
            'faculty_id' => $validated['faculty_id'],
            'sender' => ChatMessage::SENDER_STUDENT,
            'body' => $validated['message'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent',
            'data' => [
                'id' => $message->id,
                'sender' => $message->sender,
                'body' => $message->body,
                'created_at' => $message->created_at?->toDateTimeString(),
            ],
        ], 201);
    }

    /**
     * POST /student/feedback
     */
    public function feedback(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'category' => 'nullable|string|max:100',
        ]);

        $feedback = Feedback::create([
            'student_id' => $student->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'state' => 'submitted',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Feedback submitted',
            'data' => ['id' => $feedback->id],
        ], 201);
    }

    /**
     * GET /student/points — Gamification stats (points, rank, badges).
     */
    public function points(Request $request): JsonResponse
    {
        $student = $this->resolveStudent($request);

        $stats = app(GamificationService::class)->getStudentStats($student);

        return response()->json([
            'status' => 'success',
            'data' => $stats,
        ]);
    }

    protected function gpaLetter(float $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'A',
            $percentage >= 80 => 'B',
            $percentage >= 70 => 'C',
            $percentage >= 60 => 'D',
            default => 'F',
        };
    }

    /**
     * POST /admin/students/{student}/reset-password
     */
    public function resetStudentPassword(\App\Models\Student $student, \Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $apiUser = \App\Models\ApiUser::where('student_id', $student->id)->first();

        if (! $apiUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يوجد حساب مobile لهذا الطالب',
            ], 404);
        }

        $apiUser->password = $request->new_password;
        $apiUser->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح',
            'data' => ['username' => $apiUser->username],
        ]);
    }
}
