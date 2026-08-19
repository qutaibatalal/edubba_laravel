<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Activity;
use App\Models\Admission;
use App\Models\AdmissionRegister;
use App\Models\AttendanceLine;
use App\Models\AttendanceSheet;
use App\Models\Batch;
use App\Models\ClassSession;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Department;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamRoom;
use App\Models\ExamRoomAllocation;
use App\Models\ExamSchedule;
use App\Models\ExamType;
use App\Models\Faculty;
use App\Models\FeeLine;
use App\Models\FeeStructure;
use App\Models\Holiday;
use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelRoom;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\LibraryBook;
use App\Models\LibraryIssue;
use App\Models\LibraryMembership;
use App\Models\Marksheet;
use App\Models\MarksheetLine;
use App\Models\MinistryReport;
use App\Models\NotificationLog;
use App\Models\ParentModel;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Receipt;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\TimeTable;
use App\Models\TimeTableLine;
use App\Models\Timing;
use App\Models\TransportRoute;
use App\Models\TransportStop;
use App\Models\TransportVehicle;
use App\Models\User;
use App\Models\WeekDay;
use Illuminate\Database\Seeder;

class BigDataDemoSeeder extends Seeder
{
    private array $arabicFirstNamesMale = [
        'Ahmad', 'Mohammed', 'Ali', 'Hassan', 'Hussein', 'Omar', 'Ibrahim', 'Khaled',
        'Abdullah', 'Youssef', 'Mahmoud', 'Hamza', 'Majid', 'Sami', 'Tariq', 'Rami',
        'Faisal', 'Walid', 'Nabil', 'Rakan', 'Sultan', 'Fahad', 'Mansour', 'Saeed',
        'Nasser', 'Adel', 'Bilal', 'Rashid', 'Jamal', 'Zaid', 'Talal', 'Murad',
    ];

    private array $arabicFirstNamesFemale = [
        'Fatima', 'Aisha', 'Maryam', 'Khadija', 'Noor', 'Layla', 'Sara', 'Zainab',
        'Huda', 'Rania', 'Amira', 'Nada', 'Dina', 'Leen', 'Jana', 'Lama',
        'Reem', 'Hala', 'Salma', 'Yasmin', 'Mona', 'Nour', 'Ghada', 'Rana',
    ];

    private array $arabicLastNames = [
        'Al-Rawi', 'Al-Hamdani', 'Al-Jabouri', 'Al-Abbadi', 'Al-Kubaisi',
        'Al-Nuaimi', 'Al-Mutlaq', 'Al-Shammari', 'Al-Otaibi', 'Al-Dosari',
        'Al-Qahtani', 'Al-Ghamdi', 'Al-Zahrani', 'Al-Mutairi', 'Al-Farhan',
        'Al-Saadi', 'Al-Bayati', 'Al-Tikriti', 'Al-Mosawi', 'Al-Hakim',
    ];

    private array $cities = ['Baghdad', 'Basra', 'Erbil', 'Mosul', 'Sulaymaniyah', 'Najaf', 'Karbala', 'Kirkuk'];

    private array $subjects = [
        ['name' => 'Mathematics', 'code' => 'MATH', 'is_language' => false],
        ['name' => 'Arabic', 'code' => 'ARB', 'is_language' => true],
        ['name' => 'English', 'code' => 'ENG', 'is_language' => true],
        ['name' => 'Science', 'code' => 'SCI', 'is_language' => false],
        ['name' => 'Social Studies', 'code' => 'SOC', 'is_language' => false],
        ['name' => 'Islamic Studies', 'code' => 'ISL', 'is_language' => false],
        ['name' => 'Computer Science', 'code' => 'CS', 'is_language' => false],
        ['name' => 'Physical Education', 'code' => 'PE', 'is_language' => false],
        ['name' => 'Art', 'code' => 'ART', 'is_language' => false],
        ['name' => 'Music', 'code' => 'MUS', 'is_language' => false],
    ];

    private array $programs = [
        ['name' => 'Primary', 'code' => 'PRIM', 'duration_years' => 6],
        ['name' => 'Intermediate', 'code' => 'INTM', 'duration_years' => 3],
        ['name' => 'Secondary', 'code' => 'SEC', 'duration_years' => 3],
    ];

    private array $departments = [
        ['name' => 'Languages', 'code' => 'LANG'],
        ['name' => 'Sciences', 'code' => 'SCI'],
        ['name' => 'Mathematics', 'code' => 'MATH'],
        ['name' => 'Humanities', 'code' => 'HUM'],
        ['name' => 'Arts & PE', 'code' => 'APE'],
    ];

    private array $bookTitles = [
        ['title' => 'Introduction to Algebra', 'author' => 'John Smith', 'category' => 'Mathematics'],
        ['title' => 'Arabic Grammar Basics', 'author' => 'Ahmad Hassan', 'category' => 'Language'],
        ['title' => 'World History', 'author' => 'Sarah Johnson', 'category' => 'History'],
        ['title' => 'Biology Fundamentals', 'author' => 'Michael Brown', 'category' => 'Science'],
        ['title' => 'English Composition', 'author' => 'Emily Davis', 'category' => 'Language'],
        ['title' => 'Physics Principles', 'author' => 'David Wilson', 'category' => 'Science'],
        ['title' => 'Geography of Iraq', 'author' => 'Omar Al-Rawi', 'category' => 'Geography'],
        ['title' => 'Islamic Civilization', 'author' => 'Yusuf Al-Hamdani', 'category' => 'Religion'],
        ['title' => 'Computer Programming', 'author' => 'Lisa Anderson', 'category' => 'Technology'],
        ['title' => 'Creative Writing', 'author' => 'Mark Taylor', 'category' => 'Language'],
        ['title' => 'Environmental Science', 'author' => 'Anna White', 'category' => 'Science'],
        ['title' => 'Mathematical Thinking', 'author' => 'Robert Lee', 'category' => 'Mathematics'],
        ['title' => 'Modern Arabic Literature', 'author' => 'Nadia Al-Jabouri', 'category' => 'Literature'],
        ['title' => 'Chemistry Basics', 'author' => 'James Martin', 'category' => 'Science'],
        ['title' => 'World Geography', 'author' => 'Karen Thomas', 'category' => 'Geography'],
    ];

    public function run(): void
    {
        $this->command->info('🎓 Seeding big data demo for all modules...');

        $year = AcademicYear::where('name', '2025-2026')->first();
        $term1 = Term::where('name', 'Term 1')->where('academic_year_id', $year?->id)->first();
        $term2 = Term::where('name', 'Term 2')->where('academic_year_id', $year?->id)->first();

        $this->seedDepartmentsAndPrograms();
        $this->seedClassrooms();
        $this->seedSubjects();
        $this->seedBatchesAndCourses($year);
        $this->seedFaculty();
        $this->seedParentsAndStudents($year);
        $this->seedFeeStructuresAndInvoices($year);
        $this->seedTimetableAndSessions($year, $term1);
        $this->seedAttendance($year);
        $this->seedExams($year, $term1, $term2);
        $this->seedLibrary();
        $this->seedTransport();
        $this->seedHostel();
        $this->seedAdmissions($year);
        $this->seedHolidays($year);
        $this->seedMinistryReports($year, $term1);
        $this->seedNotifications();
        $this->seedActivities();

        $this->command->info('✅ Big data demo seeded successfully!');
        $this->printStats();
    }

    private function seedDepartmentsAndPrograms(): void
    {
        $this->command->info('  📚 Seeding departments & programs...');

        foreach ($this->departments as $d) {
            Department::firstOrCreate(['name' => $d['name']], [
                'code' => $d['code'], 'active' => true,
            ]);
        }

        $langDept = Department::where('name', 'Languages')->first();
        $sciDept = Department::where('name', 'Sciences')->first();
        $mathDept = Department::where('name', 'Mathematics')->first();
        $humDept = Department::where('name', 'Humanities')->first();

        $deptMap = [
            'Primary' => $langDept, 'Intermediate' => $sciDept, 'Secondary' => $mathDept,
        ];

        foreach ($this->programs as $p) {
            Program::firstOrCreate(['name' => $p['name']], [
                'code' => $p['code'],
                'department_id' => $deptMap[$p['name']]->id ?? $humDept->id,
                'duration_years' => $p['duration_years'],
                'active' => true,
            ]);
        }
    }

    private function seedClassrooms(): void
    {
        $this->command->info('  🏫 Seeding classrooms...');

        $buildings = ['Building A', 'Building B', 'Building C'];
        $rooms = ['101', '102', '103', '104', '105', '201', '202', '203', '204', '205', '301', '302', '303'];

        foreach ($buildings as $b) {
            foreach ($rooms as $r) {
                Classroom::firstOrCreate(
                    ['name' => "$b - Room $r"],
                    ['building' => $b, 'floor' => substr($r, 0, 1), 'capacity' => 35, 'active' => true]
                );
            }
        }
    }

    private function seedSubjects(): void
    {
        $this->command->info('  📖 Seeding subjects...');

        $deptMap = [
            'Languages' => Department::where('name', 'Languages')->first(),
            'Sciences' => Department::where('name', 'Sciences')->first(),
            'Mathematics' => Department::where('name', 'Mathematics')->first(),
            'Humanities' => Department::where('name', 'Humanities')->first],
            'Arts & PE' => Department::where('name', 'Arts & PE')->first(),
        ];

        $subjectDeptMap = [
            'Mathematics' => 'Mathematics', 'Arabic' => 'Languages', 'English' => 'Languages',
            'Science' => 'Sciences', 'Social Studies' => 'Humanities', 'Islamic Studies' => 'Humanities',
            'Computer Science' => 'Sciences', 'Physical Education' => 'Arts & PE',
            'Art' => 'Arts & PE', 'Music' => 'Arts & PE',
        ];

        foreach ($this->subjects as $s) {
            $deptName = $subjectDeptMap[$s['name']] ?? 'Languages';
            Subject::firstOrCreate(['name' => $s['name']], [
                'code' => $s['code'],
                'department_id' => $deptMap[$deptName]?->id,
                'is_language' => $s['is_language'],
                'active' => true,
            ]);
        }
    }

    private function seedBatchesAndCourses(AcademicYear $year): void
    {
        $this->command->info('  📋 Seeding batches & courses...');

        $programs = Program::all();
        $batchNames = ['A', 'B', 'C', 'D'];

        foreach ($programs as $program) {
            foreach ($batchNames as $section) {
                $name = "{$program->name} Grade {$section}";
                Batch::firstOrCreate(
                    ['name' => $name, 'program_id' => $program->id, 'academic_year_id' => $year->id],
                    ['capacity' => 40, 'active' => true]
                );
            }
        }

        $subjects = Subject::all();
        $batches = Batch::where('academic_year_id', $year->id)->get();

        foreach ($batches as $batch) {
            foreach ($subjects as $subject) {
                Course::firstOrCreate(
                    ['name' => "{$subject->name} - {$batch->name}", 'subject_id' => $subject->id, 'batch_id' => $batch->id, 'academic_year_id' => $year->id],
                    ['code' => strtoupper(substr($subject->code, 0, 3)) . '-' . strtoupper(substr($batch->name, 0, 4)), 'subject_id' => $subject->id, 'batch_id' => $batch->id, 'academic_year_id' => $year->id, 'active' => true]
                );
            }
        }
    }

    private function seedFaculty(): void
    {
        $this->command->info('  👨‍🏫 Seeding faculty...');

        $departments = Department::all();

        $specializations = [
            'Mathematics', 'Arabic Language', 'English Language', 'Biology', 'Physics',
            'Chemistry', 'Computer Science', 'Physical Education', 'Art', 'Music',
            'Social Studies', 'Islamic Studies', 'History', 'Geography',
        ];

        for ($i = 1; $i <= 25; $i++) {
            $gender = $i % 3 === 0 ? 'female' : 'male';
            $first = $this->rand($gender === 'male' ? $this->arabicFirstNamesMale : $this->arabicFirstNamesFemale);
            $middle = $this->rand($this->arabicFirstNamesMale);
            $last = $this->rand($this->arabicLastNames);

            $faculty = Faculty::firstOrCreate(
                ['faculty_code' => 'FAC' . str_pad($i, 4, '0', STR_PAD_LEFT)],
                [
                    'name' => $first, 'middle_name' => $middle, 'last_name' => $last,
                    'gender' => $gender,
                    'birth_date' => now()->subYears(rand(28, 55))->subDays(rand(0, 365)),
                    'national_id' => 'NID-F' . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'mobile' => '078' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'email' => strtolower($first) . '.' . strtolower($last) . '@edubba.test',
                    'address' => $this->rand($this->cities) . ', Iraq',
                    'qualification' => $this->rand(['Bachelor', 'Master', 'PhD']),
                    'specialization' => $this->rand($specializations),
                    'join_date' => now()->subYears(rand(1, 10))->subDays(rand(0, 365)),
                    'department_id' => $departments->random()->id,
                    'state' => Faculty::STATE_JOINED,
                    'active' => true,
                ]
            );

            $user = User::firstOrCreate(
                ['email' => "faculty{$i}@edubba.test"],
                ['name' => "$first $last", 'password' => 'password']
            );
            $faculty->update(['user_id' => $user->id]);
        }
    }

    private function seedParentsAndStudents(AcademicYear $year): void
    {
        $this->command->info('  👨‍👩‍👧‍👦 Seeding parents & students...');

        $batches = Batch::where('academic_year_id', $year->id)->get();
        $programs = Program::all();

        for ($i = 1; $i <= 150; $i++) {
            $gender = $i % 2 === 0 ? 'male' : 'female';
            $first = $this->rand($gender === 'male' ? $this->arabicFirstNamesMale : $this->arabicFirstNamesFemale);
            $middle = $this->rand($this->arabicFirstNamesMale);
            $last = $this->rand($this->arabicLastNames);
            $batch = $batches->random();
            $program = $programs->random();
            $city = $this->rand($this->cities);

            $parent = ParentModel::firstOrCreate(
                ['national_id' => 'PID' . str_pad($i, 6, '0', STR_PAD_LEFT)],
                [
                    'name' => $this->rand($this->arabicFirstNamesMale) . ' ' . $last,
                    'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'mobile' => '078' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'email' => 'parent' . $i . '@edubba.test',
                    'address' => $city . ', Iraq',
                    'occupation' => $this->rand(['Teacher', 'Engineer', 'Doctor', 'Business', 'Government', 'Retired']),
                    'relation' => $this->rand(['father', 'mother', 'guardian']),
                    'active' => true,
                ]
            );

            $student = Student::firstOrCreate(
                ['student_code' => 'STU' . str_pad($i, 5, '0', STR_PAD_LEFT)],
                [
                    'name' => $first, 'middle_name' => $middle, 'last_name' => $last,
                    'gender' => $gender,
                    'birth_date' => now()->subYears(rand(6, 18))->subDays(rand(0, 365)),
                    'birth_place' => $city,
                    'national_id' => 'SID' . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'residence' => $city,
                    'blood_group' => $this->rand(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                    'phone' => '079' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'mobile' => '079' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'email' => strtolower($first) . $i . '@student.edubba.test',
                    'address' => $city . ', Iraq',
                    'city' => $city,
                    'province' => $city,
                    'country' => 'Iraq',
                    'batch_id' => $batch->id,
                    'program_id' => $program->id,
                    'academic_year_id' => $year->id,
                    'parent_id' => $parent->id,
                    'state' => Student::STATE_ADMITTED,
                    'admission_date' => now()->subDays(rand(10, 90)),
                    'roll_no' => 'RN' . str_pad($i, 5, '0', STR_PAD_LEFT),
                    'active' => true,
                ]
            );

            $student->parents()->syncWithoutDetaching([
                $parent->id => [
                    'relation' => $parent->relation,
                    'is_main' => true,
                    'guardian' => true,
                ],
            ]);

            $student->courses()->syncWithoutDetaching(
                Course::where('batch_id', $batch->id)->get()->mapWithKeys(fn ($c) => [$c->id => [
                    'batch_id' => $batch->id,
                    'academic_year_id' => $year->id,
                    'state' => 'enrolled',
                    'total_fees' => rand(500000, 2000000),
                ]])->toArray()
            );
        }
    }

    private function seedFeeStructuresAndInvoices(AcademicYear $year): void
    {
        $this->command->info('  💰 Seeding fees & invoices...');

        $batches = Batch::where('academic_year_id', $year->id)->get();
        $students = Student::where('academic_year_id', $year->id)->get();

        $feeItems = [
            ['name' => 'Tuition Fee', 'amount' => 1500000, 'type' => FeeLine::TYPE_ONE_TIME],
            ['name' => 'Registration Fee', 'amount' => 200000, 'type' => FeeLine::TYPE_ONE_TIME],
            ['name' => 'Library Fee', 'amount' => 100000, 'type' => FeeLine::TYPE_RECURRING],
            ['name' => 'Lab Fee', 'amount' => 300000, 'type' => FeeLine::TYPE_ONE_TIME],
            ['name' => 'Sports Fee', 'amount' => 150000, 'type' => FeeLine::TYPE_ONE_TIME],
            ['name' => 'Transport Fee', 'amount' => 500000, 'type' => FeeLine::TYPE_RECURRING],
        ];

        $programs = Program::all();
        foreach ($programs as $program) {
            foreach ($batches->where('program_id', $program->id) as $batch) {
                $fs = FeeStructure::firstOrCreate(
                    ['name' => "Fee Structure - {$batch->name}", 'program_id' => $program->id, 'batch_id' => $batch->id, 'academic_year_id' => $year->id],
                    ['active' => true]
                );

                foreach ($feeItems as $idx => $item) {
                    FeeLine::firstOrCreate(
                        ['fee_structure_id' => $fs->id, 'name' => $item['name']],
                        ['amount' => $item['amount'] * ($program->id), 'type' => $item['type'], 'sequence' => $idx + 1]
                    );
                }
            }
        }

        $invoiceNo = 1;
        foreach ($students->random(min(120, $students->count())) as $student) {
            $totalFees = rand(2000000, 3500000);
            $paid = in_array(rand(1, 10), [1, 2, 3, 4, 5]) ? $totalFees : rand(0, $totalFees);
            $state = $paid >= $totalFees ? Invoice::STATE_PAID : ($paid > 0 ? Invoice::STATE_OPEN : Invoice::STATE_OPEN);

            $invoice = Invoice::firstOrCreate(
                ['number' => 'INV' . str_pad($invoiceNo++, 6, '0', STR_PAD_LEFT)],
                [
                    'student_id' => $student->id,
                    'parent_id' => $student->parent_id,
                    'academic_year_id' => $year->id,
                    'date' => now()->subDays(rand(10, 60)),
                    'due_date' => now()->addDays(rand(15, 45)),
                    'subtotal' => $totalFees,
                    'tax' => 0,
                    'total' => $totalFees,
                    'paid' => $paid,
                    'balance' => $totalFees - $paid,
                    'state' => $state,
                ]
            );

            $lineItems = [
                ['description' => 'Tuition Fee', 'qty' => 1, 'unit_price' => $totalFees * 0.6],
                ['description' => 'Registration Fee', 'qty' => 1, 'unit_price' => $totalFees * 0.15],
                ['description' => 'Library Fee', 'qty' => 1, 'unit_price' => $totalFees * 0.1],
                ['description' => 'Lab Fee', 'qty' => 1, 'unit_price' => $totalFees * 0.1],
                ['description' => 'Sports Fee', 'qty' => 1, 'unit_price' => $totalFees * 0.05],
            ];

            foreach ($lineItems as $line) {
                InvoiceLine::firstOrCreate(
                    ['invoice_id' => $invoice->id, 'description' => $line['description']],
                    ['qty' => $line['qty'], 'unit_price' => $line['unit_price'], 'amount' => $line['unit_price']]
                );
            }

            if ($paid > 0) {
                $payment = Payment::firstOrCreate(
                    ['reference' => 'PAY' . str_pad($invoiceNo, 6, '0', STR_PAD_LEFT)],
                    [
                        'invoice_id' => $invoice->id,
                        'student_id' => $student->id,
                        'parent_id' => $student->parent_id,
                        'amount' => $paid,
                        'method' => $this->rand([Payment::METHOD_CASH, Payment::METHOD_CARD, Payment::METHOD_TRANSFER]),
                        'state' => Payment::STATE_DONE,
                        'date' => now()->subDays(rand(5, 30)),
                    ]
                );

                Receipt::firstOrCreate(
                    ['payment_id' => $payment->id],
                    [
                        'receipt_no' => 'RCP' . str_pad($invoiceNo, 6, '0', STR_PAD_LEFT),
                        'invoice_id' => $invoice->id,
                        'date' => $payment->date,
                        'amount' => $paid,
                    ]
                );
            }
        }
    }

    private function seedTimetableAndSessions(AcademicYear $year, Term $term): void
    {
        $this->command->info('  ⏰ Seeding timetable & sessions...');

        $batches = Batch::where('academic_year_id', $year->id)->get();
        $subjects = Subject::all();
        $faculty = Faculty::where('state', Faculty::STATE_JOINED)->get();
        $classrooms = Classroom::all();
        $weekDays = WeekDay::all();
        $timings = Timing::all();
        $courses = Course::where('academic_year_id', $year->id)->get();

        foreach ($batches as $batch) {
            $tt = TimeTable::firstOrCreate(
                ['batch_id' => $batch->id, 'academic_year_id' => $year->id, 'term_id' => $term->id],
                ['name' => "Timetable - {$batch->name}", 'active' => true]
            );

            foreach ($weekDays->take(5) as $day) {
                foreach ($timings as $timing) {
                    $subject = $subjects->random();
                    $course = $courses->where('batch_id', $batch->id)->where('subject_id', $subject->id)->first();
                    if (!$course) continue;

                    $line = TimeTableLine::firstOrCreate(
                        ['time_table_id' => $tt->id, 'week_day_id' => $day->id, 'timing_id' => $timing->id],
                        [
                            'subject_id' => $subject->id,
                            'faculty_id' => $faculty->random()->id,
                            'course_id' => $course->id,
                            'classroom_id' => $classrooms->random()->id,
                        ]
                    );

                    for ($week = 0; $week < 12; $week++) {
                        $sessionDate = Carbon\Carbon::parse($year->date_start)->addWeeks($week)->addDays($day->sequence - 1);
                        if ($sessionDate->isPast() && $sessionDate->diffInDays(now()) > 30) continue;

                        ClassSession::firstOrCreate(
                            ['time_table_line_id' => $line->id, 'date' => $sessionDate->format('Y-m-d')],
                            [
                                'batch_id' => $batch->id,
                                'course_id' => $course->id,
                                'subject_id' => $subject->id,
                                'faculty_id' => $line->faculty_id,
                                'classroom_id' => $line->classroom_id,
                                'start_time' => $timing->start_time,
                                'end_time' => $timing->end_time,
                                'state' => $sessionDate->isPast() ? ClassSession::STATE_DONE : ClassSession::STATE_PLANNED,
                                'topic' => "Week {$week} - " . $subject->name,
                            ]
                        );
                    }
                }
            }
        }
    }

    private function seedAttendance(AcademicYear $year): void
    {
        $this->command->info('  📝 Seeding attendance...');

        $sessions = ClassSession::where('state', ClassSession::STATE_DONE)
            ->where('date', '>=', now()->subDays(30))
            ->limit(200)
            ->get();

        foreach ($sessions as $session) {
            $sheet = AttendanceSheet::firstOrCreate(
                ['session_id' => $session->id],
                [
                    'batch_id' => $session->batch_id,
                    'course_id' => $session->course_id,
                    'faculty_id' => $session->faculty_id,
                    'date' => $session->date,
                    'state' => AttendanceSheet::STATE_DONE,
                ]
            );

            $students = Student::where('batch_id', $session->batch_id)
                ->where('state', Student::STATE_ADMITTED)
                ->get();

            foreach ($students as $student) {
                $rand = rand(1, 100);
                $status = $rand <= 75 ? AttendanceLine::STATUS_PRESENT
                    : ($rand <= 85 ? AttendanceLine::STATUS_LATE
                    : ($rand <= 93 ? AttendanceLine::STATUS_ABSENT
                    : AttendanceLine::STATUS_LEAVE));

                AttendanceLine::firstOrCreate(
                    ['attendance_sheet_id' => $sheet->id, 'student_id' => $student->id],
                    ['status' => $status, 'note' => $status === 'absent' ? $this->rand(['No reason', 'Sick', 'Family emergency', '']) : '']
                );
            }
        }
    }

    private function seedExams(AcademicYear $year, Term $term1, Term $term2): void
    {
        $this->command->info('  📊 Seeding exams & marksheets...');

        $examTypes = [
            ['name' => 'Quiz 1', 'weight' => 10],
            ['name' => 'Midterm', 'weight' => 30],
            ['name' => 'Quiz 2', 'weight' => 10],
            ['name' => 'Assignment 1', 'weight' => 10],
            ['name' => 'Final Exam', 'weight' => 40],
        ];

        foreach ($examTypes as $et) {
            ExamType::firstOrCreate(['name' => $et['name']], ['weight' => $et['weight'], 'active' => true]);
        }

        $batches = Batch::where('academic_year_id', $year->id)->get();
        $courses = Course::where('academic_year_id', $year->id)->get();
        $subjects = Subject::all();
        $students = Student::where('academic_year_id', $year->id)->where('state', Student::STATE_ADMITTED)->get();

        ExamRoom::firstOrCreate(['name' => 'Hall A', 'code' => 'HA'], ['capacity' => 50, 'active' => true]);
        ExamRoom::firstOrCreate(['name' => 'Hall B', 'code' => 'HB'], ['capacity' => 50, 'active' => true]);
        ExamRoom::firstOrCreate(['name' => 'Hall C', 'code' => 'HC'], ['capacity' => 40, 'active' => true]);

        $rooms = ExamRoom::all();
        $examTypesModels = ExamType::all();

        $examNo = 0;
        foreach ($batches as $batch) {
            $batchStudents = $students->where('batch_id', $batch->id);
            if ($batchStudents->isEmpty()) continue;

            $batchCourses = $courses->where('batch_id', $batch->id);
            $terms = [$term1, $term2];

            foreach ($terms as $term) {
                foreach ($examTypesModels as $examType) {
                    $exam = Exam::firstOrCreate(
                        [
                            'name' => "{$examType->name} - {$batch->name}",
                            'exam_type_id' => $examType->id,
                            'academic_year_id' => $year->id,
                            'term_id' => $term->id,
                            'batch_id' => $batch->id,
                        ],
                        [
                            'date_start' => now()->subDays(rand(5, 60)),
                            'date_end' => now()->subDays(rand(1, 4)),
                            'state' => Exam::STATE_DONE,
                        ]
                    );

                    foreach ($batchCourses->random(min(3, $batchCourses->count())) as $course) {
                        $subject = $subjects->where('id', $course->subject_id)->first();
                        if (!$subject) continue;

                        ExamSchedule::firstOrCreate(
                            ['exam_id' => $exam->id, 'subject_id' => $subject->id, 'course_id' => $course->id],
                            [
                                'date' => now()->subDays(rand(1, 14)),
                                'start_time' => '09:00:00',
                                'end_time' => '11:00:00',
                                'max_marks' => 100,
                                'pass_marks' => 50,
                            ]
                        );
                    }

                    foreach ($batchStudents as $student) {
                        $totalMarks = 100;
                        $obtained = rand(15, 98);
                        $percentage = ($obtained / $totalMarks) * 100;
                        $grade = $percentage >= 90 ? 'A+' : ($percentage >= 80 ? 'A' : ($percentage >= 70 ? 'B' : ($percentage >= 60 ? 'C' : ($percentage >= 50 ? 'D' : 'F'))));

                        $ms = Marksheet::firstOrCreate(
                            ['exam_id' => $exam->id, 'student_id' => $student->id],
                            [
                                'batch_id' => $batch->id,
                                'total_marks' => $totalMarks,
                                'obtained_marks' => $obtained,
                                'percentage' => $percentage,
                                'grade' => $grade,
                                'result' => $percentage >= 50 ? Marksheet::RESULT_PASS : Marksheet::RESULT_FAIL,
                                'state' => Marksheet::STATE_DONE,
                                'finalized_at' => now(),
                            ]
                        );

                        foreach ($batchCourses->take(3) as $course) {
                            $subject = $subjects->where('id', $course->subject_id)->first();
                            if (!$subject) continue;

                            $subjMarks = rand(10, 100);
                            MarksheetLine::firstOrCreate(
                                ['marksheet_id' => $ms->id, 'subject_id' => $subject->id, 'course_id' => $course->id],
                                [
                                    'max_marks' => 100,
                                    'marks' => $subjMarks,
                                    'pass_marks' => 50,
                                    'percentage' => $subjMarks,
                                    'grade' => $subjMarks >= 90 ? 'A+' : ($subjMarks >= 80 ? 'A' : ($subjMarks >= 70 ? 'B' : ($subjMarks >= 60 ? 'C' : 'F'))),
                                    'passed' => $subjMarks >= 50,
                                ]
                            );
                        }
                    }

                    $rank = 1;
                    $exam->marksheets()->orderByDesc('obtained_marks')->get()->each(function ($ms) use (&$rank) {
                        $ms->update(['rank' => $rank++]);
                    });

                    foreach ($batchStudents as $student) {
                        $ms = $exam->marksheets()->where('student_id', $student->id)->first();
                        if (!$ms) continue;

                        ExamResult::firstOrCreate(
                            ['student_id' => $student->id, 'exam_id' => $exam->id],
                            [
                                'term_id' => $term->id,
                                'academic_year_id' => $year->id,
                                'batch_id' => $batch->id,
                                'total' => $ms->obtained_marks,
                                'average' => $ms->percentage,
                                'grade' => $ms->grade,
                                'rank' => $ms->rank,
                                'result' => $ms->result,
                                'published_at' => now(),
                            ]
                        );
                    }
                }
            }
        }
    }

    private function seedLibrary(): void
    {
        $this->command->info('  📚 Seeding library...');

        foreach ($this->bookTitles as $b) {
            $qty = rand(3, 10);
            LibraryBook::firstOrCreate(
                ['isbn' => '978-' . rand(1000000000, 9999999999)],
                [
                    'title' => $b['title'],
                    'author' => $b['author'],
                    'category' => $b['category'],
                    'total_qty' => $qty,
                    'available_qty' => $qty,
                    'active' => true,
                ]
            );
        }

        $books = LibraryBook::all();
        $students = Student::where('state', Student::STATE_ADMITTED)->limit(50)->get();

        foreach ($students as $student) {
            LibraryMembership::firstOrCreate(
                ['student_id' => $student->id],
                [
                    'start_date' => now()->subMonths(6),
                    'end_date' => now()->addMonths(6),
                    'state' => LibraryMembership::STATE_ACTIVE,
                ]
            );

            $issueCount = rand(1, 3);
            $books->random($issueCount)->each(function ($book) use ($student) {
                $returned = rand(1, 10) > 3;
                LibraryIssue::firstOrCreate(
                    ['book_id' => $book->id, 'student_id' => $student->id],
                    [
                        'issue_date' => now()->subDays(rand(10, 60)),
                        'due_date' => now()->subDays(rand(0, 30)),
                        'return_date' => $returned ? now()->subDays(rand(0, 5)) : null,
                        'fine' => $returned ? 0 : rand(1000, 10000),
                        'state' => $returned ? LibraryIssue::STATE_RETURNED : LibraryIssue::STATE_ISSUED,
                    ]
                );
            });
        }
    }

    private function seedTransport(): void
    {
        $this->command->info('  🚌 Seeding transport...');

        $vehicles = [
            ['plate_number' => 'BGD-001', 'model' => 'Toyota Coaster', 'capacity' => 30, 'driver_name' => 'Ahmad Hassan', 'driver_phone' => '07700001001'],
            ['plate_number' => 'BGD-002', 'model' => 'Toyota Coaster', 'capacity' => 30, 'driver_name' => 'Omar Ali', 'driver_phone' => '07700001002'],
            ['plate_number' => 'BSR-001', 'model' => 'Hyundai County', 'capacity' => 25, 'driver_name' => 'Khaled Mahmood', 'driver_phone' => '07800001003'],
            ['plate_number' => 'BSR-002', 'model' => 'Hyundai County', 'capacity' => 25, 'driver_name' => 'Sami Nasser', 'driver_phone' => '07900001004'],
            ['plate_number' => 'EBL-001', 'model' => 'Nissan Civilian', 'capacity' => 20, 'driver_name' => 'Tariq Youssef', 'driver_phone' => '07700001005'],
        ];

        foreach ($vehicles as $v) {
            TransportVehicle::firstOrCreate(
                ['plate_number' => $v['plate_number']],
                array_merge($v, ['active' => true])
            );
        }

        $routeNames = ['Route 1 - North', 'Route 2 - South', 'Route 3 - East', 'Route 4 - West'];
        $vehicles = TransportVehicle::all();
        $stopNames = ['Al-Mansour', 'Al-Karrada', 'Al-Adhamiyah', 'Al-Jadriya', 'Al-Saydiyah', 'Al-Za\'faraniyah', 'Al-Baladiyat', 'Al-Hurriyah'];

        foreach ($routeNames as $idx => $name) {
            $vehicle = $vehicles[$idx % $vehicles->count()];
            $route = TransportRoute::firstOrCreate(
                ['name' => $name],
                ['vehicle_id' => $vehicle->id, 'description' => "Daily route for {$name}", 'active' => true]
            );

            $stopCount = rand(3, 6);
            $usedStops = array_rand(array_flip($stopNames), $stopCount);
            foreach ($usedStops as $si => $stopName) {
                TransportStop::firstOrCreate(
                    ['route_id' => $route->id, 'name' => $stopName],
                    ['pickup_time' => sprintf('%02d:%02d:00', 7 + intval($si / 2), ($si % 2) * 30), 'sequence' => $si + 1]
                );
            }
        }
    }

    private function seedHostel(): void
    {
        $this->command->info('  🏠 Seeding hostels...');

        $hostels = [
            ['name' => 'Al-Noor Hostel', 'warden_name' => 'Fatima Al-Rawi'],
            ['name' => 'Al-Salam Hostel', 'warden_name' => 'Khadija Al-Hamdani'],
        ];

        foreach ($hostels as $h) {
            $hostel = Hostel::firstOrCreate(
                ['name' => $h['name']],
                ['address' => 'Baghdad, Al-Mansour', 'warden_name' => $h['warden_name'], 'active' => true]
            );

            for ($room = 1; $room <= 10; $room++) {
                $capacity = 4;
                $occupied = rand(0, $capacity);
                $state = $occupied >= $capacity ? HostelRoom::STATE_FULL : HostelRoom::STATE_AVAILABLE;

                $hr = HostelRoom::firstOrCreate(
                    ['hostel_id' => $hostel->id, 'room_no' => str_pad($room, 3, '0', STR_PAD_LEFT)],
                    ['capacity' => $capacity, 'occupied' => $occupied, 'monthly_rent' => rand(200000, 500000), 'state' => $state]
                );

                if ($occupied > 0) {
                    $students = Student::where('state', Student::STATE_ADMITTED)->inRandomOrder()->limit($occupied)->get();
                    foreach ($students as $student) {
                        HostelAllocation::firstOrCreate(
                            ['room_id' => $hr->id, 'student_id' => $student->id],
                            ['start_date' => now()->subMonths(rand(1, 6)), 'state' => HostelAllocation::STATE_ACTIVE]
                        );
                    }
                }
            }
        }
    }

    private function seedAdmissions(AcademicYear $year): void
    {
        $this->command->info('  📄 Seeding admissions...');

        $batch = Batch::where('academic_year_id', $year->id)->first();
        $program = $batch ? Program::find($batch->program_id) : Program::first();

        $register = AdmissionRegister::firstOrCreate(
            ['name' => "Admission Register - {$year->name}", 'academic_year_id' => $year->id],
            ['batch_id' => $batch?->id, 'start_date' => now()->subMonths(3), 'end_date' => now()->addMonth(), 'active' => true]
        );

        $states = [Admission::STATE_DRAFT, Admission::STATE_SUBMIT, Admission::STATE_APPROVE, Admission::STATE_REJECT, Admission::STATE_ADMITTED];

        for ($i = 1; $i <= 40; $i++) {
            $gender = $i % 2 === 0 ? 'male' : 'female';
            $first = $this->rand($gender === 'male' ? $this->arabicFirstNamesMale : $this->arabicFirstNamesFemale);
            $last = $this->rand($this->arabicLastNames);

            Admission::firstOrCreate(
                ['application_no' => 'ADM' . str_pad($i, 5, '0', STR_PAD_LEFT)],
                [
                    'register_id' => $register->id,
                    'academic_year_id' => $year->id,
                    'batch_id' => $batch?->id,
                    'program_id' => $program?->id,
                    'name' => $first,
                    'middle_name' => $this->rand($this->arabicFirstNamesMale),
                    'last_name' => $last,
                    'birth_date' => now()->subYears(rand(6, 18)),
                    'gender' => $gender,
                    'national_id' => 'ADM-ID' . str_pad($i, 6, '0', STR_PAD_LEFT),
                    'phone' => '077' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'address' => $this->rand($this->cities) . ', Iraq',
                    'previous_school' => $this->rand(['Al-Mustaqbal School', 'Al-Kindy School', 'Babylon School', 'Al-Hikma School']),
                    'fees_amount' => rand(1500000, 3000000),
                    'state' => $this->rand($states),
                    'notes' => $this->rand(['', 'Outstanding student', 'Has siblings in school', 'Transfer student', '']),
                ]
            );
        }
    }

    private function seedHolidays(AcademicYear $year): void
    {
        $this->command->info('  🗓️ Seeding holidays...');

        $holidays = [
            ['name' => 'Eid Al-Fitr', 'date_start' => '2026-03-20', 'date_stop' => '2026-03-23'],
            ['name' => 'Eid Al-Adha', 'date_start' => '2026-05-27', 'date_stop' => '2026-05-30'],
            ['name' => 'Islamic New Year', 'date_start' => '2026-06-17', 'date_stop' => '2026-06-18'],
            ['name' => 'Prophet Birthday', 'date_start' => '2026-08-26', 'date_stop' => '2026-08-27'],
            ['name' => 'Republic Day', 'date_start' => '2026-07-14', 'date_stop' => '2026-07-14'],
            ['name' => 'National Day', 'date_start' => '2026-10-03', 'date_stop' => '2026-10-03'],
            ['name' => 'Teachers Day', 'date_start' => '2026-03-05', 'date_stop' => '2026-03-05'],
        ];

        foreach ($holidays as $h) {
            Holiday::firstOrCreate(
                ['name' => $h['name'], 'academic_year_id' => $year->id],
                ['date_start' => $h['date_start'], 'date_stop' => $h['date_stop'], 'active' => true]
            );
        }
    }

    private function seedMinistryReports(AcademicYear $year, Term $term): void
    {
        $this->command->info('  📋 Seeding ministry reports...');

        $reportTypes = ['Student Statistics', 'Attendance Summary', 'Exam Results', 'Fee Collection', 'Staff Report'];

        foreach ($reportTypes as $type) {
            MinistryReport::firstOrCreate(
                ['name' => "{$type} - {$year->name}", 'academic_year_id' => $year->id],
                [
                    'term_id' => $term->id,
                    'report_type' => strtolower(str_replace(' ', '_', $type)),
                    'data' => [
                        'total_students' => Student::where('academic_year_id', $year->id)->count(),
                        'total_faculty' => Faculty::where('state', Faculty::STATE_JOINED)->count(),
                        'generated_at' => now()->toISOString(),
                        'summary' => "Auto-generated {$type} report for {$year->name}",
                    ],
                    'state' => $this->rand([MinistryReport::STATE_DRAFT, MinistryReport::STATE_GENERATED]),
                ]
            );
        }
    }

    private function seedNotifications(): void
    {
        $this->command->info('  🔔 Seeding notifications...');

        $channels = ['sms', 'email', 'push'];
        $templates = [
            'Welcome to the new academic year!',
            'Exam results have been published.',
            'Fee payment reminder: Please check your invoices.',
            'School will be closed for Eid holidays.',
            'Parent-teacher meeting scheduled for next week.',
            'New assignment posted for your course.',
            'Attendance report is available.',
            'School bus schedule has been updated.',
        ];

        $students = Student::where('state', Student::STATE_ADMITTED)->limit(30)->get();

        foreach ($students as $student) {
            $count = rand(1, 4);
            for ($i = 0; $i < $count; $i++) {
                $sent = rand(1, 10) > 2;
                NotificationLog::create([
                    'channel' => $this->rand($channels),
                    'recipient' => $student->mobile ?? $student->email,
                    'body' => $this->rand($templates),
                    'state' => $sent ? NotificationLog::STATE_SENT : NotificationLog::STATE_PENDING,
                    'student_id' => $student->id,
                    'sent_at' => $sent ? now()->subDays(rand(0, 30)) : null,
                ]);
            }
        }
    }

    private function seedActivities(): void
    {
        $this->command->info('  📜 Seeding activity logs...');

        $admin = User::where('email', 'admin@edubba.test')->first();
        if (!$admin) return;

        $actions = [
            ['type' => 'created', 'body' => 'Created new student record'],
            ['type' => 'updated', 'body' => 'Updated student information'],
            ['type' => 'created', 'body' => 'Generated new invoice'],
            ['type' => 'updated', 'body' => 'Marked attendance for class session'],
            ['type' => 'created', 'body' => 'Created new exam schedule'],
            ['type' => 'updated', 'body' => 'Finalized marksheets'],
            ['type' => 'created', 'body' => 'Registered payment'],
            ['type' => 'updated', 'body' => 'Approved admission request'],
            ['type' => 'created', 'body' => 'Generated ministry report'],
            ['type' => 'updated', 'body' => 'Updated school settings'],
        ];

        $models = [Student::class, Invoice::class, Exam::class, AttendanceSheet::class, Admission::class, Faculty::class];

        for ($i = 0; $i < 50; $i++) {
            $action = $this->rand($actions);
            Activity::create([
                'subject_type' => $this->rand($models),
                'subject_id' => rand(1, 20),
                'type' => $action['type'],
                'body' => $action['body'],
                'user_id' => $admin->id,
                'changes' => ['field' => $this->rand(['name', 'state', 'amount', 'date']), 'old' => 'old_value', 'new' => 'new_value'],
            ]);
        }
    }

    private function rand(array $array): mixed
    {
        return $array[array_rand($array)];
    }

    private function printStats(): void
    {
        $this->command->newLine();
        $this->command->info('📊 Database Statistics:');
        $this->command->info("  Departments:      " . Department::count());
        $this->command->info("  Programs:         " . Program::count());
        $this->command->info("  Subjects:         " . Subject::count());
        $this->command->info("  Batches:          " . Batch::count());
        $this->command->info("  Courses:          " . Course::count());
        $this->command->info("  Classrooms:       " . Classroom::count());
        $this->command->info("  Faculty:          " . Faculty::count());
        $this->command->info("  Parents:          " . ParentModel::count());
        $this->command->info("  Students:         " . Student::count());
        $this->command->info("  Fee Structures:   " . FeeStructure::count());
        $this->command->info("  Invoices:         " . Invoice::count());
        $this->command->info("  Payments:         " . Payment::count());
        $this->command->info("  Attendance Sheets:" . AttendanceSheet::count());
        $this->command->info("  Attendance Lines: " . AttendanceLine::count());
        $this->command->info("  Exams:            " . Exam::count());
        $this->command->info("  Marksheets:       " . Marksheet::count());
        $this->command->info("  Exam Results:     " . ExamResult::count());
        $this->command->info("  Admissions:       " . Admission::count());
        $this->command->info("  Library Books:    " . LibraryBook::count());
        $this->command->info("  Library Issues:   " . LibraryIssue::count());
        $this->command->info("  Vehicles:         " . TransportVehicle::count());
        $this->command->info("  Routes:           " . TransportRoute::count());
        $this->command->info("  Hostels:          " . Hostel::count());
        $this->command->info("  Holidays:         " . Holiday::count());
        $this->command->info("  Notifications:    " . NotificationLog::count());
        $this->command->info("  Activities:       " . Activity::count());
    }
}
