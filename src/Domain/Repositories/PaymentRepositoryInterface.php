<?php

namespace MediaLibrary\Domain\Repositories;

use MediaLibrary\Domain\ValueObjects\Money;
use MediaLibrary\Domain\ValueObjects\ReservationId;
use MediaLibrary\Domain\ValueObjects\UserId;

/**
 * Payment Repository Interface (DDD)
 */
interface PaymentRepositoryInterface
{
    /**
     * Create payment record
     */
    public function create(
        UserId $userId,
        ?ReservationId $reservationId,
        string $stripeSessionId,
        Money $amount,
        string $paymentType = 'reservation'
    ): int;

    /**
     * Find payment by Stripe session ID
     */
    public function findByStripeSessionId(string $stripeSessionId): ?array;

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $paymentId, string $status): bool;

    /**
     * Get payments by user ID
     */
    public function getByUserId(UserId $userId): array;

    /**
     * Get payment by ID
     */
    public function getById(int $paymentId): ?array;
}
