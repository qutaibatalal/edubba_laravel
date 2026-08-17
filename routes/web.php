<?php

use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AttendancePdfController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\HostelController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TransportController;
use App\Http\Controllers\Admin\TutoringController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Models\MobileAppConfig;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// ---- Admin auth ----
Route::get('/admin/login', [AuthController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// ---- Language switcher ----
Route::get('/language/{locale}', function (string $locale) {
    if (! in_array($locale, ['ar', 'en'], true)) {
        $locale = 'ar';
    }

    session(['locale' => $locale]);
    app()->setLocale($locale);

    return redirect()->back();
})->name('language.switch');

// ---- Admin 2FA (reachable before a full session is established) ----
Route::get('/admin/2fa', [TwoFactorController::class, 'show'])->name('admin.2fa.form');
Route::post('/admin/2fa', [TwoFactorController::class, 'verify'])->name('admin.2fa.verify');
Route::post('/admin/2fa/resend', [TwoFactorController::class, 'resend'])->name('admin.2fa.resend');

// ---- Admin panel (web session guard + admin role) ----
Route::prefix('admin')->middleware(['auth', 'admin.web'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Students
    Route::resource('students', StudentController::class)->names('admin.students');
    Route::get('students/{student}/card', [StudentController::class, 'downloadStudentCard'])->name('admin.students.card');
    Route::get('students/{student}/certificate', [StudentController::class, 'downloadEnrollmentCertificate'])->name('admin.students.certificate');

    // Admissions
    Route::get('admissions', [AdmissionController::class, 'index'])->name('admin.admissions.index');
    Route::get('admissions/create', [AdmissionController::class, 'create'])->name('admin.admissions.create');
    Route::post('admissions', [AdmissionController::class, 'store'])->name('admin.admissions.store');
    Route::post('admissions/{admission}/submit', [AdmissionController::class, 'submit'])->name('admin.admissions.submit');
    Route::post('admissions/{admission}/approve', [AdmissionController::class, 'approve'])->name('admin.admissions.approve');
    Route::post('admissions/{admission}/reject', [AdmissionController::class, 'reject'])->name('admin.admissions.reject');
    Route::post('admissions/{admission}/admit', [AdmissionController::class, 'admit'])->name('admin.admissions.admit');

    // Parents
    Route::resource('parents', ParentController::class)->except('show')->names('admin.parents');

    // Faculty
    Route::resource('faculty', FacultyController::class)->except('show')->names('admin.faculty');
    Route::get('faculty/{member}', [FacultyController::class, 'show'])->name('admin.faculty.show');
    Route::get('faculty/{member}/card', [FacultyController::class, 'downloadIdCard'])->name('admin.faculty.card');

    // School structure
    Route::get('batches', [SchoolController::class, 'batchesIndex'])->name('admin.batches.index');
    Route::get('batches/create', [SchoolController::class, 'batchesCreate'])->name('admin.batches.create');
    Route::post('batches', [SchoolController::class, 'batchesStore'])->name('admin.batches.store');
    Route::get('batches/{batch}/edit', [SchoolController::class, 'batchesEdit'])->name('admin.batches.edit');
    Route::put('batches/{batch}', [SchoolController::class, 'batchesUpdate'])->name('admin.batches.update');
    Route::delete('batches/{batch}', [SchoolController::class, 'batchesDestroy'])->name('admin.batches.destroy');

    Route::get('programs', [SchoolController::class, 'programsIndex'])->name('admin.programs.index');
    Route::get('programs/create', [SchoolController::class, 'programsCreate'])->name('admin.programs.create');
    Route::post('programs', [SchoolController::class, 'programsStore'])->name('admin.programs.store');
    Route::get('programs/{program}/edit', [SchoolController::class, 'programsEdit'])->name('admin.programs.edit');
    Route::put('programs/{program}', [SchoolController::class, 'programsUpdate'])->name('admin.programs.update');
    Route::delete('programs/{program}', [SchoolController::class, 'programsDestroy'])->name('admin.programs.destroy');

    Route::get('academic-years', [SchoolController::class, 'yearsIndex'])->name('admin.academic-years.index');
    Route::get('academic-years/create', [SchoolController::class, 'yearsCreate'])->name('admin.academic-years.create');
    Route::post('academic-years', [SchoolController::class, 'yearsStore'])->name('admin.academic-years.store');
    Route::get('academic-years/{year}/edit', [SchoolController::class, 'yearsEdit'])->name('admin.academic-years.edit');
    Route::put('academic-years/{year}', [SchoolController::class, 'yearsUpdate'])->name('admin.academic-years.update');
    Route::delete('academic-years/{year}', [SchoolController::class, 'yearsDestroy'])->name('admin.academic-years.destroy');

    // Courses
    Route::resource('courses', CourseController::class)->except('show')->names('admin.courses');

    // Fees
    Route::get('fees/structures', [FeeController::class, 'structures'])->name('admin.fees.structures');
    Route::get('fees/structures/create', [FeeController::class, 'structureCreate'])->name('admin.fees.structures.create');
    Route::post('fees/structures', [FeeController::class, 'structureStore'])->name('admin.fees.structures.store');
    Route::post('fees/structures/{fee_structure}/generate', [FeeController::class, 'generateInvoices'])->name('admin.fees.structures.generate');
    Route::get('fees/invoices', [FeeController::class, 'invoices'])->name('admin.fees.invoices');
    Route::post('fees/invoices/{invoice}/pay', [FeeController::class, 'registerPayment'])->name('admin.fees.invoices.pay');
    Route::get('fees/invoices/{invoice}/pdf', [FeeController::class, 'invoicePdf'])->name('admin.fees.invoices.pdf');
    Route::get('fees/receipts/{receipt}/pdf', [FeeController::class, 'receiptPdf'])->name('admin.fees.receipts.pdf');

    // Timetable
    Route::get('timetable', [TimetableController::class, 'index'])->name('admin.timetable.index');
    Route::post('timetable/generate', [TimetableController::class, 'generate'])->name('admin.timetable.generate');

    // Attendance
    Route::get('attendance', [AttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::get('attendance/monthly', [AttendanceController::class, 'monthly'])->name('admin.attendance.monthly');
    Route::get('attendance/{sheet}/edit', [AttendanceController::class, 'edit'])->name('admin.attendance.edit');
    Route::post('attendance/{sheet}/mark', [AttendanceController::class, 'mark'])->name('admin.attendance.mark');

    Route::get('hostels', [HostelController::class, 'index'])->name('admin.hostels.index');
    Route::get('hostels/create', [HostelController::class, 'create'])->name('admin.hostel.create');
    Route::post('hostels', [HostelController::class, 'store'])->name('admin.hostel.store');

    Route::get('transport', [TransportController::class, 'index'])->name('admin.transport.index');
    Route::get('transport/create_vehicle', [TransportController::class, 'create'])->name('admin.transport.create_vehicle');
    Route::post('transport/vehicle', [TransportController::class, 'storeVehicle'])->name('admin.transport.store_vehicle');
    Route::post('transport/route', [TransportController::class, 'storeRoute'])->name('admin.transport.store_route');

    Route::get('attendance/pdf', [AttendancePdfController::class, 'show'])->name('admin.attendance.pdf');
    Route::get('attendance/pdf/download', [AttendancePdfController::class, 'download'])->name('admin.attendance.pdf.download');

    // Iraqi calendar & holidays
    Route::get('calendar', [CalendarController::class, 'index'])->name('admin.calendar.index');
    Route::post('calendar/iraqi', [CalendarController::class, 'storeIraqi'])->name('admin.calendar.store-iraqi');
    Route::post('calendar/holidays', [CalendarController::class, 'storeHoliday'])->name('admin.calendar.store-holiday');
    Route::delete('calendar/holidays/{holiday}', [CalendarController::class, 'destroyHoliday'])->name('admin.calendar.destroy-holiday');

    // Tutoring
    Route::get('tutoring', [TutoringController::class, 'index'])->name('admin.tutoring.index');

    // Exams
    Route::get('exams', [ExamController::class, 'index'])->name('admin.exams.index');
    Route::post('exams', [ExamController::class, 'store'])->name('admin.exams.store');
    Route::get('exams/{exam}', [ExamController::class, 'show'])->name('admin.exams.show');
    Route::post('exams/{exam}/schedule', [ExamController::class, 'scheduleStore'])->name('admin.exams.schedule.store');
    Route::delete('exams/{exam}/schedule/{schedule}', [ExamController::class, 'scheduleDestroy'])->name('admin.exams.schedule.destroy');
    Route::post('exams/{exam}/distribute', [ExamController::class, 'distribute'])->name('admin.exams.distribute');
    Route::post('exams/{exam}/held', [ExamController::class, 'held'])->name('admin.exams.held');
    Route::get('exams/{exam}/seating/{schedule?}', [ExamController::class, 'seatingPdf'])->name('admin.exams.seating.pdf');
    Route::get('exams/{exam}/marksheets', [ExamController::class, 'marksheets'])->name('admin.exams.marksheets');
    Route::post('exams/{exam}/marksheets/generate', [ExamController::class, 'marksheetsGenerate'])->name('admin.exams.marksheets.generate');
    Route::get('exams/{exam}/marksheets/{marksheet}', [ExamController::class, 'marksheet'])->name('admin.exams.marksheet');
    Route::post('exams/{exam}/marksheets/{marksheet}', [ExamController::class, 'marksheetStore'])->name('admin.exams.marksheet.store');
    Route::post('exams/{exam}/marksheets/{marksheet}/finalize', [ExamController::class, 'marksheetFinalize'])->name('admin.exams.marksheet.finalize');
    Route::post('exams/{exam}/marksheets/finalize-all', [ExamController::class, 'marksheetsFinalizeAll'])->name('admin.exams.marksheets.finalize-all');
    Route::get('exams/{exam}/results', [ExamController::class, 'results'])->name('admin.exams.results');
    Route::post('exams/{exam}/results/publish', [ExamController::class, 'resultsPublish'])->name('admin.exams.results.publish');
    Route::post('exams/{exam}/results/share', [ExamController::class, 'resultsShare'])->name('admin.exams.results.share');
    Route::get('exams/{exam}/results/card/{student}', [ExamController::class, 'resultCardPdf'])->name('admin.exams.result.card');
    Route::get('exams/{exam}/results/pdf', [ExamController::class, 'resultsPdf'])->name('admin.exams.results.pdf');
    Route::post('exams/rooms', [ExamController::class, 'roomStore'])->name('admin.exams.rooms.store');
    Route::put('exams/rooms/{room}', [ExamController::class, 'roomUpdate'])->name('admin.exams.rooms.update');
    Route::delete('exams/rooms/{room}', [ExamController::class, 'roomDestroy'])->name('admin.exams.rooms.destroy');

    // Ministry reports
    Route::get('reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::post('reports/generate', [ReportController::class, 'generate'])->name('admin.reports.generate');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // Two-factor auth management
    Route::post('settings/2fa/enable', [TwoFactorController::class, 'enable'])->name('admin.settings.2fa.enable');
    Route::post('settings/2fa/disable', [TwoFactorController::class, 'disable'])->name('admin.settings.2fa.disable');
});

// ---- PWA ----
// Manifest is generated dynamically so the mobile app reflects the
// school branding stored in mobile_app_configs.
Route::get('/manifest.webmanifest', function () {
    $configs = MobileAppConfig::where('active', true)->get();

    $data = [];
    foreach ($configs as $config) {
        $data[$config->config_key] = $config->value;
    }

    $school = $data['school_name'] ?? ['en' => 'Edubba School', 'ar' => 'مدرسة إدبة'];
    $color = $data['primary_color'] ?? '#1e40af';

    return response()->json([
        'name' => is_array($school) ? ($school['en'] ?? 'Edubba School') : $school,
        'short_name' => 'Edubba',
        'lang' => 'en',
        'start_url' => '/',
        'scope' => '/',
        'display' => 'standalone',
        'orientation' => 'portrait',
        'background_color' => '#ffffff',
        'theme_color' => $color,
        'description' => 'School management mobile app',
        'icons' => [
            [
                'src' => '/images/edubba_app_icon.png',
                'sizes' => '1024x1024',
                'type' => 'image/png',
                'purpose' => 'any',
            ],
            [
                'src' => '/images/edubba_app_icon.png',
                'sizes' => '1024x1024',
                'type' => 'image/png',
                'purpose' => 'maskable',
            ],
            [
                'src' => '/icons/icon.svg',
                'sizes' => 'any',
                'type' => 'image/svg+xml',
                'purpose' => 'any',
            ],
        ],
    ]);
});

// Service worker: cache-first app shell with a notification-driven update flow.
Route::get('/serviceworker.js', function () {
    $sw = <<<'SW'
const CACHE = 'edubba-v1';
const SHELL = ['/', '/manifest.webmanifest'];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE).then((cache) => cache.addAll(SHELL)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
    ).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(() => caches.match('/'))
    );
    return;
  }
  event.respondWith(
    caches.match(event.request).then(
      (hit) => hit || fetch(event.request).then((resp) => {
        const copy = resp.clone();
        caches.open(CACHE).then((cache) => cache.put(event.request, copy));
        return resp;
      })
    )
  );
});

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') self.skipWaiting();
});
SW;

    return response($sw, 200, [
        'Content-Type' => 'text/javascript; charset=UTF-8',
        'Cache-Control' => 'no-cache',
        'Service-Worker-Allowed' => '/',
    ]);
});
