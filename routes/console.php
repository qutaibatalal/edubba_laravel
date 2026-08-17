<?php

use App\Jobs\AbsenceNotificationJob;
use App\Jobs\AttendanceAggregationJob;
use App\Jobs\BackupJob;
use App\Jobs\BirthdayNotificationJob;
use App\Jobs\CommissionPayoutJob;
use App\Jobs\FeeInvoiceJob;
use App\Jobs\FeeOverdueJob;
use App\Jobs\FeeReminderJob;
use App\Jobs\GenerateSessionsJob;
use App\Jobs\MinistryReportJob;
use App\Jobs\SubscriptionRenewalJob;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Edubba Scheduler (Section 19 of the porting guide)
|--------------------------------------------------------------------------
*/

// Daily 01:00 — generate class sessions from timetables for today.
Schedule::job(new GenerateSessionsJob)->dailyAt('01:00');

// Daily 02:00 — generate attendance sheets + compute attendance %.
Schedule::job(new AttendanceAggregationJob)->dailyAt('02:00');

// Daily 03:00 — subscription renewal + payment reminders.
Schedule::job(new SubscriptionRenewalJob)->dailyAt('03:00');

// Daily 06:00 — send absence/late notifications.
Schedule::job(new AbsenceNotificationJob)->dailyAt('06:00');

// Daily 07:00 — birthday wishes.
Schedule::job(new BirthdayNotificationJob)->dailyAt('07:00');

// Daily 23:30 — database backup (prunes old backups).
Schedule::job(new BackupJob)
    ->dailyAt('23:30')
    ->after(function () {
        $keep = config('backup.keep_days', 14);
        $dir = config('backup.path', storage_path('app/backups'));
        if (is_dir($dir)) {
            collect(glob($dir.DIRECTORY_SEPARATOR.'backup-*.sqlite'))
                ->filter(fn ($f) => filemtime($f) < now()->subDays($keep)->getTimestamp())
                ->each(fn ($f) => @unlink($f));
        }
    });

// Hourly — commission / payout recalculation.
Schedule::job(new CommissionPayoutJob)->hourly();

// Monthly 1st — generate fee invoices for new term.
Schedule::job(new FeeInvoiceJob)->monthlyOn(1, '02:00');

// Daily 09:00 — WhatsApp reminders for invoices due within 3 days.
Schedule::job(new FeeReminderJob)->dailyAt('09:00');

// Daily 10:00 — overdue alerts (past due + 7 days grace).
Schedule::job(new FeeOverdueJob)->dailyAt('10:00');

// Nightly — ministry report aggregation.
Schedule::job(new MinistryReportJob)->dailyAt('23:00');
