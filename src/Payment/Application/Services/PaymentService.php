<?php

namespace MediaLibrary\Payment\Application\Services;

use MediaLibrary\Payment\Domain\Repositories\LegacyPaymentRepositoryInterface;
use MediaLibrary\Payment\Infrastructure\Persistence\PaymentRepository;

class PaymentService
{
    private LegacyPaymentRepositoryInterface $repo;

    public function __construct(?LegacyPaymentRepositoryInterface $repo = null)
    {
        if ($repo === null) {
            $db = \Database::getConnection();
            $repo = new PaymentRepository($db);
        }
        $this->repo = $repo;
    }

    public function createPayment(int $userId, ?int $reservationId, string $stripeSessionId, float $amount, string $paymentType = 'reservation'): array
    {
        try {
            $paymentId = $this->repo->create($userId, $reservationId, $stripeSessionId, $amount, $paymentType);
            return ['success' => true, 'payment_id' => $paymentId];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to create payment record.'];
        }
    }

    public function updatePaymentStatus(int $paymentId, string $status): array
    {
        try {
            $this->repo->updatePaymentStatus($paymentId, $status);
            return ['success' => true, 'message' => 'Payment status updated.'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to update payment status.'];
        }
    }

    public function findByStripeSessionId(string $stripeSessionId): ?array
    {
        return $this->repo->findByStripeSessionId($stripeSessionId);
    }

    public function getUserPayments(int $userId): array
    {
        return $this->repo->getByUserId($userId);
    }

    public function getPaymentById(int $paymentId): ?array
    {
        return $this->repo->getById($paymentId);
    }
}
