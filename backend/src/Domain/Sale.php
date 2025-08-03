<?php
namespace SalesAppApi\Domain;

use SalesAppApi\Domain\ValueObjects\DateTime;

class Sale
{
    public function __construct(
        private ?int $id,
        private int $paymentMethodId,
        private int $userId,
        private int $productId,
        private int $customerId,
        private int $quantity,
        private float $totalValue,
        private float $baseValue,
        private string $status, // active, canceled
        private DateTime $createdAt,
        private ?DateTime $canceledAt,
        private ?DateTime $updatedAt,

        /**
         * Método de pagamento
         *
         * @var PaymentMethod|null
         */
        private ?PaymentMethod $paymentMethod,

        /**
         * Cliente
         *
         * @var Customer|null
         */
        private ?Customer $customer,

        /**
         * Produto
         *
         * @var Product|null
         */
        private ?Product $product,

        /**
         * Usuário que criou o registro
         *
         * @var User|null
         */
        private ?User $user,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentMethodId(): int
    {
        return $this->paymentMethodId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotalValue(): float
    {
        return $this->totalValue;
    }

    public function getBaseValue(): float
    {
        return $this->baseValue;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getCanceledAt(): ?DateTime
    {
        return $this->canceledAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "payment_method_id" => $this->paymentMethodId,
            "users_id" => $this->userId,
            "products_id" => $this->productId,
            "customers_id" => $this->customerId,
            "quantity" => $this->quantity,
            "total_value" => $this->totalValue,
            "base_value" => $this->baseValue,
            "status" => $this->status,
            "created_at" => $this->createdAt->getDateTime(),
            "canceled_at" => $this->canceledAt ? $this->canceledAt->getDateTime() : null,
            "updated_at" => $this->updatedAt ? $this->updatedAt->getDateTime() : null,
            "payment_method" => !empty($this->paymentMethod) ? $this->paymentMethod->toArray() : null,
            "customer" => !empty($this->customer) ? $this->customer->toArray() : null,
            "product" => !empty($this->product) ? $this->product->toArray() : null,
            "user" => !empty($this->user) ? $this->user->toArray() : null
        ];
    }
}