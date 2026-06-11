<?php

namespace MediaLibrary\Payment\Domain\Repositories;

interface LegacyPaymentRepositoryInterface
{
    public function create(int $userId, ?int $reservationId, string $stripeSessionId, float $amount, string $paymentType = 'reservation'): int;
    public function findByStripeSessionId(string $stripeSessionId): ?array;
    public function updatePaymentStatus(int $paymentId, string $status): bool;
    public function getByUserId(int $userId): array;
    public function getById(int $paymentId): ?array;
}
