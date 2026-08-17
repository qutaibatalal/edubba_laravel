<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCourse;
use Illuminate\Support\Facades\DB;

class AdmissionService
{
    /**
     * draft -> submit
     */
    public static function submit(Admission $admission): Admission
    {
        if ($admission->state !== Admission::STATE_DRAFT) {
            throw new \DomainException("Cannot submit admission in state '{$admission->state}'.");
        }

        $admission->state = Admission::STATE_SUBMIT;
        $admission->save();

        return $admission;
    }

    /**
     * submit -> approve
     */
    public static function approve(Admission $admission): Admission
    {
        if (! in_array($admission->state, [Admission::STATE_SUBMIT, Admission::STATE_DRAFT])) {
            throw new \DomainException("Cannot approve admission in state '{$admission->state}'.");
        }

        $admission->state = Admission::STATE_APPROVE;
        $admission->save();

        return $admission;
    }

    /**
     * submit/approve -> reject
     */
    public static function reject(Admission $admission): Admission
    {
        if (! in_array($admission->state, [Admission::STATE_SUBMIT, Admission::STATE_APPROVE])) {
            throw new \DomainException("Cannot reject admission in state '{$admission->state}'.");
        }

        $admission->state = Admission::STATE_REJECT;
        $admission->save();

        return $admission;
    }

    /**
     * approve -> admitted
     * Creates the students row, generates student_code via sequence,
     * creates student_course rows from the program and assigns a roll_no.
     */
    public static function admit(Admission $admission): Student
    {
        if ($admission->state !== Admission::STATE_APPROVE) {
            throw new \DomainException("Only approved admissions can be admitted. Current state: '{$admission->state}'.");
        }

        return DB::transaction(function () use ($admission) {
            $student = Student::create([
                'student_code' => SequenceService::next('student_code', 'STU'),
                'name' => $admission->name,
                'middle_name' => $admission->middle_name,
                'last_name' => $admission->last_name,
                'birth_date' => $admission->birth_date,
                'gender' => $admission->gender,
                'national_id' => $admission->national_id,
                'phone' => $admission->phone,
                'email' => $admission->email,
                'address' => $admission->address,
                'batch_id' => $admission->batch_id,
                'program_id' => $admission->program_id,
                'academic_year_id' => $admission->academic_year_id,
                'state' => Student::STATE_ADMITTED,
                'admission_date' => now()->toDateString(),
                'roll_no' => SequenceService::next('roll_no', 'RN'),
            ]);

            $admission->student_id = $student->id;
            $admission->state = Admission::STATE_ADMITTED;
            $admission->save();

            // Create student_course rows from courses belonging to the program/batch/year
            $courses = Course::query()
                ->when($admission->program_id, fn ($q) => $q->where('program_id', $admission->program_id))
                ->when($admission->batch_id, fn ($q) => $q->where('batch_id', $admission->batch_id))
                ->when($admission->academic_year_id, fn ($q) => $q->where('academic_year_id', $admission->academic_year_id))
                ->get();

            foreach ($courses as $course) {
                StudentCourse::firstOrCreate(
                    ['student_id' => $student->id, 'course_id' => $course->id],
                    [
                        'batch_id' => $admission->batch_id,
                        'academic_year_id' => $admission->academic_year_id,
                        'state' => StudentCourse::STATE_RUNNING,
                        'total_fees' => $admission->fees_amount ?? 0,
                    ]
                );
            }

            return $student;
        });
    }
}
