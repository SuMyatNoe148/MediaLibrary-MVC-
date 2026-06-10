<?php

namespace MediaLibrary\Domain\Repositories;

use MediaLibrary\Domain\Entities\Media;
use MediaLibrary\Domain\ValueObjects\MediaId;

/**
 * Defines methods for retrieving catalog data (DDD Repository)
 */
interface CatalogRepositoryInterface
{
    /**
     * Get total catalog item count
     */
    public function getCount(?string $category = null, ?string $search = null): int;

    /**
     * Get complete catalog list
     * @return Media[]
     */
    public function findAll(?int $limit = null, int $offset = 0): array;

    /**
     * Get catalog items by category
     * @return Media[]
     */
    public function findByCategory(string $category, ?int $limit = null, int $offset = 0): array;

    /**
     * Search catalog items by keyword and category
     * @return Media[]
     */
    public function search(string $search, ?string $category = null, ?int $limit = null, int $offset = 0): array;

    /**
     * Get random catalog items
     * @return Media[]
     */
    public function findRandom(): array;

    /**
     * Get a single catalog item by ID
     */
    public function findById(MediaId $id): ?Media;
}
