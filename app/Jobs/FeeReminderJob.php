<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Services\FeeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Sends WhatsApp reminders for invoices due within 3 days.
 */
class FeeReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function handle(): void
    {
        $dueSoon = Invoice::whereIn('state', [Invoice::STATE_OPEN, Invoice::STATE_DRAFT])
            ->where('balance', '>', 0)
            ->whereBetween('due_date', [now()->toDateString(), now()->addDays(3)->toDateString()])
            ->get();

        foreach ($dueSoon as $invoice) {
            FeeService::sendInvoiceReminder($invoice);
        }
    }
}
