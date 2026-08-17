<?php

namespace App\Services;

use App\Models\AttendanceLine;
use App\Models\AttendanceSheet;
use App\Models\ClassSession;
use App\Models\Holiday;
use App\Models\IraqiCalendar;
use App\Models\Student;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Create an attendance sheet for a session (if not already present),
     * pre-populating one line per student of the batch.
     */
    public static function createSheetForSession(ClassSession $session): AttendanceSheet
    {
        return DB::transaction(function () use ($session) {
            $sheet = AttendanceSheet::firstOrCreate(
                ['session_id' => $session->id],
                [
                    'batch_id' => $session->batch_id,
                    'course_id' => $session->course_id,
                    'faculty_id' => $session->faculty_id,
                    'date' => $session->date,
                    'state' => AttendanceSheet::STATE_DRAFT,
                ]
            );

            if ($sheet->lines()->count() === 0 && $session->batch) {
                $students = Student::where('batch_id', $session->batch_id)->where('state', Student::STATE_ADMITTED)->get();

                foreach ($students as $student) {
                    AttendanceLine::firstOrCreate(
                        ['attendance_sheet_id' => $sheet->id, 'student_id' => $student->id],
                        ['status' => AttendanceLine::STATUS_PRESENT, 'note' => null]
                    );
                }
            }

            return $sheet;
        });
    }

    /**
     * Mark attendance for a sheet from a mapping of student_id => status.
     */
    public static function markSheet(AttendanceSheet $sheet, array $statuses): AttendanceSheet
    {
        DB::transaction(function () use ($sheet, $statuses) {
            foreach ($statuses as $studentId => $status) {
                if (! in_array($status, [AttendanceLine::STATUS_PRESENT, AttendanceLine::STATUS_ABSENT, AttendanceLine::STATUS_LATE, AttendanceLine::STATUS_LEAVE])) {
                    throw new \InvalidArgumentException("Invalid attendance status: {$status}");
                }

                AttendanceLine::updateOrCreate(
                    ['attendance_sheet_id' => $sheet->id, 'student_id' => $studentId],
                    ['status' => $status]
                );
            }

            $sheet->state = AttendanceSheet::STATE_DONE;
            $sheet->save();
        });

        return $sheet;
    }

    /**
     * Compute the attendance percentage for a student over a given academic year,
     * excluding holidays and Iraqi-calendar holiday days.
     */
    public static function attendancePercentage(Student $student, $from = null, $to = null): float
    {
        $query = AttendanceLine::query()
            ->where('student_id', $student->id)
            ->whereHas('sheet', function ($q) use ($from, $to) {
                $q->where('state', AttendanceSheet::STATE_DONE);
                if ($from) {
                    $q->where('date', '>=', $from);
                }
                if ($to) {
                    $q->where('date', '<=', $to);
                }
            });

        $total = (clone $query)->count();
        if ($total === 0) {
            return 0;
        }

        $present = (clone $query)
            ->whereIn('status', [AttendanceLine::STATUS_PRESENT, AttendanceLine::STATUS_LATE])
            ->count();

        return round(($present / $total) * 100, 2);
    }

    /**
     * Teaching days between two dates, excluding weekends and holidays.
     */
    public static function teachingDays($from, $to): int
    {
        $days = 0;
        $cursor = Carbon::parse($from);

        $holidayDates = Holiday::query()
            ->where('date_start', '<=', $to)
            ->where('date_stop', '>=', $from)
            ->get()
            ->flatMap(fn ($h) => CarbonPeriod::create($h->date_start, $h->date_stop)->toArray())
            ->map(fn ($d) => $d->format('Y-m-d'))
            ->unique()
            ->all();

        $iraqiHolidays = IraqiCalendar::where('is_holiday', true)
            ->whereBetween('gregorian_date', [$from, $to])
            ->pluck('gregorian_date')
            ->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))
            ->all();

        while ($cursor <= Carbon::parse($to)) {
            $isWeekend = in_array($cursor->dayOfWeek, [5, 6]); // Fri/Sat (Iraq)
            $dateStr = $cursor->format('Y-m-d');
            if (! $isWeekend && ! in_array($dateStr, $holidayDates) && ! in_array($dateStr, $iraqiHolidays)) {
                $days++;
            }
            $cursor->addDay();
        }

        return $days;
    }
}
