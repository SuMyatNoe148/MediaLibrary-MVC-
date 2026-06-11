<?php

namespace MediaLibrary\Identity\Application\Services;

use MediaLibrary\Identity\Domain\Repositories\LegacyUserRepositoryInterface;
use MediaLibrary\Identity\Infrastructure\Persistence\UserRepository;

class UserService
{
    private LegacyUserRepositoryInterface $repo;

    public function __construct(?LegacyUserRepositoryInterface $repo = null)
    {
        if ($repo === null) {
            $db = \Database::getConnection();
            $repo = new UserRepository($db);
        }
        $this->repo = $repo;
    }

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

    public function register(string $username, string $email, string $password, string $confirmPassword = ''): array
    {
        $errors = [];
        if (strlen($username) < 3) $errors[] = 'Username must be at least 3 characters.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if ($confirmPassword !== '' && $password !== $confirmPassword) $errors[] = 'Passwords do not match.';
        if (!empty($errors)) return ['success' => false, 'errors' => $errors];

        if ($this->repo->findByEmail($email)) {
            return ['success' => false, 'errors' => ['Email already in use.']];
        }
        if ($this->repo->findByUsername($username)) {
            return ['success' => false, 'errors' => ['Username already taken.']];
        }
        $created = $this->repo->create($username, $email, $password);
        if (!$created) {
            return ['success' => false, 'errors' => ['Failed to create account.']];
        }
        return ['success' => true];
    }

    public function getUserById(int $userId): ?array
    {
        return $this->repo->findById($userId);
    }

    public function getUserByEmail(string $email): ?array
    {
        return $this->repo->findByEmail($email);
    }

    public function updateProfile(int $userId, array $data): array
    {
        $errors = [];
        if (empty($data['username']) || strlen($data['username']) < 3) {
            $errors[] = "Username must be at least 3 characters.";
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required.";
        }
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        $result = $this->repo->updateProfile($userId, $data);
        return $result
            ? ['success' => true, 'message' => 'Profile updated successfully.']
            : ['success' => false, 'errors' => ['Failed to update profile.']];
    }

    public function changePassword(int $userId, string $current, string $new, string $confirm): array
    {
        $user = $this->repo->findById($userId);
        if (!$user || !password_verify($current, $user['password'])) {
            return ['success' => false, 'error' => 'Current password is incorrect.'];
        }
        if (strlen($new) < 6) {
            return ['success' => false, 'error' => 'New password must be at least 6 characters.'];
        }
        if ($new !== $confirm) {
            return ['success' => false, 'error' => 'New passwords do not match.'];
        }
        $result = $this->repo->updatePassword($user['email'], $new);
        return $result
            ? ['success' => true, 'message' => 'Password changed successfully.']
            : ['success' => false, 'error' => 'Failed to change password.'];
    }

    public function requestPasswordReset(string $email): array
    {
        $user = $this->repo->findByEmail($email);
        if (!$user) {
            return ['success' => false, 'error' => 'No account found with that email.'];
        }
        $token = bin2hex(random_bytes(32));
        $this->repo->createPasswordResetToken($email, $token, 1);
        return ['success' => true, 'token' => $token];
    }

    public function verifyResetToken(string $token): ?array
    {
        return $this->repo->findPasswordResetByToken($token);
    }

    public function resetPassword(string $token, string $password, string $confirmPassword): array
    {
        $tokenData = $this->repo->findPasswordResetByToken($token);
        if (!$tokenData) {
            return ['success' => false, 'error' => 'Invalid or expired token.'];
        }
        if (strlen($password) < 6) {
            return ['success' => false, 'error' => 'Password must be at least 6 characters.'];
        }
        if ($password !== $confirmPassword) {
            return ['success' => false, 'error' => 'Passwords do not match.'];
        }
        $this->repo->updatePassword($tokenData['email'], $password);
        $this->repo->deletePasswordResetToken($token);
        return ['success' => true, 'message' => 'Password reset successfully. Please login.'];
    }

    public function createRememberToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $this->repo->createRememberToken($userId, $token, 30);
        return $token;
    }

    public function loginWithRememberToken(string $token): ?array
    {
        $data = $this->repo->findRememberToken($token);
        return $data ?: null;
    }

    public function deleteRememberToken(string $token): void
    {
        $this->repo->deleteRememberToken($token);
    }
}
