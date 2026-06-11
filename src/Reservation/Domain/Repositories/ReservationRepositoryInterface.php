<?php

namespace MediaLibrary\Reservation\Domain\Repositories;

use MediaLibrary\Reservation\Domain\Entities\Reservation;
use MediaLibrary\Shared\Domain\ValueObjects\ReservationId;
use MediaLibrary\Shared\Domain\ValueObjects\UserId;
use MediaLibrary\Shared\Domain\ValueObjects\MediaId;

/**
 * Reservation Repository Interface
 */
interface ReservationRepositoryInterface
{
    /**
     * Find reservation by ID
     */
    public function findById(ReservationId $id): ?Reservation;

    /**
     * Save reservation
     */
    public function save(Reservation $reservation): Reservation;

    /**
     * Find reservations by user
     * @return Reservation[]
     */
    public function findByUserId(UserId $userId): array;

    /**
     * Find reservations by media
     * @return Reservation[]
     */
    public function findByMediaId(MediaId $mediaId): array;

    /**
     * Check if user has reservation on date
     */
    public function hasReservation(UserId $userId, MediaId $mediaId, string $date): bool;

    /**
     * Delete reservation
     */
    public function delete(ReservationId $id): bool;
}
