<?php

namespace App\Jobs;

use App\Models\Student;
use App\Services\AttendanceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AttendanceAggregationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $students = Student::where('state', Student::STATE_ADMITTED)->get();

        foreach ($students as $student) {
            // Trigger percentage recompute and persist into the daily register.
            $from = now()->startOfMonth()->toDateString();
            $to = now()->toDateString();
            AttendanceService::attendancePercentage($student, $from, $to);
        }
    }
}
