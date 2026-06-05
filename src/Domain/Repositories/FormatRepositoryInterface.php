<?php

namespace MediaLibrary\Domain\Repositories;

/**
 * Defines methods for retrieving format, category, and genre data.
 */
interface FormatRepositoryInterface
{
    // Get format dropdown list
    public function get_format_drop_down($category = null);

    // Get category dropdown list
    public function get_category_drop_down();

    // Get genres dropdown list
    public function get_genres_drop_down($category = null);
}
