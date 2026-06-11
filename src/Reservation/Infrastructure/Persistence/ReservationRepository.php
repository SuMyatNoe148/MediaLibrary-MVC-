<?php

namespace MediaLibrary\Reservation\Infrastructure\Persistence;

use MediaLibrary\Reservation\Domain\Repositories\LegacyReservationRepositoryInterface;
use MediaLibrary\Shared\Infrastructure\Persistence\BaseRepository;
use PDO;

class ReservationRepository extends BaseRepository implements LegacyReservationRepositoryInterface
{
    public function create(int $userId, int $mediaId, string $reservationDate, ?string $notes = null, ?float $amount = 0.00): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Reservations (user_id, media_id, reservation_date, notes, amount) 
             VALUES (:user_id, :media_id, :reservation_date, :notes, :amount)"
        );
        $stmt->execute([':user_id' => $userId, ':media_id' => $mediaId, ':reservation_date' => $reservationDate, ':notes' => $notes, ':amount' => $amount]);
        return (int)$this->db->lastInsertId();
    }

    public function findById(int $reservationId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.username, m.title as media_title, m.img as media_img
            FROM Reservations r
            JOIN Users u ON r.user_id = u.user_id
            JOIN Media m ON r.media_id = m.media_id
            WHERE r.reservation_id = :reservation_id
        ");
        $stmt->execute([':reservation_id' => $reservationId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getMediaById(int $mediaId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Media WHERE media_id = :media_id");
        $stmt->execute([':media_id' => $mediaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getUserReservations(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT r.*, m.title as media_title, m.img as media_img, mt.category
            FROM Reservations r
            JOIN Media m ON r.media_id = m.media_id
            JOIN Media_Types mt ON m.media_types_id = mt.media_types_id
            WHERE r.user_id = :user_id
            ORDER BY r.reservation_date DESC, r.created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserReservationsFiltered(int $userId, ?string $search = null, ?string $paymentStatus = null, ?string $reservationStatus = null): array
    {
        $sql = "
            SELECT r.*, m.title as media_title, m.img as media_img, mt.category
            FROM Reservations r
            JOIN Media m ON r.media_id = m.media_id
            JOIN Media_Types mt ON m.media_types_id = mt.media_types_id
            WHERE r.user_id = :user_id
        ";
        $params = [':user_id' => $userId];

        if (!empty($search)) { $sql .= " AND m.title LIKE :search"; $params[':search'] = '%' . $search . '%'; }
        if (!empty($paymentStatus)) { $sql .= " AND r.payment_status = :payment_status"; $params[':payment_status'] = $paymentStatus; }
        if (!empty($reservationStatus)) { $sql .= " AND r.status = :reservation_status"; $params[':reservation_status'] = $reservationStatus; }

        $sql .= " ORDER BY r.reservation_date DESC, r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMediaReservations(int $mediaId): array
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.username FROM Reservations r
            JOIN Users u ON r.user_id = u.user_id
            WHERE r.media_id = :media_id ORDER BY r.reservation_date DESC
        ");
        $stmt->execute([':media_id' => $mediaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllReservations(): array
    {
        $stmt = $this->db->query("
            SELECT r.*, u.username, m.title as media_title, mt.category
            FROM Reservations r
            JOIN Users u ON r.user_id = u.user_id
            JOIN Media m ON r.media_id = m.media_id
            JOIN Media_Types mt ON m.media_types_id = mt.media_types_id
            ORDER BY r.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $reservationId, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE Reservations SET status = :status WHERE reservation_id = :reservation_id");
        return $stmt->execute([':status' => $status, ':reservation_id' => $reservationId]);
    }

    public function cancel(int $reservationId): bool
    {
        return $this->updateStatus($reservationId, 'cancelled');
    }

    public function delete(int $reservationId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Reservations WHERE reservation_id = :reservation_id");
        return $stmt->execute([':reservation_id' => $reservationId]);
    }

    public function updatePaymentStatus(int $reservationId, string $paymentStatus): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE Reservations SET payment_status = :payment_status, processed_date = NOW() WHERE reservation_id = :reservation_id"
        );
        return $stmt->execute([':payment_status' => $paymentStatus, ':reservation_id' => $reservationId]);
    }

    public function hasReservation(int $userId, int $mediaId, string $date): bool
    {
        $stmt = $this->db->prepare(
            "SELECT 1 FROM Reservations WHERE user_id = :user_id AND media_id = :media_id AND reservation_date = :date 
             AND status IN ('pending', 'confirmed') LIMIT 1"
        );
        $stmt->execute([':user_id' => $userId, ':media_id' => $mediaId, ':date' => $date]);
        return (bool)$stmt->fetch();
    }
}
