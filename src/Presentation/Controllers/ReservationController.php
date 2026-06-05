<?php

namespace MediaLibrary\Presentation\Controllers;

use MediaLibrary\Application\Services\ReservationService;

/**
 * Handles reservation-related actions
 */
class ReservationController
{
    private ReservationService $reservationService;

    public function __construct(?ReservationService $reservationService = null)
    {
        if ($reservationService === null) {
            $reservationService = new ReservationService();
        }
        $this->reservationService = $reservationService;
    }

    /**
     * Display user's reservations
     */
    public function index()
    {
        $this->requireAuth();

        $pageTitle = "My Reservations";
        $section = null;
        $hideSearch = true;

        $userId = $_SESSION['user_id'];
        $search = $_GET['search'] ?? null;
        $paymentStatus = $_GET['payment_status'] ?? null;
        $reservationStatus = $_GET['reservation_status'] ?? null;
        
        $reservations = $this->reservationService->getUserReservationsFiltered($userId, $search, $paymentStatus, $reservationStatus);

        require BASE_PATH . '/src/Presentation/Views/reservations/index.php';
    }

    /**
     * Display create reservation form
     */
    public function create()
    {
        $this->requireAuth();

        $pageTitle = "Create Reservation";
        $section = null;
        $hideSearch = true;

        $mediaId = (int)($_GET['media_id'] ?? 0);
        $error_message = null;
        $success_message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mediaId = (int)($_POST['media_id'] ?? 0);
            $reservationDate = trim($_POST['reservation_date'] ?? '');
            $notes = trim($_POST['notes'] ?? '');

            if (empty($reservationDate)) {
                $error_message = "Please select a reservation date.";
            } else {
                $result = $this->reservationService->createReservation(
                    $_SESSION['user_id'],
                    $mediaId,
                    $reservationDate,
                    $notes
                );

                if ($result['success']) {

                    header('Location: index.php?page=reservations&success=1');
                    exit;

                } else {
                    $error_message = $result['error'];
                }
            }
        }

        require BASE_PATH . '/src/Presentation/Views/reservations/create.php';
    }

    /**
     * Cancel a reservation
     */
    public function cancel()
    {
        $this->requireAuth();

        $reservationId = (int)($_POST['reservation_id'] ?? 0);
        $redirect = $_POST['redirect'] ?? 'index.php?page=reservations';

        if ($reservationId > 0) {
            $result = $this->reservationService->cancelReservation($reservationId);
        }

        header("Location: $redirect");
        exit;
    }

    /**
     * Delete a reservation
     */
    public function delete()
    {
        $this->requireAuth();

        $reservationId = (int)($_POST['reservation_id'] ?? 0);
        $redirect = $_POST['redirect'] ?? 'index.php?page=reservations';

        if ($reservationId > 0) {
            $result = $this->reservationService->deleteReservation($reservationId);
        }

        header("Location: $redirect");
        exit;
    }

    /**
     * Helper to require authentication
     */
    private function requireAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login&required=1");
            exit;
        }
    }
}
