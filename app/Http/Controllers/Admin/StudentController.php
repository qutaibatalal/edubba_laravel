<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ApiUser;
use App\Models\Batch;
use App\Models\ParentModel;
use App\Models\Program;
use App\Models\Student;
use App\Services\AttendanceService;
use App\Services\PdfService;
use App\Services\SequenceService;
use App\Support\UploadPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::with('batch', 'academicYear', 'parent')
            ->when($request->q, fn ($q, $v) => $q->where(function ($qq) use ($v) {
                $qq->where('name', 'like', "%{$v}%")
                    ->orWhere('last_name', 'like', "%{$v}%")
                    ->orWhere('student_code', 'like', "%{$v}%");
            }))
            ->when($request->batch_id, fn ($q, $v) => $q->where('batch_id', $v))
            ->orderByDesc('id')
            ->paginate(20)
            ->onEachSide(1)
            ->withQueryString();

        $batches = Batch::where('active', true)->get();

        return view('admin.students.index', compact('students', 'batches'));
    }

    public function create(): View
    {
        return view('admin.students.form', [
            'student' => null,
            'batches' => Batch::where('active', true)->get(),
            'programs' => Program::where('active', true)->get(),
            'years' => AcademicYear::where('active', true)->get(),
            'parents' => ParentModel::where('active', true)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['student_code'] = $data['student_code'] ?? SequenceService::next('student_code', 'STU');
        $data['roll_no'] = $data['roll_no'] ?? SequenceService::next('roll_no', 'RN');
        $data['state'] = $data['state'] ?? Student::STATE_ADMITTED;

        $student = Student::create($data);

        if ($request->hasFile('photo')) {
            $this->handlePhoto($request->file('photo'), $student);
        }

        if ($request->boolean('create_api_account')) {
            $this->createApiAccount($student);
        }

        return redirect()->route('admin.students.index')->with('success', 'تم إنشاء الطالب بنجاح.');
    }

    public function show(Student $student): View
    {
        $student->load('batch', 'program', 'academicYear', 'parent', 'parents', 'courses', 'invoices');

        $attendancePercentage = AttendanceService::attendancePercentage($student);

        $attendance = $student->attendances()
            ->with('sheet')
            ->whereHas('sheet', fn ($q) => $q->where('state', 'done'))
            ->latest('created_at')
            ->limit(8)
            ->get();

        return view('admin.students.show', compact('student', 'attendancePercentage', 'attendance'));
    }

    public function edit(Student $student): View
    {
        return view('admin.students.form', [
            'student' => $student,
            'batches' => Batch::where('active', true)->get(),
            'programs' => Program::where('active', true)->get(),
            'years' => AcademicYear::where('active', true)->get(),
            'parents' => ParentModel::where('active', true)->get(),
        ]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $student->update($this->validated($request));

        if ($request->hasFile('photo')) {
            $this->handlePhoto($request->file('photo'), $student);
        }

        return redirect()->route('admin.students.index')->with('success', 'تم تحديث بيانات الطالب.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'تم حذف الطالب.');
    }

    public function downloadStudentCard(Student $student)
    {
        $student->load('batch', 'program', 'academicYear');

        return PdfService::download(
            'pdf.student-card',
            [
                'student' => $student,
                'validUntil' => $student->academicYear?->end_date ?? now()->addYear(),
            ],
            'student-card-'.$student->student_code.'.pdf'
        );
    }

    public function downloadEnrollmentCertificate(Student $student)
    {
        $student->load('batch', 'program', 'academicYear');

        return PdfService::download(
            'pdf.enrollment-certificate',
            [
                'student' => $student,
                'registerNo' => SequenceService::next('certificate_no', 'CERT'),
            ],
            'enrollment-certificate-'.$student->student_code.'.pdf'
        );
    }

    private function createApiAccount(Student $student): void
    {
        if (! class_exists(ApiUser::class)) {
            return;
        }

        if (ApiUser::where('student_id', $student->id)->exists()) {
            return;
        }

        $username = $student->student_code ?: 'stu_'.$student->id;
        $password = Str::random(10);

        ApiUser::create([
            'username' => $username,
            'password' => $password,
            'role' => 'student',
            'student_id' => $student->id,
            'active' => true,
        ]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'student_code' => 'nullable|string|max:50',
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
            'city' => 'nullable|string|max:100',
            'batch_id' => 'nullable|integer|exists:batches,id',
            'program_id' => 'nullable|integer|exists:programs,id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,id',
            'parent_id' => 'nullable|integer|exists:parents,id',
            'state' => 'nullable|in:draft,admitted,graduated,alumni',
            'admission_date' => 'nullable|date',
            'roll_no' => 'nullable|string|max:30',
            'active' => 'nullable|boolean',
            'photo' => 'nullable|file|image|mimes:jpeg,png|max:5120',
        ]);
    }

    private function handlePhoto($file, Student $student): void
    {
        UploadPolicy::validate($file, 'image');
        $name = Str::random(24).'.'.$file->getClientOriginalExtension();
        $file->storeAs('photos', $name, 'public');
        $thumbName = 'thumbs/'.pathinfo($name, PATHINFO_FILENAME).'_thumb.jpg';
        $this->makeThumbnail($file->getRealPath(), storage_path('app/public/'.$thumbName));
        $student->update(['photo' => asset('storage/photos/'.$name)]);
    }

    private function makeThumbnail(string $sourcePath, string $thumbPath): void
    {
        $dir = dirname($thumbPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        [$width, $height, $type] = @getimagesize($sourcePath);
        if (! $width || ! $height) {
            return;
        }
        $src = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => imagecreatefrompng($sourcePath),
            default => null,
        };
        if (! $src) {
            return;
        }
        $size = min($width, $height);
        $offsetX = (int) (($width - $size) / 2);
        $offsetY = (int) (($height - $size) / 2);
        $thumb = imagecreatetruecolor(150, 150);
        imagecopyresampled($thumb, $src, 0, 0, $offsetX, $offsetY, 150, 150, $size, $size);
        imagejpeg($thumb, $thumbPath, 85);
        imagedestroy($src);
        imagedestroy($thumb);
    }
}
