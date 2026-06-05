<?php

namespace MediaLibrary\Infrastructure\Persistence;

use MediaLibrary\Domain\Repositories\UserRepositoryInterface;
use PDO;

/**
 * Repository for user database operations
 */
class UserRepository implements UserRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Find user by ID
     */
    public function findById(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE user_id = :user_id LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Find user by username
     */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Create new user
     */
    public function create(string $username, string $email, string $password): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Users (username, email, password) VALUES (:username, :email, :password)"
        );
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    /**
     * Update user password
     */
    public function updatePassword(string $email, string $newPassword): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE Users SET password = :password WHERE email = :email"
        );
        return $stmt->execute([
            ':email' => $email,
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(int $userId, array $data): bool
    {
        $allowedFields = ['username', 'email', 'bio', 'avatar'];
        $updates = [];
        $params = [':user_id' => $userId];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }

        if (empty($updates)) {
            return false;
        }

        $sql = "UPDATE Users SET " . implode(', ', $updates) . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Password Reset Token
     */
    public function createPasswordResetToken(string $email, string $token, int $expiresInHours = 1): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Password_Resets (email, token, expires_at) VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL :hours HOUR))"
        );
        return $stmt->execute([
            ':email' => $email,
            ':token' => $token,
            ':hours' => $expiresInHours
        ]);
    }

    public function findPasswordResetByToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM Password_Resets WHERE token = :token AND expires_at > NOW() LIMIT 1"
        );
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function deletePasswordResetToken(string $token): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Password_Resets WHERE token = :token");
        return $stmt->execute([':token' => $token]);
    }

    /**
     * Remember Me Token
     */
    public function createRememberToken(int $userId, string $token, int $expiresInDays = 30): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Remember_Tokens (user_id, token, expires_at) VALUES (:user_id, :token, DATE_ADD(NOW(), INTERVAL :days DAY))"
        );
        return $stmt->execute([
            ':user_id' => $userId,
            ':token' => $token,
            ':days' => $expiresInDays
        ]);
    }

    public function findRememberToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT rt.*, u.* FROM Remember_Tokens rt JOIN Users u ON rt.user_id = u.user_id WHERE rt.token = :token AND rt.expires_at > NOW() LIMIT 1"
        );
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function deleteRememberToken(string $token): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Remember_Tokens WHERE token = :token");
        return $stmt->execute([':token' => $token]);
    }

    public function deleteAllUserRememberTokens(int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Remember_Tokens WHERE user_id = :user_id");
        return $stmt->execute([':user_id' => $userId]);
    }
}
