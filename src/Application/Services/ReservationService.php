<?php

namespace MediaLibrary\Application\Services;

use MediaLibrary\Domain\Repositories\ReservationRepositoryInterface;
use MediaLibrary\Infrastructure\Persistence\ReservationRepository;

/**
 * Reservation Service
 * Handles reservation-related business logic
 */
class ReservationService
{
    private ReservationRepositoryInterface $repo;

    public function __construct(?ReservationRepositoryInterface $repo = null)
    {
        if ($repo === null) {
            $db = \Database::getConnection();
            $repo = new ReservationRepository($db);
        }
        $this->repo = $repo;
    }

    /**
     * Create a new reservation
     */
    public function createReservation(int $userId, int $mediaId, string $reservationDate, ?string $notes = null): array
    {
        // Validate date (must be today or future date)
        $today = date('Y-m-d');
        if ($reservationDate < $today) {
            return ['success' => false, 'error' => 'Reservation date must be today or in the future.'];
        }

        // Check for duplicate reservation
        if ($this->repo->hasReservation($userId, $mediaId, $reservationDate)) {
            return ['success' => false, 'error' => 'You already have a reservation for this item on this date.'];
        }

        // Get media price
        $media = $this->repo->getMediaById($mediaId);
        $amount = $media['price'] ?? 0.00;

        try {
            $reservationId = $this->repo->create($userId, $mediaId, $reservationDate, $notes, $amount);
            return ['success' => true, 'message' => 'Reservation created successfully.', 'reservation_id' => $reservationId];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to create reservation.'];
        }
    }

    /**
     * Get reservation by ID
     */
    public function getReservationById(int $reservationId): ?array
    {
        return $this->repo->findById($reservationId);
    }

    /**
     * Get all reservations for a user
     */
    public function getUserReservations(int $userId): array
    {
        return $this->repo->getUserReservations($userId);
    }

    /**
     * Get filtered reservations for a user
     */
    public function getUserReservationsFiltered(int $userId, ?string $search = null, ?string $paymentStatus = null, ?string $reservationStatus = null): array
    {
        return $this->repo->getUserReservationsFiltered($userId, $search, $paymentStatus, $reservationStatus);
    }

    /**
     * Get all reservations for a media item
     */
    public function getMediaReservations(int $mediaId): array
    {
        return $this->repo->getMediaReservations($mediaId);
    }

    /**
     * Get all reservations (admin)
     */
    public function getAllReservations(): array
    {
        return $this->repo->getAllReservations();
    }

    /**
     * Update reservation status
     */
    public function updateStatus(int $reservationId, string $status): array
    {
        $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($status, $validStatuses)) {
            return ['success' => false, 'error' => 'Invalid status.'];
        }

        $result = $this->repo->updateStatus($reservationId, $status);
        if ($result) {
            return ['success' => true, 'message' => 'Reservation status updated.'];
        } else {
            return ['success' => false, 'error' => 'Failed to update status.'];
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $reservationId, string $paymentStatus): array
    {
        $validStatuses = ['pending', 'completed', 'failed'];
        if (!in_array($paymentStatus, $validStatuses)) {
            return ['success' => false, 'error' => 'Invalid payment status.'];
        }

        $result = $this->repo->updatePaymentStatus($reservationId, $paymentStatus);
        if ($result) {
            return ['success' => true, 'message' => 'Payment status updated.'];
        } else {
            return ['success' => false, 'error' => 'Failed to update payment status.'];
        }
    }

    /**
     * Cancel a reservation
     */
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
        if ($result) {
            return ['success' => true, 'message' => 'Reservation cancelled successfully.'];
        } else {
            return ['success' => false, 'error' => 'Failed to cancel reservation.'];
        }
    }

    /**
     * Delete a reservation
     */
    public function deleteReservation(int $reservationId): array
    {
        $result = $this->repo->delete($reservationId);
        if ($result) {
            return ['success' => true, 'message' => 'Reservation deleted successfully.'];
        } else {
            return ['success' => false, 'error' => 'Failed to delete reservation.'];
        }
    }
}
