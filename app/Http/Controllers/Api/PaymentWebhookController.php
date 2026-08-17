<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\FeeService;
use App\Services\Payments\QiCardService;
use App\Services\Payments\ZainCashService;
use App\Services\SequenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    /**
     * POST /payments/zaincash/callback — gateway confirms a transaction.
     */
    public function zainCashCallback(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (! app(ZainCashService::class)->verifyCallback($payload)) {
            Log::warning('ZainCash callback signature mismatch', ['payload' => $payload]);

            return response()->json(['status' => false, 'message' => 'Invalid signature'], 403);
        }

        $this->settlePayment(
            gateway: 'zaincash',
            gatewayRef: (string) ($payload['transaction_id'] ?? ''),
            orderId: (string) ($payload['order_id'] ?? ''),
            amount: (float) ($payload['amount'] ?? 0),
            status: ($payload['status'] ?? '') === 'success' ? 'done' : 'cancelled'
        );

        return response()->json(['status' => true]);
    }

    /**
     * POST /payments/qicard/callback — card gateway confirms a charge.
     */
    public function qiCardCallback(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (! app(QiCardService::class)->verifyCallback($payload)) {
            Log::warning('QiCard callback signature mismatch', ['payload' => $payload]);

            return response()->json(['status' => false, 'message' => 'Invalid signature'], 403);
        }

        $this->settlePayment(
            gateway: 'qicard',
            gatewayRef: (string) ($payload['ChargeId'] ?? $payload['charge_id'] ?? ''),
            orderId: (string) ($payload['OrderId'] ?? $payload['order_id'] ?? ''),
            amount: (float) ($payload['Amount'] ?? $payload['amount'] ?? 0),
            status: ($payload['TransactionStatus'] ?? '') === 'Successful' ? 'done' : 'cancelled'
        );

        return response()->json(['status' => true]);
    }

    /**
     * Reconcile a gateway callback with our invoice. Idempotent by transaction id.
     */
    protected function settlePayment(string $gateway, string $gatewayRef, string $orderId, float $amount, string $status): void
    {
        $existing = Payment::where('gateway', $gateway)
            ->where('transaction_id', $gatewayRef)
            ->first();

        if ($existing && $existing->state === Payment::STATE_DONE) {
            return; // already settled
        }

        $invoice = Invoice::where('number', $orderId)->first();

        if ($status === 'done') {
            if ($invoice) {
                // registerPayment creates the payment AND reconciles the invoice.
                try {
                    FeeService::registerPayment($invoice, [
                        'amount' => $amount ?: $invoice->balance,
                        'method' => Payment::METHOD_CARD,
                        'gateway' => $gateway,
                        'transaction_id' => $gatewayRef,
                        'date' => now()->toDateString(),
                    ]);
                } catch (\DomainException $e) {
                    Log::error('PaymentWebhook: settle rejected', [
                        'gateway' => $gateway,
                        'order_id' => $orderId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return;
        }

        // Failed/cancelled — record the attempt without touching the invoice.
        if (! $existing) {
            Payment::create([
                'reference' => SequenceService::next('payment', 'PAY'),
                'invoice_id' => $invoice?->id,
                'student_id' => $invoice?->student_id,
                'parent_id' => $invoice?->parent_id,
                'amount' => $amount ?: $invoice?->balance ?? 0,
                'method' => Payment::METHOD_CARD,
                'gateway' => $gateway,
                'transaction_id' => $gatewayRef,
                'state' => Payment::STATE_CANCELLED,
                'date' => now()->toDateString(),
            ]);
        } else {
            $existing->update(['state' => Payment::STATE_CANCELLED]);
        }
    }
}
