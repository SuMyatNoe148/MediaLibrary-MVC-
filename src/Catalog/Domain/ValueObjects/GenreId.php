<?php

namespace MediaLibrary\Catalog\Domain\ValueObjects;

use InvalidArgumentException;

final class GenreId
{
    private int $value;

    private function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Genre ID cannot be negative');
        }
        $this->value = $value;
    }

    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(GenreId $other): bool
    {
        return $this->value === $other->value;
    }
}
