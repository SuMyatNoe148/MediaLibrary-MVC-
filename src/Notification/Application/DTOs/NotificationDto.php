<?php

namespace MediaLibrary\Notification\Application\DTOs;

use MediaLibrary\Notification\Domain\Entities\Notification;

/**
 * Notification DTO
 */
readonly class NotificationDto
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $title,
        public string $message,
        public ?string $link,
        public bool $isRead,
        public string $createdAt
    ) {}

    public static function fromEntity(Notification $notification): self
    {
        return new self(
            id: $notification->id()->value(),
            userId: $notification->userId()->value(),
            title: $notification->title(),
            message: $notification->message(),
            link: $notification->link(),
            isRead: $notification->isRead(),
            createdAt: $notification->createdAt()->format('Y-m-d H:i:s')
        );
    }

    public function toArray(): array
    {
        return [
            'notification_id' => $this->id,
            'user_id' => $this->userId,
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
            'is_read' => $this->isRead,
            'created_at' => $this->createdAt,
        ];
    }
}
