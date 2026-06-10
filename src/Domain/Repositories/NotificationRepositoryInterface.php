<?php

namespace MediaLibrary\Domain\Repositories;

use MediaLibrary\Domain\Entities\Notification;
use MediaLibrary\Domain\ValueObjects\NotificationId;
use MediaLibrary\Domain\ValueObjects\UserId;

/**
 * Interface for notification data access operations (DDD Repository)
 */
interface NotificationRepositoryInterface
{
    /**
     * Save notification (create or update)
     */
    public function save(Notification $notification): Notification;

    /**
     * Find notification by ID
     */
    public function findById(NotificationId $id): ?Notification;

    /**
     * Find notifications by user ID
     * @return Notification[]
     */
    public function findByUserId(UserId $userId, int $limit = 10): array;

    /**
     * Get unread count for user
     */
    public function getUnreadCount(UserId $userId): int;

    /**
     * Mark notification as read
     */
    public function markAsRead(NotificationId $notificationId, UserId $userId): bool;

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead(UserId $userId): bool;

    /**
     * Delete notification
     */
    public function delete(NotificationId $id): bool;
}
