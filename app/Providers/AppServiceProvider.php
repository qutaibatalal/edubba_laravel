<?php

namespace App\Providers;

use App\Models\AcademicYear;
use App\Models\LibraryBook;
use App\Models\LibraryIssue;
use App\Models\Marksheet;
use App\Models\MobileAppConfig;
use App\Models\Payment;
use App\Observers\AcademicYearObserver;
use App\Observers\InvoiceObserver;
use App\Observers\LibraryObserver;
use App\Observers\MarksheetObserver;
use App\Observers\MobileAppConfigObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        // ===== Observers =====
        Marksheet::observe(MarksheetObserver::class);
        AcademicYear::observe(AcademicYearObserver::class);
        MobileAppConfig::observe(MobileAppConfigObserver::class);

        // Wire the invoice recompute to payment lifecycle events.
        $observer = new InvoiceObserver;
        Payment::created(fn ($payment) => $observer->paymentCreated($payment));
        Payment::updated(fn ($payment) => $observer->paymentCreated($payment));

        LibraryBook::observe(LibraryObserver::class);
        LibraryIssue::observe(LibraryObserver::class);

        // ===== API rate limits =====
        // Login: 5 attempts / 5 minutes per IP + per username.
        RateLimiter::for('api.login', function (Request $request) {
            $key = $request->input('username', '')
                ? mb_strtolower($request->input('username')).'|'.$request->ip()
                : $request->ip();

            return Limit::perMinutes(5, 5)->by($key);
        });

        // Authenticated API: 60 requests/minute per token.
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->user()?->id ?: $request->ip());
        });

        // Payment webhooks: guarded per gateway key.
        RateLimiter::for('api.webhook', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->header('X-Gateway-Secret', $request->ip()));
        });

        // OTP / sensitive endpoints: 5 attempts/hour per phone.
        RateLimiter::for('api.otp', function (Request $request) {
            return Limit::perHour(5)->by($request->input('mobile', $request->ip()));
        });

        // File uploads: 20 files / hour per user.
        RateLimiter::for('api.upload', function (Request $request) {
            return Limit::perHour(20)->by($request->user()?->id ?: $request->ip());
        });

        // Outbound WhatsApp messages: 10 / minute per user.
        RateLimiter::for('api.whatsapp', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
    }
}
