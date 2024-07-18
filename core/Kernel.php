<?php

namespace Core;

use Core\Exceptions\MethodNotAllowed;
use Core\Exceptions\MethodNotFound;
use Core\Exceptions\NotFoundExeception;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Exception;

class Kernel
{
    protected $dispatcher;
    protected $middlewares = [];

    public function __construct()
    {
        $this->dispatcher = simpleDispatcher(function (RouteCollector $r) {
            require BASE_PATH . '/routes/web.php';
            foreach (Route::getRoutes() as $method => $routes) {
                foreach ($routes as $uri => $action) {
                    $r->addRoute($method, $uri, $action);
                }
            }
        });
    }

    public function addMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function handleRequest(Request $request)
    {
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $notFOund = new NotFoundExeception();
                $notFOund->__invoke();
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $notFOund = new MethodNotAllowed();
                $notFOund->__invoke($allowedMethods);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                foreach ($this->middlewares as $middleware) {
                    $middleware->handle();
                }

                [$class, $method] = $handler;
                if (class_exists($class)) {
                    $controller = new $class;
                    if (method_exists($controller, $method)) {
                        $controller->{$method}($request, ...$vars);
                    } else {
                        $notFOund = new MethodNotFound();
                        $notFOund->__invoke($method, $class);
                    }
                } else {
                    throw new Exception("Controller class $class not found");
                }
                break;
        }
    }
}
