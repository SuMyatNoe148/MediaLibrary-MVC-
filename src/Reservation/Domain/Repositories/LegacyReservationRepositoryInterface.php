<?php

namespace MediaLibrary\Reservation\Domain\Repositories;

interface LegacyReservationRepositoryInterface
{
    public function create(int $userId, int $mediaId, string $reservationDate, ?string $notes = null, ?float $amount = 0.00): int;
    public function findById(int $reservationId): ?array;
    public function getMediaById(int $mediaId): ?array;
    public function getUserReservations(int $userId): array;
    public function getUserReservationsFiltered(int $userId, ?string $search = null, ?string $paymentStatus = null, ?string $reservationStatus = null): array;
    public function getMediaReservations(int $mediaId): array;
    public function getAllReservations(): array;
    public function updateStatus(int $reservationId, string $status): bool;
    public function cancel(int $reservationId): bool;
    public function delete(int $reservationId): bool;
    public function updatePaymentStatus(int $reservationId, string $paymentStatus): bool;
    public function hasReservation(int $userId, int $mediaId, string $date): bool;
}
