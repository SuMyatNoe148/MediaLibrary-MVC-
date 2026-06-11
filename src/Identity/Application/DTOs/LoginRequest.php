<?php

namespace MediaLibrary\Identity\Application\DTOs;

/**
 * Login Request DTO
 */
readonly class LoginRequest
{
    public function __construct(
        public string $email,
        public string $password
    ) {}

    public function validate(): array
    {
        $errors = [];

        if (empty($this->email)) {
            $errors[] = 'Email is required';
        }

        if (empty($this->password)) {
            $errors[] = 'Password is required';
        }

        return $errors;
    }
}
