<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamRoom;
use App\Models\ExamRoomAllocation;
use App\Models\ExamSchedule;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ExamDistributionService
{
    /**
     * Auto-distribute admitted students of the exam's batch into active exam rooms.
     * Returns ['allocated' => int, 'conflicts' => array].
     */
    public static function distribute(Exam $exam, ?int $scheduleId = null): array
    {
        $rooms = ExamRoom::active()->orderByDesc('capacity')->get();
        if ($rooms->isEmpty()) {
            throw new \DomainException('لا توجد قاعات امتحان نشطة. أضف قاعة أولاً.');
        }

        $schedule = $scheduleId ? ExamSchedule::find($scheduleId) : null;

        $students = Student::admitted()
            ->when($exam->batch_id, fn ($q) => $q->where('batch_id', $exam->batch_id))
            ->orderBy('roll_no')
            ->get();

        $conflicts = [];

        return DB::transaction(function () use ($exam, $schedule, $students, $rooms, &$conflicts) {
            // Clear previous allocations for this exam (and session if provided).
            $base = ExamRoomAllocation::where('exam_id', $exam->id);
            if ($schedule) {
                $base->where('exam_schedule_id', $schedule->id);
            }
            $base->delete();

            $allocated = 0;
            $roomIdx = 0;
            $room = $rooms->first();
            $capacityLeft = $room->capacity;

            foreach ($students as $student) {
                if ($schedule && ($blocked = self::conflictFor($student, $schedule))) {
                    $conflicts[] = ['student' => $student, 'reason' => $blocked];

                    continue;
                }

                if ($capacityLeft <= 0) {
                    $roomIdx++;
                    if (! isset($rooms[$roomIdx])) {
                        $conflicts[] = ['student' => $student, 'reason' => 'القاعات لا تتسع لجميع الطلاب'];

                        continue;
                    }
                    $room = $rooms[$roomIdx];
                    $capacityLeft = $room->capacity;
                }

                $seatNo = $room->capacity - $capacityLeft + 1;

                ExamRoomAllocation::create([
                    'exam_id' => $exam->id,
                    'exam_schedule_id' => $schedule?->id,
                    'exam_room_id' => $room->id,
                    'student_id' => $student->id,
                    'seat_no' => str_pad((string) $seatNo, 2, '0', STR_PAD_LEFT),
                ]);

                $capacityLeft--;
                $allocated++;
            }

            return ['allocated' => $allocated, 'conflicts' => $conflicts];
        });
    }

    /**
     * Detect if a student is already seated in another overlapping exam session.
     */
    protected static function conflictFor(Student $student, ExamSchedule $schedule): ?string
    {
        $existing = ExamRoomAllocation::query()
            ->with('examSchedule.exam')
            ->where('student_id', $student->id)
            ->whereNotNull('exam_schedule_id')
            ->get()
            ->first(function ($alloc) use ($schedule) {
                $other = $alloc->examSchedule;
                if (! $other || $other->id === $schedule->id) {
                    return false;
                }
                if (optional($other->date)->toDateString() !== optional($schedule->date)->toDateString()) {
                    return false;
                }
                if ($other->start_time && $schedule->start_time && $other->end_time && $schedule->end_time) {
                    return $other->start_time < $schedule->end_time && $schedule->start_time < $other->end_time;
                }

                return true;
            });

        return $existing
            ? 'مكرر بتاريخ '.optional($schedule->date)->format('Y-m-d').' — '.($existing->examSchedule->exam->name ?? 'امتحان آخر')
            : null;
    }

    /**
     * Mark attendance (held exam) for a schedule's room allocations.
     */
    public static function markHeld(Exam $exam, int $scheduleId, array $attended): int
    {
        $count = 0;
        foreach ($attended as $allocationId => $present) {
            $bool = in_array($present, ['1', 'true', 'present', 1, true], true);
            ExamRoomAllocation::where('id', $allocationId)
                ->where('exam_id', $exam->id)
                ->where('exam_schedule_id', $scheduleId)
                ->update(['attended' => $bool]);
            $count++;
        }

        return $count;
    }
}
