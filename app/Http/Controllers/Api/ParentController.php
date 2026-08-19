<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\MarksheetResource;
use App\Http\Resources\ParentResource;
use App\Http\Resources\StudentResource;
use App\Models\MarksheetLine;
use App\Models\Payment;
use App\Services\AttendanceService;
use App\Services\Payments\ZainCashService;
use App\Services\PdfService;
use App\Services\ReceiptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ParentController extends Controller
{
    /**
     * GET /parent/children
     */
    public function children(Request $request): JsonResponse
    {
        $parent = $request->user()->parent;

        if (! $parent) {
            abort(404, 'Parent profile not found');
        }

        $parent->load(['children' => fn ($q) => $q->with('batch', 'program', 'academicYear')]);

        return response()->json([
            'status' => 'success',
            'data' => new ParentResource($parent),
        ]);
    }

    /**
     * GET /parent/child/{id}/attendance — eager loaded.
     */
    public function childAttendance(Request $request, int $childId): JsonResponse
    {
        $student = $this->verifyChild($request, $childId);

        $lines = $student->attendances()->with(['sheet.course', 'sheet.subject', 'sheet.faculty'])->latest('id')->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => [
                'percentage' => AttendanceService::attendancePercentage($student),
                'records' => AttendanceResource::collection($lines),
            ],
        ]);
    }

    /**
     * GET /parent/child/{id}/results
     */
    public function childResults(Request $request, int $childId): JsonResponse
    {
        $student = $this->verifyChild($request, $childId);

        $marksheets = $student->marksheets()->with('lines', 'exam')->where('state', 'done')->get();

        return response()->json([
            'status' => 'success',
            'data' => MarksheetResource::collection($marksheets),
        ]);
    }

    /**
     * GET /parent/child/{id}/fees
     */
    public function childFees(Request $request, int $childId): JsonResponse
    {
        $student = $this->verifyChild($request, $childId);

        return response()->json([
            'status' => 'success',
            'data' => InvoiceResource::collection($student->invoices()->with('lines')->get()),
        ]);
    }

    /**
     * GET /parent/child/{id}/dashboard
     */
    public function childDashboard(Request $request, int $childId): JsonResponse
    {
        $student = $this->verifyChild($request, $childId);
        $student->load('batch', 'program', 'academicYear');

        $marksheets = $student->marksheets()->where('state', 'done')->get();
        $avg = $marksheets->flatMap->lines->avg('percentage') ?: 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'student' => new StudentResource($student),
                'summary' => [
                    'attendance_percentage' => AttendanceService::attendancePercentage($student),
                    'gpa' => round((float) $avg, 2),
                    'fees_balance' => (float) $student->invoices()->where('state', '!=', 'paid')->sum('balance'),
                    'courses' => $student->courses()->count(),
                ],
            ],
        ]);
    }

    /**
     * GET /parent/child/{id}/grades
     */
    public function childGrades(Request $request, int $childId): JsonResponse
    {
        $student = $this->verifyChild($request, $childId);

        $lines = MarksheetLine::with('subject', 'marksheet.exam')
            ->whereHas('marksheet', fn ($q) => $q->where('student_id', $student->id)->where('state', 'done'))
            ->get();

        $subjects = $lines->groupBy('subject_id')->map(function ($group) {
            $subject = $group->first()->subject;

            return [
                'subject' => $subject?->name ?? '—',
                'marks' => round($group->avg('marks'), 2),
                'max_marks' => round($group->avg('max_marks'), 2),
                'percentage' => round($group->avg('percentage'), 2),
                'grade' => $group->last()->grade,
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'gpa' => $lines->count() ? round($lines->avg('percentage'), 2) : 0,
                'subjects' => $subjects,
            ],
        ]);
    }

    /**
     * POST /parent/payments/zaincash/initiate
     * Body: { invoice_id, amount? }
     */
    public function initiateZainCash(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
            'amount' => 'nullable|numeric|min:1',
        ]);

        $parent = $request->user()->parent;
        if (! $parent) {
            abort(404, 'Parent profile not found');
        }

        $invoice = $parent->children()
            ->with('invoices')
            ->get()
            ->flatMap->invoices
            ->firstWhere('id', $validated['invoice_id']);

        if (! $invoice) {
            abort(404, 'Invoice not found for your children');
        }

        $amount = $validated['amount'] ?? (float) $invoice->balance;
        $phone = $parent->mobile ?? $parent->phone ?? $parent->children()->first()?->mobile;

        if (! $phone) {
            abort(422, 'No phone number on file for payment');
        }

        $orderId = 'INV-'.$invoice->id.'-'.strtoupper(Str::random(6));

        $payment = Payment::create([
            'reference' => $orderId,
            'invoice_id' => $invoice->id,
            'student_id' => $invoice->student_id,
            'parent_id' => $parent->id,
            'amount' => $amount,
            'method' => Payment::METHOD_WALLET,
            'gateway' => 'zaincash',
            'state' => Payment::STATE_DRAFT,
            'date' => now()->toDateString(),
        ]);

        $zain = new ZainCashService;
        $result = $zain->createTransaction($orderId, $amount, $phone);

        $payment->update(['transaction_id' => $result['transaction_id']]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'payment_id' => $payment->id,
                'transaction_id' => $result['transaction_id'],
                'redirect_url' => $result['redirect_url'],
            ],
        ], 201);
    }

    /**
     * GET /parent/payments/receipts/{id}
     */
    public function paymentReceipt(Request $request, int $paymentId): JsonResponse
    {
        $parent = $request->user()->parent;
        if (! $parent) {
            abort(404, 'Parent profile not found');
        }

        $childIds = $parent->children()->pluck('students.id');

        $payment = Payment::with('receipt', 'student', 'invoice')
            ->where('id', $paymentId)
            ->whereIn('student_id', $childIds)
            ->first();

        if (! $payment) {
            abort(404, 'Receipt not found');
        }

        $receipt = $payment->receipt;

        if (! $receipt) {
            $receipt = ReceiptService::create($payment);
        }

        if ($receipt->document && Storage::disk('local')->exists($receipt->document)) {
            $pdf = Storage::disk('local')->get($receipt->document);
        } else {
            $pdf = PdfService::render('pdf.receipt', ['receipt' => $receipt]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'receipt_no' => $receipt->receipt_no,
                'amount' => $receipt->amount,
                'date' => $receipt->date?->toDateString(),
                'pdf_base64' => base64_encode($pdf),
            ],
        ]);
    }

    protected function verifyChild(Request $request, int $childId)
    {
        $parent = $request->user()->parent;

        if (! $parent) {
            abort(404, 'Parent profile not found');
        }

        $student = $parent->children()->with('batch', 'program')->find($childId);

        if (! $student) {
            abort(404, 'Child not found');
        }

        return $student;
    }
}
