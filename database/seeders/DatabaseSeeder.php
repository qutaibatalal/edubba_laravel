<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\MobileAppConfig;
use App\Models\Sequence;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ReferenceDataSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            MinistryQuestionSeeder::class,
            IraqiCalendarSeeder::class,
            IraqiDemoSeeder::class,
        ]);
    }
}

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

        MobileAppConfig::firstOrCreate(['config_key' => 'school_name'], [
            'label' => 'School Name',
            'value' => ['ar' => 'مدرسة أديبا', 'en' => 'Edubba School'],
            'active' => true,
        ]);
        MobileAppConfig::firstOrCreate(['config_key' => 'primary_color'], [
            'label' => 'Primary Color',
            'value' => '#1e40af',
            'active' => true,
        ]);
        MobileAppConfig::firstOrCreate(['config_key' => 'features'], [
            'label' => 'Feature Toggles',
            'value' => ['tutoring' => true, 'training' => true, 'library' => true],
            'active' => true,
        ]);
    }
}

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'students.view', 'students.manage',
            'admissions.view', 'admissions.manage',
            'faculty.view', 'faculty.manage',
            'exams.manage', 'attendance.manage',
            'fees.manage', 'tutoring.manage',
            'ministry.view', 'admin.access',
        ] as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        foreach (['admin', 'faculty', 'student', 'parent'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        Role::where('name', 'admin')->first()?->syncPermissions(Permission::all());
    }
}

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@edubba.test'],
            ['name' => 'مدير النظام', 'password' => 'password']
        );
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }

        $faculty = Faculty::firstOrCreate(
            ['faculty_code' => 'FAC0001'],
            ['name' => 'أحمد', 'middle_name' => 'علي', 'last_name' => 'المحمداوي', 'state' => Faculty::STATE_JOINED, 'active' => true]
        );

        $facultyUser = User::firstOrCreate(
            ['email' => 'faculty@edubba.test'],
            ['name' => 'أحمد المحمداوي', 'password' => 'password']
        );
        $faculty->user_id = $facultyUser->id;
        $faculty->save();

        $facultyRole = Role::where('name', 'faculty')->first();
        if ($facultyRole && !$facultyUser->hasRole('faculty')) {
            $facultyUser->assignRole($facultyRole);
        }
    }
}
