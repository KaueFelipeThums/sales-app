<?php

namespace SalesAppApi\UseCases\Sale;

use SalesAppApi\Domain\SaleProductRepositoryInterface;
use SalesAppApi\Domain\SaleRepositoryInterface;

class GetSaleById{

    public function __construct(
        private SaleRepositoryInterface $saleRepository,
        private SaleProductRepositoryInterface $saleProductRepository
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): ?array
    {
        $sale = $this->saleRepository->getSaleById($id);

        if(empty($sale)){
            return null;
        }

        $saleProducts = $this->saleProductRepository->getAllSaleProductsBySalesId($sale->getId());

        foreach($saleProducts as $saleProduct){
            $sale->addSaleProduct($saleProduct);
        }
        return $sale->toArray();
    }
}
