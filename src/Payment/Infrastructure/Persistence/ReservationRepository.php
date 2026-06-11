<?php

namespace MediaLibrary\Payment\Infrastructure\Persistence;

use MediaLibrary\Shared\Infrastructure\Persistence\BaseRepository;
use PDO;

/**
 * Read-only reservation access for Payment context
 */
class ReservationRepository extends BaseRepository
{
    public function findById(int $reservationId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.username, m.title as media_title
            FROM Reservations r
            JOIN Users u ON r.user_id = u.user_id
            JOIN Media m ON r.media_id = m.media_id
            WHERE r.reservation_id = :reservation_id
        ");
        $stmt->execute([':reservation_id' => $reservationId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
