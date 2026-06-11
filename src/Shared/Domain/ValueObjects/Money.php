<?php

namespace MediaLibrary\Shared\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Shared Money Value Object
 * Used across all bounded contexts
 */
final class Money
{
    private float $amount;
    private string $currency;

    private function __construct(float $amount, string $currency = 'USD')
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Amount cannot be negative');
        }
        
        $this->amount = $amount;
        $this->currency = strtoupper($currency);
    }

    public static function fromFloat(float $amount, string $currency = 'USD'): self
    {
        return new self($amount, $currency);
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function add(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Cannot add different currencies');
        }
        return new self($this->amount + $other->amount, $this->currency);
    }

    public function formatted(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function __toString(): string
    {
        return $this->formatted();
    }
}
