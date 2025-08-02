<?php

namespace SalesAppApi\Bootstrap;

use Dotenv\Dotenv;
use SalesAppApi\Shared\DIContainer;
use SalesAppApi\Shared\Exceptions\EnvironmentAwareException;
use SalesAppApi\Shared\Response;
use SalesAppApi\Shared\Router;
use Throwable;

class App{
    private $router;
    private $DIContainer;

    public function __construct()
    {
        $this->router = new Router;
        $this->DIContainer = new DIContainer;

        $envPath = __DIR__ . "/../../";

        if (file_exists($envPath . ".env")) {
            $dotenv = Dotenv::createImmutable($envPath);
            $dotenv->load();
        }
    }

    public function addRoute($method, $uri, $controller, $middlewares = null) : void
    {
        $this->router->addRoute($method, $uri, $controller, $middlewares);
    }

    public function setBaseRoute($baseUri): void
    {
        $this->router->setBaseRoute($baseUri);
    }
    
    public function addResolveDefinition($classOrigin,$classDestin): void
    {
        $this->DIContainer->addResolveDefinition($classOrigin,$classDestin);
    }
    
    public function run()
    {
        if($_ENV['APP_ENV'] == 'local'){
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }

        try{
            $this->router->dispatch($this->DIContainer->getContainer());
        }catch(Throwable $th){
            $ex = new EnvironmentAwareException($th->getMessage(), $th->getCode());
            return Response::json(['message' => $ex->getMessage()], 500);
        }
    }
}
