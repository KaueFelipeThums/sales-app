<?php
namespace SalesAppApi\Config;

use SalesAppApi\Bootstrap\App;
use SalesAppApi\Interface\Http\Controllers\AuthController;
use SalesAppApi\Interface\Http\Controllers\UserController;
use SalesAppApi\Interface\Http\Middlewares\AuthMiddleware;

return function (App $app) {
    $app->setBaseRoute('/api');

    /**
     * Rotas de autenticação
     */
    $app->addRoute('POST', '/auth/login', [AuthController::class, 'login']);
    $app->addRoute('POST', '/auth/refresh', [AuthController::class, 'refresh'], [[AuthMiddleware::class]]);
    
    // Rotas públicas
    $app->addRoute('GET', '/users', [UserController::class, 'getUser'], [[AuthMiddleware::class]]);
    // $app->addRoute('POST', '/users', [UserController::class, 'store']);

    // Rota com middleware
    // $app->addRoute('GET', '/me', [UserController::class, 'profile'], [AuthMiddleware::class]);
};