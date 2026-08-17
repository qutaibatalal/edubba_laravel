<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolController extends Controller
{
    // ---- Batches ----

    public function batchesIndex(): View
    {
        $batches = Batch::with('program', 'academicYear', 'classTeacher')->withCount('students')->get();

        return view('admin.batches.index', compact('batches'));
    }

    public function batchesCreate(): View
    {
        return view('admin.batches.form', [
            'batch' => null,
            'programs' => Program::all(),
            'years' => AcademicYear::all(),
            'faculty' => Faculty::where('state', 'joined')->get(),
        ]);
    }

    public function batchesStore(Request $request): RedirectResponse
    {
        Batch::create($this->batchData($request));

        return redirect()->route('admin.batches.index')->with('success', 'تم إضافة الصف.');
    }

    public function batchesEdit(Batch $batch): View
    {
        return view('admin.batches.form', [
            'batch' => $batch,
            'programs' => Program::all(),
            'years' => AcademicYear::all(),
            'faculty' => Faculty::where('state', 'joined')->get(),
        ]);
    }

    public function batchesUpdate(Request $request, Batch $batch): RedirectResponse
    {
        $batch->update($this->batchData($request));

        return redirect()->route('admin.batches.index')->with('success', 'تم تحديث الصف.');
    }

    public function batchesDestroy(Batch $batch): RedirectResponse
    {
        $batch->delete();

        return redirect()->route('admin.batches.index')->with('success', 'تم حذف الصف.');
    }

    private function batchData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'program_id' => 'nullable|integer|exists:programs,id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,id',
            'class_teacher_id' => 'nullable|integer|exists:faculties,id',
            'capacity' => 'nullable|integer|min:1',
            'active' => 'nullable|boolean',
        ]);
    }

    // ---- Programs ----

    public function programsIndex(): View
    {
        $programs = Program::with('department')->withCount('batches')->get();

        return view('admin.programs.index', compact('programs'));
    }

    public function programsCreate(): View
    {
        return view('admin.programs.form', ['program' => null, 'departments' => Department::all()]);
    }

    public function programsStore(Request $request): RedirectResponse
    {
        Program::create($this->programData($request));

        return redirect()->route('admin.programs.index')->with('success', 'تم إضافة البرنامج.');
    }

    public function programsEdit(Program $program): View
    {
        return view('admin.programs.form', ['program' => $program, 'departments' => Department::all()]);
    }

    public function programsUpdate(Request $request, Program $program): RedirectResponse
    {
        $program->update($this->programData($request));

        return redirect()->route('admin.programs.index')->with('success', 'تم تحديث البرنامج.');
    }

    public function programsDestroy(Program $program): RedirectResponse
    {
        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'تم حذف البرنامج.');
    }

    private function programData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20',
            'department_id' => 'nullable|integer|exists:departments,id',
            'duration_years' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);
    }

    // ---- Academic Years ----

    public function yearsIndex(): View
    {
        $years = AcademicYear::withCount('batches', 'admissions')->orderByDesc('date_start')->get();

        return view('admin.years.index', compact('years'));
    }

    public function yearsCreate(): View
    {
        return view('admin.years.form', ['year' => null]);
    }

    public function yearsStore(Request $request): RedirectResponse
    {
        AcademicYear::create($this->yearData($request));

        return redirect()->route('admin.academic-years.index')->with('success', 'تم إضافة السنة الدراسية.');
    }

    public function yearsEdit(AcademicYear $year): View
    {
        return view('admin.years.form', ['year' => $year]);
    }

    public function yearsUpdate(Request $request, AcademicYear $year): RedirectResponse
    {
        $year->update($this->yearData($request));

        return redirect()->route('admin.academic-years.index')->with('success', 'تم تحديث السنة الدراسية.');
    }

    public function yearsDestroy(AcademicYear $year): RedirectResponse
    {
        $year->delete();

        return redirect()->route('admin.academic-years.index')->with('success', 'تم حذف السنة الدراسية.');
    }

    private function yearData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:30',
            'date_start' => 'nullable|date',
            'date_stop' => 'nullable|date',
            'current' => 'nullable|boolean',
            'active' => 'nullable|boolean',
        ]);
    }
}
