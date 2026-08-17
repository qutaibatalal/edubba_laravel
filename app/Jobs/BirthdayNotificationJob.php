<?php

namespace App\Jobs;

use App\Models\Student;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BirthdayNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $students = Student::query()
            ->where('state', Student::STATE_ADMITTED)
            ->whereMonth('birth_date', now()->month)
            ->whereDay('birth_date', now()->day)
            ->with(['parent'])
            ->get();

        foreach ($students as $student) {
            $recipient = $student->parent?->mobile ?? $student->mobile;
            if (! $recipient) {
                continue;
            }

            $body = "نتمنى للطالب {$student->full_name} عيد ميلاد سعيداً، وكل عام وأنتم بخير.";

            NotificationService::send('whatsapp', $recipient, $body, $student, [
                'title' => 'عيد ميلاد سعيد',
                'template' => 'birthday_wish',
                'payload' => ['name' => $student->full_name],
            ]);
        }
    }
}
