<?php

namespace App\Components\Notification\Infrastructure\Listeners;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use App\Components\Notification\Infrastructure\Events\NewRecipeUploadedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendNewRecipeNotification implements ShouldQueue
{
    public function __construct(
        private readonly NotificationServiceInterface $notificationService
    ) {
    }

    public function handle(NewRecipeUploadedEvent $event): void
    {
        try {
            $title = 'New Recipe Available';
            $body = "Check out our new recipe: {$event->recipeName}!";
            $data = [
                'recipe_uuid' => $event->recipeUuid,
                'recipe_name' => $event->recipeName,
                'recipe_description' => $event->recipeDescription,
                'categories' => $event->categories,
            ];

            $userUuids = UserEntity::pluck('uuid')->toArray();

            if (!empty($userUuids)) {
                $this->notificationService->sendToMultipleUsers(
                    $userUuids,
                    NotificationTypeEnum::NEW_RECIPE,
                    $title,
                    $body,
                    $data
                );

                Log::info('New recipe notification sent', [
                    'recipe_uuid' => $event->recipeUuid,
                    'user_count' => count($userUuids),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send new recipe notification', [
                'recipe_uuid' => $event->recipeUuid,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
