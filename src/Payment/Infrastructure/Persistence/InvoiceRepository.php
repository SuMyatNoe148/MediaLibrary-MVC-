<?php

namespace MediaLibrary\Payment\Infrastructure\Persistence;

use MediaLibrary\Payment\Domain\Repositories\LegacyInvoiceRepositoryInterface;
use MediaLibrary\Shared\Infrastructure\Persistence\BaseRepository;
use PDO;

class InvoiceRepository extends BaseRepository implements LegacyInvoiceRepositoryInterface
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Invoices (invoice_number, reservation_id, user_id, payment_intent_id, amount, currency, status)
             VALUES (:invoice_number, :reservation_id, :user_id, :payment_intent_id, :amount, :currency, :status)"
        );
        $stmt->execute([
            ':invoice_number'    => $data['invoice_number'],
            ':reservation_id'    => $data['reservation_id'],
            ':user_id'           => $data['user_id'],
            ':payment_intent_id' => $data['payment_intent_id'] ?? null,
            ':amount'            => $data['amount'],
            ':currency'          => $data['currency'] ?? 'USD',
            ':status'            => $data['status'] ?? 'PAID'
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT i.*, r.reservation_date, m.title as media_title, u.username as user_name, u.email as user_email
            FROM Invoices i
            JOIN Reservations r ON i.reservation_id = r.reservation_id
            JOIN Media m ON r.media_id = m.media_id
            JOIN Users u ON i.user_id = u.user_id
            WHERE i.invoice_id = :invoice_id
        ");
        $stmt->execute([':invoice_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT i.*, m.title as media_title, u.username as user_name, u.email as user_email
            FROM Invoices i
            JOIN Reservations r ON i.reservation_id = r.reservation_id
            JOIN Media m ON r.media_id = m.media_id
            JOIN Users u ON i.user_id = u.user_id
            WHERE i.user_id = :user_id
            ORDER BY i.created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByReservationId(int $reservationId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Invoices WHERE reservation_id = :reservation_id");
        $stmt->execute([':reservation_id' => $reservationId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT i.*, u.username as user_name, u.email as user_email, m.title as media_title
            FROM Invoices i
            JOIN Users u ON i.user_id = u.user_id
            JOIN Reservations r ON i.reservation_id = r.reservation_id
            JOIN Media m ON r.media_id = m.media_id
            ORDER BY i.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalRevenue(): float
    {
        $stmt = $this->db->query("SELECT SUM(amount) as total FROM Invoices WHERE status = 'PAID'");
        return (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function getTotalInvoices(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM Invoices");
        return (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
    }

    public function getMonthlyRevenue(): array
    {
        $stmt = $this->db->query("
            SELECT MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as revenue
            FROM Invoices WHERE status = 'PAID'
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY YEAR(created_at) DESC, MONTH(created_at) DESC
            LIMIT 12
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
