<?php

namespace MediaLibrary\Notification\Infrastructure\Persistence;

use MediaLibrary\Notification\Domain\Repositories\LegacyNotificationRepositoryInterface;
use MediaLibrary\Shared\Infrastructure\Persistence\BaseRepository;
use PDO;

class NotificationRepository extends BaseRepository implements LegacyNotificationRepositoryInterface
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO Notifications (user_id, type, title, message, link, is_read)
            VALUES (:user_id, :type, :title, :message, :link, :is_read)
        ");
        $stmt->execute([
            ':user_id'  => $data['user_id'],
            ':type'     => $data['type'],
            ':title'    => $data['title'],
            ':message'  => $data['message'],
            ':link'     => $data['link'] ?? null,
            ':is_read'  => isset($data['is_read']) ? (int)$data['is_read'] : 0
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Notifications WHERE notification_id = :notification_id");
        $stmt->execute([':notification_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByUserId(int $userId, int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM Notifications WHERE user_id = :user_id
            ORDER BY created_at DESC LIMIT :limit
        ");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnreadCount(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM Notifications WHERE user_id = :user_id AND is_read = FALSE");
        $stmt->execute([':user_id' => $userId]);
        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        $stmt = $this->db->prepare("UPDATE Notifications SET is_read = TRUE WHERE notification_id = :notification_id AND user_id = :user_id");
        return $stmt->execute([':notification_id' => $notificationId, ':user_id' => $userId]);
    }

    public function markAllAsRead(int $userId): bool
    {
        $stmt = $this->db->prepare("UPDATE Notifications SET is_read = TRUE WHERE user_id = :user_id AND is_read = FALSE");
        return $stmt->execute([':user_id' => $userId]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Notifications WHERE notification_id = :notification_id");
        return $stmt->execute([':notification_id' => $id]);
    }
}
