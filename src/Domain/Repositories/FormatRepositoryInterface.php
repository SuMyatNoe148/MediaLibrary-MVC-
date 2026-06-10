<?php

namespace MediaLibrary\Domain\Repositories;

/**
 * Defines methods for retrieving format, category, and genre data (Reference Data)
 */
interface FormatRepositoryInterface
{
    /**
     * Get format dropdown list
     */
    public function getFormats(?string $category = null): array;

    /**
     * Get category dropdown list
     */
    public function getCategories(): array;

    /**
     * Get genres dropdown list
     */
    public function getGenres(?string $category = null): array;
}
