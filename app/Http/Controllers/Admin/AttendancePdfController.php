<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLine;
use App\Models\AttendanceSheet;
use App\Models\Student;
use App\Services\PdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AttendancePdfController extends Controller
{
    public function show(Request $request): View
    {
        $month = $request->input('month') ? Carbon::createFromFormat('m-Y', $request->input('month')) : Carbon::now()->subMonth();
        $batchId = $request->input('batch_id');

        $query = Student::where('state', Student::STATE_ADMITTED);

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        $students = $query->get();

        $batches = Student::where('state', Student::STATE_ADMITTED)
            ->distinct('batch_id')
            ->pluck('batch_id')
            ->filter()
            ->map(fn ($id) => app('App\Models\Batch')->find($id));

        $batch = $batchId ? \App\Models\Batch::find($batchId) : null;

        $from = $month->copy()->startOfMonth();
        $to = $month->copy()->endOfMonth();

        $sheets = AttendanceSheet::whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->where('state', AttendanceSheet::STATE_DONE)
            ->get();

        $data = collect();

        foreach ($students as $student) {
            $lines = AttendanceLine::where('student_id', $student->id)
                ->whereHas('sheet', fn ($q) => $q->whereBetween('date', [$from, $to]))
                ->get();

            $total = $lines->count();
            $present = $lines->whereIn('status', [AttendanceLine::STATUS_PRESENT, AttendanceLine::STATUS_LATE])->count();
            $absent = $lines->where('status', AttendanceLine::STATUS_ABSENT)->count();
            $late = $lines->where('status', AttendanceLine::STATUS_LATE)->count();
            $leave = $lines->where('status', AttendanceLine::STATUS_LEAVE)->count();

            $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;

            $data->push((object) [
                'student' => $student,
                'batch' => $student->batch?->name,
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'leave' => $leave,
                'percentage' => $percentage,
            ]);
        }

        return view('admin.attendance.pdf', compact('data', 'month', 'batches', 'batchId', 'batch'));
    }

    public function download(Request $request): Response
    {
        $view = $this->show($request);

        $month = $view->getData()['month'];
        $batchId = $view->getData()['batchId'] ?? null;
        $batch = $batchId ? app('App\Models\Batch')->find($batchId) : null;

        return PdfService::download('pdf.attendance-report', [
            'data' => $view->getData()['data'],
            'month' => $month,
            'batch' => $batch,
        ], 'report-attendance-'.$month->format('m-Y').'.pdf');
    }
}
