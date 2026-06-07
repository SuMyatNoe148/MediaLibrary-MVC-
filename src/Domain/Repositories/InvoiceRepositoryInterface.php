<?php

namespace MediaLibrary\Domain\Repositories;

interface InvoiceRepositoryInterface
{
    public function create(array $data): int;
    public function findById(int $id): ?array;
    public function findByUserId(int $userId): array;
    public function findByReservationId(int $reservationId): ?array;
    public function getAll(): array;
    public function getTotalRevenue(): float;
    public function getTotalInvoices(): int;
    public function getMonthlyRevenue(): array;
}
