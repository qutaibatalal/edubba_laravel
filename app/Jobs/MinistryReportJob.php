<?php

namespace App\Jobs;

use App\Models\AcademicYear;
use App\Services\MinistryReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MinistryReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $year = AcademicYear::where('current', true)->first();
        if (! $year) {
            return;
        }

        foreach (['student_counts', 'staff_counts', 'pass_rates'] as $type) {
            MinistryReportService::generate($year, $type);
        }
    }
}
