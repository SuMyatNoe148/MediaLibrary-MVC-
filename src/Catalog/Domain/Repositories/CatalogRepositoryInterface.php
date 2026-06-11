<?php

namespace MediaLibrary\Catalog\Domain\Repositories;

interface CatalogRepositoryInterface
{
    public function getcatalog_count($category = null, $search = null);
    public function get_full_catalog($limit = null, $offset = 0);
    public function get_category_catalog($category, $limit = null, $offset = 0);
    public function get_search_catalog($search, $category = null, $limit = null, $offset = 0);
    public function get_random_catalog();
    public function get_single_item($id);
}
