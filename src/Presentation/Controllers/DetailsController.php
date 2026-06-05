<?php

namespace MediaLibrary\Presentation\Controllers;

use MediaLibrary\Application\Services\UserService;
use MediaLibrary\Application\Services\CatalogService;

/**
 * Handles displaying detailed information
 * for a single catalog item.
 */
class DetailsController
{
    private CatalogService $catalogService;
    private UserService $userService;

    public function __construct(CatalogService $catalogService, ?UserService $userService = null)
    {
        // Inject dependencies
        $this->catalogService = $catalogService;
        $this->userService = $userService ?? new UserService();
    }

    // Show item details page
    public function show()
    {
        // Validate item ID from URL
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Redirect if ID is invalid
        if (!$id) {
            header("Location: index.php?page=catalog");
            exit;
        }

        // Get item data from service
        $item = $this->catalogService->single_item_array($id);

        // Redirect if item does not exist
        if (empty($item)) {
            header("Location: index.php?page=catalog");
            exit;
        }

        // Page information
        $pageTitle = $item['title'];
        $section   = $item['category'];

        // Get user-specific data if logged in
        $avgRating = null;
        $reviews = [];

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Load details view
        require BASE_PATH . '/src/Presentation/Views/catalog/details.php';
    }
}
