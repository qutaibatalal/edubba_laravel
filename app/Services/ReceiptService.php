<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Support\Facades\Storage;

class ReceiptService
{
    /**
     * Create a receipt record for a done payment.
     */
    public static function create(Payment $payment): Receipt
    {
        $receipt = Receipt::create([
            'receipt_no' => SequenceService::next('receipt', 'RCP'),
            'payment_id' => $payment->id,
            'invoice_id' => $payment->invoice_id,
            'date' => $payment->date,
            'amount' => $payment->amount,
        ]);

        static::storeDocument($receipt);

        return $receipt;
    }

    /**
     * Generate the printable PDF for the receipt and store it in the documents column.
     */
    protected static function storeDocument(Receipt $receipt): void
    {
        try {
            $receipt->load('invoice.student', 'invoice.parent', 'payment');

            $pdf = PdfService::render('pdf.receipt', ['receipt' => $receipt]);
            $path = 'receipts/'.str_replace(['/', '\\'], '-', $receipt->receipt_no).'.pdf';

            Storage::disk('local')->put($path, $pdf);
            $receipt->update(['document' => $path]);
        } catch (\Throwable $e) {
            // PDF generation must never block payment registration.
            report($e);
        }
    }
}
