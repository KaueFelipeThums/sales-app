<?php

namespace SalesAppApi\UseCases\Product;

use Exception;
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
     *      'quantity' => int,
     *      'price' => float,
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

        $product->setName($data['name'])
            ->setQuantity($data['quantity'])
            ->setPrice($data['price'])
            ->setIsActive($data['is_active'])
            ->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));

        $this->productRepository->update($product);
        return $product->toArray();
    }
}
