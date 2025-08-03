<?php

namespace SalesAppApi\UseCases\Sale;

use SalesAppApi\Domain\SaleRepositoryInterface;

class DeleteSale{

    public function __construct(
        private SaleRepositoryInterface $saleRepository,
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): void
    {
        $this->saleRepository->delete($id);
    }
}
