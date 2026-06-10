<?php

namespace MediaLibrary\Domain\Entities;

use DateTimeImmutable;
use MediaLibrary\Domain\ValueObjects\Email;
use MediaLibrary\Domain\ValueObjects\UserId;

/**
 * User Entity - Aggregate Root
 */
class User
{
    private UserId $id;
    private string $username;
    private Email $email;
    private string $password;
    private ?string $avatar;
    private ?string $bio;
    private bool $isVerified;
    private bool $isAdmin;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        UserId $id,
        string $username,
        Email $email,
        string $password,
        ?string $avatar = null,
        ?string $bio = null,
        bool $isVerified = false,
        bool $isAdmin = false,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->avatar = $avatar;
        $this->bio = $bio;
        $this->isVerified = $isVerified;
        $this->isAdmin = $isAdmin;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
    }

    public static function create(
        string $username,
        Email $email,
        string $password,
        ?string $avatar = null,
        ?string $bio = null
    ): self {
        return new self(
            UserId::generate(),
            $username,
            $email,
            password_hash($password, PASSWORD_BCRYPT),
            $avatar,
            $bio
        );
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function avatar(): ?string
    {
        return $this->avatar;
    }

    public function bio(): ?string
    {
        return $this->bio;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function verify(): void
    {
        $this->isVerified = true;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function makeAdmin(): void
    {
        $this->isAdmin = true;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updateAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updateBio(string $bio): void
    {
        $this->bio = $bio;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->id->value(),
            'username' => $this->username,
            'email' => $this->email->value(),
            'password' => $this->password,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'is_verified' => $this->isVerified,
            'is_admin' => $this->isAdmin,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
