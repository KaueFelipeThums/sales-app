<?php
namespace SalesAppApi\Domain;

interface CustomerRepositoryInterface{
    public function getAllCustomers(string $search, int $page, int $pageCount): array;
    public function getAllActiveCustomers(): array;
    public function getCustomerById(int $id): ?Customer;

    public function update(Customer $customer): void;
    public function create(Customer $customer): void;
    public function delete(int $id): void;
}