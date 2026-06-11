<?php

namespace MediaLibrary\Identity\Infrastructure\Persistence;

use MediaLibrary\Shared\Infrastructure\Persistence\BaseRepository;
use PDO;

class AdminRepository extends BaseRepository
{
    public function getAllGenres(): array
    {
        if (!$this->tableExists('Genres')) return [];
        $stmt = $this->db->query("SELECT genre_id, genre FROM Genres ORDER BY genre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPeople(): array
    {
        if (!$this->tableExists('People')) return [];
        $stmt = $this->db->query("SELECT people_id, fullname FROM People ORDER BY fullname");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRoles(): array
    {
        if (!$this->tableExists('Role')) return [];
        $stmt = $this->db->query("SELECT role_id, role FROM Role ORDER BY role");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveMediaPeople(int $mediaId, array $peopleIds, array $roleIds): bool
    {
        if (!$this->tableExists('Media_People')) return false;
        try {
            $this->db->beginTransaction();
            $this->db->prepare("DELETE FROM Media_People WHERE media_id = ?")->execute([$mediaId]);
            $stmt = $this->db->prepare("INSERT INTO Media_People (media_id, people_id, role_id) VALUES (?, ?, ?)");
            for ($i = 0; $i < count($peopleIds); $i++) {
                if (!empty($peopleIds[$i]) && !empty($roleIds[$i])) {
                    $stmt->execute([$mediaId, (int)$peopleIds[$i], (int)$roleIds[$i]]);
                }
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function addPerson(string $fullname): int
    {
        if (!$this->tableExists('People')) return 0;
        $stmt = $this->db->prepare("INSERT INTO People (fullname) VALUES (?)");
        $stmt->execute([$fullname]);
        return (int)$this->db->lastInsertId();
    }

    public function isAdmin(int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT is_admin FROM Users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['is_admin'] == 1;
    }

    public function getTotalUsers(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM Users")->fetchColumn();
    }

    public function getTotalMedia(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM Media")->fetchColumn();
    }

    public function getTotalReviews(): int
    {
        if (!$this->tableExists('Reviews')) return 0;
        return (int) $this->db->query("SELECT COUNT(*) FROM Reviews")->fetchColumn();
    }

    public function getTotalReservations(): int
    {
        if (!$this->tableExists('Reservations')) return 0;
        return (int) $this->db->query("SELECT COUNT(*) FROM Reservations")->fetchColumn();
    }

    public function getUnreadMessages(): int
    {
        if (!$this->tableExists('Messages')) return 0;
        return (int) $this->db->query("SELECT COUNT(*) FROM Messages WHERE is_read = 0")->fetchColumn();
    }

    private function getMessagePrimaryKey(): string
    {
        $cols = $this->db->query('DESCRIBE Messages')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            if ($col['Key'] === 'PRI') return $col['Field'];
        }
        return 'id';
    }

    public function getAllMessages(string $filter = ''): array
    {
        if (!$this->tableExists('Messages')) return [];
        $pk = $this->getMessagePrimaryKey();
        $where = '';
        if ($filter === 'unread') $where = 'WHERE m.is_read = 0';
        elseif ($filter === 'read') $where = 'WHERE m.is_read = 1';
        $stmt = $this->db->query("
            SELECT m.*, m.{$pk} AS message_id,
                   COALESCE(u.username, m.name) AS username
            FROM Messages m LEFT JOIN Users u ON m.user_id = u.user_id
            {$where}
            ORDER BY m.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessage(int $id): ?array
    {
        if (!$this->tableExists('Messages')) return null;
        $pk = $this->getMessagePrimaryKey();
        $stmt = $this->db->prepare("
            SELECT m.*, m.{$pk} AS message_id,
                   COALESCE(u.username, m.name) AS username
            FROM Messages m LEFT JOIN Users u ON m.user_id = u.user_id
            WHERE m.{$pk} = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function markMessageRead(int $id): void
    {
        if (!$this->tableExists('Messages')) return;
        $pk = $this->getMessagePrimaryKey();
        $stmt = $this->db->prepare("UPDATE Messages SET is_read = 1 WHERE {$pk} = ?");
        $stmt->execute([$id]);
    }

    public function getAllUsers(): array
    {
        $stmt = $this->db->query("
            SELECT user_id, username, email, is_admin, is_verified, created_at 
            FROM Users ORDER BY created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentUsers(int $limit): array
    {
        $stmt = $this->db->prepare("
            SELECT user_id, username, email, created_at 
            FROM Users ORDER BY created_at DESC LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleAdminStatus(int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT is_admin FROM Users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $current = $stmt->fetchColumn();
        $newStatus = $current ? 0 : 1;
        $this->db->prepare("UPDATE Users SET is_admin = ? WHERE user_id = ?")->execute([$newStatus, $userId]);
        return (bool) $newStatus;
    }

    public function deleteUser(int $userId): void
    {
        $this->db->prepare("DELETE FROM Users WHERE user_id = ?")->execute([$userId]);
    }

    public function getAllMedia(?string $category = null, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $whereClause = "";
        $params = [];

        if ($category && $category !== 'all') {
            $whereClause = "WHERE mt.category = :category";
            $params[':category'] = $category;
        }

        $reviewsJoin = $this->tableExists('Reviews') ? "LEFT JOIN Reviews r ON m.media_id = r.media_id" : "";
        $reviewCount = $this->tableExists('Reviews') ? "COUNT(DISTINCT r.review_id) as review_count" : "0 as review_count";
        $ratingsJoin = $this->tableExists('Ratings') ? "LEFT JOIN Ratings rat ON m.media_id = rat.media_id" : "";
        $avgRating = $this->tableExists('Ratings') ? "AVG(rat.rating) as avg_rating" : "NULL as avg_rating";

        $sql = "
            SELECT m.media_id, m.title, mt.category, g.genre, m.format, m.year, m.img,
                   $reviewCount, $avgRating
            FROM Media m
            JOIN Media_Types mt ON m.media_types_id = mt.media_types_id
            JOIN Genres g ON m.genre_id = g.genre_id
            $reviewsJoin $ratingsJoin
            $whereClause
            GROUP BY m.media_id
            ORDER BY m.media_id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        if ($category && $category !== 'all') $stmt->bindValue(':category', $category);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalMediaCount(?string $category = null): int
    {
        $whereClause = ($category && $category !== 'all') ? "WHERE mt.category = :category" : "";
        $sql = "SELECT COUNT(*) as total FROM Media m JOIN Media_Types mt ON m.media_types_id = mt.media_types_id $whereClause";
        $stmt = $this->db->prepare($sql);
        if ($category && $category !== 'all') $stmt->bindValue(':category', $category);
        $stmt->execute();
        return (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function getCategories(): array
    {
        return $this->db->query("SELECT category FROM Media_Types ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getMediaById(int $mediaId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT m.media_id, m.title, m.img, m.format, m.year, 
                   mt.category, mt.media_types_id, g.genre, g.genre_id
            FROM Media m
            JOIN Media_Types mt ON m.media_types_id = mt.media_types_id
            JOIN Genres g ON m.genre_id = g.genre_id
            WHERE m.media_id = :media_id
        ");
        $stmt->execute([':media_id' => $mediaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateMedia(int $mediaId, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE Media SET title=:title, img=:img, format=:format, year=:year,
                media_types_id=:media_types_id, genre_id=:genre_id
            WHERE media_id=:media_id
        ");
        return $stmt->execute([
            ':media_id' => $mediaId, ':title' => $data['title'], ':img' => $data['img'],
            ':format' => $data['format'], ':year' => $data['year'],
            ':media_types_id' => $data['media_types_id'], ':genre_id' => $data['genre_id']
        ]);
    }

    public function createMedia(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO Media (title, img, format, year, media_types_id, genre_id)
            VALUES (:title, :img, :format, :year, :media_types_id, :genre_id)
        ");
        $stmt->execute([
            ':title' => $data['title'], ':img' => $data['img'], ':format' => $data['format'],
            ':year' => $data['year'], ':media_types_id' => $data['media_types_id'], ':genre_id' => $data['genre_id']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function deleteMedia(int $mediaId): void
    {
        $this->db->prepare("DELETE FROM Media WHERE media_id = ?")->execute([$mediaId]);
    }

    public function getAllReviews(): array
    {
        $stmt = $this->db->query("
            SELECT r.review_id, r.review_text, r.created_at,
                   u.username, u.user_id, m.title as media_title, m.media_id
            FROM Reviews r
            JOIN Users u ON r.user_id = u.user_id
            JOIN Media m ON r.media_id = m.media_id
            ORDER BY r.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteReview(int $reviewId): void
    {
        $this->db->prepare("DELETE FROM Reviews WHERE review_id = ?")->execute([$reviewId]);
    }

    public function getRecentActivity(int $limit): array
    {
        $stmt = $this->db->prepare("
            SELECT ua.*, u.username, m.title as media_title
            FROM User_Activity ua
            JOIN Users u ON ua.user_id = u.user_id
            LEFT JOIN Media m ON ua.media_id = m.media_id
            ORDER BY ua.created_at DESC LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
