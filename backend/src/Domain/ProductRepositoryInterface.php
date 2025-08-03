<?php
namespace SalesAppApi\Domain;

interface ProductRepositoryInterface{
    public function getAllProducts(string $search, int $page, int $pageCount): array;
    public function getAllActiveProducts(): array;
    public function getProductById(int $id): ?Product;

    public function update(Product $product): void;
    public function create(Product $product): void;
    public function delete(int $id): void;
}