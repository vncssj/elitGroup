<?php

use App\Controllers\TiposAtendimentosController;

//Simulate a token with Bearer Token Authorization
$token = base64_encode('elitgroup:123456');

$router->addMiddleware(function () use ($router, $token) {
    // Verify if the token is valid
    if ($router->getBearerToken() && $router->getBearerToken() === $token) {
        return true;
    }

    http_response_code(401);
    return false;
});

// Rota raiz
$router->addRoute('GET', '/', function () {
    echo "Wellcome, access the route /tiposAtendimentos to get all types of attendments";
});

// Route to get all types
$router->addRoute('GET', '/tiposAtendimentos', function () use ($router) {

    $result = (new TiposAtendimentosController($router))
        ->getAll();
});
