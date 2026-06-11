<?php

namespace MediaLibrary\Notification\Domain\Repositories;

use MediaLibrary\Notification\Domain\Entities\Notification;
use MediaLibrary\Shared\Domain\ValueObjects\NotificationId;
use MediaLibrary\Shared\Domain\ValueObjects\UserId;

/**
 * Notification Repository Interface
 */
interface NotificationRepositoryInterface
{
    /**
     * Save notification
     */
    public function save(Notification $notification): Notification;

    /**
     * Find notification by ID
     */
    public function findById(NotificationId $id): ?Notification;

    /**
     * Find notifications by user
     * @return Notification[]
     */
    public function findByUserId(UserId $userId, int $limit = 10): array;

    /**
     * Get unread count
     */
    public function getUnreadCount(UserId $userId): int;

    /**
     * Mark as read
     */
    public function markAsRead(NotificationId $id, UserId $userId): bool;

    /**
     * Mark all as read
     */
    public function markAllAsRead(UserId $userId): bool;

    /**
     * Delete notification
     */
    public function delete(NotificationId $id): bool;
}
