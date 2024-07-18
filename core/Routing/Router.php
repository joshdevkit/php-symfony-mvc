<?php

namespace Core\Routing;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

class Router
{
    protected $routes = [];

    public function get($uri, $handler)
    {
        $this->addRoute('GET', $uri, $handler);
    }

    public function post($uri, $handler)
    {
        $this->addRoute('POST', $uri, $handler);
    }

    public function put($uri, $handler)
    {
        $this->addRoute('PUT', $uri, $handler);
    }

    public function delete($uri, $handler)
    {
        $this->addRoute('DELETE', $uri, $handler);
    }

    public function group(\Closure $callback)
    {
        $callback($this);
    }

    protected function addRoute($method, $uri, $handler)
    {
        $this->routes[$method][$uri] = $handler;
    }

    public function dispatch($method, $uri)
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routes as $method => $routes) {
                foreach ($routes as $uri => $handler) {
                    $r->addRoute($method, $uri, $handler);
                }
            }
        });

        $routeInfo = $dispatcher->dispatch($method, $uri);
        return $routeInfo;
    }
}
