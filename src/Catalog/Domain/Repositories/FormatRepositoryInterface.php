<?php

namespace MediaLibrary\Catalog\Domain\Repositories;

interface FormatRepositoryInterface
{
    public function get_format_drop_down($category = null): array;
    public function get_category_drop_down(): array;
    public function get_genres_drop_down($category = null): array;
}
