<?php

namespace MediaLibrary\Domain\Repositories;

use MediaLibrary\Domain\Entities\User;
use MediaLibrary\Domain\ValueObjects\Email;
use MediaLibrary\Domain\ValueObjects\UserId;

/**
 * Interface for user data access operations (DDD Repository)
 */
interface UserRepositoryInterface
{
    /**
     * Find user by ID
     */
    public function findById(UserId $userId): ?User;

    /**
     * Find user by email
     */
    public function findByEmail(Email $email): ?User;

    /**
     * Find user by username
     */
    public function findByUsername(string $username): ?User;

    /**
     * Save user (create or update)
     */
    public function save(User $user): User;

    /**
     * Delete user
     */
    public function delete(UserId $userId): bool;

    /**
     * Get all users
     * @return User[]
     */
    public function findAll(): array;
}
