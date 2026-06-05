<?php

namespace MediaLibrary\Application\Services;

use MediaLibrary\Domain\Repositories\CatalogRepositoryInterface;
use MediaLibrary\Infrastructure\Persistence\CatalogRepository;

/**
 * Handles catalog business logic and communicates
 * between controllers and the catalog repository.
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

    // Get total number of catalog items
    public function get_catalog_count($category = null, $search = null)
    {
        return $this->repo->getcatalog_count($category, $search);
    }

    // Get all catalog items with pagination support
    public function full_catalog_array($limit = null, $offset = 0)
    {
        return $this->repo->get_full_catalog($limit, $offset);
    }

    // Get catalog items by category
    public function category_catalog_array($category, $limit = null, $offset = 0)
    {
        return $this->repo->get_category_catalog($category, $limit, $offset);
    }

    // Search catalog items by keyword and category
    public function search_catalog_array($search, $category = null, $limit = null, $offset = 0)
    {
        return $this->repo->get_search_catalog($search, $category, $limit, $offset);
    }

    // Get random catalog items
    public function random_catalog_array()
    {
        return $this->repo->get_random_catalog();
    }

    // Get a single catalog item by ID
    public function single_item_array($id)
    {
        return $this->repo->get_single_item($id);
    }
}
