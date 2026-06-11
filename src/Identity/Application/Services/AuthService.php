<?php

namespace MediaLibrary\Identity\Application\Services;

use MediaLibrary\Identity\Domain\Repositories\UserRepositoryInterface;
use MediaLibrary\Identity\Domain\ValueObjects\Email;
use MediaLibrary\Identity\Application\DTOs\UserDto;
use MediaLibrary\Identity\Application\DTOs\LoginRequest;
use MediaLibrary\Identity\Application\DTOs\RegisterRequest;

/**
 * Authentication Application Service
 */
class AuthService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register new user
     */
    public function register(RegisterRequest $request): array
    {
        $errors = $request->validate();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Check if email exists
        $existingEmail = $this->userRepository->findByEmail(Email::fromString($request->email));
        if ($existingEmail) {
            return ['success' => false, 'errors' => ['Email already exists']];
        }

        // Check if username exists
        $existingUsername = $this->userRepository->findByUsername($request->username);
        if ($existingUsername) {
            return ['success' => false, 'errors' => ['Username already exists']];
        }

        // Create user
        $user = \MediaLibrary\Identity\Domain\Entities\User::create(
            $request->username,
            Email::fromString($request->email),
            $request->password,
            $request->avatar,
            $request->bio
        );

        $saved = $this->userRepository->save($user);

        return [
            'success' => true,
            'user' => UserDto::fromEntity($saved)
        ];
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request): array
    {
        $errors = $request->validate();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $user = $this->userRepository->findByEmail(Email::fromString($request->email));
        
        if (!$user || !$user->verifyPassword($request->password)) {
            return ['success' => false, 'errors' => ['Invalid credentials']];
        }

        return [
            'success' => true,
            'user' => UserDto::fromEntity($user)
        ];
    }

    /**
     * Get user by ID
     */
    public function getUser(int $userId): ?UserDto
    {
        $user = $this->userRepository->findById(\MediaLibrary\Shared\Domain\ValueObjects\UserId::fromInt($userId));
        return $user ? UserDto::fromEntity($user) : null;
    }
}
