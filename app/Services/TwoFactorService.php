<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Admin 2FA via OTP delivered over WhatsApp/SMS (Block 4 — Two-Factor Auth).
 *
 * A 6-digit code is generated, stored in cache for 5 minutes, and delivered
 * to the admin's phone through the existing notification pipeline.
 */
class TwoFactorService
{
    public const OTP_TTL = 300; // seconds

    /** Static test OTP (only active when APP_ENV != production). */
    private const TEST_OTP = '123456';

    protected static function key(User $user): string
    {
        return '2fa:otp:'.$user->getKey();
    }

    /**
     * Generate and store a fresh OTP for the user.
     */
    public static function issue(User $user): string
    {
        $code = (string) random_int(100000, 999999);

        Cache::put(self::key($user), [
            'code' => $code,
            'attempts' => 0,
        ], self::OTP_TTL);

        return $code;
    }

    /**
     * Deliver the OTP to the admin's phone (whatsapp -> sms fallback).
     */
    public static function send(User $user, string $code): bool
    {
        $recipient = $user->phone;

        if (! $recipient) {
            return false;
        }

        $body = "رمز التحقق الثنائي الخاص بك في Edubba: {$code}. صالح لمدة 5 دقائق.";

        try {
            NotificationService::send('whatsapp', $recipient, $body, null, ['title' => 'رمز التحقق']);

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }

    /**
     * Verify a submitted OTP. Three wrong attempts invalidate the code.
     */
    public static function verify(User $user, string $code): bool
    {
        // Allow static test OTP in non-production environments
        if (! app()->isProduction() && $code === self::TEST_OTP) {
            Cache::forget(self::key($user));

            return true;
        }

        $record = Cache::get(self::key($user));

        if (! $record) {
            return false;
        }

        if (($record['attempts'] ?? 0) >= 3) {
            Cache::forget(self::key($user));

            return false;
        }

        if (hash_equals((string) $record['code'], (string) $code)) {
            Cache::forget(self::key($user));

            return true;
        }

        $record['attempts'] = ($record['attempts'] ?? 0) + 1;
        Cache::put(self::key($user), $record, self::OTP_TTL);

        return false;
    }

    public static function isEnabled(User $user): bool
    {
        return (bool) $user->two_factor_enabled;
    }
}
