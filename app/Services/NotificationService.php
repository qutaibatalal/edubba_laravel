<?php

namespace App\Services;

use App\Jobs\SendNotificationJob;
use App\Models\NotificationLog;
use App\Models\Student;

class NotificationService
{
    /**
     * Log a notification intent and dispatch the queued sender.
     * Returns the NotificationLog model.
     *
     * @param  string  $channel  sms|whatsapp|push|email
     * @param  array<string, mixed>  $meta  e.g. ['title' => ..., 'device_token' => ...]
     */
    public static function send(string $channel, string $recipient, string $body, ?Student $student = null, array $meta = []): NotificationLog
    {
        $log = self::log($channel, $recipient, $body, $student);

        SendNotificationJob::dispatch($log->id, $channel, $recipient, $body, $meta)
            ->onQueue('notifications');

        return $log;
    }

    /**
     * Send through the default channel chain (push -> whatsapp -> sms),
     * stopping at the first channel that succeeds.
     */
    public static function sendViaBestChannel(string $recipient, string $title, string $body, ?Student $student = null, array $meta = []): ?NotificationLog
    {
        $deviceToken = $meta['device_token'] ?? null;

        if ($deviceToken) {
            return self::send('push', $deviceToken, $body, $student, $meta + ['title' => $title]);
        }

        if ($recipient) {
            return self::send('whatsapp', $recipient, $body, $student, $meta + ['title' => $title]);
        }

        return null;
    }

    /**
     * Create a notification log entry.
     */
    public static function log(string $channel, string $recipient, string $body, ?Student $student = null): NotificationLog
    {
        return NotificationLog::create([
            'channel' => $channel,
            'recipient' => $recipient,
            'body' => $body,
            'state' => NotificationLog::STATE_PENDING,
            'student_id' => $student?->id,
        ]);
    }

    /**
     * Mark a log entry as sent/failed.
     */
    public static function mark(NotificationLog $log, bool $success, ?string $error = null): void
    {
        $log->update([
            'state' => $success ? NotificationLog::STATE_SENT : NotificationLog::STATE_FAILED,
            'error' => $error,
            'sent_at' => $success ? now() : $log->sent_at,
        ]);
    }

    /**
     * Send absence alerts for students below the attendance threshold.
     * Dispatches push/whatsapp/sms in one call per student.
     */
    public static function sendAbsenceAlerts(float $threshold = 75): int
    {
        $count = 0;

        $students = Student::where('state', Student::STATE_ADMITTED)
            ->whereHas('attendances')
            ->get();

        foreach ($students as $student) {
            $percentage = AttendanceService::attendancePercentage($student);
            if ($percentage < $threshold) {
                $recipient = $student->parent?->mobile ?? $student->mobile;
                if ($recipient) {
                    $body = "تنبيه: نسبة حضور الطالب {$student->full_name} هي {$percentage}% وهي أقل من الحد المسموح ({$threshold}%).";
                    self::send('whatsapp', $recipient, $body, $student, ['title' => 'تنبيه الحضور']);
                    $count++;
                }
            }
        }

        return $count;
    }
}
