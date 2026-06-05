<?php

namespace MediaLibrary\Infrastructure\Persistence;

use PDO;

/**
 * Admin Repository
 * Handles database operations for admin functions
 */

class AdminRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get all genres
     */
    public function getAllGenres(): array
    {
        if (!$this->tableExists('Genres')) {
            return [];
        }

        $stmt = $this->db->query("SELECT genre_id, genre FROM Genres ORDER BY genre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all people
     */
    public function getAllPeople(): array
    {
        if (!$this->tableExists('People')) {
            return [];
        }

        $stmt = $this->db->query("SELECT people_id, fullname FROM People ORDER BY fullname");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all roles
     */
    public function getAllRoles(): array
    {
        if (!$this->tableExists('Role')) {
            return [];
        }

        $stmt = $this->db->query("SELECT role_id, role FROM Role ORDER BY role");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Save media people associations
     */
    public function saveMediaPeople(int $mediaId, array $peopleIds, array $roleIds): bool
    {
        if (!$this->tableExists('Media_People')) {
            return false;
        }

        try {
            $this->db->beginTransaction();
            
            // Delete existing associations for this media
            $stmt = $this->db->prepare("DELETE FROM Media_People WHERE media_id = ?");
            $stmt->execute([$mediaId]);
            
            // Insert new associations
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

    /**
     * Add new person
     */
    public function addPerson(string $fullname): int
    {
        if (!$this->tableExists('People')) {
            return 0;
        }

        $stmt = $this->db->prepare("INSERT INTO People (fullname) VALUES (?)");
        $stmt->execute([$fullname]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT is_admin FROM Users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['is_admin'] == 1;
    }

    /**
     * Get total users count
     */
    public function getTotalUsers(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM Users");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get total media count
     */
    public function getTotalMedia(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM Media");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get total reviews count
     */
    public function getTotalReviews(): int
    {
        if (!$this->tableExists('Reviews')) {
            return 0;
        }

        $stmt = $this->db->query("SELECT COUNT(*) FROM Reviews");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get total reservations count
     */
    public function getTotalReservations(): int
    {
        if (!$this->tableExists('Reservations')) {
            return 0;
        }

        $stmt = $this->db->query("SELECT COUNT(*) FROM Reservations");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get unread messages count
     */
    public function getUnreadMessages(): int
    {
        if (!$this->tableExists('Messages')) {
            return 0;
        }

        $stmt = $this->db->query("SELECT COUNT(*) FROM Messages WHERE is_read = 0");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get all messages
     */
    public function getAllMessages(): array
    {
        if (!$this->tableExists('Messages')) {
            return [];
        }

        $stmt = $this->db->query("
            SELECT m.*, u.username 
            FROM Messages m
            LEFT JOIN Users u ON m.user_id = u.user_id
            ORDER BY m.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function tableExists(string $tableName): bool
    {
        $stmt = $this->db->query("SHOW TABLES LIKE '$tableName'");
        return $stmt !== false && $stmt->fetchColumn() !== false;
    }

    /**
     * Get all users
     */
    public function getAllUsers(): array
    {
        $stmt = $this->db->query("
            SELECT user_id, username, email, is_admin, is_verified, created_at 
            FROM Users 
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get recent users
     */
    public function getRecentUsers(int $limit): array
    {
        $stmt = $this->db->prepare("
            SELECT user_id, username, email, created_at 
            FROM Users 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Toggle admin status
     */
    public function toggleAdminStatus(int $userId): bool
    {
        // Get current status
        $stmt = $this->db->prepare("SELECT is_admin FROM Users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $current = $stmt->fetchColumn();
        
        $newStatus = $current ? 0 : 1;
        
        $stmt = $this->db->prepare("UPDATE Users SET is_admin = ? WHERE user_id = ?");
        $stmt->execute([$newStatus, $userId]);
        
        return (bool) $newStatus;
    }

    /**
     * Delete user
     */
    public function deleteUser(int $userId): void
    {
        $stmt = $this->db->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->execute([$userId]);
    }

    /**
     * Get all media with optional filtering and pagination
     */
    public function getAllMedia(?string $category = null, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = "";
        $params = [];
        
        if ($category && $category !== 'all') {
            $whereClause = "WHERE mt.category = :category";
            $params[':category'] = $category;
        }
        
        // Check if Reviews table exists
        $reviewsJoin = "";
        $ratingsJoin = "";
        $reviewCount = "0 as review_count";
        $avgRating = "NULL as avg_rating";
        
        if ($this->tableExists('Reviews')) {
            $reviewsJoin = "LEFT JOIN Reviews r ON m.media_id = r.media_id";
            $reviewCount = "COUNT(DISTINCT r.review_id) as review_count";
        }
        
        if ($this->tableExists('Ratings')) {
            $ratingsJoin = "LEFT JOIN Ratings rat ON m.media_id = rat.media_id";
            $avgRating = "AVG(rat.rating) as avg_rating";
        }
        
        $sql = "
            SELECT m.media_id, m.title, mt.category, g.genre, m.format, m.year, m.img,
                   $reviewCount,
                   $avgRating
            FROM Media m
            JOIN Media_Types mt ON m.media_types_id = mt.media_types_id
            JOIN Genres g ON m.genre_id = g.genre_id
            $reviewsJoin
            $ratingsJoin
            $whereClause
            GROUP BY m.media_id
            ORDER BY m.media_id DESC
            LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $this->db->prepare($sql);
        
        if ($category && $category !== 'all') {
            $stmt->bindValue(':category', $category);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total media count with optional filtering
     */
    public function getTotalMediaCount(?string $category = null): int
    {
        $whereClause = "";
        $params = [];
        
        if ($category && $category !== 'all') {
            $whereClause = "WHERE mt.category = :category";
            $params[':category'] = $category;
        }
        
        $sql = "
            SELECT COUNT(*) as total
            FROM Media m
            JOIN Media_Types mt ON m.media_types_id = mt.media_types_id
            $whereClause
        ";
        
        $stmt = $this->db->prepare($sql);
        
        if ($category && $category !== 'all') {
            $stmt->bindValue(':category', $category);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * Get all categories
     */
    public function getCategories(): array
    {
        $stmt = $this->db->query("SELECT category FROM Media_Types ORDER BY category");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get media by ID
     */
    public function getMediaById(int $mediaId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT m.media_id, m.title, m.img, m.format, m.year, 
                   mt.category, mt.media_types_id,
                   g.genre, g.genre_id
            FROM Media m
            JOIN Media_Types mt ON m.media_types_id = mt.media_types_id
            JOIN Genres g ON m.genre_id = g.genre_id
            WHERE m.media_id = :media_id
        ");
        $stmt->execute([':media_id' => $mediaId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Update media item
     */
    public function updateMedia(int $mediaId, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE Media 
            SET title = :title, 
                img = :img, 
                format = :format, 
                year = :year,
                media_types_id = :media_types_id,
                genre_id = :genre_id
            WHERE media_id = :media_id
        ");
        
        return $stmt->execute([
            ':media_id' => $mediaId,
            ':title' => $data['title'],
            ':img' => $data['img'],
            ':format' => $data['format'],
            ':year' => $data['year'],
            ':media_types_id' => $data['media_types_id'],
            ':genre_id' => $data['genre_id']
        ]);
    }

    /**
     * Create new media item
     */
    public function createMedia(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO Media (title, img, format, year, media_types_id, genre_id)
            VALUES (:title, :img, :format, :year, :media_types_id, :genre_id)
        ");
        
        $stmt->execute([
            ':title' => $data['title'],
            ':img' => $data['img'],
            ':format' => $data['format'],
            ':year' => $data['year'],
            ':media_types_id' => $data['media_types_id'],
            ':genre_id' => $data['genre_id']
        ]);
        
        return (int)$this->db->lastInsertId();
    }

    /**
     * Delete media
     */
    public function deleteMedia(int $mediaId): void
    {
        $stmt = $this->db->prepare("DELETE FROM Media WHERE media_id = ?");
        $stmt->execute([$mediaId]);
    }

    /**
     * Get all reviews
     */
    public function getAllReviews(): array
    {
        $stmt = $this->db->query("
            SELECT r.review_id, r.review_text, r.created_at,
                   u.username, u.user_id,
                   m.title as media_title, m.media_id
            FROM Reviews r
            JOIN Users u ON r.user_id = u.user_id
            JOIN Media m ON r.media_id = m.media_id
            ORDER BY r.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Delete review
     */
    public function deleteReview(int $reviewId): void
    {
        $stmt = $this->db->prepare("DELETE FROM Reviews WHERE review_id = ?");
        $stmt->execute([$reviewId]);
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity(int $limit): array
    {
        $stmt = $this->db->prepare("
            SELECT ua.*, u.username, m.title as media_title
            FROM User_Activity ua
            JOIN Users u ON ua.user_id = u.user_id
            LEFT JOIN Media m ON ua.media_id = m.media_id
            ORDER BY ua.created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
