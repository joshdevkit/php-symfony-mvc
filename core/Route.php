<?php

namespace Core;

class Route
{
    private static $routes = [];

    public static function get($uri, $action)
    {
        self::$routes['GET'][$uri] = $action;
    }

    public static function post($uri, $action)
    {
        self::$routes['POST'][$uri] = $action;
    }

    public static function put($uri, $action)
    {
        self::$routes['PUT'][$uri] = $action;
    }

    public static function delete($uri, $action)
    {
        self::$routes['DELETE'][$uri] = $action;
    }

    public static function controller($controller)
    {
        return new class($controller)
        {
            private $controller;

            public function __construct($controller)
            {
                $this->controller = $controller;
            }

            public function group($callback)
            {
                call_user_func($callback);
                foreach (Route::getRoutes() as $method => $routes) {
                    foreach ($routes as $uri => $action) {
                        Route::getRoutes()[$method][$uri] = [$this->controller, $action];
                    }
                }
            }
        };
    }

    public static function getRoutes()
    {
        return self::$routes;
    }
}
