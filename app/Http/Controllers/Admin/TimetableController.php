<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Services\TimetableService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimetableController extends Controller
{
    public function index(Request $request): View
    {
        $weekStart = Carbon::parse($request->input('week_start', now()->startOfWeek(Carbon::SATURDAY)->toDateString()));
        $weekStart = $weekStart->startOfWeek(Carbon::SATURDAY);
        $weekEnd = $weekStart->copy()->addDays(6);

        $sessions = ClassSession::with('course', 'subject', 'batch', 'faculty', 'classroom')
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->orderBy('start_time')
            ->get();

        $conflicts = TimetableService::conflicts();

        $weekDays = collect(range(0, 6));

        return view('admin.timetable.index', [
            'sessions' => $sessions,
            'conflicts' => $conflicts,
            'weekStart' => $weekStart->toDateString(),
            'weekEnd' => $weekEnd->toDateString(),
            'weekDays' => $weekDays,
        ]);
    }

    public function generate(Request $request): RedirectResponse
    {
        $request->validate(['date' => 'required|date']);

        $count = TimetableService::generateSessionsForDate($request->date);

        return back()->with('success', "تم توليد {$count} حصة دراسية لهذا اليوم.");
    }
}
