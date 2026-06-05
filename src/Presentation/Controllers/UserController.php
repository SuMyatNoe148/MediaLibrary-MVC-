<?php

namespace MediaLibrary\Presentation\Controllers;

use MediaLibrary\Application\Services\UserService;

/**
 * Handles user-specific actions: ratings, reviews
 */
class UserController
{
    private UserService $userService;

    public function __construct(?UserService $userService = null)
    {
        if ($userService === null) {
            $userService = new UserService();
        }
        $this->userService = $userService;
    }

    /**
     * Rate a media item
     */
    public function rate()
    {
        $this->requireAuth();

        $userId = $_SESSION['user_id'];
        $mediaId = (int) ($_POST['media_id'] ?? 0);
        $rating = (int) ($_POST['rating'] ?? 0);

        if ($mediaId > 0 && $rating >= 1 && $rating <= 5) {
            $result = $this->userService->rateMedia($userId, $mediaId, $rating);

            if (isset($_POST['ajax'])) {
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            }
        }

        header("Location: index.php?page=details&id=$mediaId");
        exit;
    }

    /**
     * Add a review
     */
    public function addReview()
    {
        $this->requireAuth();

        $userId = $_SESSION['user_id'];
        $mediaId = (int) ($_POST['media_id'] ?? 0);
        $reviewText = trim($_POST['review'] ?? '');

        if ($mediaId > 0 && strlen($reviewText) >= 10) {
            $this->userService->addReview($userId, $mediaId, $reviewText);
        }

        header("Location: index.php?page=details&id=$mediaId");
        exit;
    }

    /**
     * Delete a review
     */
    public function deleteReview()
    {
        $this->requireAuth();

        $userId = $_SESSION['user_id'];
        $reviewId = (int) ($_POST['review_id'] ?? 0);
        $mediaId = (int) ($_POST['media_id'] ?? 0);

        if ($reviewId > 0) {
            $this->userService->deleteReview($reviewId, $userId);
        }

        header("Location: index.php?page=details&id=$mediaId");
        exit;
    }

    /**
     * Helper to require authentication
     */
    private function requireAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login&required=1");
            exit;
        }
    }
}
