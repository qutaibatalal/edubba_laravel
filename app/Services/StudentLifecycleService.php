<?php

namespace App\Services;

use App\Models\Alumni;
use App\Models\Student;

class StudentLifecycleService
{
    /**
     * draft -> admitted (used when a student record exists without admission flow).
     */
    public static function admit(Student $student): Student
    {
        if ($student->state !== Student::STATE_DRAFT) {
            throw new \DomainException("Cannot admit student in state '{$student->state}'.");
        }

        $student->state = Student::STATE_ADMITTED;
        $student->admission_date = $student->admission_date ?? now()->toDateString();

        cache()->forget('edubba:dashboard:stats');
        cache()->forget('edubba:dashboard:perbatch');
        $student->roll_no = $student->roll_no ?? SequenceService::next('roll_no', 'RN');
        $student->save();

        return $student;
    }

    /**
     * admitted -> graduated
     */
    public static function graduate(Student $student): Student
    {
        if ($student->state !== Student::STATE_ADMITTED) {
            throw new \DomainException("Cannot graduate student in state '{$student->state}'.");
        }

        $student->state = Student::STATE_GRADUATED;
        $student->save();

        return $student;
    }

    /**
     * graduated -> alumni (creates an alumni record).
     */
    public static function markAlumni(Student $student): Student
    {
        if (! in_array($student->state, [Student::STATE_GRADUATED, Student::STATE_ADMITTED])) {
            throw new \DomainException("Cannot mark student in state '{$student->state}' as alumni.");
        }

        $student->state = Student::STATE_ALUMNI;
        $student->save();

        Alumni::firstOrCreate(
            ['student_id' => $student->id],
            [
                'name' => $student->full_name,
                'graduation_year' => $student->academicYear?->name,
                'contact' => $student->mobile,
            ]
        );

        return $student;
    }
}
