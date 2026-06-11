<?php

namespace MediaLibrary\Identity\Application\DTOs;

use MediaLibrary\Identity\Domain\Entities\User;

/**
 * User DTO
 */
readonly class UserDto
{
    public function __construct(
        public int $id,
        public string $username,
        public string $email,
        public ?string $avatar,
        public ?string $bio,
        public bool $isVerified,
        public bool $isAdmin,
        public string $createdAt
    ) {}

    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->id()->value(),
            username: $user->username(),
            email: $user->email()->value(),
            avatar: $user->avatar(),
            bio: $user->bio(),
            isVerified: $user->isVerified(),
            isAdmin: $user->isAdmin(),
            createdAt: $user->createdAt()->format('Y-m-d H:i:s')
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'is_verified' => $this->isVerified,
            'is_admin' => $this->isAdmin,
            'created_at' => $this->createdAt,
        ];
    }
}
