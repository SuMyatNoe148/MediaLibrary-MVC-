<?php

namespace MediaLibrary\Domain\ValueObjects;

use InvalidArgumentException;

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
            throw new InvalidArgumentException('Cannot add money with different currencies');
        }
        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Cannot subtract money with different currencies');
        }
        return new self($this->amount - $other->amount, $this->currency);
    }

    public function equals(Money $other): bool
    {
        return $this->currency === $other->currency && 
               abs($this->amount - $other->amount) < 0.01;
    }

    public function greaterThan(Money $other): bool
    {
        return $this->amount > $other->amount;
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
