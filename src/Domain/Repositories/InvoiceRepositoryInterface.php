<?php

namespace MediaLibrary\Domain\Repositories;

use MediaLibrary\Domain\Entities\Invoice;
use MediaLibrary\Domain\ValueObjects\InvoiceId;
use MediaLibrary\Domain\ValueObjects\ReservationId;
use MediaLibrary\Domain\ValueObjects\UserId;

/**
 * Interface for invoice data access operations (DDD Repository)
 */
interface InvoiceRepositoryInterface
{
    /**
     * Save invoice (create or update)
     */
    public function save(Invoice $invoice): Invoice;

    /**
     * Find invoice by ID
     */
    public function findById(InvoiceId $id): ?Invoice;

    /**
     * Find invoices by user ID
     * @return Invoice[]
     */
    public function findByUserId(UserId $userId): array;

    /**
     * Find invoice by reservation ID
     */
    public function findByReservationId(ReservationId $reservationId): ?Invoice;

    /**
     * Get all invoices
     * @return Invoice[]
     */
    public function findAll(): array;

    /**
     * Delete invoice
     */
    public function delete(InvoiceId $id): bool;
}
