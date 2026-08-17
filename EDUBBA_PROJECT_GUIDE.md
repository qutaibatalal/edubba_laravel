# دليل مشروع Edubba Laravel — شرح كامل من A إلى Z

> توثيق شامل لمشروع إعادة بناء نظام **Edubba** (نظام إدارة مدارس/تعليم/دروس خصوصية/تدريب كان يعمل على Odoo ERP) بلغة Laravel.
> يغطي هذا الملف: التصميم، البنية المعمارية، كل الموديلات والجداول، الخدمات، المراقبون (Observers)، الكونترولرات، المسارات (Routes)، المهام المجدولة، السيدر، التحقق من الصلاحيات، ومنطق العمل، بالإضافة إلى المشاكل التي وُجِدت وأُصلحت.

---

## جدول المحتويات

1. [نظرة عامة](#1-نظرة-عامة)
2. [الهدف والتصميم](#2-الهدف-والتصميم)
3. [التقنيات المستخدمة (Tech Stack)](#3-التقنيات-المستخدمة)
4. [بنية المشروع (Architecture & Directory Layout)](#4-بنية-المشروع)
5. [خريطة Odoo → Laravel](#5-خريطة-odoo--laravel)
6. [الوحدات والموديلات Modules A–K](#6-الوحدات-والموديلات)
7. [قائمة الجداول الكاملة (كل المايقريشنات)](#7-قائمة-الجداول-الكاملة)
8. [الخدمات Services](#8-الخدمات-services)
9. [المراقبون Observers](#9-المراقبون-observers)
10. [الكونترولرات Controllers (Admin + API)](#10-الكونترولرات)
11. [المسارات Routes](#11-المسارات-routes)
12. [المهام المجدولة Jobs & Scheduler](#12-المهام-المجدولة)
13. [السيدرز Seeders](#13-السيدرز)
14. [المصادقة والصلاحيات Auth & Permissions](#14-المصادقة-والصلاحيات)
15. [الموارد والبوليسي Resources & Policies](#15-الموارد-والبوليسي)
16. [منطق العمل وحالات الموديلات State Machines](#16-منطق-العمل-وحالات-الموديلات)
17. [المشاكل المكتشفة والحلول](#17-المشاكل-المكتشفة-والحلول)
18. [خطوات التشغيل (Setup & Run)](#18-خطوات-التشغيل)
19. [إحصائيات المشروع](#19-إحصائيات-المشروع)

---

## 1. نظرة عامة

مشروع **edubba_laravel** هو إعادة بناء كاملة لنظام **Edubba** التعليمي الذي كان يُطوَّر كإضافة (addons) فوق نظام **Odoo ERP**، بحيث يُبنى الآن على **Laravel 12 / PHP 8.2+** كنظام متكامل يحتوي على:

- **لوحة تحكم ويب** (Admin Panel) بتقنية Blade + Tailwind CSS v4.
- **Mobile JSON API** (معتمد على Sanctum) يخدم تطبيق الهاتف الحالي.
- **قاعدة بيانات SQLite** (مع إمكانية التبديل إلى MySQL/PostgreSQL).
- **كل وحدات النظام التعليمي**: البنية الأكاديمية، الطلاب، الكادر التدريسي، الجدول الدراسي، الامتحانات، الدروس الخصوصية، معهد التدريب، الرسوم والفواتير، المكتبة والمواصلات والسكن، تقارير الوزارة، والتواصل.

المرجع الرسمي للبناء هو ملف `LARAVEL_PORTING_GUIDE.md` (دليل نقل من Odoo إلى Laravel)، وهذا الملف الحالي يشرح **ما تم تنفيذه فعلياً في الكود**.

---

## 2. الهدف والتصميم

### 2.1 الهدف
- نقل كل موديلات Odoo (والتي يتجاوز عددها 100 موديل) إلى جداول + Eloquent Models.
- إعادة إنتاج منطق العمل: الحقول المحسوبة، آلات الحالة، القيود، والمهام المجدولة.
- إعادة بناء واجهة الـ Mobile API بالكامل وبشكل متوافق مع تطبيق الهاتف الحالي.
- توفير لوحة تحكم ويب إدارية لإدارة النظام بسهولة.

### 2.2 مبادئ التصميم
- **سجل Odoo = صف Laravel**، **موديل Odoo = Eloquent Model**، **حقل Odoo = Column**.
- الحقول المحسوبة (`@api.depends`) = Accessors أو Observers أو مهام إعادة حساب مجدولة.
- آلات الحالة = عمود `state` + دوال انتقال + Policies.
- الأرقام التسلسلية (`ir.sequence`) = جدول `sequences` + خدمة `SequenceService`.
- **كل جدول يحتوي** على: `id`، `created_at`، `updated_at`، وغالباً `active` (boolean) أو `SoftDeletes`.

### 2.3 قرارات تصميمية رئيسية
- **SoftDeletes** تُستخدم في الكيانات الهامة: الطلاب، الكادر، الفواتير، الامتحانات.
- **`active` (boolean)** تُستخدم في جداول البيانات المرجعية (subjects, programs, timings...).
- ربط المستخدمين: يوجد نوعان من المستخدمين:
  1. `users` — للموظفين/الإداريين (لوحة التحكم + Spatie Permission).
  2. `api_users` — لمستخدمي تطبيق الهاتف (طالب / ولي أمر / كادر / أدمن) مع Sanctum tokens.

---

## 3. التقنيات المستخدمة

| التقنية | الاستخدام |
|---|---|
| PHP 8.2+ | لغة التطوير |
| Laravel 12 | الإطار الرئيسي |
| SQLite | قاعدة البيانات الافتراضية (تجريبية) |
| Laravel Sanctum | مصادقة الـ Mobile API (API Tokens) |
| Spatie Laravel Permission | الأدوار والصلاحيات |
| barryvdh/laravel-dompdf | توليد PDF (الفواتير، الكشوفات، قوائم الجلوس) |
| mpdf/mpdf | توليد PDF إضافي (كشوفات النتائج) |
| Tailwind CSS v4 + Vite | واجهة لوحة التحكم |
| Laravel Tinker | اختبار سريع |

الاعتماديات كاملة في `composer.json`:
```json
"require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/sanctum": "^4.3",
    "laravel/tinker": "^2.10.1",
    "spatie/laravel-permission": "^6.25",
    "barryvdh/laravel-dompdf": "^3.1",
    "mpdf/mpdf": "*"
}
```

---

## 4. بنية المشروع

```
edubba_laravel/
├── app/
│   ├── Models/                        ← 132 موديل (كل موديل Odoo → موديل Laravel)
│   │   ├── AcademicYear, Term, Program, Department, Subject, Batch, Classroom, Course
│   │   ├── Student, ParentModel, StudentCourse, StudentExcuse, Admission, AdmissionRegister
│   │   ├── Faculty, FacultyHr, Employee
│   │   ├── WeekDay, Timing, TimeTable, TimeTableLine, ClassSession
│   │   ├── AttendanceSheet, AttendanceLine
│   │   ├── Exam, ExamType, ExamSchedule, Marksheet, MarksheetLine, ExamResult, QuestionBank
│   │   ├── ExamRoom, ExamRoomAllocation
│   │   ├── Center, Branch, Tutor, TutorAvailability, TutoringPackage, TutoringProduct
│   │   ├── StudyGroup, StudyGroupStudent, StudyGroupSession, StudyGroupAttendance
│   │   ├── Lead, LeadSource, LeadStage, Subscription, SubscriptionPayment, SubscriptionRenewal
│   │   ├── Commission, CommissionLine, TutorPerformance, Wallet, WalletTransaction
│   │   ├── Resource, ResourceBooking, AutoAssignmentRule, TutoringContract
│   │   ├── TutorPayout, TutorPayoutLine, StudentProgress, Assessment, AssessmentResult
│   │   ├── TutoringSessionFeedback, PaymentReminder, Complaint, ComplaintCategory
│   │   ├── SupportTicket, TutoringReport, TutoringDashboardConfig, TutoringSessionInvoice
│   │   ├── TrainingCourse, TrainingVenue, Trainer, TrainingCurriculum, TrainingModule
│   │   ├── TrainingEnrollment, TrainingSchedule, TrainingSession, TrainingAttendance
│   │   ├── TrainingAssessment, TrainingCertificate, TrainingMaterial, TrainingPayment, InstructorPayment
│   │   ├── FeeStructure, FeeLine, Invoice, InvoiceLine, Payment, Receipt, FeeWaiver
│   │   ├── LibraryBook, LibraryMembership, LibraryIssue, LibraryFine, LibraryReturn
│   │   ├── TransportVehicle, TransportRoute, TransportStop, TransportAssignment
│   │   ├── Hostel, HostelRoom, HostelAllocation
│   │   ├── StoreCategory, StoreProduct, StoreStock, StoreIssue, StoreRequest
│   │   ├── Alumni, Event, EventRegistration, Newsletter, IdCard, Certificate
│   │   ├── IraqiCalendar, Holiday, MinistryReport, StudentDailyAttendanceRegister
│   │   ├── ApiUser, NotificationLog, WhatsappTemplate, WhatsappMessage, FeedbackForm
│   │   ├── Feedback, FeedbackResponse, MobileAppConfig, Device, PushToken, Activity, Sequence
│   │   └── User
│   ├── Services/                      ← 17 خدمة (منطق العمل)
│   ├── Observers/                     ← 3 مراقبون
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                 ← 13 كونترولر للوحة التحكم
│   │   │   └── Api/                   ← 12 كونترولر للـ Mobile API
│   │   ├── Middleware/                ← EnsureAdmin, EnsureAdminRole
│   │   └── Resources/                 ← 25 Resource (مُنسّق البيانات)
│   ├── Policies/                      ← AdmissionPolicy, MarksheetPolicy, StudentPolicy
│   └── Jobs/                          ← 7 مهام مجدولة
├── bootstrap/app.php                  ← تسجيل الميدلوير والمسارات
├── routes/
│   ├── web.php                        ← 188 سطر مسارات لوحة التحكم
│   ├── api.php                        ← مسارات Mobile API (v1)
│   └── console.php                    ← المهام المجدولة (Schedule)
├── database/
│   ├── migrations/                    ← 23 مايقريشن
│   ├── seeders/DatabaseSeeder.php     ← 10 سيدرز فرعيين
│   └── database.sqlite                ← قاعدة البيانات
├── resources/views/                   ← 37 ملف Blade
│   ├── admin/…                        ← لوحة التحكم
│   └── pdf/…                          ← قوالب PDF
└── tests/
```

---

## 5. خريطة Odoo → Laravel

| Odoo | Laravel |
|---|---|
| `models.Model` | `extends Model` |
| `fields.Many2one` | `unsignedBigInteger FK` + `belongsTo` |
| `fields.One2many` | `hasMany` |
| `fields.Many2many` | جدول pivot + `belongsToMany` |
| `fields.Selection` | عمود `string` + ثوابت PHP + `$casts` |
| `fields.Date / Datetime` | `date` / `datetime` |
| `fields.Float / Monetary` | `decimal(12,2)` |
| `fields.Binary` | مسار ملف عبر Storage |
| `@api.depends` (computed) | `getXAttribute` / Observer / Job |
| `@api.constrains` | `boot()` / FormRequest validation |
| `active` | `SoftDeletes` أو `boolean active` |
| `ir.sequence` | جدول `sequences` + `SequenceService` |
| `state` workflow | عمود `state` + دوال تحول + ثوابت |
| `mail.thread` | جدول `activities` (polymorphic) |
| `ir.cron` | `routes/console.php` + `Schedule` |
| `ir.model.access` | `Spatie Permission` + `Policies` + Middleware |
| PDF reports (QWeb) | `barryvdh/laravel-dompdf` + `mpdf` |

---

## 6. الوحدات والموديلات

### Module A — البنية الأكاديمية (Academic Structure)
| الموديل | الجدول | علاقاته الأساسية |
|---|---|---|
| `AcademicYear` | `academic_years` | hasMany terms, batches, admissions |
| `Term` | `terms` | belongsTo academicYear |
| `Department` | `departments` | hasMany programs, subjects |
| `Program` | `programs` | belongsTo department; hasMany batches, courses |
| `Subject` | `subjects` | belongsTo department; is_language |
| `Batch` | `batches` | "Grade 6 A"; belongsTo program/academicYear; class_teacher_id |
| `Classroom` | `classrooms` | مستقل، يُشار إليه من الجدول والجلسات |
| `Course` | `courses` | "English Grade 6 A"; belongsTo subject/program/batch/year/faculty |

### Module B — الطلاب والوالدين والقبول (Students, Parents & Admission)
| الموديل | الجدول | ملاحظات |
|---|---|---|
| `Student` | `students` | `student_code` تسلسلي، حالات draft→admitted→graduated→alumni |
| `ParentModel` | `parents` | والد/ولي أمر |
| `StudentCourse` | `student_course` | pivot تسجيل الطالب في المادة |
| `StudentExcuse` | `student_excuses` | حالة pending→approved→rejected |
| `Admission` | `admissions` | طلب قبول، حالة draft→submit→approve→reject→admitted |
| `AdmissionRegister` | `admission_registers` | سجل قبول (نافذة زمنية لصف دراسي) |

### Module C — الكادر التدريسي والموارد البشرية (Faculty & HR)
| الموديل | الجدول | ملاحظات |
|---|---|---|
| `Faculty` | `faculties` | `faculty_code` تسلسلي، حالات draft→joined→left |
| `FacultyHr` | `faculty_hr` | راتب، عقد، بنك، مستندات JSON |
| `Employee` | `employees` | موظف عام (إن وُجد) |

### Module D — الجدول الدراسي والحضور (Timetable & Attendance)
| الموديل | الجدول | ملاحظات |
|---|---|---|
| `WeekDay` | `week_days` | أيام الأسبوع (بالترتيب) |
| `Timing` | `timings` | الفترات الزمنية (Period 1..3) |
| `TimeTable` | `time_tables` | جدول لصف دراسي |
| `TimeTableLine` | `time_table_lines` | سطر الجدول (يوم + فترة + مادة + كادر + قاعة) |
| `ClassSession` | `class_sessions` | جلسة فعلية، حالة planned→done→cancelled |
| `AttendanceSheet` | `attendance_sheets` | ورقة حضور، حالة draft→done |
| `AttendanceLine` | `attendance_lines` | حالة طالب: present/absent/late/leave |

### Module E — الامتحانات وكشوفات الدرجات والنتائج (Exams)
| الموديل | الجدول | ملاحظات |
|---|---|---|
| `Exam` | `exams` | حالة draft→ongoing→done→cancel |
| `ExamType` | `exam_types` | Midterm / Final / Quiz مع weight |
| `ExamSchedule` | `exam_schedules` | موعد مادة داخل امتحان |
| `Marksheet` | `marksheets` | كشف درجات لطالب في امتحان، حالة draft→done |
| `MarksheetLine` | `marksheet_lines` | درجات مادة (max/marks/pass/percentage/grade/passed) |
| `ExamResult` | `exam_results` | نتيجة مجمعة (total/average/grade/rank/result) |
| `QuestionBank` | `question_banks` | بنك أسئلة (mcq/essay/short) |
| `ExamRoom` | `exam_rooms` | قاعة امتحان |
| `ExamRoomAllocation` | `exam_room_allocations` | توزيع الطلاب على القاعات |

### Module F — مركز الدروس الخصوصية (Tutoring Center)
- `Center`, `Branch` — مراكز وفروع.
- `Tutor`, `TutorAvailability` — المدرسين الخصوصيين ومواعيدهم.
- `TutoringPackage`, `TutoringProduct` — الباقات والمنتجات.
- `StudyGroup`, `StudyGroupStudent`, `StudyGroupSession`, `StudyGroupAttendance` — المجموعات الدراسية.
- `Lead`, `LeadSource`, `LeadStage` — العملاء المحتملون.
- `Subscription`, `SubscriptionPayment`, `SubscriptionRenewal` — الاشتراكات وتجديدها.
- `Commission`, `CommissionLine` — العمولات.
- `TutorPerformance`, `TutorPayout`, `TutorPayoutLine` — أداء المدرسين ومستحقاتهم.
- `Wallet`, `WalletTransaction` — المحفظة المدفوعة مسبقاً.
- `Resource`, `ResourceBooking` — حجز الموارد/القاعات.
- `AutoAssignmentRule` — قواعد إسناد المدرسين تلقائياً.
- `TutoringContract`, `StudentProgress`, `Assessment`, `AssessmentResult`.
- `TutoringSessionFeedback`, `PaymentReminder`, `Complaint`, `ComplaintCategory`, `SupportTicket`.
- `TutoringReport`, `TutoringDashboardConfig`, `TutoringSessionInvoice`.

### Module G — معهد التدريب (Training Institute)
- `TrainingCourse`, `TrainingEnrollment`, `TrainingSession`, `TrainingAttendance`.
- `TrainingSchedule`, `TrainingMaterial`, `TrainingAssessment`, `TrainingCertificate`.
- `Trainer`, `TrainingPayment`, `InstructorPayment`, `TrainingVenue`, `TrainingCurriculum`, `TrainingModule`.

### Module H — الرسوم والفواتير والمدفوعات (Fees & Invoices)
| الموديل | الجدول | ملاحظات |
|---|---|---|
| `FeeStructure` | `fee_structures` | هيكل رسوم لصف/برنامج/سنة |
| `FeeLine` | `fee_lines` | بند الرسوم (Tuition/Transport/Books...) |
| `Invoice` | `invoices` | فاتورة، رقم تسلسلي INV/...، حالة draft→open→paid→cancel |
| `InvoiceLine` | `invoice_lines` | بنود الفاتورة |
| `Payment` | `payments` | دفع، رقم PAY/...، حالة draft→done→cancelled |
| `Receipt` | `receipts` | إيصال (PDF) |
| `FeeWaiver` | `fee_waivers` | إعفاء/خصم |

### Module I — عمليات متنوعة (Misc Ops)
- المكتبة: `LibraryBook`, `LibraryMembership`, `LibraryIssue`, `LibraryFine`, `LibraryReturn`.
- المواصلات: `TransportVehicle`, `TransportRoute`, `TransportStop`, `TransportAssignment`.
- السكن: `Hostel`, `HostelRoom`, `HostelAllocation`.
- المخزن: `StoreCategory`, `StoreProduct`, `StoreStock`, `StoreIssue`, `StoreRequest`.
- أخرى: `Alumni`, `Event`, `EventRegistration`, `Newsletter`, `IdCard`, `Certificate`.

### Module J — تقارير الوزارة والتقويم العراقي والعطل (Ministry)
- `IraqiCalendar` — تعيين التاريخ الميلادي ↔ الهجري (is_holiday).
- `Holiday` — العطل الرسمية.
- `MinistryReport` — تقارير إحصائية للوزارة (state draft→generated→submitted).
- `StudentDailyAttendanceRegister` — سجل الحضور اليومي للوزارة.

### Module K — التواصل والملاحظات والمصادقة (Comms, Feedback, Auth)
- `ApiUser` — مستخدمو التطبيق (student/parent/faculty/admin).
- `NotificationLog` — سجل الإشعارات.
- `WhatsappTemplate`, `WhatsappMessage` — قوالب ورسائل واتساب.
- `FeedbackForm`, `Feedback`, `FeedbackResponse` — استبيانات التقييم.
- `MobileAppConfig` — إعدادات التطبيق (اسم المدرسة، اللون، تفعيل الميزات).
- `Device`, `PushToken` — أجهزة الهاتف ورموز الإشعارات.
- `Activity` — سجل النشاط (مكافئ mail.thread).
- `Sequence` — عداد الأرقام التسلسلية.
- `User` — مستخدمو لوحة التحكم.

---

## 7. قائمة الجداول الكاملة

### المايقريشنات الأساسية (Laravel/Spatie)
| الملف | الجداول |
|---|---|
| `0001_01_01_000000_create_users_table` | `users`, `password_reset_tokens`, `sessions` |
| `0001_01_01_000001_create_cache_table` | `cache`, `cache_locks` |
| `0001_01_01_000002_create_jobs_table` | `jobs`, `job_batches`, `failed_jobs` |
| `2026_08_12_191438_create_permission_tables` | جداول Spatie (`roles`, `permissions`, `model_has_roles`...) |
| `2026_08_12_191439_create_personal_access_tokens_table` | `personal_access_tokens` |

### مايقريشنات الوحدات (بالترتيب التنفيذي)
| الملف | الجداول المُنشأة |
|---|---|
| `200000_create_academic_core_tables` | `academic_years`, `terms`, `programs`, `subjects` |
| `200010_create_faculties_departments_tables` | `faculties`, `departments` |
| `200020_create_batches_classrooms_courses_tables` | `batches`, `classrooms`, `courses` |
| `200030_create_student_tables` | `parents`, `students`, `student_parent`, `student_course`, `student_excuses` |
| `200040_create_admission_tables` | `admission_registers`, `admissions` |
| `200050_create_faculty_hr_tables` | `faculty_hr`, `employees` |
| `200060_create_timetable_tables` | `week_days`, `timings`, `time_tables`, `time_table_lines`, `class_sessions` |
| `200070_create_attendance_tables` | `attendance_sheets`, `attendance_lines` |
| `200080_create_exam_tables` | `exam_types`, `exams`, `exam_schedules`, `marksheets`, `marksheet_lines`, `exam_results`, `question_banks` |
| `200090_create_tutoring_core_tables` | `centers`, `branches`, `tutors`, `tutor_availabilities`, `tutoring_packages`, `tutoring_products`, `study_groups`, `study_group_students`, `study_group_sessions`, `study_group_attendances`, `lead_sources`, `lead_stages`, `leads`, `subscriptions` |
| `200100_create_tutoring_extended_tables` | `subscription_payments`, `subscription_renewals`, `commissions`, `commission_lines`, `tutor_performances`, `wallets`, `wallet_transactions`, `resources`, `resource_bookings`, `auto_assignment_rules`, `tutoring_contracts`, `tutor_payouts`, `tutor_payout_lines`, `student_progresses`, `assessments`, `assessment_results`, `tutoring_session_feedbacks`, `payment_reminders`, `complaint_categories`, `complaints`, `support_tickets`, `tutoring_reports`, `tutoring_dashboard_configs`, `tutoring_session_invoices` |
| `200110_create_training_tables` | `training_courses`, `training_venues`, `trainers`, `training_curriculums`, `training_modules`, `training_enrollments`, `training_schedules`, `training_sessions`, `training_attendances`, `training_assessments`, `training_certificates`, `training_materials`, `training_payments`, `instructor_payments` |
| `200120_create_fees_tables` | `fee_structures`, `fee_lines`, `invoices`, `invoice_lines`, `payments`, `receipts`, `fee_waivers` |
| `200130_create_ops_tables` | `library_books`, `library_memberships`, `library_issues`, `library_fines`, `library_returns`, `transport_vehicles`, `transport_routes`, `transport_stops`, `transport_assignments`, `hostels`, `hostel_rooms`, `hostel_allocations`, `store_categories`, `store_products`, `store_stocks`, `store_issues`, `store_requests`, `alumni`, `events`, `event_registrations`, `newsletters`, `id_cards`, `certificates` |
| `200140_create_ministry_tables` | `iraqi_calendars`, `holidays`, `ministry_reports`, `student_daily_attendance_registers` |
| `200150_create_comms_tables` | `api_users`, `notification_logs`, `whatsapp_templates`, `whatsapp_messages`, `feedback_forms`, `feedbacks`, `feedback_responses`, `mobile_app_configs`, `devices`, `push_tokens`, `activities`, `sequences` |
| `000001_create_exam_rooms_tables` | `exam_rooms`, `exam_room_allocations` |
| `000002_add_publish_to_exam_results` | ALTER: `exam_results` (عمود publish), `marksheets` (عمود finalized_at) |

---

## 8. الخدمات Services

### `app/Services/` — 17 خدمة

| الخدمة | الملف | المهام الرئيسية |
|---|---|---|
| `ExamService` | `ExamService.php` | `enterMarks`، `recompute`، `rankWithinBatch`، `finalize`، `aggregateResult` |
| `GradeService` | `GradeService.php` | تحويل النسبة المئوية إلى حرف تقدير (A+..F) |
| `SequenceService` | `SequenceService.php` | توليد رقم تسلسلي (مثل INV/00001) بشكل آمن داخل Transaction |
| `AdmissionService` | `AdmissionService.php` | آلة حالة القبول: submit → approve → reject → admit |
| `FeeService` | `FeeService.php` | توليد فواتير الطلاب من هيكل الرسوم، حساب المجاميع |
| `AttendanceService` | `AttendanceService.php` | تسجيل الحضور وحساب نسب الحضور |
| `TimetableService` | `TimetableService.php` | توليد الجلسات من الجداول الدراسية |
| `SubscriptionService` | `SubscriptionService.php` | دورة حياة الاشتراك وتجديده |
| `CommissionService` | `CommissionService.php` | حساب العمولات والمستحقات |
| `WalletService` | `WalletService.php` | عمليات المحفظة (خصم/إضافة) |
| `TutoringService` | `TutoringService.php` | منطق الدروس الخصوصية |
| `MinistryReportService` | `MinistryReportService.php` | تجميع بيانات تقارير الوزارة |
| `NotificationService` | `NotificationService.php` | إرسال الإشعارات |
| `ReceiptService` | `ReceiptService.php` | توليد الإيصالات |
| `PdfService` | `PdfService.php` | توليد ملفات PDF |
| `StudentLifecycleService` | `StudentLifecycleService.php` | مراحل حياة الطالب (قبول/تخرج...) |
| `ExamDistributionService` | `ExamDistributionService.php` | توزيع الطلاب على القاعات |

### تفصيل `ExamService` (الأهم — مكتشف الخطأ وأُصلح هنا)
```php
// app/Services/ExamService.php
public static function enterMarks(Marksheet $marksheet, array $data): MarksheetLine
// يُنشئ/يُحدّث خط درجة، يحسب percentage/grade/passed ثم recompute للماركشيت.

public static function recompute(Marksheet $marksheet): Marksheet
// يجمع total_marks, obtained_marks, percentage, grade, result ثم save() ثم rankWithinBatch().

public static function rankWithinBatch(Marksheet $marksheet): void
// يرتب كشوفات نفس الامتحان+الصف تنازلياً حسب percentage ويحفظ rank.

public static function finalize(Marksheet $marksheet): Marksheet
// يرفض إن لم يكن draft (DomainException)، ثم recompute، state=done، finalized_at=now، save، ثم aggregateResult.

public static function aggregateResult(Marksheet $marksheet): ExamResult
// يجمع نتائج الطالب في السنة الدراسية وينشئ/يحدث ExamResult عبر updateOrCreate.
```

### `SequenceService` — كيف يعمل
```php
public static function next(string $name, ?string $prefix = null): string
// داخل DB::transaction: يقرأ عداد الاسم، يعيد الرقم الحالي، يزيده بـ1،
// ثم يخرج بصيغة: prefix + '/' + padded number  → "INV/00001"
```

### `GradeService` — مقياس التقدير
```
>=90 A+  |  >=85 A  |  >=80 A-  |  >=75 B+  |  >=70 B  |  >=65 B-
>=60 C+  |  >=55 C  |  >=50 C-  |  >=45 D+  |  >=40 D  |  <40 F
```

---

## 9. المراقبون Observers

### 1. `MarksheetObserver` — تم إصلاحه (انظر القسم 17)
```php
class MarksheetObserver {
    protected static bool $recomputing = false;

    public function created(Marksheet $marksheet): void
    // إذا وُجدت أسطر → ExamService::recompute (للحساب الأولي عند الإنشاء).

    public function updated(Marksheet $marksheet): void
    // إذا wasChanged('state') && state === 'done' → recompute،
    // لكن مع حارس إعادة الدخول static $recomputing لمنع الحلقة اللانهائية.
}
```
**التسجيل:** `AppServiceProvider::boot()` → `Marksheet::observe(MarksheetObserver::class);`

### 2. `InvoiceObserver`
```php
public function paymentCreated(Payment $payment): void
// عند دفع مكتمل مرتبط بفاتورة:
// paid = مجموع المدفوعات المكتملة، balance = max(0, total - paid)،
// إن balance<=0 → state=paid ثم حفظ الفاتورة.
```
**التسجيل:** ربط عبر الأحداث:
```php
Payment::created(fn ($p) => $observer->paymentCreated($p));
Payment::updated(fn ($p) => $observer->paymentCreated($p));
```

### 3. `LibraryObserver`
```php
issueCreated  → إن state=issued → decrement available_qty
issueUpdated  → إن كان التغيير في state إلى returned → increment available_qty
```
**التسجيل:** `LibraryIssue::observe(LibraryObserver::class);`

---

## 10. الكونترولرات

### Admin Panel (13 كونترولر) — `app/Http/Controllers/Admin/`
| الكونترولر | الوظائف |
|---|---|
| `AuthController` | تسجيل دخول/خروج الإداري (login form) |
| `DashboardController` | لوحة التحكم الرئيسية |
| `StudentController` | CRUD الطلاب + عرض ملف الطالب |
| `AdmissionController` | قبول الطلاب + إجراءات submit/approve/reject/admit |
| `ParentController` | CRUD أولياء الأمور |
| `FacultyController` | CRUD الكادر التدريسي |
| `SchoolController` | إدارة السنة الدراسية/الترم/البرامج/الشعب/المواد |
| `CourseController` | إدارة المواد الدراسية |
| `FeeController` | هياكل الرسوم + الفواتير |
| `TimetableController` | الجداول الدراسية |
| `TutoringController` | إدارة الدروس الخصوصية |
| `ReportController` | التقارير |
| `ExamController` | إدارة الامتحانات وكشوف الدرجات والنتائج |
| `SettingController` | الإعدادات |

### Mobile API (12 كونترولر) — `app/Http/Controllers/Api/`
| الكونترولر | أبرز الدوال |
|---|---|
| `AuthController` | `login`, `logout`, `profile`, `changePassword` |
| `StudentController` | `dashboard, profile, courses, timetable, attendance, exams, results, fees, payments, excuses, requestExcuse` |
| `ParentController` | `children, childAttendance, childResults, childFees` |
| `FacultyController` | `dashboard, courses, batchStudents, timetable, exams, myGroups` |
| `AttendanceController` | `mark` (تسجيل حضور جلسة) |
| `ExamController` | `enterMarks`, `finalize` |
| `TutoringController` | `subscriptions, groups, groupSessions, markSession, wallet, leads, myGroups` |
| `AdmissionController` | `index, store, submit, approve, reject, admit` |
| `AdminController` | `feeStructures, storeFeeStructure, generateInvoices, overdueInvoices, ministryReports, generateMinistryReport, generateSessions, renewSubscription, sendAbsenceAlerts` |
| `CommonController` | `appConfig, academicYears` |
| `NotificationController` | `index` |
| `FeedbackController` | `forms, submit` |

### عقد الاستجابة الموحد للـ Mobile API
كل استجابة تتبع النمط: `{ status, message, data }`
```json
{ "status": "success", "message": "Login successful", "data": { "token": "...", "user": {...} } }
```

---

## 11. المسارات Routes

### `routes/api.php` — Mobile API (v1)
```
POST /v1/login                    → AuthController@login       (عام)
POST /v1/logout                   → AuthController@logout      (عام)
GET  /v1/config                   → CommonController@appConfig (عام)

أما الباقي فيتطلب auth:sanctum:
- /v1/profile, /v1/change-password
- /v1/student/*        (dashboard, profile, courses, timetable, attendance, exams, results, fees, payments, excuses)
- /v1/parent/children, /v1/parent/child/{id}/{attendance|results|fees}
- /v1/faculty/*        (dashboard, courses, batch/{id}/students, timetable, exams, study-groups)
- /v1/faculty/session/{id}/attendance  (POST)
- /v1/faculty/marksheet/{id}/line      (POST — enterMarks)
- /v1/faculty/marksheet/{id}/finalize  (POST)
- /v1/tutoring/*       (subscriptions, groups, group/{id}/sessions, session/{id}/attendance, wallet, leads)
- /v1/academic-years, /v1/notifications, /v1/feedback/forms, /v1/feedback/submit
- /v1/admin/*          (محجوب بـ middleware: role.admin)
    - admissions CRUD + submit/approve/reject/admit
    - fee-structures + generate-invoices + invoices/overdue
    - ministry-reports + generate
    - timetable/generate, subscriptions/{id}/renew, notifications/absence-alerts
```

### `routes/web.php` — لوحة التحكم (188 سطر)
```
GET  /                      → redirect إلى admin.login
GET  /admin/login           → نموذج تسجيل الدخول
POST /admin/login           → تسجيل الدخول
POST /admin/logout          → تسجيل الخروج

/admin  (middleware: auth + admin.web)
- GET  /                    → لوحة التحكم
- resources: students, parents, faculty, courses
- admissions: index/create/store + submit/approve/reject/admit
- school: years, programs, batches, subjects
- fees: structures, invoices
- timetable: index
- tutoring: index
- exams: index, show, marksheet, results + finalize + publish
- reports, settings
```

### `routes/console.php` — المهام المجدولة
```php
GenerateSessionsJob::class     → يومياً 01:00
AttendanceAggregationJob       → يومياً 02:00
SubscriptionRenewalJob         → يومياً 03:00
AbsenceNotificationJob         → يومياً 06:00
CommissionPayoutJob            → كل ساعة
FeeInvoiceJob                  → أول كل شهر 02:00
MinistryReportJob              → يومياً 23:00
```

---

## 12. المهام المجدولة

`app/Jobs/` — 7 مهام:
| المهمة | الغرض |
|---|---|
| `GenerateSessionsJob` | توليد جلسات اليوم من جداول المواعيد |
| `AttendanceAggregationJob` | إنشاء أوراق حضور + حساب نسب الحضور |
| `SubscriptionRenewalJob` | تجديد الاشتراكات + إشعارات الدفع |
| `AbsenceNotificationJob` | إشعارات الغياب/التأخر |
| `CommissionPayoutJob` | إعادة حساب العمولات والمستحقات |
| `FeeInvoiceJob` | توليد فواتير الرسوم في بداية الترم |
| `MinistryReportJob` | تجميع بيانات تقارير الوزارة ليلاً |

---

## 13. السيدرز

`database/seeders/DatabaseSeeder.php` يستدعي 10 سيدرز فرعيين:

| السيدر | المحتوى |
|---|---|
| `ReferenceDataSeeder` | 9 عدادات تسلسلية (student_code=STU, invoice=INV, payment=PAY...) + إعدادات التطبيق الافتراضية |
| `AcademicYearSeeder` | السنة 2025-2026 + الترم الأول والثاني |
| `DepartmentProgramSeeder` | قسم Languages + برنامج Primary + صف Grade 6 A |
| `SubjectCourseSeeder` | مادة English + المادة "English Grade 6 A" |
| `WeekDaySeeder` | أيام الأسبوع السبعة |
| `TimingSeeder` | 3 فترات دراسية |
| `RolePermissionSeeder` | 12 صلاحية + 4 أدوار (admin/faculty/student/parent) + ربط كل الصلاحيات بالأدمن |
| `AdminUserSeeder` | مستخدم admin@edubba.test + كادر FAC0001 + مستخدم faculty@edubba.test |
| `ApiUserSeeder` | (فارغ — تُنشأ الحسابات في DemoDataSeeder) |
| `DemoDataSeeder` | ولي أمر + طالب STU00001 + 4 حسابات API (student1/parent1/faculty1/admin1 بكلمة password) + ربط الكادر بالمادة + نموذج تقييم |

**حسابات الدخول التجريبية:**
| النظام | اسم المستخدم | كلمة المرور |
|---|---|---|
| لوحة التحكم | admin@edubba.test | password |
| Mobile API | admin1 / student1 / parent1 / faculty1 | password |

---

## 14. المصادقة والصلاحيات

### مساران منفصلان للمصادقة
1. **لوحة التحكم (Web Session):**
   - جدول `users` + مصادقة Laravel `Auth`.
   - أدوار Spatie (`admin`, `faculty`, ...).
   - Middleware `EnsureAdmin` يتأكد من `hasRole('admin')` (يُستخدم كـ `admin.web`).
   - تسجيل الدخول → `AuthController@login` في `Admin`.

2. **Mobile API (Sanctum Tokens):**
   - جدول `api_users` (نموذج `ApiUser` مع `HasApiTokens`).
   - `login` → يتحقق من `username/password/active` → يرجع `plainTextToken`.
   - Middleware `EnsureAdminRole` يتأكد من `role === 'admin'` (يُستخدم كـ `role.admin`).
   - أدوار ApiUser: `student`, `parent`, `faculty`, `admin` (دوال `linkedEntity()` تعيد الكائن المرتبط).

### التسجيل في `bootstrap/app.php`
```php
$middleware->alias([
    'role.admin' => EnsureAdminRole::class,   // Mobile API
    'admin.web'  => EnsureAdmin::class,       // Web panel
]);
```

---

## 15. الموارد والبوليسي

### Resources (25 ملف) — `app/Http/Resources/`
`AdmissionResource`, `AttendanceResource`, `AttendanceSheetResource`, `ClassSessionResource`, `CourseResource`, `ExamResource`, `ExamScheduleResource`, `FacultyResource`, `InvoiceResource`, `InvoiceLineResource`, `LeadResource`, `MarksheetResource`, `MarksheetLineResource`, `MinistryReportResource`, `ParentResource`, `PaymentResource`, `StudentExcuseResource`, `StudentResource`, `StudyGroupResource`, `StudyGroupAttendanceResource`, `StudyGroupSessionResource`, `SubscriptionResource`, `WalletResource`, `WalletTransactionResource`.

### Policies (3 ملف) — `app/Policies/`
- `AdmissionPolicy` — التحكم بصلاحيات عمليات القبول.
- `MarksheetPolicy` — التحكم بإدخال/اعتماد كشوف الدرجات.
- `StudentPolicy` — التحكم ببيانات الطلاب.

### Middleware (ملفان)
- `EnsureAdmin` — لوحة التحكم (الدور admin).
- `EnsureAdminRole` — الـ Mobile API (الدور admin في api_users).

---

## 16. منطق العمل وحالات الموديلات

### آلة الحالة للقبول (Admission)
```
draft → submit → approve → admit
                ↘ reject
```
- `AdmissionService::submit` يرفض إن لم تكن draft.
- `approve` يسمح من submit أو draft.
- `admit` يُنشئ الطالب + سجلات الطالب/المادة + roll_no.

### آلة الحالة للامتحان (Exam)
```
draft → ongoing → done → cancel
```

### آلة الحالة للماركشيت (Marksheet)
```
draft → done
```
- `finalize` يرفض (DomainException) إن لم تكن draft.
- عند done: recompute → rank → aggregateResult → إنشاء/تحديث ExamResult.

### آلة الحالة للطالب
```
draft → admitted → graduated → alumni
```

### آلة الحالة للكادر (Faculty)
```
draft → joined → left
```

### آلة الحالة للفواتير
```
draft → open → paid → cancel
```
- عند دفع يكتمل المبلغ → `paid` تلقائياً عبر `InvoiceObserver`.

### آلة الحالة للجلسة الدراسية
```
planned → done → cancelled
```

### آلة الحالة للحضور
```
draft → done   (خطوط الحضور: present/absent/late/leave)
```

### آلة الحالة للاشتراك (Subscription)
```
draft → active → paused → expired → cancelled
```

---

## 17. المشاكل المكتشفة والحلول

### المشكلة: حلقة لا نهائية عند اعتماد كشف الدرجات (`ExamService::finalize`)

**السيناريو:** عند استدعاء `ExamService::finalize` على الماركشيت رقم 9:
- سُجِّل في التتبع (query log) **48,548 استعلام متطابق**:
  ```sql
  update "marksheets" set "state" = ?, "finalized_at" = ?, "updated_at" = ? where "id" = ?
  ```
- استمرت الحلقة حتى نفاد الذاكرة (memory exhausted).

**السبب الجذري (مؤكد بالـ Backtrace):**
```
ExamService::finalize (ExamService.php:92)  ← state=done + save()
  → Eloquent fires `updated`
    → MarksheetObserver::updated (MarksheetObserver.php:26)
      → ExamService::recompute (MarksheetObserver.php:26)
        → $marksheet->save() (ExamService.php:55)
          → fires `updated` مرة أخرى
            → observer → recompute → save → ... (حلقة لا نهائية)
```
السبب التقني الدقيق: دالة `recompute()` تستدعي `$marksheet->save()` **بينما ما زلنا داخل حدث `updated`** الذي أُطلق للتو من `finalize()` — و Eloquent لم يُنفّذ `syncOriginal()` بعد، لذلك:
- `wasChanged('state')` يبقى `true`.
- `state` و `finalized_at` ما زالا في قائمة `getDirty()`.
- كل `save()` جديد يعيد إطلاق `updated` من جديد → حلقة لا تنتهي.

**الحل:** حارس إعادة الدخول (`re-entrancy guard`) في `MarksheetObserver`:
```php
protected static bool $recomputing = false;

public function updated(Marksheet $marksheet): void
{
    if (self::$recomputing) {
        return;
    }

    if ($marksheet->wasChanged('state') && $marksheet->state === Marksheet::STATE_DONE) {
        self::$recomputing = true;
        try {
            ExamService::recompute($marksheet);
        } finally {
            self::$recomputing = false;
        }
    }
}
```

**التحقق بعد الإصلاح:**
- `ExamService::finalize` يكتمل بنجاح → `state=done`.
- عدد الاستعلامات أصبح 2 فقط (حفظ finalize + إعادة حساب واحدة مشروعة من الـ observer).
- `php artisan test` → 2 tests ناجحة.
- `exam_results` يُنشأ بشكل صحيح.

**ملاحظة إضافية:** مشكلة مشابهة يجب الانتباه لها عند أي Observer يستدعي `save()` داخل أحداث `updated` — الحل القياسي هو إما الحارس أعلاه أو التحقق من `wasChanged()` قبل القفز.

---

## 18. خطوات التشغيل

```bash
# 1. تثبيت الاعتماديات
composer install

# 2. إعداد البيئة
copy .env.example .env
php artisan key:generate

# 3. قاعدة البيانات (SQLite افتراضياً)
# تأكد من وجود database/database.sqlite ثم:
php artisan migrate

# 4. البيانات التجريبية
php artisan db:seed

# 5. تشغيل الخادم
php artisan serve
# لوحة التحكم: http://127.0.0.1:8000/admin/login
# Mobile API: http://127.0.0.1:8000/api/v1/login

# 6. (اختياري) واجهة Vite للتطوير
npm install
npm run dev
```

### اختبار سريع للـ API
```bash
curl -X POST http://127.0.0.1:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"username":"student1","password":"password"}'
```

---

## 19. إحصائيات المشروع

| العنصر | العدد |
|---|---|
| الموديلات (Models) | 132 |
| المايقريشنات (Migrations) | 23 |
| الجداول المنشأة | ~140+ جدول |
| الكونترولرات (Admin + Api) | 27 |
| الخدمات (Services) | 17 |
| المراقبون (Observers) | 3 |
| البوليسي (Policies) | 3 |
| المهام المجدولة (Jobs) | 7 |
| الموارد (API Resources) | 25 |
| ملفات Blade | 37 |
| السيدرز | 10 سيدرز فرعيين |
| مستخدمو الـ API التجريبيون | 4 (admin1/student1/parent1/faculty1) |

---

> **المصدر الرئيسي:** `LARAVEL_PORTING_GUIDE.md` — دليل النقل الرسمي من Odoo إلى Laravel.
> **وثيقة الشرح الشامل الحالية:** `EDUBBA_PROJECT_GUIDE.md`
