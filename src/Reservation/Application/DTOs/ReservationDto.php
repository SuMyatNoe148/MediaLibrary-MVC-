<?php

namespace MediaLibrary\Reservation\Application\DTOs;

use MediaLibrary\Reservation\Domain\Entities\Reservation;

/**
 * Reservation DTO
 */
readonly class ReservationDto
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $mediaId,
        public string $reservationDate,
        public string $status,
        public ?string $notes,
        public float $amount,
        public string $paymentStatus,
        public ?string $processedDate
    ) {}

    public static function fromEntity(Reservation $reservation): self
    {
        return new self(
            id: $reservation->id()->value(),
            userId: $reservation->userId()->value(),
            mediaId: $reservation->mediaId()->value(),
            reservationDate: $reservation->reservationDate()->format('Y-m-d'),
            status: $reservation->status()->value(),
            notes: $reservation->notes(),
            amount: $reservation->amount()->amount(),
            paymentStatus: $reservation->paymentStatus()->value(),
            processedDate: $reservation->processedDate()?->format('Y-m-d H:i:s')
        );
    }

    public function toArray(): array
    {
        return [
            'reservation_id' => $this->id,
            'user_id' => $this->userId,
            'media_id' => $this->mediaId,
            'reservation_date' => $this->reservationDate,
            'status' => $this->status,
            'notes' => $this->notes,
            'amount' => $this->amount,
            'payment_status' => $this->paymentStatus,
            'processed_date' => $this->processedDate,
        ];
    }
}
