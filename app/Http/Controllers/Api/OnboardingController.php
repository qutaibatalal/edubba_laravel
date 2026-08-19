<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Faculty;
use App\Models\MobileAppConfig;
use App\Models\Program;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    /**
     * GET /admin/onboarding/status
     *
     * Returns which steps are completed so the wizard can resume.
     */
    public function status(): JsonResponse
    {
        $steps = [
            'school_info' => $this->isStepSchoolInfoDone(),
            'academic_year' => $this->isStepAcademicYearDone(),
            'departments_batches' => $this->isStepDepartmentsBatchesDone(),
            'subjects_faculty' => $this->isStepSubjectsFacultyDone(),
            'review' => true, // review is always ready
        ];

        $completed = array_filter($steps)->count();
        $schoolName = MobileAppConfig::configValue('school_name', '');

        return response()->json([
            'status' => 'success',
            'data' => [
                'steps' => $steps,
                'completed_count' => $completed,
                'total_steps' => 5,
                'current_step' => $this->getNextStep($steps),
                'school_name' => $schoolName,
                'is_setup_complete' => $completed >= 4,
            ],
        ]);
    }

    /**
     * POST /admin/onboarding/step/1
     *
     * Step 1 — School information: name, phone, address, primary color.
     */
    public function stepSchoolInfo(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_name_en' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'primary_color' => 'nullable|string|max:7',
            'logo_url' => 'nullable|string|max:500',
        ]);

        $configs = [
            'school_name' => $validated['school_name'],
            'school_name_en' => $validated['school_name_en'] ?? '',
            'school_phone' => $validated['phone'],
            'school_address' => $validated['address'] ?? '',
            'primary_color' => $validated['primary_color'] ?? '#4f46e5',
            'logo_url' => $validated['logo_url'] ?? '',
        ];

        foreach ($configs as $key => $value) {
            MobileAppConfig::updateOrCreate(
                ['config_key' => $key],
                ['value' => $value, 'active' => true]
            );
        }

        // Clear cached config
        \Illuminate\Support\Facades\Cache::forget('app_config_public');

        return response()->json([
            'status' => 'success',
            'message' => 'تم حفظ معلومات المدرسة',
            'data' => ['step' => 1, 'next_step' => 2],
        ]);
    }

    /**
     * POST /admin/onboarding/step/2
     *
     * Step 2 — Academic year + terms: create current year with two terms.
     */
    public function stepAcademicYear(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'year_name' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_stop' => 'required|date|after:date_start',
            'term1_name' => 'required|string|max:255',
            'term1_start' => 'required|date',
            'term1_end' => 'required|date|after_or_equal:term1_start',
            'term2_name' => 'required|string|max:255',
            'term2_start' => 'required|date|after:term1_end',
            'term2_end' => 'required|date|after_or_equal:term2_start',
        ]);

        $year = DB::transaction(function () use ($validated) {
            // Deactivate any existing current year
            AcademicYear::where('current', true)->update(['current' => false]);

            $year = AcademicYear::create([
                'name' => $validated['year_name'],
                'date_start' => $validated['date_start'],
                'date_stop' => $validated['date_stop'],
                'current' => true,
            ]);

            Term::create([
                'academic_year_id' => $year->id,
                'name' => $validated['term1_name'],
                'date_start' => $validated['term1_start'],
                'date_stop' => $validated['term1_end'],
                'sequence' => 1,
            ]);

            Term::create([
                'academic_year_id' => $year->id,
                'name' => $validated['term2_name'],
                'date_start' => $validated['term2_start'],
                'date_stop' => $validated['term2_end'],
                'sequence' => 2,
            ]);

            return $year;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'تم إنشاء السنة الدراسية والفصلين',
            'data' => [
                'step' => 2,
                'next_step' => 3,
                'academic_year_id' => $year->id,
            ],
        ]);
    }

    /**
     * POST /admin/onboarding/step/3
     *
     * Step 3 — Departments (programs) + batches (classes).
     */
    public function stepDepartmentsBatches(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'departments' => 'required|array|min:1',
            'departments.*.name' => 'required|string|max:255',
            'batches' => 'required|array|min:1',
            'batches.*.name' => 'required|string|max:255',
            'batches.*.department_name' => 'required|string|max:255',
            'batches.*.capacity' => 'nullable|integer|min:1',
        ]);

        $year = AcademicYear::where('current', true)->first();

        DB::transaction(function () use ($validated, $year) {
            // Create departments (programs)
            $programs = [];
            foreach ($validated['departments'] as $dept) {
                $programs[$dept['name']] = Program::firstOrCreate(
                    ['name' => $dept['name']],
                    ['academic_year_id' => $year?->id]
                );
            }

            // Create batches
            foreach ($validated['batches'] as $batch) {
                $program = $programs[$batch['department_name']] ?? null;
                Batch::firstOrCreate(
                    ['name' => $batch['name']],
                    [
                        'program_id' => $program?->id,
                        'capacity' => $batch['capacity'] ?? 30,
                    ]
                );
            }
        });

        $batchCount = Batch::count();
        $deptCount = Program::count();

        return response()->json([
            'status' => 'success',
            'message' => "تم إنشاء {$deptCount} أقسام و {$batchCount} دفعات",
            'data' => [
                'step' => 3,
                'next_step' => 4,
                'departments_count' => $deptCount,
                'batches_count' => $batchCount,
            ],
        ]);
    }

    /**
     * POST /admin/onboarding/step/4
     *
     * Step 4 — Subjects + faculty assignment.
     */
    public function stepSubjectsFaculty(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subjects' => 'required|array|min:1',
            'subjects.*.name' => 'required|string|max:255',
            'subjects.*.name_en' => 'nullable|string|max:255',
            'subjects.*.code' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['subjects'] as $sub) {
                Subject::firstOrCreate(
                    ['name' => $sub['name']],
                    [
                        'name_en' => $sub['name_en'] ?? null,
                        'code' => $sub['code'] ?? null,
                    ]
                );
            }
        });

        $subjectCount = Subject::count();
        $facultyCount = Faculty::count();

        return response()->json([
            'status' => 'success',
            'message' => "تم إنشاء {$subjectCount} مادة",
            'data' => [
                'step' => 4,
                'next_step' => 5,
                'subjects_count' => $subjectCount,
                'existing_faculty_count' => $facultyCount,
            ],
        ]);
    }

    /**
     * GET /admin/onboarding/step/5 (review)
     *
     * Step 5 — Review summary before launch.
     */
    public function stepReview(): JsonResponse
    {
        $year = AcademicYear::where('current', true)->first();
        $schoolName = MobileAppConfig::configValue('school_name', '');

        return response()->json([
            'status' => 'success',
            'data' => [
                'step' => 5,
                'is_setup_complete' => true,
                'summary' => [
                    'school_name' => $schoolName,
                    'academic_year' => $year?->name,
                    'terms' => $year?->terms()->count() ?? 0,
                    'departments' => Program::count(),
                    'batches' => Batch::count(),
                    'subjects' => Subject::count(),
                    'faculty' => Faculty::count(),
                ],
            ],
        ]);
    }

    // ─── Helpers ───

    private function isStepSchoolInfoDone(): bool
    {
        return MobileAppConfig::configValue('school_name', '') !== '';
    }

    private function isStepAcademicYearDone(): bool
    {
        return AcademicYear::where('current', true)->exists();
    }

    private function isStepDepartmentsBatchesDone(): bool
    {
        return Program::count() > 0 && Batch::count() > 0;
    }

    private function isStepSubjectsFacultyDone(): bool
    {
        return Subject::count() > 0;
    }

    private function getNextStep(array $steps): int
    {
        $stepMap = [
            1 => 'school_info',
            2 => 'academic_year',
            3 => 'departments_batches',
            4 => 'subjects_faculty',
            5 => 'review',
        ];

        foreach ($stepMap as $num => $key) {
            if (! $steps[$key]) {
                return $num;
            }
        }

        return 5;
    }
}
