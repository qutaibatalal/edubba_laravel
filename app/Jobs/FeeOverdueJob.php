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
 * Sends overdue alerts for invoices past due + 7 days grace period.
 */
class FeeOverdueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function handle(): void
    {
        $overdue = Invoice::whereIn('state', [Invoice::STATE_OPEN, Invoice::STATE_DRAFT])
            ->where('balance', '>', 0)
            ->where('due_date', '<', now()->subDays(7)->toDateString())
            ->get();

        foreach ($overdue as $invoice) {
            FeeService::sendOverdueAlert($invoice);
        }
    }
}
