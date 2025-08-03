<?php

namespace SalesAppApi\UseCases\Product;

use SalesAppApi\Domain\ProductRepositoryInterface;

class GetProductById{

    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): ?array
    {
        $product = $this->productRepository->getProductById($id);
        if(empty($product)){
            return null;
        }
        return $product->toArray();
    }
}
