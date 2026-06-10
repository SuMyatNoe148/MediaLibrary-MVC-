<?php

namespace MediaLibrary\Domain\ValueObjects;

use InvalidArgumentException;

final class InvoiceId
{
    private int $value;

    private function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Invoice ID cannot be negative');
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

    public function equals(InvoiceId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
