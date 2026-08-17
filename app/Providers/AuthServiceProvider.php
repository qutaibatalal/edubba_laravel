<?php

namespace App\Providers;

use App\Models\Admission;
use App\Models\Marksheet;
use App\Models\Student;
use App\Policies\AdmissionPolicy;
use App\Policies\MarksheetPolicy;
use App\Policies\StudentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Student::class => StudentPolicy::class,
        Admission::class => AdmissionPolicy::class,
        Marksheet::class => MarksheetPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
