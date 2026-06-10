<?php

namespace MediaLibrary\Domain\ValueObjects;

use InvalidArgumentException;

final class Email
{
    private string $value;

    private function __construct(string $value)
    {
        $value = strtolower(trim($value));
        
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format: {$value}");
        }
        
        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    public function domain(): string
    {
        return substr(strrchr($this->value, '@'), 1);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
