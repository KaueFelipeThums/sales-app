<?php
namespace SalesAppApi\Config;

use SalesAppApi\Bootstrap\App;
use SalesAppApi\Domain\CustomerRepositoryInterface;
use SalesAppApi\Domain\RefreshTokenRepositoryInterface;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Infrastructure\Repository\CustomerRepository;
use SalesAppApi\Infrastructure\Repository\RefreshTokenRepository;
use SalesAppApi\Infrastructure\Repository\UserRepository;

return function (App $app) {
    $app->addResolveDefinition(UserRepositoryInterface::class, UserRepository::class);
    $app->addResolveDefinition(RefreshTokenRepositoryInterface::class, RefreshTokenRepository::class);
    $app->addResolveDefinition(CustomerRepositoryInterface::class, CustomerRepository::class);
};