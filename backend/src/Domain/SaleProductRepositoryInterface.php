<?php
namespace SalesAppApi\Domain;

interface SaleProductRepositoryInterface{
    public function getAllSaleProductsBySaleId(int $saleId): array;
    public function getSaleProductById(int $id): ?SaleProduct;

    public function create(SaleProduct $saleProduct): int;
    public function delete(int $id): void;
    public function deleteBySaleId(int $saleId): void;
}
