<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    /**
     * Send a push notification to a device token via FCM HTTP v1.
     * Returns true on success (or in mock mode).
     */
    public function send(string $deviceToken, string $title, string $body, array $data = []): bool
    {
        if (empty($deviceToken)) {
            return false;
        }

        if (empty(config('notifications.fcm.service_account'))) {
            Log::info('[FCM-mock]', ['token' => $deviceToken, 'title' => $title, 'body' => $body]);

            return true;
        }

        try {
            $response = Http::withToken($this->accessToken())
                ->acceptJson()
                ->post('https://fcm.googleapis.com/v1/projects/'.config('notifications.fcm.project_id').'/messages:send', [
                    'message' => [
                        'token' => $deviceToken,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'data' => collect($data)->map(fn ($v) => (string) $v)->all(),
                        'android' => ['priority' => 'high'],
                    ],
                ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('FCM send failed', ['error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Resolve a Google OAuth2 access token from the service-account file.
     */
    protected function accessToken(): string
    {
        return Cache::remember('fcm:access_token', now()->addMinutes(50), function () {
            $credentials = json_decode(file_get_contents(config('notifications.fcm.service_account')), true);

            $now = time();
            $jwtHeader = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $claims = $this->base64UrlEncode(json_encode([
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => $credentials['token_uri'],
                'iat' => $now,
                'exp' => $now + 3600,
            ]));

            $signature = '';
            openssl_sign($jwtHeader.'.'.$claims, $signature, $credentials['private_key'], 'sha256WithRSAEncryption');

            $token = $jwtHeader.'.'.$claims.'.'.$this->base64UrlEncode($signature);

            $response = Http::asForm()->post($credentials['token_uri'], [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $token,
            ]);

            return $response->json('access_token');
        });
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
