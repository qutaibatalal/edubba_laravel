<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * ZainCash (Iraqi payment gateway) integration.
 *
 * Docs: https://docs.zaincash.iq/
 * Sandbox base: https://test.zaincash.iq/  |  Production: https://api.zaincash.iq/
 */
class ZainCashService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('zaincash.sandbox', true)
            ? config('zaincash.sandbox_url', 'https://test.zaincash.iq/')
            : config('zaincash.production_url', 'https://api.zaincash.iq/');
    }

    /**
     * Initialize a payment and return the transaction id + payment redirect URL.
     *
     * @param  array<string, mixed>  $extra  optional extra fields sent to the gateway
     * @return array{transaction_id: string, redirect_url: string, status: bool}
     *
     * @throws \RuntimeException when the gateway rejects the request
     */
    public function createTransaction(
        string $orderId,
        float $amount,
        string $phoneNumber,
        ?string $redirectUri = null,
        array $extra = []
    ): array {
        $payload = [
            'amount' => (string) round($amount, 2),
            'order_id' => $orderId,
            'msisdn' => $this->normalizePhone($phoneNumber),
            'merchant_id' => config('zaincash.merchant_id'),
            'redirect_uri' => $redirectUri ?? config('zaincash.redirect_uri'),
            'iqn' => config('zaincash.iqn'),
            'currency' => 'IQD',
        ] + $extra;

        $payload['signature'] = $this->signature($payload);

        $response = Http::asForm()
            ->timeout(15)
            ->post($this->baseUrl.'transaction/init', $payload);

        if ($response->failed()) {
            Log::error('ZainCash init failed', ['response' => $response->body()]);
            throw new \RuntimeException('ZainCash transaction init failed: '.$response->status());
        }

        $data = $response->json();
        $transactionId = $data['id'] ?? $data['transaction_id'] ?? null;

        if (! $transactionId) {
            Log::error('ZainCash init returned no id', ['response' => $data]);
            throw new \RuntimeException('ZainCash transaction id missing.');
        }

        return [
            'status' => true,
            'transaction_id' => $transactionId,
            'redirect_url' => $this->baseUrl.'transaction/pay?id='.$transactionId,
        ];
    }

    /**
     * Verify a webhook callback payload.
     *
     * @param  array<string, mixed>  $payload
     */
    public function verifyCallback(array $payload): bool
    {
        // HMAC signature over merchant_id + status + transaction_id + order_id + amount + msisdn
        $expected = hash_hmac(
            'sha256',
            implode('', [
                (string) ($payload['merchant_id'] ?? ''),
                (string) ($payload['status'] ?? ''),
                (string) ($payload['transaction_id'] ?? ''),
                (string) ($payload['order_id'] ?? ''),
                (string) ($payload['amount'] ?? ''),
                (string) ($payload['msisdn'] ?? ''),
            ]),
            config('zaincash.merchant_secret')
        );

        return hash_equals($expected, (string) ($payload['signature'] ?? ''));
    }

    /**
     * ZainCash signature: base64(sha256(merchant_secret + merchant_id + amount + msisdn + order_id + iqn)).
     *
     * @param  array<string, mixed>  $payload
     */
    protected function signature(array $payload): string
    {
        $raw = config('zaincash.merchant_secret')
            .config('zaincash.merchant_id')
            .$payload['amount']
            .$payload['msisdn']
            .$payload['order_id']
            .config('zaincash.iqn');

        return base64_encode(hash('sha256', $raw, true));
    }

    protected function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        return str_starts_with($digits, '964') ? $digits : '964'.ltrim($digits, '0');
    }
}
