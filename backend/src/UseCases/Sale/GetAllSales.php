<?php

namespace SalesAppApi\UseCases\Sale;

use SalesAppApi\Domain\SaleRepositoryInterface;

class GetAllSales{

    public function __construct(
        private SaleRepositoryInterface $saleRepository,
    ){}

 
    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'search' => string,
     *      'page' => int,
     *      'page_count' => int,
     *      'customer_id' => int,
     *      'product_id' => int,
     *      'status' => string
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $sales = $this->saleRepository->getAllSales(
            $data['search'], 
            $data['page'], 
            $data['page_count'], 
            $data['customer_id'],
            $data['product_id'], 
            $data['status']
        );

        $arraySales = [];

        foreach ($sales as $sale) {
            $arraySales[] = $sale->toArray();
        }

        return $arraySales;
    }
}
