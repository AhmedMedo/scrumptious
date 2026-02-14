<?php

namespace App\Components\Notification\Infrastructure\Jobs;

use App\Components\Notification\Application\Service\FcmServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendFcmNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(
        public string $userUuid,
        public string $title,
        public string $body,
        public array $data = []
    ) {
    }

    public function handle(FcmServiceInterface $fcmService): void
    {
        try {
            $notification = [
                'title' => $this->title,
                'body' => $this->body,
            ];

            $result = $fcmService->sendToUser($this->userUuid, $notification, $this->data);

            Log::info('FCM notification job completed', [
                'user_uuid' => $this->userUuid,
                'success' => $result['success'],
                'failed' => $result['failed'],
            ]);
        } catch (\Exception $e) {
            Log::error('FCM notification job failed', [
                'user_uuid' => $this->userUuid,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('FCM notification job failed permanently', [
            'user_uuid' => $this->userUuid,
            'error' => $exception->getMessage(),
        ]);
    }
}
