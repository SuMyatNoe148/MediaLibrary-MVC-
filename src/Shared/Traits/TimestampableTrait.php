<?php

namespace MediaLibrary\Shared\Traits;

use DateTimeImmutable;

trait TimestampableTrait
{
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    private function initTimestamps(?DateTimeImmutable $createdAt = null, ?DateTimeImmutable $updatedAt = null): void
    {
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
    }

    private function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
