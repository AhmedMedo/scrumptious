<?php

namespace App\Components\Notification\Infrastructure\Jobs;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Components\Notification\Data\Entity\AdminBroadcastEntity;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessScheduledBroadcastsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 600;

    public function handle(NotificationServiceInterface $notificationService): void
    {
        try {
            $broadcasts = AdminBroadcastEntity::pendingScheduled()->get();

            foreach ($broadcasts as $broadcast) {
                $userUuids = $this->getUserUuids($broadcast);

                if (empty($userUuids)) {
                    $broadcast->markAsFailed();
                    continue;
                }

                $broadcast->update(['total_recipients' => count($userUuids)]);

                $results = $notificationService->sendToMultipleUsers(
                    $userUuids,
                    NotificationTypeEnum::ADMIN_MESSAGE,
                    $broadcast->title,
                    $broadcast->body,
                    $broadcast->data
                );

                $successCount = collect($results['database'])->where('status', 'success')->count();
                $failedCount = collect($results['database'])->where('status', 'failed')->count();

                $broadcast->update([
                    'successful_sends' => $successCount,
                    'failed_sends' => $failedCount,
                ]);

                $broadcast->markAsSent();

                Log::info('Broadcast processed', [
                    'broadcast_uuid' => $broadcast->uuid,
                    'total' => count($userUuids),
                    'success' => $successCount,
                    'failed' => $failedCount,
                ]);
            }

            Log::info('Scheduled broadcasts processed', [
                'count' => $broadcasts->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to process scheduled broadcasts', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function getUserUuids(AdminBroadcastEntity $broadcast): array
    {
        if ($broadcast->target_type === 'all') {
            return UserEntity::pluck('uuid')->toArray();
        }

        if ($broadcast->target_type === 'specific' && !empty($broadcast->target_user_uuids)) {
            return $broadcast->target_user_uuids;
        }

        return [];
    }
}
