<?php

namespace App\Components\Notification\Infrastructure\Jobs;

use App\Components\MealPlanner\Data\Entity\TargetEntity;
use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessTargetRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 300;

    public function handle(NotificationServiceInterface $notificationService): void
    {
        try {
            $reminderDays = 3;
            $reminderDate = now()->addDays($reminderDays)->toDateString();

            $targets = TargetEntity::whereDate('end_date', $reminderDate)
                ->with('user')
                ->get();

            foreach ($targets as $target) {
                if (!$target->user) {
                    continue;
                }

                $title = 'Target Reminder';
                $body = "Your target '{$target->title}' is ending in {$reminderDays} days. Keep up the great work!";
                $data = [
                    'target_uuid' => $target->uuid,
                    'target_title' => $target->title,
                    'end_date' => $target->end_date->toDateString(),
                ];

                $notificationService->sendToUser(
                    $target->user_uuid,
                    NotificationTypeEnum::TARGET_REMINDER,
                    $title,
                    $body,
                    $data
                );
            }

            Log::info('Target reminders processed', [
                'count' => $targets->count(),
                'reminder_date' => $reminderDate,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to process target reminders', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
