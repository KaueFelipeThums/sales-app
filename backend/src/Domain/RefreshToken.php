<?php
namespace SalesAppApi\Domain;

use SalesAppApi\Domain\ValueObjects\DateTime;

class RefreshToken 
{
    public function __construct(
        private ?int $id,
        private int $usersId,
        private string $token,
        private DateTime $expiresAt,
        private DateTime $createdAt,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresAt(): DateTime
    {
        return $this->expiresAt;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUsersId(): int
    {
        return $this->usersId;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "users_id" => $this->usersId,
            "token" => $this->token,
            "expires_at" => $this->expiresAt->getDateTime(),
            "created_at" => $this->createdAt->getDateTime(),
        ];
    }
}