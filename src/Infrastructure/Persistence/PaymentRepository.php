<?php

namespace MediaLibrary\Infrastructure\Persistence;

use MediaLibrary\Domain\Repositories\PaymentRepositoryInterface;
use PDO;

/**
 * Payment Repository
 * Handles payment database operations
 */
class PaymentRepository implements PaymentRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new payment record
     */
    public function create(int $userId, ?int $reservationId, string $stripeSessionId, float $amount, string $paymentType = 'reservation'): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Payments (user_id, reservation_id, stripe_session_id, amount, payment_type, payment_status) 
             VALUES (:user_id, :reservation_id, :stripe_session_id, :amount, :payment_type, 'pending')"
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':reservation_id' => $reservationId,
            ':stripe_session_id' => $stripeSessionId,
            ':amount' => $amount,
            ':payment_type' => $paymentType
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Find payment by Stripe session ID
     */
    public function findByStripeSessionId(string $stripeSessionId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Payments WHERE stripe_session_id = :stripe_session_id LIMIT 1");
        $stmt->execute([':stripe_session_id' => $stripeSessionId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $paymentId, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE Payments SET payment_status = :payment_status WHERE payment_id = :payment_id");
        return $stmt->execute([
            ':payment_status' => $status,
            ':payment_id' => $paymentId
        ]);
    }

    /**
     * Get payments by user ID
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, r.reservation_id, m.title as media_title 
            FROM Payments p
            LEFT JOIN Reservations r ON p.reservation_id = r.reservation_id
            LEFT JOIN Media m ON r.media_id = m.media_id
            WHERE p.user_id = :user_id
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get payment by ID
     */
    public function getById(int $paymentId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Payments WHERE payment_id = :payment_id LIMIT 1");
        $stmt->execute([':payment_id' => $paymentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
