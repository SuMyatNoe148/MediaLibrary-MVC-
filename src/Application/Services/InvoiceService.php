<?php

namespace MediaLibrary\Application\Services;

use MediaLibrary\Domain\Repositories\InvoiceRepositoryInterface;
use MediaLibrary\Infrastructure\Persistence\ReservationRepository;

class InvoiceService
{
    private InvoiceRepositoryInterface $invoiceRepository;
    private ReservationRepository $reservationRepository;

    public function __construct(
        ?InvoiceRepositoryInterface $invoiceRepository = null,
        ?ReservationRepository $reservationRepository = null
    ) {
        $db = \Database::getConnection();
        $this->invoiceRepository = $invoiceRepository ?? new \MediaLibrary\Infrastructure\Persistence\InvoiceRepository($db);
        $this->reservationRepository = $reservationRepository ?? new \MediaLibrary\Infrastructure\Persistence\ReservationRepository($db);
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

        $invoiceNumber = $this->generateInvoiceNumber();

        // Check if invoice already exists for this reservation
        $existingInvoice = $this->invoiceRepository->findByReservationId($reservationId);
        if ($existingInvoice) {
            return $existingInvoice['invoice_id'];
        }

        $invoiceId = $this->invoiceRepository->create([
            'invoice_number' => $invoiceNumber,
            'reservation_id' => $reservationId,
            'user_id' => $reservation['user_id'],
            'payment_intent_id' => $paymentIntentId,
            'amount' => $amount,
            'currency' => strtoupper($currency),
            'status' => 'PAID'
        ]);

        return $invoiceId;
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
