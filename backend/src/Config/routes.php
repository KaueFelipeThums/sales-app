<?php
namespace SalesAppApi\Config;

use SalesAppApi\Bootstrap\App;
use SalesAppApi\Interface\Http\Controllers\AddressController;
use SalesAppApi\Interface\Http\Controllers\AuthController;
use SalesAppApi\Interface\Http\Controllers\CustomerController;
use SalesAppApi\Interface\Http\Controllers\PaymentMethodController;
use SalesAppApi\Interface\Http\Controllers\ProductController;
use SalesAppApi\Interface\Http\Controllers\SaleController;
use SalesAppApi\Interface\Http\Controllers\UserController;
use SalesAppApi\Interface\Http\Middlewares\AuthMiddleware;

return function (App $app) {
    $app->setBaseRoute('/api');

    /**
     * Rotas de autenticação
     */
    $app->addRoute('POST', '/auth/login', [AuthController::class, 'login']);
    $app->addRoute('POST', '/auth/refresh', [AuthController::class, 'refresh'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/auth/update-password', [AuthController::class, 'updateUserPassword'], [[AuthMiddleware::class]]);
    
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
    $app->addRoute('GET', '/customer/get-all-active', [CustomerController::class, 'getAllActiveCustomers'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/customer/get/{id}', [CustomerController::class, 'getCustomerById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/customer/create', [CustomerController::class, 'createCustomer'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/customer/update', [CustomerController::class, 'updateCustomer'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/customer/delete', [CustomerController::class, 'deleteCustomer'], [[AuthMiddleware::class]]);

    /**
     * Produto
     */
    $app->addRoute('GET', '/product/get-all', [ProductController::class, 'getAllProducts'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/product/get-all-active', [ProductController::class, 'getAllActiveProducts'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/product/get/{id}', [ProductController::class, 'getProductById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/product/create', [ProductController::class, 'createProduct'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/product/update', [ProductController::class, 'updateProduct'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/product/delete', [ProductController::class, 'deleteProduct'], [[AuthMiddleware::class]]);

    /**
     * Métodos de Pagamento
     */
    $app->addRoute('GET', '/payment-method/get-all', [PaymentMethodController::class, 'getAllPaymentMethods'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/payment-method/get-all-active', [PaymentMethodController::class, 'getAllActivePaymentMethods'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/payment-method/get/{id}', [PaymentMethodController::class, 'getPaymentMethodById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/payment-method/create', [PaymentMethodController::class, 'createPaymentMethod'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/payment-method/update', [PaymentMethodController::class, 'updatePaymentMethod'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/payment-method/delete', [PaymentMethodController::class, 'deletePaymentMethod'], [[AuthMiddleware::class]]);

    /**
     * Venda
     */
    $app->addRoute('GET', '/sale/get-all', [SaleController::class, 'getAllSales'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/sale/get/{id}', [SaleController::class, 'getSaleById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/sale/create', [SaleController::class, 'createSale'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/sale/update', [SaleController::class, 'updateSale'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/sale/delete', [SaleController::class, 'deleteSale'], [[AuthMiddleware::class]]);

    /**
     * Endereço
     */
    $app->addRoute('GET', '/address/get/{cep}', [AddressController::class, 'getAddressByCep'], [[AuthMiddleware::class]]);
};