<?php

namespace App\Components\Notification\Infrastructure\Jobs;

use App\Components\Notification\Application\Service\EmailNotificationServiceInterface;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(
        public string $userUuid,
        public NotificationTypeEnum $type,
        public string $title,
        public string $body,
        public ?array $data = null
    ) {
    }

    public function handle(EmailNotificationServiceInterface $emailService): void
    {
        try {
            $success = $emailService->send(
                $this->userUuid,
                $this->type,
                $this->title,
                $this->body,
                $this->data
            );

            if ($success) {
                Log::info('Email notification job completed', [
                    'user_uuid' => $this->userUuid,
                    'type' => $this->type->value,
                ]);
            } else {
                Log::warning('Email notification job completed but failed to send', [
                    'user_uuid' => $this->userUuid,
                    'type' => $this->type->value,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Email notification job failed', [
                'user_uuid' => $this->userUuid,
                'type' => $this->type->value,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Email notification job failed permanently', [
            'user_uuid' => $this->userUuid,
            'type' => $this->type->value,
            'error' => $exception->getMessage(),
        ]);
    }
}
