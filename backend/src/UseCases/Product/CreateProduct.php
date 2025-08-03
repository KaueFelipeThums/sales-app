<?php

namespace SalesAppApi\UseCases\Product;

use Exception;
use SalesAppApi\Domain\Product;
use SalesAppApi\Domain\ProductRepositoryInterface;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Shared\Auth\Auth;

class CreateProduct{

    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private UserRepositoryInterface $userRepository
    ){}

    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'name' => string,
     *      'quantity' => string,
     *      'price' => string,
     *      'is_active' => int
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $newProduct = new Product(
            null,
            Auth::id(),
            $data['name'],
            $data['quantity'],
            $data['price'],
            $data['is_active'],
            new DateTime(date('Y-m-d H:i:s')),
            null,
            null
        );


        $id = $this->productRepository->create($newProduct);
        $product = $this->productRepository->getProductById($id)->toArray();

        return $product;
    }
}
