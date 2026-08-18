<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\Department;
use App\Models\Program;
use App\Models\Subject;
use App\Models\Batch;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\ParentModel;
use App\Models\ExamType;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\Marksheet;
use App\Models\MarksheetLine;
use App\Models\ExamResult;
use App\Models\TimeTable;
use App\Models\TimeTableLine;
use App\Models\ClassSession;
use App\Models\AttendanceSheet;
use App\Models\AttendanceLine;
use App\Models\FeeStructure;
use App\Models\FeeLine;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Sequence;
use App\Models\WeekDay;
use App\Models\Timing;
use App\Models\Center;
use App\Models\Branch;
use App\Models\Tutor;
use App\Models\StudyGroup;
use App\Models\StudyGroupStudent;
use App\Models\StudyGroupSession;
use App\Models\StudyGroupAttendance;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\TutoringPackage;
use App\Models\TutoringProduct;
use App\Models\LibraryBook;
use App\Models\LibraryIssue;
use App\Models\TransportVehicle;
use App\Models\TransportRoute;
use App\Models\TransportStop;
use App\Models\TransportAssignment;
use App\Models\Hostel;
use App\Models\HostelRoom;
use App\Models\HostelAllocation;
use App\Models\NotificationLog;
use App\Models\ApiUser;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Admission;
use App\Models\AdmissionRegister;
use App\Models\TrainingCourse;
use App\Models\TrainingEnrollment;
use App\Models\TrainingSession;
use App\Models\TrainingAttendance;
use App\Models\FeedbackForm;
use App\Models\Feedback;
use App\Models\Employee;
use App\Models\FacultyHr;
use App\Models\StudentCourse;
use App\Models\Alumni;

class BigDataSeeder extends Seeder
{
    private const CHUNK_SIZE = 50;

    private function safeInsert(string $table, array $data): void
    {
        if (empty($data)) return;
        foreach (array_chunk($data, self::CHUNK_SIZE) as $chunk) {
            DB::table($table)->insertOrIgnore($chunk);
        }
    }

    private function safeInsertRaw(string $table, array $data): void
    {
        if (empty($data)) return;
        foreach (array_chunk($data, self::CHUNK_SIZE) as $chunk) {
            DB::table($table)->insert($chunk);
        }
    }

    private array $iraqiFirstNamesMale = [
        'محمد', 'أحمد', 'علي', 'عمر', 'حسن', 'يوسف', 'خالد', 'عبدالله', 'مصطفى',
        'سلام', 'كرار', 'زيد', 'مرتضى', 'حيدر', 'ثامر', 'سعد', 'ناصر', 'جمال',
        'فيصل', 'كريم', 'رائد', 'هشام', 'أيمن', 'بلال', 'أمير', 'شريف', 'وليد', 'تامر',
        'أنور', 'ربيع', 'جواد', 'كامل', 'إبراهيم', 'لؤي', 'رافع', 'سامح',
    ];

    private array $iraqiFirstNamesFemale = [
        'فاطمة', 'زينب', 'مريم', 'نور', 'ريم', 'دانا', 'جواهر', 'هند', 'سارة', 'لمى',
        'تسنيم', 'رنا', 'أمل', 'هبة', 'ندى', 'منار', 'رحاب', 'هلا',
        'نسرين', 'دعاء', 'خلود', 'عبير', 'سحر', 'أسماء', 'رباب',
    ];

    private array $lastNames = [
        'المحمداوي', 'الجبوري', 'الزيدي', 'الحسني', 'العبيدي', 'الكردي', 'الشمري',
        'النعيمي', 'المطلبي', 'الهاشمي', 'الموصلي', 'البغدادي', 'البصرة',
        'الموسوي', 'العجمي', 'الهلالي', 'الجبارة', 'ال成都市', 'العمري', 'الخالدي',
    ];

    private array $cities = [
        'بغداد', 'البصرة', 'نينوى', 'أربيل', 'النجف', 'كربلاء', 'الموصل', 'السليمانية',
        'دهوك', 'الديوانية', 'الكوت', 'العمارة', 'الحويجة', 'الرمادي', 'زاخو', 'حلبجة',
    ];

    private array $governorates = [
        'بغداد', 'البصرة', 'نينوى', 'أربيل', 'النجف', 'كربلاء', 'السليمانية',
        'دهوك', 'الديوانية', 'ميسان', 'المثنى', 'صلاح الدين', 'الأنبار', 'كركوك',
    ];

    private array $neighborhoods = [
        'المنصور', 'الكرادة', 'الجادرية', 'العدل', 'الحرية',
        'الشعب', 'الجهاد', 'البياع', 'العاميل', 'الدورة',
        'الكاظمية', 'الأعظمية', 'الكرخ', 'الرصافة', 'زيونة',
    ];

    public function run(): void
    {
        $this->command->info('جاري إدخال البيانات الكبيرة...');
        $start = microtime(true);

        DB::beginTransaction();
        try {
            $this->seedAcademicStructure();
            $this->seedDepartmentsAndPrograms();
            $this->seedSubjects();
            $this->seedClassrooms();
            $this->seedExamTypes();
            $this->seedFaculty();
            $this->seedBatches();
            $this->seedCourses();
            $this->seedParents();
            $this->seedStudents();
            $this->seedStudentCourses();
            $this->seedTimetables();
            $this->seedClassSessions();
            $this->seedAttendance();
            $this->seedExams();
            $this->seedMarksheets();
            $this->seedExamResults();
            $this->seedFeeStructures();
            $this->seedInvoices();
            $this->seedPayments();
            $this->seedAdmissions();
            $this->seedTutoring();
            $this->seedTraining();
            $this->seedLibrary();
            $this->seedTransport();
            $this->seedHostels();
            $this->seedNotifications();
            $this->seedWallets();
            $this->seedApiUsers();
            $this->seedEmployees();
            $this->seedFeedback();
            $this->seedAlumni();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $elapsed = round(microtime(true) - $start, 2);
        $this->command->info("تم إدخال البيانات بنجاح في {$elapsed} ثانية");
        $this->printStats();
    }

    private function printStats(): void
    {
        $tables = [
            'academic_years', 'terms', 'departments', 'programs', 'subjects', 'classrooms',
            'faculties', 'batches', 'courses', 'parents', 'students',
            'class_sessions', 'attendance_sheets', 'attendance_lines',
            'exam_types', 'exams', 'exam_schedules', 'marksheets', 'marksheet_lines', 'exam_results',
            'fee_structures', 'fee_lines', 'invoices', 'invoice_lines', 'payments', 'receipts',
            'centers', 'study_groups', 'subscriptions', 'subscription_payments',
            'library_books', 'library_issues',
            'transport_vehicles', 'transport_routes',
            'hostels', 'hostel_rooms', 'hostel_allocations',
            'notification_logs', 'api_users', 'wallets', 'wallet_transactions',
            'admissions', 'training_courses', 'training_enrollments',
            'employees', 'feedbacks', 'alumni',
        ];

        $this->command->newLine();
        $this->command->info('إحصائيات قاعدة البيانات:');
        $this->command->newLine();
        $maxLen = 0;
        foreach ($tables as $t) {
            $maxLen = max($maxLen, strlen($t));
        }
        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                $padded = str_pad($table, $maxLen + 2);
                $this->command->info("  {$padded} " . number_format($count));
            } catch (\Exception $e) {
            }
        }
    }

    private function seedAcademicStructure(): void
    {
        $this->command->info('  -> إدخال السنوات الدراسية والفصول...');

        $years = [
            ['name' => '2024-2025', 'date_start' => '2024-09-01', 'date_stop' => '2025-07-31', 'current' => false, 'active' => true],
            ['name' => '2025-2026', 'date_start' => '2025-09-01', 'date_stop' => '2026-07-31', 'current' => true, 'active' => true],
        ];

        foreach ($years as $y) {
            AcademicYear::updateOrCreate(['name' => $y['name']], $y);
        }

        $year2025 = AcademicYear::where('name', '2025-2026')->first();
        $year2024 = AcademicYear::where('name', '2024-2025')->first();

        $terms = [
            ['academic_year_id' => $year2025->id, 'name' => 'الفصل الأول', 'date_start' => '2025-09-01', 'date_stop' => '2026-01-15', 'active' => true],
            ['academic_year_id' => $year2025->id, 'name' => 'الفصل الثاني', 'date_start' => '2026-01-16', 'date_stop' => '2026-07-31', 'active' => true],
            ['academic_year_id' => $year2024->id, 'name' => 'الفصل الأول', 'date_start' => '2024-09-01', 'date_stop' => '2025-01-15', 'active' => true],
            ['academic_year_id' => $year2024->id, 'name' => 'الفصل الثاني', 'date_start' => '2025-01-16', 'date_stop' => '2025-07-31', 'active' => true],
        ];

        foreach ($terms as $t) {
            Term::updateOrCreate(
                ['academic_year_id' => $t['academic_year_id'], 'name' => $t['name']],
                $t
            );
        }

        WeekDay::query()->delete();
        $days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        foreach ($days as $i => $d) {
            WeekDay::create(['name' => $d, 'sequence' => $i, 'active' => true]);
        }

        Timing::query()->delete();
        $timings = [
            ['name' => 'الفترة الأولى', 'start_time' => '08:00:00', 'end_time' => '08:45:00', 'sequence' => 0],
            ['name' => 'الفترة الثانية', 'start_time' => '08:55:00', 'end_time' => '09:40:00', 'sequence' => 1],
            ['name' => 'الفترة الثالثة', 'start_time' => '09:50:00', 'end_time' => '10:35:00', 'sequence' => 2],
            ['name' => 'الفترة الرابعة', 'start_time' => '10:45:00', 'end_time' => '11:30:00', 'sequence' => 3],
            ['name' => 'الفترة الخامسة', 'start_time' => '11:40:00', 'end_time' => '12:25:00', 'sequence' => 4],
            ['name' => 'الفترة السادسة', 'start_time' => '12:35:00', 'end_time' => '13:20:00', 'sequence' => 5],
        ];
        foreach ($timings as $t) {
            Timing::create($t);
        }
    }

    private function seedDepartmentsAndPrograms(): void
    {
        $this->command->info('  -> إدخال الأقسام والبرامج...');

        $depts = [
            ['name' => 'اللغات', 'code' => 'LANG'],
            ['name' => 'العلوم', 'code' => 'SCI'],
            ['name' => 'الرياضيات', 'code' => 'MATH'],
            ['name' => 'الاجتماعيات', 'code' => 'SOC'],
            ['name' => 'الفنون', 'code' => 'ART'],
            ['name' => 'التربية الرياضية', 'code' => 'PE'],
            ['name' => 'التكنولوجيا', 'code' => 'TECH'],
            ['name' => 'الدراسات الإسلامية', 'code' => 'REL'],
        ];

        foreach ($depts as $d) {
            Department::updateOrCreate(['name' => $d['name']], array_merge($d, ['active' => true]));
        }

        $programs = [
            ['name' => 'المرحلة الابتدائية', 'code' => 'PRIM', 'dept' => 'اللغات', 'years' => 6],
            ['name' => 'المرحلة المتوسطة', 'code' => 'INTM', 'dept' => 'العلوم', 'years' => 3],
            ['name' => 'المرحلة الإعدادية', 'code' => 'PREP', 'dept' => 'العلوم', 'years' => 3],
            ['name' => 'الشعبة العلمية', 'code' => 'SCNT', 'dept' => 'العلوم', 'years' => 3],
            ['name' => 'الشعبة الأدبية', 'code' => 'LITR', 'dept' => 'اللغات', 'years' => 3],
            ['name' => 'برنامج تكنولوجيا المعلومات', 'code' => 'ITPG', 'dept' => 'التكنولوجيا', 'years' => 3],
        ];

        foreach ($programs as $p) {
            $dept = Department::where('name', $p['dept'])->first();
            Program::updateOrCreate(['name' => $p['name']], [
                'code' => $p['code'],
                'department_id' => $dept?->id,
                'duration_years' => $p['years'],
                'description' => "برنامج {$p['name']}",
                'active' => true,
            ]);
        }
    }

    private function seedSubjects(): void
    {
        $this->command->info('  -> إدخال المواد الدراسية...');

        $subjects = [
            ['name' => 'اللغة العربية', 'code' => 'AR', 'dept' => 'اللغات', 'lang' => true],
            ['name' => 'اللغة الإنجليزية', 'code' => 'EN', 'dept' => 'اللغات', 'lang' => true],
            ['name' => 'اللغة الكردية', 'code' => 'KU', 'dept' => 'اللغات', 'lang' => true],
            ['name' => 'الرياضيات', 'code' => 'MA', 'dept' => 'الرياضيات', 'lang' => false],
            ['name' => 'العلوم', 'code' => 'SC', 'dept' => 'العلوم', 'lang' => false],
            ['name' => 'الأحياء', 'code' => 'BIO', 'dept' => 'العلوم', 'lang' => false],
            ['name' => 'الكيمياء', 'code' => 'CH', 'dept' => 'العلوم', 'lang' => false],
            ['name' => 'الفيزياء', 'code' => 'PH', 'dept' => 'العلوم', 'lang' => false],
            ['name' => 'الجغرافية', 'code' => 'GEO', 'dept' => 'الاجتماعيات', 'lang' => false],
            ['name' => 'التاريخ', 'code' => 'HIS', 'dept' => 'الاجتماعيات', 'lang' => false],
            ['name' => 'التربية الوطنية', 'code' => 'CIV', 'dept' => 'الاجتماعيات', 'lang' => false],
            ['name' => 'الศาสนา الإسلامية', 'code' => 'ISL', 'dept' => 'الدراسات الإسلامية', 'lang' => false],
            ['name' => 'التربية الفنية', 'code' => 'ART', 'dept' => 'الفنون', 'lang' => false],
            ['name' => 'التربية الرياضية', 'code' => 'PE', 'dept' => 'التربية الرياضية', 'lang' => false],
            ['name' => 'تكنولوجيا المعلومات', 'code' => 'IT', 'dept' => 'التكنولوجيا', 'lang' => false],
            ['name' => 'اللغة الفرنسية', 'code' => 'FR', 'dept' => 'اللغات', 'lang' => true],
            ['name' => 'الموسيقى', 'code' => 'MUS', 'dept' => 'الفنون', 'lang' => false],
            ['name' => 'الاقتصاد المنزلي', 'code' => 'HEC', 'dept' => 'الاجتماعيات', 'lang' => false],
        ];

        foreach ($subjects as $s) {
            $dept = Department::where('name', $s['dept'])->first();
            Subject::updateOrCreate(['code' => $s['code']], [
                'name' => $s['name'],
                'department_id' => $dept?->id,
                'is_language' => $s['lang'],
                'active' => true,
            ]);
        }
    }

    private function seedClassrooms(): void
    {
        $this->command->info('  -> إدخال الفصول الدراسية...');

        $buildings = ['المبنى الرئيسي', 'جناح العلوم', 'المبنى الجديد'];
        $data = [];
        foreach ($buildings as $bi => $building) {
            for ($f = 1; $f <= 3; $f++) {
                for ($r = 1; $r <= 4; $r++) {
                    $data[] = [
                        'name' => "فصل {$building[0]}{$f}{$r}",
                        'building' => $building,
                        'floor' => $f,
                        'capacity' => 40,
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        $this->safeInsert('classrooms', $data);
    }

    private function seedExamTypes(): void
    {
        $this->command->info('  -> إدخال أنواع الاختبارات...');

        $types = [
            ['name' => 'اختبار منتصف الفصل', 'weight' => 30, 'active' => true],
            ['name' => 'اختبار نهائي', 'weight' => 50, 'active' => true],
            ['name' => 'اختبار قصير', 'weight' => 10, 'active' => true],
            ['name' => 'واجب منزلي', 'weight' => 10, 'active' => true],
        ];

        foreach ($types as $t) {
            ExamType::updateOrCreate(['name' => $t['name']], $t);
        }
    }

    private function seedFaculty(): void
    {
        $this->command->info('  -> إدخال 100 عضو هيئة تدريس...');

        $depts = Department::pluck('id')->toArray();
        $qualifications = ['بكالوريوس', 'ماجستير', 'دكتوراه', 'دبلوم عالي', 'ماجستير تربية', 'بكالوريوس تربية'];
        $specializations = ['اللغة الإنجليزية', 'الرياضيات', 'الفيزياء', 'الكيمياء', 'الأحياء',
            'اللغة العربية', 'علوم الحاسوب', 'التاريخ', 'الجغرافية', 'التربية الرياضية'];

        $data = [];
        for ($i = 1; $i <= 100; $i++) {
            $code = 'FAC' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $gender = $i % 3 === 0 ? 'female' : 'male';
            $name = $gender === 'male'
                ? $this->iraqiFirstNamesMale[array_rand($this->iraqiFirstNamesMale)]
                : $this->iraqiFirstNamesFemale[array_rand($this->iraqiFirstNamesFemale)];

            $data[] = [
                'faculty_code' => $code,
                'name' => trim($name),
                'middle_name' => mb_substr('أحمد علي حسن محمد عبدالله سعيد', rand(0, 5), 1),
                'last_name' => $this->lastNames[array_rand($this->lastNames)],
                'birth_date' => date('Y-m-d', rand(19700101, 19951231)),
                'gender' => $gender,
                'marital_status' => $i % 3 === 0 ? 'single' : 'married',
                'national_id' => 'NID' . str_pad(50000 + $i, 8, '0', STR_PAD_LEFT),
                'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'mobile' => '078' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'email' => 'faculty' . $i . '@edubba.test',
                'address' => $this->cities[array_rand($this->cities)] . ' - شارع ' . rand(1, 100),
                'qualification' => $qualifications[array_rand($qualifications)],
                'specialization' => $specializations[array_rand($specializations)],
                'join_date' => date('Y-m-d', rand(20150101, 20250901)),
                'department_id' => $depts[array_rand($depts)],
                'state' => 'joined',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('faculties', $data);
        $facultyIds = DB::table('faculties')->pluck('id')->toArray();

        $hrData = [];
        $contractTypes = ['دوام كامل', 'دوام جزئي', 'عقد'];
        foreach ($facultyIds as $fid) {
            $hrData[] = [
                'faculty_id' => $fid,
                'employee_type' => $contractTypes[array_rand($contractTypes)],
                'contract_start' => date('Y-m-d', rand(20200101, 20250901)),
                'contract_end' => date('Y-m-d', rand(20260101, 20281231)),
                'salary' => rand(800, 3000),
                'bank_name' => ['الرفدين', 'الراشد', 'بي bank', 'البنك المركزي العراقي'][array_rand([0, 1, 2, 3])],
                'bank_account' => 'BANK' . rand(10000000, 99999999),
                'tin' => 'TIN' . rand(100000, 999999),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('faculty_hr', $hrData);
    }

    private function seedBatches(): void
    {
        $this->command->info('  -> إدخال الفوج...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $programIds = Program::pluck('id')->toArray();
        $facultyIds = DB::table('faculties')->pluck('id')->toArray();

        $data = [];
        $gradeLevels = ['الصف الأول', 'الصف الثاني', 'الصف الثالث', 'الصف الرابع', 'الصف الخامس', 'الصف السادس',
            'الصف السابع', 'الصف الثامن', 'الصف التاسع', 'الصف العاشر', 'الصف الحادي عشر', 'الصف الثاني عشر'];
        $sections = ['أ', 'ب', 'ج'];

        foreach ($gradeLevels as $gi => $grade) {
            foreach ($sections as $section) {
                $progIdx = $gi % count($programIds);
                $data[] = [
                    'name' => "{$grade} {$section}",
                    'program_id' => $programIds[$progIdx],
                    'academic_year_id' => $year->id,
                    'class_teacher_id' => $facultyIds[array_rand($facultyIds)],
                    'capacity' => 40,
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $this->safeInsert('batches', $data);
    }

    private function seedCourses(): void
    {
        $this->command->info('  -> إدخال المقررات...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get();
        $subjects = Subject::all();
        $facultyIds = DB::table('faculties')->pluck('id')->toArray();

        $data = [];
        foreach ($batches as $batch) {
            $batchSubjects = $subjects->random(min(8, $subjects->count()));
            foreach ($batchSubjects as $sub) {
                $code = $sub->code . str_replace(' ', '', $batch->name);
                $data[] = [
                    'name' => "{$sub->name} {$batch->name}",
                    'code' => $code,
                    'subject_id' => $sub->id,
                    'program_id' => $batch->program_id,
                    'batch_id' => $batch->id,
                    'academic_year_id' => $year->id,
                    'faculty_id' => $facultyIds[array_rand($facultyIds)],
                    'credit_hours' => rand(1, 4),
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $this->safeInsert('courses', $data);
    }

    private function seedParents(): void
    {
        $this->command->info('  -> إدخال 3500 ولي أمر...');

        $data = [];
        for ($i = 1; $i <= 3500; $i++) {
            $name = $this->iraqiFirstNamesMale[array_rand($this->iraqiFirstNamesMale)]
                . ' ' . $this->lastNames[array_rand($this->lastNames)];
            $data[] = [
                'name' => trim($name),
                'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'mobile' => '078' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'email' => 'parent' . $i . '@test.com',
                'national_id' => 'PID' . str_pad(100000 + $i, 8, '0', STR_PAD_LEFT),
                'address' => $this->cities[array_rand($this->cities)] . ' - ' . rand(1, 200),
                'occupation' => ['مهندس', 'طبيب', 'مدرس', 'سائق', 'تاجر', 'ضابط', 'موظف'][rand(0, 6)],
                'relation' => 'father',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('parents', $data);
    }

    private function seedStudents(): void
    {
        $this->command->info('  -> إدخال 2000 طالب...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get()->keyBy('id');
        $parentIds = DB::table('parents')->pluck('id')->toArray();
        $deptIds = Department::pluck('id')->toArray();

        $studentData = [];
        $studentParentData = [];
        $studentCounter = 0;

        $studentsPerBatch = 2000 / count($batches);

        foreach ($batches as $batch) {
            $count = (int) $studentsPerBatch + (rand(0, 5) > 3 ? 1 : 0);

            for ($i = 0; $i < $count && $studentCounter < 2000; $i++) {
                $studentCounter++;
                $gender = rand(0, 1) ? 'male' : 'female';
                $namePool = $gender === 'male' ? $this->iraqiFirstNamesMale : $this->iraqiFirstNamesFemale;

                $studentData[] = [
                    'student_code' => 'STU' . str_pad($studentCounter, 5, '0', STR_PAD_LEFT),
                    'name' => trim($namePool[array_rand($namePool)]),
                    'middle_name' => trim($namePool[array_rand($namePool)]),
                    'last_name' => trim($this->lastNames[array_rand($this->lastNames)]),
                    'gender' => $gender,
                    'birth_date' => date('Y-m-d', rand(20060101, 20161231)),
                    'birth_place' => $this->cities[array_rand($this->cities)],
                    'national_id' => 'SID' . str_pad(100000 + $studentCounter, 8, '0', STR_PAD_LEFT),
                    'residence' => $this->cities[array_rand($this->cities)],
                    'marital_status' => 'single',
                    'blood_group' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'][rand(0, 7)],
                    'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'mobile' => '078' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'email' => 'student' . $studentCounter . '@edubba.test',
                    'address' => $this->cities[array_rand($this->cities)] . ' - شارع ' . rand(1, 50),
                    'city' => $this->cities[array_rand($this->cities)],
                    'province' => $this->governorates[array_rand($this->governorates)],
                    'country' => 'العراق',
                    'zip' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                    'batch_id' => $batch->id,
                    'program_id' => $batch->program_id,
                    'academic_year_id' => $year->id,
                    'parent_id' => $parentIds[array_rand($parentIds)],
                    'department_id' => $deptIds[array_rand($deptIds)],
                    'state' => 'admitted',
                    'admission_date' => '2025-09-01',
                    'roll_no' => 'RN' . str_pad($studentCounter, 5, '0', STR_PAD_LEFT),
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $parentIdx = array_rand($parentIds);
                $studentParentData[] = [
                    'student_id' => null,
                    'parent_id' => $parentIds[$parentIdx],
                    'relation' => 'father',
                    'is_main' => true,
                    'guardian' => true,
                    'emergency_contact' => true,
                ];
            }
        }

        $this->safeInsert('students', $studentData);

        $allStudents = DB::table('students')->pluck('id')->toArray();
        foreach ($studentParentData as $idx => &$sp) {
            if (isset($allStudents[$idx])) {
                $sp['student_id'] = $allStudents[$idx];
            }
        }
        $this->safeInsert('student_parent', $studentParentData);
    }

    private function seedStudentCourses(): void
    {
        $this->command->info('  -> تسجيل الطلاب في المقررات...');

        $year = AcademicYear::where('name', '2025-2026')->first();

        $students = DB::table('students')
            ->where('academic_year_id', $year->id)
            ->select('id', 'batch_id')
            ->get()
            ->groupBy('batch_id');

        $coursesByBatch = DB::table('courses')
            ->where('academic_year_id', $year->id)
            ->get()
            ->groupBy('batch_id');

        $data = [];
        foreach ($students as $batchId => $batchStudents) {
            $courses = $coursesByBatch->get($batchId, collect());
            foreach ($batchStudents as $student) {
                foreach ($courses as $course) {
                    $data[] = [
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'batch_id' => $batchId,
                        'academic_year_id' => $year->id,
                        'state' => 'running',
                        'total_fees' => rand(50000, 500000),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('student_course')->insertOrIgnore($chunk);
        }
    }

    private function seedTimetables(): void
    {
        $this->command->info('  -> إدخال الجداول الدراسية...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $term = Term::where('academic_year_id', $year->id)->where('name', 'الفصل الأول')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get();
        $weekDays = WeekDay::pluck('id')->toArray();
        $timings = Timing::pluck('id')->toArray();
        $subjects = Subject::pluck('id')->toArray();
        $classrooms = DB::table('classrooms')->pluck('id')->toArray();
        $facultyIds = DB::table('faculties')->pluck('id')->toArray();

        $lineData = [];

        foreach ($batches as $batch) {
            $tt = TimeTable::create([
                'batch_id' => $batch->id,
                'academic_year_id' => $year->id,
                'term_id' => $term?->id,
                'name' => "الجدول الزمني {$batch->name}",
                'active' => true,
            ]);

            $batchCourses = DB::table('courses')->where('batch_id', $batch->id)->get();

            foreach (array_slice($weekDays, 0, 5) as $dayId) {
                $numPeriods = rand(3, min(6, count($timings)));
                $usedTimings = array_slice($timings, 0, $numPeriods);

                foreach ($usedTimings as $timingId) {
                    $course = $batchCourses->random();
                    $lineData[] = [
                        'time_table_id' => $tt->id,
                        'week_day_id' => $dayId,
                        'timing_id' => $timingId,
                        'subject_id' => $course->subject_id ?? $subjects[array_rand($subjects)],
                        'faculty_id' => $course->faculty_id ?? $facultyIds[array_rand($facultyIds)],
                        'course_id' => $course->id,
                        'classroom_id' => $classrooms[array_rand($classrooms)],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        foreach (array_chunk($lineData, 50) as $chunk) {
            DB::table('time_table_lines')->insert($chunk);
        }
    }

    private function seedClassSessions(): void
    {
        $this->command->info('  -> إدخال الحصص الدراسية...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $term = Term::where('academic_year_id', $year->id)->where('name', 'الفصل الأول')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get();
        $facultyIds = DB::table('faculties')->pluck('id')->toArray();
        $classrooms = DB::table('classrooms')->pluck('id')->toArray();

        $data = [];
        $sessionId = 1;

        foreach ($batches as $batch) {
            $batchCourses = DB::table('courses')->where('batch_id', $batch->id)->get();
            if ($batchCourses->isEmpty()) continue;

            $startDate = new \Carbon\Carbon('2025-09-01');
            $endDate = new \Carbon\Carbon('2026-01-15');
            $daysBetween = $startDate->diffInDays($endDate);

            for ($s = 0; $s < 50; $s++) {
                $dayOffset = rand(0, $daysBetween);
                $sessionDate = $startDate->copy()->addDays($dayOffset);
                if ($sessionDate->isFriday()) continue;

                $course = $batchCourses->random();
                $startHour = rand(8, 12);
                $startMin = [0, 30, 45][rand(0, 2)];

                $data[] = [
                    'id' => $sessionId++,
                    'batch_id' => $batch->id,
                    'course_id' => $course->id,
                    'subject_id' => $course->subject_id,
                    'faculty_id' => $course->faculty_id ?? $facultyIds[array_rand($facultyIds)],
                    'classroom_id' => $classrooms[array_rand($classrooms)],
                    'date' => $sessionDate->format('Y-m-d'),
                    'start_time' => sprintf('%02d:%02d:00', $startHour, $startMin),
                    'end_time' => sprintf('%02d:%02d:00', $startHour + 1, $startMin),
                    'state' => 'done',
                    'topic' => 'الدرس ' . ($s + 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('class_sessions')->insert($chunk);
        }
    }

    private function seedAttendance(): void
    {
        $this->command->info('  -> إدخال سجلات الحضور...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $sessions = DB::table('class_sessions')
            ->where('state', 'done')
            ->select('id', 'batch_id', 'course_id', 'faculty_id', 'date')
            ->get();

        $sheetData = [];
        $lineData = [];
        $sheetId = 1;

        foreach ($sessions as $session) {
            $students = DB::table('students')
                ->where('batch_id', $session->batch_id)
                ->where('state', 'admitted')
                ->pluck('id')
                ->toArray();

            if (empty($students)) continue;

            $sheetData[] = [
                'id' => $sheetId,
                'session_id' => $session->id,
                'batch_id' => $session->batch_id,
                'course_id' => $session->course_id,
                'faculty_id' => $session->faculty_id,
                'date' => $session->date,
                'state' => 'done',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            foreach ($students as $studentId) {
                $rand = rand(1, 100);
                if ($rand <= 70) $status = 'present';
                elseif ($rand <= 85) $status = 'absent';
                elseif ($rand <= 95) $status = 'late';
                else $status = 'leave';

                $lineData[] = [
                    'attendance_sheet_id' => $sheetId,
                    'student_id' => $studentId,
                    'status' => $status,
                    'note' => null,
                ];
            }

            $sheetId++;
        }

        foreach (array_chunk($sheetData, 50) as $chunk) {
            DB::table('attendance_sheets')->insert($chunk);
        }
        foreach (array_chunk($lineData, 50) as $chunk) {
            DB::table('attendance_lines')->insert($chunk);
        }
    }

    private function seedExams(): void
    {
        $this->command->info('  -> إدخال الاختبارات...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $term = Term::where('academic_year_id', $year->id)->where('name', 'الفصل الأول')->first();
        $examTypes = ExamType::all();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get();

        $data = [];
        foreach ($batches as $batch) {
            foreach ($examTypes as $type) {
                $data[] = [
                    'name' => "{$type->name} - {$batch->name}",
                    'exam_type_id' => $type->id,
                    'academic_year_id' => $year->id,
                    'term_id' => $term?->id,
                    'batch_id' => $batch->id,
                    'date_start' => '2025-12-01',
                    'date_end' => '2025-12-20',
                    'state' => 'done',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $this->safeInsert('exams', $data);

        $exams = DB::table('exams')->get();
        $subjects = Subject::pluck('id')->toArray();

        $scheduleData = [];
        foreach ($exams as $exam) {
            $batchCourses = DB::table('courses')->where('batch_id', $exam->batch_id)->get();
            $dayOffset = 0;
            foreach ($batchCourses as $course) {
                $scheduleData[] = [
                    'exam_id' => $exam->id,
                    'subject_id' => $course->subject_id,
                    'course_id' => $course->id,
                    'date' => date('Y-m-d', strtotime("2025-12-01 +{$dayOffset} days")),
                    'start_time' => '09:00:00',
                    'end_time' => '11:00:00',
                    'max_marks' => 100,
                    'pass_marks' => 50,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $dayOffset++;
            }
        }

        foreach (array_chunk($scheduleData, 50) as $chunk) {
            DB::table('exam_schedules')->insert($chunk);
        }
    }

    private function seedMarksheets(): void
    {
        $this->command->info('  -> إدخال كشوف الدرجات...');

        $exams = DB::table('exams')->where('state', 'done')->get();

        $marksheetData = [];
        $lineData = [];
        $msId = 1;

        $gradeMap = [
            [90, 100, 'ممتاز'], [80, 89.99, 'جيد جداً'], [70, 79.99, 'جيد'],
            [60, 69.99, 'مقبول'], [50, 59.99, 'مقبول'], [40, 49.99, 'ضعيف'], [0, 39.99, 'راسب'],
        ];

        foreach ($exams as $exam) {
            $students = DB::table('students')
                ->where('batch_id', $exam->batch_id)
                ->where('state', 'admitted')
                ->pluck('id')
                ->toArray();

            $batchCourses = DB::table('courses')->where('batch_id', $exam->batch_id)->get();

            foreach ($students as $studentId) {
                $totalObtained = 0;
                $totalMax = 0;

                $msLines = [];
                foreach ($batchCourses as $course) {
                    $maxMarks = 100;
                    $marks = rand(20, 100);
                    $passMarks = 50;
                    $pct = ($marks / $maxMarks) * 100;
                    $grade = 'راسب';
                    foreach ($gradeMap as $g) {
                        if ($pct >= $g[0] && $pct <= $g[1]) { $grade = $g[2]; break; }
                    }

                    $totalObtained += $marks;
                    $totalMax += $maxMarks;

                    $msLines[] = [
                        'marksheet_id' => $msId,
                        'subject_id' => $course->subject_id,
                        'course_id' => $course->id,
                        'max_marks' => $maxMarks,
                        'marks' => $marks,
                        'pass_marks' => $passMarks,
                        'percentage' => round($pct, 2),
                        'grade' => $grade,
                        'passed' => $marks >= $passMarks,
                    ];
                }

                $overallPct = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
                $overallGrade = 'راسب';
                foreach ($gradeMap as $g) {
                    if ($overallPct >= $g[0] && $overallPct <= $g[1]) { $overallGrade = $g[2]; break; }
                }

                $marksheetData[] = [
                    'id' => $msId,
                    'exam_id' => $exam->id,
                    'student_id' => $studentId,
                    'batch_id' => $exam->batch_id,
                    'total_marks' => $totalMax,
                    'obtained_marks' => $totalObtained,
                    'percentage' => $overallPct,
                    'grade' => $overallGrade,
                    'result' => $overallPct >= 50 ? 'pass' : 'fail',
                    'rank' => null,
                    'state' => 'done',
                    'finalized_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $lineData = array_merge($lineData, $msLines);
                $msId++;
            }
        }

        foreach (array_chunk($marksheetData, 50) as $chunk) {
            DB::table('marksheets')->insert($chunk);
        }
        foreach (array_chunk($lineData, 50) as $chunk) {
            DB::table('marksheet_lines')->insert($chunk);
        }
    }

    private function seedExamResults(): void
    {
        $this->command->info('  -> إدخال نتائج الاختبارات...');

        $exams = DB::table('exams')->where('state', 'done')->get();
        $year = AcademicYear::where('name', '2025-2026')->first();
        $term = Term::where('academic_year_id', $year->id)->where('name', 'الفصل الأول')->first();

        $data = [];
        foreach ($exams as $exam) {
            $marksheets = DB::table('marksheets')
                ->where('exam_id', $exam->id)
                ->orderByDesc('percentage')
                ->get();

            $rank = 1;
            foreach ($marksheets as $ms) {
                $data[] = [
                    'student_id' => $ms->student_id,
                    'exam_id' => $exam->id,
                    'term_id' => $term?->id,
                    'academic_year_id' => $year->id,
                    'batch_id' => $exam->batch_id,
                    'total' => $ms->obtained_marks,
                    'average' => $ms->percentage,
                    'grade' => $ms->grade,
                    'rank' => $rank++,
                    'result' => $ms->result,
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('exam_results')->insert($chunk);
        }
    }

    private function seedFeeStructures(): void
    {
        $this->command->info('  -> إدخال هياكل الرسوم...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get();

        $feeLineNames = [
            ['رسوم الدراسة', 200000, 'recurring'],
            ['رسوم التسجيل', 50000, 'one_time'],
            ['رسوم المختبر', 30000, 'recurring'],
            ['رسوم المكتبة', 15000, 'one_time'],
            ['رسوم الرياضة', 10000, 'one_time'],
            ['رسوم الاختبارات', 25000, 'recurring'],
        ];

        foreach ($batches as $batch) {
            $fs = FeeStructure::create([
                'name' => "رسوم {$batch->name}",
                'program_id' => $batch->program_id,
                'batch_id' => $batch->id,
                'academic_year_id' => $year->id,
                'active' => true,
            ]);

            foreach ($feeLineNames as $seq => $fl) {
                FeeLine::create([
                    'fee_structure_id' => $fs->id,
                    'name' => $fl[0],
                    'amount' => $fl[1] + rand(-10000, 20000),
                    'type' => $fl[2],
                    'sequence' => $seq,
                ]);
            }
        }
    }

    private function seedInvoices(): void
    {
        $this->command->info('  -> إدخال الفواتير...');

        $year = AcademicYear::where('name', '2025-2026')->first();

        $students = DB::table('students')
            ->where('academic_year_id', $year->id)
            ->where('state', 'admitted')
            ->select('id', 'parent_id', 'batch_id')
            ->get();

        $invoiceData = [];
        $lineData = [];
        $invNum = 1;

        foreach ($students as $student) {
            $invId = $invNum;

            $feeLines = [
                ['رسوم الدراسة', rand(150000, 250000)],
                ['رسوم التسجيل', rand(40000, 60000)],
                ['رسوم الاختبارات', rand(20000, 30000)],
            ];

            $total = 0;
            foreach ($feeLines as $fl) {
                $total += $fl[1];
                $lineData[] = [
                    'invoice_id' => $invId,
                    'description' => $fl[0],
                    'qty' => 1,
                    'unit_price' => $fl[1],
                    'amount' => $fl[1],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $states = ['open', 'open', 'open', 'paid', 'paid'];
            $state = $states[array_rand($states)];

            $invoiceData[] = [
                'number' => 'INV' . str_pad($invNum, 6, '0', STR_PAD_LEFT),
                'student_id' => $student->id,
                'parent_id' => $student->parent_id,
                'academic_year_id' => $year->id,
                'date' => '2025-09-15',
                'due_date' => '2025-10-15',
                'subtotal' => $total,
                'tax' => 0,
                'total' => $total,
                'paid' => $state === 'paid' ? $total : 0,
                'balance' => $state === 'paid' ? 0 : $total,
                'state' => $state,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $invNum++;
        }

        foreach (array_chunk($invoiceData, 50) as $chunk) {
            DB::table('invoices')->insert($chunk);
        }
        foreach (array_chunk($lineData, 50) as $chunk) {
            DB::table('invoice_lines')->insert($chunk);
        }
    }

    private function seedPayments(): void
    {
        $this->command->info('  -> إدخال المدفوعات والإيصالات...');

        $paidInvoices = DB::table('invoices')->where('state', 'paid')->get();
        $methods = ['نقدي', 'بطاقة', 'تحويل', 'محفظة'];

        $payData = [];
        $receiptData = [];
        $payId = 1;
        $recNum = 1;

        foreach ($paidInvoices as $inv) {
            $ref = 'PAY' . str_pad($payId, 6, '0', STR_PAD_LEFT);
            $method = $methods[array_rand($methods)];

            $payData[] = [
                'reference' => $ref,
                'invoice_id' => $inv->id,
                'student_id' => $inv->student_id,
                'parent_id' => $inv->parent_id,
                'amount' => $inv->total,
                'method' => $method,
                'gateway' => $method === 'محفظة' ? 'زين كاش' : null,
                'transaction_id' => $method !== 'نقدي' ? 'TXN' . rand(100000, 999999) : null,
                'state' => 'done',
                'date' => '2025-10-01',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $receiptData[] = [
                'receipt_no' => 'RCP' . str_pad($recNum, 6, '0', STR_PAD_LEFT),
                'payment_id' => $payId,
                'invoice_id' => $inv->id,
                'date' => '2025-10-01',
                'amount' => $inv->total,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $payId++;
            $recNum++;
        }

        foreach (array_chunk($payData, 50) as $chunk) {
            DB::table('payments')->insert($chunk);
        }
        foreach (array_chunk($receiptData, 50) as $chunk) {
            DB::table('receipts')->insert($chunk);
        }
    }

    private function seedAdmissions(): void
    {
        $this->command->info('  -> إدخال طلبات القبول...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get();

        $reg = AdmissionRegister::firstOrCreate(
            ['name' => 'قبول 2025-2026'],
            ['academic_year_id' => $year->id, 'batch_id' => $batches->first()->id ?? null,
                'start_date' => '2025-06-01', 'end_date' => '2025-08-31', 'active' => true]
        );

        $states = ['draft', 'submit', 'approve', 'admitted', 'reject'];
        $data = [];

        for ($i = 1; $i <= 200; $i++) {
            $batch = $batches->random();
            $state = $states[array_rand($states)];
            $gender = rand(0, 1) ? 'male' : 'female';
            $namePool = $gender === 'male' ? $this->iraqiFirstNamesMale : $this->iraqiFirstNamesFemale;
            $data[] = [
                'application_no' => 'ADM' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'register_id' => $reg->id,
                'academic_year_id' => $year->id,
                'batch_id' => $batch->id,
                'program_id' => $batch->program_id,
                'name' => trim($namePool[array_rand($namePool)]),
                'middle_name' => trim($namePool[array_rand($namePool)]),
                'last_name' => trim($this->lastNames[array_rand($this->lastNames)]),
                'birth_date' => date('Y-m-d', rand(20060101, 20161231)),
                'gender' => $gender,
                'national_id' => 'ADMNID' . str_pad(200000 + $i, 8, '0', STR_PAD_LEFT),
                'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'email' => 'adm' . $i . '@test.com',
                'address' => $this->cities[array_rand($this->cities)],
                'previous_school' => 'مدرسة ' . rand(1, 50),
                'fees_amount' => rand(150000, 300000),
                'state' => $state,
                'notes' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('admissions', $data);
    }

    private function seedTutoring(): void
    {
        $this->command->info('  -> إدخال مراكز التقوية...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $subjects = Subject::pluck('id')->toArray();

        $centerNames = ['مركز النور', 'مركز البيان', 'مركز الحكمة', 'مركز الفلاح', 'مركز الإيمان'];
        foreach ($centerNames as $cn) {
            Center::firstOrCreate(['name' => $cn], [
                'address' => $this->cities[array_rand($this->cities)],
                'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'active' => true,
            ]);
        }

        $centers = Center::all();
        foreach ($centers as $center) {
            for ($b = 1; $b <= 2; $b++) {
                Branch::firstOrCreate(
                    ['center_id' => $center->id, 'name' => "{$center->name} فرع {$b}"],
                    ['address' => $center->address . " - فرع {$b}", 'active' => true]
                );
            }
        }

        $facultyIds = DB::table('faculties')->pluck('id')->toArray();
        $tutorData = [];
        for ($i = 1; $i <= 25; $i++) {
            $tutorData[] = [
                'name' => trim($this->iraqiFirstNamesMale[array_rand($this->iraqiFirstNamesMale)] . ' ' . $this->lastNames[array_rand($this->lastNames)]),
                'phone' => '079' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'email' => 'tutor' . $i . '@edubba.test',
                'faculty_id' => $facultyIds[array_rand($facultyIds)],
                'subjects' => json_encode(array_slice($subjects, 0, rand(1, 3))),
                'hourly_rate' => rand(5000, 25000),
                'state' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('tutors', $tutorData);

        $pkgData = [
            ['name' => 'الأساسي (8 حصص)', 'sessions' => 8, 'price' => 80000, 'active' => true],
            ['name' => 'القياسي (16 حصة)', 'sessions' => 16, 'price' => 150000, 'active' => true],
            ['name' => 'المتقدم (24 حصة)', 'sessions' => 24, 'price' => 200000, 'active' => true],
            ['name' => 'المكثف (32 حصة)', 'sessions' => 32, 'price' => 260000, 'active' => true],
        ];
        foreach ($pkgData as $p) {
            TutoringPackage::firstOrCreate(['name' => $p['name']], $p);
        }

        $prodData = [
            ['name' => 'المواد الدراسية', 'code' => 'MAT01', 'price' => 25000, 'active' => true],
            ['name' => 'كتاب التمارين', 'code' => 'MAT02', 'price' => 15000, 'active' => true],
        ];
        foreach ($prodData as $p) {
            TutoringProduct::firstOrCreate(['code' => $p['code']], $p);
        }

        $tutors = DB::table('tutors')->get();
        $packages = TutoringPackage::all();
        $products = TutoringProduct::all();
        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->toArray();
        $centerIds = Center::pluck('id')->toArray();

        $sgData = [];
        $sgStudentData = [];
        $sgSessionData = [];
        $sgAttendData = [];

        $groupSubjects = ['الرياضيات', 'العلوم', 'اللغة العربية', 'اللغة الإنجليزية', 'الفيزياء', 'الكيمياء'];

        for ($i = 1; $i <= 40; $i++) {
            $sgId = $i;
            $tutor = $tutors->random();
            $maxStud = rand(5, 15);
            $subj = $groupSubjects[array_rand($groupSubjects)];

            $sgData[] = [
                'id' => $sgId,
                'name' => "مجموعة {$subj} {$i}",
                'subject_id' => $subjects[array_rand($subjects)],
                'tutor_id' => $tutor->id,
                'center_id' => $centerIds[array_rand($centerIds)],
                'max_students' => $maxStud,
                'level' => ['مبتدئ', 'متوسط', 'متقدم'][rand(0, 2)],
                'state' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $groupStudents = array_slice($studentIds, ($i * 10) % count($studentIds), $maxStud);
            foreach ($groupStudents as $sid) {
                $sgStudentData[] = [
                    'study_group_id' => $sgId,
                    'student_id' => $sid,
                    'join_date' => '2025-09-15',
                    'state' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            for ($s = 1; $s <= 10; $s++) {
                $sessId = ($sgId - 1) * 10 + $s;
                $sgSessionData[] = [
                    'id' => $sessId,
                    'study_group_id' => $sgId,
                    'tutor_id' => $tutor->id,
                    'date' => date('Y-m-d', strtotime("2025-09-15 +" . (($s - 1) * 2) . " days")),
                    'start_time' => '14:00:00',
                    'end_time' => '16:00:00',
                    'state' => 'done',
                    'notes' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                foreach ($groupStudents as $sid) {
                    $sgAttendData[] = [
                        'study_group_session_id' => $sessId,
                        'student_id' => $sid,
                        'status' => rand(0, 10) > 2 ? 'present' : 'absent',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        $this->safeInsert('study_groups', $sgData);
        $this->safeInsert('study_group_students', $sgStudentData);
        $this->safeInsert('study_group_sessions', $sgSessionData);
        $this->safeInsert('study_group_attendances', $sgAttendData);

        $subsData = [];
        $subPayData = [];
        $subNum = 1;

        for ($i = 1; $i <= 300; $i++) {
            $pkg = $packages->random();
            $studIdx = ($i - 1) % count($studentIds);
            $subId = $subNum;

            $subsData[] = [
                'reference' => 'SUB' . str_pad($subNum, 6, '0', STR_PAD_LEFT),
                'student_id' => $studentIds[$studIdx],
                'parent_id' => DB::table('parents')->pluck('id')->random(),
                'tutor_id' => $tutors->random()->id,
                'study_group_id' => rand(1, 40),
                'package_id' => $pkg->id,
                'product_id' => $products->random()->id,
                'start_date' => '2025-09-15',
                'end_date' => '2026-01-15',
                'frequency' => 'weekly',
                'sessions_count' => $pkg->sessions,
                'sessions_used' => rand(0, $pkg->sessions),
                'amount' => $pkg->price,
                'paid_amount' => rand(0, $pkg->price),
                'state' => ['active', 'active', 'active', 'paused', 'expired'][rand(0, 4)],
                'next_renewal_date' => '2026-02-01',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (rand(0, 1)) {
                $subPayData[] = [
                    'subscription_id' => $subId,
                    'date' => '2025-09-15',
                    'amount' => $pkg->price * 0.5,
                    'method' => ['نقدي', 'بطاقة', 'محفظة'][rand(0, 2)],
                    'transaction_id' => 'TPAY' . rand(100000, 999999),
                    'state' => 'done',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $subNum++;
        }

        $this->safeInsert('subscriptions', $subsData);
        $this->safeInsert('subscription_payments', $subPayData);
    }

    private function seedTraining(): void
    {
        $this->command->info('  -> إدخال الدورات التدريبية...');

        $courses = [
            ['name' => 'تطوير تطبيقات الويب', 'code' => 'TR001', 'description' => 'تطوير مواقع الويب الكامل', 'duration_hours' => 60, 'price' => 500000],
            ['name' => 'لغة بايثون لعلوم البيانات', 'code' => 'TR002', 'description' => 'أساسيات بايثون والتعلم الآلي', 'duration_hours' => 40, 'price' => 400000],
            ['name' => 'إعداد اختبار آيلتس', 'code' => 'TR003', 'description' => 'تحضير لاختبار آيلتس', 'duration_hours' => 30, 'price' => 350000],
            ['name' => 'تصميم الجرافيك', 'code' => 'TR004', 'description' => 'تدريب على أدوبى', 'duration_hours' => 25, 'price' => 300000],
            ['name' => 'إدارة المشاريع', 'code' => 'TR005', 'description' => 'إعداد شهادة إدارة المشاريع', 'duration_hours' => 35, 'price' => 450000],
            ['name' => 'إدارة الشبكات', 'code' => 'TR006', 'description' => 'إعداد شهادة سيسكو', 'duration_hours' => 50, 'price' => 550000],
            ['name' => 'تطوير تطبيقات الهاتف', 'code' => 'TR007', 'description' => 'تدريب رياكت نيتيف', 'duration_hours' => 45, 'price' => 480000],
            ['name' => 'التسويق الرقمي', 'code' => 'TR008', 'description' => 'تحسين محركات البحث والوسائط الاجتماعية', 'duration_hours' => 20, 'price' => 250000],
        ];

        foreach ($courses as $c) {
            TrainingCourse::firstOrCreate(['code' => $c['code']], $c);
        }

        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->toArray();
        $trainCourses = TrainingCourse::all();

        $enrollData = [];
        for ($i = 1; $i <= 150; $i++) {
            $tc = $trainCourses->random();
            $sid = $studentIds[array_rand($studentIds)];
            $enrollData[] = [
                'training_course_id' => $tc->id,
                'student_id' => $sid,
                'participant_id' => null,
                'enroll_date' => '2025-10-01',
                'state' => ['confirmed', 'done'][rand(0, 1)],
                'amount_paid' => rand(100000, $tc->price),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('training_enrollments', $enrollData);
    }

    private function seedLibrary(): void
    {
        $this->command->info('  -> إدخال المكتبة...');

        $categories = ['علوم', 'رياضيات', 'أدب', 'تاريخ', 'تكنولوجيا', 'فنون', 'دين', 'لغة'];
        $authors = ['جبران خليل جبران', 'محمود درويش', 'أحمد رامي', 'نيكانور بارا', 'جورج طرابيشي'];

        $bookData = [];
        for ($i = 1; $i <= 500; $i++) {
            $qty = rand(1, 10);
            $cat = $categories[array_rand($categories)];
            $bookData[] = [
                'title' => "كتاب {$cat} رقم {$i}",
                'author' => $authors[array_rand($authors)],
                'isbn' => '978-' . rand(100, 999) . '-' . rand(1000, 9999) . '-' . rand(0, 9),
                'category' => $cat,
                'total_qty' => $qty,
                'available_qty' => $qty,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('library_books', $bookData);

        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->toArray();
        $bookIds = DB::table('library_books')->pluck('id')->toArray();

        $issueData = [];
        for ($i = 1; $i <= 2000; $i++) {
            $issueDate = date('Y-m-d', rand(20250901, 20260115));
            $issueData[] = [
                'book_id' => $bookIds[array_rand($bookIds)],
                'student_id' => $studentIds[array_rand($studentIds)],
                'issue_date' => $issueDate,
                'due_date' => date('Y-m-d', strtotime($issueDate) + 14 * 86400),
                'return_date' => rand(0, 1) ? date('Y-m-d', strtotime($issueDate) + rand(3, 20) * 86400) : null,
                'fine' => 0,
                'state' => 'issued',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('library_issues', $issueData);
    }

    private function seedTransport(): void
    {
        $this->command->info('  -> إدخال النقل المدرسي...');

        $vehicleData = [];
        for ($i = 1; $i <= 15; $i++) {
            $vehicleData[] = [
                'plate_number' => str_pad($i, 4, '0', STR_PAD_LEFT) . '-' . ['BA', 'BB', 'BC'][rand(0, 2)] . '-' . rand(100, 999),
                'model' => ['هينداي كونتي', 'نيسان سيفيلين', 'تويوتا كوستر', 'مرسيدس سبرنتر'][rand(0, 3)],
                'capacity' => [20, 30, 40, 50][rand(0, 3)],
                'driver_name' => trim($this->iraqiFirstNamesMale[array_rand($this->iraqiFirstNamesMale)] . ' ' . $this->lastNames[array_rand($this->lastNames)]),
                'driver_phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('transport_vehicles', $vehicleData);

        $vehicles = DB::table('transport_vehicles')->get();
        $routeData = [];
        $stopData = [];

        foreach ($vehicles as $v) {
            $routeId = $v->id;

            $routeData[] = [
                'id' => $routeId,
                'name' => "خط {$v->plate_number}",
                'vehicle_id' => $v->id,
                'description' => 'خط النقل المدرسي',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $numStops = rand(3, 6);
            for ($s = 0; $s < $numStops; $s++) {
                $stopData[] = [
                    'route_id' => $routeId,
                    'name' => $this->neighborhoods[array_rand($this->neighborhoods)] . ' محطة ' . ($s + 1),
                    'pickup_time' => sprintf('%02d:%02d:00', 7 - $s, 30 * ($s % 2)),
                    'sequence' => $s,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $this->safeInsert('transport_routes', $routeData);
        $this->safeInsert('transport_stops', $stopData);

        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->toArray();
        $routeIds = DB::table('transport_routes')->pluck('id')->toArray();
        $stopIds = DB::table('transport_stops')->pluck('id')->toArray();

        $assignData = [];
        for ($i = 1; $i <= 300; $i++) {
            $assignData[] = [
                'student_id' => $studentIds[array_rand($studentIds)],
                'route_id' => $routeIds[array_rand($routeIds)],
                'stop_id' => $stopIds[array_rand($stopIds)],
                'start_date' => '2025-09-01',
                'end_date' => '2026-07-31',
                'state' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('transport_assignments', $assignData);
    }

    private function seedHostels(): void
    {
        $this->command->info('  -> إدخال المهاجع...');

        $hostelNames = ['مهاجع النور', 'مهاجع البركة', 'مهاجع الأمين'];
        $hostelIds = [];

        foreach ($hostelNames as $hn) {
            $h = Hostel::firstOrCreate(['name' => $hn], [
                'address' => $this->cities[array_rand($this->cities)],
                'warden_name' => trim($this->iraqiFirstNamesMale[array_rand($this->iraqiFirstNamesMale)]),
                'active' => true,
            ]);
            $hostelIds[] = $h->id;
        }

        $roomData = [];
        foreach ($hostelIds as $hid) {
            for ($f = 1; $f <= 3; $f++) {
                for ($r = 1; $r <= 10; $r++) {
                    $capacity = [2, 4, 6][rand(0, 2)];
                    $roomData[] = [
                        'hostel_id' => $hid,
                        'room_no' => "{$f}" . str_pad($r, 2, '0', STR_PAD_LEFT),
                        'capacity' => $capacity,
                        'occupied' => rand(0, $capacity),
                        'monthly_rent' => rand(50000, 200000),
                        'state' => 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        $this->safeInsert('hostel_rooms', $roomData);

        $roomIds = DB::table('hostel_rooms')->pluck('id')->toArray();
        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->toArray();

        $allocData = [];
        for ($i = 1; $i <= 200; $i++) {
            $allocData[] = [
                'room_id' => $roomIds[array_rand($roomIds)],
                'student_id' => $studentIds[array_rand($studentIds)],
                'start_date' => '2025-09-01',
                'end_date' => '2026-07-31',
                'state' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('hostel_allocations', $allocData);
    }

    private function seedNotifications(): void
    {
        $this->command->info('  -> إدخال الإشعارات...');

        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->toArray();
        $channels = ['sms', 'whatsapp', 'push', 'email'];
        $bodies = [
            'تم نشر جدول الاختبارات الرجاء مراجعته.',
            'تذكير بسداد الرسوم: الفاتورة مستحقة الأسبوع القادم.',
            'سيكون المدرسة مغلقة غداً بمناسبة العطلة الرسمية.',
            'بطاقة التقارير متاحة للتحميل.',
            'تم تحديد موعد اجتماع ولي الأمر والمعلم.',
            'تم نشر واجب جديد في مقررك.',
            'تقرير الحضور: كنت غائباً اليوم.',
            'تم استلام دفعة رسوم الدراسة. شكراً لك.',
            'تم تغيير خط النقل المدرسي بدءاً من الأسبوع القادم.',
            'احتفال اليوم الدراسي السنوي يوم الجمعة.',
        ];

        $data = [];
        for ($i = 1; $i <= 5000; $i++) {
            $sid = $studentIds[array_rand($studentIds)];
            $data[] = [
                'channel' => $channels[array_rand($channels)],
                'recipient' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'body' => $bodies[array_rand($bodies)],
                'state' => ['sent', 'sent', 'sent', 'read', 'pending'][rand(0, 4)],
                'student_id' => $sid,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('notification_logs')->insert($chunk);
        }
    }

    private function seedWallets(): void
    {
        $this->command->info('  -> إدخال المحافظ الإلكترونية...');

        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->take(500)->toArray();
        $walletData = [];
        $txData = [];

        foreach ($studentIds as $sid) {
            $balance = rand(0, 500000);
            $walletId = count($walletData) + 1;

            $walletData[] = [
                'student_id' => $sid,
                'parent_id' => DB::table('students')->where('id', $sid)->value('parent_id'),
                'balance' => $balance,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $numTx = rand(1, 5);
            for ($t = 0; $t < $numTx; $t++) {
                $amount = rand(10000, 100000);
                $txData[] = [
                    'wallet_id' => $walletId,
                    'type' => $t === 0 ? 'credit' : (rand(0, 1) ? 'credit' : 'debit'),
                    'amount' => $amount,
                    'reference' => 'TX' . rand(100000, 999999),
                    'description' => ['شحن الرصيد', 'دفع', 'استرداد', 'غرامة'][rand(0, 3)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $this->safeInsert('wallets', $walletData);
        $this->safeInsert('wallet_transactions', $txData);
    }

    private function seedApiUsers(): void
    {
        $this->command->info('  -> إدخال مستخدمي API...');

        $students = DB::table('students')->where('state', 'admitted')->select('id')->take(100)->get();
        $studentData = [];
        foreach ($students as $idx => $s) {
            $studentData[] = [
                'username' => 'student' . ($idx + 1),
                'password' => bcrypt('password'),
                'role' => 'student',
                'student_id' => $s->id,
                'parent_id' => null,
                'faculty_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('api_users', $studentData);

        $parents = DB::table('parents')->select('id')->take(50)->get();
        $parentData = [];
        foreach ($parents as $idx => $p) {
            $parentData[] = [
                'username' => 'parent' . ($idx + 1),
                'password' => bcrypt('password'),
                'role' => 'parent',
                'student_id' => null,
                'parent_id' => $p->id,
                'faculty_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('api_users', $parentData);

        $faculties = DB::table('faculties')->select('id')->take(30)->get();
        $facultyData = [];
        foreach ($faculties as $idx => $f) {
            $facultyData[] = [
                'username' => 'faculty' . ($idx + 1),
                'password' => bcrypt('password'),
                'role' => 'faculty',
                'student_id' => null,
                'parent_id' => null,
                'faculty_id' => $f->id,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('api_users', $facultyData);

        DB::table('api_users')->insertOrIgnore([
            'username' => 'admin1',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'student_id' => null,
            'parent_id' => null,
            'faculty_id' => null,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function seedEmployees(): void
    {
        $this->command->info('  -> إدخال الموظفين...');

        $jobTitles = ['مساعد إداري', 'محاسب', 'أمين مكتبة', 'عامل نظافة', 'حارس أمن',
            'ممرض', 'سكرتير', 'دعم تقني', 'عامل قاعة الطعام', 'عامل صيانة'];

        $data = [];
        for ($i = 1; $i <= 50; $i++) {
            $gender = rand(0, 1) ? 'male' : 'female';
            $namePool = $gender === 'male' ? $this->iraqiFirstNamesMale : $this->iraqiFirstNamesFemale;
            $data[] = [
                'employee_code' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => trim($namePool[array_rand($namePool)] . ' ' . $this->lastNames[array_rand($this->lastNames)]),
                'gender' => $gender,
                'birth_date' => date('Y-m-d', rand(19700101, 20001231)),
                'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'email' => 'emp' . $i . '@edubba.test',
                'address' => $this->cities[array_rand($this->cities)],
                'job_title' => $jobTitles[array_rand($jobTitles)],
                'department' => ['الإدارة', 'المالية', 'تقنية المعلومات', 'العمليات'][rand(0, 3)],
                'join_date' => date('Y-m-d', rand(20150101, 20250901)),
                'salary' => rand(400, 1500),
                'state' => 'active',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('employees', $data);
    }

    private function seedFeedback(): void
    {
        $this->command->info('  -> إدخال الاستبيانات...');

        $form = FeedbackForm::firstOrCreate(
            ['name' => 'استبيان نهاية الفصل'],
            [
                'type' => 'student',
                'questions' => ['ما مدى رضاك عن التدريس؟', 'قيّم المرافق والتجهيزات.', 'هل لديك أي اقتراحات أو ملاحظات؟'],
                'active' => true,
            ]
        );

        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->take(500)->toArray();

        $data = [];
        foreach ($studentIds as $sid) {
            $data[] = [
                'form_id' => $form->id,
                'student_id' => $sid,
                'rating' => rand(1, 5),
                'comment' => ['ممتاز', 'جيد جداً', 'جيد', 'يحتاج تحسين', 'مقبول'][rand(0, 4)],
                'state' => 'submitted',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('feedbacks', $data);
    }

    private function seedAlumni(): void
    {
        $this->command->info('  -> إدخال الخريجين...');

        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->take(100)->toArray();
        $data = [];

        foreach ($studentIds as $idx => $sid) {
            $name = DB::table('students')->where('id', $sid)->value('name');
            $data[] = [
                'student_id' => $sid,
                'name' => $name,
                'graduation_year' => rand(2020, 2024),
                'contact' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'note' => 'تخرج بنجاح',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('alumni', $data);
    }
}
