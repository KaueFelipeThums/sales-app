<?php
namespace SalesAppApi\Domain;

use SalesAppApi\Domain\User;
use SalesAppApi\Domain\ValueObjects\DateTime;

class Customer 
{
    public function __construct(
        private ?int $id,
        private int $usersId,
        private string $name,
        private string $cpf,
        private ?string $email,
        private ?string $zipCode,
        private ?string $street,
        private ?string $number,
        private ?string $complement,
        private ?string $neighborhood,
        private ?string $city,
        private ?string $state,
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function getEmail(): ?string
    {
        return $this->email;
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

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getState(): ?string
    {
        return $this->state;
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
            "name" => $this->name,
            "cpf" => $this->cpf,
            "email" => $this->email,
            "zip_code" => $this->zipCode,
            "street" => $this->street,
            "number" => $this->number,
            "complement" => $this->complement,
            "neighborhood" => $this->neighborhood,
            "city" => $this->city,
            "state" => $this->state,
            "is_active" => $this->isActive,
            "created_at" => $this->createdAt->getDateTime(),
            "updated_at" => $this->updatedAt ? $this->updatedAt->getDateTime() : null,
            "user" => !empty($this->user) ? $this->user->toArray() : null
        ];
    }


}