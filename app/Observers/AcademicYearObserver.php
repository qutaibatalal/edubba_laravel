<?php

namespace App\Observers;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\Cache;

class AcademicYearObserver
{
    public function saved(AcademicYear $year): void
    {
        Cache::forget('academic_years_all');
    }

    public function deleted(AcademicYear $year): void
    {
        Cache::forget('academic_years_all');
    }
}
