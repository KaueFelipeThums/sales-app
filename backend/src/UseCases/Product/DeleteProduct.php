<?php

namespace SalesAppApi\UseCases\Product;

use SalesAppApi\Domain\ProductRepositoryInterface;

class DeleteProduct{

    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): void
    {
        $this->productRepository->delete($id);
    }
}
