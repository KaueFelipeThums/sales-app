<?php

namespace SalesAppApi\Domain;

use SalesAppApi\Domain\ValueObjects\DateTime;

class Product
{
    public function __construct(
        private ?int $id,
        private int $usersId,
        private string $name,
        private int $quantity,
        private float $price,
        private int $isActive,
        private DateTime $createdAt,
        private ?DateTime $updatedAt,

        /**
         * Usuário que criou o registro
         *
         * @var User|null
         */
        private ?User $user
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsersId(): int
    {
        return $this->usersId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "users_id" => $this->usersId,
            "name" => $this->name,
            "quantity" => $this->quantity,
            "price" => $this->price,
            "is_active" => $this->isActive,
            "created_at" => $this->createdAt->getDateTime(),
            "updated_at" => $this->updatedAt ? $this->updatedAt->getDateTime() : null,
            "user" => !empty($this->user) ? $this->user->toArray() : null
        ];
    }
}