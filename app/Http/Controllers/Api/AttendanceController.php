<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceSheetResource;
use App\Models\ApiUser;
use App\Models\ClassSession;
use App\Models\Student;
use App\Services\AttendanceService;
use App\Services\FaceRecognitionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * POST /faculty/session/{id}/attendance
     * Body: { "statuses": { student_id: "present|absent|late|leave", ... } }
     */
    public function mark(Request $request, int $sessionId): JsonResponse
    {
        $session = ClassSession::findOrFail($sessionId);

        if (! $request->user()->faculty || $session->faculty_id !== $request->user()->faculty_id) {
            if ($request->user()->role !== ApiUser::ROLE_ADMIN) {
                abort(403, 'Not allowed');
            }
        }

        $request->validate([
            'statuses' => 'required|array',
            'statuses.*' => 'required|in:present,absent,late,leave',
        ]);

        $sheet = AttendanceService::createSheetForSession($session);
        AttendanceService::markSheet($sheet, $request->input('statuses'));

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance saved',
            'data' => new AttendanceSheetResource($sheet->load('lines.student')),
        ]);
    }

    /**
     * GET /student/qr-token
     * Returns a signed payload the faculty can scan to mark the student present.
     */
    public function qrToken(Request $request): JsonResponse
    {
        $user = $request->user();
        $student = $user->student;

        if (! $student) {
            abort(404, 'Student profile not found');
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'qr' => $this->signStudent($student),
                'student_id' => $student->id,
            ],
        ]);
    }

    /**
     * POST /faculty/session/{id}/attendance/qr
     * Body: { "qr": "<signed-payload>" } — marks the student present via QR.
     */
    public function markByQr(Request $request, int $sessionId): JsonResponse
    {
        $session = ClassSession::findOrFail($sessionId);

        if (! $request->user()->faculty || $session->faculty_id !== $request->user()->faculty_id) {
            if ($request->user()->role !== ApiUser::ROLE_ADMIN) {
                abort(403, 'Not allowed');
            }
        }

        $request->validate(['qr' => 'required|string']);

        $student = $this->verifySignedStudent((string) $request->qr);

        $sheet = AttendanceService::createSheetForSession($session);
        AttendanceService::markSheet($sheet, [(string) $student->id => 'present']);

        return response()->json([
            'status' => 'success',
            'message' => "تم تسجيل حضور {$student->full_name}",
            'data' => new AttendanceSheetResource($sheet->load('lines.student')),
        ]);
    }

    /**
     * POST /faculty/attendance/face-mark
     * Body: { "session_id": N, "image_base64": "..." }
     * Matches the face via the external microservice, falls back to a manual
     * confirmation state when the service is unavailable.
     */
    public function markByFace(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|integer|exists:class_sessions,id',
            'image_base64' => 'required|string',
        ]);

        $session = ClassSession::findOrFail($request->session_id);

        if (! $request->user()->faculty || $session->faculty_id !== $request->user()->faculty_id) {
            if ($request->user()->role !== ApiUser::ROLE_ADMIN) {
                abort(403, 'Not allowed');
            }
        }

        $result = FaceRecognitionService::identify((string) $request->image_base64);

        // Fallback: service disabled/unreachable → require manual confirmation.
        if (! ($result['matched'] ?? false)) {
            return response()->json([
                'status' => 'success',
                'message' => $result['error'] === 'face_recognition_disabled' || $result['error'] === 'face_service_unreachable'
                    ? 'الخدمة غير متاحة — يُرجى تسجيل الحضور يدوياً'
                    : 'لم يتم التعرف على الوجه — يُرجى التحقق يدوياً',
                'requires_confirmation' => true,
                'error' => $result['error'] ?? null,
            ], 200);
        }

        $student = Student::findOrFail((int) $result['student_id']);

        $sheet = AttendanceService::createSheetForSession($session);
        AttendanceService::markSheet($sheet, [(string) $student->id => 'present']);

        return response()->json([
            'status' => 'success',
            'message' => "تم تسجيل حضور {$student->full_name}",
            'confidence' => $result['confidence'] ?? null,
            'data' => new AttendanceSheetResource($sheet->load('lines.student')),
        ]);
    }

    /**
     * POST /faculty/attendance/face-enroll
     * Body: { "student_id": N, "image_base64": "..." } — enrolls a student face.
     */
    public function faceEnroll(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'image_base64' => 'required|string',
        ]);

        $student = Student::findOrFail($request->student_id);
        $ok = FaceRecognitionService::enroll($student, (string) $request->image_base64);

        return response()->json([
            'status' => $ok ? 'success' : 'error',
            'message' => $ok ? 'تم تسجيل الوجه بنجاح' : 'فشل تسجيل الوجه — الخدمة غير متاحة',
            'enrolled' => $ok,
        ]);
    }

    /**
     * Signed QR payload: "studentId.hmac(secret|studentId|day)" — valid for today.
     */
    protected function signStudent(Student $student): string
    {
        $day = now()->format('Y-m-d');
        $sig = hash_hmac('sha256', $student->id.'|'.$day, config('app.key'));

        return $student->id.'.'.$sig;
    }

    protected function verifySignedStudent(string $qr): Student
    {
        [$id, $sig] = array_pad(explode('.', $qr, 2), 2, '');

        if (! $id || ! $sig) {
            abort(422, 'QR format invalid');
        }

        $day = now()->format('Y-m-d');
        $expected = hash_hmac('sha256', $id.'|'.$day, config('app.key'));

        if (! hash_equals($expected, $sig)) {
            abort(403, 'QR code invalid or expired');
        }

        return Student::findOrFail((int) $id);
    }

    /**
     * GET /faculty/attendance/qr-session/start
     * Faculty starts a QR session. Returns a QR code valid for 10 minutes.
     * Students scan this QR to mark their own attendance.
     */
    public function startQrSession(Request $request): JsonResponse
    {
        $faculty = $request->user()->faculty;

        if (! $faculty) {
            abort(404, 'Faculty profile not found');
        }

        $request->validate(['session_id' => 'required|integer|exists:class_sessions,id']);

        $session = ClassSession::findOrFail($request->session_id);

        if ($session->faculty_id !== $faculty->id) {
            abort(403, 'Not allowed');
        }

        $token = Str::uuid()->toString();

        Cache::put("qr_session_{$token}", [
            'session_id' => $session->id,
            'faculty_id' => $faculty->id,
            'expires_at' => now()->addMinutes(10)->toIso8601String(),
        ], 600);

        return response()->json([
            'status' => 'success',
            'data' => [
                'qr_token' => $token,
                'qr_url' => route('api.attendance.qr-mark', $token),
                'expires_at' => now()->addMinutes(10)->toDateTimeString(),
            ],
        ]);
    }

    /**
     * POST /attendance/qr-mark/{token}
     * Student scans the QR code to mark their attendance for the session.
     */
    public function qrMark(Request $request, string $token): JsonResponse
    {
        $session = Cache::get("qr_session_{$token}");

        if (! $session) {
            return response()->json([
                'status' => 'error',
                'message' => 'انتهت صلاحية رمز QR أو غير موجود',
            ], 410);
        }

        if (now()->gt($session['expires_at'])) {
            Cache::forget("qr_session_{$token}");

            return response()->json([
                'status' => 'error',
                'message' => 'انتهت صلاحية رمز QR',
            ], 410);
        }

        $student = $request->user()->student;

        if (! $student) {
            abort(404, 'Student profile not found');
        }

        $classSession = ClassSession::findOrFail($session['session_id']);

        $sheet = AttendanceService::createSheetForSession($classSession);
        AttendanceService::markSheet($sheet, [(string) $student->id => 'present']);

        return response()->json([
            'status' => 'success',
            'message' => "تم تسجيل حضور {$student->full_name}",
            'data' => [
                'student_name' => $student->full_name,
                'marked_at' => now()->format('H:i'),
            ],
        ]);
    }
}
