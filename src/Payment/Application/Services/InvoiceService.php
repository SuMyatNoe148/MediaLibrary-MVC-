<?php

namespace MediaLibrary\Payment\Application\Services;

use MediaLibrary\Payment\Domain\Repositories\LegacyInvoiceRepositoryInterface;
use MediaLibrary\Payment\Infrastructure\Persistence\InvoiceRepository;
use MediaLibrary\Payment\Infrastructure\Persistence\ReservationRepository;

class InvoiceService
{
    private LegacyInvoiceRepositoryInterface $invoiceRepository;
    private ReservationRepository $reservationRepository;

    public function __construct(
        ?LegacyInvoiceRepositoryInterface $invoiceRepository = null,
        ?ReservationRepository $reservationRepository = null
    ) {
        $db = \Database::getConnection();
        $this->invoiceRepository = $invoiceRepository ?? new InvoiceRepository($db);
        $this->reservationRepository = $reservationRepository ?? new ReservationRepository($db);
    }

    public function generateInvoiceNumber(): string
    {
        return 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
    }

    public function createInvoice(int $reservationId, ?string $paymentIntentId, float $amount, string $currency = 'USD'): int
    {
        $reservation = $this->reservationRepository->findById($reservationId);
        if (!$reservation) {
            throw new \Exception('Reservation not found');
        }
        $existing = $this->invoiceRepository->findByReservationId($reservationId);
        if ($existing) {
            return $existing['invoice_id'];
        }
        return $this->invoiceRepository->create([
            'invoice_number'   => $this->generateInvoiceNumber(),
            'reservation_id'   => $reservationId,
            'user_id'          => $reservation['user_id'],
            'payment_intent_id'=> $paymentIntentId,
            'amount'           => $amount,
            'currency'         => strtoupper($currency),
            'status'           => 'PAID'
        ]);
    }

    public function getUserInvoices(int $userId): array
    {
        return $this->invoiceRepository->findByUserId($userId);
    }

    public function getInvoiceById(int $id): ?array
    {
        return $this->invoiceRepository->findById($id);
    }

    public function getAllInvoices(): array
    {
        return $this->invoiceRepository->getAll();
    }

    public function getTotalRevenue(): float
    {
        return $this->invoiceRepository->getTotalRevenue();
    }

    public function getTotalInvoices(): int
    {
        return $this->invoiceRepository->getTotalInvoices();
    }

    public function getMonthlyRevenue(): array
    {
        return $this->invoiceRepository->getMonthlyRevenue();
    }
}
