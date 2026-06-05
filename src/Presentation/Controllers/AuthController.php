<?php

namespace MediaLibrary\Presentation\Controllers;

use MediaLibrary\Application\Services\UserService;

/**
 * Handles user authentication (login, register, logout)
 */
class AuthController
{
    private UserService $userService;

    public function __construct(?UserService $userService = null)
    {
        if ($userService === null) {
            $userService = new UserService();
        }

        $this->userService = $userService;
    }

    /**
     * Display login form and handle login submission
     */
    public function login()
    {
        $pageTitle = "Login";
        $section = null;
        $hideSearch = true;

        $email = '';
        $error_message = null;
        $success_message = null;

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
            $password = $_POST["password"] ?? '';

            $result = $this->userService->login($email, $password);

            if ($result['success']) {
                // Start session and store user data
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $result['user']['user_id'];
                $_SESSION['username'] = $result['user']['username'];
                $_SESSION['email'] = $result['user']['email'];
                $_SESSION['is_admin'] = $result['user']['is_admin'] ?? 0;

                // Redirect to home page
                header("Location: index.php");
                exit;
            } else {
                $error_message = $result['error'];
            }
        }

        // Check for registration success message
        if (isset($_GET['registered']) && $_GET['registered'] === '1') {
            $success_message = "Registration successful! Please login with your credentials.";
        }

        require BASE_PATH . '/src/Presentation/Views/auth/login.php';
    }

    /**
     * Display register form and handle registration submission
     */
    public function register()
    {
        $pageTitle = "Register";
        $section = null;
        $hideSearch = true;

        $username = '';
        $email = '';
        $error_messages = [];

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim(filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS));
            $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
            $password = $_POST["password"] ?? '';
            $confirm_password = $_POST["confirm_password"] ?? '';

            // Honeypot spam protection
            if (!empty($_POST['address'])) {
                $error_messages[] = "Bad form input";
            } else {
                $result = $this->userService->register($username, $email, $password, $confirm_password);

                if ($result['success']) {
                    // Redirect to login with success message
                    header("Location: index.php?page=login&registered=1");
                    exit;
                } else {
                    $error_messages = $result['errors'];
                }
            }
        }

        require BASE_PATH . '/src/Presentation/Views/auth/register.php';
    }

    /**
     * Handle user logout
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            $this->userService->deleteRememberToken($_COOKIE['remember_token']);
            setcookie('remember_token', '', time() - 3600, '/');
        }

        // Clear all session data
        $_SESSION = [];

        // Destroy session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destroy session
        session_destroy();

        // Redirect to home page
        header("Location: index.php");
        exit;
    }

    /**
     * Forgot Password - Display form and send reset email
     */
    public function forgotPassword()
    {
        $pageTitle = "Forgot Password";
        $section = null;
        $hideSearch = true;

        $email = '';
        $error_message = null;
        $success_message = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));

            $result = $this->userService->requestPasswordReset($email);

            if ($result['success']) {
                // In a real app, send email here
                // For demo, show token in success message
                $success_message = "Password reset link sent to your email. (Demo: token={$result['token']})";
            } else {
                $error_message = $result['error'];
            }
        }

        require BASE_PATH . '/src/Presentation/Views/auth/forgot_password.php';
    }

    /**
     * Reset Password - Display form and update password
     */
    public function resetPassword()
    {
        $pageTitle = "Reset Password";
        $section = null;
        $hideSearch = true;

        $token = $_GET['token'] ?? '';
        $error_message = null;
        $success_message = null;

        // Verify token
        $tokenData = $this->userService->verifyResetToken($token);
        if (!$tokenData) {
            $error_message = "Invalid or expired token. Please request a new password reset.";
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && $tokenData) {
            $password = $_POST["password"] ?? '';
            $confirm_password = $_POST["confirm_password"] ?? '';

            $result = $this->userService->resetPassword($token, $password, $confirm_password);

            if ($result['success']) {
                $success_message = $result['message'];
            } else {
                $error_message = $result['error'];
            }
        }

        require BASE_PATH . '/src/Presentation/Views/auth/reset_password.php';
    }

    /**
     * User Profile - Display and update
     */
    public function profile()
    {
        $this->requireAuth();

        $pageTitle = "My Profile";
        $section = null;
        $hideSearch = true;

        $userId = $_SESSION['user_id'];
        $user = $this->userService->getUserById($userId);
        $error_messages = [];
        $success_message = null;

        // Get user stats
        $reservationService = new \MediaLibrary\Application\Services\ReservationService();
        $reservations = $reservationService->getUserReservations($userId);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $action = $_POST['action'] ?? '';

            if ($action === 'update_profile') {
                $data = [
                    'username' => trim(filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS)),
                    'email' => trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL)),
                    'bio' => trim(filter_input(INPUT_POST, "bio", FILTER_SANITIZE_SPECIAL_CHARS))
                ];

                $result = $this->userService->updateProfile($userId, $data);

                if ($result['success']) {
                    $_SESSION['username'] = $data['username'];
                    $success_message = $result['message'];
                    $user = $this->userService->getUserById($userId);
                } else {
                    $error_messages = $result['errors'];
                }
            } elseif ($action === 'change_password') {
                $current = $_POST['current_password'] ?? '';
                $new = $_POST['new_password'] ?? '';
                $confirm = $_POST['confirm_password'] ?? '';

                $result = $this->userService->changePassword($userId, $current, $new, $confirm);

                if ($result['success']) {
                    $success_message = $result['message'];
                } else {
                    $error_messages[] = $result['error'];
                }
            }
        }

        require BASE_PATH . '/src/Presentation/Views/auth/profile.php';
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
