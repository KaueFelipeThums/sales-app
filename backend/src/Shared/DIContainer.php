<?php

namespace SalesAppApi\Shared;

use DI\Container;

class DIContainer{
    
    private $dependencyResolve = [];
    private static ?Container $container = null;

    public function addResolveDefinition($classOrigin,$classDestin){
        $this->dependencyResolve[$classOrigin] = \DI\get($classDestin);
    }

    public function getContainer(): Container
    {
        if(empty(self::$container)){
            self::$container = new Container($this->dependencyResolve);
        }

        return self::$container;
    }

    public function get($class){
        $container = $this->getContainer();
        return $container->get($class);
    }
}
