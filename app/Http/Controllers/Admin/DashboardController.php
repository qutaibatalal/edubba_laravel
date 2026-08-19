<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Admission;
use App\Models\AttendanceSheet;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Faculty;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = cache()->remember('edubba:dashboard:stats', 60, function () {
            return [
                'students' => Student::admitted()->count(),
                'faculty' => Faculty::count(),
                'batches' => Batch::count(),
                'courses' => Course::count(),
                'admissions_pending' => Admission::whereIn('state', [Admission::STATE_DRAFT, Admission::STATE_SUBMIT])->count(),
                'invoices_open' => Invoice::where('state', Invoice::STATE_OPEN)->count(),
                'invoices_balance' => (float) Invoice::where('state', '!=', Invoice::STATE_PAID)->sum('balance'),
                'payments_today' => (float) Payment::whereDate('created_at', today())->sum('amount'),
            ];
        });

        $year = AcademicYear::where('current', true)->first();
        $yearId = $year?->id;

        // Students per batch (current year)
        $perBatch = cache()->remember('edubba:dashboard:perbatch', 120, function () use ($yearId) {
            return Student::admitted()
                ->when($yearId, fn ($q) => $q->where('academic_year_id', $yearId))
                ->select('batch_id', DB::raw('count(*) as total'))
                ->with('batch')
                ->groupBy('batch_id')
                ->orderByDesc('total')
                ->get()
                ->map(fn ($row) => ['batch' => $row->batch?->name ?? '—', 'total' => $row->total]);
        });

        // Recent invoices
        $recentInvoices = cache()->remember('edubba:dashboard:invoices', 120, fn () =>
            Invoice::with('student')->orderByDesc('id')->limit(6)->get()
        );

        // Recent admissions
        $recentAdmissions = cache()->remember('edubba:dashboard:admissions', 120, fn () =>
            Admission::with('batch')->orderByDesc('id')->limit(6)->get()
        );

        $alerts = cache()->remember('edubba:dashboard:alerts', 120, function () use ($yearId) {
            $alerts = collect();

            $overdue = Invoice::where('state', Invoice::STATE_OPEN)
                ->where('balance', '>', 0)
                ->where('due_date', '<', today())
                ->with('student')
                ->orderByDesc('due_date')
                ->limit(5)
                ->get();
            foreach ($overdue as $inv) {
                $alerts->push((object) [
                    'level' => 'danger',
                    'icon' => 'bi-exclamation-triangle',
                    'title' => 'فاتورة متأخرة',
                    'text' => "{$inv->student?->full_name} — {$inv->number} (متبقي ".number_format($inv->balance).')',
                    'href' => route('admin.fees.invoices'),
                ]);
            }

            $upcomingExams = Exam::with('examType')
                ->where('exam_date', '>=', today())
                ->orderBy('exam_date')
                ->limit(4)
                ->get();
            foreach ($upcomingExams as $exam) {
                $alerts->push((object) [
                    'level' => 'info',
                    'icon' => 'bi-journal-text',
                    'title' => 'امتحان قادم',
                    'text' => "{$exam->title} — ".$exam->exam_date?->format('d/m/Y'),
                    'href' => route('admin.exams.show', $exam),
                ]);
            }

            return $alerts;
        });

        // Low attendance — use DB aggregate instead of loading all students
        $lowAttendanceIds = DB::table('attendance_lines')
            ->select('student_id', DB::raw("COUNT(CASE WHEN status = 'present' THEN 1 END) as present_count"), DB::raw('COUNT(*) as total'))
            ->groupBy('student_id')
            ->havingRaw('present_count * 100.0 / total < 75')
            ->orderByRaw('present_count * 100.0 / total')
            ->limit(5)
            ->pluck('student_id');

        $lowAttendanceStudents = Student::whereIn('id', $lowAttendanceIds)
            ->with('batch')
            ->get()
            ->map(fn ($s) => (object) [
                'level' => 'warning',
                'icon' => 'bi-person-exclamation',
                'title' => 'حضور منخفض',
                'text' => $s->full_name,
                'href' => route('admin.students.show', $s),
            ]);

        $alerts = $alerts->merge($lowAttendanceStudents);

        // Recent activity (today's sheets + payments) — cached 2 min
        $activity = cache()->remember('edubba:dashboard:activity', 120, function () {
            $activity = collect();

            AttendanceSheet::whereDate('created_at', today())
                ->with(['batch', 'course'])
                ->orderByDesc('id')
                ->limit(4)
                ->get()
                ->each(fn ($s) => $activity->push((object) [
                    'icon' => 'bi-clipboard2-check',
                    'color' => 'success',
                    'text' => "تسجيل حضور {$s->batch?->name} — {$s->course?->name}",
                    'time' => $s->created_at,
                ]));

            Payment::whereDate('created_at', today())
                ->with('student')
                ->orderByDesc('id')
                ->limit(4)
                ->get()
                ->each(fn ($p) => $activity->push((object) [
                    'icon' => 'bi-cash-coin',
                    'color' => 'primary',
                    'text' => "دفعة {$p->student?->full_name} — ".number_format($p->amount),
                    'time' => $p->created_at,
                ]));

            return $activity->sortByDesc('time')->take(6)->values();
        });

        return view('admin.dashboard', compact(
            'stats', 'perBatch', 'recentInvoices', 'recentAdmissions', 'alerts', 'activity'
        ));
    }
}
