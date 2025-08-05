<?php
namespace SalesAppApi\Domain;

class SaleProduct
{
    public function __construct(
        private ?int $id,
        private int $salesId,
        private int $productsId,
        private int $quantity,
        private float $baseValue,
        private ?Product $product
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getSalesId(): int
    {
        return $this->salesId;
    }

    public function setSalesId(int $salesId): self
    {
        $this->salesId = $salesId;
        return $this;
    }

    public function getProductsId(): int
    {
        return $this->productsId;
    }

    public function setProductsId(int $productsId): self
    {
        $this->productsId = $productsId;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getBaseValue(): float
    {
        return $this->baseValue;
    }

    public function setBaseValue(float $baseValue): self
    {
        $this->baseValue = $baseValue;
        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sales_id' => $this->salesId,
            'products_id' => $this->productsId,
            'quantity' => $this->quantity,
            'base_value' => $this->baseValue,
            'product' => !empty($this->product) ? $this->product->toArray() : null,
        ];
    }
}
