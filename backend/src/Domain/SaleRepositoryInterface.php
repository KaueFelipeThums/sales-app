<?php
namespace SalesAppApi\Domain;

interface SaleRepositoryInterface{
    public function getAllSales(string $search, int $page, int $pageCount, ?int $customerId, ?int $productId): array;
    public function getSaleById(int $id): ?Sale;

    public function update(Sale $sale): void;
    public function create(Sale $sale): int;
    public function delete(int $id): void;
}
