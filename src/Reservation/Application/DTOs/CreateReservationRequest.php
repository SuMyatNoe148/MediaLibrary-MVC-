<?php

namespace MediaLibrary\Reservation\Application\DTOs;

/**
 * Create Reservation Request DTO
 */
readonly class CreateReservationRequest
{
    public function __construct(
        public int $userId,
        public int $mediaId,
        public string $reservationDate,
        public ?string $notes = null,
        public ?float $amount = null
    ) {}

    public function validate(): array
    {
        $errors = [];

        if ($this->userId <= 0) {
            $errors[] = 'Invalid user ID';
        }

        if ($this->mediaId <= 0) {
            $errors[] = 'Invalid media ID';
        }

        if (empty($this->reservationDate)) {
            $errors[] = 'Reservation date is required';
        }

        return $errors;
    }
}
