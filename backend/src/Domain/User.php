<?php
namespace SalesAppApi\Domain;

use SalesAppApi\Domain\ValueObjects\DateTime;

class User
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private ?string $password,
        private int $isActive,
        private DateTime $createdAt,
        private ?DateTime $updatedAt
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "is_active" => $this->isActive,
            "created_at" => $this->createdAt->getDateTime(),
            "updated_at" => $this->updatedAt ? $this->updatedAt->getDateTime() : null
        ];
    }
}