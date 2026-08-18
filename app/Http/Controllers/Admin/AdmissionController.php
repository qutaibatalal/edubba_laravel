<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Admission;
use App\Models\Batch;
use App\Models\Program;
use App\Services\AdmissionService;
use App\Services\SequenceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    public function index(Request $request): View
    {
        $admissions = Admission::with('batch', 'program', 'student')
            ->when($request->state, fn ($q, $v) => $q->where('state', $v))
            ->orderByDesc('id')
            ->paginate(20)
            ->onEachSide(1)
            ->withQueryString();

        return view('admin.admissions.index', compact('admissions'));
    }

    public function create(): View
    {
        return view('admin.admissions.form', [
            'admission' => null,
            'years' => AcademicYear::where('active', true)->get(),
            'batches' => Batch::where('active', true)->get(),
            'programs' => Program::where('active', true)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'national_id' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'previous_school' => 'nullable|string|max:200',
            'fees_amount' => 'nullable|numeric|min:0',
            'academic_year_id' => 'nullable|integer|exists:academic_years,id',
            'batch_id' => 'nullable|integer|exists:batches,id',
            'program_id' => 'nullable|integer|exists:programs,id',
        ]);

        $data['state'] = Admission::STATE_DRAFT;
        $data['application_no'] = SequenceService::next('admission', 'ADM');

        Admission::create($data);

        return redirect()->route('admin.admissions.index')->with('success', 'تم إنشاء طلب القبول.');
    }

    public function submit(Admission $admission): RedirectResponse
    {
        try {
            AdmissionService::submit($admission);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'تم تقديم الطلب.');
    }

    public function approve(Admission $admission): RedirectResponse
    {
        try {
            AdmissionService::approve($admission);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'تمت الموافقة على الطلب.');
    }

    public function reject(Admission $admission): RedirectResponse
    {
        try {
            AdmissionService::reject($admission);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'تم رفض الطلب.');
    }

    public function admit(Admission $admission): RedirectResponse
    {
        try {
            AdmissionService::admit($admission);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'تم قبول الطالب بنجاح.');
    }
}
