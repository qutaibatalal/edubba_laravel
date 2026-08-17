<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Employee;
use App\Models\Faculty;
use App\Models\Marksheet;
use App\Models\MinistryReport;
use App\Models\Student;

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
}
