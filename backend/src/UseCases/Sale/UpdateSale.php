<?php

namespace SalesAppApi\UseCases\Sale;

use Exception;
use SalesAppApi\Domain\CustomerRepositoryInterface;
use SalesAppApi\Domain\PaymentMethodRepositoryInterface;
use SalesAppApi\Domain\ProductRepositoryInterface;
use SalesAppApi\Domain\Sale;
use SalesAppApi\Domain\SaleRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Shared\Auth\Auth;

class UpdateSale{

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
     *      'id' => int,
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
        $sale = $this->saleRepository->getSaleById($data['id']);

        if(empty($sale)) {
            throw new Exception("Venda não encontrada", 422);
        }

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

        $productQuantity = $product->getQuantity() + $sale->getQuantity();
        /**
         * Verificar quantidade
         */
        if($productQuantity < $data['quantity']) {
            throw new Exception("Quantidade indisponivel", 422);
        }

        $sale->setCustomerId($customer->getId())
            ->setProductId($product->getId())
            ->setPaymentMethodId($paymentMethod->getId())
            ->setQuantity($data['quantity'])
            ->setTotalValue($product->getPrice() * $data['quantity'])
            ->setBaseValue($product->getPrice())
            ->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));

        $product->setQuantity($productQuantity - $data['quantity'])->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));
        $sale->setProduct($product);

        $this->saleRepository->update($sale);
        $this->productRepository->update($product);

        return $sale->toArray();
    }
}
