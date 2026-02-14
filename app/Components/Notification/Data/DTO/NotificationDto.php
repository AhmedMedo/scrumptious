<?php

namespace App\Components\Notification\Data\DTO;

use App\Components\Notification\Data\Enums\NotificationTypeEnum;

class NotificationDto
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $userUuid,
        private readonly NotificationTypeEnum $type,
        private readonly string $title,
        private readonly string $body,
        private readonly ?array $data = null,
        private readonly bool $isRead = false,
        private readonly ?string $readAt = null,
        private readonly ?array $sentVia = null,
        private readonly ?string $fcmSentAt = null,
        private readonly ?string $emailSentAt = null,
        private readonly ?string $createdAt = null,
    ) {
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function userUuid(): string
    {
        return $this->userUuid;
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

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function readAt(): ?string
    {
        return $this->readAt;
    }

    public function sentVia(): ?array
    {
        return $this->sentVia;
    }

    public function fcmSentAt(): ?string
    {
        return $this->fcmSentAt;
    }

    public function emailSentAt(): ?string
    {
        return $this->emailSentAt;
    }

    public function createdAt(): ?string
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'user_uuid' => $this->userUuid,
            'type' => $this->type->value,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'is_read' => $this->isRead,
            'read_at' => $this->readAt,
            'sent_via' => $this->sentVia,
            'fcm_sent_at' => $this->fcmSentAt,
            'email_sent_at' => $this->emailSentAt,
            'created_at' => $this->createdAt,
        ];
    }
}
