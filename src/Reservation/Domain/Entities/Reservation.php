<?php

namespace MediaLibrary\Reservation\Domain\Entities;

use DateTimeImmutable;
use MediaLibrary\Reservation\Domain\Enums\ReservationStatus;
use MediaLibrary\Reservation\Domain\Enums\PaymentStatus;
use MediaLibrary\Shared\Domain\ValueObjects\ReservationId;
use MediaLibrary\Shared\Domain\ValueObjects\UserId;
use MediaLibrary\Shared\Domain\ValueObjects\MediaId;
use MediaLibrary\Shared\Domain\ValueObjects\Money;

/**
 * Reservation Entity - Reservation Bounded Context Aggregate Root
 */
class Reservation
{
    private ReservationId $id;
    private UserId $userId;
    private MediaId $mediaId;
    private DateTimeImmutable $reservationDate;
    private ReservationStatus $status;
    private ?string $notes;
    private Money $amount;
    private PaymentStatus $paymentStatus;
    private ?DateTimeImmutable $processedDate;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        ReservationId $id,
        UserId $userId,
        MediaId $mediaId,
        DateTimeImmutable $reservationDate,
        ReservationStatus $status,
        ?string $notes,
        Money $amount,
        PaymentStatus $paymentStatus,
        ?DateTimeImmutable $processedDate,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->mediaId = $mediaId;
        $this->reservationDate = $reservationDate;
        $this->status = $status;
        $this->notes = $notes;
        $this->amount = $amount;
        $this->paymentStatus = $paymentStatus;
        $this->processedDate = $processedDate;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
    }

    public static function create(
        UserId $userId,
        MediaId $mediaId,
        DateTimeImmutable $reservationDate,
        Money $amount,
        ?string $notes = null
    ): self {
        return new self(
            ReservationId::generate(),
            $userId,
            $mediaId,
            $reservationDate,
            ReservationStatus::PENDING,
            $notes,
            $amount,
            PaymentStatus::PENDING,
            null
        );
    }

    public function id(): ReservationId { return $this->id; }
    public function userId(): UserId { return $this->userId; }
    public function mediaId(): MediaId { return $this->mediaId; }
    public function reservationDate(): DateTimeImmutable { return $this->reservationDate; }
    public function status(): ReservationStatus { return $this->status; }
    public function notes(): ?string { return $this->notes; }
    public function amount(): Money { return $this->amount; }
    public function paymentStatus(): PaymentStatus { return $this->paymentStatus; }
    public function processedDate(): ?DateTimeImmutable { return $this->processedDate; }

    public function confirm(): void
    {
        $this->status = ReservationStatus::CONFIRMED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function cancel(): void
    {
        $this->status = ReservationStatus::CANCELLED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function complete(): void
    {
        $this->status = ReservationStatus::COMPLETED;
        $this->processedDate = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function markPaymentCompleted(): void
    {
        $this->paymentStatus = PaymentStatus::COMPLETED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'reservation_id' => $this->id->value(),
            'user_id' => $this->userId->value(),
            'media_id' => $this->mediaId->value(),
            'reservation_date' => $this->reservationDate->format('Y-m-d'),
            'status' => $this->status->value(),
            'notes' => $this->notes,
            'amount' => $this->amount->amount(),
            'payment_status' => $this->paymentStatus->value(),
            'processed_date' => $this->processedDate?->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
