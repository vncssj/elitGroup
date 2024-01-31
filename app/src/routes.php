<?php

use App\Controllers\TiposAtendimentosController;

//Simula uma chave salva na aplicação para verificação de autenticação
$token = base64_encode('elitgroup:123456');

$router->addMiddleware(function () use ($router, $token) {
    // Verifica se a requisição tem o token de autenticação
    if ($router->getBearerToken() && $router->getBearerToken() === $token) {
        return true;
    }

    http_response_code(401);
    return false;
});

// Rota raiz
$router->addRoute('GET', '/', function () {
    echo "Bem vindo, acesse a rota /tiposAtendimentos para ver os tipos de atendimentos";
});

// Rota para obter todos os tipos de atendimentos
$router->addRoute('GET', '/tiposAtendimentos', function () use ($router) {

    $result = (new TiposAtendimentosController())
        ->getAll();
    $router->response($result);
});
