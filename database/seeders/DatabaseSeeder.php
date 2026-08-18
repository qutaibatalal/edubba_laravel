<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ApiUser;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\FeedbackForm;
use App\Models\MobileAppConfig;
use App\Models\ParentModel;
use App\Models\Program;
use App\Models\Sequence;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\Timing;
use App\Models\User;
use App\Models\WeekDay;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with the baseline reference data
     * required by the Edubba modules.
     */
    public function run(): void
    {
        $this->call([
            ReferenceDataSeeder::class,
            AcademicYearSeeder::class,
            DepartmentProgramSeeder::class,
            SubjectCourseSeeder::class,
            WeekDaySeeder::class,
            TimingSeeder::class,
            MinistryQuestionSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            ApiUserSeeder::class,
            DemoDataSeeder::class,
            IraqiCalendarSeeder::class,
            BigDataSeeder::class,
        ]);
    }
}

/*
 * Sequences used by ir.sequence replacements.
 */
class ReferenceDataSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'student_code' => 'STU',
            'roll_no' => 'RN',
            'admission' => 'ADM',
            'invoice' => 'INV',
            'payment' => 'PAY',
            'receipt' => 'RCP',
            'commission' => 'COM',
            'subscription' => 'SUB',
            'tutor_payout' => 'TPO',
        ] as $name => $prefix) {
            Sequence::firstOrCreate(['name' => $name], ['name' => $name, 'prefix' => $prefix, 'next' => 1, 'padding' => 5]);
        }

        // Default mobile app configuration
        $configs = [
            'school_name' => ['label' => 'School Name', 'value' => ['en' => 'Edubba School', 'ar' => 'مدرسة إدبة']],
            'primary_color' => ['label' => 'Primary Color', 'value' => '#1e40af'],
            'features' => ['label' => 'Feature Toggles', 'value' => ['tutoring' => true, 'training' => true, 'library' => true]],
        ];
        foreach ($configs as $key => $cfg) {
            MobileAppConfig::firstOrCreate(['config_key' => $key], array_merge($cfg, ['active' => true]));
        }
    }
}

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::firstOrCreate(
            ['name' => '2025-2026'],
            ['date_start' => '2025-09-01', 'date_stop' => '2026-07-31', 'current' => true, 'active' => true]
        );

        Term::firstOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Term 1'],
            ['date_start' => '2025-09-01', 'date_stop' => '2026-01-15', 'active' => true]
        );
        Term::firstOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'Term 2'],
            ['date_start' => '2026-01-16', 'date_stop' => '2026-07-31', 'active' => true]
        );
    }
}

class DepartmentProgramSeeder extends Seeder
{
    public function run(): void
    {
        $dept = Department::firstOrCreate(['name' => 'Languages'], ['code' => 'LANG', 'active' => true]);

        $program = Program::firstOrCreate(
            ['name' => 'Primary'],
            ['code' => 'PRIM', 'department_id' => $dept->id, 'duration_years' => 6, 'active' => true]
        );

        $primaryYear = AcademicYear::where('name', '2025-2026')->first();

        Batch::firstOrCreate(
            ['name' => 'Grade 6 A', 'program_id' => $program->id, 'academic_year_id' => $primaryYear?->id],
            ['program_id' => $program->id, 'academic_year_id' => $primaryYear?->id, 'capacity' => 30, 'active' => true]
        );
    }
}

class SubjectCourseSeeder extends Seeder
{
    public function run(): void
    {
        $dept = Department::where('name', 'Languages')->first();
        $subject = Subject::firstOrCreate(
            ['name' => 'English'],
            ['code' => 'ENG', 'department_id' => $dept?->id, 'is_language' => true, 'active' => true]
        );

        $batch = Batch::where('name', 'Grade 6 A')->first();
        $year = AcademicYear::where('name', '2025-2026')->first();

        Course::firstOrCreate(
            ['name' => 'English Grade 6 A', 'subject_id' => $subject->id, 'batch_id' => $batch?->id, 'academic_year_id' => $year?->id],
            ['code' => 'ENG6A', 'subject_id' => $subject->id, 'batch_id' => $batch?->id, 'academic_year_id' => $year?->id, 'active' => true]
        );
    }
}

class WeekDaySeeder extends Seeder
{
    public function run(): void
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($days as $i => $day) {
            WeekDay::firstOrCreate(['name' => $day], ['name' => $day, 'sequence' => $i, 'active' => true]);
        }
    }
}

class TimingSeeder extends Seeder
{
    public function run(): void
    {
        $timings = [
            ['name' => 'Period 1', 'start_time' => '08:00:00', 'end_time' => '08:45:00'],
            ['name' => 'Period 2', 'start_time' => '08:55:00', 'end_time' => '09:40:00'],
            ['name' => 'Period 3', 'start_time' => '09:50:00', 'end_time' => '10:35:00'],
        ];
        foreach ($timings as $i => $t) {
            Timing::firstOrCreate(['name' => $t['name']], array_merge($t, ['sequence' => $i]));
        }
    }
}

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'students.view', 'students.manage',
            'admissions.view', 'admissions.manage',
            'faculty.view', 'faculty.manage',
            'exams.manage', 'attendance.manage',
            'fees.manage', 'tutoring.manage',
            'ministry.view', 'admin.access',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = ['admin', 'faculty', 'student', 'parent'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $adminRole = Role::where('name', 'admin')->first();
        $adminRole?->syncPermissions(Permission::all());
    }
}

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@edubba.test'],
            ['name' => 'System Admin', 'password' => 'password']
        );

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && ! $user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }

        $faculty = Faculty::firstOrCreate(
            ['faculty_code' => 'FAC0001'],
            ['name' => 'Demo', 'middle_name' => 'A', 'last_name' => 'Teacher', 'state' => Faculty::STATE_JOINED, 'active' => true]
        );

        $facultyUser = User::firstOrCreate(
            ['email' => 'faculty@edubba.test'],
            ['name' => 'Demo Teacher', 'password' => 'password']
        );
        $faculty->user_id = $facultyUser->id;
        $faculty->save();

        $facultyRole = Role::where('name', 'faculty')->first();
        if ($facultyRole && ! $facultyUser->hasRole('faculty')) {
            $facultyUser->assignRole($facultyRole);
        }
    }
}

class ApiUserSeeder extends Seeder
{
    public function run(): void
    {
        // Demo student + parent API users are created in DemoDataSeeder to
        // keep FK relationships consistent.
    }
}

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::where('name', '2025-2026')->first();
        $batch = Batch::where('name', 'Grade 6 A')->first();

        $parent = ParentModel::firstOrCreate(
            ['national_id' => 'PAR0001'],
            ['name' => 'Parent One', 'phone' => '07700000001', 'mobile' => '07700000001', 'relation' => 'father', 'active' => true]
        );

        $student = Student::firstOrCreate(
            ['student_code' => 'STU00001'],
            [
                'name' => 'Student', 'middle_name' => 'A', 'last_name' => 'One',
                'gender' => 'male', 'birth_date' => '2013-05-01',
                'batch_id' => $batch?->id, 'academic_year_id' => $year?->id,
                'parent_id' => $parent->id, 'state' => Student::STATE_ADMITTED,
                'admission_date' => '2025-09-01', 'roll_no' => 'RN00001', 'active' => true,
            ]
        );

        $student->parents()->syncWithoutDetaching([
            $parent->id => ['relation' => 'father', 'is_main' => true, 'guardian' => true],
        ]);

        // API users for login testing
        ApiUser::firstOrCreate(
            ['username' => 'student1'],
            ['username' => 'student1', 'password' => 'password', 'role' => ApiUser::ROLE_STUDENT, 'student_id' => $student->id, 'active' => true]
        );
        ApiUser::firstOrCreate(
            ['username' => 'parent1'],
            ['username' => 'parent1', 'password' => 'password', 'role' => ApiUser::ROLE_PARENT, 'parent_id' => $parent->id, 'active' => true]
        );
        $faculty = Faculty::where('faculty_code', 'FAC0001')->first();

        ApiUser::firstOrCreate(
            ['username' => 'faculty1'],
            ['username' => 'faculty1', 'password' => 'password', 'role' => ApiUser::ROLE_FACULTY, 'faculty_id' => $faculty?->id, 'active' => true]
        );
        ApiUser::firstOrCreate(
            ['username' => 'admin1'],
            ['username' => 'admin1', 'password' => 'password', 'role' => ApiUser::ROLE_ADMIN, 'active' => true]
        );

        // Link the demo faculty to the demo course so faculty views have data.
        if ($faculty) {
            Course::where('name', 'English Grade 6 A')->update(['faculty_id' => $faculty->id]);
        }

        // Feedback form for the mobile feedback module.
        FeedbackForm::firstOrCreate(
            ['name' => 'End of Term Survey'],
            [
                'name' => 'End of Term Survey',
                'type' => 'student',
                'questions' => ['How satisfied are you with teaching?', 'Rate the facilities.', 'Any suggestions?'],
                'active' => true,
            ]
        );
    }
}
