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
    $app->addRoute('POST', '/v1/auth/login', [AuthController::class, 'login']);
    $app->addRoute('POST', '/v1/auth/refresh', [AuthController::class, 'refresh'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/auth/update-password', [AuthController::class, 'updateUserPassword'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/v1/auth/user', [AuthController::class, 'getAuthUser'], [[AuthMiddleware::class]]);
    
    /**
     * Usuário
     */
    $app->addRoute('GET', '/v1/user/get-all', [UserController::class, 'getAllUsers'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/v1/user/get/{id}', [UserController::class, 'getUserById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/user/create', [UserController::class, 'createUser'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/user/update', [UserController::class, 'updateUser'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/user/delete', [UserController::class, 'deleteUser'], [[AuthMiddleware::class]]);

    /**
     * Cliente
     */
    $app->addRoute('GET', '/v1/customer/get-all', [CustomerController::class, 'getAllCustomers'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/v1/customer/get-all-active', [CustomerController::class, 'getAllActiveCustomers'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/v1/customer/get/{id}', [CustomerController::class, 'getCustomerById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/customer/create', [CustomerController::class, 'createCustomer'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/customer/update', [CustomerController::class, 'updateCustomer'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/customer/delete', [CustomerController::class, 'deleteCustomer'], [[AuthMiddleware::class]]);

    /**
     * Produto
     */
    $app->addRoute('GET', '/v1/product/get-all', [ProductController::class, 'getAllProducts'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/v1/product/get-all-active', [ProductController::class, 'getAllActiveProducts'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/v1/product/get/{id}', [ProductController::class, 'getProductById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/product/create', [ProductController::class, 'createProduct'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/product/update', [ProductController::class, 'updateProduct'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/product/delete', [ProductController::class, 'deleteProduct'], [[AuthMiddleware::class]]);

    /**
     * Métodos de Pagamento
     */
    $app->addRoute('GET', '/v1/payment-method/get-all', [PaymentMethodController::class, 'getAllPaymentMethods'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/v1/payment-method/get-all-active', [PaymentMethodController::class, 'getAllActivePaymentMethods'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/v1/payment-method/get/{id}', [PaymentMethodController::class, 'getPaymentMethodById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/payment-method/create', [PaymentMethodController::class, 'createPaymentMethod'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/payment-method/update', [PaymentMethodController::class, 'updatePaymentMethod'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/payment-method/delete', [PaymentMethodController::class, 'deletePaymentMethod'], [[AuthMiddleware::class]]);

    /**
     * Venda
     */
    $app->addRoute('GET', '/v1/sale/get-all', [SaleController::class, 'getAllSales'], [[AuthMiddleware::class]]);
    $app->addRoute('GET', '/v1/sale/get/{id}', [SaleController::class, 'getSaleById'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/sale/create', [SaleController::class, 'createSale'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/sale/update', [SaleController::class, 'updateSale'], [[AuthMiddleware::class]]);
    $app->addRoute('POST', '/v1/sale/delete', [SaleController::class, 'deleteSale'], [[AuthMiddleware::class]]);

    /**
     * Endereço
     */
    $app->addRoute('GET', '/v1/address/get/{cep}', [AddressController::class, 'getAddressByCep'], [[AuthMiddleware::class]]);
};