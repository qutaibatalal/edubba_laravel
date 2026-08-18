<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyGroup;
use App\Models\Subscription;
use App\Models\TutoringPackage;
use Illuminate\Http\JsonResponse;
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
