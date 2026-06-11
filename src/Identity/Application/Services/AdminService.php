<?php

namespace MediaLibrary\Identity\Application\Services;

use MediaLibrary\Identity\Infrastructure\Persistence\AdminRepository;

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

    public function getAllGenres(): array { return $this->repo->getAllGenres(); }
    public function getAllPeople(): array { return $this->repo->getAllPeople(); }
    public function getAllRoles(): array { return $this->repo->getAllRoles(); }
    public function saveMediaPeople(int $mediaId, array $peopleIds, array $roleIds): bool { return $this->repo->saveMediaPeople($mediaId, $peopleIds, $roleIds); }
    public function addPerson(string $fullname): int { return $this->repo->addPerson($fullname); }
    public function isAdmin(int $userId): bool { return $this->repo->isAdmin($userId); }
    public function getAllUsers(): array { return $this->repo->getAllUsers(); }
    public function getAllMessages(string $filter = ''): array { return $this->repo->getAllMessages($filter); }
    public function getMessage(int $id): ?array { return $this->repo->getMessage($id); }
    public function markMessageRead(int $id): void { $this->repo->markMessageRead($id); }
    public function getAllReviews(): array { return $this->repo->getAllReviews(); }
    public function getCategories(): array { return $this->repo->getCategories(); }
    public function getRecentActivity(int $limit = 50): array { return $this->repo->getRecentActivity($limit); }

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

    public function toggleAdminStatus(int $userId): array
    {
        try {
            $newStatus = $this->repo->toggleAdminStatus($userId);
            return ['success' => true, 'message' => $newStatus ? 'User promoted to admin.' : 'User demoted from admin.'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to update admin status.'];
        }
    }

    public function deleteUser(int $userId): array
    {
        try {
            $this->repo->deleteUser($userId);
            return ['success' => true, 'message' => 'User deleted successfully.'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to delete user.'];
        }
    }

    public function getAllMedia(?string $category = null, int $page = 1, int $perPage = 10): array { return $this->repo->getAllMedia($category, $page, $perPage); }
    public function getTotalMediaCount(?string $category = null): int { return $this->repo->getTotalMediaCount($category); }
    public function getMediaById(int $mediaId): ?array { return $this->repo->getMediaById($mediaId); }

    public function updateMedia(int $mediaId, array $data): array
    {
        try {
            $result = $this->repo->updateMedia($mediaId, $data);
            return $result ? ['success' => true, 'message' => 'Media updated successfully.'] : ['success' => false, 'error' => 'Failed to update media.'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Error: ' . $e->getMessage()];
        }
    }

    public function createMedia(array $data): array
    {
        try {
            $mediaId = $this->repo->createMedia($data);
            return ['success' => true, 'message' => 'Media created successfully.', 'media_id' => $mediaId];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Error: ' . $e->getMessage()];
        }
    }

    public function deleteMedia(int $mediaId): array
    {
        try {
            $this->repo->deleteMedia($mediaId);
            return ['success' => true, 'message' => 'Media item deleted successfully.'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to delete media item.'];
        }
    }

    public function deleteReview(int $reviewId): array
    {
        try {
            $this->repo->deleteReview($reviewId);
            return ['success' => true, 'message' => 'Review deleted successfully.'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to delete review.'];
        }
    }
}
