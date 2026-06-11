<?php

namespace MediaLibrary\Identity\Domain\Repositories;

/**
 * Legacy User Repository Interface
 * Array-based, matches current UserRepository implementation
 */
interface LegacyUserRepositoryInterface
{
    public function findById(int $userId): ?array;
    public function findByEmail(string $email): ?array;
    public function findByUsername(string $username): ?array;
    public function create(string $username, string $email, string $password): bool;
    public function updatePassword(string $email, string $newPassword): bool;
    public function updateProfile(int $userId, array $data): bool;
    public function createPasswordResetToken(string $email, string $token, int $expiresInHours = 1): bool;
    public function findPasswordResetByToken(string $token): ?array;
    public function deletePasswordResetToken(string $token): bool;
    public function createRememberToken(int $userId, string $token, int $expiresInDays = 30): bool;
    public function findRememberToken(string $token): ?array;
    public function deleteRememberToken(string $token): bool;
    public function deleteAllUserRememberTokens(int $userId): bool;
}
