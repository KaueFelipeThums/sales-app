<?php
namespace SalesAppApi\Domain;

use SalesAppApi\Domain\ValueObjects\DateTime;

class PaymentMethod
{
    public function __construct(
        private ?int $id,
        private int $usersId,
        private string $name,
        private int $installments,
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

    public function setId(?int $id): PaymentMethod
    {
        $this->id = $id;
        return $this;
    }

    public function getUsersId(): int
    {
        return $this->usersId;
    }

    public function setUsersId(int $usersId): PaymentMethod
    {
        $this->usersId = $usersId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PaymentMethod
    {
        $this->name = $name;
        return $this;
    }

    public function getInstallments(): int
    {
        return $this->installments;
    }

    public function setInstallments(int $installments): PaymentMethod
    {
        $this->installments = $installments;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): PaymentMethod
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): PaymentMethod
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): PaymentMethod
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): PaymentMethod
    {
        $this->user = $user;
        return $this;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "installments" => $this->installments,
            "is_active" => $this->isActive,
            "created_at" => $this->createdAt->getDateTime(),
            "updated_at" => $this->updatedAt ? $this->updatedAt->getDateTime() : null,
            "user" => !empty($this->user) ? $this->user->toArray() : null
        ];
    }
}
