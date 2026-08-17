<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AdmissionController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusTrackingController;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\PaymentWebhookController;
use App\Http\Controllers\Api\QuestionBankController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TrainingCertificateController;
use App\Http\Controllers\Api\TutoringController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Edubba Mobile API (rebuild of edubba_mobile_api)
|--------------------------------------------------------------------------
| All responses follow the { data, status, message } contract expected by
| the existing mobile app.
*/

Route::prefix('v1')->group(function () {
    // 17.1 Authentication (public)
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:api.login');
    Route::post('/logout', [AuthController::class, 'logout']);

    // Public app config used by the login screen (branding, features).
    Route::get('/config', [CommonController::class, 'appConfig']);

    // Payment gateway callbacks (server-to-server, signature verified in controller).
    Route::post('/payments/zaincash/callback', [PaymentWebhookController::class, 'zainCashCallback'])->middleware('throttle:api.webhook');
    Route::post('/payments/qicard/callback', [PaymentWebhookController::class, 'qiCardCallback'])->middleware('throttle:api.webhook');

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        // Auth self
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/upload-photo', [AuthController::class, 'uploadPhoto'])->middleware('throttle:api.upload');

        // 17.2 Student
        Route::get('/student/dashboard', [StudentController::class, 'dashboard']);
        Route::get('/student/profile', [StudentController::class, 'profile']);
        Route::get('/student/courses', [StudentController::class, 'courses']);
        Route::get('/student/timetable', [StudentController::class, 'timetable']);
        Route::get('/student/attendance', [StudentController::class, 'attendance']);
        Route::get('/student/qr-token', [AttendanceController::class, 'qrToken']);
        Route::get('/student/exams', [StudentController::class, 'exams']);
        Route::get('/student/grades', [StudentController::class, 'grades']);
        Route::get('/student/exam-conflicts', [StudentController::class, 'examConflicts']);
        Route::get('/student/results', [StudentController::class, 'results']);
        Route::get('/student/fees', [StudentController::class, 'fees']);
        Route::get('/student/payments', [StudentController::class, 'payments']);
        Route::get('/student/excuses', [StudentController::class, 'excuses']);
        Route::post('/student/excuse/request', [StudentController::class, 'requestExcuse']);
        Route::post('/student/assignments/submit', [StudentController::class, 'submitAssignment'])->middleware('throttle:api.upload');
        Route::get('/student/id-card', [StudentController::class, 'idCard']);
        Route::get('/student/certificate', [StudentController::class, 'certificate']);
        Route::get('/student/syllabus', [StudentController::class, 'syllabus']);
        Route::get('/student/chat', [StudentController::class, 'chatList']);
        Route::get('/student/chat/{facultyId}', [StudentController::class, 'chatShow']);
        Route::post('/student/chat/send', [StudentController::class, 'chatSend']);
        Route::post('/student/feedback', [StudentController::class, 'feedback']);

        // 17.2 Parent
        Route::get('/parent/children', [ParentController::class, 'children']);
        Route::get('/parent/child/{id}/dashboard', [ParentController::class, 'childDashboard']);
        Route::get('/parent/child/{id}/attendance', [ParentController::class, 'childAttendance']);
        Route::get('/parent/child/{id}/grades', [ParentController::class, 'childGrades']);
        Route::get('/parent/child/{id}/results', [ParentController::class, 'childResults']);
        Route::get('/parent/child/{id}/fees', [ParentController::class, 'childFees']);
        Route::post('/parent/payments/zaincash/initiate', [ParentController::class, 'initiateZainCash']);
        Route::get('/parent/payments/receipts/{id}', [ParentController::class, 'paymentReceipt']);

        // 17.3 Faculty
        Route::get('/faculty/dashboard', [FacultyController::class, 'dashboard']);
        Route::get('/faculty/courses', [FacultyController::class, 'courses']);
        Route::get('/faculty/batch/{id}/students', [FacultyController::class, 'batchStudents']);
        Route::get('/faculty/timetable', [FacultyController::class, 'timetable']);
        Route::get('/faculty/timetable/conflicts', [FacultyController::class, 'timetableConflicts']);
        Route::post('/faculty/session/{id}/attendance', [AttendanceController::class, 'mark']);
        Route::post('/faculty/session/{id}/attendance/qr', [AttendanceController::class, 'markByQr']);
        Route::post('/faculty/attendance/face-mark', [AttendanceController::class, 'markByFace']);
        Route::post('/faculty/attendance/face-enroll', [AttendanceController::class, 'faceEnroll']);
        Route::get('/faculty/exams', [FacultyController::class, 'exams']);
        Route::get('/faculty/grade-entry', [FacultyController::class, 'gradeEntry']);
        Route::post('/faculty/grade-entry/save', [FacultyController::class, 'gradeEntrySave']);
        Route::post('/faculty/marksheet/{id}/line', [ExamController::class, 'enterMarks']);
        Route::post('/faculty/marksheet/{id}/finalize', [ExamController::class, 'finalize']);
        Route::post('/faculty/assignments/create', [FacultyController::class, 'createAssignment']);
        Route::post('/faculty/notifications/send', [FacultyController::class, 'sendNotification'])->middleware('throttle:api.whatsapp');
        Route::get('/faculty/study-groups', [TutoringController::class, 'myGroups']);

        // 17.4 Tutoring
        Route::get('/tutoring/subscriptions', [TutoringController::class, 'subscriptions']);
        Route::get('/tutoring/groups', [TutoringController::class, 'groups']);
        Route::get('/tutoring/group/{id}/sessions', [TutoringController::class, 'groupSessions']);
        Route::post('/tutoring/session/{id}/attendance', [TutoringController::class, 'markSession']);
        Route::get('/tutoring/wallet', [TutoringController::class, 'wallet']);
        Route::get('/tutoring/leads', [TutoringController::class, 'leads']);

        // Block 8 — Tutoring marketplace API
        Route::get('/tutoring/packages', [TutoringController::class, 'packages']);

        // Block 9 — Training certificates API
        Route::get('/training/certificates', [TrainingCertificateController::class, 'index']);
        Route::get('/training/certificates/enrollment/{enrollmentId}', [TrainingCertificateController::class, 'show']);
        Route::get('/training/certificates/download/{enrollmentId}', [TrainingCertificateController::class, 'download']);

        // 17.5 Common / System
        Route::get('/academic-years', [CommonController::class, 'academicYears']);
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/register-device', [NotificationController::class, 'registerDevice']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll']);
        Route::get('/feedback/forms', [FeedbackController::class, 'forms']);
        Route::post('/feedback/submit', [FeedbackController::class, 'submit']);

        // Block 5 — Ministry question bank (instant grading)
        Route::get('/question-bank', [QuestionBankController::class, 'index']);
        Route::post('/question-bank/practice', [QuestionBankController::class, 'practice']);

        // Block 5 — School bus live tracking
        Route::middleware('role.admin')->group(function () {
            Route::post('/bus/{vehicle}/location', [BusTrackingController::class, 'updateLocation']);
        });
        Route::get('/parent/bus-tracking', [BusTrackingController::class, 'tracking']);

        // 3.6 Analytics
        Route::get('/analytics/attendance-trends', [AnalyticsController::class, 'attendanceTrends']);
        Route::get('/analytics/gpa-trends', [AnalyticsController::class, 'gpaTrends']);

        // 3.7 Library
        Route::get('/library/books', [LibraryController::class, 'books']);
        Route::post('/library/books/{id}/reserve', [LibraryController::class, 'reserve']);
        Route::get('/library/my-books', [LibraryController::class, 'myBooks']);

        // Admin endpoints (role-gated)
        Route::prefix('admin')->middleware('role.admin')->group(function () {
            Route::get('/admissions', [AdmissionController::class, 'index']);
            Route::post('/admissions', [AdmissionController::class, 'store']);
            Route::post('/admissions/{id}/submit', [AdmissionController::class, 'submit']);
            Route::post('/admissions/{id}/approve', [AdmissionController::class, 'approve']);
            Route::post('/admissions/{id}/reject', [AdmissionController::class, 'reject']);
            Route::post('/admissions/{id}/admit', [AdmissionController::class, 'admit']);

            Route::get('/fee-structures', [AdminController::class, 'feeStructures']);
            Route::post('/fee-structures', [AdminController::class, 'storeFeeStructure']);
            Route::post('/fee-structures/{id}/generate-invoices', [AdminController::class, 'generateInvoices']);
            Route::get('/invoices/overdue', [AdminController::class, 'overdueInvoices']);

            Route::get('/ministry-reports', [AdminController::class, 'ministryReports']);
            Route::post('/ministry-reports/generate', [AdminController::class, 'generateMinistryReport']);

            Route::post('/timetable/generate', [AdminController::class, 'generateSessions']);
            Route::post('/subscriptions/{id}/renew', [AdminController::class, 'renewSubscription']);
            Route::post('/notifications/absence-alerts', [AdminController::class, 'sendAbsenceAlerts']);
        });
    });
});
