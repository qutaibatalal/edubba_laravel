<?php

namespace App\Jobs;

use App\Models\NotificationLog;
use App\Services\FcmService;
use App\Services\NotificationService;
use App\Services\SmsService;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 10;

    public int $timeout = 30;

    public function __construct(
        public int $logId,
        public string $channel,
        public string $recipient,
        public string $body,
        public array $meta = [],
    ) {}

    public function handle(): void
    {
        $log = NotificationLog::find($this->logId);

        if (! $log) {
            return;
        }

        $success = match ($this->channel) {
            'push' => app(FcmService::class)->send(
                $this->recipient,
                $this->meta['title'] ?? 'إشعار',
                $this->body,
                $this->meta['data'] ?? []
            ),
            'sms' => app(SmsService::class)->send($this->recipient, $this->body),
            'whatsapp' => $this->sendWhatsApp($log),
            default => false,
        };

        if ($success) {
            NotificationService::mark($log, true);

            return;
        }

        NotificationService::mark($log, false, 'Send failed');
        Log::warning('Notification dispatch failed', [
            'log_id' => $this->logId,
            'channel' => $this->channel,
        ]);
    }

    protected function sendWhatsApp(NotificationLog $log): bool
    {
        $template = $this->meta['template'] ?? null;

        if ($template) {
            return app(WhatsAppService::class)->sendTemplate(
                $this->recipient,
                $template,
                $this->meta['language'] ?? 'ar',
                $this->meta['payload'] ?? []
            );
        }

        return app(WhatsAppService::class)->send($this->recipient, $this->body);
    }
}
