<?php

namespace MediaLibrary\Payment\Application\DTOs;

use MediaLibrary\Payment\Domain\Entities\Invoice;

/**
 * Invoice DTO
 */
readonly class InvoiceDto
{
    public function __construct(
        public int $id,
        public string $invoiceNumber,
        public ?int $reservationId,
        public int $userId,
        public float $amount,
        public string $status,
        public string $createdAt
    ) {}

    public static function fromEntity(Invoice $invoice): self
    {
        return new self(
            id: $invoice->id()->value(),
            invoiceNumber: $invoice->invoiceNumber(),
            reservationId: $invoice->reservationId()?->value(),
            userId: $invoice->userId()->value(),
            amount: $invoice->amount()->amount(),
            status: $invoice->status()->value(),
            createdAt: $invoice->createdAt()->format('Y-m-d H:i:s')
        );
    }

    public function toArray(): array
    {
        return [
            'invoice_id' => $this->id,
            'invoice_number' => $this->invoiceNumber,
            'reservation_id' => $this->reservationId,
            'user_id' => $this->userId,
            'amount' => $this->amount,
            'status' => $this->status,
            'created_at' => $this->createdAt,
        ];
    }
}
