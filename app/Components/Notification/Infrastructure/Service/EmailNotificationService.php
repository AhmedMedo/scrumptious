<?php

namespace App\Components\Notification\Infrastructure\Service;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Notification\Application\Service\EmailNotificationServiceInterface;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use App\Components\Notification\Infrastructure\Mail\AdminMessageMail;
use App\Components\Notification\Infrastructure\Mail\MealPlanCustomizedMail;
use App\Components\Notification\Infrastructure\Mail\NewRecipeMail;
use App\Components\Notification\Infrastructure\Mail\TargetReminderMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService implements EmailNotificationServiceInterface
{
    public function send(string $userUuid, NotificationTypeEnum $type, string $title, string $body, ?array $data = null): bool
    {
        try {
            $user = UserEntity::where('uuid', $userUuid)->first();

            if (!$user || !$user->email) {
                Log::warning('User not found or has no email', ['user_uuid' => $userUuid]);
                return false;
            }

            $mailable = $this->getMailable($type, $user, $title, $body, $data);

            Mail::to($user->email)->send($mailable);

            Log::info('Email notification sent successfully', [
                'user_uuid' => $userUuid,
                'email' => $user->email,
                'type' => $type->value,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'user_uuid' => $userUuid,
                'type' => $type->value,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function sendToMultiple(array $userUuids, NotificationTypeEnum $type, string $title, string $body, ?array $data = null): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'users' => [],
        ];

        foreach ($userUuids as $userUuid) {
            $success = $this->send($userUuid, $type, $title, $body, $data);
            
            if ($success) {
                $results['success']++;
                $results['users'][] = ['user_uuid' => $userUuid, 'status' => 'success'];
            } else {
                $results['failed']++;
                $results['users'][] = ['user_uuid' => $userUuid, 'status' => 'failed'];
            }
        }

        return $results;
    }

    private function getMailable(NotificationTypeEnum $type, UserEntity $user, string $title, string $body, ?array $data): object
    {
        return match($type) {
            NotificationTypeEnum::MEAL_PLAN_CUSTOMIZED => new MealPlanCustomizedMail($user, $title, $body, $data),
            NotificationTypeEnum::TARGET_REMINDER => new TargetReminderMail($user, $title, $body, $data),
            NotificationTypeEnum::NEW_RECIPE => new NewRecipeMail($user, $title, $body, $data),
            NotificationTypeEnum::ADMIN_MESSAGE => new AdminMessageMail($user, $title, $body, $data),
        };
    }
}
