<?php

namespace MediaLibrary\Shared\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Shared UserId Value Object
 * Used across all bounded contexts
 */
final class UserId
{
    private int $value;

    private function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('User ID cannot be negative');
        }
        $this->value = $value;
    }

    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self(0);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(UserId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
