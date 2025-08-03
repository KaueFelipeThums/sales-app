<?php
namespace SalesAppApi\Domain;

interface PaymentMethodRepositoryInterface{
    public function getAllPaymentMethods(string $search, int $page, int $pageCount): array;
    public function getAllActivePaymentMethods(): array;
    public function getPaymentMethodById(int $id): ?PaymentMethod;

    public function update(PaymentMethod $paymentMethod): void;
    public function create(PaymentMethod $paymentMethod): void;
    public function delete(int $id): void;
}