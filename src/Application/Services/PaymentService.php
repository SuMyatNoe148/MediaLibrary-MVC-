<?php

namespace MediaLibrary\Application\Services;

use MediaLibrary\Domain\Repositories\PaymentRepositoryInterface;
use MediaLibrary\Infrastructure\Persistence\PaymentRepository;

/**
 * Payment Service
 * Handles payment-related business logic
 */
class PaymentService
{
    private PaymentRepositoryInterface $repo;

    public function __construct(?PaymentRepositoryInterface $repo = null)
    {
        if ($repo === null) {
            $db = \Database::getConnection();
            $repo = new PaymentRepository($db);
        }
        $this->repo = $repo;
    }

    /**
     * Create a new payment record
     */
    public function createPayment(int $userId, ?int $reservationId, string $stripeSessionId, float $amount, string $paymentType = 'reservation'): array
    {
        try {
            $paymentId = $this->repo->create($userId, $reservationId, $stripeSessionId, $amount, $paymentType);
            return ['success' => true, 'payment_id' => $paymentId];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to create payment record.'];
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $paymentId, string $status): array
    {
        try {
            $this->repo->updatePaymentStatus($paymentId, $status);
            return ['success' => true, 'message' => 'Payment status updated.'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to update payment status.'];
        }
    }

    /**
     * Find payment by Stripe session ID
     */
    public function findByStripeSessionId(string $stripeSessionId): ?array
    {
        return $this->repo->findByStripeSessionId($stripeSessionId);
    }

    /**
     * Get user payments
     */
    public function getUserPayments(int $userId): array
    {
        return $this->repo->getByUserId($userId);
    }

    /**
     * Get payment by ID
     */
    public function getPaymentById(int $paymentId): ?array
    {
        return $this->repo->getById($paymentId);
    }
}
