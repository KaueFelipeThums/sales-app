<?php

namespace SalesAppApi\UseCases\Sale;

use SalesAppApi\Domain\SaleRepositoryInterface;

class GetSaleById{

    public function __construct(
        private SaleRepositoryInterface $saleRepository,
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): array
    {
        $sale = $this->saleRepository->getSaleById($id);
        if(empty($sale)){
            return [];
        }
        return $sale->toArray();
    }
}
