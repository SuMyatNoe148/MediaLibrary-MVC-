<?php

namespace MediaLibrary\Catalog\Domain\Entities;

use MediaLibrary\Shared\Domain\ValueObjects\MediaId;
use MediaLibrary\Shared\Domain\ValueObjects\Money;

/**
 * Media Entity - Catalog Bounded Context Aggregate Root
 */
class Media
{
    private MediaId $id;
    private string $title;
    private string $image;
    private int $genreId;
    private string $format;
    private int $year;
    private int $mediaTypeId;
    private Money $price;

    public function __construct(
        MediaId $id,
        string $title,
        string $image,
        int $genreId,
        string $format,
        int $year,
        int $mediaTypeId,
        Money $price
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->image = $image;
        $this->genreId = $genreId;
        $this->format = $format;
        $this->year = $year;
        $this->mediaTypeId = $mediaTypeId;
        $this->price = $price;
    }

    public static function create(
        string $title,
        string $image,
        int $genreId,
        string $format,
        int $year,
        int $mediaTypeId,
        Money $price
    ): self {
        return new self(
            MediaId::generate(),
            $title,
            $image,
            $genreId,
            $format,
            $year,
            $mediaTypeId,
            $price
        );
    }

    public function id(): MediaId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function image(): string
    {
        return $this->image;
    }

    public function genreId(): int
    {
        return $this->genreId;
    }

    public function format(): string
    {
        return $this->format;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function mediaTypeId(): int
    {
        return $this->mediaTypeId;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function updatePrice(Money $newPrice): void
    {
        $this->price = $newPrice;
    }

    public function updateImage(string $image): void
    {
        $this->image = $image;
    }

    public function toArray(): array
    {
        return [
            'media_id' => $this->id->value(),
            'title' => $this->title,
            'img' => $this->image,
            'genre_id' => $this->genreId,
            'format' => $this->format,
            'year' => $this->year,
            'media_types_id' => $this->mediaTypeId,
            'price' => $this->price->amount(),
        ];
    }
}
