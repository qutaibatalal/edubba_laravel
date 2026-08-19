<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyGroup;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\Tutor;
use App\Models\TutoringPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TutoringController extends Controller
{
    public function index(): View
    {
        $groups = StudyGroup::with('subject', 'tutor', 'students')->withCount('students')->paginate(15)->onEachSide(1);
        $subscriptions = Subscription::with('student', 'tutor', 'package')->orderByDesc('id')->limit(25)->get();
        $packages = TutoringPackage::all();

        return view('admin.tutoring.index', compact('groups', 'subscriptions', 'packages'));
    }

    public function create(): View
    {
        return view('admin.tutoring.create', [
            'subjects' => Subject::where('active', true)->get(),
            'tutors' => Tutor::where('state', 'active')->get(),
            'packages' => TutoringPackage::where('active', true)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'tutor_id' => 'nullable|integer|exists:tutors,id',
            'max_students' => 'nullable|integer|min:1',
            'level' => 'nullable|string|max:50',
        ]);

        StudyGroup::create($validated + ['state' => 'active']);

        return redirect()->route('admin.tutoring.index')->with('success', 'تم إنشاء مجموعة الدراسة.');
    }

    public function packages(): JsonResponse
    {
        return Cache::remember('tutoring:packages', 60, function () {
            return TutoringPackage::with('subject', 'tutor')->get()->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'price' => $p->price,
                'duration' => $p->duration,
                'subject' => $p->subject?->name,
                'tutor' => $p->tutor?->user?->name,
                'max_students' => $p->max_students,
                'active' => $p->active,
            ]);
        });
    }
}
