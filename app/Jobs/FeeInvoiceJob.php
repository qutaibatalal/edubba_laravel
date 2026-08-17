<?php

namespace App\Jobs;

use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Services\FeeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FeeInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 30;

    public function handle(): void
    {
        $year = AcademicYear::where('current', true)->first();
        if (! $year) {
            return;
        }

        $structures = FeeStructure::where('active', true)
            ->where('academic_year_id', $year->id)
            ->get();

        foreach ($structures as $structure) {
            $invoices = FeeService::generateInvoicesForBatchWithInvoices($structure);

            // Send a WhatsApp notification for each newly created invoice.
            foreach ($invoices as $invoice) {
                FeeService::sendNewInvoiceNotification($invoice);
            }
        }
    }
}
