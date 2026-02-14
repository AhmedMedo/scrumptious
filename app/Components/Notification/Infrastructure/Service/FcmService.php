<?php

namespace App\Components\Notification\Infrastructure\Service;

use App\Components\Notification\Application\Query\UserDeviceTokenQueryInterface;
use App\Components\Notification\Application\Service\FcmServiceInterface;
use App\Components\Notification\Domain\Exception\FcmException;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;

class FcmService implements FcmServiceInterface
{
    public function __construct(
        private readonly Messaging $messaging,
        private readonly UserDeviceTokenQueryInterface $deviceTokenQuery
    ) {
    }

    public function sendToDevice(string $token, array $notification, array $data = []): bool
    {
        try {
            if (empty($token)) {
                throw FcmException::invalidToken('Token is empty');
            }

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(FcmNotification::create(
                    $notification['title'] ?? '',
                    $notification['body'] ?? ''
                ))
                ->withData($data);

            $this->messaging->send($message);

            Log::info('FCM notification sent successfully', [
                'token' => substr($token, 0, 20) . '...',
                'title' => $notification['title'] ?? '',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send FCM notification', [
                'token' => substr($token, 0, 20) . '...',
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function sendToMultipleDevices(array $tokens, array $notification, array $data = []): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'tokens' => [],
        ];

        foreach ($tokens as $token) {
            $success = $this->sendToDevice($token, $notification, $data);
            
            if ($success) {
                $results['success']++;
                $results['tokens'][] = ['token' => $token, 'status' => 'success'];
            } else {
                $results['failed']++;
                $results['tokens'][] = ['token' => $token, 'status' => 'failed'];
            }
        }

        return $results;
    }

    public function sendToTopic(string $topic, array $notification, array $data = []): bool
    {
        try {
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification(FcmNotification::create(
                    $notification['title'] ?? '',
                    $notification['body'] ?? ''
                ))
                ->withData($data);

            $this->messaging->send($message);

            Log::info('FCM notification sent to topic successfully', [
                'topic' => $topic,
                'title' => $notification['title'] ?? '',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send FCM notification to topic', [
                'topic' => $topic,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function sendToUser(string $userUuid, array $notification, array $data = []): array
    {
        $deviceTokens = $this->deviceTokenQuery->getActiveTokensByUserUuid($userUuid);

        if ($deviceTokens->isEmpty()) {
            Log::warning('No active device tokens found for user', ['user_uuid' => $userUuid]);
            return [
                'success' => 0,
                'failed' => 0,
                'tokens' => [],
            ];
        }

        $tokens = $deviceTokens->pluck('device_token')->toArray();

        return $this->sendToMultipleDevices($tokens, $notification, $data);
    }
}
