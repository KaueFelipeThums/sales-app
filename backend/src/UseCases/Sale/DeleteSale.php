<?php

namespace SalesAppApi\UseCases\Sale;

use Exception;
use SalesAppApi\Domain\ProductRepositoryInterface;
use SalesAppApi\Domain\SaleProductRepositoryInterface;
use SalesAppApi\Domain\SaleRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Infrastructure\Database\Database;

class DeleteSale{

    public function __construct(
        private SaleRepositoryInterface $saleRepository,
        private ProductRepositoryInterface $productRepository,
        private SaleProductRepositoryInterface $saleProductRepository,
        private Database $database
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): void
    {
        $this->database->beginTransaction();
        try {
            $sale = $this->saleRepository->getSaleById($id);
            if(empty($sale)){
                throw new Exception("Venda nao encontrada", 404);
            }

            /**
             * Products to update
             */
            $saleProducts = $this->saleProductRepository->getAllSaleProductsBySalesId($sale->getId());

            foreach ($saleProducts as $saleProduct) {
                $product = $saleProduct->getProduct();
                $product->setQuantity($product->getQuantity() + $saleProduct->getQuantity())
                    ->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));

                $this->productRepository->update($product);
            }

            $this->saleProductRepository->deleteBySalesId($sale->getId());
            $this->saleRepository->delete($id);
            $this->database->commit();
        } catch (Exception $e) {
            $this->database->rollBack();
            throw $e;
        }
    }
}
