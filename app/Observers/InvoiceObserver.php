<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\Payment;

class InvoiceObserver
{
    /**
     * Recompute paid/balance when a payment is linked to an invoice.
     */
    public function paymentCreated(Payment $payment): void
    {
        if ($payment->invoice_id && $payment->state === Payment::STATE_DONE) {
            $invoice = $payment->invoice;
            $invoice->paid = $invoice->payments()
                ->where('state', Payment::STATE_DONE)
                ->sum('amount');
            $invoice->balance = max(0, $invoice->total - $invoice->paid);
            if ($invoice->balance <= 0) {
                $invoice->state = Invoice::STATE_PAID;
            }
            $invoice->save();
        }
    }
}
