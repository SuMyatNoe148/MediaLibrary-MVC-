<?php

namespace MediaLibrary\Notification\Application\Services;

use MediaLibrary\Notification\Domain\Repositories\LegacyNotificationRepositoryInterface;
use MediaLibrary\Notification\Infrastructure\Persistence\NotificationRepository;

class NotificationService
{
    private LegacyNotificationRepositoryInterface $notificationRepository;

    public function __construct(?LegacyNotificationRepositoryInterface $notificationRepository = null)
    {
        $db = \Database::getConnection();
        $this->notificationRepository = $notificationRepository ?? new NotificationRepository($db);
    }

    public function createNotification(int $userId, string $type, string $title, string $message, ?string $link = null): int
    {
        return $this->notificationRepository->create([
            'user_id' => $userId, 'type' => $type, 'title' => $title,
            'message' => $message, 'link' => $link, 'is_read' => false
        ]);
    }

    public function getUserNotifications(int $userId, int $limit = 10): array
    {
        return $this->notificationRepository->findByUserId($userId, $limit);
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->notificationRepository->getUnreadCount($userId);
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        return $this->notificationRepository->markAsRead($notificationId, $userId);
    }

    public function markAllAsRead(int $userId): bool
    {
        return $this->notificationRepository->markAllAsRead($userId);
    }

    public function deleteNotification(int $notificationId): bool
    {
        return $this->notificationRepository->delete($notificationId);
    }

    public function notifyReservationConfirmed(int $userId, string $mediaTitle): int
    {
        return $this->createNotification($userId, 'reservation_confirmed', 'Reservation Confirmed', "Your reservation for '{$mediaTitle}' has been confirmed.", 'index.php?page=reservations');
    }

    public function notifyReservationCancelled(int $userId, string $mediaTitle): int
    {
        return $this->createNotification($userId, 'reservation_cancelled', 'Reservation Cancelled', "Your reservation for '{$mediaTitle}' has been cancelled.", 'index.php?page=reservations');
    }

    public function notifyPaymentCompleted(int $userId, string $invoiceNumber, float $amount): int
    {
        return $this->createNotification($userId, 'payment_completed', 'Payment Completed', "Payment of $" . number_format($amount, 2) . " for invoice #{$invoiceNumber} was successful.", 'index.php?page=invoices');
    }

    public function notifyNewReservation(int $adminId, string $userName, string $mediaTitle): int
    {
        return $this->createNotification($adminId, 'new_reservation', 'New Reservation', "{$userName} has made a new reservation for '{$mediaTitle}'.", 'index.php?page=admin-reservations');
    }

    public function notifyNewPayment(int $adminId, string $userName, float $amount): int
    {
        return $this->createNotification($adminId, 'new_payment', 'New Payment', "{$userName} has made a payment of $" . number_format($amount, 2) . ".", 'index.php?page=admin-invoices');
    }

    public function notifyNewSuggestion(int $adminId, string $userName, string $mediaTitle): int
    {
        return $this->createNotification($adminId, 'new_message', 'New Media Suggestion', "{$userName} suggested \"{$mediaTitle}\".", 'index.php?page=admin-messages');
    }
}
