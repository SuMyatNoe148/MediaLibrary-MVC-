<?php

namespace MediaLibrary\Catalog\Application\Services;

use MediaLibrary\Catalog\Domain\Repositories\CatalogRepositoryInterface;
use MediaLibrary\Catalog\Infrastructure\Persistence\CatalogRepository;

/**
 * Catalog Application Service
 * Handles catalog business logic
 */
class CatalogService
{
    private CatalogRepositoryInterface $repo;

    public function __construct(?CatalogRepositoryInterface $repo = null)
    {
        if ($repo === null) {
            $db = \Database::getConnection();
            $repo = new CatalogRepository($db);
        }
        $this->repo = $repo;
    }

    public function get_catalog_count($category = null, $search = null)
    {
        return $this->repo->getcatalog_count($category, $search);
    }

    public function full_catalog_array($limit = null, $offset = 0)
    {
        return $this->repo->get_full_catalog($limit, $offset);
    }

    public function category_catalog_array($category, $limit = null, $offset = 0)
    {
        return $this->repo->get_category_catalog($category, $limit, $offset);
    }

    public function search_catalog_array($search, $category = null, $limit = null, $offset = 0)
    {
        return $this->repo->get_search_catalog($search, $category, $limit, $offset);
    }

    public function random_catalog_array()
    {
        return $this->repo->get_random_catalog();
    }

    public function single_item_array($id)
    {
        return $this->repo->get_single_item($id);
    }
}
