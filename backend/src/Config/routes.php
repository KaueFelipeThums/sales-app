<?php
namespace SalesAppApi\Config;

use SalesAppApi\Bootstrap\App;
use SalesAppApi\Interface\Http\Controllers\AuthController;
use SalesAppApi\Interface\Http\Controllers\CustomerController;
use SalesAppApi\Interface\Http\Controllers\ProductController;
use SalesAppApi\Interface\Http\Controllers\UserController;
use SalesAppApi\Interface\Http\Middlewares\AuthMiddleware;

return function (App $app) {
    $app->setBaseRoute('/api');

    /**
     * Rotas de autenticação
     */
    $app->addRoute('POST', '/auth/login', [AuthController::class, 'login']);
    $app->addRoute('POST', '/auth/refresh', [AuthController::class, 'refresh'], [[AuthMiddleware::class]]);
    
    /**
     * Usuário
     */
    $app->addRoute('GET', '/user/get-all', [UserController::class, 'getAllUsers'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/user/get/{id}', [UserController::class, 'getUserById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/user/create', [UserController::class, 'createUser'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/user/update', [UserController::class, 'updateUser'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/user/delete', [UserController::class, 'deleteUser'], [[AuthMiddleware::class]]);

    /**
     * Cliente
     */
    $app->addRoute('GET', '/customer/get-all', [CustomerController::class, 'getAllCustomers'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/customer/get-all-active', [CustomerController::class, 'getAllActiverCustomers'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/customer/get/{id}', [CustomerController::class, 'getCustomerById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/customer/create', [CustomerController::class, 'createCustomer'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/customer/update', [CustomerController::class, 'updateCustomer'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/customer/delete', [CustomerController::class, 'deleteCustomer'], [[AuthMiddleware::class]]);

    /**
     * Produto
     */
    $app->addRoute('GET', '/product/get-all', [ProductController::class, 'getAllProducts'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/product/get-all-active', [ProductController::class, 'getAllActiverProducts'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/product/get/{id}', [ProductController::class, 'getProductById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/product/create', [ProductController::class, 'createProduct'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/product/update', [ProductController::class, 'updateProduct'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/product/delete', [ProductController::class, 'deleteProduct'], [[AuthMiddleware::class]]);

    // Rotas públicas
    // $app->addRoute('POST', '/users', [UserController::class, 'store']);

    // Rota com middleware
    // $app->addRoute('GET', '/me', [UserController::class, 'profile'], [AuthMiddleware::class]);
};