<?php

namespace MediaLibrary\Presentation\Controllers;

use MediaLibrary\Application\Services\CatalogService;

/**
 * Handles catalog pages, homepage display,
 * searching, filtering, and pagination.
 */
class CatalogController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        // Inject catalog service dependency
        $this->catalogService = $catalogService;
    }

    // Display homepage with random catalog items
    public function home()
    {
        $pageTitle = "Personal Media Library";
        $section = "catalog";

        // Get random media items
        $random = $this->catalogService->random_catalog_array();

        require BASE_PATH . '/src/Presentation/Views/catalog/home.php';
    }

    // Display full catalog page
    public function index()
    {
        $pageTitle = "Full Catalog";
        $section  = null;
        $search = null;

        // Number of items shown per page
        $item_per_page = 8;

        // Filter by category
        if (isset($_GET["cat"])) {

            if ($_GET["cat"] === "books") {
                $pageTitle = "Books";
                $section = "books";

            } elseif ($_GET["cat"] === "movies") {
                $pageTitle = "Movies";
                $section = "movies";

            } elseif ($_GET["cat"] === "music") {
                $pageTitle = "Music";
                $section = "music";
            }
        }

        // Get search keyword
        if (isset($_GET["s"])) {
            $search = trim(
                filter_input(INPUT_GET, "s", FILTER_SANITIZE_STRING)
            );
        }

        // Get current page number
        $current_page = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);

        // Default to page 1 if invalid
        if ($current_page === false || $current_page === null) {
            $current_page = 1;
        }

        // Get total catalog item count
        $total_items = $this->catalogService
            ->get_catalog_count($section, $search);

        $found_in_full_catalog = 0;
        $total_pages = 1;
        $offset = 0;

        // Calculate pagination values
        if ($total_items > 0) {

            $total_pages = ceil($total_items / $item_per_page);

            $limit_results = "";

            // Keep category filter in pagination links
            if (!empty($section)) {
                $limit_results .= "cat=" . urlencode($section) . "&";
            }

            // Keep search keyword in pagination links
            if (!empty($search)) {
                $limit_results .= "s=" . urlencode($search) . "&";
            }

            // Prevent invalid page numbers
            if ($current_page < 1) {
                $current_page = 1;
            }

            if ($current_page > $total_pages) {
                $current_page = $total_pages;
            }

            // Calculate database offset
            $offset = ($current_page - 1) * $item_per_page;
        }

        // Load catalog data based on filters
        if (!empty($search) && !empty($section)) {

            $catalog = $this->catalogService
                ->search_catalog_array(
                    $search,
                    $section,
                    $item_per_page,
                    $offset
                );

        } elseif (!empty($search)) {

            $catalog = $this->catalogService
                ->search_catalog_array(
                    $search,
                    null,
                    $item_per_page,
                    $offset
                );

        } elseif (!empty($section)) {

            $catalog = $this->catalogService
                ->category_catalog_array(
                    $section,
                    $item_per_page,
                    $offset
                );

        } else {

            $catalog = $this->catalogService
                ->full_catalog_array(
                    $item_per_page,
                    $offset
                );
        }

        // Load catalog view
        require BASE_PATH . '/src/Presentation/Views/catalog/catalog.php';
    }
}
