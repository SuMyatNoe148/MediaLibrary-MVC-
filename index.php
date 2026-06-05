
<?php
/**
 * Main application entry point.
 * Initializes dependencies, services, and application routing.
 */

define('BASE_PATH', __DIR__);

// Start session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/config/DatabaseConnection.php';

use Dotenv\Dotenv;
use MediaLibrary\Presentation\Controllers\CatalogController;
use MediaLibrary\Presentation\Controllers\DetailsController;
use MediaLibrary\Presentation\Controllers\SuggestController;
use MediaLibrary\Presentation\Controllers\AuthController;
use MediaLibrary\Presentation\Controllers\UserController;
use MediaLibrary\Presentation\Controllers\AdminController;
use MediaLibrary\Presentation\Controllers\ReservationController;
use MediaLibrary\Presentation\Controllers\StripeController;
use MediaLibrary\Application\Services\CatalogService;
use MediaLibrary\Application\Services\FormatService;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

/* AUTHENTICATION HELPER */

function requireAuth(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login&required=1');
        exit;
    }
}

function isAuthenticated(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['user_id']);
}

/*BUILD SHARED OBJECTS*/

$db = Database::getConnection();

/* Services */
$catalogService = new CatalogService();
$formatService  = new FormatService();

/*ROUTING */

$page = $_GET['page'] ?? 'home';

switch ($page) {

    case 'details':
        $controller = new DetailsController($catalogService);
        $controller->show();
        break;

    case 'suggest':
        requireAuth(); // Protected - require login
        $controller = new SuggestController($formatService);
        $controller->index();
        break;

    case 'catalog':
        requireAuth(); // Protected - require login
        $controller = new CatalogController($catalogService);
        $controller->index();
        break;

    case 'login':
        // Redirect if already logged in
        if (isAuthenticated()) {
            header('Location: index.php');
            exit;
        }
        $controller = new AuthController();
        $controller->login();
        break;

    case 'register':
        // Redirect if already logged in
        if (isAuthenticated()) {
            header('Location: index.php');
            exit;
        }
        $controller = new AuthController();
        $controller->register();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'forgot-password':
        if (isAuthenticated()) {
            header('Location: index.php');
            exit;
        }
        $controller = new AuthController();
        $controller->forgotPassword();
        break;

    case 'reset-password':
        if (isAuthenticated()) {
            header('Location: index.php');
            exit;
        }
        $controller = new AuthController();
        $controller->resetPassword();
        break;

    case 'profile':
        $controller = new AuthController();
        $controller->profile();
        break;

    case 'rate':
        $controller = new UserController();
        $controller->rate();
        break;

    case 'add-review':
        $controller = new UserController();
        $controller->addReview();
        break;

    case 'delete-review':
        $controller = new UserController();
        $controller->deleteReview();
        break;

    case 'reservations':
        $controller = new ReservationController();
        $controller->index();
        break;

    case 'create-reservation':
        $controller = new ReservationController();
        $controller->create();
        break;

    case 'cancel-reservation':
        $controller = new ReservationController();
        $controller->cancel();
        break;

    case 'delete-reservation':
        $controller = new ReservationController();
        $controller->delete();
        break;

    case 'admin':
        $controller = new AdminController();
        $controller->index();
        break;

    case 'admin-users':
        $controller = new AdminController();
        $controller->users();
        break;

    case 'admin-catalog':
        $controller = new AdminController();
        $controller->catalog();
        break;

    case 'admin-add-person':
        $controller = new AdminController();
        $controller->addPerson();
        break;

    case 'admin-reviews':
        $controller = new AdminController();
        $controller->reviews();
        break;

    case 'admin-activity':
        $controller = new AdminController();
        $controller->activity();
        break;

    case 'admin-reservations':
        $controller = new AdminController();
        $controller->reservations();
        break;

    case 'admin-messages':
        $controller = new AdminController();
        $controller->messages();
        break;

    case 'stripe-checkout':
        $controller = new StripeController();
        $controller->checkout();
        break;

    case 'stripe-success':
        $controller = new StripeController();
        $controller->success();
        break;

    case 'stripe-cancel':
        $controller = new StripeController();
        $controller->cancel();
        break;

    case 'stripe-webhook':
        $controller = new StripeController();
        $controller->webhook();
        break;

    case '404':
        http_response_code(404);
        $pageTitle = "Page Not Found";
        $section = null;
        $hideSearch = true;
        require BASE_PATH . '/src/Presentation/Views/404.php';
        break;

    default: // HOME PAGE - Public access
        $controller = new CatalogController($catalogService);
        $controller->home();
}

