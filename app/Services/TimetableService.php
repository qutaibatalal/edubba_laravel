<?php

namespace App\Services;

use App\Models\ClassSession;
use App\Models\TimeTable;
use App\Models\TimeTableLine;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TimetableService
{
    /**
     * Generate class sessions for a given date from active time tables.
     * The day name must match the timetable line's week day.
     */
    public static function generateSessionsForDate(string $date): int
    {
        $day = Carbon::parse($date);
        $dayName = $day->format('l'); // Monday..Sunday

        $tables = TimeTable::with(['lines.weekDay', 'lines.timing'])
            ->where('active', true)
            ->get();

        $created = 0;

        foreach ($tables as $table) {
            foreach ($table->lines as $line) {
                if ($line->weekDay && $line->weekDay->name !== $dayName) {
                    continue;
                }

                ClassSession::firstOrCreate(
                    [
                        'time_table_line_id' => $line->id,
                        'date' => $date,
                    ],
                    [
                        'batch_id' => $table->batch_id,
                        'course_id' => $line->course_id,
                        'subject_id' => $line->subject_id,
                        'faculty_id' => $line->faculty_id,
                        'classroom_id' => $line->classroom_id,
                        'start_time' => $line->timing?->start_time,
                        'end_time' => $line->timing?->end_time,
                        'state' => ClassSession::STATE_PLANNED,
                    ]
                );

                $created++;
            }
        }

        return $created;
    }

    /**
     * Detect timetable conflicts: a faculty, classroom, or batch booked twice
     * at the same day+timing.
     *
     * @return Collection<int, object{type: string, label: string, day: string, line_a: int, line_b: int}>
     */
    public static function conflicts(): Collection
    {
        $lines = TimeTableLine::with(['timeTable.batch', 'weekDay', 'timing', 'faculty', 'classroom'])
            ->whereHas('timeTable', fn ($q) => $q->where('active', true))
            ->get();

        $conflicts = collect();

        $byFaculty = $lines->groupBy(fn ($l) => $l->faculty_id.'|'.$l->week_day_id.'|'.$l->timing_id);
        foreach ($byFaculty as $key => $group) {
            if ($group->count() < 2) {
                continue;
            }
            [$facultyId, $dayId, $timingId] = explode('|', $key);
            $first = $group->first();
            $conflicts->push((object) [
                'type' => 'faculty',
                'label' => $first->faculty?->name ?? 'عضو هيئة',
                'day' => $first->weekDay?->name ?? $dayId,
                'timing' => $first->timing?->start_time,
                'lines' => $group->pluck('id')->implode(', '),
            ]);
        }

        $byClassroom = $lines->groupBy(fn ($l) => $l->classroom_id.'|'.$l->week_day_id.'|'.$l->timing_id);
        foreach ($byClassroom as $key => $group) {
            if ($group->count() < 2) {
                continue;
            }
            [$classroomId, $dayId, $timingId] = explode('|', $key);
            $first = $group->first();
            $conflicts->push((object) [
                'type' => 'classroom',
                'label' => $first->classroom?->name ?? 'قاعة',
                'day' => $first->weekDay?->name ?? $dayId,
                'timing' => $first->timing?->start_time,
                'lines' => $group->pluck('id')->implode(', '),
            ]);
        }

        $byBatch = $lines->groupBy(fn ($l) => $l->timeTable?->batch_id.'|'.$l->week_day_id.'|'.$l->timing_id);
        foreach ($byBatch as $key => $group) {
            if ($group->count() < 2) {
                continue;
            }
            [$batchId, $dayId, $timingId] = explode('|', $key);
            $first = $group->first();
            $conflicts->push((object) [
                'type' => 'batch',
                'label' => $first->timeTable?->batch?->name ?? 'صف',
                'day' => $first->weekDay?->name ?? $dayId,
                'timing' => $first->timing?->start_time,
                'lines' => $group->pluck('id')->implode(', '),
            ]);
        }

        return $conflicts;
    }
}
