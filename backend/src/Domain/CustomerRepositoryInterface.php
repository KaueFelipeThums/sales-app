<?php
namespace SalesAppApi\Domain;

interface CustomerRepositoryInterface{
    public function getAllCustomers(string $search, int $page, int $pageCount): array;
    public function getAllActiveCustomers(string $search, int $page, int $pageCount): array;
    public function getCustomerById(int $id): ?Customer;
    public function getCustomerByCpf(int $cpf): ?Customer;

    public function update(Customer $customer): void;
    public function create(Customer $customer): int;
    public function delete(int $id): void;
}