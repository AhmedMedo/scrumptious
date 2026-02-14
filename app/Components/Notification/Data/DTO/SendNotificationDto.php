<?php

namespace App\Components\Notification\Data\DTO;

use App\Components\Notification\Data\Enums\NotificationTypeEnum;

class SendNotificationDto
{
    public function __construct(
        private readonly string|array $userUuids,
        private readonly NotificationTypeEnum $type,
        private readonly string $title,
        private readonly string $body,
        private readonly ?array $data = null,
        private readonly bool $sendFcm = false,
        private readonly bool $sendEmail = false,
        private readonly bool $saveToDatabase = true,
    ) {
    }

    public function userUuids(): array
    {
        return is_array($this->userUuids) ? $this->userUuids : [$this->userUuids];
    }

    public function type(): NotificationTypeEnum
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function data(): ?array
    {
        return $this->data;
    }

    public function shouldSendFcm(): bool
    {
        return $this->sendFcm;
    }

    public function shouldSendEmail(): bool
    {
        return $this->sendEmail;
    }

    public function shouldSaveToDatabase(): bool
    {
        return $this->saveToDatabase;
    }
}
