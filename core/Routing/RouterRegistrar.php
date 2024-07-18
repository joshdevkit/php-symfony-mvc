<?php

namespace Core;

use Core\Routing\Router;

class RouteRegistrar
{
    protected $router;
    protected $attributes = [];

    protected $passthru = [
        'get', 'post', 'put', 'patch', 'delete', 'options', 'any',
    ];

    protected $allowedAttributes = [
        'as', 'controller', 'domain', 'middleware', 'name', 'namespace', 'prefix', 'where', 'withoutMiddleware',
    ];

    protected $aliases = [
        'name' => 'as',
        'withoutMiddleware' => 'excluded_middleware',
    ];

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function __call($method, $parameters)
    {
        if (in_array($method, $this->passthru)) {
            return $this->registerRoute($method, ...$parameters);
        }

        if (in_array($method, $this->allowedAttributes)) {
            $this->attributes[$method] = $parameters[0];
            return $this;
        }

        if (array_key_exists($method, $this->aliases)) {
            $this->attributes[$this->aliases[$method]] = $parameters[0];
            return $this;
        }

        throw new \BadMethodCallException("Method {$method} does not exist.");
    }

    protected function registerRoute($method, $uri, $action)
    {
        if (isset($this->attributes['controller'])) {
            $action = $this->attributes['controller'] . '@' . $action;
        }

        $route = $this->router->{$method}($uri, $action);

        foreach ($this->attributes as $key => $value) {
            if (!in_array($key, ['controller'])) {
                $route->{$key}($value);
            }
        }

        return $route;
    }

    public function group(\Closure $callback)
    {
        $callback($this);
    }
}
