<?php

namespace Core;

use Core\Exceptions\MethodNotAllowed;
use Core\Exceptions\MethodNotFound;
use Core\Exceptions\NotFoundException;
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
                    error_log("Registering route: Method - $method, URI - $uri, Action - " . json_encode($action));
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
        // Debugging output for request handling
        error_log("Handling request: Method - " . $request->getMethod() . ", URI - " . $request->getPathInfo());

        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $this->handleNotFound();
                break;

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $this->handleMethodNotAllowed($allowedMethods);
                break;

            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                // Debugging output for route found
                error_log("Route found: Handler - " . json_encode($handler) . ", Vars - " . json_encode($vars));

                foreach ($this->middlewares as $middleware) {
                    $middleware->handle();
                }

                $this->handleFound($handler, $request, $vars);
                break;
        }
    }

    protected function handleNotFound()
    {
        $notFound = new NotFoundException();
        $notFound->__invoke();
    }

    protected function handleMethodNotAllowed($allowedMethods)
    {
        $methodNotAllowed = new MethodNotAllowed();
        $methodNotAllowed->__invoke($allowedMethods);
    }

    protected function handleFound($handler, $request, $vars)
    {
        [$class, $method] = $handler;

        // Debugging output for handler
        error_log("Handler class: $class");
        error_log("Handler method: $method");

        if (!class_exists($class)) {
            throw new Exception("Controller class $class not found");
        }

        $controller = new $class;

        // More debugging output
        error_log("Controller instantiated: " . get_class($controller));

        if (!method_exists($controller, $method)) {
            $this->handleMethodNotFound($method, $class);
            return;
        }

        // Even more debugging output
        error_log("Method found in controller: $method");

        $controller->{$method}($request, ...$vars);
    }

    protected function handleMethodNotFound($method, $class)
    {
        $methodNotFound = new MethodNotFound();
        $methodNotFound->__invoke($method, $class);
    }
}
