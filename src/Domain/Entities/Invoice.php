<?php

namespace MediaLibrary\Domain\Entities;

use DateTimeImmutable;
use MediaLibrary\Domain\ValueObjects\InvoiceId;
use MediaLibrary\Domain\ValueObjects\Money;
use MediaLibrary\Domain\ValueObjects\ReservationId;
use MediaLibrary\Domain\ValueObjects\UserId;
use MediaLibrary\Domain\Enums\InvoiceStatus;

/**
 * Invoice Entity
 */
class Invoice
{
    private InvoiceId $id;
    private string $invoiceNumber;
    private ?ReservationId $reservationId;
    private UserId $userId;
    private Money $amount;
    private InvoiceStatus $status;
    private DateTimeImmutable $createdAt;

    public function __construct(
        InvoiceId $id,
        string $invoiceNumber,
        ?ReservationId $reservationId,
        UserId $userId,
        Money $amount,
        InvoiceStatus $status,
        ?DateTimeImmutable $createdAt = null
    ) {
        $this->id = $id;
        $this->invoiceNumber = $invoiceNumber;
        $this->reservationId = $reservationId;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->status = $status;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
    }

    public static function create(
        string $invoiceNumber,
        UserId $userId,
        Money $amount,
        ?ReservationId $reservationId = null
    ): self {
        return new self(
            InvoiceId::generate(),
            $invoiceNumber,
            $reservationId,
            $userId,
            $amount,
            InvoiceStatus::PENDING
        );
    }

    public function id(): InvoiceId
    {
        return $this->id;
    }

    public function invoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function reservationId(): ?ReservationId
    {
        return $this->reservationId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function status(): InvoiceStatus
    {
        return $this->status;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function markAsPaid(): void
    {
        $this->status = InvoiceStatus::PAID;
    }

    public function cancel(): void
    {
        $this->status = InvoiceStatus::CANCELLED;
    }

    public function toArray(): array
    {
        return [
            'invoice_id' => $this->id->value(),
            'invoice_number' => $this->invoiceNumber,
            'reservation_id' => $this->reservationId?->value(),
            'user_id' => $this->userId->value(),
            'amount' => $this->amount->amount(),
            'status' => $this->status->value(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
