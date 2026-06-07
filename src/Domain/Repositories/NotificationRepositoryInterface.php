<?php

namespace MediaLibrary\Domain\Repositories;

interface NotificationRepositoryInterface
{
    public function create(array $data): int;
    public function findById(int $id): ?array;
    public function findByUserId(int $userId, int $limit = 10): array;
    public function getUnreadCount(int $userId): int;
    public function markAsRead(int $notificationId, int $userId): bool;
    public function markAllAsRead(int $userId): bool;
    public function delete(int $id): bool;
}
