<?php
namespace SalesAppApi\Domain;

interface SaleProductRepositoryInterface{
    public function getAllSaleProductsBySalesId(int $salesId): array;
    public function getSaleProductById(int $id): ?SaleProduct;

    public function create(SaleProduct $saleProduct): int;
    public function delete(int $id): void;
    public function deleteBySalesId(int $salesId): void;
}
