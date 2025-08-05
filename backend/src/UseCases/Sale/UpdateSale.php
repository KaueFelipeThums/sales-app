<?php

namespace SalesAppApi\UseCases\Sale;

use Exception;
use SalesAppApi\Domain\CustomerRepositoryInterface;
use SalesAppApi\Domain\PaymentMethodRepositoryInterface;
use SalesAppApi\Domain\ProductRepositoryInterface;
use SalesAppApi\Domain\Sale;
use SalesAppApi\Domain\SaleProduct;
use SalesAppApi\Domain\SaleProductRepositoryInterface;
use SalesAppApi\Domain\SaleRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Shared\Auth\Auth;

class UpdateSale{

    public function __construct(
        private SaleRepositoryInterface $saleRepository,
        private ProductRepositoryInterface $productRepository,
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
        private CustomerRepositoryInterface $customerRepository,
        private SaleProductRepositoryInterface $saleProductRepository
    ){}

 
    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'payment_methods_id' => int,
     *      'customers_id' => int,
     *      'total_value' => float,
     *      'products' => array
     *          [
     *              'products_id' => int,
     *              'quantity' => int
     *          ]
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $sale = $this->saleRepository->getSaleById($data['id']);

        if(empty($sale)) {
            throw new Exception("Venda não encontrada", 422);
        }

        $customer = $this->customerRepository->getCustomerById($data['customers_id']);
        if(empty($customer) || $customer->getIsActive() == 0) {
            throw new Exception("Cliente não encontrado ou inativo", 422);
        }

        $paymentMethod = $this->paymentMethodRepository->getPaymentMethodById($data['payment_methods_id']);
        if(empty($paymentMethod) || $paymentMethod->getIsActive() == 0) {
            throw new Exception("Forma de pagamento não encontrada ou inativa", 422);
        }

        /**
         * Products to update
         */
        $saleProducts = $this->saleProductRepository->getAllSaleProductsBySalesId($sale->getId());
        $arrayProductsUpdate = [];

        foreach ($saleProducts as $saleProduct) {
            $product = $saleProduct->getProduct();
            $product->setQuantity($product->getQuantity() + $saleProduct->getQuantity());
            $arrayProductsUpdate[$product->getId()] = $product;
        }

        /**
         * Products to insert
         */
        $arraySaleProducts = [];
        $totalValue = 0;

        foreach ($data['products'] as $selectedProduct) {
            /**
             * Check array data
             */
            if(empty($selectedProduct['quantity']) || $selectedProduct['quantity'] <= 0) {
                throw new Exception("Quantidade inválida", 422);
            }

            if(empty($selectedProduct['products_id'])) {
                throw new Exception("Produto inválido", 422);
            }

            if(!empty($arrayProductsUpdate[$selectedProduct['products_id']])) {
                $product = $arrayProductsUpdate[$selectedProduct['products_id']];
            } else {
                $product = $this->productRepository->getProductById($selectedProduct['products_id']);
            }


            if(empty($product) || $product->getIsActive() == 0) {
                throw new Exception("Produto não encontrado ou inativo", 422);
            }

            /**
             * Check quantity
             */
            if($product->getQuantity() < $selectedProduct['quantity']) {
                throw new Exception("Quantidade indisponível do produto {$product->getName()}", 422);
            }
            
            $product->setQuantity($product->getQuantity() - $selectedProduct['quantity']);
            $arraySaleProducts[] = [
                'products_id' => $product->getId(),
                'quantity' => $selectedProduct['quantity'],
                'base_value' => $product->getPrice(),
                'product' => $product
            ];
            $totalValue += $product->getPrice() * $selectedProduct['quantity'];

            unset($arrayProductsUpdate[$product->getId()]);
        }

        $sale->setCustomersId($customer->getId())
            ->setPaymentMethodsId($paymentMethod->getId())
            ->setTotalValue($totalValue)
            ->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));

        $this->saleRepository->update($sale);

        /**
         * Reset products
         */ 
        $this->saleProductRepository->deleteBySalesId($sale->getId());

        /**
         * Update removed products
         */
        foreach ($arrayProductsUpdate as $product) {
            $product->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));
            $this->productRepository->update($product);
        }

        /**
         * Insert new products
         */
        foreach ($arraySaleProducts as $saleProduct) {
            $newSaleProduct = new SaleProduct(
                null,
                $sale->getId(),
                $saleProduct['products_id'],
                $saleProduct['quantity'],
                $saleProduct['base_value'],
                $saleProduct['product']
            );


            $saleProductsId = $this->saleProductRepository->create($newSaleProduct);
            $newSaleProduct->setId($saleProductsId);

            $this->productRepository->update($saleProduct['product']);
            $sale->addSaleProduct($newSaleProduct);
        }

        return $sale->toArray();
    }
}
