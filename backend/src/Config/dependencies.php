<?php
namespace SalesAppApi\Config;

use SalesAppApi\Bootstrap\App;
use SalesAppApi\Domain\RefreshTokenRepositoryInterface;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Infrastructure\Repository\RefreshTokenRepository;
use SalesAppApi\Infrastructure\Repository\UserRepository;

return function (App $app) {
    $app->addResolveDefinition(UserRepositoryInterface::class, UserRepository::class);
    $app->addResolveDefinition(RefreshTokenRepositoryInterface::class, RefreshTokenRepository::class);
};