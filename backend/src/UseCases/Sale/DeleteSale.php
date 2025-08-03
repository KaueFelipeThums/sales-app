<?php

namespace SalesAppApi\UseCases\Sale;

use Exception;
use SalesAppApi\Domain\ProductRepositoryInterface;
use SalesAppApi\Domain\SaleRepositoryInterface;

class DeleteSale{

    public function __construct(
        private SaleRepositoryInterface $saleRepository,
        private ProductRepositoryInterface $productRepository
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): void
    {
        $sale = $this->saleRepository->getSaleById($id);
        if(empty($sale)){
            throw new Exception("Venda nao encontrada", 404);
        }

        $product = $sale->getProduct();
        $product->setQuantity($product->getQuantity() + $sale->getQuantity());

        $this->saleRepository->delete($id);
        $this->productRepository->update($product);
    }
}
