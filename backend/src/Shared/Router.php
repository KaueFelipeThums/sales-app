<?php
namespace SalesAppApi\Shared;

use DI\Container;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

class Router
{
    private $httpMethod;
    private $uri;
    private $routes = [];
    private $request;
    private $baseRoute = '';

    public function __construct()
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $_SERVER['REQUEST_URI'] ?? "/";
        $this->request = new Request;       
    }

    public function setBaseRoute(string $baseRoute): void
    {
        $this->baseRoute = $baseRoute;
    }

    public function addRoute($method, $uri, $controller, $middleware)
    {
        $this->routes[] = [
            'method'     => $method,
            'uri'        => $this->baseRoute . $uri,
            'controller' => $controller,
            'middleware' => $middleware
        ];
    }

    public function dispatch(Container $DIContainer)
    {
        $dispatcher = simpleDispatcher(function(RouteCollector $r) {
            foreach($this->routes as $route){
                $r->addRoute(
                    $route['method'],
                    $route['uri'],
                    [
                        $route['controller'][0], 
                        $route['controller'][1], 
                        $route['middleware']
                    ]
                );
            }
        });

        // Fetch method and URI from somewhere
        $httpMethod = $this->httpMethod;
        $uri = $this->uri;

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                http_response_code(404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                http_response_code(405);
                break;
            case Dispatcher::FOUND:
        
                $handler = $routeInfo[1];
                $vars = $routeInfo[2]; 

                $middlewares = (array)$handler[2];

                foreach($middlewares as $middlewareDef){
                  if (is_array($middlewareDef)) {
                        $middleware = $DIContainer->get($middlewareDef[0]);
                        $middleware->execute($this->request, $vars, $middlewareDef[1] ?? []);
                    } else {
                        $middleware = $DIContainer->get($middlewareDef);
                        $middleware->execute($this->request, $vars, []);
                    }
                }

                $class = $DIContainer->get($handler[0]);
                $class->{$handler[1]}($this->request, $vars);
                break;
        }
    }
}