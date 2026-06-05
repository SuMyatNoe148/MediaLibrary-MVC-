<?php

namespace MediaLibrary\Application\Services;

use MediaLibrary\Domain\Repositories\UserRepositoryInterface;
use MediaLibrary\Infrastructure\Persistence\UserRepository;

/**
 * User Service
 * Handles user-related business logic
 */
class UserService
{
    private UserRepositoryInterface $repo;

    public function __construct(?UserRepositoryInterface $repo = null)
    {
        if ($repo === null) {
            $db = \Database::getConnection();
            $repo = new UserRepository($db);
        }
        $this->repo = $repo;
    }

    /**
     * Login user
     */
    public function login(string $email, string $password): array
    {
        $user = $this->repo->findByEmail($email);

        if (!$user) {
            return ['success' => false, 'error' => 'Invalid email or password'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'error' => 'Invalid email or password'];
        }

        return ['success' => true, 'user' => $user];
    }

    /**
     * Register new user
     */
    public function register(string $username, string $email, string $password): array
    {
        if ($this->repo->findByEmail($email)) {
            return ['success' => false, 'error' => 'Email already exists'];
        }

        if ($this->repo->findByUsername($username)) {
            return ['success' => false, 'error' => 'Username already exists'];
        }

        $created = $this->repo->create($username, $email, $password);

        if (!$created) {
            return ['success' => false, 'error' => 'Failed to create account'];
        }

        return ['success' => true];
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $userId): ?array
    {
        return $this->repo->findById($userId);
    }

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email): ?array
    {
        return $this->repo->findByEmail($email);
    }

    /**
     * Update user profile
     */
    public function updateProfile(int $userId, array $data): array
    {
        $errors = [];

        // Validate username
        if (empty($data['username']) || strlen($data['username']) < 3) {
            $errors[] = "Username must be at least 3 characters.";
        }

        // Validate email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required.";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $result = $this->repo->updateProfile($userId, $data);

        if ($result) {
            return ['success' => true, 'message' => 'Profile updated successfully.'];
        } else {
            return ['success' => false, 'errors' => ['Failed to update profile.']];
        }
    }

    /**
     * Change user password
     */
    public function changePassword(int $userId, string $current, string $new, string $confirm): array
    {
        // Verify current password
        $user = $this->repo->findById($userId);
        if (!$user || !password_verify($current, $user['password'])) {
            return ['success' => false, 'error' => 'Current password is incorrect.'];
        }

        // Validate new password
        if (strlen($new) < 6) {
            return ['success' => false, 'error' => 'New password must be at least 6 characters.'];
        }

        if ($new !== $confirm) {
            return ['success' => false, 'error' => 'New passwords do not match.'];
        }

        $result = $this->repo->updatePassword($user['email'], $new);

        if ($result) {
            return ['success' => true, 'message' => 'Password changed successfully.'];
        } else {
            return ['success' => false, 'error' => 'Failed to change password.'];
        }
    }
}
