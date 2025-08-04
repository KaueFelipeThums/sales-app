<?php
namespace SalesAppApi\Domain;

class SaleProduct
{
    public function __construct(
        private ?int $id,
        private int $saleId,
        private int $productId,
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

    public function getSaleId(): int
    {
        return $this->saleId;
    }

    public function setSaleId(int $saleId): self
    {
        $this->saleId = $saleId;
        return $this;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;
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
            'sale_id' => $this->saleId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'base_value' => $this->baseValue,
            'product' => !empty($this->product) ? $this->product->toArray() : null,
        ];
    }
}
