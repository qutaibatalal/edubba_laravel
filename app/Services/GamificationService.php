<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentBadge;
use App\Models\StudentPoint;

class GamificationService
{
    const POINTS = [
        'present' => 5,
        'pass_exam' => 20,
        'high_grade' => 50,
        'submit_assignment' => 10,
    ];

    /**
     * Award attendance points and check for badges.
     */
    public function awardAttendance(Student $student): void
    {
        $this->award($student, self::POINTS['present'], 'attendance');
        $this->checkAttendanceBadge($student);
    }

    /**
     * Award exam grade points based on percentage.
     */
    public function awardGrade(Student $student, float $percentage): void
    {
        $points = $percentage >= 90
            ? self::POINTS['high_grade']
            : self::POINTS['pass_exam'];

        $this->award($student, $points, 'grade');

        if ($percentage >= 90) {
            $this->checkTopStudentBadge($student);
        }
    }

    /**
     * Award assignment submission points.
     */
    public function awardAssignment(Student $student): void
    {
        $this->award($student, self::POINTS['submit_assignment'], 'assignment');
    }

    /**
     * Get student's total points, rank, and badges.
     */
    public function getStudentStats(Student $student): array
    {
        $totalPoints = StudentPoint::where('student_id', $student->id)->sum('points');

        $rank = StudentPoint::select('student_id')
            ->selectRaw('SUM(points) as total')
            ->groupBy('student_id')
            ->orderByDesc('total')
            ->pluck('student_id')
            ->values()
            ->search($student->id);

        $totalStudents = Student::where('state', 'admitted')
            ->where('batch_id', $student->batch_id)
            ->count();

        $badges = StudentBadge::where('student_id', $student->id)
            ->orderByDesc('earned_date')
            ->get()
            ->map(fn ($b) => [
                'type' => $b->badge_type,
                'name' => $b->badge_name,
                'earned_date' => $b->earned_date->toDateString(),
            ]);

        $history = StudentPoint::where('student_id', $student->id)
            ->orderByDesc('earned_at')
            ->limit(20)
            ->get()
            ->map(fn ($p) => [
                'points' => $p->points,
                'reason' => $p->reason,
                'earned_at' => $p->earned_at->toDateTimeString(),
            ]);

        return [
            'total_points' => (int) $totalPoints,
            'rank' => $rank !== false ? $rank + 1 : 0,
            'total_students' => $totalStudents,
            'badges' => $badges,
            'history' => $history,
        ];
    }

    protected function award(Student $student, int $points, string $reason): void
    {
        StudentPoint::create([
            'student_id' => $student->id,
            'points' => $points,
            'reason' => $reason,
            'earned_at' => now(),
        ]);
    }

    protected function checkAttendanceBadge(Student $student): void
    {
        $streak = $this->getAttendanceStreak($student);

        if ($streak >= 30 && ! $this->hasBadge($student, 'perfect_attendance')) {
            StudentBadge::create([
                'student_id' => $student->id,
                'badge_type' => 'perfect_attendance',
                'badge_name' => 'الحضور المثالي',
                'earned_date' => today(),
            ]);
        }
    }

    protected function checkTopStudentBadge(Student $student): void
    {
        $highGrades = StudentPoint::where('student_id', $student->id)
            ->where('reason', 'grade')
            ->where('points', self::POINTS['high_grade'])
            ->count();

        if ($highGrades >= 3 && ! $this->hasBadge($student, 'top_student')) {
            StudentBadge::create([
                'student_id' => $student->id,
                'badge_type' => 'top_student',
                'badge_name' => 'الطالب المتميز',
                'earned_date' => today(),
            ]);
        }
    }

    protected function hasBadge(Student $student, string $type): bool
    {
        return StudentBadge::where('student_id', $student->id)
            ->where('badge_type', $type)
            ->where('earned_date', today())
            ->exists();
    }

    protected function getAttendanceStreak(Student $student): int
    {
        $attendances = $student->attendances()
            ->where('status', 'present')
            ->orderByDesc('created_at')
            ->limit(60)
            ->pluck('created_at');

        if ($attendances->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $expectedDate = now()->startOfDay();

        foreach ($attendances as $date) {
            if ($date->startOfDay()->eq($expectedDate)) {
                $streak++;
                $expectedDate = $expectedDate->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }
}
