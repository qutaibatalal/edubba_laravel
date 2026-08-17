<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS through the configured provider. Returns true on success.
     * The "mock" provider logs the message and returns true (used in dev/tests).
     */
    public function send(string $recipient, string $message): bool
    {
        if ($this->wouldExceedDailyCap()) {
            Log::warning('SmsService: daily cap reached, skipping.', ['recipient' => $recipient]);

            return false;
        }

        $provider = config('notifications.sms.provider', 'mock');

        try {
            return match ($provider) {
                'twilio' => $this->sendViaTwilio($recipient, $message),
                'unifonic' => $this->sendViaUnifonic($recipient, $message),
                'iraqsms' => $this->sendViaIraqSms($recipient, $message),
                default => $this->sendViaMock($recipient, $message),
            };
        } catch (\Throwable $e) {
            Log::error('SmsService failed', [
                'provider' => $provider,
                'recipient' => $recipient,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function sendViaMock(string $recipient, string $message): bool
    {
        Log::info('[SMS-mock]', compact('recipient', 'message'));
        $this->incrementCounter();

        return true;
    }

    protected function sendViaTwilio(string $recipient, string $message): bool
    {
        $response = Http::withBasicAuth(
            config('notifications.sms.twilio.sid'),
            config('notifications.sms.twilio.token')
        )->asForm()->post('https://api.twilio.com/2010-04-01/Accounts/'.config('notifications.sms.twilio.sid').'/Messages.json', [
            'From' => config('notifications.sms.twilio.from'),
            'To' => $this->normalizeNumber($recipient, 'twilio'),
            'Body' => $message,
        ]);

        $success = $response->successful();
        if ($success) {
            $this->incrementCounter();
        }

        return $success;
    }

    protected function sendViaUnifonic(string $recipient, string $message): bool
    {
        $response = Http::post('https://el.cloud.unifonic.com/rest/SMS/messages', [
            'AppSid' => config('notifications.sms.unifonic.app_sid'),
            'SenderID' => config('notifications.sms.unifonic.from'),
            'Recipient' => $this->normalizeNumber($recipient),
            'Body' => $message,
        ]);

        $success = $response->successful() && ($response->json('success') ?? false);
        if ($success) {
            $this->incrementCounter();
        }

        return $success;
    }

    protected function sendViaIraqSms(string $recipient, string $message): bool
    {
        $response = Http::post('https://www.iraqsms.com/API', [
            'username' => config('notifications.sms.iraqsms.username'),
            'password' => config('notifications.sms.iraqsms.password'),
            'sender' => config('notifications.sms.iraqsms.sender'),
            'mobile' => $this->normalizeNumber($recipient),
            'text' => $message,
            'lang' => 'ar',
        ]);

        $success = $response->successful() && str_contains($response->body(), 'success');
        if ($success) {
            $this->incrementCounter();
        }

        return $success;
    }

    /**
     * Normalize a local (Iraqi) number to international format.
     * 07xxxxxxxxx -> 9647xxxxxxxxx
     */
    protected function normalizeNumber(string $number, string $provider = 'generic'): string
    {
        $digits = preg_replace('/\D+/', '', $number);

        // Strip any leading 00 or 0 (e.g. 00964..., 0790...).
        $trimmed = ltrim($digits, '0');

        if (str_starts_with($trimmed, '964')) {
            return $trimmed;
        }
        if (str_starts_with($trimmed, '7') && strlen($trimmed) === 10) {
            return '964'.$trimmed;
        }

        return $trimmed ?: $digits;
    }

    protected function wouldExceedDailyCap(): bool
    {
        $key = 'notif:sms:daily:'.now()->format('Y-m-d');

        return (int) Cache::get($key, 0) >= config('notifications.rate_limit.daily_cap');
    }

    protected function incrementCounter(): void
    {
        $key = 'notif:sms:daily:'.now()->format('Y-m-d');
        Cache::put($key, (int) Cache::get($key, 0) + 1, now()->endOfDay());
    }
}
