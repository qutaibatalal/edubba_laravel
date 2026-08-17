<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Qi Card (National Payment Card - Iraq) integration.
 *
 * Docs: https://www.qicard.com/
 */
class QiCardService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('qicard.sandbox', true)
            ? config('qicard.sandbox_url', 'https://sbs.qicard.com/')
            : config('qicard.production_url', 'https://sbs.qicard.com/');
    }

    /**
     * Request a card charge. Returns the charge id and the redirect URL.
     *
     * @param  array<string, mixed>  $extra
     * @return array{charge_id: string, redirect_url: string, status: bool}
     *
     * @throws \RuntimeException when the gateway rejects the request
     */
    public function createCharge(
        string $orderId,
        float $amount,
        string $cardType = 'Card',
        ?string $redirectUri = null,
        array $extra = []
    ): array {
        $payload = [
            'TerminalId' => config('qicard.terminal_id'),
            'UserName' => config('qicard.username'),
            'Password' => config('qicard.password'),
            'OrderId' => $orderId,
            'Amount' => (string) round($amount, 2),
            'CardType' => $cardType,
            'Ccy' => 'IQD',
            'ReturnUrl' => $redirectUri ?? config('qicard.redirect_uri'),
            'Signature' => $this->signature($orderId, (string) round($amount, 2)),
        ] + $extra;

        $response = Http::asForm()
            ->timeout(15)
            ->post($this->baseUrl.'Charge.aspx', $payload);

        if ($response->failed()) {
            Log::error('QiCard charge failed', ['response' => $response->body()]);
            throw new \RuntimeException('QiCard charge failed: '.$response->status());
        }

        $data = $response->json() ?: $this->parseResponse($response->body());
        $chargeId = $data['ChargeId'] ?? $data['charge_id'] ?? null;

        if (! $chargeId) {
            Log::error('QiCard charge returned no id', ['response' => $data]);
            throw new \RuntimeException('QiCard charge id missing.');
        }

        return [
            'status' => true,
            'charge_id' => $chargeId,
            'redirect_url' => $this->baseUrl.'Charge.aspx?ChargeId='.$chargeId,
        ];
    }

    /**
     * Verify a callback/redirect response signature.
     *
     * @param  array<string, mixed>  $payload
     */
    public function verifyCallback(array $payload): bool
    {
        $raw = config('qicard.terminal_id')
            .($payload['OrderId'] ?? '')
            .($payload['CardNumber'] ?? '')
            .($payload['Amount'] ?? '')
            .($payload['TransactionStatus'] ?? '');

        $expected = $this->hash($raw);

        return hash_equals($expected, (string) ($payload['Signature'] ?? ''));
    }

    protected function signature(string $orderId, string $amount): string
    {
        return $this->hash(config('qicard.terminal_id').$orderId.$amount);
    }

    protected function hash(string $raw): string
    {
        return hash_hmac('sha256', $raw, config('qicard.merchant_key'));
    }

    /**
     * Qi Card may return a plain-text "OrderId,ChargeId,ReturnUrl" response.
     */
    protected function parseResponse(string $body): array
    {
        $parts = array_map('trim', explode(',', $body));
        if (count($parts) >= 2) {
            return ['order_id' => $parts[0], 'charge_id' => $parts[1]];
        }

        return [];
    }
}
