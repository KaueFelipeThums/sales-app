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

    public function setId(?int $id): Customer
    {
        $this->id = $id;
        return $this;
    }

    public function getUsersId(): int
    {
        return $this->usersId;
    }

    public function setUsersId(int $usersId): Customer
    {
        $this->usersId = $usersId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Customer
    {
        $this->name = $name;
        return $this;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): Customer
    {
        $this->cpf = $cpf;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Customer
    {
        $this->email = $email;
        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): Customer
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): Customer
    {
        $this->street = $street;
        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): Customer
    {
        $this->number = $number;
        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): Customer
    {
        $this->complement = $complement;
        return $this;
    }

    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(?string $neighborhood): Customer
    {
        $this->neighborhood = $neighborhood;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): Customer
    {
        $this->city = $city;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): Customer
    {
        $this->state = $state;
        return $this;
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): Customer
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Customer
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): Customer
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Customer
    {
        $this->user = $user;
        return $this;
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
