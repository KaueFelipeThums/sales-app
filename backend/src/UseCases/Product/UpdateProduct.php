<?php

namespace SalesAppApi\UseCases\Product;

use Exception;
use SalesAppApi\Domain\Product;
use SalesAppApi\Domain\ProductRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;

class UpdateProduct{

    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ){}

 
    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'id' => int,
     *      'name' => string,
     *      'quantity' => string,
     *      'price' => string,
     *      'is_active' => int
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $product = $this->productRepository->getProductById($data['id']);

        if(empty($product)) {
            throw new Exception("Produto não encontrado", 422);
        }

       $newProduct = new Product(
            $product->getId(),
            $product->getUsersId(),
            $data['name'],
            $data['quantity'],
            $data['price'],
            $data['is_active'],
            $product->getCreatedAt(),
            new DateTime(date('Y-m-d H:i:s')),
            null
        );

        $this->productRepository->update($newProduct);
        $newProductArray = $newProduct->toArray();

        return $newProductArray;
    }
}
