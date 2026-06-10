<?php

namespace MediaLibrary\Domain\Repositories;

use MediaLibrary\Domain\Entities\Reservation;
use MediaLibrary\Domain\ValueObjects\MediaId;
use MediaLibrary\Domain\ValueObjects\ReservationId;
use MediaLibrary\Domain\ValueObjects\UserId;

/**
 * Interface for reservation data access operations (DDD Repository)
 */
interface ReservationRepositoryInterface
{
    /**
     * Find reservation by ID
     */
    public function findById(ReservationId $reservationId): ?Reservation;

    /**
     * Save reservation (create or update)
     */
    public function save(Reservation $reservation): Reservation;

    /**
     * Get all reservations for a user
     * @return Reservation[]
     */
    public function findByUserId(UserId $userId): array;

    /**
     * Get all reservations for a media item
     * @return Reservation[]
     */
    public function findByMediaId(MediaId $mediaId): array;

    /**
     * Get all reservations
     * @return Reservation[]
     */
    public function findAll(): array;

    /**
     * Delete a reservation
     */
    public function delete(ReservationId $reservationId): bool;

    /**
     * Check if user has reservation for media on specific date
     */
    public function hasReservation(UserId $userId, MediaId $mediaId, string $date): bool;
}
