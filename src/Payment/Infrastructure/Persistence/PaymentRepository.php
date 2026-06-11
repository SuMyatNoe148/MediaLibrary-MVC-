<?php

namespace MediaLibrary\Payment\Infrastructure\Persistence;

use MediaLibrary\Payment\Domain\Repositories\LegacyPaymentRepositoryInterface;
use MediaLibrary\Shared\Infrastructure\Persistence\BaseRepository;
use PDO;

class PaymentRepository extends BaseRepository implements LegacyPaymentRepositoryInterface
{
    public function create(int $userId, ?int $reservationId, string $stripeSessionId, float $amount, string $paymentType = 'reservation'): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Payments (user_id, reservation_id, stripe_session_id, amount, payment_type, payment_status)
             VALUES (:user_id, :reservation_id, :stripe_session_id, :amount, :payment_type, 'pending')"
        );
        $stmt->execute([
            ':user_id'           => $userId,
            ':reservation_id'    => $reservationId,
            ':stripe_session_id' => $stripeSessionId,
            ':amount'            => $amount,
            ':payment_type'      => $paymentType
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function findByStripeSessionId(string $stripeSessionId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Payments WHERE stripe_session_id = :stripe_session_id LIMIT 1");
        $stmt->execute([':stripe_session_id' => $stripeSessionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updatePaymentStatus(int $paymentId, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE Payments SET payment_status = :payment_status WHERE payment_id = :payment_id");
        return $stmt->execute([':payment_status' => $status, ':payment_id' => $paymentId]);
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, r.reservation_id, m.title as media_title
            FROM Payments p
            LEFT JOIN Reservations r ON p.reservation_id = r.reservation_id
            LEFT JOIN Media m ON r.media_id = m.media_id
            WHERE p.user_id = :user_id ORDER BY p.created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $paymentId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Payments WHERE payment_id = :payment_id LIMIT 1");
        $stmt->execute([':payment_id' => $paymentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
