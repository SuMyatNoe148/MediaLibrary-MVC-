<?php

namespace MediaLibrary\Payment\Domain\Repositories;

use MediaLibrary\Payment\Domain\Entities\Invoice;
use MediaLibrary\Shared\Domain\ValueObjects\InvoiceId;
use MediaLibrary\Shared\Domain\ValueObjects\UserId;
use MediaLibrary\Shared\Domain\ValueObjects\ReservationId;

/**
 * Invoice Repository Interface
 */
interface InvoiceRepositoryInterface
{
    /**
     * Save invoice
     */
    public function save(Invoice $invoice): Invoice;

    /**
     * Find invoice by ID
     */
    public function findById(InvoiceId $id): ?Invoice;

    /**
     * Find invoices by user
     * @return Invoice[]
     */
    public function findByUserId(UserId $userId): array;

    /**
     * Find invoice by reservation
     */
    public function findByReservationId(ReservationId $reservationId): ?Invoice;

    /**
     * Delete invoice
     */
    public function delete(InvoiceId $id): bool;
}
