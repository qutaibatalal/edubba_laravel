<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use App\Services\PdfService;
use App\Services\SequenceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacultyController extends Controller
{
    public function index(Request $request): View
    {
        $faculty = Faculty::with('department')
            ->when($request->q, fn ($q, $v) => $q->where(function ($qq) use ($v) {
                $qq->where('name', 'like', "%{$v}%")
                    ->orWhere('last_name', 'like', "%{$v}%")
                    ->orWhere('faculty_code', 'like', "%{$v}%");
            }))
            ->orderByDesc('id')
            ->paginate(20)
            ->onEachSide(1)
            ->withQueryString();

        return view('admin.faculty.index', compact('faculty'));
    }

    public function create(): View
    {
        return view('admin.faculty.form', [
            'member' => null,
            'departments' => Department::where('active', true)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['faculty_code'] = $data['faculty_code'] ?? SequenceService::next('faculty_code', 'FAC');
        $data['state'] = $data['state'] ?? Faculty::STATE_JOINED;

        Faculty::create($data);

        return redirect()->route('admin.faculty.index')->with('success', 'تم إضافة عضو هيئة تدريسية.');
    }

    public function show(Faculty $member): View
    {
        $member->load('department', 'courses.subject', 'courses.batch', 'classSessions');

        return view('admin.faculty.show', compact('member'));
    }

    public function downloadIdCard(Faculty $member)
    {
        $member->load('department');

        return PdfService::download(
            'pdf.faculty-card',
            ['member' => $member],
            'faculty-card-'.$member->faculty_code.'.pdf'
        );
    }

    public function edit(Faculty $faculty): View
    {
        return view('admin.faculty.form', [
            'member' => $faculty,
            'departments' => Department::where('active', true)->get(),
        ]);
    }

    public function update(Request $request, Faculty $faculty): RedirectResponse
    {
        $faculty->update($this->validated($request));

        return redirect()->route('admin.faculty.index')->with('success', 'تم تحديث البيانات.');
    }

    public function destroy(Faculty $faculty): RedirectResponse
    {
        $faculty->delete();

        return redirect()->route('admin.faculty.index')->with('success', 'تم حذف العضو.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'faculty_code' => 'nullable|string|max:50',
            'name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'national_id' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:30',
            'mobile' => 'nullable|string|max:30',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'qualification' => 'nullable|string|max:200',
            'specialization' => 'nullable|string|max:200',
            'join_date' => 'nullable|date',
            'department_id' => 'nullable|integer|exists:departments,id',
            'state' => 'nullable|in:draft,joined,left',
            'active' => 'nullable|boolean',
        ]);
    }
}
