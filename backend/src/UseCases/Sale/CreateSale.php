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
use SalesAppApi\Infrastructure\Database\Database;
use SalesAppApi\Shared\Auth\Auth;

class CreateSale{
    public function __construct(
        private SaleRepositoryInterface $saleRepository,
        private ProductRepositoryInterface $productRepository,
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
        private CustomerRepositoryInterface $customerRepository,
        private SaleProductRepositoryInterface $saleProductRepository,
        private Database $database
    ){}

    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'payment_methods_id' => int,
     *      'customers_id' => int,
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
        $this->database->beginTransaction();
        try {
            $customer = $this->customerRepository->getCustomerById($data['customers_id']);
            if(empty($customer) || $customer->getIsActive() == 0) {
                throw new Exception("Cliente não encontrado ou inativo", 422);
            }

            $paymentMethod = $this->paymentMethodRepository->getPaymentMethodById($data['payment_methods_id']);
            if(empty($paymentMethod) || $paymentMethod->getIsActive() == 0) {
                throw new Exception("Forma de pagamento não encontrada ou inativa", 422);
            }


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

                $product = $this->productRepository->getProductById($selectedProduct['products_id']);
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
            }

            $newSale = new Sale(
                null,
                $paymentMethod->getId(),
                Auth::id(),
                $customer->getId(),
                $totalValue,
                new DateTime(date('Y-m-d H:i:s')),
                null,
                null,
                null,
                [],
                null,
            );

            $id = $this->saleRepository->create($newSale);
            $sale = $this->saleRepository->getSaleById($id);

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
            $this->database->commit();
            return $sale->toArray();
        } catch (Exception $e) {
            $this->database->rollBack();
            throw $e;
        }
    }
}
