<?php

namespace MediaLibrary\Reservation\Application\Services;

use MediaLibrary\Reservation\Domain\Repositories\LegacyReservationRepositoryInterface;
use MediaLibrary\Reservation\Infrastructure\Persistence\ReservationRepository;

class ReservationService
{
    private LegacyReservationRepositoryInterface $repo;

    public function __construct(?LegacyReservationRepositoryInterface $repo = null)
    {
        if ($repo === null) {
            $db = \Database::getConnection();
            $repo = new ReservationRepository($db);
        }
        $this->repo = $repo;
    }

    public function createReservation(int $userId, int $mediaId, string $reservationDate, ?string $notes = null): array
    {
        $today = date('Y-m-d');
        if ($reservationDate < $today) {
            return ['success' => false, 'error' => 'Reservation date must be today or in the future.'];
        }
        if ($this->repo->hasReservation($userId, $mediaId, $reservationDate)) {
            return ['success' => false, 'error' => 'You already have a reservation for this item on this date.'];
        }
        $media = $this->repo->getMediaById($mediaId);
        $amount = $media['price'] ?? 0.00;
        try {
            $reservationId = $this->repo->create($userId, $mediaId, $reservationDate, $notes, $amount);
            return ['success' => true, 'message' => 'Reservation created successfully.', 'reservation_id' => $reservationId];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to create reservation.'];
        }
    }

    public function getReservationById(int $reservationId): ?array
    {
        return $this->repo->findById($reservationId);
    }

    public function getUserReservations(int $userId): array
    {
        return $this->repo->getUserReservations($userId);
    }

    public function getUserReservationsFiltered(int $userId, ?string $search = null, ?string $paymentStatus = null, ?string $reservationStatus = null): array
    {
        return $this->repo->getUserReservationsFiltered($userId, $search, $paymentStatus, $reservationStatus);
    }

    public function getMediaReservations(int $mediaId): array
    {
        return $this->repo->getMediaReservations($mediaId);
    }

    public function getAllReservations(): array
    {
        return $this->repo->getAllReservations();
    }

    public function updateStatus(int $reservationId, string $status): array
    {
        $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($status, $validStatuses)) {
            return ['success' => false, 'error' => 'Invalid status.'];
        }
        $result = $this->repo->updateStatus($reservationId, $status);
        return $result
            ? ['success' => true, 'message' => 'Reservation status updated.']
            : ['success' => false, 'error' => 'Failed to update status.'];
    }

    public function updatePaymentStatus(int $reservationId, string $paymentStatus): array
    {
        $validStatuses = ['pending', 'completed', 'failed'];
        if (!in_array($paymentStatus, $validStatuses)) {
            return ['success' => false, 'error' => 'Invalid payment status.'];
        }
        $result = $this->repo->updatePaymentStatus($reservationId, $paymentStatus);
        return $result
            ? ['success' => true, 'message' => 'Payment status updated.']
            : ['success' => false, 'error' => 'Failed to update payment status.'];
    }

    public function cancelReservation(int $reservationId): array
    {
        $reservation = $this->repo->findById($reservationId);
        if (!$reservation) {
            return ['success' => false, 'error' => 'Reservation not found.'];
        }
        if ($reservation['status'] === 'cancelled') {
            return ['success' => false, 'error' => 'Reservation already cancelled.'];
        }
        if ($reservation['status'] === 'completed') {
            return ['success' => false, 'error' => 'Cannot cancel completed reservation.'];
        }
        $result = $this->repo->cancel($reservationId);
        return $result
            ? ['success' => true, 'message' => 'Reservation cancelled successfully.']
            : ['success' => false, 'error' => 'Failed to cancel reservation.'];
    }

    public function deleteReservation(int $reservationId): array
    {
        $result = $this->repo->delete($reservationId);
        return $result
            ? ['success' => true, 'message' => 'Reservation deleted successfully.']
            : ['success' => false, 'error' => 'Failed to delete reservation.'];
    }
}
