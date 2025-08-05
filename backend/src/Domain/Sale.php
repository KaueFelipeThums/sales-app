<?php
namespace SalesAppApi\Domain;

use SalesAppApi\Domain\ValueObjects\DateTime;

class Sale
{
    public function __construct(
        private ?int $id,
        private int $paymentMethodsId,
        private int $usersId,
        private int $customersId,
        private float $totalValue,
        private DateTime $createdAt,
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
         * Produtos
         *
         * @var SaleProduct[]
         */
        private array $saleProducts,

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

    public function getPaymentMethodsId(): int
    {
        return $this->paymentMethodsId;
    }

    public function setPaymentMethodsId(int $paymentMethodsId): Sale
    {
        $this->paymentMethodsId = $paymentMethodsId;
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

    public function getCustomersId(): int
    {
        return $this->customersId;
    }

    public function setCustomersId(int $customersId): Sale
    {
        $this->customersId = $customersId;
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Sale
    {
        $this->createdAt = $createdAt;
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

    public function getSaleProducts(): ?array
    {
        return $this->saleProducts;
    }

    public function addSaleProduct(SaleProduct $product): Sale
    {
        $this->saleProducts[] = $product;
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
        $arraySaleProducts = [];
        foreach ($this->saleProducts as $saleProduct) {
            $arraySaleProducts[] = $saleProduct->toArray();
        }

        return [
            "id" => $this->id,
            "payment_methods_id" => $this->paymentMethodsId,
            "users_id" => $this->usersId,
            "customers_id" => $this->customersId,
            "total_value" => $this->totalValue,
            "created_at" => $this->createdAt->getDateTime(),
            "updated_at" => $this->updatedAt ? $this->updatedAt->getDateTime() : null,
            "payment_method" => !empty($this->paymentMethod) ? $this->paymentMethod->toArray() : null,
            "customer" => !empty($this->customer) ? $this->customer->toArray() : null,
            "sale_products" => $arraySaleProducts,
            "user" => !empty($this->user) ? $this->user->toArray() : null
        ];
    }
}
