<?php

namespace MediaLibrary\Catalog\Application\DTOs;

use MediaLibrary\Catalog\Domain\Entities\Media;

/**
 * Media DTO - Data Transfer Object
 */
readonly class MediaDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $image,
        public int $genreId,
        public string $format,
        public int $year,
        public int $mediaTypeId,
        public float $price,
        public string $formattedPrice
    ) {}

    public static function fromEntity(Media $media): self
    {
        return new self(
            id: $media->id()->value(),
            title: $media->title(),
            image: $media->image(),
            genreId: $media->genreId(),
            format: $media->format(),
            year: $media->year(),
            mediaTypeId: $media->mediaTypeId(),
            price: $media->price()->amount(),
            formattedPrice: '$' . number_format($media->price()->amount(), 2)
        );
    }

    public function toArray(): array
    {
        return [
            'media_id' => $this->id,
            'title' => $this->title,
            'img' => $this->image,
            'genre_id' => $this->genreId,
            'format' => $this->format,
            'year' => $this->year,
            'media_types_id' => $this->mediaTypeId,
            'price' => $this->price,
            'formatted_price' => $this->formattedPrice,
        ];
    }
}
