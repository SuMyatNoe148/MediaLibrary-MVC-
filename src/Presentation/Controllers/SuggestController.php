<?php

namespace MediaLibrary\Presentation\Controllers;

use MediaLibrary\Catalog\Application\Services\FormatService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Handles media suggestion requests,
 * form validation, and email sending.
 */
class SuggestController
{
    private FormatService $formatService;

    public function __construct(FormatService $formatService)
    {
        // Inject format service dependency
        $this->formatService = $formatService;
    }

    private function saveToMessages(array $data): void
    {
        try {
            $db = \Database::getConnection();

            $subject = 'Media Suggestion: ' . $data['title'] . ' (' . ucfirst($data['category']) . ')';
            $body = "From: {$data['name']} <{$data['email']}>"
                . "\nCategory: {$data['category']}"
                . "\nTitle: {$data['title']}"
                . "\nFormat: {$data['format']}"
                . "\nGenre: {$data['genre']}"
                . "\nYear: {$data['year']}"
                . "\nDetails: {$data['details']}";

            // Find sender user_id by email if they are logged in
            $userId = null;
            if (!empty($data['email'])) {
                $uStmt = $db->prepare("SELECT user_id FROM Users WHERE email = ? LIMIT 1");
                $uStmt->execute([$data['email']]);
                $u = $uStmt->fetch(\PDO::FETCH_ASSOC);
                if ($u) $userId = $u['user_id'];
            }

            $stmt = $db->prepare(
                "INSERT INTO Messages (user_id, name, email, subject, message, is_read) VALUES (:user_id, :name, :email, :subject, :message, 0)"
            );
            $stmt->execute([
                ':user_id' => $userId,
                ':name'    => $data['name'],
                ':email'   => $data['email'],
                ':subject' => $subject,
                ':message' => $body,
            ]);

            // Notify admin via bell notification
            $adminStmt = $db->query("SELECT user_id FROM Users WHERE is_admin = 1 LIMIT 1");
            $admin = $adminStmt->fetch(\PDO::FETCH_ASSOC);
            if ($admin) {
                $notif = new \MediaLibrary\Notification\Application\Services\NotificationService();
                $notif->notifyNewSuggestion((int)$admin['user_id'], $data['name'], $data['title']);
            }
        } catch (\Exception $e) {
            error_log('SuggestController::saveToMessages failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    // Display suggestion form page
    public function index()
    {
        $pageTitle = "Suggest a media item";
        $section   = "suggest";
        $hideSearch = true;

        // Default form values
        $name = $email = $category = $title = $format = $genre = $year = $details = null;
        $error_message = null;

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $result = $this->handleForm();

            // Extract returned form data
            extract($result);
        }

        // Load dropdown data
        $categories = $this->formatService->category_drop_down();
        $formats    = $this->formatService->format_array();
        $genres     = $this->formatService->genres_array();

        require BASE_PATH . '/src/Presentation/Views/catalog/suggest.php';
    }

    // Process and validate form submission
    private function handleForm(): array
    {
        $data = [
            'name' => null,
            'email' => null,
            'category' => null,
            'title' => null,
            'format' => null,
            'genre' => null,
            'year' => null,
            'details' => null,
            'error_message' => null
        ];

        // Sanitize user input
        $data['name']     = trim(filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS));
        $data['email']    = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
        $data['category'] = trim(filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS));
        $data['title']    = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS));
        $data['format']   = trim(filter_input(INPUT_POST, "format", FILTER_SANITIZE_SPECIAL_CHARS));
        $data['genre']    = trim(filter_input(INPUT_POST, "genre", FILTER_SANITIZE_SPECIAL_CHARS));
        $data['year']     = trim(filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT));
        $data['details']  = trim(filter_input(INPUT_POST, "details", FILTER_SANITIZE_SPECIAL_CHARS));

        // Validate required fields
        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['category']) ||
            empty($data['title'])
        ) {
            $data['error_message'] =
                "Please fill in the required fields: Name, Email, Category and Title";

            return $data;
        }

        // Honeypot spam protection
        if (!empty($_POST['address'])) {
            $data['error_message'] = "Bad form input";
            return $data;
        }

        // Validate email format
        if (!PHPMailer::validateAddress($data['email'])) {
            $data['error_message'] = "Invalid email address";
            return $data;
        }

        /* SEND EMAIL */

        // Build email message body
        $email_body  = "Name: {$data['name']}\n";
        $email_body .= "Email: {$data['email']}\n\n";
        $email_body .= "Category: {$data['category']}\n";
        $email_body .= "Title: {$data['title']}\n";
        $email_body .= "Format: {$data['format']}\n";
        $email_body .= "Genre: {$data['genre']}\n";
        $email_body .= "Year: {$data['year']}\n";
        $email_body .= "Details:\n{$data['details']}\n";

        // Configure PHPMailer
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth   = true;

        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];

        // Set sender and receiver
        $mail->setFrom($_ENV['MAIL_FROM_EMAIL'], $_ENV['MAIL_FROM_NAME']);
        $mail->addReplyTo($data['email'], $data['name']);
        $mail->addAddress($_ENV['MAIL_FROM_EMAIL']);

        // Set email content
        $mail->Subject = 'Library Suggestion from: ' . $data['name'];
        $mail->Body    = $email_body;

        // Always save to Messages table so admin sees it
        $this->saveToMessages($data);

        // Send email and redirect on success
        try {
            if ($mail->send()) {
                header("Location: index.php?page=suggest&status=thanks");
                exit;
            }
        } catch (Exception $e) {
            // Email failed but message is saved — still redirect
        }

        // Redirect regardless of email result
        header("Location: index.php?page=suggest&status=thanks");
        exit;
    }
}
