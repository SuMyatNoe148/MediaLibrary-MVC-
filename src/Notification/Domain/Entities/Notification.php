<?php

namespace MediaLibrary\Notification\Domain\Entities;

use DateTimeImmutable;
use MediaLibrary\Shared\Domain\ValueObjects\NotificationId;
use MediaLibrary\Shared\Domain\ValueObjects\UserId;

/**
 * Notification Entity - Notification Bounded Context
 */
class Notification
{
    private NotificationId $id;
    private UserId $userId;
    private string $title;
    private string $message;
    private ?string $link;
    private bool $isRead;
    private DateTimeImmutable $createdAt;

    public function __construct(
        NotificationId $id,
        UserId $userId,
        string $title,
        string $message,
        ?string $link,
        bool $isRead,
        ?DateTimeImmutable $createdAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
        $this->isRead = $isRead;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
    }

    public static function create(
        UserId $userId,
        string $title,
        string $message,
        ?string $link = null
    ): self {
        return new self(
            NotificationId::generate(),
            $userId,
            $title,
            $message,
            $link,
            false
        );
    }

    public function id(): NotificationId { return $this->id; }
    public function userId(): UserId { return $this->userId; }
    public function title(): string { return $this->title; }
    public function message(): string { return $this->message; }
    public function link(): ?string { return $this->link; }
    public function isRead(): bool { return $this->isRead; }
    public function createdAt(): DateTimeImmutable { return $this->createdAt; }

    public function markAsRead(): void
    {
        $this->isRead = true;
    }

    public function toArray(): array
    {
        return [
            'notification_id' => $this->id->value(),
            'user_id' => $this->userId->value(),
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link,
            'is_read' => $this->isRead,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
