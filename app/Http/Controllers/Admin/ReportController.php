<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\MinistryReport;
use App\Services\MinistryReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $reports = MinistryReport::with('academicYear', 'term')->orderByDesc('id')->get();
        $years = AcademicYear::where('active', true)->get();

        return view('admin.reports.index', compact('reports', 'years'));
    }

    public function generate(Request $request): RedirectResponse
    {
        $request->validate([
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'report_type' => 'required|in:student_counts,staff_counts,pass_rates',
        ]);

        $year = AcademicYear::findOrFail($request->academic_year_id);
        MinistryReportService::generate($year, $request->report_type);

        return back()->with('success', 'تم توليد التقرير بنجاح.');
    }
}
