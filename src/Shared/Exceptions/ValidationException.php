<?php

namespace MediaLibrary\Shared\Exceptions;

class ValidationException extends DomainException
{
    private array $errors;

    public function __construct(array $errors)
    {
        parent::__construct(implode(', ', $errors));
        $this->errors = $errors;
    }

    public static function withErrors(array $errors): self
    {
        return new self($errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
