<?php

namespace MediaLibrary\Catalog\Domain\Repositories;

use MediaLibrary\Catalog\Domain\Entities\Media;
use MediaLibrary\Shared\Domain\ValueObjects\MediaId;

/**
 * Media Repository Interface - Catalog Bounded Context
 */
interface MediaRepositoryInterface
{
    /**
     * Find media by ID
     */
    public function findById(MediaId $id): ?Media;

    /**
     * Find all media
     * @return Media[]
     */
    public function findAll(?int $limit = null, int $offset = 0): array;

    /**
     * Find media by category
     * @return Media[]
     */
    public function findByCategory(string $category, ?int $limit = null, int $offset = 0): array;

    /**
     * Search media
     * @return Media[]
     */
    public function search(string $search, ?string $category = null, ?int $limit = null, int $offset = 0): array;

    /**
     * Save media
     */
    public function save(Media $media): Media;

    /**
     * Delete media
     */
    public function delete(MediaId $id): bool;

    /**
     * Get total count
     */
    public function count(?string $category = null, ?string $search = null): int;
}
