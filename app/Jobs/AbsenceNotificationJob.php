<?php

namespace App\Jobs;

use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AbsenceNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 600;

    /**
     * Send absence alerts for students under the attendance threshold.
     * Alerts go through WhatsApp first, falling back to SMS.
     */
    public function handle(): void
    {
        NotificationService::sendAbsenceAlerts(75);
    }
}
