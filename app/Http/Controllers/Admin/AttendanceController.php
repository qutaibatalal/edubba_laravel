<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLine;
use App\Models\AttendanceSheet;
use App\Models\Batch;
use App\Models\ClassSession;
use App\Models\Student;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Daily attendance dashboard — pick a batch + date, then mark presence.
     */
    public function index(Request $request): View
    {
        $batches = Batch::orderBy('name')->get();
        $date = $request->input('date', today()->toDateString());

        $session = null;
        $sheet = null;

        $sessionId = $request->integer('session_id');
        $batchId = $request->integer('batch_id');

        if ($sessionId) {
            $session = ClassSession::with(['batch', 'course', 'subject', 'faculty'])->find($sessionId);
            $sheet = $session ? AttendanceService::createSheetForSession($session) : null;
        }

        // Sessions for the chosen batch+date
        $sessions = ClassSession::whereDate('date', $date)
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
            ->with(['batch', 'course', 'subject'])
            ->orderBy('start_time')
            ->get();

        $summary = $this->summary();

        return view('admin.attendance.index', compact('batches', 'date', 'sessions', 'session', 'sheet', 'summary', 'batchId'));
    }

    /**
     * Mark attendance for a sheet.
     */
    public function mark(Request $request, AttendanceSheet $sheet): RedirectResponse
    {
        $request->validate([
            'statuses' => ['required', 'array'],
            'statuses.*' => ['required', 'in:present,absent,late,leave'],
        ]);

        AttendanceService::markSheet($sheet, $request->input('statuses'));

        return back()->with('success', 'تم تسجيل الحضور بنجاح.');
    }

    /**
     * Open a sheet for editing (mark done sheet again).
     */
    public function edit(Request $request, AttendanceSheet $sheet): View
    {
        $sheet->load(['session', 'batch', 'course', 'lines.student']);

        return view('admin.attendance.edit', compact('sheet'));
    }

    /**
     * Monthly attendance report per batch.
     */
    public function monthly(Request $request): View
    {
        $batches = Batch::orderBy('name')->get();
        $month = $request->input('month', now()->format('Y-m'));
        $batchId = $request->integer('batch_id');

        $start = $month.'-01';
        $end = now()->parse($start)->endOfMonth()->toDateString();

        $rows = collect();

        $sheets = AttendanceSheet::whereBetween('date', [$start, $end])
            ->where('state', AttendanceSheet::STATE_DONE)
            ->with('lines')
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
            ->get();

        $students = Student::admitted()
            ->when($batchId, fn ($q) => $q->where('batch_id', $batchId))
            ->with('batch')
            ->get();

        foreach ($students as $student) {
            $lines = $sheets->flatMap(fn ($s) => $s->lines->where('student_id', $student->id));
            $total = $lines->count();
            $present = $lines->whereIn('status', [AttendanceLine::STATUS_PRESENT, AttendanceLine::STATUS_LATE])->count();

            $rows->push((object) [
                'student' => $student,
                'total' => $total,
                'present' => $present,
                'absent' => $lines->where('status', AttendanceLine::STATUS_ABSENT)->count(),
                'late' => $lines->where('status', AttendanceLine::STATUS_LATE)->count(),
                'leave' => $lines->where('status', AttendanceLine::STATUS_LEAVE)->count(),
                'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
            ]);
        }

        $rows = $rows->sortByDesc(fn ($r) => $r->percentage);

        return view('admin.attendance.monthly', compact('batches', 'month', 'rows', 'batchId'));
    }

    /**
     * Today's summary strip for the attendance dashboard.
     */
    protected function summary(): array
    {
        $today = today();
        $sheetsToday = AttendanceSheet::where('state', AttendanceSheet::STATE_DONE)->whereDate('date', $today)->count();
        $linesToday = AttendanceLine::whereHas('sheet', fn ($q) => $q->whereDate('date', $today)->where('state', AttendanceSheet::STATE_DONE));

        return [
            'sheets_today' => $sheetsToday,
            'present_today' => (clone $linesToday)->whereIn('status', [AttendanceLine::STATUS_PRESENT, AttendanceLine::STATUS_LATE])->count(),
            'absent_today' => (clone $linesToday)->where('status', AttendanceLine::STATUS_ABSENT)->count(),
            'late_today' => (clone $linesToday)->where('status', AttendanceLine::STATUS_LATE)->count(),
        ];
    }
}
