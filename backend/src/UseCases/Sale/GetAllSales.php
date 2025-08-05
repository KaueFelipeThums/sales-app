<?php

namespace SalesAppApi\UseCases\Sale;

use SalesAppApi\Domain\SaleProductRepositoryInterface;
use SalesAppApi\Domain\SaleRepositoryInterface;

class GetAllSales{

    public function __construct(
        private SaleRepositoryInterface $saleRepository,
        private SaleProductRepositoryInterface $saleProductRepository
    ){}

 
    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'search' => string,
     *      'page' => int,
     *      'page_count' => int,
     *      'customers_dd' => int
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $sales = $this->saleRepository->getAllSales(
            $data['search'], 
            $data['page'], 
            $data['page_count'], 
            $data['customers_dd']
        );

        $arraySales = [];

        foreach ($sales as $sale) {
            $saleProducts = $this->saleProductRepository->getAllSaleProductsBySalesId($sale->getId());

            foreach($saleProducts as $saleProduct){
                $sale->addSaleProduct($saleProduct);
            }

            $arraySales[] = $sale->toArray();
        }

        return $arraySales;
    }
}
