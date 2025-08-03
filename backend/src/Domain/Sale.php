<?php
namespace SalesAppApi\Domain;

use SalesAppApi\Domain\ValueObjects\DateTime;

class Sale
{
    public function __construct(
        private ?int $id,
        private int $paymentMethodId,
        private int $usersId,
        private int $productId,
        private int $customerId,
        private int $quantity,
        private float $totalValue,
        private float $baseValue,
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

    public function setId(?int $id): Sale
    {
        $this->id = $id;
        return $this;
    }

    public function getPaymentMethodId(): int
    {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(int $paymentMethodId): Sale
    {
        $this->paymentMethodId = $paymentMethodId;
        return $this;
    }

    public function getUsersId(): int
    {
        return $this->usersId;
    }

    public function setUsersId(int $usersId): Sale
    {
        $this->usersId = $usersId;
        return $this;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): Sale
    {
        $this->productId = $productId;
        return $this;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): Sale
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): Sale
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getTotalValue(): float
    {
        return $this->totalValue;
    }

    public function setTotalValue(float $totalValue): Sale
    {
        $this->totalValue = $totalValue;
        return $this;
    }

    public function getBaseValue(): float
    {
        return $this->baseValue;
    }

    public function setBaseValue(float $baseValue): Sale
    {
        $this->baseValue = $baseValue;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Sale
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCanceledAt(): ?DateTime
    {
        return $this->canceledAt;
    }

    public function setCanceledAt(?DateTime $canceledAt): Sale
    {
        $this->canceledAt = $canceledAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): Sale
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(PaymentMethod $paymentMethod): Sale
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): Sale
    {
        $this->customer = $customer;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): Sale
    {
        $this->product = $product;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): Sale
    {
        $this->user = $user;
        return $this;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "payment_method_id" => $this->paymentMethodId,
            "users_id" => $this->usersId,
            "products_id" => $this->productId,
            "customers_id" => $this->customerId,
            "quantity" => $this->quantity,
            "total_value" => $this->totalValue,
            "base_value" => $this->baseValue,
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
