<?php

namespace MediaLibrary\Presentation\Controllers;

use MediaLibrary\Application\Services\ReservationService;
use MediaLibrary\Application\Services\PaymentService;
use MediaLibrary\Application\Services\InvoiceService;
use MediaLibrary\Config\StripeConfig;

/**
 * Stripe Payment Controller
 * Handles Stripe checkout for reservations
 */
class StripeController
{
    private ReservationService $reservationService;
    private PaymentService $paymentService;
    private InvoiceService $invoiceService;

    public function __construct(?ReservationService $reservationService = null, ?PaymentService $paymentService = null, ?InvoiceService $invoiceService = null)
    {
        $this->reservationService = $reservationService ?? new ReservationService();
        $this->paymentService = $paymentService ?? new PaymentService();
        $this->invoiceService = $invoiceService ?? new InvoiceService();
    }

    /**
     * Create Stripe checkout session
     */
    public function checkout()
    {
        if (!isset($_SESSION['user_id'])) {
            error_log('Stripe checkout: User not logged in');
            header('Location: index.php?page=login&required=1');
            exit;
        }

        $reservationId = $_GET['reservation_id'] ?? null;
        
        if (!$reservationId) {
            error_log('Stripe checkout: No reservation ID provided');
            header('Location: index.php?page=reservations');
            exit;
        }

        error_log('Stripe checkout: Attempting checkout for reservation_id=' . $reservationId . ', user_id=' . $_SESSION['user_id']);

        // Get reservation details
        $reservation = $this->reservationService->getReservationById($reservationId);
        
        if (!$reservation) {
            error_log('Stripe checkout: Reservation not found for reservation_id=' . $reservationId);
            header('Location: index.php?page=reservations&error=reservation_not_found');
            exit;
        }

        error_log('Stripe checkout: Reservation found, user_id=' . $reservation['user_id'] . ', payment_status=' . ($reservation['payment_status'] ?? 'null'));

        if ($reservation['user_id'] != $_SESSION['user_id']) {
            error_log('Stripe checkout: Reservation belongs to different user');
            header('Location: index.php?page=reservations&error=unauthorized');
            exit;
        }

        // Check if already paid
        if ($reservation['payment_status'] === 'completed') {
            error_log('Stripe checkout: Reservation already paid');
            header('Location: index.php?page=reservations&error=already_paid');
            exit;
        }

        // Initialize Stripe
        try {
            StripeConfig::init();
            error_log('Stripe checkout: Stripe initialized successfully');
        } catch (\Exception $e) {
            error_log('Stripe checkout: Stripe initialization failed - ' . $e->getMessage());
            header('Location: index.php?page=reservations&error=stripe_config');
            exit;
        }

        try {
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $reservation['media_title'],
                        ],
                        'unit_amount' => $reservation['amount'] * 100, // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://localhost/ITVisionHub/media_library/index.php?page=stripe-success&reservation_id=' . $reservationId,
                'cancel_url' => 'http://localhost/ITVisionHub/media_library/index.php?page=stripe-cancel&reservation_id=' . $reservationId,
            ]);

            // Create payment record
            $this->paymentService->createPayment(
                $_SESSION['user_id'],
                $reservationId,
                $checkoutSession->id,
                $reservation['amount'],
                'reservation'
            );

            header('Location: ' . $checkoutSession->url);
            exit;

        } catch (\Exception $e) {
            error_log('Stripe checkout error: ' . $e->getMessage());
            header('Location: index.php?page=reservations&error=payment_failed');
            exit;
        }
    }

    /**
     * Handle successful payment
     */
    public function success()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login&required=1');
            exit;
        }

        $reservationId = $_GET['reservation_id'] ?? null;
        
        if (!$reservationId) {
            header('Location: index.php?page=reservations');
            exit;
        }

        error_log("Stripe success: Processing reservation_id=$reservationId");

        // Update reservation payment status and reservation status
        $result1 = $this->reservationService->updatePaymentStatus($reservationId, 'completed');
        $result2 = $this->reservationService->updateStatus($reservationId, 'completed');
        
        error_log("Stripe success: updatePaymentStatus result=" . ($result1 ? 'success' : 'failed') . ", updateStatus result=" . ($result2 ? 'success' : 'failed'));

        // Get reservation details for invoice
        $reservation = $this->reservationService->getReservationById($reservationId);
        
        if ($reservation && $result1) {
            try {
                // Create invoice
                $invoiceId = $this->invoiceService->createInvoice(
                    $reservationId,
                    null, // payment_intent_id - can be retrieved from Stripe if needed
                    $reservation['amount'],
                    'USD'
                );
                error_log("Stripe success: Invoice created with ID=$invoiceId");

                // Send notification to user
                try {
                    $notificationService = new \MediaLibrary\Application\Services\NotificationService();
                    $invoice = $this->invoiceService->getInvoiceById($invoiceId);
                    if ($invoice) {
                        $notificationService->notifyPaymentCompleted(
                            $reservation['user_id'],
                            $invoice['invoice_number'],
                            $reservation['amount']
                        );
                    }
                } catch (\Exception $e) {
                    error_log("Stripe success: Notification failed - " . $e->getMessage());
                }
            } catch (\Exception $e) {
                error_log("Stripe success: Invoice creation failed - " . $e->getMessage());
            }
        }

        $pageTitle = "Payment Successful";
        $section = null;
        $hideSearch = true;

        require BASE_PATH . '/src/Presentation/Views/stripe/success.php';
    }

    /**
     * Handle Stripe webhook
     */
    public function webhook()
    {
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        try {
            StripeConfig::init();
            
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $_ENV['STRIPE_WEBHOOK_SECRET'] ?? ''
            );

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                
                // Find payment by Stripe session ID
                $payment = $this->paymentService->findByStripeSessionId($session->id);
                
                if ($payment) {
                    // Update payment status
                    $this->paymentService->updatePaymentStatus($payment['payment_id'], 'completed');
                    
                    // Update reservation payment status if linked
                    if ($payment['reservation_id']) {
                        $this->reservationService->updatePaymentStatus($payment['reservation_id'], 'completed');
                    }
                }
            }

            http_response_code(200);
            echo json_encode(['status' => 'success']);
            
        } catch (\Exception $e) {
            error_log('Webhook error: ' . $e->getMessage());
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Handle cancelled payment
     */
    public function cancel()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login&required=1');
            exit;
        }

        $pageTitle = "Payment Cancelled";
        $section = null;
        $hideSearch = true;

        require BASE_PATH . '/src/Presentation/Views/stripe/cancel.php';
    }
}
