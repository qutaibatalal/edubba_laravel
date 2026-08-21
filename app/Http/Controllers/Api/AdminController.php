<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\MinistryReportResource;
use App\Models\AcademicYear;
use App\Models\ApiUser;
use App\Models\Batch;
use App\Models\FeeStructure;
use App\Models\MinistryReport;
use App\Models\Subscription;
use App\Services\FeeService;
use App\Services\MinistryReportService;
use App\Services\NotificationService;
use App\Services\PdfService;
use App\Services\SubscriptionService;
use App\Services\TimetableService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * GET /admin/fee-structures
     */
    public function feeStructures(Request $request): JsonResponse
    {
        $structures = FeeStructure::with('lines', 'batch', 'program', 'academicYear')->get();

        return response()->json([
            'status' => 'success',
            'data' => $structures,
        ]);
    }

    /**
     * POST /admin/fee-structures
     */
    public function storeFeeStructure(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'academic_year_id' => 'nullable|integer|exists:academic_years,id',
            'batch_id' => 'nullable|integer|exists:batches,id',
            'program_id' => 'nullable|integer|exists:programs,id',
            'lines' => 'nullable|array',
            'lines.*.name' => 'required_with:lines|string',
            'lines.*.amount' => 'required_with:lines|numeric|min:0',
            'lines.*.type' => 'nullable|string',
        ]);

        $structure = FeeStructure::create([
            'name' => $validated['name'],
            'academic_year_id' => $validated['academic_year_id'] ?? null,
            'batch_id' => $validated['batch_id'] ?? null,
            'program_id' => $validated['program_id'] ?? null,
            'active' => true,
        ]);

        if (! empty($validated['lines'])) {
            foreach ($validated['lines'] as $index => $line) {
                $structure->lines()->create([
                    'name' => $line['name'],
                    'amount' => $line['amount'],
                    'type' => $line['type'] ?? null,
                    'sequence' => $index,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Fee structure created',
            'data' => $structure->load('lines'),
        ], 201);
    }

    /**
     * POST /admin/fee-structures/{id}/generate-invoices
     */
    public function generateInvoices(Request $request, int $id): JsonResponse
    {
        $structure = FeeStructure::findOrFail($id);
        $count = FeeService::generateInvoicesForBatch($structure);

        return response()->json([
            'status' => 'success',
            'message' => "Generated {$count} invoices",
            'data' => ['count' => $count],
        ]);
    }

    /**
     * GET /admin/invoices (overdue)
     */
    public function overdueInvoices(Request $request): JsonResponse
    {
        $invoices = FeeService::overdueInvoices();

        return response()->json([
            'status' => 'success',
            'data' => InvoiceResource::collection($invoices),
        ]);
    }

    /**
     * POST /admin/ministry-reports/generate
     * Body: { academic_year_id, report_type }
     */
    public function generateMinistryReport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'report_type' => 'required|in:student_counts,staff_counts,pass_rates',
        ]);

        $year = AcademicYear::findOrFail($validated['academic_year_id']);
        $report = MinistryReportService::generate($year, $validated['report_type']);

        return response()->json([
            'status' => 'success',
            'message' => 'Ministry report generated',
            'data' => new MinistryReportResource($report),
        ]);
    }

    /**
     * GET /admin/ministry-reports
     */
    public function ministryReports(Request $request): JsonResponse
    {
        $reports = MinistryReport::with('academicYear', 'term')->orderByDesc('id')->get();

        return response()->json([
            'status' => 'success',
            'data' => MinistryReportResource::collection($reports),
        ]);
    }

    /**
     * POST /admin/timetable/generate
     * Body: { date }
     */
    public function generateSessions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        $count = TimetableService::generateSessionsForDate($validated['date']);

        return response()->json([
            'status' => 'success',
            'message' => "Generated {$count} sessions",
            'data' => ['count' => $count],
        ]);
    }

    /**
     * POST /admin/subscriptions/{id}/renew
     */
    public function renewSubscription(Request $request, int $id): JsonResponse
    {
        $subscription = Subscription::findOrFail($id);
        $renewal = SubscriptionService::renew($subscription);

        return response()->json([
            'status' => 'success',
            'message' => 'Subscription renewed',
            'data' => $renewal,
        ]);
    }

    /**
     * POST /admin/notifications/absence-alerts
     */
    public function sendAbsenceAlerts(Request $request): JsonResponse
    {
        $threshold = (float) ($request->input('threshold', 75));
        $count = NotificationService::sendAbsenceAlerts($threshold);

        return response()->json([
            'status' => 'success',
            'message' => "Sent {$count} absence alerts",
            'data' => ['count' => $count],
        ]);
    }

    /**
     * GET /admin/ministry-reports/attendance-pdf?batch_id=X&month=2026-09
     */
    public function ministryAttendancePdf(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|integer|exists:batches,id',
            'month' => 'required|date_format:Y-m',
        ]);

        $batch = Batch::findOrFail($validated['batch_id']);
        $path = MinistryReportService::generateAttendancePdf($batch, $validated['month']);

        $filename = "attendance_{$batch->name}_{$validated['month']}.pdf";

        return response()->disk('local')->download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * GET /admin/ministry-reports/results-pdf?batch_id=X&term_id=X
     */
    public function ministryResultsPdf(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|integer|exists:batches,id',
            'term_id' => 'required|integer|exists:terms,id',
        ]);

        $batch = Batch::findOrFail($validated['batch_id']);
        $term = \App\Models\Term::findOrFail($validated['term_id']);
        $path = MinistryReportService::generateResultsPdf($batch, $term);

        $filename = "results_{$batch->name}_{$term->name}.pdf";

        return response()->disk('local')->download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * POST /admin/users/{user}/reset-password
     * Reset any API user's password (student, parent, faculty, admin)
     */
    public function resetUserPassword(ApiUser $user, Request $request): JsonResponse
    {
        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $user->password = $request->new_password;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح',
            'data' => ['username' => $user->username, 'role' => $user->role],
        ]);
    }

    /**
     * GET /admin/users/search?q=username
     * Search API users by username for password reset
     */
    public function searchUsers(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|min:1']);

        $users = ApiUser::where('username', 'like', '%'.$request->q.'%')
            ->select('id', 'username', 'role', 'active')
            ->limit(20)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }
}
