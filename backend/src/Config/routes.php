<?php
namespace SalesAppApi\Config;

use SalesAppApi\Bootstrap\App;
use SalesAppApi\Infrastructure\Http\Controllers\UserController as ControllersUserController;
use SalesAppApi\Infrastructure\Http\Middlewares\AuthMiddleware;

return function (App $app) {
    $app->setBaseRoute('/api');

    // Rotas públicas
    $app->addRoute('GET', '/users', [ControllersUserController::class, 'getUser'], [[AuthMiddleware::class]]);
    // $app->addRoute('POST', '/users', [UserController::class, 'store']);

    // Rota com middleware
    // $app->addRoute('GET', '/me', [UserController::class, 'profile'], [AuthMiddleware::class]);
};