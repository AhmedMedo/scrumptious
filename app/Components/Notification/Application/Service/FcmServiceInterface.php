<?php

namespace App\Components\Notification\Application\Service;

interface FcmServiceInterface
{
    public function sendToDevice(string $token, array $notification, array $data = []): bool;

    public function sendToMultipleDevices(array $tokens, array $notification, array $data = []): array;

    public function sendToTopic(string $topic, array $notification, array $data = []): bool;

    public function sendToUser(string $userUuid, array $notification, array $data = []): array;
}
