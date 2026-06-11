<?php

namespace MediaLibrary\Identity\Infrastructure\Persistence;

use MediaLibrary\Identity\Domain\Repositories\LegacyUserRepositoryInterface;
use MediaLibrary\Shared\Infrastructure\Persistence\BaseRepository;
use PDO;

class UserRepository extends BaseRepository implements LegacyUserRepositoryInterface
{
    public function findById(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE user_id = :user_id LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

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

    public function updatePassword(string $email, string $newPassword): bool
    {
        $stmt = $this->db->prepare("UPDATE Users SET password = :password WHERE email = :email");
        return $stmt->execute([
            ':email' => $email,
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

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
        return $this->db->prepare($sql)->execute($params);
    }

    public function createPasswordResetToken(string $email, string $token, int $expiresInHours = 1): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Password_Resets (email, token, expires_at) VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL :hours HOUR))"
        );
        return $stmt->execute([':email' => $email, ':token' => $token, ':hours' => $expiresInHours]);
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

    public function createRememberToken(int $userId, string $token, int $expiresInDays = 30): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Remember_Tokens (user_id, token, expires_at) VALUES (:user_id, :token, DATE_ADD(NOW(), INTERVAL :days DAY))"
        );
        return $stmt->execute([':user_id' => $userId, ':token' => $token, ':days' => $expiresInDays]);
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
