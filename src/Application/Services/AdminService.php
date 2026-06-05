<?php

namespace MediaLibrary\Application\Services;

use MediaLibrary\Infrastructure\Persistence\AdminRepository;

/**
 * Admin Service
 * Handles admin-related business logic
 */
class AdminService
{
    private AdminRepository $repo;

    public function __construct(?AdminRepository $repo = null)
    {
        if ($repo === null) {
            $db = \Database::getConnection();
            $repo = new AdminRepository($db);
        }
        $this->repo = $repo;
    }

    /**
     * Get all genres
     */
    public function getAllGenres(): array
    {
        return $this->repo->getAllGenres();
    }

    /**
     * Get all people
     */
    public function getAllPeople(): array
    {
        return $this->repo->getAllPeople();
    }

    /**
     * Get all roles
     */
    public function getAllRoles(): array
    {
        return $this->repo->getAllRoles();
    }

    /**
     * Save media people associations
     */
    public function saveMediaPeople(int $mediaId, array $peopleIds, array $roleIds): bool
    {
        return $this->repo->saveMediaPeople($mediaId, $peopleIds, $roleIds);
    }

    /**
     * Add new person
     */
    public function addPerson(string $fullname): int
    {
        return $this->repo->addPerson($fullname);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(int $userId): bool
    {
        return $this->repo->isAdmin($userId);
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'total_users' => $this->repo->getTotalUsers(),
            'total_media' => $this->repo->getTotalMedia(),
            'total_reservations' => $this->repo->getTotalReservations(),
            'total_reviews' => $this->repo->getTotalReviews(),
            'unread_messages' => $this->repo->getUnreadMessages(),
            'recent_users' => $this->repo->getRecentUsers(5),
            'recent_activity' => $this->repo->getRecentActivity(10),
        ];
    }

    /**
     * Get all messages
     */
    public function getAllMessages(): array
    {
        return $this->repo->getAllMessages();
    }

    public function getAllUsers(): array
    {
        return $this->repo->getAllUsers();
    }

    /**
     * Toggle admin status
     */
    public function toggleAdminStatus(int $userId): array
    {
        try {
            $newStatus = $this->repo->toggleAdminStatus($userId);
            return [
                'success' => true,
                'message' => $newStatus ? 'User promoted to admin.' : 'User demoted from admin.'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Failed to update admin status.'];
        }
    }

    /**
     * Delete user
     */
    public function deleteUser(int $userId): array
    {
        try {
            $this->repo->deleteUser($userId);
            return ['success' => true, 'message' => 'User deleted successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Failed to delete user.'];
        }
    }

    /**
     * Get all media with pagination and filtering
     */
    public function getAllMedia(?string $category = null, int $page = 1, int $perPage = 10): array
    {
        return $this->repo->getAllMedia($category, $page, $perPage);
    }

    /**
     * Get total media count with filtering
     */
    public function getTotalMediaCount(?string $category = null): int
    {
        return $this->repo->getTotalMediaCount($category);
    }

    /**
     * Get all categories
     */
    public function getCategories(): array
    {
        return $this->repo->getCategories();
    }

    /**
     * Get media by ID
     */
    public function getMediaById(int $mediaId): ?array
    {
        return $this->repo->getMediaById($mediaId);
    }

    /**
     * Update media
     */
    public function updateMedia(int $mediaId, array $data): array
    {
        try {
            $result = $this->repo->updateMedia($mediaId, $data);
            if ($result) {
                return ['success' => true, 'message' => 'Media updated successfully.'];
            } else {
                return ['success' => false, 'error' => 'Failed to update media.'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Create new media
     */
    public function createMedia(array $data): array
    {
        try {
            $mediaId = $this->repo->createMedia($data);
            return ['success' => true, 'message' => 'Media created successfully.', 'media_id' => $mediaId];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Delete media
     */
    public function deleteMedia(int $mediaId): array
    {
        try {
            $this->repo->deleteMedia($mediaId);
            return ['success' => true, 'message' => 'Media item deleted successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Failed to delete media item.'];
        }
    }

    /**
     * Get all reviews
     */
    public function getAllReviews(): array
    {
        return $this->repo->getAllReviews();
    }

    /**
     * Delete review
     */
    public function deleteReview(int $reviewId): array
    {
        try {
            $this->repo->deleteReview($reviewId);
            return ['success' => true, 'message' => 'Review deleted successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Failed to delete review.'];
        }
    }

    /**
     * Get recent activity
     */
    public function getRecentActivity(int $limit = 50): array
    {
        return $this->repo->getRecentActivity($limit);
    }
}
