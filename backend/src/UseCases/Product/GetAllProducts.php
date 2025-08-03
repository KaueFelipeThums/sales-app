<?php

namespace SalesAppApi\UseCases\Product;

use SalesAppApi\Domain\ProductRepositoryInterface;

class GetAllProducts{

    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ){}

 
    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'search' => string,
     *      'page' => int,
     *      'page_count' => int
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $products = $this->productRepository->getAllProducts($data['search'], $data['page'], $data['page_count']);

        $arrayProducts = [];

        foreach ($products as $product) {
            $arrayProducts[] = $product->toArray();
        }

        return $arrayProducts;
    }
}
