<?php

namespace MediaLibrary\Domain\Repositories;

/**
 * Interface for reservation data access operations
 */
interface ReservationRepositoryInterface
{
    /**
     * Create a new reservation
     */
    public function create(int $userId, int $mediaId, string $reservationDate, ?string $notes = null, ?float $amount = 0.00): int;

    /**
     * Get reservation by ID
     */
    public function findById(int $reservationId): ?array;

    /**
     * Get all reservations for a user
     */
    public function getUserReservations(int $userId): array;

    /**
     * Get filtered reservations for a user
     */
    public function getUserReservationsFiltered(int $userId, ?string $search = null, ?string $paymentStatus = null, ?string $reservationStatus = null): array;

    /**
     * Get all reservations for a media item
     */
    public function getMediaReservations(int $mediaId): array;

    /**
     * Get all reservations (admin)
     */
    public function getAllReservations(): array;

    /**
     * Update reservation status
     */
    public function updateStatus(int $reservationId, string $status): bool;

    /**
     * Cancel a reservation
     */
    public function cancel(int $reservationId): bool;

    /**
     * Delete a reservation
     */
    public function delete(int $reservationId): bool;

    /**
     * Check if user has reservation for media on specific date
     */
    public function hasReservation(int $userId, int $mediaId, string $date): bool;
}
