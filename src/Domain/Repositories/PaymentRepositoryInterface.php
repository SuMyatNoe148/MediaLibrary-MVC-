<?php

namespace MediaLibrary\Domain\Repositories;

/**
 * Payment Repository Interface
 */
interface PaymentRepositoryInterface
{
    public function create(int $userId, ?int $reservationId, string $stripeSessionId, float $amount, string $paymentType = 'reservation'): int;
    public function findByStripeSessionId(string $stripeSessionId): ?array;
    public function updatePaymentStatus(int $paymentId, string $status): bool;
    public function getByUserId(int $userId): array;
    public function getById(int $paymentId): ?array;
}
