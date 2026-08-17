<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Face recognition attendance helper.
 *
 * The real matching is delegated to an external (Python) microservice configured
 * via FACE_SERVICE_URL. When the service is unavailable we fall back to a manual
 * confirmation flow so attendance can still be recorded offline.
 */
class FaceRecognitionService
{
    /**
     * Attempt to identify a student from a base64 image.
     *
     * @return array{matched: bool, student_id?: int, confidence?: float, error?: string}
     */
    public static function identify(string $base64Image): array
    {
        $url = config('face_recognition.service_url');
        $enabled = config('face_recognition.enabled', false);

        if (! $enabled || ! $url) {
            return ['matched' => false, 'error' => 'face_recognition_disabled'];
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders(['Authorization' => 'Bearer '.config('face_recognition.api_key')])
                ->post($url.'/identify', [
                    'image_base64' => $base64Image,
                ]);

            if (! $response->successful()) {
                Log::warning('Face service error', ['status' => $response->status(), 'body' => $response->body()]);

                return ['matched' => false, 'error' => 'face_service_error'];
            }

            $data = $response->json();
            $matched = (bool) ($data['matched'] ?? false);

            return [
                'matched' => $matched,
                'student_id' => $data['student_id'] ?? null,
                'confidence' => $data['confidence'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::warning('Face service unreachable', ['error' => $e->getMessage()]);

            return ['matched' => false, 'error' => 'face_service_unreachable'];
        }
    }

    /**
     * Register (enroll) a student face. Used by faculty when adding a student photo.
     */
    public static function enroll(Student $student, string $base64Image): bool
    {
        $url = config('face_recognition.service_url');
        if (! $url) {
            return false;
        }

        try {
            return Http::timeout(15)
                ->withHeaders(['Authorization' => 'Bearer '.config('face_recognition.api_key')])
                ->post($url.'/enroll', [
                    'student_id' => $student->id,
                    'student_code' => $student->student_code,
                    'image_base64' => $base64Image,
                ])
                ->successful();
        } catch (\Throwable $e) {
            Log::warning('Face enroll failed', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
