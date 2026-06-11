<?php

namespace MediaLibrary\Presentation\Controllers;

use MediaLibrary\Identity\Application\Services\AdminService;
use MediaLibrary\Reservation\Application\Services\ReservationService;
use MediaLibrary\Payment\Application\Services\InvoiceService;

/**
 * Admin Dashboard Controller
 * Handles admin panel functionality
 */
class AdminController
{
    private AdminService $adminService;
    private ReservationService $reservationService;
    private InvoiceService $invoiceService;

    public function __construct(?AdminService $adminService = null, ?ReservationService $reservationService = null, ?InvoiceService $invoiceService = null)
    {
        if ($adminService === null) {
            $this->adminService = new AdminService();
        } else {
            $this->adminService = $adminService;
        }
        $this->reservationService = $reservationService ?? new ReservationService();
        $this->invoiceService = $invoiceService ?? new InvoiceService();
    }

    /**
     * Check if user is admin
     */
    private function requireAdmin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login&required=1');
            exit;
        }

        if (!$this->adminService->isAdmin($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    /**
     * Admin Dashboard Home
     */
    public function index()
    {
        $this->requireAdmin();

        $pageTitle = "Admin Dashboard";
        $section = null;
        $hideSearch = true;

        // Get dashboard statistics
        $stats = $this->adminService->getDashboardStats();
        
        // Get invoice statistics
        $invoiceStats = [
            'total_revenue' => $this->invoiceService->getTotalRevenue(),
            'total_invoices' => $this->invoiceService->getTotalInvoices(),
            'monthly_revenue' => $this->invoiceService->getMonthlyRevenue()
        ];

        require BASE_PATH . '/src/Presentation/Views/admin/dashboard.php';
    }

    /**
     * Manage Users
     */
    public function users()
    {
        $this->requireAdmin();

        $pageTitle = "Manage Users";
        $section = null;
        $hideSearch = true;

        // Handle actions
        $message = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $userId = (int)($_POST['user_id'] ?? 0);

            if ($action === 'toggle_admin' && $userId > 0) {
                $result = $this->adminService->toggleAdminStatus($userId);
                if ($result['success']) {
                    $message = $result['message'];
                } else {
                    $error = $result['error'];
                }
            } elseif ($action === 'delete_user' && $userId > 0) {
                // Prevent self-deletion
                if ($userId === $_SESSION['user_id']) {
                    $error = "You cannot delete your own account.";
                } else {
                    $result = $this->adminService->deleteUser($userId);
                    if ($result['success']) {
                        $message = $result['message'];
                    } else {
                        $error = $result['error'];
                    }
                }
            }
        }

        $users = $this->adminService->getAllUsers();

        require BASE_PATH . '/src/Presentation/Views/admin/users.php';
    }

    /**
     * Manage Media Catalog
     */
    public function catalog()
    {
        $this->requireAdmin();

        $pageTitle = "Manage Catalog";
        $section = null;
        $hideSearch = true;

        $message = null;
        $error = null;

        // Get filter and pagination params
        $category = $_GET['category'] ?? 'all';
        $page = (int)($_GET['p'] ?? 1);
        $perPage = 10;
        
        if ($page < 1) $page = 1;

        // Handle actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'delete_media') {
                $mediaId = (int)($_POST['media_id'] ?? 0);
                if ($mediaId > 0) {
                    $result = $this->adminService->deleteMedia($mediaId);
                    if ($result['success']) {
                        $message = $result['message'];
                    } else {
                        $error = $result['error'];
                    }
                }
            } elseif ($action === 'update_media') {
                $mediaId = (int)($_POST['media_id'] ?? 0);
                if ($mediaId > 0) {
                    $data = [
                        'title' => trim($_POST['title'] ?? ''),
                        'img' => trim($_POST['img'] ?? ''),
                        'format' => trim($_POST['format'] ?? ''),
                        'year' => (int)($_POST['year'] ?? 0),
                        'media_types_id' => (int)($_POST['media_types_id'] ?? 1),
                        'genre_id' => (int)($_POST['genre_id'] ?? 1)
                    ];
                    $result = $this->adminService->updateMedia($mediaId, $data);
                    if ($result['success']) {
                        // Save media people associations
                        $peopleIds = $_POST['people_id'] ?? [];
                        $roleIds = $_POST['role_id'] ?? [];
                        
                        // Add new person if provided
                        $newPersonName = trim($_POST['edit_new_person_name'] ?? '');
                        $newPersonRole = (int)($_POST['edit_new_person_role'] ?? 0);
                        if (!empty($newPersonName) && $newPersonRole > 0) {
                            $newPersonId = $this->adminService->addPerson($newPersonName);
                            if ($newPersonId > 0) {
                                $peopleIds[] = $newPersonId;
                                $roleIds[] = $newPersonRole;
                            }
                        }
                        
                        $this->adminService->saveMediaPeople($mediaId, $peopleIds, $roleIds);
                        $message = $result['message'];
                    } else {
                        $error = $result['error'];
                    }
                }
            } elseif ($action === 'create_media') {
                // Handle file upload
                $imgPath = '';
                if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = $this->handleImageUpload($_FILES['image_file'], (int)($_POST['media_types_id'] ?? 1));
                    if ($uploadResult['success']) {
                        $imgPath = $uploadResult['path'];
                    } else {
                        $error = $uploadResult['error'];
                    }
                } elseif (!empty($_POST['img'])) {
                    $imgPath = trim($_POST['img']);
                }

                if (empty($error)) {
                    $data = [
                        'title' => trim($_POST['title'] ?? ''),
                        'img' => $imgPath,
                        'format' => trim($_POST['format'] ?? ''),
                        'year' => (int)($_POST['year'] ?? date('Y')),
                        'media_types_id' => (int)($_POST['media_types_id'] ?? 1),
                        'genre_id' => (int)($_POST['genre_id'] ?? 1)
                    ];
                    $result = $this->adminService->createMedia($data);
                    if ($result['success']) {
                        // Save media people associations
                        $peopleIds = $_POST['people_id'] ?? [];
                        $roleIds = $_POST['role_id'] ?? [];
                        
                        // Add new person if provided
                        $newPersonName = trim($_POST['new_person_name'] ?? '');
                        $newPersonRole = (int)($_POST['new_person_role'] ?? 0);
                        if (!empty($newPersonName) && $newPersonRole > 0) {
                            $newPersonId = $this->adminService->addPerson($newPersonName);
                            if ($newPersonId > 0) {
                                $peopleIds[] = $newPersonId;
                                $roleIds[] = $newPersonRole;
                            }
                        }
                        
                        $this->adminService->saveMediaPeople($result['media_id'], $peopleIds, $roleIds);
                        $message = $result['message'];
                    } else {
                        $error = $result['error'];
                    }
                }
            }
        }

        // Get catalog items with pagination
        $catalog = $this->adminService->getAllMedia($category === 'all' ? null : $category, $page, $perPage);
        $totalItems = $this->adminService->getTotalMediaCount($category === 'all' ? null : $category);
        $totalPages = (int)ceil($totalItems / $perPage);
        
        // Get categories for filter
        $categories = $this->adminService->getCategories();
        
        // Get genres for dropdown
        $genres = $this->adminService->getAllGenres();
        
        // Get people and roles for media people
        $people = $this->adminService->getAllPeople();
        $roles = $this->adminService->getAllRoles();

        // Check if editing
        $editMedia = null;
        if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
            $editMedia = $this->adminService->getMediaById((int)$_GET['edit']);
        }

        require BASE_PATH . '/src/Presentation/Views/admin/catalog.php';
    }

    /**
     * Add new person (AJAX endpoint)
     */
    public function addPerson()
    {
        $this->requireAdmin();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $fullname = trim($_POST['fullname'] ?? '');
        if (empty($fullname)) {
            echo json_encode(['success' => false, 'error' => 'Name is required']);
            return;
        }
        
        $personId = $this->adminService->addPerson($fullname);
        if ($personId > 0) {
            echo json_encode(['success' => true, 'person_id' => $personId, 'fullname' => $fullname]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to add person']);
        }
    }

    /**
     * Manage Reviews
     */
    public function reviews()
    {
        $this->requireAdmin();

        $pageTitle = "Manage Reviews";
        $section = null;
        $hideSearch = true;

        $message = null;
        $error = null;

        // Handle delete action
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $reviewId = (int)($_POST['review_id'] ?? 0);

            if ($action === 'delete_review' && $reviewId > 0) {
                $result = $this->adminService->deleteReview($reviewId);
                if ($result['success']) {
                    $message = $result['message'];
                } else {
                    $error = $result['error'];
                }
            }
        }

        $reviews = $this->adminService->getAllReviews();

        require BASE_PATH . '/src/Presentation/Views/admin/reviews.php';
    }

    /**
     * System Activity Log
     */
    public function activity()
    {
        $this->requireAdmin();

        $pageTitle = "Activity Log";
        $section = null;
        $hideSearch = true;

        $activities = $this->adminService->getRecentActivity(100);

        require BASE_PATH . '/src/Presentation/Views/admin/activity.php';
    }

    /**
     * Manage Reservations
     */
    public function reservations()
    {
        $this->requireAdmin();

        $pageTitle = "Manage Reservations";
        $section = null;
        $hideSearch = true;

        $message = null;
        $error = null;

        // Handle actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $reservationId = (int)($_POST['reservation_id'] ?? 0);

            if ($action === 'update_status' && $reservationId > 0) {
                $status = $_POST['status'] ?? 'pending';
                $result = $this->reservationService->updateStatus($reservationId, $status);
                if ($result['success']) {
                    $message = $result['message'];
                    
                    // Send notification based on status change
                    try {
                        $notificationService = new \MediaLibrary\Notification\Application\Services\NotificationService();
                        $reservation = $this->reservationService->getReservationById($reservationId);
                        
                        if ($reservation) {
                            if ($status === 'confirmed') {
                                $notificationService->notifyReservationConfirmed(
                                    $reservation['user_id'],
                                    $reservation['media_title']
                                );
                            } elseif ($status === 'cancelled') {
                                $notificationService->notifyReservationCancelled(
                                    $reservation['user_id'],
                                    $reservation['media_title']
                                );
                            }
                        }
                    } catch (\Exception $e) {
                        error_log("Admin reservation update: Notification failed - " . $e->getMessage());
                    }
                } else {
                    $error = $result['error'];
                }
            } elseif ($action === 'delete_reservation' && $reservationId > 0) {
                $result = $this->reservationService->deleteReservation($reservationId);
                if ($result['success']) {
                    $message = $result['message'];
                } else {
                    $error = $result['error'];
                }
            }
        }

        $reservations = $this->reservationService->getAllReservations();

        require BASE_PATH . '/src/Presentation/Views/admin/reservations.php';
    }

    /**
     * Manage Messages
     */
    public function messages()
    {
        $this->requireAdmin();

        $pageTitle = "Messages";
        $section = null;
        $hideSearch = true;

        $filter = $_GET['filter'] ?? '';
        $messages = $this->adminService->getAllMessages($filter);

        require BASE_PATH . '/src/Presentation/Views/admin/messages.php';
    }

    public function viewMessage(): void
    {
        $this->requireAdmin();
        $pageTitle = 'Message Detail';
        $section = null;
        $hideSearch = true;
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?page=admin-messages');
            exit;
        }
        $message = $this->adminService->getMessage($id);
        if (!$message) {
            header('Location: index.php?page=admin-messages');
            exit;
        }
        $this->adminService->markMessageRead($id);
        require BASE_PATH . '/src/Presentation/Views/admin/message-detail.php';
    }

    public function markMessageRead(): void
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->adminService->markMessageRead($id);
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Admin Invoice Management
     */
    public function invoices()
    {
        $this->requireAdmin();

        $pageTitle = "Invoice Management";
        $section = null;
        $hideSearch = true;

        // Get all invoices
        $invoices = $this->invoiceService->getAllInvoices();

        require BASE_PATH . '/src/Presentation/Views/admin/invoices.php';
    }

    /**
     * Handle image file upload
     */
    private function handleImageUpload(array $file, int $mediaTypeId): array
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Check file type by MIME or extension
        $fileType = strtolower($file['type']);
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($fileType, $allowedTypes) && !in_array($fileExtension, $allowedExtensions)) {
            return ['success' => false, 'error' => 'Invalid file type. Only JPG, JPEG, PNG, GIF, WebP allowed.'];
        }

        // Check file size
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'File too large. Max 5MB allowed.'];
        }

        // Determine folder based on media type
        $folderMap = [
            1 => 'books',
            2 => 'movies',
            3 => 'music'
        ];
        $folder = $folderMap[$mediaTypeId] ?? 'other';

        // Create upload path
        $uploadDir = BASE_PATH . '/Public/img/' . $folder;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', $file['name']);
        $filepath = $uploadDir . '/' . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $relativePath = 'Public/img/' . $folder . '/' . $filename;
            return ['success' => true, 'path' => $relativePath];
        } else {
            return ['success' => false, 'error' => 'Failed to upload file.'];
        }
    }
}
