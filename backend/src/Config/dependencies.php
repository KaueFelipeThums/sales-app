<?php
namespace SalesAppApi\Config;

use SalesAppApi\Bootstrap\App;
use SalesAppApi\Domain\CustomerRepositoryInterface;
use SalesAppApi\Domain\PaymentMethodRepositoryInterface;
use SalesAppApi\Domain\ProductRepositoryInterface;
use SalesAppApi\Domain\RefreshTokenRepositoryInterface;
use SalesAppApi\Domain\SaleProductRepositoryInterface;
use SalesAppApi\Domain\SaleRepositoryInterface;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Infrastructure\Repository\CustomerRepository;
use SalesAppApi\Infrastructure\Repository\PaymentMethodRepository;
use SalesAppApi\Infrastructure\Repository\ProductRepository;
use SalesAppApi\Infrastructure\Repository\RefreshTokenRepository;
use SalesAppApi\Infrastructure\Repository\SaleProductRepository;
use SalesAppApi\Infrastructure\Repository\SaleRepository;
use SalesAppApi\Infrastructure\Repository\UserRepository;

return function (App $app) {
    $app->addResolveDefinition(UserRepositoryInterface::class, UserRepository::class);
    $app->addResolveDefinition(RefreshTokenRepositoryInterface::class, RefreshTokenRepository::class);
    $app->addResolveDefinition(CustomerRepositoryInterface::class, CustomerRepository::class);
    $app->addResolveDefinition(ProductRepositoryInterface::class, ProductRepository::class);
    $app->addResolveDefinition(PaymentMethodRepositoryInterface::class, PaymentMethodRepository::class);
    $app->addResolveDefinition(SaleRepositoryInterface::class, SaleRepository::class);
    $app->addResolveDefinition(SaleProductRepositoryInterface::class, SaleProductRepository::class);
};