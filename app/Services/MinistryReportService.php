<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Employee;
use App\Models\Faculty;
use App\Models\Marksheet;
use App\Models\MinistryReport;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MinistryReportService
{
    /**
     * Generate an aggregated ministry report (students by gender, batch, result).
     */
    public static function generate(AcademicYear $year, string $reportType): MinistryReport
    {
        $data = [];

        if ($reportType === 'student_counts') {
            $data['students_by_gender'] = Student::where('academic_year_id', $year->id)
                ->where('state', Student::STATE_ADMITTED)
                ->selectRaw('gender, count(*) as total')
                ->groupBy('gender')
                ->pluck('total', 'gender')
                ->toArray();

            $data['students_by_batch'] = Student::where('academic_year_id', $year->id)
                ->where('state', Student::STATE_ADMITTED)
                ->with('batch:id,name')
                ->get()
                ->groupBy(fn ($s) => $s->batch?->name ?? 'Unassigned')
                ->map->count()
                ->toArray();

            $data['total_students'] = array_sum($data['students_by_gender']);
        }

        if ($reportType === 'staff_counts') {
            $data['faculty_count'] = Faculty::where('state', Faculty::STATE_JOINED)->count();
            $data['employee_count'] = Employee::where('state', Employee::STATE_JOINED)->count();
        }

        if ($reportType === 'pass_rates') {
            $data['pass_rate'] = Marksheet::where('state', Marksheet::STATE_DONE)
                ->whereHas('exam', fn ($q) => $q->where('academic_year_id', $year->id))
                ->get()
                ->pipe(function ($marksheets) {
                    $total = $marksheets->count();
                    $passed = $marksheets->where('result', Marksheet::RESULT_PASS)->count();

                    return $total > 0 ? round(($passed / $total) * 100, 2) : 0;
                });
        }

        $report = MinistryReport::updateOrCreate(
            ['academic_year_id' => $year->id, 'report_type' => $reportType],
            [
                'name' => "{$reportType} - {$year->name}",
                'data' => $data,
                'state' => MinistryReport::STATE_GENERATED,
            ]
        );

        return $report;
    }

    /**
     * Generate monthly attendance PDF for a batch (ministry format).
     */
    public static function generateAttendancePdf($batch, string $month): string
    {
        $start = Carbon::parse($month.'-01');
        $end = $start->copy()->endOfMonth();

        $students = Student::where('batch_id', $batch->id)
            ->where('state', Student::STATE_ADMITTED)
            ->with(['attendanceLines' => fn ($q) =>
                $q->whereBetween('created_at', [$start->toDateTimeString(), $end->toDateTimeString()])
            ])
            ->get()
            ->map(fn ($s) => (object) [
                'student' => $s,
                'batch' => $batch->name,
                'total' => $s->attendanceLines->count(),
                'present' => $s->attendanceLines->where('state', 'present')->count(),
                'late' => $s->attendanceLines->where('state', 'late')->count(),
                'absent' => $s->attendanceLines->where('state', 'absent')->count(),
                'leave' => $s->attendanceLines->where('state', 'leave')->count(),
                'percentage' => $s->attendanceLines->count() > 0
                    ? round(($s->attendanceLines->where('state', 'present')->count() / $s->attendanceLines->count()) * 100, 1)
                    : 0,
            ]);

        $html = PdfService::html('pdf.attendance-report', [
            'data' => $students,
            'batch' => $batch,
            'month' => $start,
        ]);

        $pdf = PdfService::mpdf();
        $pdf->WriteHTML($html);

        $path = "reports/attendance_{$batch->id}_{$month}.pdf";
        Storage::put($path, $pdf->Output('', 'S'));

        return $path;
    }

    /**
     * Generate results PDF for a batch + term (ministry format).
     */
    public static function generateResultsPdf($batch, $term): string
    {
        $marksheets = Marksheet::where('batch_id', $batch->id)
            ->where('state', Marksheet::STATE_DONE)
            ->with(['lines.student', 'subject'])
            ->get();

        $students = $marksheets->flatMap(fn ($m) => $m->lines->pluck('student')->flatten())
            ->unique('id')
            ->map(fn ($s) => (object) [
                'student' => $s,
                'batch' => $batch->name,
                'marksheets' => $marksheets->filter(fn ($m) => $m->lines->contains('student_id', $s->id)),
            ]);

        $html = PdfService::html('pdf.result-card', [
            'data' => $students,
            'batch' => $batch,
            'term' => $term,
        ]);

        $pdf = PdfService::mpdf();
        $pdf->WriteHTML($html);

        $path = "reports/results_{$batch->id}_{$term->id}.pdf";
        Storage::put($path, $pdf->Output('', 'S'));

        return $path;
    }
}
