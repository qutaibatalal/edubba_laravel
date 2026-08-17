<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a plain text message via WhatsApp Cloud API (falls back to mock).
     */
    public function send(string $recipient, string $message): bool
    {
        if (config('notifications.whatsapp.provider', 'mock') === 'mock') {
            Log::info('[WhatsApp-mock]', ['recipient' => $recipient, 'message' => $message]);

            return true;
        }

        $response = Http::withToken(config('notifications.whatsapp.meta.token'))
            ->post($this->endpoint(), [
                'messaging_product' => 'whatsapp',
                'to' => $this->normalizeNumber($recipient),
                'type' => 'text',
                'text' => ['body' => $message],
            ]);

        $ok = $response->successful();
        if (! $ok) {
            Log::error('WhatsApp send failed', ['error' => $response->body()]);
        }

        return $ok;
    }

    /**
     * Send a templated message. $payload keys become template variable values.
     *
     * @param  array<string, string>  $payload
     */
    public function sendTemplate(string $recipient, string $templateName, string $language, array $payload): bool
    {
        if (config('notifications.whatsapp.provider', 'mock') === 'mock') {
            Log::info('[WhatsApp-mock:template]', [
                'recipient' => $recipient,
                'template' => $templateName,
                'payload' => $payload,
            ]);

            return true;
        }

        $response = Http::withToken(config('notifications.whatsapp.meta.token'))
            ->post($this->endpoint(), [
                'messaging_product' => 'whatsapp',
                'to' => $this->normalizeNumber($recipient),
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => ['code' => $language],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => collect($payload)
                                ->map(fn ($value) => ['type' => 'text', 'text' => (string) $value])
                                ->values()
                                ->all(),
                        ],
                    ],
                ],
            ]);

        $ok = $response->successful();
        if (! $ok) {
            Log::error('WhatsApp template send failed', ['error' => $response->body()]);
        }

        return $ok;
    }

    /**
     * Pre-built Arabic template payloads.
     */
    public static function templates(): array
    {
        return [
            'attendance_alert' => 'تنبيه الحضور',
            'fee_reminder' => 'تذكير بالرسوم',
            'invoice_generated' => 'فاتورة جديدة',
            'result_published' => 'صدور النتائج',
            'birthday_wish' => 'تهنئة بعيد الميلاد',
        ];
    }

    protected function endpoint(): string
    {
        return sprintf(
            'https://graph.facebook.com/%s/%s/messages',
            config('notifications.whatsapp.meta.api_version'),
            config('notifications.whatsapp.meta.phone_number_id')
        );
    }

    protected function normalizeNumber(string $number): string
    {
        $digits = preg_replace('/\D+/', '', $number);

        if (str_starts_with($digits, '964')) {
            return $digits;
        }
        if (str_starts_with($digits, '7') && strlen($digits) === 10) {
            return '964'.$digits;
        }

        return $digits;
    }
}
