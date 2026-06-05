<?php

namespace MediaLibrary\Domain\Repositories;

/**
 * Interface for user data access operations
 */
interface UserRepositoryInterface
{
    /**
     * Find user by ID
     */
    public function findById(int $userId): ?array;

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?array;

    /**
     * Find user by username
     */
    public function findByUsername(string $username): ?array;

    /**
     * Create new user
     */
    public function create(string $username, string $email, string $password): bool;

    /**
     * Update user password
     */
    public function updatePassword(string $email, string $newPassword): bool;

    /**
     * Update user profile
     */
    public function updateProfile(int $userId, array $data): bool;
}
