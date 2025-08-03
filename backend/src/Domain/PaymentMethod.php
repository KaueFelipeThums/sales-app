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
         * @var User
         */
        private User $user
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

    public function getInstallments(): int
    {
        return $this->installments;
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

    public function getUser(): User
    {
        return $this->user;
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
            "user" => $this->user->toArray()
        ];
    }
}