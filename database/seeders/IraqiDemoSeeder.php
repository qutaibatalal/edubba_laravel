<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
use App\Models\Admission;
use App\Models\AdmissionRegister;
use App\Models\ApiUser;
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
use App\Models\NotificationLog;
use App\Models\FeedbackForm;
use App\Models\Feedback;
use App\Models\FacultyHr;
use App\Models\StudentCourse;

class IraqiDemoSeeder extends Seeder
{
    private const CHUNK_SIZE = 50;

    private function safeInsert(string $table, array $data): void
    {
        if (empty($data)) return;
        foreach (array_chunk($data, self::CHUNK_SIZE) as $chunk) {
            DB::table($table)->insertOrIgnore($chunk);
        }
    }

    // ── الأسماء العراقية ──────────────────────────────────────────

    private array $maleFirstNames = [
        'محمد', 'أحمد', 'علي', 'عمر', 'حسن', 'يوسف', 'خالد', 'عبدالله', 'مصطفى',
        'سلام', 'كرار', 'زيد', 'مرتضى', 'حيدر', 'ثامر', 'سعد', 'ناصر', 'جمال',
        'فيصل', 'كريم', 'رائد', 'هشام', 'أيمن', 'بلال', 'أمير', 'شريف', 'وليد', 'تامر',
        'أنور', 'ربيع', 'جواد', 'كامل', 'إبراهيم', 'لؤي', 'رافع', 'سامح',
        'ماجد', 'طارق', 'ياسر', 'عادل', 'مروان', 'أياد', 'فراس', 'عمران', 'هيثم',
        'زياد', 'باسم', 'وسيم', 'معاذ', 'دانیال', 'سيف', '严', 'قصي', 'ثابت',
    ];

    private array $femaleFirstNames = [
        'فاطمة', 'زينب', 'مريم', 'نور', 'ريم', 'دانا', 'جواهر', 'هند', 'سارة', 'لمى',
        'تسنيم', 'رنا', 'أمل', 'هبة', 'ندى', 'منار', 'رحاب', 'هلا',
        'نسرين', 'دعاء', 'خلود', 'عبير', 'سحر', 'أسماء', 'رباب',
        'ميساء', 'رим', 'ивана', 'بثينة', 'حور', 'إسراء', 'ملاك', 'جنان',
        'آلاء', 'إيمان', 'نورس', 'سناء', '.existsSync', 'ليلى', 'imed',
    ];

    private array $lastNames = [
        'المحمداوي', 'الجبوري', 'الزيدي', 'الحسني', 'العبيدي', 'الكردي', 'الشمري',
        'النعيمي', 'المطلبي', 'الهاشمي', 'الموصلي', 'البغدادي', 'العجمي', 'الهلالي',
        'الخالدي', 'العمري', 'الراوي', 'الفهداوي', 'التميمي', 'ال教练', 'الساعدي',
        'البصري', 'الكربلائي', 'النجفي', 'السلفي', 'الatori', 'الجشيري',
    ];

    private array $middleNames = [
        'أحمد', 'علي', 'حسن', 'محمد', 'عبدالله', 'سعيد', 'جاسم', 'كريم',
        'ياسر', 'عادل', 'ماجد', 'طارق', 'سامي', 'هشام', 'فيصل',
    ];

    // ── البيانات العراقية ────────────────────────────────────────

    private array $governorates = [
        'بغداد' => [
            'neighborhoods' => ['المنصور', 'الكرادة', 'الجادرية', 'العدل', 'الحرية', 'الشعب', 'الجهاد', 'البياع', 'العاميل', 'الدورة', 'الكاظمية', 'الأعظمية', 'الكرخ', 'الرصافة', 'زيونة', 'الك吕布', 'الحارثية', 'الحبيبية', 'ال师范', 'الجامعة', 'ال敫', 'ال verde', 'الم預', 'ال berkow'],
        ],
        'البصرة' => [
            'neighborhoods' => ['العشار', 'المجرير', 'الزبير', 'أبو الخصيب', 'الفاو', 'شط العرب', 'القرنة', 'البوبشيب', 'الهارثة', 'الرواجف'],
        ],
        'نينوى' => [
            'neighborhoods' => ['الموصل القديمة', 'الثورة', '17 تموز', 'النهرين', '鸿海', 'الجوسق', 'قره صفي', ' tits', 'ال裁判', 'العنك'],
        ],
        'أربيل' => [
            'neighborhoods' => ['عنكاوا', 'شقلاوة', 'عينكاوة', '灵魂', 'الرشيد', 'زاخو', 'العمادية', '�述ا', 'كونه باشا'],
        ],
        'النجف' => [
            'neighborhoods' => ['الكوفة', 'الحيرة', 'المناذرة', 'الهندية', 'الأ cpu', 'ال actually', 'ال you', 'الokay'],
        ],
        'كردستان' => [
            'neighborhoods' => ['السليمانية', 'حلبجة', 'عمادية', 'رانية', 'بنجوين', 'دوكان', ' sul'],
        ],
        'صلاح الدين' => [
            'neighborhoods' => ['تكريت', 'سامراء', 'بلد', 'الدور', 'بلد الرز', 'البوعkas', 'ال actually'],
        ],
    ];

    private array $iraqiSchoolNames = [
        'المتوسطة والابتدائية', 'ابتدائية', 'ال面上',
    ];

    // ── المدارس العراقية ─────────────────────────────────────────

    private array $iraqiSchools = [
        'مدرسة الزهراء الابتدائية', 'مدرسة أبو بكر الصديق الابتدائية',
        'مدرسة عمر بن الخطاب الابتدائية', 'مدرسة عثمان بن عفان الابتدائية',
        'مدرسة علي بن أبي طالب الابتدائية', 'مدرسة الفاروق الابتدائية',
        'مدرسة النور الابتدائية', 'مدرسة الأمل الابتدائية',
        'مدرسة السلام الابتدائية', 'مدرسة التحرير الابتدائية',
        'مدرسة الوحدة الابتدائية', 'مدرسة الشهداء الابتدائية',
        'مدرسة النجاح الابتدائية', 'مدرسة المتفوقين الابتدائية',
        'مدرسة الغد الابتدائية', 'مدرسة الرافدين الابتدائية',
        'مدرسة بابل الابتدائية', 'مدرسة الرافدين الابتدائية',
        'مدرسة iris', 'مدرسة actually',
    ];

    // ── المواد العراقية للمرحلة الابتدائية ─────────────────────

    private array $primarySubjects = [
        ['name' => 'اللغة العربية', 'code' => 'AR', 'dept' => 'اللغات', 'lang' => true],
        ['name' => 'الرياضيات', 'code' => 'MA', 'dept' => 'الرياضيات', 'lang' => false],
        ['name' => 'العلوم', 'code' => 'SC', 'dept' => 'العلوم', 'lang' => false],
        ['name' => 'التربية الإسلامية', 'code' => 'ISL', 'dept' => 'الدراسات الإسلامية', 'lang' => false],
        ['name' => 'اللغة الإنجليزية', 'code' => 'EN', 'dept' => 'اللغات', 'lang' => true],
        ['name' => 'التربية الوطنية', 'code' => 'CIV', 'dept' => 'الاجتماعيات', 'lang' => false],
        ['name' => 'التربية الفنية', 'code' => 'ART', 'dept' => 'الفنون', 'lang' => false],
        ['name' => 'التربية الرياضية', 'code' => 'PE', 'dept' => 'التربية الرياضية', 'lang' => false],
        ['name' => 'الموسيقى', 'code' => 'MUS', 'dept' => 'الفنون', 'lang' => false],
    ];

    // ── الرسوم الدراسية العراقية (بالدينار العراقي) ─────────────

    private array $iraqiFeeStructure = [
        ['name' => 'رسوم الدراسة', 'amount' => 500000, 'type' => 'recurring'],
        ['name' => 'رسوم التسجيل', 'amount' => 50000, 'type' => 'one_time'],
        ['name' => 'رسوم الكتب المدرسية', 'amount' => 80000, 'type' => 'one_time'],
        ['name' => 'رسوم الزي المدرسي', 'amount' => 40000, 'type' => 'one_time'],
        ['name' => 'رسوم النقل المدرسي', 'amount' => 150000, 'type' => 'recurring'],
        ['name' => 'رسوم الإطعام', 'amount' => 100000, 'type' => 'recurring'],
        ['name' => 'رسوم النشاطات', 'amount' => 30000, 'type' => 'one_time'],
        ['name' => 'رسوم الاختبارات', 'amount' => 25000, 'type' => 'recurring'],
    ];

    public function run(): void
    {
        $this->command->info('جاري إدخال البيانات العراقية للمرحلة الابتدائية...');
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
            $this->seedTutors();
            $this->seedTutoring();
            $this->seedLibrary();
            $this->seedTransport();
            $this->seedNotifications();
            $this->seedApiUsers();
            $this->seedFeedback();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $elapsed = round(microtime(true) - $start, 2);
        $this->command->info("✓ تم إدخال البيانات العراقية بنجاح في {$elapsed} ثانية");
        $this->printStats();
    }

    private function printStats(): void
    {
        $tables = [
            'academic_years', 'terms', 'departments', 'programs', 'subjects', 'classrooms',
            'faculties', 'batches', 'courses', 'parents', 'students',
            'student_parent', 'student_course',
            'class_sessions', 'attendance_sheets', 'attendance_lines',
            'exam_types', 'exams', 'exam_schedules', 'marksheets', 'marksheet_lines', 'exam_results',
            'fee_structures', 'fee_lines', 'invoices', 'invoice_lines', 'payments', 'receipts',
            'admissions', 'api_users',
        ];

        $this->command->newLine();
        $this->command->info('📊 إحصائيات قاعدة البيانات:');
        $this->command->newLine();
        $maxLen = 0;
        foreach ($tables as $t) $maxLen = max($maxLen, strlen($t));

        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                $padded = str_pad($table, $maxLen + 2);
                $this->command->info("  {$padded} " . number_format($count));
            } catch (\Exception $e) {}
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 1. البنية الأكاديمية
    // ═══════════════════════════════════════════════════════════════

    private function seedAcademicStructure(): void
    {
        $this->command->info('  -> إدخال السنة الدراسية والفصول...');

        $year = AcademicYear::updateOrCreate(
            ['name' => '2025-2026'],
            ['date_start' => '2025-09-01', 'date_stop' => '2026-07-31', 'current' => true, 'active' => true]
        );

        Term::updateOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'الفصل الأول'],
            ['date_start' => '2025-09-01', 'date_stop' => '2026-01-15', 'active' => true]
        );
        Term::updateOrCreate(
            ['academic_year_id' => $year->id, 'name' => 'الفصل الثاني'],
            ['date_start' => '2026-01-16', 'date_stop' => '2026-07-31', 'active' => true]
        );

        // أيام الأسبوع العراقية
        WeekDay::query()->delete();
        $days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'];
        foreach ($days as $i => $d) {
            WeekDay::create(['name' => $d, 'sequence' => $i, 'active' => true]);
        }

        // الفترات الدراسية (نظام الصفوف伊拉克)
        Timing::query()->delete();
        $timings = [
            ['name' => 'الفترة الأولى', 'start_time' => '08:00:00', 'end_time' => '08:40:00', 'sequence' => 0],
            ['name' => 'الفترة الثانية', 'start_time' => '08:50:00', 'end_time' => '09:30:00', 'sequence' => 1],
            ['name' => 'الفترة الثالثة', 'start_time' => '09:40:00', 'end_time' => '10:20:00', 'sequence' => 2],
            ['name' => 'الفترة الرابعة', 'start_time' => '10:30:00', 'end_time' => '11:10:00', 'sequence' => 3],
            ['name' => 'الفترة الخامسة', 'start_time' => '11:20:00', 'end_time' => '12:00:00', 'sequence' => 4],
            ['name' => 'الفترة السادسة', 'start_time' => '12:10:00', 'end_time' => '12:50:00', 'sequence' => 5],
        ];
        foreach ($timings as $t) Timing::create($t);
    }

    // ═══════════════════════════════════════════════════════════════
    // 2. الأقسام والبرامج
    // ═══════════════════════════════════════════════════════════════

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
            ['name' => 'الدراسات الإسلامية', 'code' => 'REL'],
        ];

        foreach ($depts as $d) {
            Department::updateOrCreate(['name' => $d['name']], array_merge($d, ['active' => true]));
        }

        // المرحلة الابتدائية فقط (6 سنوات)
        Program::updateOrCreate(
            ['name' => 'المرحلة الابتدائية'],
            [
                'code' => 'PRIM',
                'department_id' => Department::where('name', 'اللغات')->first()->id,
                'duration_years' => 6,
                'description' => 'المرحلة الابتدائية - الصف الأول إلى السادس',
                'active' => true,
            ]
        );
    }

    // ═══════════════════════════════════════════════════════════════
    // 3. المواد الدراسية伊拉克
    // ═══════════════════════════════════════════════════════════════

    private function seedSubjects(): void
    {
        $this->command->info('  -> إدخال المواد الدراسية العراقية...');

        foreach ($this->primarySubjects as $s) {
            $dept = Department::where('name', $s['dept'])->first();
            Subject::updateOrCreate(['code' => $s['code']], [
                'name' => $s['name'],
                'department_id' => $dept?->id,
                'is_language' => $s['lang'],
                'active' => true,
            ]);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 4. الفصول الدراسية
    // ═══════════════════════════════════════════════════════════════

    private function seedClassrooms(): void
    {
        $this->command->info('  -> إدخال الفصول الدراسية...');

        $buildings = ['المبنى الرئيسي', 'جناح المرحلة الابتدائية', 'المبنى الجديد'];
        $data = [];

        foreach ($buildings as $building) {
            for ($floor = 1; $floor <= 2; $floor++) {
                for ($room = 1; $room <= 6; $room++) {
                    $data[] = [
                        'name' => "فصل {$building[0]}{$floor}{$room}",
                        'building' => $building,
                        'floor' => $floor,
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

    // ═══════════════════════════════════════════════════════════════
    // 5. أنواع الاختبارات
    // ═══════════════════════════════════════════════════════════════

    private function seedExamTypes(): void
    {
        $this->command->info('  -> إدخال أنواع الاختبارات...');

        $types = [
            ['name' => 'اختبار قصير', 'weight' => 10, 'active' => true],
            ['name' => 'واجب منزلي', 'weight' => 10, 'active' => true],
            ['name' => 'اختبار شفهي', 'weight' => 10, 'active' => true],
            ['name' => 'اختبار منتصف الفصل', 'weight' => 30, 'active' => true],
            ['name' => 'اختبار نهائي', 'weight' => 40, 'active' => true],
        ];

        foreach ($types as $t) {
            ExamType::updateOrCreate(['name' => $t['name']], $t);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 6. أعضاء الهيئة التدريسيةIraqi
    // ═══════════════════════════════════════════════════════════════

    private function seedFaculty(): void
    {
        $this->command->info('  -> إدخال أعضاء الهيئة التدريسية...');

        $depts = Department::pluck('id')->toArray();
        $qualifications = ['بكالوريوس', 'ماجستير', 'دبلوم عالي', 'ماجستير تربية', 'بكالوريوس تربية'];
        $specializations = [
            'اللغة العربية', 'الرياضيات', 'العلوم', 'التربية الإسلامية',
            'اللغة الإنجليزية', 'التربية الفنية', 'التربية الرياضية',
        ];

        $facultyData = [];
        $hrData = [];

        $facultyNames = [
            // معلمات ومعلمون عراقيون بأسماء واقعية
            ['name' => 'فاطمة', 'middle' => 'علي', 'last' => 'المحمداوي', 'gender' => 'female'],
            ['name' => 'أحمد', 'middle' => 'حسن', 'last' => 'الجبوري', 'gender' => 'male'],
            ['name' => 'مريم', 'middle' => 'محمد', 'last' => 'الزيدي', 'gender' => 'female'],
            ['name' => 'عمر', 'middle' => 'عبدالله', 'last' => 'الحسني', 'gender' => 'male'],
            ['name' => 'زينب', 'middle' => 'أحمد', 'last' => 'العبيدي', 'gender' => 'female'],
            ['name' => 'خالد', 'middle' => 'جاسم', 'last' => 'الكردي', 'gender' => 'male'],
            ['name' => 'سارة', 'middle' => 'كريم', 'last' => 'الشمري', 'gender' => 'female'],
            ['name' => 'مصطفى', 'middle' => 'ياسر', 'last' => 'النعيمي', 'gender' => 'male'],
            ['name' => 'نور', 'middle' => 'عادل', 'last' => 'المطلبي', 'gender' => 'female'],
            ['name' => 'ياسر', 'middle' => 'طارق', 'last' => 'الهاشمي', 'gender' => 'male'],
            ['name' => 'هند', 'middle' => 'ماجد', 'last' => 'الموصلي', 'gender' => 'female'],
            ['name' => ' BDS', 'middle' => 'سامي', 'last' => 'البغدادي', 'gender' => 'male'],
            ['name' => 'رنا', 'middle' => 'هشام', 'last' => 'العجمي', 'gender' => 'female'],
            ['name' => ' BDS', 'middle' => ' فيصل', 'last' => 'الهلالي', 'gender' => 'male'],
            ['name' => 'لمى', 'middle' => ' BDS', 'last' => 'الخالدي', 'gender' => 'female'],
            ['name' => ' BDS', 'middle' => 'أنور', 'last' => 'العمري', 'gender' => 'male'],
            ['name' => 'ندى', 'middle' => ' BDS', 'last' => 'الراوي', 'gender' => 'female'],
            ['name' => ' BDS', 'middle' => ' BDS', 'last' => 'الفهداوي', 'gender' => 'male'],
            ['name' => 'تسنيم', 'middle' => ' BDS', 'last' => 'التميمي', 'gender' => 'female'],
            ['name' => ' BDS', 'middle' => ' BDS', 'last' => 'الساعدي', 'gender' => 'male'],
            ['name' => 'دانية', 'middle' => ' BDS', 'last' => 'البصري', 'gender' => 'female'],
            ['name' => ' BDS', 'middle' => ' BDS', 'last' => 'الكربلائي', 'gender' => 'male'],
            ['name' => 'أمجد', 'middle' => ' BDS', 'last' => 'النجفي', 'gender' => 'male'],
            ['name' => ' BDS', 'middle' => ' BDS', 'last' => 'الجشيري', 'gender' => 'male'],
            ['name' => 'حور', 'middle' => ' BDS', 'last' => 'المحمداوي', 'gender' => 'female'],
        ];

        foreach ($facultyNames as $i => $f) {
            $code = 'FAC' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $nationalId = '9' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);

            $facultyData[] = [
                'faculty_code' => $code,
                'name' => trim($f['name']),
                'middle_name' => trim($f['middle']),
                'last_name' => trim($f['last']),
                'birth_date' => date('Y-m-d', rand(19750101, 19951231)),
                'gender' => $f['gender'],
                'marital_status' => rand(0, 1) ? 'married' : 'single',
                'national_id' => $nationalId,
                'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'mobile' => '078' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'email' => 'faculty' . ($i + 1) . '@edubba.test',
                'address' => 'بغداد - ' . ['المنصور', 'الكرادة', 'الجادرية', 'الشعب', 'زيونة'][rand(0, 4)] . ' شارع ' . rand(1, 50),
                'qualification' => $qualifications[array_rand($qualifications)],
                'specialization' => $specializations[array_rand($specializations)],
                'join_date' => date('Y-m-d', rand(20150101, 20250901)),
                'department_id' => $depts[array_rand($depts)],
                'state' => 'joined',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $hrData[] = [
                'faculty_id' => null, // will be set after insert
                'employee_type' => ['دوام كامل', 'دوام جزئي'][rand(0, 1)],
                'contract_start' => date('Y-m-d', rand(20200101, 20250901)),
                'contract_end' => date('Y-m-d', rand(20260101, 20281231)),
                'salary' => rand(800, 2500),
                'bank_name' => ['الرفدين', 'الراشد', 'بي bank', 'البنك المركزي العراقي'][rand(0, 3)],
                'bank_account' => 'IQ' . rand(10000000, 99999999),
                'tin' => str_pad(rand(100000, 999999), 10, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('faculties', $facultyData);

        // ربط HR بالهيئة
        $facultyIds = DB::table('faculties')->pluck('id')->toArray();
        foreach ($hrData as $idx => &$hr) {
            if (isset($facultyIds[$idx])) {
                $hr['faculty_id'] = $facultyIds[$idx];
            }
        }
        $this->safeInsert('faculty_hr', $hrData);
    }

    // ═══════════════════════════════════════════════════════════════
    // 7. الصفوفIraqi الابتدائية (1-6)
    // ═══════════════════════════════════════════════════════════════

    private function seedBatches(): void
    {
        $this->command->info('  -> إدخال صفوف المرحلة الابتدائية (1-6)...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $program = Program::where('name', 'المرحلة الابتدائية')->first();
        $facultyIds = DB::table('faculties')->pluck('id')->toArray();

        $grades = [
            'الصف الأول الابتدائي',
            'الصف الثاني الابتدائي',
            'الصف الثالث الابتدائي',
            'الصف الرابع الابتدائي',
            'الصف الخامس الابتدائي',
            'الصف السادس الابتدائي',
        ];

        $sections = ['أ', 'ب'];
        $data = [];

        foreach ($grades as $grade) {
            foreach ($sections as $section) {
                $data[] = [
                    'name' => "{$grade} {$section}",
                    'program_id' => $program->id,
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

    // ═══════════════════════════════════════════════════════════════
    // 8. المقررات الدراسية
    // ═══════════════════════════════════════════════════════════════

    private function seedCourses(): void
    {
        $this->command->info('  -> إدخال المقررات الدراسية...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get();
        $subjects = Subject::all();
        $facultyIds = DB::table('faculties')->pluck('id')->toArray();

        $data = [];
        foreach ($batches as $batch) {
            foreach ($subjects as $sub) {
                $shortName = str_replace('المرحلة الابتدائية', '', $batch->name);
                $code = $sub->code . $batch->id;

                $data[] = [
                    'name' => "{$sub->name} {$batch->name}",
                    'code' => $code,
                    'subject_id' => $sub->id,
                    'program_id' => $batch->program_id,
                    'batch_id' => $batch->id,
                    'academic_year_id' => $year->id,
                    'faculty_id' => $facultyIds[array_rand($facultyIds)],
                    'credit_hours' => rand(1, 3),
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $this->safeInsert('courses', $data);
    }

    // ═══════════════════════════════════════════════════════════════
    // 9. أولياء الأمورIraqi
    // ═══════════════════════════════════════════════════════════════

    private function seedParents(): void
    {
        $this->command->info('  -> إدخال أولياء الأمور...');

        $data = [];
        $parentNames = [
            'محمد الجبوري', 'أحمد المحمداوي', 'علي الزيدي', 'عمر الحسني',
            'حسن العبيدي', 'يوسف الكردي', 'خالد الشمري', 'عبدالله النعيمي',
            'مصطفى المطلبي', 'سلام الهاشمي', 'كرار الموصلي', 'زيد البغدادي',
            'مرتضى العجمي', 'حيدر الهلالي', 'ثامر الخالدي', 'سعد العمري',
            'ناصر الراوي', 'جمال الفهداوي', 'فيصل التميمي', 'كريم الساعدي',
            'رائد البصري', 'هشام الكربلائي', 'أيمن النجفي', 'بلال الجشيري',
            'أمير المحمداوي', 'شريف الجبوري', 'وليد الزيدي', 'تامر الحسني',
            'أنور العبيدي', 'ربيع الكردي', 'جواد الشمري', 'كامل النعيمي',
            'إبراهيم المطلبي', 'لؤي الهاشمي', 'رافع الموصلي', 'سامح البغدادي',
            'ماجد العجمي', 'طارق الهلالي', 'ياسر الخالدي', 'عادل العمري',
            'مروان الراوي', 'أياد الفهداوي', 'فراس التميمي', 'عمران الساعدي',
            'هيثم البصري', 'زياد الكربلائي', 'باسم النجفي', 'وسيم الجشيري',
            'معاذ المحمداوي', 'قصي الجبوري',
        ];

        foreach ($parentNames as $i => $name) {
            $nationalId = '9' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);

            $data[] = [
                'name' => $name,
                'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'mobile' => '078' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'email' => 'parent' . ($i + 1) . '@edubba.test',
                'national_id' => $nationalId,
                'address' => 'بغداد - ' . ['المنصور', 'الكرادة', 'الجادرية', 'العدل', 'الحرية', 'الشعب', 'الجهاد', 'البياع', 'زيونة'][rand(0, 8)],
                'occupation' => ['مهندس', 'طبيب', 'مدرس', 'سائق', 'تاجر', 'ضابط', 'موظف', 'محاسب', 'محامي', 'صيدلي'][rand(0, 9)],
                'relation' => 'father',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('parents', $data);
    }

    // ═══════════════════════════════════════════════════════════════
    // 10. الطلاب Iraqiproducts
    // ═══════════════════════════════════════════════════════════════

    private function seedStudents(): void
    {
        $this->command->info('  -> إدخال طلاب المرحلة الابتدائية...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get()->keyBy('id');
        $parentIds = DB::table('parents')->pluck('id')->toArray();
        $deptIds = Department::pluck('id')->toArray();

        // أسماء طلاب عراقيين بأسماء واقعية
        $studentNames = [
            // ذكور
            ['name' => 'محمد', 'middle' => 'أحمد', 'last' => 'المحمداوي', 'gender' => 'male', 'birth' => '2015-03-15'],
            ['name' => 'أحمد', 'middle' => ' علي', 'last' => 'الجبوري', 'gender' => 'male', 'birth' => '2014-07-22'],
            ['name' => 'عمر', 'middle' => 'حسن', 'last' => 'الزيدي', 'gender' => 'male', 'birth' => '2015-01-10'],
            ['name' => ' علي', 'middle' => 'محمد', 'last' => 'الحسني', 'gender' => 'male', 'birth' => '2014-09-05'],
            ['name' => 'يوسف', 'middle' => 'عمر', 'last' => 'العبيدي', 'gender' => 'male', 'birth' => '2015-05-18'],
            ['name' => 'خالد', 'middle' => 'أحمد', 'last' => 'الكردي', 'gender' => 'male', 'birth' => '2014-11-30'],
            ['name' => 'عبدالله', 'middle' => 'حسن', 'last' => 'الشمري', 'gender' => 'male', 'birth' => '2015-02-14'],
            ['name' => 'مصطفى', 'middle' => 'علي', 'last' => 'النعيمي', 'gender' => 'male', 'birth' => '2014-06-25'],
            ['name' => 'سلام', 'middle' => 'محمد', 'last' => 'المطلبي', 'gender' => 'male', 'birth' => '2015-08-12'],
            ['name' => 'كرار', 'middle' => 'عمر', 'last' => 'الهاشمي', 'gender' => 'male', 'birth' => '2014-04-20'],
            ['name' => 'زيد', 'middle' => 'أحمد', 'last' => 'الموصلي', 'gender' => 'male', 'birth' => '2015-10-08'],
            ['name' => 'مرتضى', 'middle' => 'حسن', 'last' => 'البغدادي', 'gender' => 'male', 'birth' => '2014-12-03'],
            ['name' => 'حيدر', 'middle' => 'علي', 'last' => 'العجمي', 'gender' => 'male', 'birth' => '2015-06-17'],
            ['name' => 'ثامر', 'middle' => 'محمد', 'last' => 'الهلالي', 'gender' => 'male', 'birth' => '2014-08-28'],
            ['name' => 'سعد', 'middle' => 'عمر', 'last' => 'الخالدي', 'gender' => 'male', 'birth' => '2015-04-05'],
            ['name' => 'ناصر', 'middle' => 'أحمد', 'last' => 'العمري', 'gender' => 'male', 'birth' => '2014-02-19'],
            ['name' => 'جمال', 'middle' => 'حسن', 'last' => 'الراوي', 'gender' => 'male', 'birth' => '2015-09-14'],
            ['name' => 'فيصل', 'middle' => 'علي', 'last' => 'الفهداوي', 'gender' => 'male', 'birth' => '2014-07-07'],
            ['name' => 'كريم', 'middle' => 'محمد', 'last' => 'التميمي', 'gender' => 'male', 'birth' => '2015-11-22'],
            ['name' => 'رائد', 'middle' => 'عمر', 'last' => 'الساعدي', 'gender' => 'male', 'birth' => '2014-05-30'],
            ['name' => 'هشام', 'middle' => 'أحمد', 'last' => 'البصري', 'gender' => 'male', 'birth' => '2015-03-08'],
            ['name' => 'أيمن', 'middle' => 'حسن', 'last' => 'الكربلائي', 'gender' => 'male', 'birth' => '2014-09-25'],
            ['name' => 'بلال', 'middle' => ' علي', 'last' => 'النجفي', 'gender' => 'male', 'birth' => '2015-07-16'],
            ['name' => 'أمير', 'middle' => 'محمد', 'last' => 'الجشيري', 'gender' => 'male', 'birth' => '2014-11-11'],
            ['name' => 'شريف', 'middle' => 'عمر', 'last' => 'المحمداوي', 'gender' => 'male', 'birth' => '2015-01-28'],
            ['name' => 'وليد', 'middle' => 'أحمد', 'last' => 'الجبوري', 'gender' => 'male', 'birth' => '2014-04-13'],
            ['name' => 'تامر', 'middle' => 'حسن', 'last' => 'الزيدي', 'gender' => 'male', 'birth' => '2015-08-05'],
            ['name' => 'أنور', 'middle' => ' علي', 'last' => 'الحسني', 'gender' => 'male', 'birth' => '2014-06-22'],
            ['name' => 'ربيع', 'middle' => 'محمد', 'last' => 'العبيدي', 'gender' => 'male', 'birth' => '2015-12-19'],
            ['name' => 'جواد', 'middle' => 'عمر', 'last' => 'الكردي', 'gender' => 'male', 'birth' => '2014-03-04'],
            ['name' => 'كامل', 'middle' => 'أحمد', 'last' => 'الشمري', 'gender' => 'male', 'birth' => '2015-05-27'],
            ['name' => 'إبراهيم', 'middle' => 'حسن', 'last' => 'النعيمي', 'gender' => 'male', 'birth' => '2014-10-15'],
            ['name' => 'لؤي', 'middle' => ' علي', 'last' => 'المطلبي', 'gender' => 'male', 'birth' => '2015-02-09'],
            ['name' => 'رافع', 'middle' => 'محمد', 'last' => 'الهاشمي', 'gender' => 'male', 'birth' => '2014-08-31'],
            ['name' => 'سامح', 'middle' => 'عمر', 'last' => 'الموصلي', 'gender' => 'male', 'birth' => '2015-04-20'],
            ['name' => 'ماجد', 'middle' => 'أحمد', 'last' => 'البغدادي', 'gender' => 'male', 'birth' => '2014-01-12'],
            ['name' => 'طارق', 'middle' => 'حسن', 'last' => 'العجمي', 'gender' => 'male', 'birth' => '2015-09-03'],
            ['name' => 'ياسر', 'middle' => ' علي', 'last' => 'الهلالي', 'gender' => 'male', 'birth' => '2014-07-18'],
            ['name' => 'عادل', 'middle' => 'محمد', 'last' => 'الخالدي', 'gender' => 'male', 'birth' => '2015-11-25'],
            ['name' => 'مروان', 'middle' => 'عمر', 'last' => 'العمري', 'gender' => 'male', 'birth' => '2014-05-08'],
            // إناث
            ['name' => 'فاطمة', 'middle' => 'أحمد', 'last' => 'المحمداوي', 'gender' => 'female', 'birth' => '2015-03-20'],
            ['name' => 'زينب', 'middle' => ' علي', 'last' => 'الجبوري', 'gender' => 'female', 'birth' => '2014-07-15'],
            ['name' => 'مريم', 'middle' => 'محمد', 'last' => 'الزيدي', 'gender' => 'female', 'birth' => '2015-01-25'],
            ['name' => 'نور', 'middle' => 'عمر', 'last' => 'الحسني', 'gender' => 'female', 'birth' => '2014-09-12'],
            ['name' => 'ريم', 'middle' => 'أحمد', 'last' => 'العبيدي', 'gender' => 'female', 'birth' => '2015-05-08'],
            ['name' => 'دانا', 'middle' => 'حسن', 'last' => 'الكردي', 'gender' => 'female', 'birth' => '2014-11-17'],
            ['name' => 'جواهر', 'middle' => ' علي', 'last' => 'الشمري', 'gender' => 'female', 'birth' => '2015-02-28'],
            ['name' => 'هند', 'middle' => 'محمد', 'last' => 'النعيمي', 'gender' => 'female', 'birth' => '2014-06-10'],
            ['name' => 'سارة', 'middle' => 'عمر', 'last' => 'المطلبي', 'gender' => 'female', 'birth' => '2015-08-22'],
            ['name' => 'لمى', 'middle' => 'أحمد', 'last' => 'الهاشمي', 'gender' => 'female', 'birth' => '2014-04-05'],
            ['name' => 'تسنيم', 'middle' => 'حسن', 'last' => 'الموصلي', 'gender' => 'female', 'birth' => '2015-10-18'],
            ['name' => 'رنا', 'middle' => ' علي', 'last' => 'البغدادي', 'gender' => 'female', 'birth' => '2014-12-30'],
            ['name' => 'أمل', 'middle' => 'محمد', 'last' => 'العجمي', 'gender' => 'female', 'birth' => '2015-06-14'],
            ['name' => 'هبة', 'middle' => 'عمر', 'last' => 'الهلالي', 'gender' => 'female', 'birth' => '2014-08-09'],
            ['name' => 'ندى', 'middle' => 'أحمد', 'last' => 'الخالدي', 'gender' => 'female', 'birth' => '2015-04-25'],
            ['name' => 'منار', 'middle' => 'حسن', 'last' => 'العمري', 'gender' => 'female', 'birth' => '2014-02-14'],
            ['name' => 'رحاب', 'middle' => ' علي', 'last' => 'الراوي', 'gender' => 'female', 'birth' => '2015-09-07'],
            ['name' => 'هلا', 'middle' => 'محمد', 'last' => 'الفهداوي', 'gender' => 'female', 'birth' => '2014-07-28'],
            ['name' => 'نسرين', 'middle' => 'عمر', 'last' => 'التميمي', 'gender' => 'female', 'birth' => '2015-11-11'],
            ['name' => 'دعاء', 'middle' => 'أحمد', 'last' => 'الساعدي', 'gender' => 'female', 'birth' => '2014-05-22'],
            ['name' => 'خلود', 'middle' => 'حسن', 'last' => 'البصري', 'gender' => 'female', 'birth' => '2015-03-05'],
            ['name' => 'عبير', 'middle' => ' علي', 'last' => 'الكربلائي', 'gender' => 'female', 'birth' => '2014-09-19'],
            ['name' => 'سحر', 'middle' => 'محمد', 'last' => 'النجفي', 'gender' => 'female', 'birth' => '2015-07-30'],
            ['name' => 'أسماء', 'middle' => 'عمر', 'last' => 'الجشيري', 'gender' => 'female', 'birth' => '2014-01-08'],
            ['name' => 'رباب', 'middle' => 'أحمد', 'last' => 'المحمداوي', 'gender' => 'female', 'birth' => '2015-12-15'],
            ['name' => 'ميساء', 'middle' => 'حسن', 'last' => 'الجبوري', 'gender' => 'female', 'birth' => '2014-04-27'],
            ['name' => 'بثينة', 'middle' => ' علي', 'last' => 'الزيدي', 'gender' => 'female', 'birth' => '2015-08-03'],
            ['name' => 'حور', 'middle' => 'محمد', 'last' => 'الحسني', 'gender' => 'female', 'birth' => '2014-06-21'],
            ['name' => 'إسراء', 'middle' => 'عمر', 'last' => 'العبيدي', 'gender' => 'female', 'birth' => '2015-02-12'],
            ['name' => 'ملاك', 'middle' => 'أحمد', 'last' => 'الكردي', 'gender' => 'female', 'birth' => '2014-10-05'],
            ['name' => 'جنان', 'middle' => 'حسن', 'last' => 'الشمري', 'gender' => 'female', 'birth' => '2015-04-18'],
            ['name' => 'آلاء', 'middle' => ' علي', 'last' => 'النعيمي', 'gender' => 'female', 'birth' => '2014-12-24'],
            ['name' => 'إيمان', 'middle' => 'محمد', 'last' => 'المطلبي', 'gender' => 'female', 'birth' => '2015-09-29'],
            ['name' => 'نورس', 'middle' => 'عمر', 'last' => 'الهاشمي', 'gender' => 'female', 'birth' => '2014-03-16'],
            ['name' => 'سناء', 'middle' => 'أحمد', 'last' => 'الموصلي', 'gender' => 'female', 'birth' => '2015-07-08'],
            ['name' => 'ليلى', 'middle' => 'حسن', 'last' => 'البغدادي', 'gender' => 'female', 'birth' => '2014-11-03'],
        ];

        $studentData = [];
        $studentParentData = [];
        $studentCounter = 0;

        // توزيع الطلاب على الصفوف
        $batchArray = $batches->toArray();
        $studentsPerBatch = (int) ceil(count($studentNames) / count($batches));

        foreach ($batches as $batch) {
            $batchStudents = array_slice($studentNames, $studentCounter, $studentsPerBatch);

            foreach ($batchStudents as $studentInfo) {
                $studentCounter++;
                $nationalId = '9' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);

                $studentData[] = [
                    'student_code' => 'STU' . str_pad($studentCounter, 5, '0', STR_PAD_LEFT),
                    'name' => trim($studentInfo['name']),
                    'middle_name' => trim($studentInfo['middle']),
                    'last_name' => trim($studentInfo['last']),
                    'gender' => $studentInfo['gender'],
                    'birth_date' => $studentInfo['birth'],
                    'birth_place' => 'بغداد',
                    'national_id' => $nationalId,
                    'residence' => 'بغداد',
                    'marital_status' => 'single',
                    'blood_group' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'][rand(0, 7)],
                    'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'mobile' => '078' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'email' => 'student' . $studentCounter . '@edubba.test',
                    'address' => 'بغداد - ' . ['المنصور', 'الكرادة', 'الجادرية', 'العدل', 'الحرية', 'الشعب', 'زيونة'][rand(0, 6)] . ' شارع ' . rand(1, 30),
                    'city' => 'بغداد',
                    'province' => 'بغداد',
                    'country' => 'العراق',
                    'zip' => '10001',
                    'batch_id' => $batch->id,
                    'program_id' => $batch->program_id,
                    'academic_year_id' => $year->id,
                    'parent_id' => null, // will be set after
                    'department_id' => $deptIds[array_rand($deptIds)],
                    'state' => 'admitted',
                    'admission_date' => '2025-09-01',
                    'roll_no' => 'RN' . str_pad($studentCounter, 5, '0', STR_PAD_LEFT),
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $studentParentData[] = [
                    'student_id' => null, // will be set after insert
                    'parent_id' => $parentIds[array_rand($parentIds)],
                    'relation' => 'father',
                    'is_main' => true,
                    'guardian' => true,
                    'emergency_contact' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                ];
            }
        }

        $this->safeInsert('students', $studentData);

        // ربط الطلاب بأولياء الأمور
        $allStudents = DB::table('students')->pluck('id')->toArray();
        foreach ($studentParentData as $idx => &$sp) {
            if (isset($allStudents[$idx])) {
                $sp['student_id'] = $allStudents[$idx];
                // also set parent_id on student
                DB::table('students')->where('id', $allStudents[$idx])->update(['parent_id' => $sp['parent_id']]);
            }
        }
        $this->safeInsert('student_parent', $studentParentData);
    }

    // ═══════════════════════════════════════════════════════════════
    // 11. تسجيل الطلاب في المقررات
    // ═══════════════════════════════════════════════════════════════

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
                        'total_fees' => rand(50000, 200000),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        foreach (array_chunk($data, self::CHUNK_SIZE) as $chunk) {
            DB::table('student_course')->insertOrIgnore($chunk);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 12. الجداول الدراسية
    // ═══════════════════════════════════════════════════════════════

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

            foreach ($weekDays as $dayId) {
                $numPeriods = rand(4, 6);
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

        foreach (array_chunk($lineData, self::CHUNK_SIZE) as $chunk) {
            DB::table('time_table_lines')->insert($chunk);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 13. الحصص الدراسية
    // ═══════════════════════════════════════════════════════════════

    private function seedClassSessions(): void
    {
        $this->command->info('  -> إدخال الحصص الدراسية...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get();
        $facultyIds = DB::table('faculties')->pluck('id')->toArray();
        $classrooms = DB::table('classrooms')->pluck('id')->toArray();

        $data = [];
        $sessionId = 1;

        $startDate = new \Carbon\Carbon('2025-09-01');
        $endDate = new \Carbon\Carbon('2026-01-15');
        $daysBetween = $startDate->diffInDays($endDate);

        foreach ($batches as $batch) {
            $batchCourses = DB::table('courses')->where('batch_id', $batch->id)->get();
            if ($batchCourses->isEmpty()) continue;

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

        foreach (array_chunk($data, self::CHUNK_SIZE) as $chunk) {
            DB::table('class_sessions')->insert($chunk);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 14. سجلات الحضور
    // ═══════════════════════════════════════════════════════════════

    private function seedAttendance(): void
    {
        $this->command->info('  -> إدخال سجلات الحضور...');

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
                if ($rand <= 75) $status = 'present';
                elseif ($rand <= 88) $status = 'absent';
                elseif ($rand <= 96) $status = 'late';
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

        foreach (array_chunk($sheetData, self::CHUNK_SIZE) as $chunk) {
            DB::table('attendance_sheets')->insert($chunk);
        }
        foreach (array_chunk($lineData, self::CHUNK_SIZE) as $chunk) {
            DB::table('attendance_lines')->insert($chunk);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 15. الاختبارات
    // ═══════════════════════════════════════════════════════════════

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

        foreach (array_chunk($scheduleData, self::CHUNK_SIZE) as $chunk) {
            DB::table('exam_schedules')->insert($chunk);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 16. كشوف الدرجات
    // ═══════════════════════════════════════════════════════════════

    private function seedMarksheets(): void
    {
        $this->command->info('  -> إدخال كشوف الدرجات...');

        $exams = DB::table('exams')->where('state', 'done')->get();

        $gradeMap = [
            [90, 100, 'ممتاز'], [80, 89.99, 'جيد جداً'], [70, 79.99, 'جيد'],
            [60, 69.99, 'مقبول'], [50, 59.99, 'مقبول'], [0, 49.99, 'راسب'],
        ];

        $marksheetData = [];
        $lineData = [];
        $msId = 1;

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
                    $marks = rand(30, 100);
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

        foreach (array_chunk($marksheetData, self::CHUNK_SIZE) as $chunk) {
            DB::table('marksheets')->insert($chunk);
        }
        foreach (array_chunk($lineData, self::CHUNK_SIZE) as $chunk) {
            DB::table('marksheet_lines')->insert($chunk);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 17. نتائج الاختبارات
    // ═══════════════════════════════════════════════════════════════

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

        foreach (array_chunk($data, self::CHUNK_SIZE) as $chunk) {
            DB::table('exam_results')->insert($chunk);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 18. هياكل الرسوم العراقية
    // ═══════════════════════════════════════════════════════════════

    private function seedFeeStructures(): void
    {
        $this->command->info('  -> إدخال هياكل الرسوم العراقية...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get();

        foreach ($batches as $batch) {
            $fs = FeeStructure::create([
                'name' => "رسوم {$batch->name}",
                'program_id' => $batch->program_id,
                'batch_id' => $batch->id,
                'academic_year_id' => $year->id,
                'active' => true,
            ]);

            foreach ($this->iraqiFeeStructure as $seq => $fl) {
                FeeLine::create([
                    'fee_structure_id' => $fs->id,
                    'name' => $fl['name'],
                    'amount' => $fl['amount'] + rand(-10000, 20000),
                    'type' => $fl['type'],
                    'sequence' => $seq,
                ]);
            }
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 19. الفواتير
    // ═══════════════════════════════════════════════════════════════

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
            $feeLines = [
                ['رسوم الدراسة', rand(450000, 550000)],
                ['رسوم التسجيل', rand(40000, 60000)],
                ['رسوم الكتب', rand(70000, 90000)],
                ['رسوم الاختبارات', rand(20000, 30000)],
            ];

            $total = 0;
            foreach ($feeLines as $fl) {
                $total += $fl[1];
                $lineData[] = [
                    'invoice_id' => $invNum,
                    'description' => $fl[0],
                    'qty' => 1,
                    'unit_price' => $fl[1],
                    'amount' => $fl[1],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $states = ['open', 'open', 'open', 'paid', 'paid', 'paid'];
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

        foreach (array_chunk($invoiceData, self::CHUNK_SIZE) as $chunk) {
            DB::table('invoices')->insert($chunk);
        }
        foreach (array_chunk($lineData, self::CHUNK_SIZE) as $chunk) {
            DB::table('invoice_lines')->insert($chunk);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 20. المدفوعات
    // ═══════════════════════════════════════════════════════════════

    private function seedPayments(): void
    {
        $this->command->info('  -> إدخال المدفوعات...');

        $paidInvoices = DB::table('invoices')->where('state', 'paid')->get();
        $methods = ['نقدي', 'بطاقة', 'تحويل بنكي', 'زين كاش', 'كي كارد'];

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
                'gateway' => in_array($method, ['زين كاش', 'كي كارد']) ? $method : null,
                'transaction_id' => !in_array($method, ['نقدي']) ? 'TXN' . rand(100000, 999999) : null,
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

        foreach (array_chunk($payData, self::CHUNK_SIZE) as $chunk) {
            DB::table('payments')->insert($chunk);
        }
        foreach (array_chunk($receiptData, self::CHUNK_SIZE) as $chunk) {
            DB::table('receipts')->insert($chunk);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 21. طلبات القبول
    // ═══════════════════════════════════════════════════════════════

    private function seedAdmissions(): void
    {
        $this->command->info('  -> إدخال طلبات القبول...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $batches = DB::table('batches')->where('academic_year_id', $year->id)->get();

        $reg = AdmissionRegister::firstOrCreate(
            ['name' => 'تسجيل دخول 2025-2026'],
            [
                'academic_year_id' => $year->id,
                'batch_id' => $batches->first()->id ?? null,
                'start_date' => '2025-06-01',
                'end_date' => '2025-08-31',
                'active' => true,
            ]
        );

        $states = ['draft', 'submit', 'approve', 'admitted', 'reject'];
        $iraqiSchools = [
            'مدرسة الزهراء الابتدائية', 'مدرسة أبو بكر الصديق الابتدائية',
            'مدرسة عمر بن الخطاب الابتدائية', 'مدرسة عثمان بن عفان الابتدائية',
            'مدرسة علي بن أبي طالب الابتدائية', 'مدرسة الفاروق الابتدائية',
            'مدرسة النور الابتدائية', 'مدرسة الأمل الابتدائية',
            'مدرسة السلام الابتدائية', 'مدرسة التحرير الابتدائية',
        ];

        $admissionNames = [
            'محمد', 'أحمد', ' علي', 'عمر', 'حسن', 'يوسف', 'خالد', 'عبدالله',
            'فاطمة', 'زينب', 'مريم', 'نور', 'ريم', 'دانا', 'جواهر', 'هند',
            'سارة', 'لمى', 'تسنيم', 'رنا', 'أمل', 'هبة', 'ندى', 'منار',
            'كرار', 'زيد', 'مرتضى', 'حيدر', 'ثامر', 'سعد', 'ناصر', 'جمال',
        ];

        $data = [];
        for ($i = 1; $i <= 100; $i++) {
            $batch = $batches->random();
            $state = $states[array_rand($states)];
            $gender = rand(0, 1) ? 'male' : 'female';
            $name = $admissionNames[array_rand($admissionNames)];

            $data[] = [
                'application_no' => 'ADM' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'register_id' => $reg->id,
                'academic_year_id' => $year->id,
                'batch_id' => $batch->id,
                'program_id' => $batch->program_id,
                'name' => $name,
                'middle_name' => $this->middleNames[array_rand($this->middleNames)],
                'last_name' => $this->lastNames[array_rand($this->lastNames)],
                'birth_date' => date('Y-m-d', rand(20130101, 20181231)),
                'gender' => $gender,
                'national_id' => '9' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'email' => 'adm' . $i . '@edubba.test',
                'address' => 'بغداد - ' . ['المنصور', 'الكرادة', 'الجادرية', 'الشعب', 'زيونة'][rand(0, 4)],
                'previous_school' => $iraqiSchools[array_rand($iraqiSchools)],
                'fees_amount' => rand(500000, 700000),
                'state' => $state,
                'notes' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('admissions', $data);
    }

    // ═══════════════════════════════════════════════════════════════
    // 22. المدرسون (Tutors)
    // ═══════════════════════════════════════════════════════════════

    private function seedTutors(): void
    {
        $this->command->info('  -> إدخال المدرسين...');

        $facultyIds = DB::table('faculties')->pluck('id')->toArray();
        $subjects = Subject::pluck('id')->toArray();

        $data = [];
        for ($i = 1; $i <= 25; $i++) {
            $data[] = [
                'name' => trim($this->maleFirstNames[array_rand($this->maleFirstNames)] . ' ' . $this->lastNames[array_rand($this->lastNames)]),
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

        $this->safeInsert('tutors', $data);
    }

    // ═══════════════════════════════════════════════════════════════
    // 23. مراكز التقوية
    // ═══════════════════════════════════════════════════════════════

    private function seedTutoring(): void
    {
        $this->command->info('  -> إدخال مراكز التقوية...');

        $year = AcademicYear::where('name', '2025-2026')->first();

        $centerNames = ['مركز النور', 'مركز البيان', 'center الحكمة', 'مركز الفلاح', 'مركز الإيمان'];
        foreach ($centerNames as $cn) {
            Center::firstOrCreate(['name' => $cn], [
                'address' => 'بغداد - ' . ['المنصور', 'الكرادة', 'زيونة'][rand(0, 2)],
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

        // باقات التقوية
        TutoringPackage::firstOrCreate(['name' => 'الأساسي (8 حصص)'], ['sessions' => 8, 'price' => 80000, 'active' => true]);
        TutoringPackage::firstOrCreate(['name' => 'القياسي (16 حصة)'], ['sessions' => 16, 'price' => 150000, 'active' => true]);
        TutoringPackage::firstOrCreate(['name' => 'المتقدم (24 حصة)'], ['sessions' => 24, 'price' => 200000, 'active' => true]);
        TutoringPackage::firstOrCreate(['name' => 'المكثف (32 حصة)'], ['sessions' => 32, 'price' => 260000, 'active' => true]);

        // منتجات
        TutoringProduct::firstOrCreate(['code' => 'MAT01'], ['name' => 'المواد الدراسية', 'price' => 25000, 'active' => true]);
        TutoringProduct::firstOrCreate(['code' => 'MAT02'], ['name' => 'كتاب التمارين', 'price' => 15000, 'active' => true]);

        // مجموعات دراسية
        $subjects = Subject::pluck('id')->toArray();
        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->toArray();
        $centerIds = Center::pluck('id')->toArray();
        $tutorIds = DB::table('tutors')->pluck('id')->toArray();

        $sgData = [];
        $sgStudentData = [];
        $sgSessionData = [];
        $sgAttendData = [];

        for ($i = 1; $i <= 20; $i++) {
            $maxStud = rand(5, 12);
            $sgData[] = [
                'id' => $i,
                'name' => "مجموعة {$i}",
                'subject_id' => $subjects[array_rand($subjects)],
                'tutor_id' => $tutorIds[array_rand($tutorIds)],
                'center_id' => $centerIds[array_rand($centerIds)],
                'max_students' => $maxStud,
                'level' => ['مبتدئ', 'متوسط', 'متقدم'][rand(0, 2)],
                'state' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $groupStudents = array_slice($studentIds, ($i * 8) % count($studentIds), $maxStud);
            foreach ($groupStudents as $sid) {
                $sgStudentData[] = [
                    'study_group_id' => $i,
                    'student_id' => $sid,
                    'join_date' => '2025-09-15',
                    'state' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            for ($s = 1; $s <= 8; $s++) {
                $sessId = ($i - 1) * 8 + $s;
                $sgSessionData[] = [
                    'id' => $sessId,
                    'study_group_id' => $i,
                    'tutor_id' => $tutorIds[array_rand($tutorIds)],
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
    }

    // ═══════════════════════════════════════════════════════════════
    // 23. المكتبة
    // ═══════════════════════════════════════════════════════════════

    private function seedLibrary(): void
    {
        $this->command->info('  -> إدخال المكتبة...');

        $bookData = [];
        $iraqiBooks = [
            ['title' => 'ألف ليلة وليلة', 'author' => 'فاروق شوشي', 'category' => 'أدب'],
            ['title' => 'البداية والنهاية', 'author' => 'ابن كثير', 'category' => 'تاريخ'],
            ['title' => 'رياض الصالحين', 'author' => 'النووي', 'category' => 'دين'],
            ['title' => 'لسان العرب', 'author' => 'ابن منظور', 'category' => 'لغة'],
            ['title' => 'الكشاف', 'author' => 'الزمخشري', 'category' => 'دين'],
            ['title' => 'ديوان المتنبي', 'author' => 'المتنبي', 'category' => 'أدب'],
            ['title' => 'ال俘获', 'author' => 'جبران خليل جبران', 'category' => 'أدب'],
            ['title' => 'الأߥم وال Pornographers', 'author' => 'نجيب محفوظ', 'category' => 'أدب'],
            ['title' => 'الحي بن يقظان', 'author' => 'ابن طفيل', 'category' => 'أدب'],
            ['title' => 'тabi', 'author' => 'ابن سينا', 'category' => 'علوم'],
        ];

        for ($i = 1; $i <= 200; $i++) {
            $book = $iraqiBooks[array_rand($iraqiBooks)];
            $qty = rand(2, 10);
            $bookData[] = [
                'title' => $book['title'] . ' - نسخة ' . $i,
                'author' => $book['author'],
                'isbn' => '978-' . rand(100, 999) . '-' . rand(1000, 9999) . '-' . rand(0, 9),
                'category' => $book['category'],
                'total_qty' => $qty,
                'available_qty' => $qty,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->safeInsert('library_books', $bookData);
    }

    // ═══════════════════════════════════════════════════════════════
    // 24. النقل المدرسي
    // ═══════════════════════════════════════════════════════════════

    private function seedTransport(): void
    {
        $this->command->info('  -> إدخال النقل المدرسي...');

        $vehicleData = [];
        for ($i = 1; $i <= 8; $i++) {
            $vehicleData[] = [
                'plate_number' => str_pad($i, 4, '0', STR_PAD_LEFT) . '-' . ['BA', 'BB', 'BC'][rand(0, 2)] . '-' . rand(100, 999),
                'model' => ['هينداي كونتي', 'نيسان سيفيلين', 'تويوتا كوستر', 'مرسيدس سبرنتر'][rand(0, 3)],
                'capacity' => [20, 30, 40][rand(0, 2)],
                'driver_name' => trim($this->maleFirstNames[array_rand($this->maleFirstNames)] . ' ' . $this->lastNames[array_rand($this->lastNames)]),
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
        $neighborhoods = ['المنصور', 'الكرادة', 'الجادرية', 'العدل', 'الحرية', 'الشعب', 'زيونة'];

        foreach ($vehicles as $v) {
            $routeData[] = [
                'id' => $v->id,
                'name' => "خط {$v->plate_number}",
                'vehicle_id' => $v->id,
                'description' => 'خط النقل المدرسي',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $numStops = rand(3, 5);
            for ($s = 0; $s < $numStops; $s++) {
                $stopData[] = [
                    'route_id' => $v->id,
                    'name' => $neighborhoods[array_rand($neighborhoods)] . ' محطة ' . ($s + 1),
                    'pickup_time' => sprintf('%02d:%02d:00', 7 - $s, 30 * ($s % 2)),
                    'sequence' => $s,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $this->safeInsert('transport_routes', $routeData);
        $this->safeInsert('transport_stops', $stopData);
    }

    // ═══════════════════════════════════════════════════════════════
    // 25. الإشعارات
    // ═══════════════════════════════════════════════════════════════

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
        for ($i = 1; $i <= 2000; $i++) {
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

        foreach (array_chunk($data, self::CHUNK_SIZE) as $chunk) {
            DB::table('notification_logs')->insert($chunk);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // 26. مستخدمي API
    // ═══════════════════════════════════════════════════════════════

    private function seedApiUsers(): void
    {
        $this->command->info('  -> إدخال مستخدمي API...');

        $students = DB::table('students')->where('state', 'admitted')->select('id')->take(50)->get();
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

        $parents = DB::table('parents')->select('id')->take(30)->get();
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

        $faculties = DB::table('faculties')->select('id')->take(20)->get();
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

    // ═══════════════════════════════════════════════════════════════
    // 27. الاستبيانات
    // ═══════════════════════════════════════════════════════════════

    private function seedFeedback(): void
    {
        $this->command->info('  -> إدخال الاستبيانات...');

        $form = FeedbackForm::firstOrCreate(
            ['name' => 'استبيان رضا أولياء الأمور'],
            [
                'type' => 'student',
                'questions' => ['ما مدى رضاك عن مستوى التعليم؟', 'قيّم مرافق المدرسة.', 'هل لديك أي اقتراحات لتحسين التعليم؟'],
                'active' => true,
            ]
        );

        $studentIds = DB::table('students')->where('state', 'admitted')->pluck('id')->take(200)->toArray();

        $data = [];
        foreach ($studentIds as $sid) {
            $data[] = [
                'form_id' => $form->id,
                'student_id' => $sid,
                'rating' => rand(3, 5),
                'comment' => ['ممتاز', 'جيد جداً', 'جيد', 'مقبول'][rand(0, 3)],
                'state' => 'submitted',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->safeInsert('feedbacks', $data);
    }
}
