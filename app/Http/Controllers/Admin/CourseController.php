<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::with('subject', 'batch', 'academicYear', 'faculty')
            ->when($request->batch_id, fn ($q, $v) => $q->where('batch_id', $v))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $batches = Batch::where('active', true)->get();

        return view('admin.courses.index', compact('courses', 'batches'));
    }

    public function create(): View
    {
        return view('admin.courses.form', [
            'course' => null,
            'subjects' => Subject::where('active', true)->get(),
            'batches' => Batch::where('active', true)->get(),
            'programs' => Program::where('active', true)->get(),
            'years' => AcademicYear::where('active', true)->get(),
            'faculty' => Faculty::where('state', 'joined')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Course::create($this->data($request));

        return redirect()->route('admin.courses.index')->with('success', 'تم إضافة المقرر.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.form', [
            'course' => $course,
            'subjects' => Subject::where('active', true)->get(),
            'batches' => Batch::where('active', true)->get(),
            'programs' => Program::where('active', true)->get(),
            'years' => AcademicYear::where('active', true)->get(),
            'faculty' => Faculty::where('state', 'joined')->get(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $course->update($this->data($request));

        return redirect()->route('admin.courses.index')->with('success', 'تم تحديث المقرر.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'تم حذف المقرر.');
    }

    private function data(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20',
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'program_id' => 'nullable|integer|exists:programs,id',
            'batch_id' => 'nullable|integer|exists:batches,id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,id',
            'faculty_id' => 'nullable|integer|exists:faculties,id',
            'credit_hours' => 'nullable|integer|min:0',
            'syllabus' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);
    }
}
