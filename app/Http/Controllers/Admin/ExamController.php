<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamRoom;
use App\Models\ExamSchedule;
use App\Models\ExamType;
use App\Models\Marksheet;
use App\Models\MarksheetLine;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Services\ExamDistributionService;
use App\Services\ExamService;
use App\Services\GradeService;
use App\Services\NotificationService;
use App\Services\PdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(): View
    {
        $exams = Exam::with('examType', 'batch')->orderByDesc('id')->get();

        return view('admin.exams.index', [
            'exams' => $exams,
            'types' => ExamType::where('active', true)->get(),
            'years' => AcademicYear::where('active', true)->get(),
            'terms' => Term::all(),
            'batches' => Batch::where('active', true)->get(),
            'rooms' => ExamRoom::orderByDesc('active')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'exam_type_id' => 'nullable|integer|exists:exam_types,id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,id',
            'term_id' => 'nullable|integer|exists:terms,id',
            'batch_id' => 'nullable|integer|exists:batches,id',
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date',
        ]);

        Exam::create($validated + ['state' => Exam::STATE_DRAFT, 'created_by' => auth()->id()]);

        return redirect()->route('admin.exams.index')->with('success', 'تم إنشاء الامتحان.');
    }

    public function show(Exam $exam): View
    {
        $exam->load(['examType', 'batch', 'schedules', 'roomAllocations.examRoom', 'roomAllocations.student']);

        $distribution = $exam->roomAllocations
            ->groupBy(fn ($a) => $a->exam_schedule_id ?: 0)
            ->map(fn ($group) => $group->groupBy('exam_room_id'));

        return view('admin.exams.show', [
            'exam' => $exam,
            'distribution' => $distribution,
            'rooms' => ExamRoom::active()->orderBy('name')->get(),
            'subjects' => Subject::all(),
            'courses' => Course::where('active', true)->get(),
            'schedules' => $exam->schedules,
        ]);
    }

    public function scheduleStore(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'course_id' => 'nullable|integer|exists:courses,id',
            'date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'max_marks' => 'nullable|numeric|min:0',
            'pass_marks' => 'nullable|numeric|min:0',
        ]);

        $exam->schedules()->create($validated);

        return back()->with('success', 'تمت إضافة جلسة الامتحان.');
    }

    public function scheduleDestroy(Exam $exam, ExamSchedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return back()->with('success', 'تم حذف الجلسة.');
    }

    public function roomStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:30',
            'location' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1|max:500',
        ]);

        ExamRoom::create($validated + ['active' => true]);

        return back()->with('success', 'تمت إضافة القاعة.');
    }

    public function roomUpdate(Request $request, ExamRoom $room): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:30',
            'location' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1|max:500',
            'active' => 'nullable',
        ]);

        $room->update($validated + ['active' => $request->boolean('active')]);

        return back()->with('success', 'تم تحديث القاعة.');
    }

    public function roomDestroy(ExamRoom $room): RedirectResponse
    {
        $room->delete();

        return back()->with('success', 'تم حذف القاعة.');
    }

    public function distribute(Request $request, Exam $exam): RedirectResponse
    {
        $scheduleId = $request->integer('exam_schedule_id') ?: null;

        $result = ExamDistributionService::distribute($exam, $scheduleId);

        $msg = "تم توزيع {$result['allocated']} طالب على القاعات.";
        if ($result['conflicts']) {
            $msg .= ' (تضاربات: '.count($result['conflicts']).')';
        }

        return back()->with($result['conflicts'] ? 'error' : 'success', $msg);
    }

    public function held(Request $request, Exam $exam): RedirectResponse
    {
        $scheduleId = $request->integer('exam_schedule_id');
        $attended = $request->input('attended', []);

        if (! $scheduleId) {
            return back()->with('error', 'اختر جلسة الامتحان أولاً.');
        }

        $count = ExamDistributionService::markHeld($exam, $scheduleId, $attended);

        return back()->with('success', "تم تسجيل حضور {$count} طالب.");
    }

    public function seatingPdf(Exam $exam, ?int $scheduleId = null)
    {
        $exam->load(['batch', 'roomAllocations.examRoom', 'roomAllocations.student']);

        $grouped = $exam->roomAllocations
            ->when($scheduleId, fn ($q) => $q->where('exam_schedule_id', $scheduleId))
            ->groupBy('exam_room_id');

        return PdfService::download('pdf.exam-seating', [
            'exam' => $exam,
            'grouped' => $grouped,
            'schedule' => $scheduleId ? ExamSchedule::with('subject', 'course')->find($scheduleId) : null,
        ], 'seating-'.$exam->id.'.pdf');
    }

    // ============ Results management (Odoo-style) ============

    public function marksheets(Exam $exam): View
    {
        $exam->load(['batch', 'schedules.subject', 'marksheets.student', 'roomAllocations']);

        $subjects = $exam->schedules->filter(fn ($s) => $s->subject_id)->map(fn ($s) => $s->subject)->unique('id')->values();

        return view('admin.exams.marksheets', [
            'exam' => $exam,
            'marksheets' => $exam->marksheets,
            'subjects' => $subjects,
            'eligible' => $this->eligibleStudents($exam),
        ]);
    }

    public function marksheetsGenerate(Exam $exam): RedirectResponse
    {
        $students = $this->eligibleStudents($exam);
        $schedules = $exam->schedules()->whereNotNull('subject_id')->get();

        $created = 0;
        foreach ($students as $student) {
            if ($exam->marksheets()->where('student_id', $student->id)->exists()) {
                continue;
            }

            $marksheet = $exam->marksheets()->create([
                'student_id' => $student->id,
                'batch_id' => $student->batch_id,
                'state' => Marksheet::STATE_DRAFT,
            ]);

            foreach ($schedules as $schedule) {
                MarksheetLine::firstOrCreate(
                    ['marksheet_id' => $marksheet->id, 'subject_id' => $schedule->subject_id],
                    ['max_marks' => $schedule->max_marks, 'pass_marks' => $schedule->pass_marks, 'marks' => 0]
                );
            }

            $created++;
        }

        return back()->with('success', "تم إنشاء {$created} كشف درجات جديد.");
    }

    public function marksheet(Exam $exam, Marksheet $marksheet): View
    {
        abort_unless($marksheet->exam_id === $exam->id, 404);

        $marksheet->load(['student.batch', 'lines.subject']);

        return view('admin.exams.marksheet', ['exam' => $exam, 'marksheet' => $marksheet]);
    }

    public function marksheetStore(Request $request, Exam $exam, Marksheet $marksheet): RedirectResponse
    {
        abort_unless($marksheet->exam_id === $exam->id, 404);

        $lines = $request->input('lines', []);

        foreach ($lines as $lineId => $marks) {
            if ($marks === '' || $marks === null) {
                continue;
            }

            $line = MarksheetLine::findOrFail($lineId);
            $line->marks = max(0, (float) $marks);
            $line->save();

            $line->percentage = $line->max_marks > 0 ? round(($line->marks / $line->max_marks) * 100, 2) : 0;
            $line->grade = GradeService::gradeFor($line->percentage);
            $line->passed = $line->marks >= $line->pass_marks;
            $line->save();
        }

        ExamService::recompute($marksheet);

        return back()->with('success', 'تم حفظ العلامات.');
    }

    public function marksheetFinalize(Exam $exam, Marksheet $marksheet): RedirectResponse
    {
        abort_unless($marksheet->exam_id === $exam->id, 404);

        if ($marksheet->is_finalized) {
            return back()->with('error', 'كشف الدرجات معتمد مسبقاً.');
        }

        ExamService::finalize($marksheet);

        return back()->with('success', 'تم اعتماد الكشف وتجميع النتيجة.');
    }

    public function marksheetsFinalizeAll(Exam $exam): RedirectResponse
    {
        $count = 0;
        foreach ($exam->marksheets()->where('state', Marksheet::STATE_DRAFT)->get() as $marksheet) {
            ExamService::finalize($marksheet);
            $count++;
        }

        return back()->with('success', "تم اعتماد {$count} كشف درجات.");
    }

    public function results(Exam $exam): View
    {
        $exam->load(['batch', 'results.student']);

        return view('admin.exams.results', ['exam' => $exam]);
    }

    public function resultsPublish(Exam $exam): RedirectResponse
    {
        $count = ExamResult::where('exam_id', $exam->id)
            ->orWhere(function ($q) use ($exam) {
                $q->where('academic_year_id', $exam->academic_year_id)
                    ->where('batch_id', $exam->batch_id);
            })
            ->whereNull('published_at')
            ->update(['published_at' => now()]);

        return back()->with('success', "تم نشر {$count} نتيجة.");
    }

    public function resultCardPdf(Exam $exam, Student $student)
    {
        $result = ExamResult::where('student_id', $student->id)
            ->where(function ($q) use ($exam, $student) {
                $q->where('exam_id', $exam->id)
                    ->orWhere(fn ($w) => $w->where('academic_year_id', $exam->academic_year_id)
                        ->where('batch_id', $student->batch_id));
            })
            ->with('student.batch')
            ->firstOrFail();

        $marksheets = Marksheet::with('lines.subject')
            ->where('student_id', $student->id)
            ->where('state', Marksheet::STATE_DONE)
            ->whereHas('exam', fn ($q) => $q->where('academic_year_id', $exam->academic_year_id))
            ->get();

        return PdfService::download('pdf.result-card', [
            'exam' => $exam,
            'result' => $result,
            'marksheets' => $marksheets,
        ], 'result-'.$student->student_code.'.pdf');
    }

    public function resultsPdf(Exam $exam)
    {
        $results = ExamResult::with('student.batch')
            ->where('exam_id', $exam->id)
            ->orWhere(fn ($q) => $q->where('academic_year_id', $exam->academic_year_id)->where('batch_id', $exam->batch_id))
            ->orderBy('rank')
            ->get();

        $marksheets = Marksheet::with('lines.subject')
            ->whereIn('student_id', $results->pluck('student_id'))
            ->where('state', Marksheet::STATE_DONE)
            ->get()
            ->groupBy('student_id');

        return PdfService::download('pdf.result-card', [
            'exam' => $exam,
            'results' => $results,
            'marksheetsByStudent' => $marksheets,
        ], 'results-'.$exam->id.'.pdf');
    }

    public function resultsShare(Exam $exam): RedirectResponse
    {
        $results = ExamResult::with('student.parent', 'student.parents')
            ->where('exam_id', $exam->id)
            ->whereNotNull('published_at')
            ->get();

        $sent = 0;
        foreach ($results as $result) {
            $recipient = $result->student?->parents->first()?->phone
                ?? $result->student?->parent?->phone;

            if (! $recipient) {
                continue;
            }

            $gradeText = [
                'pass' => 'ناجح 🎉',
                'fail' => 'راسب',
            ][$result->result] ?? $result->result;

            $body = "نتيجة الطالب {$result->student->full_name} في {$exam->name}: النسبة {$result->average}%، الدرجة {$result->grade}، الترتيب {$result->rank}، الحالة: {$gradeText}";

            NotificationService::send('whatsapp', $recipient, $body, $result->student, [
                'title' => 'نتيجة الامتحان',
                'template' => 'result_published',
                'payload' => [
                    'student' => $result->student->full_name,
                    'exam' => $exam->name,
                    'average' => $result->average,
                    'grade' => $result->grade,
                    'result' => $gradeText,
                ],
            ]);

            $sent++;
        }

        return back()->with('success', "تم تجهيز {$sent} رسالة نتيجة (WhatsApp).");
    }

    /**
     * Students eligible for marksheets: seated students if the exam was
     * distributed, otherwise all admitted students of the exam's batch.
     */
    protected function eligibleStudents(Exam $exam): Collection
    {
        $seated = $exam->roomAllocations->map(fn ($a) => $a->student)->filter();

        if ($seated->isNotEmpty()) {
            return $seated->unique('id')->values();
        }

        return Student::admitted()
            ->when($exam->batch_id, fn ($q) => $q->where('batch_id', $exam->batch_id))
            ->orderBy('roll_no')
            ->get();
    }
}
