<?php

namespace MediaLibrary\Presentation\Controllers;

use MediaLibrary\Application\Services\NotificationService;

class NotificationController
{
    private NotificationService $notificationService;

    public function __construct(?NotificationService $notificationService = null)
    {
        $this->notificationService = $notificationService ?? new NotificationService();
    }

    /**
     * Get user notifications (AJAX endpoint)
     */
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $notifications = $this->notificationService->getUserNotifications($userId, 10);
        $unreadCount = $this->notificationService->getUnreadCount($userId);

        header('Content-Type: application/json');
        echo json_encode([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
        exit;
    }

    /**
     * Display notifications list page
     */
    public function list()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $notifications = $this->notificationService->getUserNotifications($userId, 50);
        $pageTitle = "Notifications";
        $section = null;
        $hideSearch = true;

        require BASE_PATH . '/src/Presentation/Views/notifications/index.php';
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $notificationId = $_POST['notification_id'] ?? null;
        $userId = $_SESSION['user_id'];

        if (!$notificationId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Notification ID required']);
            exit;
        }

        $success = $this->notificationService->markAsRead($notificationId, $userId);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $success = $this->notificationService->markAllAsRead($userId);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }

    /**
     * Delete notification
     */
    public function delete()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $notificationId = $_POST['notification_id'] ?? null;

        if (!$notificationId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Notification ID required']);
            exit;
        }

        $success = $this->notificationService->deleteNotification($notificationId);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }
}
