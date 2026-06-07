<?php

namespace MediaLibrary\Presentation\Controllers;

use MediaLibrary\Application\Services\InvoiceService;

class InvoiceController
{
    private InvoiceService $invoiceService;

    public function __construct(?InvoiceService $invoiceService = null)
    {
        $this->invoiceService = $invoiceService ?? new InvoiceService();
    }

    /**
     * Display user's invoices
     */
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login&required=1');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $invoices = $this->invoiceService->getUserInvoices($userId);

        $pageTitle = "My Invoices";
        $section = null;
        $hideSearch = true;

        require BASE_PATH . '/src/Presentation/Views/invoices/index.php';
    }

    /**
     * Display invoice details
     */
    public function view()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login&required=1');
            exit;
        }

        $invoiceId = $_GET['id'] ?? null;
        
        if (!$invoiceId) {
            header('Location: index.php?page=invoices');
            exit;
        }

        $invoice = $this->invoiceService->getInvoiceById($invoiceId);
        
        if (!$invoice) {
            header('Location: index.php?page=invoices&error=not_found');
            exit;
        }

        // Check if user owns this invoice or is admin
        if ($invoice['user_id'] != $_SESSION['user_id'] && !($_SESSION['is_admin'] ?? false)) {
            header('Location: index.php?page=invoices&error=unauthorized');
            exit;
        }

        $pageTitle = "Invoice #" . $invoice['invoice_number'];
        $section = null;
        $hideSearch = true;

        require BASE_PATH . '/src/Presentation/Views/invoices/view.php';
    }

    /**
     * Download invoice as PDF
     */
    public function download()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login&required=1');
            exit;
        }

        $invoiceId = $_GET['id'] ?? null;
        
        if (!$invoiceId) {
            header('Location: index.php?page=invoices');
            exit;
        }

        $invoice = $this->invoiceService->getInvoiceById($invoiceId);
        
        if (!$invoice) {
            header('Location: index.php?page=invoices&error=not_found');
            exit;
        }

        // Check if user owns this invoice or is admin
        if ($invoice['user_id'] != $_SESSION['user_id'] && !($_SESSION['is_admin'] ?? false)) {
            header('Location: index.php?page=invoices&error=unauthorized');
            exit;
        }

        // Send notification to user about PDF download
        try {
            $notificationService = new \MediaLibrary\Application\Services\NotificationService();
            $notificationService->notifyInvoiceDownloaded(
                $_SESSION['user_id'],
                $invoice['invoice_number']
            );
        } catch (\Exception $e) {
            error_log("Invoice download: Notification failed - " . $e->getMessage());
        }

        // Pass invoice data to view for client-side PDF generation
        $pageTitle = "Invoice #" . $invoice['invoice_number'];
        $section = null;
        $hideSearch = true;

        require BASE_PATH . '/src/Presentation/Views/invoices/download.php';
    }
}
