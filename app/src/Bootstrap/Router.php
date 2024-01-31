<?php

namespace App\Bootstrap;

class Router
{
    protected $routes = [];
    protected $middlewares = [];

    // Add a route to the router
    public function addRoute($method, $route, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
        ];
    }
    function addMiddleware($middleware): void
    {
        $this->middlewares[] = $middleware;
    }
    // Find a route and call the controller
    function matchRoute($method, $uri)
    {
        foreach ($this->routes as $route) {
            if ($method == $route['method'] && $route['route'] === $uri) {

                if ($this->callMiddlewares()) {
                    return call_user_func_array($route['controller'], []);
                }
            }
        }

        http_response_code(404);
        return;
    }

    // Call all middlewares in order
    function callMiddlewares()
    {
        if (empty($this->middlewares)) {
            return true;
        }

        $status = false;
        foreach ($this->middlewares as $middleware) {
            $status = call_user_func_array($middleware, []);
        }
        return $status;
    }
    // Return a response with JSON
    function response($data, $status = 200)
    {
        http_response_code($status);
        echo json_encode($data);
    }

    // Get the bearer token from the request
    function getBearerToken()
    {
        $headers = apache_request_headers();

        foreach ($headers as $nome => $valor) {
            if (strtolower($nome) === 'authorization' && strpos($valor, 'Bearer ') === 0) {
                return substr($valor, 7);
            }
        }

        return null;
    }
}
