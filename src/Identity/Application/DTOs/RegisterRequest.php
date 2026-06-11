<?php

namespace MediaLibrary\Identity\Application\DTOs;

/**
 * Register Request DTO
 */
readonly class RegisterRequest
{
    public function __construct(
        public string $username,
        public string $email,
        public string $password,
        public ?string $avatar = null,
        public ?string $bio = null
    ) {}

    public function validate(): array
    {
        $errors = [];

        if (strlen($this->username) < 3) {
            $errors[] = 'Username must be at least 3 characters';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if (strlen($this->password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        return $errors;
    }
}
