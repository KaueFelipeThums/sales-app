<?php

namespace SalesAppApi\UseCases\Sale;

use Exception;
use SalesAppApi\Domain\CustomerRepositoryInterface;
use SalesAppApi\Domain\PaymentMethodRepositoryInterface;
use SalesAppApi\Domain\ProductRepositoryInterface;
use SalesAppApi\Domain\Sale;
use SalesAppApi\Domain\SaleRepositoryInterface;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Shared\Auth\Auth;

class CreateSale{

    public function __construct(
        private SaleRepositoryInterface $saleRepository,
        private ProductRepositoryInterface $productRepository,
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
        private CustomerRepositoryInterface $customerRepository
    ){}

    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'payment_method_id' => int,
     *      'product_id' => int,
     *      'customer_id' => int,
     *      'quantity' => int,
     *      'total_value' => float,
     *      'base_value' => float
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $product = $this->productRepository->getProductById($data['product_id']);
        if(empty($product) || $product->getIsActive() == 0) {
            throw new Exception("Produto não encontrado ou inativo", 422);
        }

        $customer = $this->customerRepository->getCustomerById($data['customer_id']);
        if(empty($customer) || $customer->getIsActive() == 0) {
            throw new Exception("Cliente não encontrado ou inativo", 422);
        }

        $paymentMethod = $this->paymentMethodRepository->getPaymentMethodById($data['payment_method_id']);
        if(empty($paymentMethod) || $paymentMethod->getIsActive() == 0) {
            throw new Exception("Forma de pagamento não encontrada ou inativa", 422);
        }

        /**
         * Verificar quantidade
         */
        if($product->getQuantity() < $data['quantity']) {
            throw new Exception("Quantidade indisponivel", 422);
        }

        $newSale = new Sale(
            null,
            $paymentMethod->getId(),
            Auth::id(),
            $product->getId(),
            $customer->getId(),
            $data['quantity'],
            $product->getPrice() * $data['quantity'],
            $product->getPrice(),
            new DateTime(date('Y-m-d H:i:s')),
            null,
            null,
            null,
            null,
            null,
            null
        );

        $product->setQuantity($product->getQuantity() - $data['quantity'])->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));

        $id = $this->saleRepository->create($newSale);
        $this->productRepository->update($product);

        $sale = $this->saleRepository->getSaleById($id)->toArray();

        return $sale;
    }
}
