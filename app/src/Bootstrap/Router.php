<?php

namespace App\Bootstrap;

class Router
{
    protected $routes = [];
    protected $middlewares = [];

    // Adiciona uma rota ao array de rotas
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
    // Encontra a rota correspondente para a solicitação atual
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

    function callMiddlewares()
    {
        if (empty($this->middlewares)) {
            return true;
        }

        $status = false;
        // Chama todos os middlewares
        foreach ($this->middlewares as $middleware) {
            $status = call_user_func_array($middleware, []);
        }
        return $status;
    }
    function response($data, $status = 200)
    {
        http_response_code($status);
        echo json_encode($data);
    }

    function getHeaders(): array
    {
        $headers = [];

        foreach ($_SERVER as $nome => $valor) {
            if (substr($nome, 0, 5) === 'HTTP_') {
                // Converte o nome do header para o formato correto
                $nomeHeader = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($nome, 5)))));
                $headers[$nomeHeader] = $valor;
            }
        }

        return $headers;
    }

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
