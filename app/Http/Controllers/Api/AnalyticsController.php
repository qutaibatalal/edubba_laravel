<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Models\AttendanceLine;
use App\Models\AttendanceSheet;
use App\Models\Marksheet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * GET /analytics/attendance-trends
     */
    public function attendanceTrends(Request $request): JsonResponse
    {
        $user = $request->user();
        $student = match ($user->role) {
            ApiUser::ROLE_STUDENT => $user->student,
            ApiUser::ROLE_PARENT => $user->parent?->children()->first(),
            default => null,
        };

        if (! $student) {
            abort(404, 'No student linked to this account');
        }

        $rows = AttendanceLine::join('attendance_sheets', 'attendance_lines.attendance_sheet_id', '=', 'attendance_sheets.id')
            ->select(DB::raw('DATE(attendance_sheets.date) as day'), DB::raw('COUNT(*) as total'), DB::raw('SUM(CASE WHEN attendance_lines.status IN ("present","late") THEN 1 ELSE 0 END) as present'))
            ->where('attendance_lines.student_id', $student->id)
            ->where('attendance_sheets.state', AttendanceSheet::STATE_DONE)
            ->groupBy(DB::raw('DATE(attendance_sheets.date)'))
            ->orderBy(DB::raw('DATE(attendance_sheets.date)'))
            ->get();

        $labels = $rows->pluck('day')->values();
        $data = $rows->map(fn ($r) => $r->total > 0 ? round(($r->present / $r->total) * 100, 2) : 0)->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'labels' => $labels,
                'data' => $data,
                'avg' => $data->count() ? round($data->avg(), 2) : 0,
            ],
        ]);
    }

    /**
     * GET /analytics/gpa-trends
     */
    public function gpaTrends(Request $request): JsonResponse
    {
        $user = $request->user();
        $student = match ($user->role) {
            ApiUser::ROLE_STUDENT => $user->student,
            ApiUser::ROLE_PARENT => $user->parent?->children()->first(),
            default => null,
        };

        if (! $student) {
            abort(404, 'No student linked to this account');
        }

        $marksheets = Marksheet::with('exam')
            ->where('student_id', $student->id)
            ->where('state', Marksheet::STATE_DONE)
            ->orderBy('id')
            ->get();

        $semesters = $marksheets->map(function ($m) {
            $term = $m->exam?->term?->name;

            return $term ?: ('Exam #'.$m->exam_id);
        })->values();

        $gpa = $marksheets->pluck('percentage')->map(fn ($p) => round((float) $p, 2))->values();

        $avg = $gpa->count() ? round($gpa->avg(), 2) : 0;
        $trend = 'flat';
        if ($gpa->count() >= 2) {
            $first = $gpa[0];
            $last = $gpa[$gpa->count() - 1];
            $trend = $last > $first ? 'up' : ($last < $first ? 'down' : 'flat');
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'semesters' => $semesters,
                'gpa' => $gpa,
                'avg' => $avg,
                'trend' => $trend,
            ],
        ]);
    }
}
